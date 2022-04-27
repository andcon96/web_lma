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

        if(is_null($req->sjnbr)){
            alert()->error('Error', 'PO tidak boleh kosong')->persistent('Dismiss');
            return redirect()->back();
        }

        // WSA QAD
        $po_receipt = (new WSAServices())->wsagetpo($req->sjnbr);

        if($po_receipt === false){
            alert()->error('Error', 'WSA Failed');
            return redirect()->route('poreceipt.index');
        }else{
            if($po_receipt[1] == "false"){
                alert()->error('Error', 'PO tidak ditemukan');
                return redirect()->back();
            }else{
                $tempPO = (new CreateTempTable())->createPOTemp($po_receipt[0]);
            }
        }
        
        
        return redirect()->route('showReceipt')->with(['tablepo' => $tempPO,'receiptdate'=> $receiptdate]);
    }

    public function showReceipt(){
        $po = Session::get('tablepo');

        $receiptdate = Session::get('receiptdate');

        $loc = LocMstr::where('loc_domain',Session::get('domain'))->get();
        
        if(is_null($po)){
            alert()->error('Error', 'Silahkan Search Ulang')->persistent('Dismiss');
            // return view('transaksi.poreceipt.index');
            return redirect()->route('poreceipt.index');
        }
        
        return view('transaksi.poreceipt.view', compact('po','receiptdate','loc'));
    }

    public function submitReceipt(Request $req){
        $newrequest = $req->all();

        // dd($newrequest);
        if(is_null($req->nopol)){
            alert()->error('Error', 'Nomor Polisi tidak boleh kosong')->persistent('Dismiss');
            return redirect()->route('poreceipt.index');
        }

        $poreceipt_submit = (new QxtendServices())->submitreceipt($newrequest);
        if($poreceipt_submit === 'qxtend_err'){
            alert()->error('Error', 'Qxtend Error')->persistent('Dismiss');
            return redirect()->route('poreceipt.index');
        }
        
        if($poreceipt_submit === false){
            alert()->error('Error', 'Terdapat masalah pada qxtend')->persistent('Dismiss');
            return redirect()->route('poreceipt.index');
        }


        alert()->success('Success', 'PO berhasil di receipt')->persistent('Dismiss');;
        return redirect()->route('poreceipt.index');

    }
    
}
