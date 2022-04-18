<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Qxwsa;
use Illuminate\Http\Request;

class QxWsaMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Qxwsa::first();

        return view('setting.qxwsa.wsas',['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'domain' => 'required',
            'wsaurl' => 'required',
            'wsapath' => 'required',
        ]);

        $qxwsa = Qxwsa::firstOrNew(array('id' => '1'));
        $qxwsa->wsas_domain = $request->domain;
        $qxwsa->wsas_url = $request->wsaurl;
        $qxwsa->wsas_path = $request->wsapath;
        $qxwsa->qx_enable = $request->qxenable;
        $qxwsa->qx_url = $request->qxurl;
        $qxwsa->qx_path = $request->qxpath;
        $qxwsa->save();

        alert()->success('Success', 'QX WSA Successfully Updated');
        return redirect()->route('qxwsa.index');

    }
}
