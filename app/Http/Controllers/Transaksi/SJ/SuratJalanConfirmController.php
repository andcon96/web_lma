<?php

namespace App\Http\Controllers\Transaksi\SJ;

use App\Http\Controllers\Controller;
use App\Models\Master\LocMstr;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use App\Services\QxtendServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as Session;

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


        $listsjopen = SuratJalanDetail::with('getMaster')
            ->whereRelation('getMaster', 'sj_status', 'New')
            ->whereRelation('getMaster', 'sj_so_nbr', $data->sj_so_nbr)
            ->get();
        $listsjship = SuratJalanDetail::with('getMaster')
            ->whereRelation('getMaster', 'sj_status', 'Closed')
            ->whereRelation('getMaster', 'sj_so_nbr', $data->sj_so_nbr)
            ->get();

        $loc = LocMstr::where('loc_domain',Session::get('domain'))->get();
        
        
        // dd($listsj);
        return view('transaksi.sjconfirm.edit',compact('data','listsjopen','loc','listsjship'));
    }

    public function update(Request $request){
        $sendqxtend = (new QxtendServices())->qxSOShipment($request->all());
        if($sendqxtend === false){
            alert()->error('Error', 'Failed to Ship SJ')->persistent('Dismiss');
            return back();
        }

        alert()->success('Success', 'Surat jalan Succesfully Shipped')->persistent('Dismiss');
        return redirect()->route('sjconfirm.index');
    }

    public function show($id){
        $data = SuratJalan::with('getDetail')->findOrFail($id);

        return view('transaksi.sjconfirm.show',compact('data'));
    }
}
