<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Budgeting;
use App\Models\Master\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BudgetingMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Budgeting::first();
        $user = User::get();

        return view('setting.budgeting.index', ['datas' => $data, 'users' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Budgeting = Budgeting::firstOrNew(array('id' => '1'));
        $Budgeting->approver_budget = $request->appr_budget;
        $Budgeting->alt_approver_budget = $request->alt_appr_budget;
        $Budgeting->created_at = Carbon::now()->toDateTimeString();
        $Budgeting->updated_at = Carbon::now()->toDateTimeString();
        $Budgeting->save();

        $request->session()->flash('updated', 'Approval Budget Successfully Updated');
        return redirect()->route('budgeting.index');
    }

}
