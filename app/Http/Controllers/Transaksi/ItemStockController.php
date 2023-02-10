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
        // $wsa = (new WSAServices())->wsagetstokitem(Session::get('domain'),$id);
        // if($wsa === false){
        //     alert()->error('Error', 'WSA Failed');
        //     return redirect()->back();
        // }

        // $data = collect($wsa[0]);

        // $sjdata = SuratJalan::where('sj_nbr','SJ220002')->get();
        // dd($sjdata);

        // return view('transaksi.viewitem.show',compact('data','id'));
    }

    public function getdetail($id,$dom){
        // dd($id,$dom);
        $wsa = (new WSAServices())->wsagetstokitem(Session::get('domain'),$id);
        if($wsa === false){
            alert()->error('Error', 'WSA Failed');
            return redirect()->back();
        }else{
            $tempStockItem = (new CreateTempTable())->tempDetailItem($wsa[0]);
        }

        $data = $tempStockItem;

        // $data = collect($wsa[0]);

        

        return view('transaksi.viewitem.show',compact('data','id'));
    }
}
