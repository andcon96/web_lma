<?php

namespace App\Http\Controllers;

use App\Models\InventoryDetail;
use App\Models\InventoryMaster;
use App\Models\Master\Role;
use App\Models\Master\User;
use App\Models\PODetail;
use App\Models\POMaster;
use App\Models\RFQMaster;
use App\Models\TRHistories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $id = Auth::user()->id;

        $users = User::where('id', $id)->first();
        
        $role = Role::where('id', $users->role_id)->first();

        if ($role->role === Role::SUPPLIER) {
            return redirect()->route('poconfirmation.index');
        }

        $poUnconfirmedBySupp = POMaster::where('pom_status', '=', 'UnConfirm')->count();

        $poDueIn7Days = POMaster::whereHas('getPODetail', function ($query) {
            $query->where('pod_det_status', '!=', 'Closed')
                ->where('pod_det_qty_open', '>', '0')
                ->whereRaw('datediff(curdate(), pod_det_due_date) < 0');
        })->count();

        $openPO = POMaster::whereHas('getPODetail', function ($query) {
            $query->where('pod_det_status', '!=', 'Closed')
                ->where('pod_det_qty_open', '>', '0')
                ->whereRaw('datediff(curdate(), pod_det_due_date) > 0');
        })->count();

        $openRFQ = RFQMaster::whereHas('getDetail', function ($query) {
            $query->where('rfq_flag', '!=', 2)
                ->where('rfq_flag', '!=', 3)
                ->where('rfq_flag', '!=', 4);
        })->count();

        $purItemNoAct30 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '30')->get();
        $purItemNoAct90 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '90')->get();
        $purItemNoAct180 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '180')->get();
        $purItemNoAct365 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '365')->get();

        $manItemNoAct30 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '30')->get();
        $manItemNoAct90 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '90')->get();
        $manItemNoAct180 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '180')->get();
        $manItemNoAct365 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '365')->get();

        $unApprPO = POMaster::where('pom_status', '=', 'UnConfirm')->count();

        $approvedPO = POMaster::where('pom_status', '=', 'ApprovedByPurchasing')
            ->orWhere('pom_status', '=', 'ApprovedBySupplier')
            ->count();

        $unApprPOLess3 = POMaster::where('pom_status', '=', 'UnConfirm')
            ->whereRaw('datediff(curdate(), pom_due_date) > 0')
            ->whereRaw('datediff(curdate(), pom_due_date) < 3')
            ->count();

        $unApprPOMore7 = POMaster::where('pom_status', '=', 'UnConfirm')
            ->whereRaw('datediff(curdate(), pom_due_date) > 7')->count();

        $totalPastDuePO = POMaster::whereHas('getPODetail', function ($query) {
            $query->where('pod_det_qty_open', '>', '0')
                ->where('pod_det_status', '!=', 'Closed');
        })->whereRaw('datediff(curdate(), pom_due_date) > 0')->count();

        $totalPastDuePO7 = POMaster::whereRaw('datediff(curdate(), pom_due_date) > 0')
            ->whereRaw('datediff(curdate(), pom_due_date) < 8')
            ->whereHas('getPODetail', function ($query) {
                $query->where('pod_det_qty_open', '>', '0')
                    ->where('pod_det_status', '!=', 'Closed');
            })->count();

        $totalPastDuePOLess30 = POMaster::whereRaw('datediff(curdate(), pom_due_date) > 7')
            ->whereRaw('datediff(curdate(), pom_due_date) < 31')
            ->whereHas('getPODetail', function($query) {
                $query->where('pod_det_status', '!=', 'Closed')
                    ->where('pod_det_qty_open', '>', '0');
            })
            ->count();

        $totalPastDuePOMore30 = POMaster::whereRaw('datediff(curdate(), pom_due_date) > 30')
            ->whereHas('getPODetail', function ($query) {
                $query->where('pod_det_qty_open', '>', '0')
                    ->where('pod_det_status', '!=', 'Closed');
            })->count();

        $safetyStock = InventoryMaster::where('inv_reach_sfty_stk', 1)->count();
        $sentEmailStock = InventoryMaster::where('inv_has_sent_email', 1)->count();

        $itemExpire = InventoryDetail::where('inv_det_ed', '0')->count();
        $itemExpire30 = InventoryDetail::where('inv_det_ed', '30')->count();
        $itemExpire90 = InventoryDetail::where('inv_det_ed', '90')->count();
        $itemExpire180 = InventoryDetail::where('inv_det_ed', '180')->count();

        // return view("dashboard", ["users" => $users]);
        return view('dashboard', compact(
            'users',
            'poUnconfirmedBySupp',
            'poDueIn7Days',
            'openPO',
            'openRFQ',
            'purItemNoAct30',
            'purItemNoAct90',
            'purItemNoAct180',
            'purItemNoAct365',
            'manItemNoAct30',
            'manItemNoAct90',
            'manItemNoAct180',
            'manItemNoAct365',
            'unApprPO',
            'approvedPO',
            'unApprPOLess3',
            'unApprPOMore7',
            'totalPastDuePO',
            'totalPastDuePO7',
            'totalPastDuePOLess30',
            'totalPastDuePOMore30',
            'safetyStock',
            'sentEmailStock',
            'itemExpire',
            'itemExpire30',
            'itemExpire90',
            'itemExpire180'
        ));
    }

    public function listUnApprovedPOBySupplier(Request $request)
    {
        $unapprovedPO = PODetail::with(['getPOMaster.getSupplier', 'getItemMaster'])
            ->whereHas('getPOMaster', function ($query) {
                $query->where('pom_status', '=', 'UnConfirm');
            });

        if ($request->ajax()) {
            $po_nbr = $request->unposrc;
            $unapprovedPO = $unapprovedPO->whereHas('getPOMaster', function ($query) use ($po_nbr) {
                $query->where('pom_nbr', 'LIKE', '%' . $po_nbr . '%');
            })->paginate(10);
            return view('dashboard.unapprovedPO.table', compact('unapprovedPO'));
        } else {
            $unapprovedPO = $unapprovedPO->paginate(10);
            return view('dashboard.unapprovedPO.index', compact('unapprovedPO'));
        }
    }

    public function listPODueIn7(Request $request)
    {
        $po_due_in_7 = POMaster::with(['getPODetail.getItemMaster', 'getSupplier'])
            ->whereHas('getPODetail', function ($query) {
                $query->where('pod_det_qty_open', '>', '0')
                    ->where('pod_det_status', '!=', 'Closed');
            })->whereRaw('datediff(curdate(), pom_due_date) < 0');

        if ($request->ajax()) {
            $po_nbr = $request->up_search;

            $po_due_in_7 = $po_due_in_7->where('pom_nbr', 'LIKE', '%' . $po_nbr . '%')->paginate(10);
            return view('dashboard.poDueIn7.table', compact('po_due_in_7'));
        } else {
            $po_due_in_7 = $po_due_in_7->paginate(10);
            return view('dashboard.poDueIn7.index', compact('po_due_in_7'));
        }
    }

    public function listOpenPO(Request $request)
    {
        $openPO = POMaster::with(['getPODetail.getItemMaster', 'getSupplier'])
            ->whereHas('getPODetail', function ($query) {
                $query->where('pod_det_qty_open', '!=', '0');
            })->where('pom_status', '!=', 'Closed');

        if ($request->ajax()) {
            $po_nbr = $request->po_search3;

            $openPO = $openPO->where('pom_nbr', 'LIKE', '%' . $po_nbr . '%')->paginate(10);;
            return view('dashboard.openPO.table', compact('openPO'));
        } else {
            $openPO = $openPO->paginate(10);
            return view('dashboard.openPO.index', compact('openPO'));
        }
    }

    public function listOpenRFQ(Request $request)
    {
        $openRFQ = RFQMaster::with(['getDetail', 'getSite', 'getItem'])
            ->where('rfq_flag', '!=', '2')
            ->orWhere('rfq_flag', '!=', '3')
            ->orWhere('rfq_flag', '!=', '4');

        if ($request->ajax()) {
            $rfq_nbr = $request->rfq_search;

            $openRFQ = $openRFQ->where('rfq_id', $rfq_nbr)->paginate(10);
            return view('dashboard.openRFQ.table', compact('openRFQ'));
        } else {
            $openRFQ = $openRFQ->paginate(10);
            return view('dashboard.openRFQ.index', compact('openRFQ'));
        }
    }

    public function listPurchasedItemNoActivity(Request $request)
    {
        $purchasedItemNoAct = TRHistories::where('tr_hist_pm', 'p');
        $purItemNoAct30 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '30')->get();
        $purItemNoAct90 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '90')->get();
        $purItemNoAct180 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '180')->get();
        $purItemNoAct365 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '365')->get();

        if ($request->ajax()) {
            $item_part = $request->item_nbr;
            $purchasedItemNoAct = $purchasedItemNoAct->where('tr_hist_part', 'LIKE', '%' . $item_part . '%')->paginate(10);
            return view('dashboard.purchasedItemNoAct.table', compact(
                'purchasedItemNoAct'
            ));
        } else {
            $purchasedItemNoAct = $purchasedItemNoAct->paginate(10);
            return view('dashboard.purchasedItemNoAct.index', compact(
                'purchasedItemNoAct',
                'purItemNoAct30',
                'purItemNoAct90',
                'purItemNoAct180',
                'purItemNoAct365'
            ));
        }
    }

    public function listPurchasedItemNoActivity30(Request $request)
    {
        $purchasedItemNoAct = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '30');
        $purItemNoAct30 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '30')->get();
        $purItemNoAct90 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '90')->get();
        $purItemNoAct180 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '180')->get();
        $purItemNoAct365 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '365')->get();

        if ($request->ajax()) {
            $item_part = $request->item_nbr;
            $purchasedItemNoAct = $purchasedItemNoAct->where('tr_hist_part', 'LIKE', '%' . $item_part . '%')->paginate(10);
            return view('dashboard.purchasedItemNoAct.table', compact(
                'purchasedItemNoAct'
            ));
        } else {
            $purchasedItemNoAct = $purchasedItemNoAct->paginate(10);
            return view('dashboard.purchasedItemNoAct.index', compact(
                'purchasedItemNoAct',
                'purItemNoAct30',
                'purItemNoAct90',
                'purItemNoAct180',
                'purItemNoAct365'
            ));
        }
    }

    public function listPurchasedItemNoActivity90(Request $request)
    {
        $purchasedItemNoAct = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '90');
        $purItemNoAct30 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '30')->get();
        $purItemNoAct90 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '90')->get();
        $purItemNoAct180 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '180')->get();
        $purItemNoAct365 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '365')->get();

        if ($request->ajax()) {
            $item_part = $request->item_nbr;
            $purchasedItemNoAct = $purchasedItemNoAct->where('tr_hist_part', 'LIKE', '%' . $item_part . '%')->paginate(10);
            return view('dashboard.purchasedItemNoAct.table', compact(
                'purchasedItemNoAct'
            ));
        } else {
            $purchasedItemNoAct = $purchasedItemNoAct->paginate(10);
            return view('dashboard.purchasedItemNoAct.index', compact(
                'purchasedItemNoAct',
                'purItemNoAct30',
                'purItemNoAct90',
                'purItemNoAct180',
                'purItemNoAct365'
            ));
        }
    }

    public function listPurchasedItemNoActivity180(Request $request)
    {
        $purchasedItemNoAct = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '180');
        $purItemNoAct30 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '30')->get();
        $purItemNoAct90 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '90')->get();
        $purItemNoAct180 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '180')->get();
        $purItemNoAct365 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '365')->get();

        if ($request->ajax()) {
            $item_part = $request->item_nbr;
            $purchasedItemNoAct = $purchasedItemNoAct->where('tr_hist_part', 'LIKE', '%' . $item_part . '%')->paginate(10);
            return view('dashboard.purchasedItemNoAct.table', compact(
                'purchasedItemNoAct'
            ));
        } else {
            $purchasedItemNoAct = $purchasedItemNoAct->paginate(10);
            return view('dashboard.purchasedItemNoAct.index', compact(
                'purchasedItemNoAct',
                'purItemNoAct30',
                'purItemNoAct90',
                'purItemNoAct180',
                'purItemNoAct365'
            ));
        }
    }

    public function listPurchasedItemNoActivity365(Request $request)
    {
        $purchasedItemNoAct = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '365');
        $purItemNoAct30 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '30')->get();
        $purItemNoAct90 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '90')->get();
        $purItemNoAct180 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '180')->get();
        $purItemNoAct365 = TRHistories::where('tr_hist_pm', 'p')->where('tr_hist_ket', '365')->get();

        if ($request->ajax()) {
            $item_part = $request->item_nbr;
            $purchasedItemNoAct = $purchasedItemNoAct->where('tr_hist_part', 'LIKE', '%' . $item_part . '%')->paginate(10);
            return view('dashboard.purchasedItemNoAct.table', compact(
                'purchasedItemNoAct'
            ));
        } else {
            $purchasedItemNoAct = $purchasedItemNoAct->paginate(10);
            return view('dashboard.purchasedItemNoAct.index', compact(
                'purchasedItemNoAct',
                'purItemNoAct30',
                'purItemNoAct90',
                'purItemNoAct180',
                'purItemNoAct365'
            ));
        }
    }

    public function listManufacturedItemNoActivity(Request $request)
    {
        $manItemNoAct = TRHistories::where('tr_hist_pm', 'm');
        $manItemNoAct30 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '30')->get();
        $manItemNoAct90 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '90')->get();
        $manItemNoAct180 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '180')->get();
        $manItemNoAct365 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '365')->get();

        if ($request->ajax()) {
            $item_part = $request->item_nbr;
            $manItemNoAct = $manItemNoAct->where('tr_hist_part', 'LIKE', '%' . $item_part . '%')->paginate(10);
            return view('dashboard.manufacturedItemNoAct.table', compact(
                'manItemNoAct'
            ));
        } else {
            $manItemNoAct = $manItemNoAct->paginate(10);
            return view('dashboard.manufacturedItemNoAct.index', compact(
                'manItemNoAct',
                'manItemNoAct30',
                'manItemNoAct90',
                'manItemNoAct180',
                'manItemNoAct365'
            ));
        }
    }

    public function listManufacturedItemNoActivity30(Request $request)
    {
        $manItemNoAct = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '30');
        $manItemNoAct30 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '30')->get();
        $manItemNoAct90 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '90')->get();
        $manItemNoAct180 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '180')->get();
        $manItemNoAct365 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '365')->get();

        if ($request->ajax()) {
            $item_part = $request->item_nbr;
            $manItemNoAct = $manItemNoAct->where('tr_hist_part', 'LIKE', '%' . $item_part . '%')->paginate(10);
            return view('dashboard.manufacturedItemNoAct.table', compact(
                'manItemNoAct'
            ));
        } else {
            $manItemNoAct = $manItemNoAct->paginate(10);
            return view('dashboard.manufacturedItemNoAct.index', compact(
                'manItemNoAct',
                'manItemNoAct30',
                'manItemNoAct90',
                'manItemNoAct180',
                'manItemNoAct365'
            ));
        }
    }

    public function listManufacturedItemNoActivity90(Request $request)
    {
        $manItemNoAct = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '90');
        $manItemNoAct30 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '30')->get();
        $manItemNoAct90 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '90')->get();
        $manItemNoAct180 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '180')->get();
        $manItemNoAct365 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '365')->get();

        if ($request->ajax()) {
            $item_part = $request->item_nbr;
            $manItemNoAct = $manItemNoAct->where('tr_hist_part', 'LIKE', '%' . $item_part . '%')->paginate(10);
            return view('dashboard.manufacturedItemNoAct.table', compact(
                'manItemNoAct'
            ));
        } else {
            $manItemNoAct = $manItemNoAct->paginate(10);
            return view('dashboard.manufacturedItemNoAct.index', compact(
                'manItemNoAct',
                'manItemNoAct30',
                'manItemNoAct90',
                'manItemNoAct180',
                'manItemNoAct365'
            ));
        }
    }

    public function listManufacturedItemNoActivity180(Request $request)
    {
        $manItemNoAct = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '180');
        $manItemNoAct30 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '30')->get();
        $manItemNoAct90 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '90')->get();
        $manItemNoAct180 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '180')->get();
        $manItemNoAct365 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '365')->get();

        if ($request->ajax()) {
            $item_part = $request->item_nbr;
            $manItemNoAct = $manItemNoAct->where('tr_hist_part', 'LIKE', '%' . $item_part . '%');
            $manItemNoAct = $manItemNoAct->paginate(10);
            return view('dashboard.manufacturedItemNoAct.table', compact(
                'manItemNoAct'
            ));
        } else {
            $manItemNoAct = $manItemNoAct->paginate(10);
            return view('dashboard.manufacturedItemNoAct.index', compact(
                'manItemNoAct',
                'manItemNoAct30',
                'manItemNoAct90',
                'manItemNoAct180',
                'manItemNoAct365'
            ));
        }
    }

    public function listManufacturedItemNoActivity365(Request $request)
    {
        $manItemNoAct = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '365');
        $manItemNoAct30 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '30')->get();
        $manItemNoAct90 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '90')->get();
        $manItemNoAct180 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '180')->get();
        $manItemNoAct365 = TRHistories::where('tr_hist_pm', 'm')->where('tr_hist_ket', '365')->get();

        if ($request->ajax()) {
            $item_part = $request->item_nbr;
            $manItemNoAct = $manItemNoAct->where('tr_hist_part', 'LIKE', '%' . $item_part . '%')->paginate(10);
            return view('dashboard.manufacturedItemNoAct.table', compact(
                'manItemNoAct'
            ));
        } else {
            $manItemNoAct = $manItemNoAct->paginate(10);
            return view('dashboard.manufacturedItemNoAct.index', compact(
                'manItemNoAct',
                'manItemNoAct30',
                'manItemNoAct90',
                'manItemNoAct180',
                'manItemNoAct365'
            ));
        }
    }

    public function listApprovedPO(Request $request)
    {
        $poList = PODetail::with(['getItemMaster', 'getPOMaster.getSupplier'])
            ->whereHas('getPOMaster', function ($query) {
                $query->where('pom_status', '=', 'ApprovedByPurchasing')
                    ->orWhere('pom_status', '=', 'ApprovedBySupplier');
            });

        if ($request->ajax()) {
            $po_nbr = $request->po_search;
            $poList = $poList->whereHas('getPOMaster', function ($query) use ($po_nbr) {
                $query->where('pom_nbr', 'LIKE', '%' . $po_nbr . '%');
            })->paginate(10);

            return view('dashboard.POApprovalStatus.table', compact('poList'));
        } else {
            $poList = $poList->paginate(10);
            return view('dashboard.POApprovalStatus.index', compact('poList'));
        }
    }

    public function listUnapprovedPOLess3(Request $request)
    {
        $poList = PODetail::with(['getPOMaster.getSupplier', 'getItemMaster'])
            ->whereHas('getPOMaster', function ($query) {
                $query->where('pom_status', '=', 'UnConfirm')
                    ->orWhere('pom_status', '=', 'RejectedBySupplier')
                    ->whereRaw('datediff(curdate(), created_at) > 3')
                    ->whereRaw('datediff(curdate(), created)at) < 7');
            });

        if ($request->ajax()) {
            $po_nbr = $request->po_search2;
            $poList = $poList->whereHas('pom_nbr', function ($query) use ($po_nbr) {
                $query->where('pom_nbr', 'LIKE', '%' . $po_nbr . '%');
            })->paginate(10);
            return view('dashboard.POApprovalStatus.table', compact('poList'));
        } else {
            $poList = $poList->paginate(10);
            return view('dashboard.POApprovalStatus.lessThan3', compact('poList'));
        }
    }

    public function listUnapprovedPOMore7(Request $request)
    {
        $poList = PODetail::with(['getPOMaster.getSupplier', 'getItemMaster'])
            ->whereHas('getPOMaster', function ($query) {
                $query->where('pom_status', '=', 'UnConfirm')
                    ->orWhere('pom_status', '=', 'RejectedBySupplier')
                    ->whereRaw('datediff(curdate(), created)at) < 7');
            });

        if ($request->ajax()) {
            $po_nbr = $request->po_search3;
            $poList = $poList->whereHas('pom_nbr', function ($query) use ($po_nbr) {
                $query->where('pom_nbr', 'LIKE', '%' . $po_nbr . '%');
            })->paginate(10);
            return view('dashboard.POApprovalStatus.table', compact('poList'));
        } else {
            $poList = $poList->paginate(10);
            return view('dashboard.POApprovalStatus.moreThan7', compact('poList'));
        }
    }

    public function pastDuePO(Request $request)
    {
        $pastDuePO = PODetail::with(['getPOMaster.getSupplier', 'getItemMaster'])
            ->where('pod_det_status', '!=', 'Closed')
            ->where('pod_det_qty_open', '>', '0')
            ->whereHas('getPOMaster', function ($query) {
                $query->whereRaw('datediff(curdate(), pom_due_date) > 0')
                    ->whereRaw('datediff(curdate(), pom_due_date) < 8');
            });

        if ($request->ajax()) {
            $po_nbr = $request->po_search;
            $pastDuePO = $pastDuePO->whereHas('getPOMaster', function ($query) use ($po_nbr) {
                $query->where('pom_nbr', 'LIKE', '%' . $po_nbr . '%');
            })->paginate(10);

            return view('dashboard.pastDuePO.table', compact('pastDuePO'));
        } else {
            $pastDuePO = $pastDuePO->paginate(10);
            return view('dashboard.pastDuePO.index', compact('pastDuePO'));
        }
    }

    public function pastDuePO2(Request $request)
    {
        $pastDuePO = PODetail::with(['getPOMaster.getSupplier', 'getItemMaster'])
            ->where('pod_det_status', '!=', 'Closed')
            ->where('pod_det_qty_open', '>', '0')
            ->whereHas('getPOMaster', function ($query) {
                $query->whereRaw('datediff(curdate(), pom_due_date) > 7')
                    ->whereRaw('datediff(curdate(), pom_due_date) < 31');
            });

        if ($request->ajax()) {
            $po_nbr = $request->po_search2;

            $pastDuePO = $pastDuePO->whereHas('getPOMaster', function ($query) use ($po_nbr) {
                $query->where('pom_nbr', 'LIKE', '%' . $po_nbr . '%');
            })->paginate(10);
            return view('dashboard.pastDuePO.table', compact('pastDuePO'));
        } else {
            $pastDuePO = $pastDuePO->paginate(10);

            return view('dashboard.pastDuePO.index', compact('pastDuePO'));
        }
    }

    public function pastDuePO3(Request $request)
    {
        $pastDuePO = PODetail::with(['getPOMaster.getSupplier', 'getItemMaster'])
            ->where('pod_det_status', '!=', 'Closed')
            ->where('pod_det_qty_open', '>', '0')
            ->whereHas('getPOMaster', function ($query) {
                $query->whereRaw('datediff(curdate(), pom_due_date) > 30');
            });

        if ($request->ajax()) {
            $po_nbr = $request->po_search3;

            $pastDuePO = $pastDuePO->whereHas('getPOMaster', function ($query) use ($po_nbr) {
                $query->where('pom_nbr', 'LIKE', '%' . $po_nbr . '%');
            })->paginate(10);
            return view('dashboard.pastDuePO.table', compact('pastDuePO'));
        } else {
            $pastDuePO = $pastDuePO->paginate(10);

            return view('dashboard.pastDuePO.index', compact('pastDuePO'));
        }
    }
}
