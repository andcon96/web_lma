<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\CustMstr;
use App\Models\Master\Domain;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustMstrController extends Controller
{
    //

    public function index(Request $req){
        $cust = CustMstr::query();

        $custsearch = CustMstr::groupBy('cust_code')->select('cust_code','cust_name')->get();

        $lastrun = CustMstr::select('updated_at')->first();

        if($req->cust){
            $cust->where('cust_code',$req->cust);
        }

        $cust = $cust->orderByRaw('cust_dom,cust_code,cust_name')->paginate(10);

        return view('masterdata.customer.index', compact('cust','custsearch','lastrun'));
    }

    public function store(){
        $domains = Domain::get();

        foreach($domains as $datadomain){
            // dump($datadomain->domain_code);
            $custdata = (new WSAServices())->wsagetcust($datadomain->domain_code);

            if($custdata === false){
                alert()->error('Error', 'WSA Failed');
                return redirect()->back();
            }else{
    
                if($custdata[1] == "false"){
                    alert()->error('Error', 'Data Customer tidak ditemukan');
                    return redirect()->back();
                }else{
                    
                    foreach($custdata[0] as $datas){
                        $custdatas = CustMstr::firstOrNew(['cust_code'=>$datas->t_cmaddr,
                                                            'cust_dom'=>$datas->t_cmdom]);
                        
                        $custdatas->cust_dom = $datas->t_cmdom;
                        $custdatas->cust_code  = $datas->t_cmaddr;
                        $custdatas->cust_name = $datas->t_cmname;
                        $custdatas->cust_addr = $datas->t_addr1.' '.$datas->t_addr2.' '.$datas->t_addr3;
                        $custdatas->save();
             
                    }
    
                }
    
            }
        }

        alert()->success('Success','Data Customer berhasil diload');
        return redirect()->route('custmstr.index');

    }



}
