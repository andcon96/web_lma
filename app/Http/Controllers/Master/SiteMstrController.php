<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\SiteMstr;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteMstrController extends Controller
{
    //

    public function index(Request $req){

        $site = SiteMstr::query();

        // $custsearch = SiteMstr::groupBy('cust_code')->select('cust_code','cust_name')->get();

        $lastrun = SiteMstr::select('created_at')->first();

        // if($req->site){
        //     $site->where('cust_code',$req->cust);
        // }

        $site = $site->paginate(10);

        return view('masterdata.site.index', compact('site','lastrun'));
    }

    public function store(){
        $sitedata = (new WSAServices())->wsagetsite();

        if($sitedata === false){
            alert()->error('Error', 'WSA Failed');
            return redirect()->back();
        }else{

            if($sitedata[1] == "false"){
                alert()->error('Error', 'Data Site tidak ditemukan');
                return redirect()->back();
            }else{
                SiteMstr::truncate();
                foreach($sitedata[0] as $datas){
    
                    DB::table('site_mstr')->insert([
                        'site_domain'  => $datas->t_domain,
                        'site_entity' => $datas->t_entity,
                        'site_site' => $datas->t_site,
                    ]);
                }
    
    
                alert()->success('Success','Data Site berhasil diload');
                return redirect()->route('sitemstr.index');
            }

        }

    }
}
