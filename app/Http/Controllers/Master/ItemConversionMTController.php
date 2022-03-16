<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ItemConversion;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemConversionMTController extends Controller
{
    public function index(Request $request)
    {
        $items = ItemConversion::paginate(10);

        if($request->ajax()) {
            return view('setting..itemconversion.table', compact('items'));
        } else {
            return view('setting..itemconversion.index', compact('items'));
        }
    }

    public function loaditemconversion(Request $request) {
        $dataItemConversion = (new WSAServices())->wsaloaditemconversion();
        if($dataItemConversion[1] == 'true') {
            DB::beginTransaction();
            try {
                foreach ($dataItemConversion[0] as $dataloop) {
                    ItemConversion::insert([
                        'ic_item_code' => $dataloop->t_itemcode,
                        'ic_um_1' => $dataloop->t_um1,
                        'ic_um_2' => $dataloop->t_um2,
                        'ic_qty_item' => $dataloop->t_qtyitem
                    ]);
                }

                DB::commit();
                alert()->success('Success', 'Load Item Conversion Success');
                return back();
            } catch (\Exception $err) {
                DB::rollBack();
                $request->session()->flash('error', 'Failed to save item conversion');
            }
        } else {
            alert()->error('Error', 'Load Supplier UM Failed');
            return back();
        }
    }

    public function searchitemconversion(Request $request)
    {
        if($request->ajax()) {
            $items = ItemConversion::where('ic_item_code', $request->itemcode)->paginate(10);

            return view('setting..itemconversion.table', compact('items'));
        }
    }
}
