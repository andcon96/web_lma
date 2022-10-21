<?php

namespace App\Http\Controllers\Transaksi\PO;

use App\Http\Controllers\Controller;
use App\Jobs\EmailPOInvcApproval;
use App\Models\Master\PoInvcEmail;
use App\Models\Transaksi\POInvc;
use App\Models\Transaksi\POInvcApprHist;
use App\Services\CreateTempTable;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class POApprovalController extends Controller
{
    //
    public function index(){
        return view('transaksi.poapproval.index');
    }

    public function searchpoinvc(Request $req){
        if(is_null($req->ponbr)){
            alert()->error('Error', 'PO tidak boleh kosong');
            return redirect()->back();
        }

        $po_invoice = (new WSAServices())->getpoinvoice($req->ponbr);

        if($po_invoice === false){
            alert()->error('Error', 'WSA Failed');
            return redirect()->route('poapproval.index');
        }else{
            if($po_invoice[1] == "false"){
                alert()->error('Error','PO tidak ditemukan');
                return redirect()->back();
            }else{
                $tempPO = (new CreateTempTable())->createPOInvcTemp($po_invoice[0]);
            }
        }
        
        return redirect()->route('showInvoice')->with(['tablepo' => $tempPO]);
    }

    public function showInvoice(){
        $poinvoice = Session::get('tablepo');
        $statusappr = POInvcApprHist::get();
        
        if(is_null($poinvoice)){
            alert()->error('Error', 'Silahkan Search Ulang');
            return redirect()->route('poapproval.index');
        }
        
        return view('transaksi.poapproval.view', compact('poinvoice','statusappr'));
    }

    public function sendMailApproval(Request $req){
        // dd($req->all());
        $i = 1;
        $sendmail = $req->sendmail;

        if(is_null($req->sendmail)){
            alert()->error('Error','Kirim email tidak boleh kosong, pilih minimal satu');
            return redirect()->route('poapproval.index');
        }

        $emailto = PoInvcEmail::first();
        // dd($emailto);
        if(is_null($emailto)){
            alert()->error('Error','Harap setting terlebih dahulu email untuk Approver dan Receiver di PO Invoice Email Control');
            return redirect()->route('poapproval.index');
        }

        if($emailto->email_invc == "" && $emailto->email_receiver == ""){
            alert()->error('Error','Harap setting terlebih dahulu email untuk Approver dan Receiver di PO Invoice Email Control');
            return redirect()->route('poapproval.index');
        }

        foreach($req->hide_check as $key => $v){
            // dump($req->hide_check[$key]);
            if($v != 'R'){
                
                

                    $pesan = 'New PO Invoice Approval';
                    $ponbr = $req->ponbr[$key];
                    $supplier = $req->supp[$key];
                    $posdate = $req->posting_date[$key];
                    $invcnbr = $req->invoice_nbr[$key];
                    $invcamt = $req->invoice_amt[$key];
                    $penerima = $emailto->name_invc;
                    $alamatemail = $emailto->email_invc;
        
                    EmailPOInvcApproval::dispatch(
                        $pesan,
                        $ponbr,
                        $supplier,
                        $posdate,
                        $invcnbr,
                        $invcamt,
                        $penerima,
                        $alamatemail
                    );
                    
                    $newdata = POInvc::where('eh_ponbr','=',$ponbr)->where('eh_invcnbr','=',$invcnbr)->first();

                    if(!$newdata){
                        POInvc::insert([
                            'eh_ponbr' => $req->ponbr[$key],
                            'eh_invcnbr' => $req->invoice_nbr[$key],
                        ]);
                    }
                
            }
                    
        }

        // dd('stop');

        alert()->success('Success', 'Email PO Invoice Approval Berhasil Dikirim');
        return redirect()->route('poapproval.index');

        // $countemail = count($req->sendmail);

        // dd($countemail);
    }

    public function browseHistSent(Request $req){
        
        $datasentlist = POInvc::query();

        if($req->ponbr){
            $datasentlist->where('eh_ponbr','=',$req->ponbr);
        }

        if($req->invno){
            $datasentlist->where('eh_invcnbr','=',$req->invno);
        }


        $datasentlist = $datasentlist->orderBy('id','desc')->paginate(10);

        return view('transaksi.poapproval.browsehist_sentmail',compact('datasentlist'));
    }
}
