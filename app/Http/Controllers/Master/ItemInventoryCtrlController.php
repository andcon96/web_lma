<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ItemInventoryCtrl;
use App\Models\Master\ItemInventoryMaster;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemInventoryCtrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = ItemInventoryCtrl::where('iic_item_isRFQ', 0)->get();
        return view('setting..inventorycontrol.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('setting..inventorycontrol.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $part    = $request->input('part');
        $type    = $request->input('type');
        $dsgn    = $request->input('dsgn');
        $promo   = $request->input('promo');
        $grp     = $request->input('grp');
        $line    = $request->input('line');


        if(is_null($part)) {
            $part = "";
        }

        if (is_null($promo)) {
            $promo = "";
        }
        if (is_null($type)) {
            $type = "";
        }
        if (is_null($grp)) {
            $grp = "";
        }
        if (is_null($line)) {
            $line = "";
        }
        if (is_null($dsgn)) {
            $dsgn = "";
        }

        DB::beginTransaction();

        try {
            $items = new ItemInventoryCtrl();
            $items->iic_item_part = $part;
            $items->iic_item_type = $type;
            $items->iic_item_design = $dsgn;
            $items->iic_item_promo = $promo;
            $items->iic_item_isRFQ = 0;
            $items->iic_item_group = $grp;
            $items->iic_item_prod_line = $line;
            $items->save();

            DB::commit();

            $request->session()->flash('updated', 'Item inventory control successfully created');
        } catch (\Exception $err) {
            DB::rollBack();
            $request->session()->flash('error', 'Failed to save item inventory control');
        }
        return redirect()->route('iteminventorycontrol.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = ItemInventoryCtrl::find($id);

        return view('setting..inventorycontrol.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $part    = $request->input('part');
        $type    = $request->input('type');
        $dsgn    = $request->input('dsgn');
        $promo   = $request->input('promo');
        $grp     = $request->input('grp');
        $line    = $request->input('line');

        if (is_null($promo)) {
            $promo = "";
        }
        if (is_null($part)) {
            $part = "";
        }
        if (is_null($type)) {
            $type = "";
        }
        if (is_null($grp)) {
            $grp = "";
        }
        if (is_null($line)) {
            $line = "";
        }
        if (is_null($dsgn)) {
            $dsgn = "";
        }

        $item = ItemInventoryCtrl::find($id);
        $item->iic_item_part = $part;
        $item->iic_item_prod_line = $line;
        $item->iic_item_design = $dsgn;
        $item->iic_item_promo = $promo;
        $item->iic_item_type = $type;
        $item->iic_item_group = $grp;
        $item->save();

        $request->session()->flash('updated', 'Item inventory control successfully updated');
        return redirect()->route('iteminventorycontrol.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->delete_id;

        DB::beginTransaction();

        try {
            $item = ItemInventoryCtrl::find($id);
            $item->delete();

            DB::commit();
            $request->session()->flash('updated', 'Item inventory control succesfully deleted');
        } catch (\Exception $err) {
            DB::rollBack();
            $request->session()->flash('error', 'Failed to delete item inventory control');
        }
        return redirect()->back();
    }

    public function loadItem(Request $request)
    {
        $loadItem = (new WSAServices())->wsaitem();
        DB::beginTransaction();
        if($loadItem[1] == 'true') {
            try {
                ItemInventoryMaster::where('iim_item_isRFQ', 0)->delete();

                $itemValidation = ItemInventoryCtrl::where('iic_item_isRFQ', 0)->get();
                if (!is_null($itemValidation)) {
                    foreach ($itemValidation as $validation) {
                        $newCollection = collect($loadItem[0]);
                        if ($validation->iic_item_part != '') {
                            $newCollection = $newCollection->where('t_part', '=', $validation->iic_item_part);
                        }

                        if ($validation->iic_item_prod_line != '') {
                            $newCollection = $newCollection->where('t_prod_line', $validation->iic_item_prod_line);
                        }

                        if ($validation->iic_item_type != '') {
                            $newCollection = $newCollection->where('t_part_type', '=', $validation->iic_item_type);
                        }

                        if ($validation->iic_item_group != '') {
                            $newCollection = $newCollection->where('t_group', '=', $validation->iic_item_group);
                        }

                        if ($validation->iic_item_promo != '') {
                            $newCollection = $newCollection->where('t_promo', '=', $validation->iic_item_promo);
                        }

                        if ($validation->iic_item_design != '') {
                            $newCollection = $newCollection->where('t_dsgn_grp', '=', $validation->iic_item_group);
                        }

                        foreach ($newCollection as $collection) {
                            ItemInventoryMaster::insert([
                                'iim_item_domain' => $collection->t_domain,
                                'iim_item_part' => $collection->t_part,
                                'iim_item_desc' => $collection->t_desc,
                                'iim_item_um' => $collection->t_um,
                                'iim_item_prod_line' => $collection->t_prod_line,
                                'iim_item_type' => $collection->t_part_type,
                                'iim_item_isRFQ' => 0,
                                'iim_item_group' => $collection->t_group,
                                'iim_item_pm' => $collection->t_pm_code,
                                'iim_item_safety_stk' => $collection->t_sfty_stk,
                                'iim_item_price' => $collection->t_price,
                                'iim_item_promo' => $collection->t_promo,
                                'iim_item_design' => $collection->t_dsgn_grp,
                                'iim_item_acc' => $collection->t_acc,
                                'iim_item_subacc' => $collection->t_subacc,
                                'iim_item_costcenter' => $collection->t_cc,
                            ]);
                        }
                    }
                } else {
                    foreach ($loadItem[0] as $dataloop) {
                        ItemInventoryMaster::insert([
                            'iim_item_domain' => $dataloop->t_domain,
                            'iim_item_part' => $dataloop->t_part,
                            'iim_item_desc' => $dataloop->t_desc,
                            'iim_item_um' => $dataloop->t_um,
                            'iim_item_prod_line' => $dataloop->t_prod_line,
                            'iim_item_type' => $dataloop->t_part_type,
                            'iim_item_isRFQ' => 0,
                            'iim_item_group' => $dataloop->t_group,
                            'iim_item_pm' => $dataloop->t_pm_code,
                            'iim_item_safety_stk' => $dataloop->t_sfty_stk,
                            'iim_item_price' => $dataloop->t_price,
                            'iim_item_promo' => $dataloop->t_promo,
                            'iim_item_design' => $dataloop->t_dsgn_grp,
                            'iim_item_acc' => $dataloop->t_acc,
                            'iim_item_subacc' => $dataloop->t_subacc,
                            'iim_item_costcenter' => $dataloop->t_cc,
                        ]);
                    }
                }
                DB::commit();
                alert()->success('Success', 'Load Item Inventory Success');
            } catch (\Exception $err) {
                $request->session()->flash('error', 'Failed to save item inventory master');
            }
        } else {
            DB::rollback();
            alert()->error('Error', 'Load Item Inventory Failed');
        }
        return redirect()->back();
    }
}
