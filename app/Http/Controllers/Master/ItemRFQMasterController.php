<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ItemInventoryMaster;
use Illuminate\Http\Request;

class ItemRFQMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = ItemInventoryMaster::where('iim_item_isRFQ', 1)->paginate(10);

        if ($request->ajax()) {
            $part   = $request->input('part');
            $prod   = $request->input('prod');
            $type   = $request->input('type');

            $items = new ItemInventoryMaster();

            if (isset($part)) {
                $items = $items->where('iim_item_part', $part);
            }

            if (isset($prod)) {
                $items = $items->where('iim_item_prod_line', $prod);
            }

            if (isset($type)) {
                $items = $items->where('iim_item_type', $type);
            }
            $items = $items->where('iim_item_isRFQ', 1)->paginate(10);
            
            return view('setting..rfqmaster.table', compact('items'));
        } else {
            return view('setting..rfqmaster.index', compact('items'));
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
