<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\CustMstr;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustMstrController extends Controller
{
    //

    public function index(Request $req){
        $cust = CustMstr::query();

        $custsearch = CustMstr::groupBy('cust_code')->select('cust_code','cust_name')->get();

        $lastrun = CustMstr::select('created_at')->first();

        if($req->cust){
            $cust->where('cust_code',$req->cust);
        }

        $cust = $cust->paginate(10);

        return view('masterdata.customer.index', compact('cust','custsearch','lastrun'));
    }

    public function store(){
        $custdata = (new WSAServices())->wsagetcust();

        if($custdata === false){
            alert()->error('Error', 'WSA Failed');
            return redirect()->back();
        }else{

            if($custdata[1] == "false"){
                alert()->error('Error', 'Data Customer tidak ditemukan');
                return redirect()->back();
            }else{
                CustMstr::truncate();
                foreach($custdata[0] as $datas){
    
                    DB::table('cust_mstr')->insert([
                        'cust_code'  => $datas->t_cmaddr,
                        'cust_name' => $datas->t_cmname,
                        'cust_addr'   => $datas->t_addr1.' '.$datas->t_addr2.' '.$datas->t_addr3,
                    ]);
                }
    
    
                alert()->success('Success','Data Customer berhasil diload');
                return redirect()->route('custmstr.index');
            }

        }

    }



}
