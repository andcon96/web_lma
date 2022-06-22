<?php

namespace App\Http\Controllers\Transaksi\PO;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\POhist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as Session;

class POBrowseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        //

        $datas = POhist::with('getUser.getRoleType');

        $supps = POhist::groupBy('ph_supp')->select('ph_supp','ph_suppname')->get();

        if($req->ponbr){
            // dump($req->ponbr);
            $datas->where('ph_ponbr','=',$req->ponbr);
        }
        if($req->supp){
            // dump($req->supp);
            $datas->where('ph_supp','=',$req->supp);
        }
        if($req->receiptdate){
            // dump($req->receiptdate);
            $datas->where('ph_receiptdate','=',$req->receiptdate);
        }
        if($req->pocon){
            // dump($req->pocon);
            $datas->where('ph_pokontrak','=',$req->pocon);
        }


        $datas = $datas/*->whereRelation('getUser.getRoleType','usertype', 'office')->orWhereRelation('getUser.getRoleType','usertype', 'notoffice')->WhereRelation('getUser.getRoleType','usertype','all')*/->orderBy('id','desc')->paginate(10);

        // dump($datas);
        return view('transaksi.porcpbrowse.index',compact('datas','supps'));
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
    public function store(Request $request)
    {
        //
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
