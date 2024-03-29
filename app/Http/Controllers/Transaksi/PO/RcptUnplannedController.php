<?php

namespace App\Http\Controllers\Transaksi\PO;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\RcptUnplanned;
use App\Services\QxtendServices;
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

        $rcpt = RcptUnplanned::where('status','=','Open');

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

        $allreq = $request->all();

        $unplanned_submit = (new QxtendServices())->qxRcptUnplanned($allreq);
        
        if($unplanned_submit === 'qxtend_err'){
            alert()->error('Error', 'Qxtend Error')->persistent('Dismiss');
            return redirect()->route('rcptunplanned.index');
        }
        
        if($unplanned_submit === false){
            alert()->error('Error', 'Terdapat masalah pada qxtend')->persistent('Dismiss');
            return redirect()->route('rcptunplanned.index');
        }

        if($unplanned_submit === 'db_err'){
            alert()->error('Error', 'Terdapat masalah pada database')->persistent('Dismiss');
            return redirect()->route('rcptunplanned.index');
        }

        alert()->success('Success', 'PO : '.$request->po_nbr.' dengan PO Contract : '.$request->po_kontrak.' berhasil receipt unplanned')->persistent('Dismiss');
        return redirect()->route('rcptunplanned.index');
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

        $this->authorize('view', [RcptUnplanned::class, $detaildata]);

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
