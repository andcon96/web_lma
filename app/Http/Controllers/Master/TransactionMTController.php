<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Transaction;
use Illuminate\Http\Request;

class TransactionMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Transaction::get();

        return view('setting.transaction.thistinput',['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Transaction = new Transaction();
        $Transaction = Transaction::firstOrNew(array('transaction_type' => $request->xtr_type));
        $Transaction->transaction_desc = $request->xtr_desc;
        $Transaction->save();

        Session()->flash('updated','Data Successfully Updated');
        return redirect()->route('transaction.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Transaction::findOrFail($request->temp_id)->delete();
        
        Session()->flash('updated','Data Succesfully Deleted');
        return redirect()->route('transaction.index');
    }
}
