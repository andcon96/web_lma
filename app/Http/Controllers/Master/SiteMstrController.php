<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use App\Models\Master\SiteMstr;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteMstrController extends Controller
{
    //

    public function index(Request $req)
    {

        $site = SiteMstr::query();

        // $custsearch = SiteMstr::groupBy('cust_code')->select('cust_code','cust_name')->get();

        $lastrun = SiteMstr::select('updated_at')->first();

        // if($req->site){
        //     $site->where('cust_code',$req->cust);
        // }

        $site = $site->orderByRaw('site_domain,site_entity,site_site')->paginate(10);

        return view('masterdata.site.index', compact('site', 'lastrun'));
    }

    public function store()
    {

        $domains = Domain::get();

        // dd($domains);

        foreach ($domains as $datadomain) {
            // dump($datadomain->domain_code);
            $sitedata = (new WSAServices())->wsagetsite($datadomain->domain_code);

            if ($sitedata === false) {
                alert()->error('Error', 'WSA Failed');
                return redirect()->back();
            } else {

                if ($sitedata[1] == "false") {
                    alert()->error('Error', 'Data Site tidak ditemukan');
                    return redirect()->back();
                } else {
                   
                    foreach ($sitedata[0] as $datas) {
                        $sites = SiteMstr::firstOrNew(['site_site'=>$datas->t_site,
                                                       'site_domain'=> $datas->t_domain]);
                            $sites->site_domain  = $datas->t_domain;
                            $sites->site_entity = $datas->t_entity;
                            $sites->site_site = $datas->t_site;
                            $sites->save();
                    }
                }
            }
        }

        alert()->success('Success', 'Data Site berhasil diload');
        return redirect()->route('sitemstr.index');
    }
}
