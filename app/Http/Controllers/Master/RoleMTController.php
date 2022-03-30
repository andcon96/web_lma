<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Role;
use App\Models\Master\RoleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roleTypes = RoleType::with('getRole')->get();

        return view('setting.roles.index', compact('roleTypes'));
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
        $request->validate([
            'role' => 'required',
            'role_type' => 'required',
        ]);

        $role = $request->role;

        $role_id = Role::where('role', $role)->value('id');

        DB::beginTransaction();

        try {
            $roleType = new RoleType();
            $roleType->role_type = ucwords($request->role_type);
            $roleType->role_id = $role_id;
            $roleType->save();

            DB::commit();

            alert()->success('Success', 'Role type successfully created!');
        } catch (\Exception $err) {
            DB::rollBack();
            alert()->error('Error', 'Failed to save role type');
        }

        return redirect()->route('rolemaint.index');
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
    public function update(Request $request)
    {
        $request->validate([
            'e_role' => 'required',
            'e_roleType' => 'required',
        ]);

        $roleType = RoleType::where('id', $request->e_id)->first();

        DB::beginTransaction();

        try {
            $roleType->role_type = $request->e_roleType;
            $roleType->save();

            DB::commit();

            alert()->success('Success', 'Role type successfully updated');

            // $request->session()->flash('updated', 'Role type successfully updated');
        } catch (\Exception $err) {
            DB::rollBack();
            alert()->error('Error', 'Failed to update role type');
            // $request->session()->flash('error', 'Failed to update role type');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        // dd($request->all());
        DB::beginTransaction();

        try {
            $roleTypes = RoleType::where('id', $request->temp_id)->first();
            $roleTypes->delete();

            DB::commit();

            alert()->success('Success', 'Role type successfully deleted');
            // $request->session()->flash('updated', 'Role type successfully deleted');
        } catch (\Exception $err) {
            DB::rollBack();
            alert()->error('Error', 'Failed to delete role type');
        }

        return redirect()->back();
    }
}
