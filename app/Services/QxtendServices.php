<?php

namespace App\Services;

use App\Models\Master\ItemConversion;
use App\Models\Master\Prefix;
use App\Models\Master\Qxwsa;
use App\Models\Master\UM;
use App\Models\RFPMaster;
use App\Models\Transaksi\POhist;
use App\Models\Transaksi\RcptUnplanned;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class QxtendServices
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

  public function qxPOMaintanance($data, $nopo, $supplier)
  {
    // dd($data);
    $qxwsa = Qxwsa::first();
    $site = $data[0]->site;
    $duedate = $data[0]->due_date;

    // Var Qxtend
    $qxUrl          = $qxwsa->qx_url; // Edit Here

    $timeout        = 0;

    // XML Qextend ** Edit Here
    $qdocHead = '<?xml version="1.0" encoding="UTF-8"?>
      <soapenv:Envelope xmlns:wsa="http://www.w3.org/2005/08/addressing" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:qcom="urn:schemas-qad-com:xml-services:common" xmlns="urn:schemas-qad-com:xml-services">
          <soapenv:Header>
              <wsa:Action/>
              <wsa:To>urn:services-qad-com:QX_DNP</wsa:To>
              <wsa:MessageID>urn:services-qad-com::QX_DNP</wsa:MessageID>
              <wsa:ReferenceParameters>
                  <qcom:suppressResponseDetail>true</qcom:suppressResponseDetail>
              </wsa:ReferenceParameters>
              <wsa:ReplyTo>
                  <wsa:Address>urn:services-qad-com:</wsa:Address>
              </wsa:ReplyTo>
          </soapenv:Header>
          <soapenv:Body>
              <maintainPurchaseOrder>
                  <qcom:dsSessionContext>
                      <qcom:ttContext>
                          <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                          <qcom:propertyName>domain</qcom:propertyName>
                          <qcom:propertyValue/>
                      </qcom:ttContext>
                      <qcom:ttContext>
                          <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                          <qcom:propertyName>scopeTransaction</qcom:propertyName>
                          <qcom:propertyValue>false</qcom:propertyValue>
                      </qcom:ttContext>
                      <qcom:ttContext>
                          <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                          <qcom:propertyName>version</qcom:propertyName>
                          <qcom:propertyValue>eB2_3</qcom:propertyValue>
                      </qcom:ttContext>
                      <qcom:ttContext>
                          <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                          <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
                          <qcom:propertyValue>false</qcom:propertyValue>
                      </qcom:ttContext>
                      <qcom:ttContext>
                          <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                          <qcom:propertyName>username</qcom:propertyName>
                          <qcom:propertyValue>mfg</qcom:propertyValue>
                      </qcom:ttContext>
                      <qcom:ttContext>
                          <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                          <qcom:propertyName>password</qcom:propertyName>
                          <qcom:propertyValue/>
                      </qcom:ttContext>
                  <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>action</qcom:propertyName>
                      <qcom:propertyValue/>
                  </qcom:ttContext>
                  <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>entity</qcom:propertyName>
                      <qcom:propertyValue/>
                  </qcom:ttContext>
                  <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>email</qcom:propertyName>
                      <qcom:propertyValue/>
                  </qcom:ttContext>
                  <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>emailLevel</qcom:propertyName>
                      <qcom:propertyValue/>
                  </qcom:ttContext>
              </qcom:dsSessionContext>';


    $qdocbody = '<dsPurchaseOrder>
                        <purchaseOrder>
                          <poNbr>' . $nopo . '</poNbr>
                          <poVend>' . $supplier . '</poVend>
                          <poShip>' . $supplier . '</poShip>
                          <poDueDate>' . $duedate . '</poDueDate>
                          <poSite>' . $site . '</poSite>';

    foreach ($data as $key => $datas) {
      $line = $key + 1;
      $qdocbody .= '<lineDetail>
            <line>' . $line . '</line>
            <yn>true</yn>
            <podSite>' . $datas->site . '</podSite>
            <podPart>' . $datas->iim_item_part . '</podPart>
            <podQtyOrd>' . $datas->qty_pur . '</podQtyOrd>
            <podPurCost>' . $datas->price . '</podPurCost>
            <yn1>true</yn1>
            </lineDetail>';
    }


    $qdocfoot = '</purchaseOrder>
                </dsPurchaseOrder>
                </maintainPurchaseOrder>
                        </soapenv:Body>
                        </soapenv:Envelope>';

    $qdocRequest = $qdocHead . $qdocbody . $qdocfoot;

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
      //
      $curlErrno = curl_errno($curl);
      $curlError = curl_error($curl);
      $first = true;
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
    if (is_bool($qdocResponse)) {
      return false;
    }
    $xmlResp = simplexml_load_string($qdocResponse);
    $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
    $qdocResult = (string) $xmlResp->xpath('//ns1:result')[0];

    if ($qdocResult == "success" or $qdocResult == "warning") {
      return true;
    } else {
      return false;
    }
  }

  public function qxPOReceipt($qxtend, $surat_jalan)
  {
    $site = $surat_jalan[0]->getPOMaster->getPODetail[0]->getSite->site_code;

    $qxUrl          = $qxtend->qx_url;
    $timeout        = 0;

    $qdocHead =
      '<?xml version="1.0" encoding="UTF-8"?>
      <soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services"
        xmlns:qcom="urn:schemas-qad-com:xml-services:common"
        xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsa="http://www.w3.org/2005/08/addressing">
        <soapenv:Header>
          <wsa:Action/>
          <wsa:To>urn:services-qad-com:QX_DNP</wsa:To>
          <wsa:MessageID>urn:services-qad-com::QX_DNP</wsa:MessageID>
          <wsa:ReferenceParameters>
            <qcom:suppressResponseDetail>true</qcom:suppressResponseDetail>
          </wsa:ReferenceParameters>
          <wsa:ReplyTo>
            <wsa:Address>urn:services-qad-com:</wsa:Address>
          </wsa:ReplyTo>
        </soapenv:Header>
        <soapenv:Body>
          <receivePurchaseOrder>
            <qcom:dsSessionContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>domain</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>scopeTransaction</qcom:propertyName>
                <qcom:propertyValue>false</qcom:propertyValue>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>version</qcom:propertyName>
                <qcom:propertyValue>eB_2</qcom:propertyValue>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
                <qcom:propertyValue>false</qcom:propertyValue>
              </qcom:ttContext>
              
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>username</qcom:propertyName>
                <qcom:propertyValue>mfg</qcom:propertyValue>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>password</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
            
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>action</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>entity</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>email</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>emailLevel</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
            </qcom:dsSessionContext>';

    $qdocBody =
      '<dsPurchaseOrderReceive>
              <purchaseOrderReceive>
              <ordernum>' . $surat_jalan[0]->getPOMaster->pom_nbr . '</ordernum>';

    foreach ($surat_jalan as $sj) {
      $qdocBody .=
        '<lineDetail>
                  <line>' . $sj->sj_line . '</line>
                  <lotserialQty>' . $sj->sj_qty_rcvd . '</lotserialQty>
                  <site>' . $site . '</site>
                  <location>' . $sj->sj_loc . '</location>
                  <lotserial>' . $sj->sj_lot . '</lotserial>
                  <lotref>' . $sj->sj_ref . '</lotref>
                </lineDetail>';
    }

    $qdocFoot =
      '</purchaseOrderReceive>
            </dsPurchaseOrderReceive>
          </receivePurchaseOrder>
        </soapenv:Body>
      </soapenv:Envelope>';

    $qdocRequest = $qdocHead . $qdocBody . $qdocFoot;

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
      //
      $curlErrno = curl_errno($curl);
      $curlError = curl_error($curl);
      $first = true;
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

    if (is_bool($qdocResponse)) {
      return false;
    }
    $xmlResp = simplexml_load_string($qdocResponse);
    $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
    $qdocResult = (string) $xmlResp->xpath('//ns1:result')[0];

    if ($qdocResult == "success" or $qdocResult == "warning") {
      return true;
    } else {
      return false;
    }
  }


  public function submitreceipt($datas)
  {
    // dd($datas,'b');
    $ponbr = $datas['po_nbr'];
    $supp = $datas['supphidden'];
    $suppname = $datas['suppnamehidden'];
    $partloc = $datas['partloc'];
    $partlot = $datas['partlot'];
    $poline = $datas['poline'];
    $qtyterima = $datas['qtyterima'];
    $qtyfg = $datas['qtyfg'];
    $qtyord = $datas['poqtyord'];
    $qtyrcvd = $datas['poqtyrcvd'];
    $popart = $datas['popart'];
    $popartname = $datas['popartdesc'];
    $receiptdate = $datas['receiptdate'];
    $effdate = $datas['effdate'];
    $pokontrak = $datas['po_kontrak'];
    $partsite = $datas['podsite'];
    // $listnopol = implode(" , ", $datas['nopol']);
    $nopol = $datas['nopol'];

    $domain = Session::get('domain');

    // dd($listnopol);
    // foreach($ponbr as $key => $p){
    //   dump($key,$poline[$key]);
    // }
    //  dd($datas,$datas['ponbr']);

    DB::beginTransaction();
    try {

      foreach ($poline as $key => $a) {
        $qtyreject = 0;
        $qtylebih = 0;

        $qtyreject = $qtyterima[$key] - $qtyfg[$key];

        if($qtyreject < 0){
          $qtylebih = abs($qtyreject);
          $qtyreject = 0;

          $rcptunplanned = new RcptUnplanned();
          
          $rcptunplanned->domain = $domain;
          $rcptunplanned->effdate = $effdate;
          $rcptunplanned->receiptdate = $receiptdate;
          $rcptunplanned->ponbr = $ponbr;
          $rcptunplanned->supp = $supp;
          $rcptunplanned->suppname = $suppname;
          $rcptunplanned->line = $a;
          $rcptunplanned->part = $popart[$key];
          $rcptunplanned->partdesc = $popartname[$key];
          $rcptunplanned->loc = $partloc[$key];
          $rcptunplanned->lot = $partlot[$key];
          $rcptunplanned->site = $partsite[$key];
          $rcptunplanned->qty_unplanned = $qtylebih;
          $rcptunplanned->pokontrak = $pokontrak;
          $rcptunplanned->nopol = $nopol;
          $rcptunplanned->save();
        }

          $pohist = new POhist();

          // dd($popartname[$key]);

          $pohist->ph_ponbr = $ponbr;
          $pohist->ph_supp = $supp;
          $pohist->ph_suppname = $suppname;
          $pohist->ph_line = $a;
          $pohist->ph_part = $popart[$key];
          $pohist->ph_partname = $popartname[$key] != null ? $popartname[$key] : '';
          $pohist->ph_qty_order = $qtyord[$key];
          $pohist->ph_qty_rcvd = $qtyrcvd[$key];
          $pohist->ph_qty_terima = $qtyterima[$key];
          $pohist->ph_qty_fg = $qtyfg[$key];
          $pohist->ph_qty_rjct = $qtyreject;
          $pohist->ph_qty_lebih = $qtylebih;
          $pohist->ph_nopol = $nopol;
          $pohist->ph_effdate = $effdate;
          $pohist->ph_receiptdate = $receiptdate;
          $pohist->ph_loc = $partloc[$key];
          $pohist->ph_lot = $partlot[$key];
          $pohist->created_by = auth()->user()->id;
          $pohist->ph_domain = $domain;
          $pohist->ph_pokontrak = $pokontrak;
          $pohist->save();

      }

      $datetoday = Carbon::parse(now())->format('Y-m-d');

      $qxwsa = Qxwsa::first();
      // dd($qxtend);
      $qxUrl          = $qxwsa->qx_url;

      $qxRcv = $qxwsa->qx_rcv;


      $timeout = 0;

      $array_unplanned = [];
      $i = 0;

      $qdocHead =
        '<?xml version="1.0" encoding="UTF-8"?>
      <soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services"
        xmlns:qcom="urn:schemas-qad-com:xml-services:common"
        xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsa="http://www.w3.org/2005/08/addressing">
        <soapenv:Header>
          <wsa:Action/>
          <wsa:To>urn:services-qad-com:' . $qxRcv . '</wsa:To>
          <wsa:MessageID>urn:services-qad-com::' . $qxRcv . '</wsa:MessageID>
          <wsa:ReferenceParameters>
            <qcom:suppressResponseDetail>true</qcom:suppressResponseDetail>
          </wsa:ReferenceParameters>
          <wsa:ReplyTo>
            <wsa:Address>urn:services-qad-com:</wsa:Address>
          </wsa:ReplyTo>
        </soapenv:Header>
        <soapenv:Body>
          <receivePurchaseOrder>
            <qcom:dsSessionContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>domain</qcom:propertyName>
                <qcom:propertyValue>' . $domain . '</qcom:propertyValue>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>scopeTransaction</qcom:propertyName>
                <qcom:propertyValue>true</qcom:propertyValue>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>version</qcom:propertyName>
                <qcom:propertyValue>eB_2</qcom:propertyValue>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
                <qcom:propertyValue>false</qcom:propertyValue>
              </qcom:ttContext>
              <!--
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>username</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>password</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              -->
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>action</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>entity</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <!-- <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>email</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>emailLevel</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext> -->
            </qcom:dsSessionContext>';
      $qdocBody =
        '<dsPurchaseOrderReceive>
                  <purchaseOrderReceive>
                    <ordernum>' . $ponbr . '</ordernum>
                    <effDate>'.$effdate.'</effDate>
                    <shipDate>'.$datetoday.'</shipDate>
                    <receiptDate>' . $receiptdate . '</receiptDate>
                    <cmmtYn>true</cmmtYn>
                    <yn>true</yn>
                    <yn1>true</yn1>
                    <purchaseOrderReceiveTransComment>
                      <cmtCmmt>' . $nopol . '</cmtCmmt>
                    </purchaseOrderReceiveTransComment>';
      foreach ($poline as $key => $p) {
        $qtyreject = 0;

        $qtyreject = $qtyterima[$key] - $qtyfg[$key];

        // dd($index);
        $qdocBody .= ' <lineDetail>
                        <line>' . $p . '</line>
                        <lotserialQty>' . $qtyterima[$key]  . '</lotserialQty>
                        <location>' . $partloc[$key] . '</location>
                        <lotserial>' . $partlot[$key] . '</lotserial>
                        <multiEntry>true</multiEntry>';

        if ($qtyfg[$key] > $qtyterima[$key]) {
          $qdocBody .= ' <receiptDetail>
                        <location>' . $partloc[$key] . '</location>
                        <lotserialQty>' . $qtyterima[$key] . '</lotserialQty>
                        <serialsYn>true</serialsYn>
                      </receiptDetail>';
        } else {
          if ($qtyfg > 0) {
            $qdocBody .= ' <receiptDetail>
                          <location>' . $partloc[$key] . '</location>
                          <lotserialQty>' . $qtyfg[$key] . '</lotserialQty>
                          <serialsYn>true</serialsYn>
                        </receiptDetail>';
          }
        }

        if ($qtyreject > 0) {

          $qdocBody .= ' <receiptDetail>
                          <location>Reject</location>
                          <lotserialQty>' . $qtyreject . '</lotserialQty>
                          <serialsYn>true</serialsYn>
                        </receiptDetail>';
        }

        $qdocBody .= '</lineDetail>';
      }
      $qdocFoot = ' </purchaseOrderReceive>
                </dsPurchaseOrderReceive>
              </receivePurchaseOrder>
            </soapenv:Body>
          </soapenv:Envelope>';


      $qdocRequest = $qdocHead . $qdocBody . $qdocFoot;
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
        //
        $curlErrno = curl_errno($curl);
        $curlError = curl_error($curl);
        $first = true;
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

      if (is_bool($qdocResponse)) {
        // dd('false');
        DB::rollBack();
        return false;
      }

      // dd($qdocResponse);
      $xmlResp = simplexml_load_string($qdocResponse);
      $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
      $qdocResult = (string) $xmlResp->xpath('//ns1:result')[0];

      if ($qdocResult == "success" or $qdocResult == "warning") {
        // dd('true');
        DB::commit();
        return true;
      } else {
        // dd('qxtend_err');
        DB::rollBack();
        return 'qxtend_err';
      }
    } catch (Exception $e) {
      // dd('db_err');

      // dd($e);

      // dd(json_encode($e->getMessage(), true));
      DB::rollBack();
      return 'db_err';
    }
  }

  public function qxSOShipment($datas)
  {

    DB::beginTransaction();

    try {

      $sj_mstr = SuratJalan::findOrFail($datas['idmaster']);
      $sj_mstr->sj_status = 'Closed';
      $sj_mstr->sj_remark = $datas['remarks'];
      $sj_mstr->sj_eff_date = $datas['effdate'];
      $sj_mstr->sj_nopol = $datas['nopol'];
      $sj_mstr->sj_potongdp = $datas['potongdp'];
      $sj_mstr->sj_exgudang = $datas['exgudang'];
      $sj_mstr->sj_exkapal = $datas['exkapal'];
      $sj_mstr->sj_qtykarung = $datas['qtykarung'];
      $sj_mstr->sj_transportir_name = $datas['transportirname'];
      $sj_mstr->save();

      foreach ($datas['line'] as $key => $data) {
        $sj_dets = SuratJalanDetail::findOrFail($datas['iddetail'][$key]);
        $sj_dets->sj_qty_rcvd = $datas['qtyinp'][$key];
        $sj_dets->sj_loc = $datas['partloc'][$key];
        $sj_dets->sj_lot = $datas['lot'][$key];
        $sj_dets->save();
      }

      $qxwsa = Qxwsa::first();

      // Var Qxtend
      $qxUrl          = $qxwsa->qx_url; // Edit Here

      $qxRcv = $qxwsa->qx_rcv;

      $timeout        = 0;

      $domain         = Session::get('domain');

      // XML Qextend ** Edit Here
      $qdocHead = '<?xml version="1.0" encoding="UTF-8"?>
            <soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services"
              xmlns:qcom="urn:schemas-qad-com:xml-services:common"
              xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsa="http://www.w3.org/2005/08/addressing">
              <soapenv:Header>
                <wsa:Action/>
                <wsa:To>urn:services-qad-com:' . $qxRcv . '</wsa:To>
                <wsa:MessageID>urn:services-qad-com::' . $qxRcv . '</wsa:MessageID>
                <wsa:ReferenceParameters>
                  <qcom:suppressResponseDetail>true</qcom:suppressResponseDetail>
                </wsa:ReferenceParameters>
                <wsa:ReplyTo>
                  <wsa:Address>urn:services-qad-com:</wsa:Address>
                </wsa:ReplyTo>
              </soapenv:Header>
              <soapenv:Body>
                <shipSalesOrder>
                  <qcom:dsSessionContext>
                    <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>domain</qcom:propertyName>
                      <qcom:propertyValue>' . $domain . '</qcom:propertyValue>
                    </qcom:ttContext>
                    <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>scopeTransaction</qcom:propertyName>
                      <qcom:propertyValue>true</qcom:propertyValue>
                    </qcom:ttContext>
                    <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>version</qcom:propertyName>
                      <qcom:propertyValue>ERP3_2</qcom:propertyValue>
                    </qcom:ttContext>
                    <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
                      <qcom:propertyValue>false</qcom:propertyValue>
                    </qcom:ttContext>
                    <!--
                    <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>username</qcom:propertyName>
                      <qcom:propertyValue/>
                    </qcom:ttContext>
                    <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>password</qcom:propertyName>
                      <qcom:propertyValue/>
                    </qcom:ttContext>
                    -->
                    <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>action</qcom:propertyName>
                      <qcom:propertyValue/>
                    </qcom:ttContext>
                    <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>entity</qcom:propertyName>
                      <qcom:propertyValue/>
                    </qcom:ttContext>
                    <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>email</qcom:propertyName>
                      <qcom:propertyValue/>
                    </qcom:ttContext>
                    <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>emailLevel</qcom:propertyName>
                      <qcom:propertyValue/>
                    </qcom:ttContext>
                  </qcom:dsSessionContext>';


      $qdocBody = '<dsSalesOrderShipment>
                  <SalesOrderShipment>
                      <soNbr>' . $datas['sonbr'] . '</soNbr>
                      <effDate>' . $datas['effdate'] . '</effDate>
                      <document>' . $datas['remarks'] . ';' . $datas['nopol'] . '</document>';
      foreach ($datas['line'] as $key => $data) {
        $qdocBody .= '
                  <lineDetail>
                          <line>' . $data . '</line>
                          <lotserialQty>' . $datas['qtysj'][$key] . '</lotserialQty>
                          <location>' . $datas['partloc'][$key] . '</location>
                          <lotserial>' . $datas['lot'][$key] . '</lotserial>
                          <pickLogic>false</pickLogic>
                          <yn>true</yn>
                          <yn1>true</yn1>        
                          </lineDetail>';
      }
      $qdocfooter =   '<soTrl1Amt>-' . $datas['potongdp'] . '</soTrl1Amt>
                    </SalesOrderShipment> 
                  </dsSalesOrderShipment>
                          </shipSalesOrder>
                          </soapenv:Body>
                          </soapenv:Envelope>';

      $qdocRequest = $qdocHead . $qdocBody . $qdocfooter;

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
        //
        $curlErrno = curl_errno($curl);
        $curlError = curl_error($curl);
        $first = true;
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
      if (is_bool($qdocResponse)) {
        DB::rollBack();
        return false;
      }
      $xmlResp = simplexml_load_string($qdocResponse);
      $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
      $qdocResult = (string) $xmlResp->xpath('//ns1:result')[0];

      // dd($qdocResponse, $qdocResult);
      if ($qdocResult == "success" or $qdocResult == "warning") {

        DB::commit();
        return true;
      } else {

        DB::rollBack();
        return 'response_err';
      }
    } catch (Exception $e) {
      DB::rollBack();
      return 'db_err';
    }
  }

  public function qxSOMT($datas)
  {
    // dd($datas[0]->sod_nbr,$datas);
    $qxwsa = Qxwsa::first();

    // Var Qxtend
    $qxUrl          = $qxwsa->qx_url; // Edit Here

    $qxRcv          = $qxwsa->qx_rcv;

    $timeout        = 0;

    $domain         = Session::get('domain');

    // XML Qextend ** Edit Here
    $qdocHead = '<?xml version="1.0" encoding="UTF-8"?>
              <soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services"
                xmlns:qcom="urn:schemas-qad-com:xml-services:common"
                xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsa="http://www.w3.org/2005/08/addressing">
                <soapenv:Header>
                  <wsa:Action/>
                  <wsa:To>urn:services-qad-com:' . $qxRcv . '</wsa:To>
                  <wsa:MessageID>urn:services-qad-com::' . $qxRcv . '</wsa:MessageID>
                  <wsa:ReferenceParameters>
                    <qcom:suppressResponseDetail>true</qcom:suppressResponseDetail>
                  </wsa:ReferenceParameters>
                  <wsa:ReplyTo>
                    <wsa:Address>urn:services-qad-com:</wsa:Address>
                  </wsa:ReplyTo>
                </soapenv:Header>
                <soapenv:Body>
                  <maintainSalesOrder>
                    <qcom:dsSessionContext>
                      <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>domain</qcom:propertyName>
                        <qcom:propertyValue>' . $domain . '</qcom:propertyValue>
                      </qcom:ttContext>
                      <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>scopeTransaction</qcom:propertyName>
                        <qcom:propertyValue>true</qcom:propertyValue>
                      </qcom:ttContext>
                      <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>version</qcom:propertyName>
                        <qcom:propertyValue>ERP3_2</qcom:propertyValue>
                      </qcom:ttContext>
                      <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
                        <qcom:propertyValue>false</qcom:propertyValue>
                      </qcom:ttContext>
                      <!--
                      <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>username</qcom:propertyName>
                        <qcom:propertyValue/>
                      </qcom:ttContext>
                      <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>password</qcom:propertyName>
                        <qcom:propertyValue/>
                      </qcom:ttContext>
                      -->
                      <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>action</qcom:propertyName>
                        <qcom:propertyValue/>
                      </qcom:ttContext>
                      <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>entity</qcom:propertyName>
                        <qcom:propertyValue/>
                      </qcom:ttContext>
                      <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>email</qcom:propertyName>
                        <qcom:propertyValue/>
                      </qcom:ttContext>
                      <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>emailLevel</qcom:propertyName>
                        <qcom:propertyValue/>
                      </qcom:ttContext>
                    </qcom:dsSessionContext>';


    $qdocBody = '<dsSalesOrder>
                  <salesOrder>
                    <soNbr>' . $datas[0]->sod_nbr . '</soNbr>
                    <yn>true</yn>';
    foreach ($datas as $key => $data) {
      $qdocBody .= '
                          <salesOrderDetail>
                            <operation>A</operation>
                            <sodPart>' . $data->sod_part . '</sodPart>
                            <sodQtyOrd>' . $data->sod_qty_sj . '</sodQtyOrd>
                            <sodListPr>' . $data->sod_price_ls . '</sodListPr>
                            <lYn>true</lYn>
                            <sodType>M</sodType>
                          </salesOrderDetail>';
    }
    $qdocfooter =   '</salesOrder>
                      </dsSalesOrder>
                    </maintainSalesOrder>
                  </soapenv:Body>
                  </soapenv:Envelope>';

    $qdocRequest = $qdocHead . $qdocBody . $qdocfooter;

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
      //
      $curlErrno = curl_errno($curl);
      $curlError = curl_error($curl);
      $first = true;
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

    if (is_bool($qdocResponse)) {
      return false;
    }
    $xmlResp = simplexml_load_string($qdocResponse);
    $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
    $qdocResult = (string) $xmlResp->xpath('//ns1:result')[0];

    return $qdocResult;
  }

  public function qxRcptUnplanned($datas)
  {

    DB::beginTransaction();

    try {

      $rcpt_unplanned = RcptUnplanned::findOrFail($datas['idmaster']);
      $rcpt_unplanned->updated_at = now();
      $rcpt_unplanned->status = 'Closed';
      $rcpt_unplanned->save();

      $qxwsa = Qxwsa::first();

      // Var Qxtend
      $qxUrl          = $qxwsa->qx_url; // Edit Here

      $qxRcv          = $qxwsa->qx_rcv;

      $timeout        = 0;

      $domain         = Session::get('domain');

      // XML Qextend ** Edit Here
      $qdocHead = '<?xml version="1.0" encoding="UTF-8"?>
    <soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services"
      xmlns:qcom="urn:schemas-qad-com:xml-services:common"
      xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsa="http://www.w3.org/2005/08/addressing">
      <soapenv:Header>
        <wsa:Action/>
        <wsa:To>urn:services-qad-com:' . $qxRcv . '</wsa:To>
        <wsa:MessageID>urn:services-qad-com::' . $qxRcv . '</wsa:MessageID>
        <wsa:ReferenceParameters>
          <qcom:suppressResponseDetail>true</qcom:suppressResponseDetail>
        </wsa:ReferenceParameters>
        <wsa:ReplyTo>
          <wsa:Address>urn:services-qad-com:</wsa:Address>
        </wsa:ReplyTo>
      </soapenv:Header>
      <soapenv:Body>
        <receiveInventory>
          <qcom:dsSessionContext>
            <qcom:ttContext>
              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
              <qcom:propertyName>domain</qcom:propertyName>
              <qcom:propertyValue>' . $domain . '</qcom:propertyValue>
            </qcom:ttContext>
            <qcom:ttContext>
              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
              <qcom:propertyName>scopeTransaction</qcom:propertyName>
              <qcom:propertyValue>true</qcom:propertyValue>
            </qcom:ttContext>
            <qcom:ttContext>
              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
              <qcom:propertyName>version</qcom:propertyName>
              <qcom:propertyValue>eB_2</qcom:propertyValue>
            </qcom:ttContext>
            <qcom:ttContext>
              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
              <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
              <qcom:propertyValue>false</qcom:propertyValue>
            </qcom:ttContext>
            <!--
            <qcom:ttContext>
              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
              <qcom:propertyName>username</qcom:propertyName>
              <qcom:propertyValue/>
            </qcom:ttContext>
            <qcom:ttContext>
              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
              <qcom:propertyName>password</qcom:propertyName>
              <qcom:propertyValue/>
            </qcom:ttContext>
            -->
            <qcom:ttContext>
              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
              <qcom:propertyName>action</qcom:propertyName>
              <qcom:propertyValue/>
            </qcom:ttContext>
            <qcom:ttContext>
              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
              <qcom:propertyName>entity</qcom:propertyName>
              <qcom:propertyValue/>
            </qcom:ttContext>
            <qcom:ttContext>
              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
              <qcom:propertyName>email</qcom:propertyName>
              <qcom:propertyValue/>
            </qcom:ttContext>
            <qcom:ttContext>
              <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
              <qcom:propertyName>emailLevel</qcom:propertyName>
              <qcom:propertyValue/>
            </qcom:ttContext>
          </qcom:dsSessionContext>';


      $qdocBody = ' <dsInventoryReceipt>
                    <inventoryReceipt>
                      <ptPart>' . $datas['part'] . '</ptPart>
                      <lotserialQty>' . $datas['qtyunplanned'] . '</lotserialQty>
                      <site>' . $datas['site'] . '</site>
                      <location>' . $datas['loc'] . '</location>
                      <lotserial>' . $datas['lot'] . '</lotserial>
                      <rmks>' . $datas['po_nbr'] . '</rmks>
                      <effDate>' . $datas['receiptdate'] . '</effDate>';
      $qdocfooter =   '</inventoryReceipt>
                    </dsInventoryReceipt>
                  </receiveInventory>
                </soapenv:Body>
              </soapenv:Envelope>';

      $qdocRequest = $qdocHead . $qdocBody . $qdocfooter;

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
        //
        $curlErrno = curl_errno($curl);
        $curlError = curl_error($curl);
        $first = true;
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

      if (is_bool($qdocResponse)) {

        DB::rollBack();
        return false;
      }
      $xmlResp = simplexml_load_string($qdocResponse);
      $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
      $qdocResult = (string) $xmlResp->xpath('//ns1:result')[0];



      if ($qdocResult == "success" or $qdocResult == "warning") {

        DB::commit();
        return true;
      } else {

        DB::rollBack();
        return 'qxtend_err';
      }
    } catch (Exception $e) {

      DB::rollback();
      return 'db_err';
    }
  }
}
