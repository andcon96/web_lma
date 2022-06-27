<?php

namespace App\Http\Controllers\Transaksi\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
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

        $lastrun = StockItm::select('created_at')->first();

        if($req->item){
            $stock->where('item_nbr',$req->item);
        }

        $stock = $stock->paginate(10);

        return view('transaksi.report.stockitem.index', compact('stock','items','lastrun'));
    }

    public function store(){
        $domains = Domain::get();
        // dd($domains);
        foreach($domains as $datadomain){
            $stockitem = (new WSAServices())->wsastockitem($datadomain->domain_code);

            if($stockitem === false){
                alert()->error('Error', 'WSA Failed');
                return redirect()->back();
            }else{

                if($stockitem[1] == "false"){
                    alert()->error('Error', 'Stock Item Loc. FG tidak ditemukan');
                    return redirect()->back();
                }else{
                    
                    foreach($stockitem[0] as $datas){   

                        $stocks =  StockItm::firstOrNew(['itemdom'=>$datas->t_dom,
                                                        'item_nbr'=>$datas->t_part,
                                                        'item_loc'=>$datas->t_loc]);

                        $stocks->item_desc = $datas->t_desc1.' '.$datas->t_desc2;
                        $stocks->item_um = $datas->t_um;
                        $stocks->item_qtyoh = $datas->t_qtyoh;
                        $stocks->save();
                    }
        
        
                    alert()->success('Success','Data berhasil diload');
                    return redirect()->route('stockitm.index');
                }

            }
        }

    }
}
