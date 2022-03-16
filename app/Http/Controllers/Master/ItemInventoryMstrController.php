<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ItemInventoryMaster;
use Illuminate\Http\Request;

class ItemInventoryMstrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = ItemInventoryMaster::where('iim_item_isRFQ', 0)->paginate(10);

        if ($request->ajax()) {
            $item_part = $request->input('part');
            $item_prod_line = $request->input('prod');
            $item_type = $request->input('type');

            $items = new ItemInventoryMaster();

            if (isset($item_part)) {
                $items = $items->where('iim_item_isRFQ', 0)->where('iim_item_part', $item_part);
            }

            if (isset($item_prod_line)) {
                $items = $items->where('iim_item_isRFQ', 0)->where('iim_item_prod_line', $item_prod_line);
            }

            if (isset($item_type)) {
                $items = $items->where('iim_item_isRFQ', 0)->where('iim_item_type', $item_type);
            }

            $items = $items->where('iim_item_isRFQ', 0)->paginate(10);

            return view('setting..inventorymaster.table', compact('items'));
        } else {
            return view('setting..inventorymaster.index', compact('items'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
