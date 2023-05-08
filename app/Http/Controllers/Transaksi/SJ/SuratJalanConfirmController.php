<?php

namespace App\Http\Controllers\Transaksi\SJ;

use App\Exports\SJExport;
use App\Http\Controllers\Controller;
use App\Models\Master\LocMstr;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use App\Services\CreateTempTable;
use App\Services\QxtendServices;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as Session;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Console\Input\Input;

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
        if($request->tanggalsj){
            $data->where('created_at','like', $request->tanggalsj.'%');
        }
        if($request->nopol){
            $data->where('sj_nopol',$request->nopol);
        }

        $data->where('sj_domain',Session::get('domain'));

        $data = $data->orderBy('created_at','DESC')->paginate(10);

        return view('transaksi.sjconfirm.index',compact('data','cust'));
    }

    public function edit($id){
        $data = SuratJalan::with(['getDetail','getDetailCust','getDetailShip','getDetailBill'])->findOrFail($id);

        $this->authorize('update', [SuratJalan::class, $data]);

        $listsjopen = SuratJalanDetail::with('getMaster')
            ->whereRelation('getMaster', 'sj_status', 'New')
            ->whereRelation('getMaster', 'sj_so_nbr', $data->sj_so_nbr)
            ->whereRelation('getMaster', 'sj_domain', Session::get('domain'))
            ->get();
        $listsjship = SuratJalanDetail::with('getMaster')
            ->whereRelation('getMaster', 'sj_status', 'Closed')
            ->whereRelation('getMaster', 'sj_so_nbr', $data->sj_so_nbr)
            ->whereRelation('getMaster', 'sj_domain', Session::get('domain'))
            ->get();

        $loc = LocMstr::where('loc_domain',Session::get('domain'))->get();

        $sonbr = $data->sj_so_nbr;
        $socust = $data->sj_so_cust;

        //harus ambil data qty ship so tersebut dari qad untuk perhitungan Qty Open
        $getso = (new WSAServices())->wsagetso($socust,$sonbr);
        if ($getso === false) {
            alert()->error('Error', 'WSA Failed')->persistent('Dismiss');
            return redirect()->route('sjconfirm.index');
        } else {
            if ($getso[1] == 'false') {
                alert()->error('Error', 'SO Number or Customer Not Found')->persistent('Dismiss');
                return redirect()->route('sjconfirm.index');
            }

            $tempPO = (new CreateTempTable())->createSOTemp($getso[0]);
        }

        $soqad = $tempPO[1]->first()->sod_qty_ship;
        
        
        // dd($listsj);
        return view('transaksi.sjconfirm.edit',compact('data','listsjopen','loc','listsjship','soqad'));
    }

    public function update(Request $request){
        $sendqxtend = (new QxtendServices())->qxSOShipment($request->all());
        if($sendqxtend === false){
            alert()->error('Error', 'Please check QAD connection')->persistent('Dismiss');
            return back()->withInput($request->only('qtyinp','partloc','potongdp','exkapal','exgudang','qtykarung','transportirname'));
        }elseif($sendqxtend === 'response_err'){
            alert()->error('Error', 'Please check SO location / qty')->persistent('Dismiss');
            return back()->withInput($request->only('qtyinp','partloc','potongdp','exkapal','exgudang','qtykarung','transportirname'));
        }elseif($sendqxtend === 'db_err'){
            alert()->error('Error', 'Please try again.')->persistent('Dismiss');
            return back()->withInput($request->only('qtyinp','partloc','potongdp','exkapal','exgudang','qtykarung','transportirname'));
        }else{
            $createNewLine = (new CreateTempTable())->createNewLineSO($request->all());
            if(count($createNewLine) > 0){
                $qxSOMT = (new QxtendServices())->qxSOMT($createNewLine);
                if($qxSOMT == 'false' || $qxSOMT === false){
                    alert()->error('Error', 'Shipment Berhasil, SO gagal diupdate untuk Qty Input yang tidak sama dengan Qty SJ')->persistent('Dismiss');
                    return back()->withInput($request->only('qtyinp','partloc','potongdp','exkapal','exgudang','qtykarung','transportirname'));
                }
            }
        }

        alert()->success('Success', 'Surat jalan Succesfully Shipped')->persistent('Dismiss');
        return redirect()->route('sjconfirm.index');
    }

    public function show($id){
        $data = SuratJalan::with('getDetail')->findOrFail($id);
        
        $this->authorize('view', [SuratJalan::class, $data]);

        return view('transaksi.sjconfirm.show',compact('data'));
    }

    public function sjtoexcel(Request $request){
        $h_sjnbr = $request->h_sjnbr;
        $h_sonbr = $request->h_sonbr;
        $h_customer = $request->h_customer;
        $h_status = $request->h_status;
        $h_tanggalsj = $request->h_tanggalsj;
        $h_nopol = $request->h_nopol;
        return Excel::download(new SJExport($h_sjnbr,$h_sonbr,$h_customer,$h_status,$h_tanggalsj,$h_nopol), 'suratjalan_'.date("Y_m_d_H:i:s").'.xlsx');
    }
}
