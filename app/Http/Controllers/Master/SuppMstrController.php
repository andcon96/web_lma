<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use App\Models\Master\SuppMstr;
use App\Services\WSAServices;
use Illuminate\Http\Request;

class SuppMstrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        //
        $supp = SuppMstr::query();

        $suppsearch = SuppMstr::select('supp_code','supp_name')->get();

        $lastrun = SuppMstr::select('updated_at')->first();

        if($req->supp){
            $supp->where('supp_code',$req->supp);
        }

        $supp = $supp->orderByRaw('supp_dom,supp_code,supp_name')->paginate(10);

        return view('masterdata.customer.index', compact('supp','suppsearch','lastrun'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
        $domains = Domain::get();

        foreach($domains as $datadomain){
            // dump($datadomain->domain_code);
            $suppdata = (new WSAServices())->wsagetsupp($datadomain->domain_code);

            if($suppdata === false){
                alert()->error('Error', 'WSA Failed');
                return redirect()->back();
            }else{
    
                if($suppdata[1] == "false"){
                    alert()->error('Error', 'Data Customer tidak ditemukan');
                    return redirect()->back();
                }else{
                    
                    foreach($suppdata[0] as $datas){
                        $suppdatas = SuppMstr::firstOrNew(['supp_code'=>$datas->t_suppnbr,
                                                            'supp_dom'=>$datas->t_suppdom]);
                        
                        $suppdatas->supp_dom = $datas->t_suppdom;
                        $suppdatas->supp_code  = $datas->t_suppnbr;
                        $suppdatas->supp_name = $datas->t_suppname;
                        $suppdatas->save();
             
                    }
    
                }
    
            }
        }

        alert()->success('Success','Data Customer berhasil diload');
        return redirect()->route('custmstr.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
