<?php

namespace App\Http\Controllers\Transaksi\Report;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\StockItm;
use App\Services\CreateTempTable;
use App\Services\WSAServices;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockItemController extends Controller
{
    //

    public function index(Request $req){
        $stock = StockItm::query();

        $items = StockItm::groupBy('item_nbr')->select('item_nbr','item_desc')->get();

        if($req->item){
            $stock->where('item_nbr',$req->item);
        }

        $stock = $stock->paginate(10);

        return view('transaksi.report.stockitem.index', compact('stock','items'));
    }

    public function store(){
        $stockitem = (new WSAServices())->wsastockitem();

        if($stockitem[1] == "false"){
            alert()->error('Error', 'Stock Item Loc. FG tidak ditemukan');
            return redirect()->back();
        }else{
            foreach($stockitem[0] as $datas){
                DB::table('stockitm')->insert([
                    'item_nbr'  => $datas->t_part,
                    'item_desc' => $datas->t_desc1.' '.$datas->t_desc2,
                    'item_um'   => $datas->t_um,
                    // 'item_site' => $datas->t_site,
                    'item_loc' => $datas->t_loc,
                    'item_qtyoh' => $datas->t_qtyoh
                ]);
            }
        }

        return redirect()->route('stockitm.index');
    }
}
