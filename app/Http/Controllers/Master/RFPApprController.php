<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Department;
use App\Models\Master\RFPAppr;
use App\Models\Master\Role;
use App\Models\Master\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RFPApprController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $departments = Department::paginate(10);

            $role = Role::where('role', '=', 'Purchasing')->first();

            $users = User::distinct()->where('role_id', '=', $role->id)->orderBy('name')->get();

            return view('setting.rfpapproval.table', compact('departments', 'users'));
        } else {
            $departments = Department::paginate(10);

            $role = Role::where('role', '=', 'Purchasing')->first();

            $users = User::distinct()->where('role_id', '=', $role->id)->orderBy('name')->get();

            return view('setting.rfpapproval.index', compact('departments', 'users'));
        }
    }

    public function update(Request $request)
    {
        $deptname = $request->dept_name;
        $deptcode = substr($deptname, 0, strpos($deptname, ' '));

        $department = Department::where('department_code', $deptcode)->first();

        $flg = 0;
        $listApprover = '';
        $order = '';

        if ($request->suppname) {
            foreach ($request->suppname as $data) {
                $flg += 1;
            }

            for ($x = 0; $x < $flg; $x++) {
                if ($request->suppname[$x] == $request->altname[$x]) {
                    // session()->flash('error', 'Approver and Alternate Cannot be The Same');
                    alert()->error('Error', 'Approver and Alternate Cannot be The Same');
                    return back();
                }

                if (strpos($listApprover, $request->suppname[$x]) !== false) {
                    // session()->flash('error', 'Approver cannot be the same');
                    alert()->error('Error', 'Approver cannot be the same');
                    return back();
                }
                if ($order == $request->order[$x]) {
                    alert()->error('Error', 'Order cannot be the same');
                    // return redirect()->back()->with('error', 'Order cannot be the same');
                }

                $order .= $request->order[$x];
                $listApprover .= $request->suppname[$x];
            }
        }

        DB::beginTransaction();
        try {
            RFPAppr::where('rfps_department_id', $department->id)->delete();

            if (isset($request->suppname)) {
                if (count($request->suppname) >= 0) {
                    foreach ($request->suppname as $item => $v) {

                        $data2 = array(
                            'rfps_user_id' => $request->suppname[$item],
                            'rfps_department_id' => $department->id,
                            'rfps_alt_user_id' => $request->altname[$item],
                            'rfps_order' => $request->order[$item],
                            'created_at' => now(),
                            'updated_at' => now(),
                        );

                        DB::table('rfps_approval')->insert($data2);
                    }
                }
            }
            DB::commit();
            alert()->success('Success', 'RFP Control succesfully created for departemen : ' . $deptname);
            return back();
        } catch (\InvalidArgumentException $ex) {
            DB::rollBack();
            return back()->withError($ex->getMessage())->withInput();
        } catch (\Exception $ex) {
            DB::rollBack();
            return back()->withError($ex->getMessage())->withInput();
        } catch (\Error $ex) {
            DB::rollBack();
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $output = '';
            $flg = 0;

            $department = Department::where('department_code', $request->search)->first();
            $rfpAppr = RFPAppr::where('rfps_department_id', $department->id)->get();
            $role = Role::where('role', '=', 'Purchasing')->first();
            $newdata = User::distinct()->where('role_id', '=', $role->id)->get();

            if ($rfpAppr) {
                foreach ($rfpAppr as $key => $users) {
                    $output .= "<tr>" .
                        "<td>
                            <select id='suppname[]' class='form-control suppname' name='suppname[]' required autofocus>";
                    foreach ($newdata as $data) :
                        if ($users->rfps_user_id == $data->id) :
                            $output .= '<option value=' . $data->id . ' Selected>' . $data->name . ' - ' . $data->getRoleType->role_type . '</option>';
                        else :
                            $output .= '<option value=' . $data->id . ' >' . $data->name . ' - ' . $data->getRoleType->role_type . '</option>';
                        endif;
                    endforeach;
                    $output .= "</select>
                        </td>" .

                        "<td>
                            <select id='altname[]' class='form-control altname' name='altname[]' required autofocus>";
                    foreach ($newdata as $new) :
                        if ($users->rfps_alt_user_id == $new->id) :
                            $output .= '<option value=' . $new->id . ' Selected>' . $new->name . ' - ' . $new->getRoleType->role_type . '</option>';
                        else :
                            $output .= '<option value=' . $new->id . ' >' . $new->name . ' - ' . $new->getRoleType->role_type . '</option>';
                        endif;
                    endforeach;

                    $output .= "</select>
                        </td>" .

                        "<td> 
                            <input type='number' class='form-control order' min='1' Autocomplete='Off' id='order[]' name='order[]' style='height:38px' 
                            value='" . $users->rfps_order . "' required autofocus autocomplete='off'/>
                        </td>" .

                        "<td data-title='Action'><input type='button' class='ibtnDel btn btn-danger' value='delete'></td>" .

                        "</tr>";

                    $flg = $flg + 1;
                }

                return Response($output);
            }
        }
    }
}
