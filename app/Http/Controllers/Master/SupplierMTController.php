<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Supplier;
use App\Services\WSAServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierMTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::paginate(10);

        if ($request->ajax()) {
            # code...
            $suppcode = $request->suppcode;

            $suppliers = new Supplier();

            if (isset($suppcode)) {
                $suppliers = $suppliers->where('supp_code', 'like', '%' . $suppcode . '%');
            }

            $suppliers = $suppliers->paginate(10);

            return view('setting.suppliers.table', compact('suppliers'));
        } else {
            # code...
            return view('setting.suppliers.index', compact('suppliers'));
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
        $id = $request->input('edit_id');
        $isActive = $request->input('active');
        $supplierPOAppr = $request->input('poapprove');

        $d_one = $request->input('alertdays1');
        $d_two = $request->input('alertdays2');
        $d_three = $request->input('alertdays3');
        $d_four = $request->input('alertdays4');
        $d_five = $request->input('alertdays5');

        $email_one = $request->input('alertemail1');
        $email_two = $request->input('alertemail2');
        $email_three = $request->input('alertemail3');
        $email_four = $request->input('alertemail4');
        $email_five = $request->input('alertemail5');

        $idle_day = $request->input('idledays');
        $idle_email = $request->input('idleemail');

        $email_purchasing = $request->input('emailpur');
        $phone = $request->input('phone');

        if ($phone) {
            if (!str_starts_with($phone, '+628')) {
                alert()->error('Error', 'Phone number must be start with +628');

                return back();
            }
        }

        DB::beginTransaction();

        try {
            $supplier = Supplier::where('id', $id)->first();

            $supplier->supp_IsActive = $isActive;
            $supplier->supp_po_appr = $supplierPOAppr;
            $supplier->supp_phone = $phone;
            $supplier->supp_idle_days = $idle_day;
            $supplier->supp_idle_emails = $idle_email;
            $supplier->supp_email_pur = $email_purchasing;
            $supplier->supp_day_one = $d_one;
            $supplier->supp_day_two = $d_two;
            $supplier->supp_day_three = $d_three;
            $supplier->supp_day_four = $d_four;
            $supplier->supp_day_five = $d_five;
            $supplier->supp_email_d_one = $email_one;
            $supplier->supp_email_d_two = $email_two;
            $supplier->supp_email_d_three = $email_three;
            $supplier->supp_email_d_four = $email_four;
            $supplier->supp_email_d_five = $email_five;
            $supplier->save();

            DB::commit();

            $request->session()->flash('updated', 'Supplier successfully updated');
        } catch (\Exception $err) {
            DB::rollBack();

            $request->session()->flash('error', 'Failed to update supplier');
        }

        return redirect()->back();
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

    public function searchsupplierwhenedit(Request $request)
    {
        if ($request->ajax()) {
            $output = "";

            $supplier = Supplier::where('id', $request->search)->get();

            $array = json_decode(json_encode($supplier), true);

            return response()->json($array);
        }
    }

    public function loadsupplier(Request $request)
    {
        $dataSupplier = (new WSAServices())->wsasupplier();
        if ($dataSupplier[1] == 'true') {
            DB::beginTransaction();

            try {
                foreach ($dataSupplier[0] as $dataloop) {
                    Supplier::updateOrInsert(
                        [
                            'supp_code' => $dataloop->t_suppcode,
                        ],
                        [
                            'supp_name' => $dataloop->t_suppname,
                            'supp_addr' => $dataloop->t_address,
                            'supp_isActive' => 1,
                            'supp_po_appr' => 1,
                        ]
                    );
                }

                DB::commit();

                alert()->success('Success', 'Load Supplier Success');
                return back();
            } catch (\Exception $err) {
                DB::rollBack();

                $request->session()->flash('error', 'Failed to save supplier');
            }
        } else {
            alert()->error('Error', 'Load Supplier UM Failed');
            return back();
        }
    }
}
