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
        // Dashboard
        $cbDashboard = $request->input('cbDashboard');

        // Menu PO
        $cbPOBrowse = $request->input('cbPOBrowse');
        $cbPOReceipt = $request->input('cbPOReceipt');
        $cbPOApproval = $request->input('cbPOApproval');
        $cbLast10PO = $request->input('cbLast10PO');
        $cbAuditPOApp = $request->input('cbAuditPOApp');
        $cbAuditPO = $request->input('cbAuditPO');
        $cbResetApproval = $request->input('cbResetApp');

        // Menu RFQ
        $cbRfqMain = $request->input('cbRfqMain');
        $cbRFQApp = $request->input('cbRFQApp');
        $cbLast10RFQ = $request->input('cbLast10RFQ');
        $cbAuditRFQ = $request->input('cbAuditRFQ');

        //Menu RFP
        $cbRfpMain = $request->input('cbRfpMain');
        $cbRfpApp = $request->input('cbRfpApp');
        // $cbRFfpgeneratepo = $request->input('cbRFfpgeneratepo');
        $cbHistRfp = $request->input('cbHistRfp');
        $cbAuditRfp = $request->input('cbAuditRfp');
        $cbresetRfp = $request->input('cbresetRfp');


        // Menu Supp
        $cbPOConf = $request->input('cbPOConf');
        $cbShipConf = $request->input('cbShipConf');
        $cbRFQFeed = $request->input('cbRFQFeed');
        $cbShipBrowse = $request->input('cbShipBrowse');

        // Menu Ship
        $cbStockData = $request->input('cbStockData');
        $cbExpInv = $request->input('cbExpInv');
        $cbSlowMov = $request->input('cbSlowMov');

        // Menu Purplan
        $cbPPbrowse = $request->input('cbPPbrowse');
        $cbPPcreate = $request->input('cbPPcreate');

        // Menu Setting
        $cbUserMT = $request->input('cbUserMT');
        $cbRoleMT = $request->input('cbRoleMT');
        $cbRoleMenu = $request->input('cbRoleMenu');
        $cbSuppMT = $request->input('cbSuppMT');
        $cbItem = $request->input('cbItem');
        $cbItemMT = $request->input('cbItemMT');
        $cbSuppItem = $request->input('cbSuppItem');
        $cbRfqControl = $request->input('cbRFQControl');
        $cbAppCont = $request->input('cbAppCont');
        $cbSiteCon = $request->input('cbSiteCon');
        $cbLicense = $request->input('cbLicense');
        $cbTrSync = $request->input('cbTrSync');
        $cbDept = $request->input('cbDept');
        $cbRFPApprove = $request->input('cbRFPApprove');
        $cbItemConv = $request->input('cbItemConv');
        $cbUmMaint = $request->input('cbUmMaint');

        $data = $cbPOBrowse . $cbPOReceipt . $cbPOApproval . $cbLast10PO . 
                $cbAuditPOApp . $cbAuditPO . $cbRfqMain . $cbRFQApp . 
                $cbLast10RFQ . $cbResetApproval . $cbAuditRFQ . $cbRfpMain . $cbRfpApp . 
                $cbHistRfp . $cbAuditRfp . $cbresetRfp . $cbPOConf . $cbShipConf . 
                $cbRFQFeed . $cbShipBrowse . $cbStockData . $cbExpInv . $cbSlowMov . 
                $cbUserMT . $cbRoleMT . $cbRoleMenu . $cbSuppMT . $cbItem . 
                $cbItemMT . $cbSuppItem . $cbRfqControl . $cbAppCont . $cbSiteCon . 
                $cbLicense . $cbTrSync . $cbDept . $cbRFPApprove . $cbItemConv . 
                $cbUmMaint . $cbPPbrowse . $cbPPcreate. $cbDashboard;

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
