<?php

namespace App\Console\Commands;

use App\Models\InventoryDetail;
use App\Models\InventoryMaster;
use App\Models\Master\Company;
use App\Models\Master\Domain;
use App\Models\Master\ItemInventoryMaster;
use App\Models\Master\Qxwsa;
use App\Models\Master\Site;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LoadInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load_inventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load inventory';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    private function httpHeader($req)
    {
        return array(
            'Content-type: text/xml;charset="utf-8"',
            'Accept: text/xml',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            'SOAPAction: ""',        // jika tidak pakai SOAPAction, isinya harus ada tanda petik 2 --> ""
            'Content-length: ' . strlen(preg_replace("/\s+/", " ", $req))
        );
    }

    public function handle()
    {
        DB::beginTransaction();

        try {
            $wsa = Qxwsa::first();

            $qxUrl = $wsa->wsas_url;
            $qxReceiver = '';
            $qxSuppRes = 'false';
            $qxScopeTrx = '';
            $qdocName = '';
            $qdocVersion = '';
            $dsName = '';
            $timeout = 0;
            $domain = $wsa->wsas_domain;

            $qdocRequest =
                '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">' .
                    '<Body>' .
                        '<supp_inv_mstr xmlns="' . $wsa->wsas_path . '">' .
                        '<inpdomain>' . $domain . '</inpdomain>' .
                        '</supp_inv_mstr>' .
                    '</Body>' .
                '</Envelope>';

            $curlOptions = array(
                CURLOPT_URL => $qxUrl,
                CURLOPT_CONNECTTIMEOUT => $timeout,        // in seconds, 0 = unlimited / wait indefinitely.
                CURLOPT_TIMEOUT => $timeout + 120, // The maximum number of seconds to allow cURL functions to execute. must be greater than CURLOPT_CONNECTTIMEOUT
                CURLOPT_HTTPHEADER => $this->httpHeader($qdocRequest),
                CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $qdocRequest),
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            );

            $getInfo = '';
            $httpCode = 0;
            $curlErrno = 0;
            $curlError = '';
            $qdocResponse = '';

            $curl = curl_init();
            if ($curl) {
                curl_setopt_array($curl, $curlOptions);
                $qdocResponse = curl_exec($curl);           // sending qdocRequest here, the result is qdocResponse.
                $curlErrno    = curl_errno($curl);
                $curlError    = curl_error($curl);
                $first        = true;

                foreach (curl_getinfo($curl) as $key => $value) {
                    if (gettype($value) != 'array') {
                        if (!$first) $getInfo .= ", ";
                        $getInfo = $getInfo . $key . '=>' . $value;
                        $first = false;
                        if ($key == 'http_code') $httpCode = $value;
                    }
                }
                curl_close($curl);
            }

            $xmlResp = simplexml_load_string($qdocResponse);

            $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);

            $dataloop    = $xmlResp->xpath('//ns1:tempRow');
            $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

            $company = Company::firstOrFail();

            /**
             * Load Inventory Master
             */
            if ($qdocResult == 'true') {
                InventoryDetail::query()->delete();
                InventoryMaster::query()->delete();
                foreach ($dataloop as $data) {
                    $site = Site::where('site_code', $data->t_site)->first();
                    if ($site) {
                        $item = ItemInventoryMaster::where('iim_item_part', $data->t_part)->where('iim_item_isRfq', 0)->first();
                        $domain_id = Domain::where('domain_code', $data->t_domain)->value('id');
                        $hasSent = 0; // 'N'

                        if ($item) {
                            $proyeksi = $data->t_sfty_stk + ($data->t_sfty_stk * $item->iim_item_safety_stk) / 100;
                            if ($data->t_qty_oh > $data->t_sfty_stk && $data->t_qty_oh < $proyeksi) {
                                $hasSent = 1; // 'Y'
                                Mail::send(
                                    'email.emailexp',
                                    [
                                        'pesan' => 'There is an item that going to expired',
                                        'note1' => $data->t_part,
                                        'note2' => $data->t_qty_oh,
                                        'note3' => $data->t_site,
                                        'note4' => $data->t_sfty_stk,
                                        'note7' => 'Please check'
                                    ],
                                    function ($message) use ($item, $company) {
                                        $message->subject('Web Support IMI Notification');
                                        $message->from($company->company_email); // Email Admin Fix
                                        $message->to($item->iim_item_sfty_email);
                                    }
                                );
                            }

                            $inventory_master = InventoryMaster::firstOrNew(
                                [
                                    'inv_domain_id' => $domain_id,
                                    'inv_item_id' => $item->id,
                                    'inv_site_id' => $site->id
                                ],
                            );

                            $inventory_master->inv_domain_id = $domain_id;
                            $inventory_master->inv_item_id = $item->id;
                            $inventory_master->inv_site_id = $site->id;
                            $inventory_master->inv_safety_stock = $data->t_sfty_stk;
                            $inventory_master->inv_qty_oh = $data->t_qty_oh;
                            $inventory_master->inv_qty_ord = $data->t_qty_ord;
                            $inventory_master->inv_qty_req = $data->t_qty_req;
                            $inventory_master->inv_reach_sfty_stk = $data->t_stkles == 'N' ? 0 : 1;
                            $inventory_master->inv_has_sent_email = $hasSent;
                            $inventory_master->save();
                        }
                    }
                }
            } else {
                alert()->error('Error', 'WSA return false');
                return back();
            }

            /**
             * Load Inventory Detail
             */

            $qdocRequest =  
                '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">' .
                    '<Body>' .
                        '<supp_inv_det xmlns="' . $wsa->wsas_path . '">' .
                        '<inpdomain>' . $domain . '</inpdomain>' .
                        '</supp_inv_det>' .
                    '</Body>' .
                '</Envelope>';
            
            $curlOptions = array(
                CURLOPT_URL => $qxUrl,
                CURLOPT_CONNECTTIMEOUT => $timeout,        // in seconds, 0 = unlimited / wait indefinitely.
                CURLOPT_TIMEOUT => $timeout + 120, // The maximum number of seconds to allow cURL functions to execute. must be greater than CURLOPT_CONNECTTIMEOUT
                CURLOPT_HTTPHEADER => $this->httpHeader($qdocRequest),
                CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $qdocRequest),
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            );

            $getInfo = '';
            $httpCode = 0;
            $curlErrno = 0;
            $curlError = '';
            $qdocResponse = '';

            $curl = curl_init();
            if ($curl) {
                curl_setopt_array($curl, $curlOptions);
                $qdocResponse = curl_exec($curl);           // sending qdocRequest here, the result is qdocResponse.
                $curlErrno    = curl_errno($curl);
                $curlError    = curl_error($curl);
                $first        = true;

                foreach (curl_getinfo($curl) as $key => $value) {
                    if (gettype($value) != 'array') {
                        if (!$first) $getInfo .= ", ";
                        $getInfo = $getInfo . $key . '=>' . $value;
                        $first = false;
                        if ($key == 'http_code') $httpCode = $value;
                    }
                }
                curl_close($curl);
            }

            $xmlResp = simplexml_load_string($qdocResponse);

            $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);

            $dataloop    = $xmlResp->xpath('//ns1:tempRow');
            $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

            if ($qdocResult == 'true') {
                foreach ($dataloop as $data) {
                    $site = Site::where('site_code', $data->t_site)->first();
                    if ($site) {
                        $item = ItemInventoryMaster::where('iim_item_part', $data->t_part)->where('iim_item_isRfq', 0)->first();
                        if ($item) {
                            if ((string)$data->t_exp == '') {
                                $expire_date = NULL;
                            } else {
                                $expire_date = $data->t_exp;
                            }

                            if ((string)$data->t_ket == '') {
                                $inv_det_ed = 0;
                            } else {
                                $inv_det_ed = $data->t_ket;
                            }

                            if ((string)$data->t_hit == '') {
                                $inv_det_days = NULL;
                            } else {
                                $inv_det_days = $data->t_hit;
                            }
                            $domain = Domain::where('domain_code', $data->t_domain)->value('id');
                            $inv_mstr_id = InventoryMaster::where('inv_item_id', $item->id)
                                ->where('inv_domain_id', $domain)->value('id');
                            $inventory_detail = InventoryDetail::firstOrNew(
                                [
                                    'inv_mstr_id' => $inv_mstr_id,
                                    'inv_det_loc' => $data->t_loc,
                                    'inv_det_lot' => $data->t_lot,
                                ]
                            );
                            $inventory_detail->inv_mstr_id = $inv_mstr_id;
                            $inventory_detail->inv_det_loc = $data->t_loc;
                            $inventory_detail->inv_det_lot = $data->t_lot;
                            $inventory_detail->inv_det_ref = $data->t_ref;
                            $inventory_detail->inv_det_qty_oh = $data->t_qty_oh;
                            $inventory_detail->inv_det_qty_all = $data->t_qty_all;
                            $inventory_detail->inv_det_expire_date = $expire_date;
                            $inventory_detail->inv_det_ed = $inv_det_ed;
                            $inventory_detail->inv_det_days = $inv_det_days;
                            $inventory_detail->inv_det_amount = $data->t_amt;
                            $inventory_detail->save();
                        }
                    }

                    $item_day1 = ItemInventoryMaster::where('iim_item_part', $data->t_part)
                        ->where('iim_item_day1', $data->t_hit)
                        ->where('iim_item_day_email1', '!=', '')
                        ->get();

                    if ($item_day1->count() > 0) {
                        foreach ($item_day1 as $emailDay1) {
                            Mail::send(
                                'email.emailexp',
                                [
                                    'pesan' => 'There is an item that going to expired',
                                    'note1' => $data->t_part,
                                    'note2' => $data->t_qty_oh,
                                    'note3' => $data->t_loc,
                                    'note4' => $data->t_lot,
                                    'note5' => $data->t_ref,
                                    'note6' => $data->t_hit . ' Days',
                                    'note7' => 'Please check'
                                ], function ($message) use ($emailDay1, $company) {
                                    $message->subject('Web Support IMI Notification');
                                    $message->from($company->com_email); // Email Admin Fix
                                    $message->to($emailDay1->iim_item_day_email1);
                                }
                            );
                        }
                    }

                    $item_day2 = ItemInventoryMaster::where('iim_item_part', $data->t_part)
                        ->where('iim_item_day2', $data->t_hit)
                        ->where('iim_item_day_email2', '!=', '')
                        ->get();

                    if ($item_day2->count() > 0) {
                        foreach ($item_day2 as $emailDay2) {
                            Mail::send(
                                'email.emailexp',
                                [
                                    'pesan' => 'There is an item that going to expired',
                                    'note1' => $data->t_part,
                                    'note2' => $data->t_qty_oh,
                                    'note3' => $data->t_loc,
                                    'note4' => $data->t_lot,
                                    'note5' => $data->t_ref,
                                    'note6' => $data->t_hit . ' Days',
                                    'note7' => 'Please check'
                                ],
                                function ($message) use ($emailDay2, $company) {
                                    $message->subject('Web Support IMI Notification');
                                    $message->from($company->com_email); // Email Admin Fix
                                    $message->to($emailDay2->iim_item_day_email1);
                                }
                            );
                        }
                    }

                    $item_day3 = ItemInventoryMaster::where('iim_item_part', $data->t_part)
                        ->where('iim_item_day3', $data->t_hit)
                        ->where('iim_item_day_email3', '!=', '')
                        ->get();

                    if ($item_day3->count() > 0) {
                        foreach ($item_day3 as $emailDay3) {
                            Mail::send(
                                'email.emailexp',
                                [
                                    'pesan' => 'There is an item that going to expired',
                                    'note1' => $data->t_part,
                                    'note2' => $data->t_qty_oh,
                                    'note3' => $data->t_loc,
                                    'note4' => $data->t_lot,
                                    'note5' => $data->t_ref,
                                    'note6' => $data->t_hit . ' Days',
                                    'note7' => 'Please check'
                                ],
                                function ($message) use ($emailDay3, $company) {
                                    $message->subject('Web Support IMI Notification');
                                    $message->from($company->com_email); // Email Admin Fix
                                    $message->to($emailDay3->iim_item_day_email1);
                                }
                            );
                        }
                    }
                }
            } else {
                alert()->error('Error', 'WSA return false');
                return back();
            }
            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            alert()->error('Error', 'Failed to load WSA');
            return back();
        }
    }
}
