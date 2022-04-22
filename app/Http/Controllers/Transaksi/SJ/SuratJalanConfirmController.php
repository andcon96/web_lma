<?php

namespace App\Http\Controllers\Transaksi\SJ;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use App\Services\QxtendServices;
use Illuminate\Http\Request;

class SuratJalanConfirmController extends Controller
{
    public function index(Request $request){
        $data = SuratJalan::with(['getDetailCust','getDetailShip','getDetailBill']);
        
        $cust = SuratJalan::with('getDetailCust')->groupBy('sj_so_cust')->get();
        
        if($request->sjnbr){
            $data->where('sj_nbr',$request->sjnbr);
        }
        if($request->sonbr){
            $data->where('sj_so_nbr',$request->sonbr);
        }
        if($request->cust){
            $data->where('sj_so_cust',$request->cust);
        }
        if($request->status){
            $data->where('sj_status',$request->status);
        }

        $data = $data->orderBy('created_at','DESC')->paginate(10);

        return view('transaksi.sjconfirm.index',compact('data','cust'));
    }

    public function edit($id){
        $data = SuratJalan::with(['getDetail','getDetailCust','getDetailShip','getDetailBill'])->findOrFail($id);

        $listsjopen = SuratJalanDetail::with('getMaster')->whereRelation('getMaster','sj_status','New')->get();
        
        
        // dd($listsj);
        return view('transaksi.sjconfirm.edit',compact('data','listsjopen'));
    }

    public function update(Request $request){
        // dd($request->all());
        
        
        $sendqxtend = (new QxtendServices())->qxSOShipment($request->all());
        if($sendqxtend === false){
            alert()->error('Error', 'Failed to Ship SJ');
            return back();
        }

        alert()->success('Success', 'Surat jalan Succesfully Shipped');
        return redirect()->route('sjconfirm.index');
    }

    public function show($id){
        $data = SuratJalan::with('getDetail')->findOrFail($id);

        return view('transaksi.sjconfirm.show',compact('data'));
    }
}
