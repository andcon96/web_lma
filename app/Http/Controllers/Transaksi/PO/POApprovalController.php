<?php

namespace App\Http\Controllers\Transaksi\PO;

use App\Http\Controllers\Controller;
use App\Jobs\EmailPOInvcApproval;
use App\Models\Master\PoInvcEmail;
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
        if($po_invoice[1] == "false"){
            alert()->error('Error', 'PO tidak ditemukan');
            return redirect()->back();
        }else{
            $tempPO = (new CreateTempTable())->createPOInvcTemp($po_invoice[0]);
        }
        
        return redirect()->route('showInvoice')->with(['tablepo' => $tempPO]);
    }

    public function showInvoice(){
        $poinvoice = Session::get('tablepo');
        
        if(is_null($poinvoice)){
            alert()->error('Error', 'Silahkan Search Ulang');
            return redirect()->route('poapproval.index');
        }
        
        return view('transaksi.poapproval.view', compact('poinvoice'));
    }

    public function sendMailApproval(Request $req){
        // dd($req->all());
        $i = 1;
        $sendmail = $req->sendmail;

        $emailto = PoInvcEmail::first();

        foreach($sendmail as $key => $counsend){

            $pesan = 'New PO Invoice Approval';
            $ponbr = $req->ponbr[$key];
            $invcnbr = $req->invoice_nbr[$key];
            $invcamt = $req->invoice_amt[$key];
            $penerima = $emailto->name_invc;
            $alamatemail = $emailto->email_invc;

            EmailPOInvcApproval::dispatch(
                $pesan,
                $ponbr,
                $invcnbr,
                $invcamt,
                $penerima,
                $alamatemail
            );

            DB::table('email_hist')->insert([
                'eh_ponbr' => $req->ponbr[$key],
                'eh_invcnbr' => $req->invoice_nbr[$key],
            ]);

        }

        alert()->success('Success', 'Email PO Invoice Approval Berhasil Dikirim');
        return redirect()->route('poapproval.index');

        // $countemail = count($req->sendmail);

        // dd($countemail);
    }
}
