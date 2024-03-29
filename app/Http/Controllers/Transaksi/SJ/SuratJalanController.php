<?php

namespace App\Http\Controllers\Transaksi\SJ;

use App\Exports\SJExport;
use App\Http\Controllers\Controller;
use App\Models\Master\CustMstr;
use App\Models\Master\Domain;
use App\Models\Master\LocMstr;
use App\Models\Master\Prefix;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use App\Services\CreateTempTable;
use App\Services\WSAServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

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

        $custdat = CustMstr::where('cust_dom','=',Session::get('domain'))->get();

        return view('transaksi.suratjalan.index', compact('custdat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $so = Session::get('tableso');

        if (is_null($so)) {
            alert()->error('Error', 'Silahkan Search Ulang')->persistent('Dismiss');
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
    {   //return back()->withInput($request->only('nopol','exkapal','exgudang','qtykarung','transportirname'));
        DB::beginTransaction();
        try {
            $newprefix = (new CreateTempTable())->getRNSJ();
            
            $sj_mstr = new SuratJalan();
            $sj_mstr->sj_nbr = $newprefix[0];
            $sj_mstr->sj_so_nbr = $request->sonbr;
            $sj_mstr->sj_so_cust = $request->customer;
            $sj_mstr->sj_so_ship = $request->shipto;
            $sj_mstr->sj_so_bill = $request->billto;
            $sj_mstr->sj_status = 'New';
            $sj_mstr->sj_nopol = $request->nopol;
            $sj_mstr->sj_so_po = $request->sopo;
            $sj_mstr->sj_domain = Session::get('domain');
            $sj_mstr->sj_exkapal = $request->exkapal;
            $sj_mstr->sj_exgudang = $request->exgudang;
            $sj_mstr->sj_qtykarung = $request->qtykarung;
            $sj_mstr->sj_transportir_name = $request->transportirname;
            $sj_mstr->save();

            $id = $sj_mstr->id;
            foreach ($request->sodline as $key => $datas) {
                // if($request->qtyinput[$key] > 0 ){ /* dimatiin biar bisa qty input nya minus */
                    $sj_dets = new SuratJalanDetail();
                    $sj_dets->sj_mstr_id = $id;
                    $sj_dets->sj_line = $datas;
                    $sj_dets->sj_part = $request->sodpart[$key];
                    $sj_dets->sj_part_desc = $request->soddesc[$key];
                    $sj_dets->sj_loc = $request->partloc[$key];
                    $sj_dets->sj_lot = $request->lot[$key];
                    $sj_dets->sj_qty_ord = $request->sodqtyord[$key];
                    $sj_dets->sj_qty_ship = $request->sodqtyship[$key];
                    $sj_dets->sj_qty_input = $request->qtyinput[$key];
                    $sj_dets->sj_price_ls = $request->sodpricels[$key];
                    $sj_dets->save();
                // }else{ /* dimatiin biar bisa qty input nya minus */
                //     DB::rollBack();

                //     alert()->error('Error', 'Failed submit, Qty tidak bisa 0')->persistent('Dismiss');
                //     return back()->withInput($request->only('nopol','exkapal','exgudang','qtykarung','transportirname'));
                // } 
            }

            $domain = Domain::where('domain_code',Session::get('domain'))->firstOrFail();
            $domain->domain_sj_rn = $newprefix[1];
            $domain->save();
            
            DB::commit();
            alert()->success('Success', 'Surat jalan Created, SJ Number : ' . $newprefix[0])->persistent('Dismiss');
            return redirect()->route('suratjalan.edit',$request->sonbr);
            // return redirect()->route('suratjalan.index');
        } catch (Exception $err) {
            DB::rollBack();
            Log::channel('shipment')->info($err);
            alert()->error('Error', 'Failed to Create SJ')->persistent('Dismiss');
            return redirect()->route('suratjalan.edit',$request->sonbr);
            // return redirect()->route('suratjalan.index');
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
        try {
            $master = SuratJalan::findOrFail($request->idmaster);
            $master->sj_nopol = $request->nopol;
            $master->sj_exkapal = $request->exkapal;
            $master->sj_exgudang = $request->exgudang;
            $master->sj_qtykarung = $request->qtykarung;
            $master->sj_transportir_name = $request->transportirname;
            $master->save();

            foreach ($request->iddetail as $key => $datas) {
                $detail = SuratJalanDetail::findOrFail($datas);
                if ($request->operation[$key] == 'R') {
                    $detail->delete();
                } else {
                    $detail->sj_qty_input = $request->qtyinp[$key];
                    $detail->sj_loc = $request->partloc[$key];
                    $detail->sj_lot = $request->lot[$key];
                    $detail->save();
                }
            }
            DB::commit();
            alert()->success('Success', 'Surat jalan Updated')->persistent('Dismiss');
            return back();
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Error', 'Failed to Update SJ')->persistent('Dismiss');
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

    public function searchSO(Request $request)
    {
        if(is_null($request->sjnbr) && is_null($request->sonbr)){
            $socust = $request->sjnbr;
            $sonbr = $request->sonbr;
            // dd($ponbrtampung);
            // alert()->error('Error', 'PO tidak boleh kosong')->persistent('Dismiss');
            // return redirect()->back();
        }else{
            $socust = $request->sjnbr;
            $sonbr = $request->sonbr;
        }

        //tommy punya

        $getso = (new WSAServices())->wsagetso($socust,$sonbr);
        if ($getso === false) {
            alert()->error('Error', 'WSA Failed')->persistent('Dismiss');
            return redirect()->route('suratjalan.index');
        } else {
            if ($getso[1] == 'false') {
                alert()->error('Error', 'SO Number or Customer Not Found')->persistent('Dismiss');
                return redirect()->route('suratjalan.index');
            }
            $tempPO = (new CreateTempTable())->createSOTemp($getso[0]);
        }

        session::put('allso', $tempPO[0]);

        return redirect()->route('createBrowse')->with(['tableso' => $tempPO[1]]);
    }

    public function createBrowse(Request $req){
        $so = Session::get('tableso');
        $sodetail = Session::get('allso');

        if(is_null($so)){
            alert()->error('Error', 'Silahkan Search Ulang')->persistent('Dismiss');

            return redirect()->route('suratjalan.index');
        }

        return view('transaksi.suratjalan.view-browse', compact('so','sodetail'));
        
    }

    public function edit($id){
        // if(!Session::get('allso')){
        //     alert()->error('Error', 'Silahkan Search Ulang')->persistent('Dismiss');

        //     return redirect()->route('suratjalan.index');
        // }

        $getso = (new WSAServices())->wsagetso('',$id);

        if ($getso === false) {
            alert()->error('Error', 'WSA Failed')->persistent('Dismiss');
            return redirect()->route('suratjalan.index');
        } else {
            if ($getso[1] == 'false') {
                alert()->error('Error', 'SO Number or Customer Not Found')->persistent('Dismiss');
                return redirect()->route('suratjalan.index');
            }
            $tempPO = (new CreateTempTable())->createSOTemp($getso[0]);
        }

        $so = $tempPO[0];
        // $so = collect($so);
        
        if($so->count() == 0){
            alert()->error('Error', 'Silahkan Search Ulang')->persistent('Dismiss');

            return redirect()->route('suratjalan.index');
        }

        $loc = LocMstr::where('loc_domain',Session::get('domain'))->get();

        return view('transaksi.suratjalan.create', compact('so','loc'));
    }

    public function browsesj(Request $request)
    {
        $data = SuratJalan::with('getDetailCust', 'getDetailShip', 'getDetailBill');
        $cust = SuratJalan::with('getDetailCust')->groupBy('sj_so_cust')->get();

        if ($request->sjnbr) {
            $data->where('sj_nbr', $request->sjnbr);
        }
        if ($request->sonbr) {
            $data->where('sj_so_nbr', $request->sonbr);
        }
        if ($request->cust) {
            $data->where('sj_so_cust', $request->cust);
        }
        if ($request->status) {
            $data->where('sj_status', $request->status);
        }
        if($request->tanggalsj){
            $data->where('created_at','like', $request->tanggalsj.'%');
        }
        if ($request->nopol){
            $data->where('sj_nopol', $request->nopol);
        }

        $data->where('sj_domain',Session::get('domain'));

        $data = $data->orderBy('created_at', 'Desc')->paginate(10);

        return view('transaksi.suratjalan.browse', compact('data', 'cust'));
    }

    public function editjsbrowse($id)
    {
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

        $sonbr = $data->sj_so_nbr;
        $socust = $data->sj_so_cust;

        //harus ambil data qty ship so tersebut dari qad untuk perhitungan Qty Open
        $getso = (new WSAServices())->wsagetso($socust,$sonbr);
        if ($getso === false) {
            alert()->error('Error', 'WSA Failed')->persistent('Dismiss');
            return redirect()->route('browseSJ');
        } else {
            if ($getso[1] == 'false') {
                alert()->error('Error', 'Error WSA QAD Data')->persistent('Dismiss');
                return redirect()->route('browseSJ');
            }

            $tempPO = (new CreateTempTable())->createSOTemp($getso[0]);
        }

        $soqad = $tempPO[1]->first()->sod_qty_ship;

        // dd($soqad);

        $loc  = LocMstr::where('loc_domain', Session::get('domain'))->get();

        return view('transaksi.suratjalan.edit-browse', compact('data', 'listsjopen', 'loc', 'listsjship','soqad'));
    }

    public function viewjsbrowse($id)
    {
        $data = SuratJalan::with('getDetail')->findOrFail($id);

        $this->authorize('view', [SuratJalan::class, $data]);

        $listsjopen = SuratJalanDetail::with('getMaster')->whereRelation('getMaster', 'sj_status', 'New')->get();

        return view('transaksi.suratjalan.show-browse', compact('data'));
    }

    public function deletejsbrowse($id, Request $request)
    {
        // dd($id, $request->all());

        $datas = SuratJalan::with('getDetail')->findOrFail($id);
        $datas->sj_status = 'Cancelled';
        $datas->sj_reject_reason = $request->reason;
        $datas->save();

        $data = SuratJalan::where('sj_domain',Session::get('domain'))->orderBy('created_at', 'Desc')->paginate(10);
        return view('transaksi.suratjalan.browse-table', compact('data'));


        // alert()->success('Success', 'SJ Cancelled')->persistent('Dismiss');
        // return back();

    }

    public function changesjbrowse($id)
    {
        $data = SuratJalan::with('getDetail')->findOrFail($id);
        $so = [];

        $this->authorize('redo', [SuratJalan::class, $data]);
        
        return view('transaksi.suratjalan.redosj.index', compact('data','so'));
    }

    public function searchchangesj(Request $request){
        $getso = (new WSAServices())->wsagetso('',$request->sonbr);
        if ($getso === false) {
            alert()->error('Error', 'WSA Failed')->persistent('Dismiss');
            return redirect()->route('changeSJBrowse',['id'=>$request->idsj]);
        } else {
            if ($getso[1] == 'false') {
                alert()->error('Error', 'SO Number : ' . $request->sonbr . ' Not Found')->persistent('Dismiss');
                return redirect()->route('changeSJBrowse',['id'=>$request->idsj]);
            }
            $tempPO = (new CreateTempTable())->createSOTemp($getso[0]);
        }

        $tempPO = $tempPO[0];

        return redirect()->route('dispChangeSJ')->with(['tableso' => $tempPO,'sjnbr' => $request->sj, 'nopol' => $request->nopol]);
    }

    public function dispchangesj(Request $request){
        $so = Session::get('tableso');
        $sjnbr = Session::get('sjnbr');
        $nopol = Session::get('nopol');

        if (is_null($so) || is_null($sjnbr)) {
            alert()->error('Error', 'Silahkan Search Ulang')->persistent('Dismiss');
            return redirect()->route('browseSJ');
        }

        return view('transaksi.suratjalan.redosj.create', compact('so','sjnbr','nopol'));
    }

    public function updatechangesj(Request $request){
        // dd($request->all(),$request->sodline);
        
        DB::beginTransaction();
        try {
            $sj_mstr = new SuratJalan();
            $sj_mstr->sj_nbr = $request->sj;
            $sj_mstr->sj_so_nbr = $request->sonbr;
            $sj_mstr->sj_so_cust = $request->customer;
            $sj_mstr->sj_so_ship = $request->shipto;
            $sj_mstr->sj_so_bill = $request->billto;
            $sj_mstr->sj_status = 'New';
            $sj_mstr->sj_nopol = $request->nopol;
            $sj_mstr->sj_so_po = $request->sopo;
            $sj_mstr->sj_domain = Session::get('domain');
            $sj_mstr->save();

            $id = $sj_mstr->id;
            foreach ($request->sodline as $key => $datas) {
                // if($request->qtyinput[$key] > 0 ){ /* dimatiin biar bisa qty inputnya minus */
                    $sj_dets = new SuratJalanDetail();
                    $sj_dets->sj_mstr_id = $id;
                    $sj_dets->sj_line = $datas;
                    $sj_dets->sj_part = $request->sodpart[$key];
                    $sj_dets->sj_part_desc = $request->soddesc[$key];
                    $sj_dets->sj_loc = $request->partloc[$key];
                    $sj_dets->sj_lot = $request->lot[$key];
                    $sj_dets->sj_qty_ord = $request->sodqtyord[$key];
                    $sj_dets->sj_qty_ship = $request->sodqtyship[$key];
                    $sj_dets->sj_qty_input = $request->qtyinput[$key];
                    $sj_dets->sj_price_ls = $request->sodpricels[$key];
                    $sj_dets->save();
                // } 
            }
            
            DB::commit();
            alert()->success('Success', 'Surat jalan Created, SJ Number : ' . $request->sj)->persistent('Dismiss');
            return redirect()->route('browseSJ');
        } catch (Exception $err) {
            DB::rollBack();

            alert()->error('Error', 'Failed to Create SJ')->persistent('Dismiss');
            return redirect()->route('browseSJ');
        }
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
