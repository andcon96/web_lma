<?php

namespace App\Http\Controllers\Transaksi\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use App\Models\Transaksi\HutangCust;
use App\Services\WSAServices;
use Illuminate\Http\Request;

class HutangCustController extends Controller
{
    //

    public function index(Request $req){
        $hutangcust = HutangCust::query();

        $cust = HutangCust::groupBy('hutang_custnbr')->select('hutang_custnbr','hutang_cust')->get();

        $lastrun = HutangCust::select('created_at')->first();

        if($req->invoice_nbr){
            $hutangcust->where('hutang_invcnbr',$req->invoice_nbr);
        }
        if($req->cust){
            $hutangcust->where('hutang_custnbr',$req->cust);
        }


        $hutangcust = $hutangcust->orderby('hutang_invcdate','Desc')->orderBy('hutang_invcnbr','Desc')->paginate(10);
        
        return view('transaksi.report.hutangcustomer.index', compact('hutangcust','cust','lastrun'));
    }

    public function store(){

        $domains = Domain::get();

        foreach ($domains as $datadomain) {
            $hutangcust = (new WSAServices())->wsagethutang($datadomain->domain_code);

            if($hutangcust === false){
                alert()->error('Error', 'WSA Failed');
                return redirect()->back();
            }else{

                if($hutangcust[1] == "false"){
                    alert()->error('Error', 'Data Hutang Customer tidak ditemukan');
                    return redirect()->back();
                }else{
                    foreach($hutangcust[0] as $datas){
                        
                        $hutangs = HutangCust::firstOrNew(['hutangdom'=>$datas->t_dom,
                                                            'hutang_custnbr'=>$datas->t_custcode,
                                                            'hutang_invcnbr'=>$datas->t_invccode]);

                        $hutangs->hutang_cust = $datas->t_custname;
                        $hutangs->hutang_invcdate = $datas->t_invcdate;
                        $hutangs->hutang_amt = $datas->t_aropen;

                        $hutangs->save();
                    }
        
                }

            }
        }

        alert()->success('Success','Data berhasil diload');
        return redirect()->route('hutangcust.index');
        
    }
}
