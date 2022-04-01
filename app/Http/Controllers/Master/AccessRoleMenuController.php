<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\RoleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessRoleMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roleAccess = RoleType::with('getRole')->get();

        return view('setting.accessrolemenu.index', compact('roleAccess'));
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
        // Menu SJ
        $cbCreateSJ = $request->input('cbCreateSJ');
        $cbBrowseSJ = $request->input('cbBrowseSJ');
        $cbConfSJ = $request->input('cbConfSJ');

        $data = $cbCreateSJ . $cbBrowseSJ . $cbConfSJ;

        DB::beginTransaction();

        try {
            $roleAccess = RoleType::where('id', $request->edit_id)->first();
            $roleAccess->accessmenu = $data;
            $roleAccess->save();
            
            DB::commit();
            $request->session()->flash('updated', 'Role Access successfully updated');
            return redirect()->back();
        } catch (\Exception $err) {
            DB::rollBack();
            $request->session()->flash('error', 'Failed to save role access');
            return redirect()->back();
        }
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

    public function accessmenu(Request $request)
    {
        if ($request->ajax()) {

            $output = "";

            $accessmenu = RoleType::where('id', $request->search)->get();

            if ($accessmenu) {
                foreach ($accessmenu as $menu) {
                    $output .= $menu->accessmenu;
                }
            }

            return Response($output);
        }
    }
}
