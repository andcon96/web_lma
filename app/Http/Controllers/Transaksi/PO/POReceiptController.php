<?php

namespace App\Http\Controllers\Transaksi\PO;

use App\Http\Controllers\Controller;
use App\Models\Master\LocMstr;
use App\Models\Master\SuppMstr;
use App\Models\Transaksi\POhist;
use App\Models\Transaksi\PurchaseOrder;
use App\Services\CreateTempTable;
use App\Services\QxtendServices;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as Session;

class POReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Session::get('domain'));
        $suppdat = SuppMstr::where('supp_dom','=',Session::get('domain'))->get();

        return view('transaksi.poreceipt.index', compact('suppdat'));
    }

    public function searchPO(Request $req){
        // dd('searchPO');
        // 
        // Validasi Webif(is_null($req->sjnbr) && is_null($req->suppcode)){
        //     alert()->error('Error', 'Harap isi salah satu dari PO No. atau Supplier Name')->persistent('Dismiss');
        //     return redirect()->back();
        // }
        // $receiptdate = $req->receiptdate;
        // $errorcode = Session::get('errors');
        // $sessionpo = Session::get('session_po');

        if(is_null($req->sjnbr) && is_null($req->suppcode) && is_null($req->pokontrak)){
            $ponbrtampung = $req->ponbr;
            $supptampung = '';
            $kontraktampung = '';
            // dd($ponbrtampung);
            // alert()->error('Error', 'PO tidak boleh kosong')->persistent('Dismiss');
            // return redirect()->back();
        }else{
            $ponbrtampung = $req->sjnbr;
            $supptampung = $req->suppcode;
            $kontraktampung = $req->pokontrak;
        }

        Session::put('dom_search', Session::get('domain'));

        // WSA QAD
        $po_receipt = (new WSAServices())->wsagetpo($ponbrtampung,$supptampung,$kontraktampung);

        if($po_receipt === false){

            alert()->error('Error', 'WSA Failed');
            return redirect()->route('poreceipt.index');
        }else{
            if($po_receipt[1] == "false"){
                alert()->error('Error', 'Data PO tidak ditemukan');
                return redirect()->back();
            }else{
                $tempPO = (new CreateTempTable())->createPOTemp($po_receipt[0]);
            }
        }

        Session::put('allporeceipt', $tempPO[0]);
        
        
        return redirect()->route('showReceipt')->with(['tablepo' => $tempPO[1]]);
    }

    public function showReceipt(){
        // dd('showReceipt');
        // dd('aa');
        $po = Session::get('tablepo');

        $podetail = Session::get('allporeceipt');

        // $receiptdate = Session::get('receiptdate');

        // $errorcode = Session::get('errorcode');

        // $sessionpo = Session::get('sessionpo');

        // dd($sessionpo);


        // $loc = LocMstr::where('loc_domain',Session::get('domain'))->get();
        
        if(is_null($po)){
            alert()->error('Error', 'Silahkan Search Ulang')->persistent('Dismiss');

            return redirect()->route('poreceipt.index');
        }

        // if($errorcode === 1){
        //     alert()->error('Error', 'Nomor Polisi tidak boleh kosong')->persistent('Dismiss');
        //     return view('transaksi.poreceipt.view', compact('po','receiptdate','loc','sessionpo'));
        // }elseif($errorcode === 2){
        //     alert()->error('Error', 'Qxtend Error')->persistent('Dismiss');
        //     return view('transaksi.poreceipt.view', compact('po','receiptdate','loc','sessionpo'));
        // }elseif($errorcode === 3){
        //     alert()->error('Error', 'Terdapat masalah pada qxtend')->persistent('Dismiss');
        //     return view('transaksi.poreceipt.view', compact('po','receiptdate','loc','sessionpo'));
        // }

        return view('transaksi.poreceipt.view-browse', compact('po','podetail'));
    }

    public function edit($id){
        //check if domain changed
        if(Session::get('domain') != Session::get('dom_search')){
            Session::forget('dom_search');
            alert()->error('Error', 'Terjadi Perubahan Domain. Harap lakukan pencarian ulang sesuai domain saat ini.')->persistent('Dismiss');
            return redirect()->route('poreceipt.index');
        }
        // dd($id);
        // dd(Session::get('allporeceipt')->where('po_nbr','=',$id),Session::get('session_po'));
        if(!Session::get('allporeceipt')){
            alert()->error('Error', 'Silahkan Search Ulang')->persistent('Dismiss');

            return redirect()->route('poreceipt.index');
        }

        $po_receipt = (new WSAServices())->wsagetpo($id,'','');

        if($po_receipt === false){

            alert()->error('Error', 'WSA Failed');
            return redirect()->route('poreceipt.index');
        }else{
            if($po_receipt[1] == "false"){
                alert()->error('Error', 'Data PO tidak ditemukan');
                return redirect()->back();
            }else{
                $tempPO = (new CreateTempTable())->createPOTemp($po_receipt[0]);
                // dd($tempPO[0]);
            }
        }

        $receiptdetail = $tempPO[0];

        // $receiptdetail = Session::get('allporeceipt')->where('po_nbr','=',$id)->values()->all();
        // $receiptdetail = collect($receiptdetail);
        
        if($receiptdetail->count() == 0){
            alert()->error('Error', 'Silahkan Search Ulang')->persistent('Dismiss');

            return redirect()->route('poreceipt.index');
        }

        $sessionpo = Session::get('session_po');
        Session::forget('session_po');

        if(Session::get('errorcode')){
            // dd(Session::get('errorcode'));
            if(Session::get('errorcode') === 1){
                // dd('error code 1');
                Session::forget('errorcode');
                alert()->error('Error', 'Qxtend Error')->persistent('Dismiss');
                return view('transaksi.poreceipt.view', compact('receiptdetail','sessionpo'));
            }elseif(Session::get('errorcode') === 2){
                // dd('error code 2');
                Session::forget('errorcode');
                alert()->error('Error', 'Terdapat masalah pada qxtend')->persistent('Dismiss');
                return view('transaksi.poreceipt.view', compact('receiptdetail','sessionpo'));
            }elseif(Session::get('errorcode') === 3){
                // dd('error code 3');
                Session::forget('errorcode');
                alert()->error('Error', 'Terdapat masalah pada Database')->persistent('Dismiss');
                return view('transaksi.poreceipt.view', compact('receiptdetail','sessionpo'));
            }
            // elseif(Session::get('errorcode') === 3){
            //     // dd('error code 2');
            //     Session::forget('errorcode');
            //     alert()->error('Error', 'Domain berbeda. Silahkan ganti domain,')->persistent('Dismiss');
            //     return view('transaksi.poreceipt.view', compact('receiptdetail','sessionpo'));
            // }
        }

        return view('transaksi.poreceipt.view', compact('receiptdetail','sessionpo'));
    }

    public function toDetailPO($id,$supp,$cont){

    }

    public function submitReceipt(Request $req){
        $newrequest = $req->all();

        // if(Session::get('dom_search') != Session::get('domain')){
        //     $poSession = (new CreateTempTable())->createPOSessionTemp($newrequest);
        //     Session::put('session_po',$poSession);
        //     Session::put('errorcode',3);
        //     return redirect()->route('poreceipt.edit',$req->po_nbr);
        // }

        // dd($newrequest);
        // if(is_null($req->nopol)){
        //     alert()->error('Error', 'Nomor Polisi tidak boleh kosong')->persistent('Dismiss');
        //     $poSession = (new CreateTempTable())->createPOSessionTemp($newrequest);
        //     $remarkreceipt = $req->old('remarkreceipt');
        //     return redirect()->route('searchPO')->with(['ponbr' => $req->po_nbr,'errors'=>1,'session_po'=>$poSession]);
        // }

        $poreceipt_submit = (new QxtendServices())->submitreceipt($newrequest);
        
        if($poreceipt_submit === 'qxtend_err'){
            // alert()->error('Error', 'Qxtend Error')->persistent('Dismiss');
            // return redirect()->route('poreceipt.index');
            $poSession = (new CreateTempTable())->createPOSessionTemp($newrequest);
            Session::put('session_po',$poSession);
            Session::put('errorcode',1);
            return redirect()->route('poreceipt.edit',$req->po_nbr);
        }
        
        if($poreceipt_submit === false){
            // alert()->error('Error', 'Terdapat masalah pada qxtend')->persistent('Dismiss');
            // return redirect()->route('poreceipt.index');
            $poSession = (new CreateTempTable())->createPOSessionTemp($newrequest);
            Session::put('session_po',$poSession);
            Session::put('errorcode',2);
            return redirect()->route('poreceipt.edit',$req->po_nbr);
        }

        if($poreceipt_submit === 'db_err'){
            // alert()->error('Error', 'Qxtend Error')->persistent('Dismiss');
            // return redirect()->route('poreceipt.index');
            $poSession = (new CreateTempTable())->createPOSessionTemp($newrequest);
            Session::put('session_po',$poSession);
            Session::put('errorcode',3);
            return redirect()->route('poreceipt.edit',$req->po_nbr);
        }

        // $poSession = (new CreateTempTable())->createPOSessionTemp($newrequest);
        // Session::put('session_po',$poSession);

        //tommy punya

        alert()->success('Success', 'PO : '.$req->po_nbr.' dengan PO Contract : '.$req->po_kontrak.' berhasil di receipt')->persistent('Dismiss');
        // return redirect()->route('poreceipt.index');
        return redirect()->route('poreceipt.edit',$req->po_nbr);

    }
    
}
