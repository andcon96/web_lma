<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Prefix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RfqRfpMTController extends Controller
{
    public function index()
    {
        $rfq_rfp = Prefix::first();

        if($rfq_rfp == null) {
            $datavalue = 0;
            $datavaluepr = 0;
            $datavaluerfqprefix = "";
            $datavaluerfpprefix = "";
            $datavaluepoprefix = "";
            $datavalueprprefix = "";
            $datavaluerfqnbr = "";
            $datavaluerfpnbr = "";
            $datavalueponbr = "";
            $datavalueprnbr = "";
        } else {
            $datavalue = $rfq_rfp->po;
            $datavaluepr = $rfq_rfp->pr;
            $datavaluerfqprefix = $rfq_rfp->rfq_prefix;
            $datavaluerfpprefix = $rfq_rfp->rfp_prefix;
            $datavaluepoprefix = $rfq_rfp->po_prefix;
            $datavalueprprefix = $rfq_rfp->pr_prefix;
            $datavaluerfqnbr = $rfq_rfp->rfq_nbr;
            $datavaluerfpnbr = $rfq_rfp->rfp_nbr;
            $datavalueponbr = $rfq_rfp->po_nbr;
            $datavalueprnbr = $rfq_rfp->pr_nbr;
        }

        return view('setting.rfq-rfp.index', compact(
            'datavalue', 'datavaluepr', 'datavaluerfqprefix',
            'datavaluerfpprefix', 'datavaluepoprefix', 'datavalueprprefix',
            'datavaluerfqnbr', 'datavaluerfpnbr', 'datavalueponbr', 'datavalueprnbr'
        ));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $rfq_prefix = $request->input('prefix');
        $rfp_prefix = $request->input('prefix_rfp');
        $prefixpo = $request->input('prefix_po');
        $prefixpr = $request->input('prefix_pr');
        $curnumber = $request->input('curnumber');
        $nbrpo = $request->input('ponbr');
        $nbrpr = $request->input('prnbr');
        $rfp_nbr = $request->input('rfpnbr');
        $poallowed = $request->input('cbpoallowed');
        $prallowed = $request->input('cbprallowed');

        DB::beginTransaction();
        try {
            $rfq_rfp = Prefix::firstOrNew(['id' => 1,]);
            $rfq_rfp->rfq_prefix = strtoupper($rfq_prefix);
            $rfq_rfp->pr_prefix = strtoupper($prefixpr);
            $rfq_rfp->po_prefix = strtoupper($prefixpo);
            $rfq_rfp->rfp_prefix = strtoupper($rfp_prefix);
            $rfq_rfp->rfq_nbr = $curnumber;
            $rfq_rfp->po_nbr = $nbrpo;
            $rfq_rfp->pr_nbr = $nbrpr;
            $rfq_rfp->rfp_nbr = $rfp_nbr;
            $rfq_rfp->po = $poallowed;
            $rfq_rfp->pr = $prallowed;
            $rfq_rfp->save();

            DB::commit();
            $request->session()->flash('updated', 'RFQ RFP Maintenance successfully updated');
        } catch (\Exception $err) {
            dd($err);
            DB::rollBack();
            $request->session()->flash('error', 'Failed to save RFQ RFP Maintenance');
        }

        return redirect()->back();
    }
}
