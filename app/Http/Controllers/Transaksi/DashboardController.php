<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use App\Services\CreateTempTable;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunsj = SuratJalan::query()
            ->select(DB::raw("Year(created_at) as tahun"))
            ->groupBy(DB::raw("Year(created_at)"))
            ->pluck('tahun');

        // Dashboard 1 --> Surat Jalan Ongoing

        $listsj = SuratJalan::query()
            ->select(
                DB::raw("COUNT(*) as count"),
                DB::raw("MONTHNAME(created_at) as month_name")
            )
            ->where('sj_status', '=', 'New')
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('count', 'month_name');

        $labelsj = $listsj->keys();
        $datasj = $listsj->values();

        // Dashboard 2 --> List Item SJ Ongoing

        $listdetail = SuratJalanDetail::query()
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('sj_part'),
                DB::raw('sj_part_desc'),
                DB::raw('SUM(sj_qty_input) as totalitem')
            )
            ->whereRelation('getMaster', 'sj_status', 'New')
            ->groupBy('sj_part')
            ->pluck('totalitem', 'sj_part_desc');

        $labelpart = $listdetail->keys();
        $datapart  = $listdetail->values();

        // Dashboard 3 --> Outstanding Invoice

        $getinvoice = (new WSAServices())->wsaGetOutstandingInvoice(Session::get('domain'));
        if ($getinvoice === false) {
            alert()->error('Error', 'WSA Failed');
            return redirect()->back();
        }
        $getinvoice = collect($getinvoice)->groupBy('group_inv_date');
        $invoiceTotalByMonthYear = $getinvoice->map(function ($invoices) {
            return $invoices->sum('t_sisainv');
        });

        $labelinvoice = $invoiceTotalByMonthYear->keys();
        $datainvoice  = $invoiceTotalByMonthYear->values();

        // Dashboard 4 --> Stok All Item 

        $getItem = (new WSAServices())->wsagetstokallitem(Session::get('domain'));
        // Ambil Semua Jenis Item
        $getLocation = collect($getItem)->groupBy('t_location');
        $listlocation = $getLocation->keys()->toArray();
        array_walk($listlocation, function(&$value) {
            if ($value === '') {
                $value = null;
            }
        });
        $getLotSerial = collect($getItem)->groupBy('t_lot');
        $listLotSerial = $getLotSerial->keys()->toArray();
        array_walk($listLotSerial, function(&$value) {
            if ($value === '') {
                $value = null;
            }
        });
        
        
        $getKeyItem = collect($getItem)->groupBy('t_part');
        $labelitem = $getKeyItem->keys()->toArray();

        // Ambil Semua Item yang ada Reject
        $getItemReject = collect($getItem)->where('t_location', 'Reject')->groupBy('t_part');
        $getItemReject = $getItemReject->map(function ($t_qtyoh) {
            return $t_qtyoh->sum('t_qtyoh');
        })->toArray();

        // Ambil Semua Item Yang tidak reject
        $getItemNonReject = collect($getItem)->where('t_location', '!=', 'Reject')->groupBy('t_part');
        $getItemNonReject = $getItemNonReject->map(function ($t_qtyoh) {
            return $t_qtyoh->sum('t_qtyoh');
        })->toArray();
        
        // Ambil Stok di web.
        $ongoingweb = (new CreateTempTable())->tempDetailItemAll($getItem);
        
        // Tambah row di array jika tidak ada
        foreach ($labelitem as $key => $datas) {
            if (!array_key_exists($datas, $getItemReject)) {
                $getItemReject[$datas] = 0;
            }
            if (!array_key_exists($datas, $getItemNonReject)) {
                $getItemNonReject[$datas] = 0;
            }
            if (!array_key_exists($datas, $ongoingweb)) {
                $ongoingweb[$datas] = 0;
            }
        }

        // Sort Biar urutan sama untuk di chart js.
        ksort($getItemReject);
        ksort($getItemNonReject);
        ksort($ongoingweb);
        sort($labelitem);
        
        // Collect Lagi biar bisa kebaca Chart JS
        $getItemReject = collect($getItemReject);
        $getItemNonReject = collect($getItemNonReject);
        $ongoingweb = collect($ongoingweb);
        $labelitem = collect($labelitem);

        $getItemReject  = $getItemReject->values();
        $getItemNonReject  = $getItemNonReject->values();
        $ongoingweb  = $ongoingweb->values();

        return view('transaksi.dashboard.index', compact(
            'listsj',
            'labelsj',
            'datasj',
            'tahunsj',
            'listdetail',
            'labelpart',
            'datapart',
            'getinvoice',
            'labelinvoice',
            'datainvoice',
            'labelitem',
            'getItemReject',
            'getItemNonReject',
            'ongoingweb'
        ));
    }

    public function getallsj(Request $request)
    {
        $listsj = SuratJalan::query()
            ->select(
                DB::raw("COUNT(*) as count"),
                DB::raw("MONTHNAME(created_at) as month_name")
            )
            ->where('sj_status', '=', 'New');

        if ($request->tahun != 'All') {
            $listsj->whereYear('created_at', $request->tahun);
        }

        $listsj =  $listsj->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('count', 'month_name');

        $labelsj = $listsj->keys();
        $datasj = $listsj->values();

        return [$labelsj, $datasj];
    }

    public function getallpartsj(Request $request)
    {

        $listdetail = SuratJalanDetail::query()
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('sj_part'),
                DB::raw('sj_part_desc'),
                DB::raw('SUM(sj_qty_input) as totalitem')
            )
            ->whereRelation('getMaster', 'sj_status', 'New')
            ->groupBy('sj_part');


        if ($request->opsi == 1) {
            // By Qty Part SJ
            $listdetail = $listdetail
                ->pluck('totalitem', 'sj_part_desc');
        }

        if ($request->opsi == 2) {
            // By Total SJ
            $listdetail = $listdetail
                ->pluck('count', 'sj_part_desc');
        }

        // dd($listdetail, $request->opsi);

        $labelsj = $listdetail->keys();
        $datasj = $listdetail->values();


        return [$labelsj, $datasj];
    }

    public function getstokitemlokasi(Request $request)
    {

        $getItem = (new WSAServices())->wsagetstokallitem(Session::get('domain'));
        $getAllItem = collect($getItem)->where('t_part', $request->part)->groupBy('t_location');
        $getAllItem = $getAllItem->map(function ($t_qtyoh) {
            return $t_qtyoh->sum('t_qtyoh');
        });

        $label = $getAllItem->keys();
        $data = $getAllItem->values();

        return [$label, $data];
    }

    public function detailsj($bulan, $tahun)
    {
        $listsj = SuratJalan::query()
            ->where('sj_status', '=', 'New')
            ->whereMonth('created_at', '=', date('m', strtotime($bulan)));

        if ($tahun != 'All') {
            $listsj->whereYear('created_at', $tahun);
        }

        $listsj =  $listsj->paginate(10);

        return view('transaksi.dashboard.detailsj', compact('listsj', 'bulan', 'tahun'));
    }

    public function detailsjpart($part)
    {
        // dd($part);
        $listdetail = SuratJalanDetail::query()
            ->with('getMaster')
            ->whereRelation('getMaster', 'sj_status', 'New')
            ->where('sj_part_desc', $part)
            ->paginate(10);

        return view('transaksi.dashboard.detailsjpart', compact('listdetail', 'part'));
    }

    public function detailinvoice($tahunbulan)
    {
        $getinvoice = (new WSAServices())->wsaGetOutstandingInvoice(Session::get('domain'));
        if ($getinvoice === false) {
            alert()->error('Error', 'WSA Failed');
            return redirect()->back();
        }
        $getinvoice = collect($getinvoice)->where('group_inv_date', $tahunbulan);

        return view('transaksi.dashboard.detailinvoice', compact('getinvoice', 'tahunbulan'));
    }

    public function detailstokitem($item, $lokasi)
    {

        $getItem = (new WSAServices())->wsagetstokitem(Session::get('domain'), $item);
        if ($getItem[1] == 'false') {
            alert()->error('Error', 'WSA Failed');
            return redirect()->back();
        }

        if($lokasi == 'Ongoing Web'){
            $data = (new CreateTempTable())->tempDetailItem($getItem[0]);
            
            return view('transaksi.dashboard.detailstokitem', compact('data', 'lokasi'));
        }

        $data = $getItem[0];
        $data = collect($data);

        if ($lokasi == 'Reject') {
            $data = $data->where('t_location', '=', 'Reject');
        } else {
            $data = $data->where('t_location', '!=', 'Reject');
        }

        return view('transaksi.dashboard.detailstokitem', compact('data', 'lokasi'));
    }
}
