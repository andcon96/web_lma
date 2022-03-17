<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\DepartmentController;
use App\Http\Controllers\Master\AccessRoleMenuController;
use App\Http\Controllers\Master\RoleMTController;
use App\Http\Controllers\Master\UserMTController;
use App\Http\Controllers\Master\QxWsaMTController;
use App\Http\Controllers\Master\SiteMTController;
use App\Http\Controllers\Transaksi\PO\POReceiptController;
use App\Http\Controllers\NotificationController;
use App\Models\Transaksi\PurchaseOrder;
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
        Route::resource('poreceipt', POReceiptController::class);
        Route::get('searchpo', [POReceiptController::class, 'searchPO'])->name('searchPO');
        Route::get('showreceipt', [POReceiptController::class, 'showReceipt'])->name('showReceipt');
        Route::post('submitreceipt', [POReceiptController::class, 'submitReceipt'])->name('submitReceipt');
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
    });
});