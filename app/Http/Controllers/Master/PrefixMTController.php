<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Prefix;
use Illuminate\Http\Request;

class PrefixMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Prefix::first();
        return view('setting.prefix.index',compact('data'));
    }

    public function store(Request $request)
    {

        $newprefix = Prefix::firstOrNew(['id' => '1']);
        $newprefix->prefix_sj = $request->prefixsj;
        $newprefix->rn_sj = $request->rnsj;
        $newprefix->save();

        
        alert()->success('Success', 'Prefix Updated');
        return redirect()->route('prefixmaint.index');
    }
}
