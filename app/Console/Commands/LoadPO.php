<?php

namespace App\Console\Commands;

use App\Jobs\EmailPOApproval;
use App\Models\Master\Company;
use App\Models\Master\Domain;
use App\Models\Master\ItemInventoryMaster;
use App\Models\Master\PoApprover;
use App\Models\Master\Qxwsa;
use App\Models\Master\Site;
use App\Models\Master\Supplier;
use App\Models\Master\User;
use App\Models\PODetail;
use App\Models\POHistory;
use App\Models\POMaster;
use App\Models\POTransAppr;
use App\Models\POTransApprHist;
use App\Models\SuratJalan;
use App\Notifications\eventNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoadPO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:po';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load Purchase Order Daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            Company::first()->update([
                'company_last_sync' => now()
            ]);

            $wsa = Qxwsa::first();
            $qxUrl          = $wsa->wsas_url;
            $qxReceiver     = '';
            $qxSuppRes      = 'false';
            $qxScopeTrx     = '';
            $qdocName       = '';
            $qdocVersion    = '';
            $dsName         = '';
            $timeout        = 0;

            $domain         = $wsa->wsas_domain;


            /**
             * Wsa PO Master
             */
            $qdocRequest =  '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                            <Body>
                            <supp_po_mstr xmlns="' . $wsa->wsas_path . '">
                            <inpdomain>' . $domain . '</inpdomain>
                            </supp_po_mstr>
                            </Body>
                            </Envelope>';
                            
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

            $dataloop   = $xmlResp->xpath('//ns1:tempRow');
            $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

            if ($qdocResult == 'true') {
                foreach ($dataloop as $data) {
                    $po_master = POMaster::where('pom_nbr', $data->t_nbr)->first(); //Cek ada PO atau tidak di web.
                    $domain_id = Domain::where('domain_code', $data->t_domain)->value('id');
                    $supplier = Supplier::where('supp_code', $data->t_vend)->first();
                    // Cek PO di web ada atau tidak
                    if ($po_master) {
                        //buat ngecek kalau ada PO yang di delete di QAD pas load ulang
                        if ($data->status == 'delete') {
                            $po_details = PODetail::where('pod_mstr_id', $po_master->id)
                                ->with(['getPOMaster', 'getItemMaster', 'getSite'])
                                ->get();

                            foreach ($po_details as $detail) {
                                $po_hist = new POHistory();
                                $po_hist->poh_domain_id = $domain_id;
                                $po_hist->poh_mstr_id = $po_master->id;
                                $po_hist->poh_item_id = $detail->getItemMaster->id;
                                $po_hist->poh_line = $detail->pod_det_line;
                                $po_hist->poh_qty_ord = $detail->pod_qty_ord;
                                $po_hist->poh_qty_rcvd = $detail->pod_det_qty_rcvd;
                                $po_hist->poh_qty_open = $detail->pod_det_qty_open;
                                $po_hist->poh_qty_ship = $detail->pod_det_qty_ship;
                                $po_hist->poh_qty_tole = $detail->pod_det_qty_tole;
                                $po_hist->poh_qty_prom = $detail->pod_det_qty_prom;
                                $po_hist->poh_price = $detail->pod_det_price;
                                $po_hist->poh_loc = $detail->pod_det_loc;
                                $po_hist->poh_lot = $detail->pod_det_lot;
                                $po_hist->poh_due_date = $detail->pod_det_due_date;
                                $po_hist->poh_status = 'Closed';
                                $po_hist->poh_site_id = $detail->pod_site_id;
                                $po_hist->poh_supplier_id = $detail->pod_supplier_id;
                                $po_hist->poh_total = $detail->pod_det_total;
                                $po_hist->save();
                            }

                            PODetail::where('pod_mstr_id', $po_master->id)->delete();
                            POMaster::where('pom_nbr', $data->t_nbr)->delete();
                            SuratJalan::where('sj_po_nbr', $data->t_nbr)->update([
                                'sj_status' => 'Closed'
                            ]);
                        } else if ($po_master->pom_total != str_replace(',', '.', $data->t_hitung)) {
                            // Update kalau ada perubahan harga
                            $po_mstr = POMaster::where('pom_nbr', $data->t_nbr)->first();
                            $po_mstr->pom_total = str_replace(',', '.', $data->t_hitung);
                            $po_mstr->pom_supplier_id = $supplier->id;
                            $po_mstr->pom_ship = $data->t_ship;
                            $po_mstr->pom_curr = $data->t_curr;
                            $po_mstr->pom_due_date = $data->t_lvt_due;
                            $po_mstr->pom_rev = $po_master->pom_rev + 1;
                            $po_mstr->save();

                            // Cek kalau supplier butuh approve / re approve
                            if ($supplier->supp_po_appr = '1' && $supplier->supp_reapprove == '1') {
                                $po_appr_trans = POTransAppr::where('po_mstr_id', $po_master->id)
                                    ->where('po_appr_status', '!=', 'UnConfirm')
                                    ->get();

                                foreach ($po_appr_trans as $trans) {
                                    $po_appr_trans_hist = new POTransApprHist();
                                    $po_appr_trans_hist->po_mstr_id = $po_master->id;
                                    $po_appr_trans_hist->po_user_approver = $trans->getUserApprover->id;
                                    $po_appr_trans_hist->po_alt_user_approver = $trans->getAltUserApprover->id;
                                    $po_appr_trans_hist->po_appr_order = $trans->po_appr_order;
                                    $po_appr_trans_hist->po_appr_status = $trans->po_appr_status;
                                    $po_appr_trans_hist->save();
                                }
                                POTransAppr::where('po_mstr_id', $po_master->id)->delete();

                                if ($data->t_vend != '') {
                                    $approver = PoApprover::whereHas('getSuppInfo', function ($query) use ($data) {
                                        $query->where('supp_code', $data->t_vend);
                                    })->whereRaw(
                                        '' . str_replace(',', '.', $data->t_hitung) . ' BETWEEN po_app_min_amt and po_app_max_amt'
                                    );
                                } else {
                                    $approver = PoApprover::whereHas('getSuppInfo', function ($query) use ($data) {
                                        $query->where('supp_code', '=', 'GENERAL');
                                    })->whereRaw(
                                        '' . str_replace(',', '.', $data->t_hitung) . ' BETWEEN po_app_min_amt and po_app_max_amt'
                                    );
                                }

                                $approver = $approver->with('getSuppInfo', 'getUserApprover', 'getAltUserApprover')
                                    ->orderBy('po_app_min_amt', 'desc')->get();

                                // Kalau ada approver
                                if ($approver->count() > 0) {
                                    POMaster::where('pom_nbr', $data->t_nbr)->update([
                                        'pom_need_appr' => 1,
                                        'pom_status' => 'UnConfirm'
                                    ]);

                                    foreach ($approver as $index => $appr) {
                                        $po_appr_trans = new POTransAppr();
                                        $po_appr_trans->po_mstr_id = $po_master->id;
                                        $po_appr_trans->po_user_approver = $appr->getUserApprover->id;
                                        $po_appr_trans->po_alt_user_approver = $appr->getAltUserApprover->id;
                                        $po_appr_trans->po_appr_order = $index + 1;
                                        $po_appr_trans->po_appr_status = 'UnConfirm';
                                        $po_appr_trans->save();
                                    }

                                    $emails = [
                                        $approver[0]->getUserApprover->email,
                                        $approver[0]->getAltUserApprover->email
                                    ];

                                    $company = Company::first();

                                    $pesan = 'There is an update on an old Purchase Order :';
                                    $po_nbr = (string)$data->t_nbr;
                                    $po_ord_date = (string)$data->t_lvt_ord;
                                    $po_due_date = (string)$data->t_lvt_due;
                                    $po_total = number_format((int)$data->t_hitung, 2);
                                    $com_name = $company->company_name;
                                    $com_email = $company->company_email;

                                    EmailPOApproval::dispatch(
                                        $pesan,
                                        $po_nbr,
                                        $po_ord_date,
                                        $po_due_date,
                                        $po_total,
                                        $emails,
                                        $com_name,
                                        $com_email
                                    );

                                    $user = User::where('id', '=', $approver[0]->po_app_approver)->first(); // user siapa yang terima notif (lewat id)
                                    $useralt = User::where('id', '=', $approver[0]->po_app_alt_approver)->first();
                                    $details = [
                                        'body' => 'There is an update on an old Purchase Order :',
                                        'url' => 'polist.index',
                                        'nbr' => (string)$data->t_nbr,
                                        'note' => 'Approval is needed, Please check'

                                    ]; // isi data yang dioper

                                    $user->notify(new \App\Notifications\eventNotification($details)); // syntax laravel
                                    $useralt->notify(new \App\Notifications\eventNotification($details));
                                }
                            }
                        }
                    } else {
                        if ($data->t_status != 'delete') {
                            $po_mstr = new POMaster();
                            $po_mstr->pom_domain_id = $domain_id;
                            $po_mstr->pom_nbr = $data->t_nbr;
                            $po_mstr->pom_ord_date = Carbon::createFromFormat('Y-m-d', $data->t_lvt_ord)->format('Y-m-d');
                            $po_mstr->pom_supplier_id = $supplier->id;
                            $po_mstr->pom_ship = $data->t_ship;
                            $po_mstr->pom_due_date = (string)$data->t_lvt_due;
                            $po_mstr->pom_curr = $data->t_curr;
                            $po_mstr->pom_status = 'UnConfirm';
                            $po_mstr->pom_last_conf = null;
                            $po_mstr->pom_total = str_replace(',', '.', $data->t_hitung);
                            $po_mstr->pom_ppn = str_replace(',', '.', $data->t_ppn);
                            $po_mstr->pom_total_conf = '0';
                            $po_mstr->save();
                            //Cek apakah supplier perlu approve
                            if ($supplier->supp_po_appr == '1') {
                                // Cek kalau suppliernya ga kosong, cari supplier yang sama dengan hasil wsa
                                if ($data->t_vend != '') {
                                    $approver = PoApprover::whereHas('getSuppInfo', function ($query) use ($data) {
                                        $query->where('supp_code', $data->t_vend);
                                    })->whereRaw(
                                        '' . str_replace(',', '.', $data->t_hitung) . ' BETWEEN po_app_min_amt and po_app_max_amt'
                                    );
                                } else {
                                    $approver = PoApprover::whereHas('getSuppInfo', function ($query) use ($data) {
                                        $query->where('supp_code', '=', 'GENERAL');
                                    })->whereRaw(
                                        '' . str_replace(',', '.', $data->t_hitung) . ' BETWEEN po_app_min_amt and po_app_max_amt'
                                    );
                                }

                                $approver = $approver->with('getSuppInfo', 'getUserApprover', 'getAltUserApprover')
                                    ->orderBy('po_app_min_amt', 'desc')->get();

                                // Kalau ada approver
                                if ($approver->count() > 0) {
                                    POMaster::where('pom_nbr', $data->t_nbr)->update([
                                        'pom_need_appr' => 1,
                                        'pom_status' => 'UnConfirm'
                                    ]);

                                    foreach ($approver as $index => $appr) {
                                        $po_mstr_id = POMaster::where('pom_nbr', $data->t_nbr)->value('id');
                                        $po_appr_trans = new POTransAppr();
                                        $po_appr_trans->po_mstr_id = $po_mstr_id;
                                        $po_appr_trans->po_user_approver = $appr->getUserApprover->id;
                                        $po_appr_trans->po_alt_user_approver = $appr->getAltUserApprover->id;
                                        $po_appr_trans->po_appr_order = $index + 1;
                                        $po_appr_trans->po_appr_status = 'UnConfirm';
                                        $po_appr_trans->save();
                                    }

                                    $emails = [
                                        $approver[0]->getUserApprover->email,
                                        $approver[0]->getAltUserApprover->email
                                    ];

                                    $company = Company::first();

                                    $pesan = 'There is a new Purchase Order :';
                                    $po_nbr = (string)$data->t_nbr;
                                    $po_ord_date = (string)$data->t_lvt_ord;
                                    $po_due_date = (string)$data->t_lvt_due;
                                    $po_total = number_format((int)$data->t_hitung, 2);
                                    $com_name = $company->company_name;
                                    $com_email = $company->company_email;

                                    EmailPOApproval::dispatch(
                                        $pesan,
                                        $po_nbr,
                                        $po_ord_date,
                                        $po_due_date,
                                        $po_total,
                                        $emails,
                                        $com_name,
                                        $com_email
                                    );

                                    $user = User::where('id', '=', $approver[0]->po_app_approver)->first(); // user siapa yang terima notif (lewat id)
                                    $useralt = User::where('id', '=', $approver[0]->po_app_alt_approver)->first();
                                    $details = [
                                        'body' => 'There is new PO that you need to approve',
                                        'url' => 'poappbrowse',
                                        'nbr' => (string)$data->t_nbr,
                                        'note' => 'Approval is needed, Please check'
                                    ]; // isi data yang dioper

                                    $user->notify(new eventNotification($details)); // syntax laravel
                                    $useralt->notify(new eventNotification($details));
                                }
                            }
                        }
                    }
                }
            }

            /**
             * Wsa PO Detail
             */
            $qdocRequest =  
                '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                    <Body>
                        <supp_pod_det xmlns="' . $wsa->wsas_path . '">
                            <inpdomain>' . $domain . '</inpdomain>
                        </supp_pod_det>
                    </Body>
                </Envelope>';

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

            $dataloopdet    = $xmlResp->xpath('//ns1:tempRow');
            $qdocResult     = (string) $xmlResp->xpath('//ns1:outOK')[0];

            if ($qdocResult == 'true') {
                foreach ($dataloopdet as $data) {
                    $domain = Domain::where('domain_code', $data->t_domain)->first();
                    $po_master = POMaster::where('pom_nbr', $data->t_nbr)->with('getSupplier')->first();
                    $item = ItemInventoryMaster::where('iim_item_part', $data->t_part)->first();
                    $site = Site::where('site_code', $data->t_site)->first();

                    if ($data->t_lvt_ord == null) {
                        $newDate = '2020-01-01';
                    } else {
                        $newDate = $data->t_lvt_ord;
                    }

                    if ($data->t_stats == 'delete') {
                        $po_details = PODetail::with('getPOMaster')
                            ->where('pod_mstr_id', $po_master->id)
                            ->where('pod_det_line', $data->t_line)->get();

                        //Kalau ada po detail di web tapi dari qad po nya di hapus
                        if ($po_details) {
                            foreach ($po_details as $detail) {
                                $po_history = new POHistory();
                                $po_history->poh_domain_id = $domain->id;
                                $po_history->poh_mstr_id = $po_master->id;
                                $po_history->poh_item_id = $item->id;
                                $po_history->poh_line = $detail->pod_det_line;
                                $po_history->poh_qty_ord = $detail->pod_det_qty_ord;
                                $po_history->poh_qty_rcvd = '0';
                                $po_history->poh_qty_open = $detail->pod_det_qty_ord;
                                $po_history->poh_qty_prom = $detail->pod_det_qty_ord;
                                $po_history->poh_price = $detail->pod_det_price;
                                $po_history->poh_lot = $detail->pod_det_lot;
                                $po_history->poh_loc = $detail->pod_det_loc;
                                $po_history->poh_due_date = $detail->pod_det_due_date;
                                $po_history->poh_supplier_id = $po_master->pom_supplier_id;
                                $po_history->poh_status = $po_master->pom_status;
                                $po_history->save();
                            }

                            PODetail::where('pod_mstr_id', $po_master->id)->where('pod_det_line', $data->t_line)->delete();
                            SuratJalan::where('sj_po_nbr', $data->t_nbr)->update([
                                'sj_status' => 'Closed'
                            ]);
                        }
                    } else {
                        $pod_detail = PODetail::firstOrNew([
                            'pod_mstr_id' => $po_master->id,
                            'pod_det_line' => $data->t_line
                        ]);

                        $pod_detail->pod_site_id = $site->id;
                        $pod_detail->pod_item_id = $item->id;
                        $pod_detail->pod_det_line = $data->t_line;
                        $pod_detail->pod_det_qty_ord = $data->t_qty_ord;
                        $pod_detail->pod_det_qty_rcvd = $data->t_qty_rcvd;
                        $pod_detail->pod_det_qty_open = $data->t_qty_ord;
                        $pod_detail->pod_det_qty_prom = $data->t_qty_ord;
                        $pod_detail->pod_det_price = $data->t_price;
                        $pod_detail->pod_det_loc = $data->t_loc;
                        $pod_detail->pod_det_lot = $data->t_lot;
                        $pod_detail->pod_det_due_date = $data->t_lvt_due;
                        $pod_detail->pod_det_date_creation = $newDate;
                        $pod_detail->save();

                        $po_histories = POHistory::where('poh_mstr_id', $po_master->id)
                            ->where('poh_item_id', $item->id)
                            ->where('poh_line', $data->t_line)
                            ->where('poh_qty_ord', $data->t_qty_ord)
                            ->where('poh_price', $data->t_price)
                            ->first();

                        if ($po_histories == null) {
                            $po_history = new POHistory();
                            $po_history->poh_domain_id = $domain->id;
                            $po_history->poh_mstr_id = $po_master->id;
                            $po_history->poh_item_id = $item->id;
                            $po_history->poh_line = $data->t_line;
                            $po_history->poh_qty_ord = $data->t_qty_ord;
                            $po_history->poh_qty_rcvd = '0';
                            $po_history->poh_qty_open = $data->t_qty_ord;
                            $po_history->poh_qty_prom = $data->t_qty_ord;
                            $po_history->poh_price = $data->t_price;
                            $po_history->poh_lot = $data->t_lot;
                            $po_history->poh_loc = $data->t_loc;
                            $po_history->poh_due_date = $data->t_lvt_due;
                            $po_history->poh_supplier_id = $po_master->pom_supplier_id;
                            $po_history->poh_status = $po_master->pom_status;
                            $po_history->save();
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            Log::channel('loadPO')->info($err);
        }
    }
}