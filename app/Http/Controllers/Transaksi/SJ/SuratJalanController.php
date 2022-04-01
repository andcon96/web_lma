<?php

namespace App\Http\Controllers\Transaksi\SJ;

use App\Http\Controllers\Controller;
use App\Models\Master\Prefix;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use App\Services\CreateTempTable;
use App\Services\WSAServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SuratJalanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = SuratJalan::paginate(10);

        return view('transaksi.suratjalan.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $so = Session::get('tableso');
        
        if(is_null($so)){
            alert()->error('Error', 'Silahkan Search Ulang');
            return view('transaksi.suratjalan.index');
        }
        
        return view('transaksi.suratjalan.create', compact('so'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all()); 
        DB::beginTransaction();
        try{
            $prefix = Prefix::firstOrFail();
            $newrn = str_pad($prefix->rn_sj + 1,6,'0',STR_PAD_LEFT);
            $newprefix = $prefix->prefix_sj . $newrn;

            $sj_mstr = New SuratJalan();
            $sj_mstr->sj_nbr = $newprefix;
            $sj_mstr->sj_so_nbr = $request->sonbr;
            $sj_mstr->sj_so_cust = $request->customer;
            $sj_mstr->sj_so_ship = $request->shipto;
            $sj_mstr->sj_so_bill = $request->billto;
            $sj_mstr->sj_status = 'New';
            $sj_mstr->save();

            $id = $sj_mstr->id;
            foreach($request->sodline as $key => $datas){
                $sj_dets = New SuratJalanDetail();
                $sj_dets->sj_mstr_id = $id;
                $sj_dets->sj_line = $datas;
                $sj_dets->sj_part = $request->sodpart[$key];
                $sj_dets->sj_part_desc = $request->soddesc[$key];
                $sj_dets->sj_qty_ord = $request->sodqtyord[$key];
                $sj_dets->sj_qty_ship = $request->sodqtyship[$key];
                $sj_dets->sj_qty_input = $request->qtyinput[$key];
                $sj_dets->save();
            }

            $prefix->rn_sj = $newrn;
            $prefix->save();

            DB::commit();
            alert()->success('Success', 'Surat jalan Created, SJ Number : '.$newprefix);
            return redirect()->route('suratjalan.index');
        }catch(Exception $err){
            DB::rollBack();
            alert()->error('Error', 'Failed to Create SJ');
            return redirect()->route('suratjalan.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi\SuratJalan  $suratJalan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SuratJalan $suratJalan)
    {
        DB::beginTransaction();
        try{
            foreach($request->iddetail as $key => $datas){
                $detail = SuratJalanDetail::findOrFail($datas);
                if($request->operation[$key] == 'R'){
                    $detail->delete();
                }else{
                    $detail->sj_qty_input = $request->qtyinp[$key];
                    $detail->save();
                }
            }
            DB::commit();
            alert()->success('Success', 'Surat jalan Updated');
            return back();
        }catch(Exception $e){
            DB::rollBack();
            alert()->error('Error', 'Failed to Update SJ');
            return redirect()->route('browseSJ');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi\SuratJalan  $suratJalan
     * @return \Illuminate\Http\Response
     */
    public function destroy(SuratJalan $suratJalan)
    {
        //
    }

    public function searchSO(Request $request){
        $getso = (new WSAServices())->wsagetso($request->sjnbr);
        if($getso === false){
            alert()->error('Error', 'WSA Failed');
            return redirect()->route('suratjalan.index');
        }else{
            if($getso[1] == 'false'){
                alert()->error('Error', 'SO Number : '.$request->sjnbr.' Not Found');
                return redirect()->route('suratjalan.index');
            }
            $tempPO = (new CreateTempTable())->createSOTemp($getso[0]);
        }

        return redirect()->route('suratjalan.create')->with(['tableso' => $tempPO]);
    }

    public function browsesj(Request $request){
        $data = SuratJalan::query();
        $cust = SuratJalan::groupBy('sj_so_cust')->select('sj_so_cust')->get();
        
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

        $data = $data->orderBy('created_at','Desc')->paginate(10);

        return view('transaksi.suratjalan.browse',compact('data','cust'));
    }

    public function editjsbrowse($id){
        $data = SuratJalan::with('getDetail')->findOrFail($id);

        return view('transaksi.suratjalan.edit-browse',compact('data'));
    }

    public function viewjsbrowse($id){
        $data = SuratJalan::with('getDetail')->findOrFail($id);

        return view('transaksi.suratjalan.show-browse',compact('data'));
    }

    public function deletejsbrowse($id){
        $data = SuratJalan::with('getDetail')->findOrFail($id);
        $data->sj_status = 'Cancelled';
        $data->save();

        alert()->success('Success', 'SJ Cancelled');
        return back();
    }
}
