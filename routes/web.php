<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Master\BudgetingMTController;
use App\Http\Controllers\Master\DepartmentController;
use App\Http\Controllers\Master\POApproverController;
use App\Http\Controllers\Master\AccessRoleMenuController;
use App\Http\Controllers\Master\ItemConversionMTController;
use App\Http\Controllers\Master\ItemInventoryCtrlController;
use App\Http\Controllers\Master\ItemInventoryMstrController;
use App\Http\Controllers\Master\ItemRFQCtrlController;
use App\Http\Controllers\Master\ItemRFQMasterController;
use App\Http\Controllers\Master\RoleMTController;
use App\Http\Controllers\Master\SupplierMTController;
use App\Http\Controllers\Master\UserMTController;
use App\Http\Controllers\Master\QxWsaMTController;
use App\Http\Controllers\Master\RFPApprController;
use App\Http\Controllers\Master\RfqRfpMTController;
use App\Http\Controllers\Master\SiteMTController;
use App\Http\Controllers\Master\SupplierInventoryMTController;
use App\Http\Controllers\Master\TransactionMTController;
use App\Http\Controllers\Master\UMMTController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return Redirect::to('home');
    }
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    //================================
    // Logout & Home
    //================================
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
    //================================
    

    //================================
    // Notification
    //================================
    Route::post('/mark-as-read', [NotificationController::class, 'notifread'])->name('notifread');
    Route::post('/mark-all-as-read', [NotificationController::class, 'notifreadall'])->name('notifreadall');
    //================================


    /** 
     * Transaction
     */


    
    /**
     * Group yang bisa akses menu settings
     */
    Route::group(['middleware'=>'can:access_masters'], function () {
        //================================
        // User Maintenance
        //================================
        Route::resource('usermaint', UserMTController::class);
        Route::get('/user/getdata', [UserMTController::class, 'index']);
        Route::get('/searchoptionuser', [UserMTController::class, 'searchoptionuser']);
        Route::post('/adminchangepass', [UserMTController::class, 'adminchangepass']);
        //================================

        //================================
        // Department Maintenance
        //================================
        Route::resource('deptmaint', DepartmentController::class);
        //================================

        //================================
        // Supplier Maintenance
        //================================
        Route::resource('suppmaint', SupplierMTController::class);
        Route::get('/searchsupplier', [SupplierMTController::class, 'index']);
        Route::get('/searchsupplierwhenedit', [SupplierMTController::class, 'searchsupplierwhenedit']);
        Route::post('/loadsupplier', [SupplierMTController::class, 'loadsupplier'])->name('loadsupplier');
        //================================


        //================================
        // Role Maintenance
        //================================
        Route::resource('rolemaint', RoleMTController::class);
        //================================


        //================================
        // Access Role Menu
        //================================
        Route::resource('accessrolemenu', AccessRoleMenuController::class);
        Route::get('/accessmenu', [AccessRoleMenuController::class, 'accessmenu'])->name('accessmenu');
        //================================

        //================================
        // Site Maintenance
        //================================
        Route::resource('sitemaint', SiteMTController::class);
        //================================

        //================================
        // Item Inventory Control
        //================================
        Route::resource('iteminventorycontrol', ItemInventoryCtrlController::class);
        Route::post('/loaditem', [ItemInventoryCtrlController::class, 'loadItem'])->name('iteminventorycontrol.loaditem');
        //================================

        //================================
        // Item Inventory Master
        //================================
        Route::resource('iteminventorymaster', ItemInventoryMstrController::class);
        Route::get('/searchitemmaster', [ItemInventoryMstrController::class, 'index']);
        //================================

        //================================
        // RQF Control
        //================================
        Route::resource('itemrfqcontrol', ItemRFQCtrlController::class);
        Route::post('/loaditemrfq', [ItemRFQCtrlController::class, 'loadItem'])->name('itemrfqcontrol.loaditemrfq');
        //================================

        //================================
        // RFQ Master
        //================================
        Route::resource('itemrfqmaster', ItemRFQMasterController::class);
        Route::get('/searchrfqmaster', [ItemRFQMasterController::class, 'index']);
        //================================

        //================================
        // Item Conversion MT
        //================================
        Route::resource('itemconversionMT', ItemConversionMTController::class);
        Route::get('/loaditemconversion', [ItemConversionMTController::class, 'loaditemconversion'])->name('itemconversionMT.loaditemconversion');
        Route::get('/searchitemconversion', [ItemConversionMTController::class, 'searchitemconversion'])->name('searchitemconversion');
        //================================

        //================================
        // UM MT
        //================================
        Route::resource('UMMT', UMMTController::class);
        Route::get('/loadum', [UMMTController::class, 'loadum'])->name('loadum');
        Route::get('/searchum', [UMMTController::class, 'searchumcode'])->name('searchum');
        //================================

        //================================
        // Supplier Inventory MT
        //================================
        Route::resource('supplierinventoryMT', SupplierInventoryMTController::class);
        Route::get('/searchsupplierinventory', [SupplierInventoryMTController::class, 'searchsupplierinventory'])->name('searchsupplierinventory');
        //================================

        //================================
        // RFQ RFP MT
        //================================
        Route::resource('rfq-rfpMT', RfqRfpMTController::class);
        //================================

        //================================
        // RFQ Approval
        //================================
        Route::resource('rfpapproval', RFPApprController::class);
        Route::get('/getData', [RFPApprController::class, 'getData'])->name('getData');
        //================================

        //================================
        // Transaction Type Master
        //================================
        Route::resource('transaction', TransactionMTController::class);
        //================================


        //================================
        // Budgeting Approval Master
        //================================
        Route::resource('budgeting', BudgetingMTController::class);
        //================================


        //================================
        // PO Approver Master
        //================================
        Route::resource('poapprover', POApproverController::class);
        Route::get('getdetailapp', [POApproverController::class, 'getdetailapp']);
        //================================

        // QX WSA Master
        //================================
        Route::resource('qxwsa', QxWsaMTController::class);
        //================================
    });
});