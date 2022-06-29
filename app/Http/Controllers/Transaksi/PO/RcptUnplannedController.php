<?php

namespace App\Http\Controllers\Transaksi\PO;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\RcptUnplanned;
use Illuminate\Http\Request;

class RcptUnplannedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        $rcpt = RcptUnplanned::query();

        $supp = RcptUnplanned::groupBy('supp')->select('supp','suppname')->get();

        if ($request->ponbr) {
            $rcpt->where('ponbr', '=', $request->ponbr);
        }
        if ($request->supp) {
            $rcpt->where('supp', '=', $request->supp);
        }
        if ($request->receiptdate) {
            $rcpt->where('receiptdate', '=', $request->receiptdate);
        }
        if ($request->pocon) {
            $rcpt->where('pokontrak', '=', $request->pocon);
        }

        $rcpt = $rcpt->orderBy('id', 'desc')->paginate(10);

        return view('transaksi.receipt_unplanned.index',compact('rcpt','supp'));
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

        $detaildata = RcptUnplanned::where('id',$id)->first();

        return view('transaksi.receipt_unplanned.detail', compact('detaildata'));
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
