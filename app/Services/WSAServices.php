<?php

namespace App\Services;

use App\Models\Master\Domain;
use App\Models\Master\ItemConversion;
use App\Models\Master\Qxwsa;
use App\Models\Master\UM;
use App\Models\RFPMaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WSAServices
{
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

    public function wsasupplier()
    {
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
            '<supp_supp_mstr xmlns="' . $wsa->wsas_path . '">' .
            '<inpdomain>' . $domain . '</inpdomain>' .
            '</supp_supp_mstr>' .
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
        // dd($qdocResponse);
        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        return [
            $dataloop,
            $qdocResult,
        ];
    }

    public function wsaitem()
    {
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
            '<supp_item_mstr xmlns="' . $wsa->wsas_path . '">' .
            '<inpdomain>' . $domain . '</inpdomain>' .
            '</supp_item_mstr>' .
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
        // dd($qdocResponse);
        $dataloop    = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        return [
            $dataloop,
            $qdocResult,
        ];
    }

    public function wsaloaditemconversion()
    {
        ItemConversion::truncate();

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

        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">' .
            '<Body>' .
            '<itemconv xmlns="' . $wsa->wsas_path . '">' .
            '<inpdomain>' . $domain . '</inpdomain>' .
            '</itemconv>' .
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

        return [
            $dataloop,
            $qdocResult,
        ];
    }

    public function wsaloadum()
    {
        UM::truncate();

        $wsa = Qxwsa::first();

        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        $domain         = $wsa->wsas_domain;

        $qdocRequest =  '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">' .
            '<Body>' .
            '<um_master xmlns="' . $wsa->wsas_path . '">' .
            '<inpdomain>' . $domain . '</inpdomain>' .
            '</um_master>' .
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

        return [
            $dataloop,
            $qdocResult
        ];
    }

    /**
     * Load wsa budget today
     */
    public function wsabugdet()
    {
        $wsa = Qxwsa::first();

        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        $domain         = $wsa->wsas_domain;

        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                <Body>
                    <supp_budget_check_rfp xmlns="' . $wsa->wsas_path . '">
                        <inpdomain>' . $domain . '</inpdomain>
                    </supp_budget_check_rfp>
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

        // dd($qdocResult);
        // dump($dataloop);

        if ($qdocResult == 'true') {
            foreach ($dataloop as $data) {
                if (
                    (string)$data->t_rcpsub != '' ||
                    (string)$data->t_subacc != '' ||
                    (string)$data->t_ccacc != ''
                ) {
                    $tot_budget  = $data->t_budget;
                    $used_budget = $data->t_usedbudget;
                    $acc         = $data->t_rcpsub;
                    $subacc      = $data->t_subacc;
                    $cc          = $data->t_ccacc;
                    $used_budget = $data->t_usedbudget;
                    $total_detail = 0;
                    $budget_rfp = 0; // Total RFP

                    $rfp = RFPMaster::join('rfp_dets', 'rfp_masters.id', 'rfp_dets.rfp_mstr_id')
                        ->join('item_inventory_masters', 'item_inventory_masters.id', '=', 'rfp_dets.rfp_item_id')
                        ->selectRaw('*, rfp_masters.rfp_status as rfp_status_master')
                        ->where('rfp_masters.rfp_status', '=', 'New Request')
                        ->where('item_inventory_masters.iim_item_isRfq', 1)
                        ->where('item_inventory_masters.iim_item_acc', '=', $data->t_rcpsub)
                        ->where('item_inventory_masters.iim_item_subacc', '=', $data->t_subacc)
                        ->where('item_inventory_masters.iim_item_costcenter', '=', $data->t_ccacc)
                        ->get();

                    foreach ($rfp as $datarfp) {
                        $tmp = $datarfp->rfp_qty_order * $datarfp->rfp_price;
                        $budget_rfp = $budget_rfp + $tmp;
                    }

                    $used_budget = $used_budget + $budget_rfp;
                }

                if ($data->t_orddate == Carbon::now()->toDateString()) {
                    $total_detail = $total_detail + $data->t_totdet;
                    $used_budget = $used_budget + $data->t_totdet;
                } else {
                    // SO kemarin di receipt
                    $total_detail = $total_detail + $data->t_totdet - $data->t_totfull;
                    $used_budget = $used_budget + $data->t_totdet - $data->t_totfull;
                }

                DB::table('budgets')
                    ->updateOrInsert([
                        'gl' => $data->t_rcpsub,
                        'subacc' => $data->t_subacc,
                        'cc' => $data->t_ccacc
                    ], [
                        'used_budget' => $used_budget,
                        'total_budget' => $data->t_budget,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);
            }

            $data = DB::table('budgets')->get();

            DB::table('budgets')->delete();

            return $data;
        } else {
            return false;
        }
    }

    /**
     * Load wsa budget yesterday
     */
    public function wsabudgetrfp()
    {
        $wsa = Qxwsa::first();

        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        $domain         = $wsa->wsas_domain;

        $qdocRequest =  
        '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <supp_budget_check_rfp xmlns="' . $wsa->wsas_path . '">
                    <inpdomain>' . $domain . '</inpdomain>
                </supp_budget_check_rfp>
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

        if($qdocResult == 'true') {
            foreach($dataloop as $data) {
                if (
                    (string)$data->t_rcpsub != '' ||
                    (string)$data->t_subacc != '' ||
                    (string)$data->t_ccacc != ''
                ) {
                    $tot_budget  = $data->t_budget;
                    $used_budget = $data->t_usedbudget;
                    $acc         = $data->t_rcpsub;
                    $subacc      = $data->t_subacc;
                    $cc          = $data->t_ccacc;
                    $total_detail = 0;
                    $used_budget = $data->t_usedbudget;
                }

                DB::table('budgets')
                    ->updateOrInsert(
                        [
                            'gl' => $data->t_rcpsub,
                            'subacc' => $data->t_subacc,
                            'cc' => $data->ts_cct_ccaccacc,
                        ],
                        [
                            'used_budget' => $used_budget,
                            'total_budget' => $data->t_budget,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ]

                    );
            }

            $data = DB::table('budgets')->get();

            DB::table('budgets')->delete();

            return $data;
        } else {
            return false;
        }
    }

    /**
     * get PO by Number
     */
    public function wsagetpo($ponbr,$suppcode,$kontrak){
        $wsa = Qxwsa::first();

        $domain = Session::get('domain');

        // dd($domain);

        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        $qdocRequest =   
        '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">' .
            '<Body>' .
                '<LMA_getPO xmlns="' . $wsa->wsas_path . '">' .
                    '<inpdomain>' . $domain . '</inpdomain>' .
                    '<innbr>' . $ponbr . '</innbr>' .
                    '<insupp>'.$suppcode.'</insupp>' .
                    '<inkon>'.$kontrak.'</inkon>' .
                '</LMA_getPO>' .
            '</Body>' .
        '</Envelope>';

        dump($qdocRequest);

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

        dd($qdocResponse);

        if(is_bool($qdocResponse)){
            return false;
        }

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);

        $dataloop   = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        return [$dataloop, $qdocResult];
    }


    /**
     * Load wsa po receipt
     */
    public function poreceipt($domain, $po_nbr, $line)
    {
        $wsa = Qxwsa::first();

        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        $qdocRequest =   
        '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">' .
            '<Body>' .
                '<porcp1 xmlns="' . $wsa->wsas_path . '">' .
                    '<inpdomain>' . $domain . '</inpdomain>' .
                    '<innbr>' . $po_nbr . '</innbr>' .
                    '<inline>' . $line . '</inline>' .
                '</porcp1>' .
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

        if(is_bool($qdocResponse)){
            return false;
        }

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);

        $dataloop   = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        return [$dataloop, $qdocResult];
    }

    public function getpoinvoice($ponbr){
        $wsa = Qxwsa::first();

        $domain = Session::get('domain');

        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        // $domain         = $wsa->wsas_domain;

        $qdocRequest =   
        '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <LMA_poappr xmlns="'. $wsa->wsas_path .'">
                    <inpref>'.$ponbr.'</inpref>
                </LMA_poappr>
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

        if(is_bool($qdocResponse)){
            return false;
        }

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);

        $dataloop   = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        return [$dataloop, $qdocResult];
    }

    public function wsagetso($sonbr){
        $wsa = Qxwsa::first();

        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        $domain         = Session::get('domain');

        $qdocRequest =   
        '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <LMA_sosearch xmlns="'. $wsa->wsas_path .'">
                    <inpdomain>'.$domain.'</inpdomain>
                    <inpsocust>'.$sonbr.'</inpsocust>
                </LMA_sosearch>
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

        if(is_bool($qdocResponse)){
            return false;
        }

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);

        $dataloop   = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        return [$dataloop, $qdocResult];
    }

    public function wsastockitem(){
        $wsa = Qxwsa::first();

        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        $domain         = $wsa->wsas_domain;

        $qdocRequest =   
        '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <LMA_stockitem xmlns="'. $wsa->wsas_path .'">
                    <inpdomain>'.$domain.'</inpdomain>
                </LMA_stockitem>
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

        if(is_bool($qdocResponse)){
            return false;
        }

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);

        $dataloop   = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        return [$dataloop, $qdocResult];
    }

    public function wsagethutang(){
        $wsa = Qxwsa::first();

        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        $domain         = $wsa->wsas_domain;

        $qdocRequest =   
        '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <LMA_hutangcust xmlns="'. $wsa->wsas_path .'">
                    <dom>'.$domain.'</dom>
                </LMA_hutangcust>
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

        if(is_bool($qdocResponse)){
            return false;
        }

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);

        $dataloop   = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        return [$dataloop, $qdocResult];
    }

    public function wsagetcust($dom){
        $wsa = Qxwsa::first();
        
        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        // $domain         = $wsa->wsas_domain;

        $qdocRequest =   
        '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <LMA_cust_mstr xmlns="'. $wsa->wsas_path .'">
                    <inpdomain>'.$dom.'</inpdomain>
                </LMA_cust_mstr>
            </Body>
        </Envelope>';

        // dd($qdocRequest);

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
        // dd($qdocResponse);

        if(is_bool($qdocResponse)){
            return false;
        }

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);

        $dataloop   = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        return [$dataloop, $qdocResult];
    }

    public function wsagetloc($dom){
        $wsa = Qxwsa::first();

        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        // $domain         = $wsa->wsas_domain;

        $qdocRequest =   
        '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <LMA_loc_mstr xmlns="'. $wsa->wsas_path .'">
                    <inpdomain>'.$dom.'</inpdomain>
                </LMA_loc_mstr>
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
        
        if(is_bool($qdocResponse)){
            return false;
        }

        

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);

        $dataloop   = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        return [$dataloop, $qdocResult];
    }

    public function wsagetsite($dom){
        $wsa = Qxwsa::first();

        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        $domain         = $wsa->wsas_domain;

        $qdocRequest =   
        '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <LMA_site_mstr xmlns="'. $wsa->wsas_path .'">
                    <inpdomain>'.$dom.'</inpdomain>
                </LMA_site_mstr>
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

        if(is_bool($qdocResponse)){
            return false;
        }

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);

        $dataloop   = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        return [$dataloop, $qdocResult];
    }

    public function wsagetsupp($dom){
        $wsa = Qxwsa::first();
        
        // Validasi WSA
        $qxUrl          = $wsa->wsas_url;
        $qxReceiver     = '';
        $qxSuppRes      = 'false';
        $qxScopeTrx     = '';
        $qdocName       = '';
        $qdocVersion    = '';
        $dsName         = '';
        $timeout        = 0;

        // $domain         = $wsa->wsas_domain;

        $qdocRequest =   
        '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <LMA_supp_mstr xmlns="'. $wsa->wsas_path .'">
                    <inpdomain>'.$dom.'</inpdomain>
                </LMA_supp_mstr>
            </Body>
        </Envelope>';

        

        // dd($qdocRequest);

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
        // dd($qdocResponse);

        if(is_bool($qdocResponse)){
            return false;
        }

        $xmlResp = simplexml_load_string($qdocResponse);

        $xmlResp->registerXPathNamespace('ns1', $wsa->wsas_path);

        $dataloop   = $xmlResp->xpath('//ns1:tempRow');
        $qdocResult = (string) $xmlResp->xpath('//ns1:outOK')[0];

        return [$dataloop, $qdocResult];
    }


}
