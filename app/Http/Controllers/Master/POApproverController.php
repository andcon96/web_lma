<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\PoApprover;
use App\Models\Master\Supplier;
use App\Models\Master\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POApproverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Supplier = Supplier::with('getPOApprover')->paginate(10);
        $names = User::with(['getRole','getRoleType'])->where('role_id','3')->get(); //purhcasing

        if($request->ajax()){
            return view('setting.poapprover.tablepo',['users' => $Supplier, 'names' => $names]);
        }

        return view('setting.poapprover.poappcontrol',['users' => $Supplier, 'names' => $names]);
    }
    
    public function store(Request $request)
    {
        $listapprover = '';
        if(!is_null($request->appid)){
            foreach($request->appid as $key => $data){
                if($request->suppname[$key] == $request->altname[$key]){
                    // Altname == Approver kirim error
                    // session()->flash("error","Approver and Alternate Cannot be The Same ");
                    alert()->error('Error','Approver and Alternate Cannot be The Same');
                    return back(); 
                }

                if(strpos($listapprover,$request->suppname[$key]) !== false){
                    // Approver sama kirim error
                    // session()->flash("error","Approver cannot be the same");
                    alert()->error('Error','Approver cannot be the same');
                    return back(); 
                }

                if(!is_numeric($request->min_amt[$key]) or !is_numeric($request->max_amt[$key])){
                    // session()->flash("error","Min or Max value must be digit");
                    alert()->error('Error','Min or Max value must be digit');
                    return back(); 
                }

                $listapprover .= $request->suppname[$key];
            }
        }
        
        DB::beginTransaction();
        try{
            // dd($request->all());
            // Update Supplier
            $supplier = Supplier::findOrFail($request->edit_id);
            $supplier->supp_intv = $request->int_rem;
            $supplier->supp_reapprove = $request->reapprove;
            $supplier->save();

            // Truncate & Insert PO Approve By Supp
            PoApprover::where('po_app_supp_code',$request->edit_id)->delete();
            if(!is_null($request->appid)){
                foreach($request->appid as $key => $data){
                    $poapp = PoApprover::firstOrNew(array('id' => $data));
                    $poapp->po_app_supp_code = $request->edit_id;
                    $poapp->po_app_approver = $request->suppname[$key];
                    $poapp->po_app_alt_approver = $request->altname[$key];
                    $poapp->po_app_min_amt = $request->min_amt[$key];
                    $poapp->po_app_max_amt = $request->max_amt[$key];
                    $poapp->created_at = Carbon::now()->toDateTimeString();
                    $poapp->updated_at = Carbon::now()->toDateTimeString();
                    $poapp->save();
                }
            }
            
            DB::commit();
            $request->session()->flash('updated', 'Approver PO Updated');
            return back();
        }catch(Exception $e){
            DB::rollBack();
            dd($e);
            $request->session()->flash('error', 'Update Approver PO Failed');
            return back();
        }


    }

    public function getdetailapp(Request $request){
        if($request->ajax()){
            $data = PoApprover::where('po_app_supp_code',$request->search)->get();
            $user = User::where('role_id',3)->get();
            $output = '';
            foreach ($data as $key => $datas) {
                $output.= "<tr>".
                "<td>
                    <select id='suppname[]' class='form-control suppname' name='suppname[]' required autofocus>";
                    foreach($user as $new1):
                        if($datas->po_app_approver == $new1->id):
                        $output .= '<option value='.$new1->id.' Selected >'.$new1->name.' - '.$new1->getRoleType->role_type.'</option>';
                        else:
                        $output .= '<option value='.$new1->id.' >'.$new1->name.' - '.$new1->getRoleType->role_type.'</option>';
                        endif;
                    endforeach;
                $output .= "</select>
                </td>".
                "<td> 
                    <input type='text' class='form-control minnbr' Autocomplete='Off' id='minamt[]' name='min_amt[]' style='height:38px' value='".$datas->po_app_min_amt."' required/>
                </td>".
                "<td> 
                    <input type='text' class='form-control maxnbr' Autocomplete='Off' id='minamt[]' name='max_amt[]' style='height:38px' value='".$datas->po_app_max_amt."' required/>
                    <input type='hidden' id='appid[]' name='appid[]' value='".$datas->id."' />
                </td>".
                "<td> 
                    <select id='altname[]' class='form-control altname' name='altname[]' required autofocus>";
                    foreach($user as $new):
                        if($datas->po_app_alt_approver == $new->id):
                        $output .= '<option value='.$new->id.' Selected>'.$new->name.' - '.$new->getRoleType->role_type.'</option>';
                        else:
                        $output .= '<option value='.$new->id.' >'.$new->name.' - '.$new->getRoleType->role_type.'</option>';
                        endif;
                    endforeach;
                $output .= "</select>
                </td>".
                "<td data-title='Action'><input type='button' class='ibtnDel btn btn-danger'  value='delete'></td>".
                "</tr>";
            }
            return response($output);
        }
    }
}
