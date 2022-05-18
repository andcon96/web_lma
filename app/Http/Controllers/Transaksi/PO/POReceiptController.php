<?php

namespace App\Http\Controllers\Transaksi\PO;

use App\Http\Controllers\Controller;
use App\Models\Master\LocMstr;
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
        return view('transaksi.poreceipt.index');
    }

    public function searchPO(Request $req){
        // Validasi Web
        $receiptdate = $req->receiptdate;
        $errorcode = Session::get('errors');
        $sessionpo = Session::get('session_po');

        if(is_null($req->sjnbr)){
            $ponbrtampung = Session::get('ponbr');

            // alert()->error('Error', 'PO tidak boleh kosong')->persistent('Dismiss');
            // return redirect()->back();
        }else{
            $ponbrtampung = $req->sjnbr;
        }

        // WSA QAD
        $po_receipt = (new WSAServices())->wsagetpo($ponbrtampung);

        if($po_receipt === false){
            alert()->error('Error', 'WSA Failed');
            return redirect()->route('poreceipt.index');
        }else{
            if($po_receipt[1] == "false"){
                alert()->error('Error', 'PO Contract tidak ditemukan');
                return redirect()->back();
            }else{
                $tempPO = (new CreateTempTable())->createPOTemp($po_receipt[0]);
            }
        }

        Session::put('allporeceipt', $tempPO[0]);
        
        
        return redirect()->route('showReceipt')->with(['tablepo' => $tempPO[1],'receiptdate'=> $receiptdate,'errorcode'=>$errorcode, 'sessionpo'=>$sessionpo]);
    }

    public function showReceipt(){
        // dd('aa');
        $po = Session::get('tablepo');

        $receiptdate = Session::get('receiptdate');

        $errorcode = Session::get('errorcode');

        $sessionpo = Session::get('sessionpo');

        // dd($sessionpo);


        $loc = LocMstr::where('loc_domain',Session::get('domain'))->get();
        
        if(is_null($po)){
            alert()->error('Error', 'Silahkan Search Ulang')->persistent('Dismiss');

            return redirect()->route('poreceipt.index');
        }

        if($errorcode === 1){
            alert()->error('Error', 'Nomor Polisi tidak boleh kosong')->persistent('Dismiss');
            return view('transaksi.poreceipt.view', compact('po','receiptdate','loc','sessionpo'));
        }elseif($errorcode === 2){
            alert()->error('Error', 'Qxtend Error')->persistent('Dismiss');
            return view('transaksi.poreceipt.view', compact('po','receiptdate','loc','sessionpo'));
        }elseif($errorcode === 3){
            alert()->error('Error', 'Terdapat masalah pada qxtend')->persistent('Dismiss');
            return view('transaksi.poreceipt.view', compact('po','receiptdate','loc','sessionpo'));
        }

        return view('transaksi.poreceipt.view-browse', compact('po'));
    }

    public function edit($id){
        // dd($id);
        // dd(Session::get('allporeceipt')->where('po_nbr','=',$id),Session::get('session_po'));
        if(!Session::get('allporeceipt')){
            alert()->error('Error', 'Silahkan Search Ulang')->persistent('Dismiss');

            return redirect()->route('poreceipt.index');
        }

        $receiptdetail = Session::get('allporeceipt')->where('po_nbr','=',$id)->values()->all();
        $receiptdetail = collect($receiptdetail);
        
        if($receiptdetail->count() == 0){
            alert()->error('Error', 'Silahkan Search Ulang')->persistent('Dismiss');

            return redirect()->route('poreceipt.index');
        }

        if(Session::get('errorcode')){
            if(Session::get('errorcode' == 1)){
                dd('error code 1');
            }elseif(Session::get('errorcode' == 2)){
                dd('error code 2');
            }
        }

        Session::forget('errorcode');
        
        // dd($receiptdetail);

        $sessionpo = Session::get('session_po');
        Session::forget('session_po');
        return view('transaksi.poreceipt.view', compact('receiptdetail','sessionpo'));
    }

    public function submitReceipt(Request $req){
        $newrequest = $req->all();

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


        alert()->success('Success', 'PO : '.$req->po_nbr.' dengan PO Contract : '.$req->po_kontrak.' berhasil di receipt')->persistent('Dismiss');
        return redirect()->route('poreceipt.index');

    }
    
}
