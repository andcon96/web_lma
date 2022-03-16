<?php

namespace App\Console\Commands;

use App\Models\Master\Qxwsa;
use App\Models\TRHistories;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoadTRHist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load_tr_hist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Untuk load tr hist';

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

        $qdocRequest =  '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                      <Body>
                      <supp_trhist xmlns="' . $wsa->wsas_path . '">
                      <inpdomain>' . $domain . '</inpdomain>
                      </supp_trhist>
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

        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        DB::beginTransaction();

        try {
            if ($qdocResult == 'true') {
                foreach ($dataloop as $data) {
                    $tr_histories = TRHistories::firstOrNew([
                        'tr_hist_domain' => $data->t_domain,
                        'tr_hist_part' => $data->t_part
                    ]);

                    $tr_hist_ket = '';

                    if ($data->t_ket1 == '') {
                        $tr_hist_ket = $data->t_ket2;
                    }

                    if ($data->t_ket4 == '') {
                        if ($data->t_ket3 == '') {
                            if ($data->t_ket2 == '') {
                                if ($data->t_ket1 == '') {
                                    $tr_hist_ket = '0';
                                } else {
                                    $tr_hist_ket = $data->t_ket1;
                                }
                            } else {
                                $tr_hist_ket = $data->t_ket2;
                            }
                        } else {
                            $tr_hist_ket = $data->t_ket3;
                        }
                    } else {
                        $tr_hist_ket = $data->t_ket4;
                    }

                    $newLastDate = Carbon::parse($data->t_lvt_date)->format('Y-m-d');

                    $tr_histories->tr_hist_domain = $data->t_domain;
                    $tr_histories->tr_hist_part = $data->t_part;
                    $tr_histories->tr_hist_desc = $data->t_desc;
                    $tr_histories->tr_hist_um = $data->t_um;
                    $tr_histories->tr_hist_pm = $data->t_pm_code;
                    $tr_histories->tr_hist_qty_oh = $data->t_qty;
                    $tr_histories->tr_hist_last_date = $newLastDate;
                    $tr_histories->tr_hist_days = $data->t_hit;
                    $tr_histories->tr_hist_amount = (string)$data->t_amt;
                    $tr_histories->tr_hist_ket = $tr_hist_ket;
                    $tr_histories->tr_hist_type = $data->t_tr_type;
                    $tr_histories->save();
                    // dump($tr_histories->tr_hist_ket);
                }
            }
            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            Log::channel('loadTRHist')->info($err);
        }
    }
}
