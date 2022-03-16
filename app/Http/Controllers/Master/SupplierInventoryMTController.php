<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ItemInventoryMaster;
use App\Models\Master\Supplier;
use App\Models\Master\SupplierItemRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierInventoryMTController extends Controller
{
    public function index(Request $request)
    {
        $supplierInventories = SupplierItemRelation::with('getItem')->paginate(10);
        $itemMasters = ItemInventoryMaster::where('iim_item_isRFQ', 1)->get();
        $suppliers = Supplier::with('hasItemRelation')->where('supp_code', '<>', 'GENERAL')->get();

        if($request->ajax()) {
            return view('setting.supplierinventory.table', compact('supplierInventories', 'itemMasters', 'suppliers'));
        } else {
            return view('setting.supplierinventory.index', compact('supplierInventories', 'itemMasters', 'suppliers'));
        }
    }

    public function store(Request $request)
    {
        $item_part_id = $request->itempart;
        $supp_id = $request->alrtsupp;

        DB::beginTransaction();

        try {
            $supplierInventory = new SupplierItemRelation();
            $supplierInventory->sir_item_part = $item_part_id;
            $supplierInventory->sir_supp_code = $supp_id;
            $supplierInventory->save();

            DB::commit();

            $request->session()->flash('updated', 'Supplier Inventory successfully created');
        } catch (\Exception $err) {
            DB::rollBack();

            $request->session()->flash('error', 'Failed to save supplier inventory');
        }

        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $id = $request->temp_id;

        DB::beginTransaction();

        try {
            $supplierInventory = SupplierItemRelation::find($id);
            $supplierInventory->delete();

            DB::commit();

            $request->session()->flash('updated', 'Supplier inventory successfully deleted');
        } catch (\Exception $err) {
            DB::rollBack();

            $request->session()->flash('error', 'Failed to delete supplier inventory');
        }
        
        return redirect()->back();
    }

    public function searchsupplierinventory(Request $request)
    {
        if($request->ajax()) {
            $item_part = $request->item_search;
            $item_part_id = ItemInventoryMaster::where('iim_item_isRFQ', 1)->where('iim_item_part', $item_part)->value('id');

            $supplierInventories = SupplierItemRelation::with('getItem')->where('item_part_id', $item_part_id)->paginate(10);
        }

        return view('setting.supplierinventory.table', compact('supplierInventories'));
    }
}
