<?php

namespace App\Services;

use App\Models\Master\ItemConversion;
use App\Models\Master\Prefix;
use App\Models\Master\Qxwsa;
use App\Models\Master\UM;
use App\Models\RFPMaster;
use App\Models\Transaksi\POhist;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use Carbon\Carbon;
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
    // dd($newrequest,'b');
    $ponbr = $datas['ponbr'];
    $poline = $datas['poline'];
    $qtyinput = $datas['qtyinput'];
    $qtyord = $datas['poqtyord'];
    $qtyrcvd = $datas['poqtyrcvd'];
    $popart = $datas['popart'];
    // foreach($ponbr as $key => $p){
    //   dump($key,$poline[$key]);
    // }
    //  dd($datas,$datas['ponbr']);



    $qxwsa = Qxwsa::first();
    // dd($qxtend);
    $qxUrl          = $qxwsa->qx_url;

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
                <qcom:propertyValue>10USA</qcom:propertyValue>
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
                    <ordernum>' . $ponbr[0] . '</ordernum>
                    <move>true</move>
                    <yn>true</yn>
                    <yn1>true</yn1>';
    foreach ($poline as $key => $p) {
      // dd($index);
      if ($qtyinput[$key] > 0) {
        $qdocBody .= ' <lineDetail>
                    <line>' . $p . '</line>
                    <lotserialQty>' . $qtyinput[$key] . '</lotserialQty>
                    </lineDetail>';
      }
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
      return false;
    }

    // dd($qdocResponse);
    $xmlResp = simplexml_load_string($qdocResponse);
    $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
    $qdocResult = (string) $xmlResp->xpath('//ns1:result')[0];

    if ($qdocResult == "success" or $qdocResult == "warning") {

      foreach ($ponbr as $key => $a) {

        if ($qtyinput[$key] > 0) {
          $pohist = new POhist();

          $pohist->ph_ponbr = $a;
          $pohist->ph_line = $poline[$key];
          $pohist->ph_part = $popart[$key];
          $pohist->ph_qty_order = $qtyord[$key];
          $pohist->ph_qty_rcvd = $qtyrcvd[$key];
          $pohist->ph_qty_input = $qtyinput[$key];
          $pohist->created_by = auth()->user()->username;
          $pohist->save();
        }
      }

      return true;
    } else {
      return 'qxtend_err';
    }
  }

  public function qxSOShipment($datas)
  {
    $qxwsa = Qxwsa::first();

    // Var Qxtend
    $qxUrl          = $qxwsa->qx_url; // Edit Here

    $timeout        = 0;

    $domain         = Session::get('domain');

    // XML Qextend ** Edit Here
    $qdocHead = '<?xml version="1.0" encoding="UTF-8"?>
            <soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services"
              xmlns:qcom="urn:schemas-qad-com:xml-services:common"
              xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsa="http://www.w3.org/2005/08/addressing">
              <soapenv:Header>
                <wsa:Action/>
                <wsa:To>urn:services-qad-com:QADLMA</wsa:To>
                <wsa:MessageID>urn:services-qad-com::QADLMA</wsa:MessageID>
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
                      <qcom:propertyValue>'.$domain.'</qcom:propertyValue>
                    </qcom:ttContext>
                    <qcom:ttContext>
                      <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                      <qcom:propertyName>scopeTransaction</qcom:propertyName>
                      <qcom:propertyValue>false</qcom:propertyValue>
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
                      <soNbr>'.$datas['sonbr'].'</soNbr>
                      <effDate>'.$datas['effdate'].'</effDate>
                      <document>'.$datas['remarks'].'</document>';
                      foreach($datas['line'] as $key => $data){ 
                          $qdocBody.= '
                  <lineDetail>
                          <line>'.$data.'</line>
                          <lotserialQty>'.$datas['qtyinp'][$key].'</lotserialQty>
                          <location>'.$datas['partloc'][$key].'</location>
                          <pickLogic>false</pickLogic>
                          <yn>true</yn>
                          <yn1>true</yn1>        
                          </lineDetail>';
                      }
    $qdocfooter =   '</SalesOrderShipment> 
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
      return false;
    }
    $xmlResp = simplexml_load_string($qdocResponse);
    $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
    $qdocResult = (string) $xmlResp->xpath('//ns1:result')[0];

    // dd($qdocResponse, $qdocResult);
    if ($qdocResult == "success" or $qdocResult == "warning") {
      
      $sj_mstr = SuratJalan::findOrFail($datas['idmaster']);
      $sj_mstr->sj_status = 'Closed';
      $sj_mstr->save();

      foreach($datas['line'] as $key => $data){
        $sj_dets = SuratJalanDetail::findOrFail($datas['iddetail'][$key]);
        $sj_dets->sj_qty_rcvd = $datas['qtyinp'][$key];
        $sj_dets->sj_loc = $datas['partloc'][$key];
        $sj_dets->save();
      }
      return true;
    } else {
      return false;
    }
    
  }
}
