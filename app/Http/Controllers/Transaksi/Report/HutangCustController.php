<?php

namespace App\Http\Controllers\Transaksi\Report;

use App\Http\Controllers\Controller;
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
        $hutangcust = (new WSAServices())->wsagethutang();

        if($hutangcust === false){
            alert()->error('Error', 'WSA Failed');
            return redirect()->back();
        }else{

            if($hutangcust[1] == "false"){
                alert()->error('Error', 'Data Hutang Customer tidak ditemukan');
                return redirect()->back();
            }else{
                HutangCust::truncate();
                foreach($hutangcust[0] as $datas){
                    HutangCust::insert([
                        'hutang_invcnbr'  => $datas->t_invccode,
                        'hutang_custnbr' => $datas->t_custcode,
                        'hutang_cust'   => $datas->t_custname,
                        'hutang_invcdate' => $datas->t_invcdate,
                        'hutang_amt' => $datas->t_aropen
                    ]);
                }
    
    
                alert()->success('Success','Data berhasil diload');
                return redirect()->route('hutangcust.index');
            }

        }
    }
}
