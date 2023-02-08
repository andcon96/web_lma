<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\SuratJalan;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ItemStockController extends Controller
{
    public function index()
    {
        $wsa = (new WSAServices())->wsagetitem(Session::get('domain'));
        if($wsa === false){
            alert()->error('Error', 'WSA Failed');
            return redirect()->back();
        }

        $data = collect($wsa[0]);
        
        return view('transaksi.viewitem.index',compact('data'));
    }

    public function show($id)
    {
        $wsa = (new WSAServices())->wsagetstokitem(Session::get('domain'),$id);
        if($wsa === false){
            alert()->error('Error', 'WSA Failed');
            return redirect()->back();
        }

        $data = collect($wsa[0]);

        $sjdata = SuratJalan::with(['getDetail' => function($query){
                $query->selectRaw('sj_part,sj_loc,sj_lot, SUM(sj_qty_input) as qty_input')
                    ->groupBy('sj_part','sj_loc','sj_lot');
            }])->get();
        dd($sjdata);

        return view('transaksi.viewitem.show',compact('data','id'));
    }
}
