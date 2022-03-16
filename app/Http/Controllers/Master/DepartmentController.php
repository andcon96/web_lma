<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depts = Department::get();

        return view('setting.departments.index', compact('depts'));
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
            'department_code' => 'required',
            'department_name' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $dept = new Department();
            $dept->department_code = $request->department_code;
            $dept->department_name = $request->department_name;
            $dept->save();

            DB::commit();
            $request->session()->flash('updated', 'Department successfully created');
        } catch (\Exception $err) {
            DB::rollback();
            $request->session()->flash('error', 'Failed to create department');   
        }

        return redirect()->back();
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
    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $dept = Department::where('id', $request->temp_id)->first();
            $dept->delete();

            DB::commit();

            $request->session()->flash('updated', 'Department successfully deleted');
        } catch (\Exception $err) {
            DB::rollBack();
            $request->session()->flash('error', 'Failed to delete department');
        }
        return redirect()->back();
    }
}
