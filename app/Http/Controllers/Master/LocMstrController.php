<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use App\Models\Master\LocMstr;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocMstrController extends Controller
{
    //

    public function index(Request $req)
    {
        $loc = LocMstr::query();

        $locsearch = LocMstr::groupBy('loc')->select('loc', 'loc_desc')->get();

        $lastrun = LocMstr::select('created_at')->first();

        if ($req->loc) {
            $loc->where('loc', $req->loc);
        }

        $loc = $loc->orderByRaw('loc_domain,loc,loc_site')->paginate(10);

        return view('masterdata.location.index', compact('loc', 'locsearch', 'lastrun'));
    }

    public function store()
    {
        $domains = Domain::get();

        foreach ($domains as $datadomain) {

            $locdata = (new WSAServices())->wsagetloc($datadomain->domain_code);

            if ($locdata === false) {
                alert()->error('Error', 'WSA Failed');
                return redirect()->back();
            } else {

                if ($locdata[1] == "false") {
                    alert()->error('Error', 'Data Location tidak ditemukan');
                    return redirect()->back();
                } else {
                    
                    foreach ($locdata[0] as $datas) {
                        $locs = LocMstr::firstOrNew(['loc'=>$datas->t_loc,
                                                     'loc_domain'=>$datas->t_domain]);
                        
                            $locs->loc_domain = $datas->t_domain;
                            $locs->loc  = $datas->t_loc;
                            $locs->loc_desc = $datas->t_locdesc;
                            $locs->loc_site = $datas->t_locsite;
                            $locs->loc_type = $datas->t_loctype;
                            $locs->save();
                        
                    }
                }
            }
        }

        alert()->success('Success', 'Data Location berhasil diload');
        return redirect()->route('locmstr.index');
    }
}
