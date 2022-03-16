<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\UM;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UMMTController extends Controller
{
    public function index(Request $request)
    {
        $ums = UM::paginate(10);
        
        if($request->ajax()) {
            return view('setting.um.table', compact('ums'));
        } else {
            return view('setting.um.index', compact('ums'));
        }
    }

    public function loadum(Request $request)
    {
        $loadUM = (new WSAServices())->wsaloadum();
        if($loadUM[1] == 'true') {
            DB::beginTransaction();

            try {
                foreach ($loadUM[0] as $dataloop) {
                    UM::insert([
                        'iu_um' => $dataloop->t_um,
                        'iu_um_desc' => $dataloop->t_cmmt
                    ]);
                }

                DB::commit();
                alert()->success('Success', 'Load UM Success');
            } catch (\Exception $err) {
                DB::rollBack();

                $request->session()->flash('error', 'Failed to save um');
            }
        } else {
            alert()->error('Error', 'Load UM Failed');
        }
        return redirect()->back();
    }

    public function searchumcode(Request $request)
    {
        if($request->ajax()) {
            $ums = UM::where('iu_um', $request->um)->paginate(10);
        }

        return view('setting.um.table', compact('ums'));
    }
}
