<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\DepartmentController;
use App\Http\Controllers\Master\AccessRoleMenuController;
use App\Http\Controllers\Master\CustMstrController;
use App\Http\Controllers\Master\POInvcMTController;
use App\Http\Controllers\Master\PrefixMTController;
use App\Http\Controllers\Master\RoleMTController;
use App\Http\Controllers\Master\UserMTController;
use App\Http\Controllers\Master\QxWsaMTController;
use App\Http\Controllers\Master\SiteMTController;
use App\Http\Controllers\Transaksi\Report\StockItemController;
use App\Http\Controllers\Transaksi\PO\POReceiptController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Transaksi\PO\POApprovalController;
use App\Http\Controllers\Transaksi\Report\HutangCustController;
use App\Http\Controllers\Transaksi\SJ\SuratJalanConfirmController;
use App\Http\Controllers\Transaksi\SJ\SuratJalanController;
use App\Models\Transaksi\PurchaseOrder;
use App\Models\Transaksi\SuratJalan;
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

    Route::group(['middleware'=>'can:po_receipt'],function (){
        
        Route::get('poreceipt/showreceipt', [POReceiptController::class, 'showReceipt'])->name('showReceipt');
        Route::resource('poreceipt', POReceiptController::class);
        Route::get('searchpo', [POReceiptController::class, 'searchPO'])->name('searchPO');
        Route::post('submitreceipt', [POReceiptController::class, 'submitReceipt'])->name('submitReceipt');
    });

    Route::group(['middleware'=>'can:po_approval'],function (){
        Route::get('poapproval/showinvoice', [POApprovalController::class, 'showInvoice'])->name('showInvoice');
        Route::resource('poapproval', POApprovalController::class);
        Route::get('searchpoinvc', [POApprovalController::class, 'searchpoinvc'])->name('searchpoinvc');
        Route::post('sendmailapproval', [POApprovalController::class, 'sendMailApproval'])->name('sendMailApproval');
    });

    Route::group(['middleware'=>'can:sj_create'],function(){
        Route::resource('suratjalan', SuratJalanController::class);
        Route::get('searchso',[SuratJalanController::class, 'searchso'])->name('searchSO');
    });

    Route::group(['middleware'=>'can:sj_browse'],function(){
        Route::get('browsesj',[SuratJalanController::class, 'browsesj'])->name('browseSJ');
        Route::get('browsesj/editjsbrowse/{id}',[SuratJalanController::class, 'editjsbrowse'])->name('editSJBrowse');
        Route::get('browsesj/deletejsbrowse/{id}',[SuratJalanController::class, 'deletejsbrowse'])->name('deleteSJBrowse');
        Route::get('browsesj/viewjsbrowse/{id}',[SuratJalanController::class, 'viewjsbrowse'])->name('viewSJBrowse');
    });

    Route::group(['middleware'=>'can:sj_confirm'],function(){
        Route::resource('sjconfirm', SuratJalanConfirmController::class);
    });

    Route::group(['middleware'=>'can:stock_item'],function(){
        Route::resource('stockitm', StockItemController::class);
    });

    Route::group(['middleware'=>'can:hutang_cust'],function(){
        Route::resource('hutangcust', HutangCustController::class);
    });

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

        // QX WSA Master
        //================================
        Route::resource('qxwsa', QxWsaMTController::class);
        //================================

        // Prefix Master
        //================================
        Route::resource('prefixmaint', PrefixMTController::class);
        //================================

        // Invoice PO Email Setting
        //================================
        Route::resource('poinvcemail', POInvcMTController::class);
        //================================

        // Customer Masters
        //================================
        Route::resource('custmstr', CustMstrController::class);
        //================================

    });
});