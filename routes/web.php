<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\AccessRoleMenuController;
use App\Http\Controllers\Master\CustMstrController;
use App\Http\Controllers\Master\DomainController;
use App\Http\Controllers\Master\LocMstrController;
use App\Http\Controllers\Master\POInvcMTController;
use App\Http\Controllers\Master\PrefixMTController;
use App\Http\Controllers\Master\RoleMTController;
use App\Http\Controllers\Master\UserMTController;
use App\Http\Controllers\Master\QxWsaMTController;
use App\Http\Controllers\Master\SiteMstrController;
use App\Http\Controllers\Master\SuppMstrController;
use App\Http\Controllers\Transaksi\Report\StockItemController;
use App\Http\Controllers\Transaksi\PO\POReceiptController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Transaksi\DashboardController;
use App\Http\Controllers\Transaksi\ItemStockController;
use App\Http\Controllers\Transaksi\PO\POApprovalController;
use App\Http\Controllers\Transaksi\PO\POBrowseController;
use App\Http\Controllers\Transaksi\PO\RcptUnplannedController;
use App\Http\Controllers\Transaksi\Report\HutangCustController;
use App\Http\Controllers\Transaksi\SJ\SuratJalanConfirmController;
use App\Http\Controllers\Transaksi\SJ\SuratJalanController;
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
    Route::get('/changedomain', [NotificationController::class, 'changedomain'])->name('changeDomain');
    //================================


    /** 
     * Transaction
     */

    Route::group(['middleware'=>'can:po_receipt'],function (){
        
        Route::get('poreceipt/showreceipt', [POReceiptController::class, 'showReceipt'])->name('showReceipt');
        Route::resource('poreceipt', POReceiptController::class);
        Route::get('searchpo', [POReceiptController::class, 'searchPO'])->name('searchPO');
        Route::post('submitreceipt', [POReceiptController::class, 'submitReceipt'])->name('submitReceipt');
        Route::get('poreceipt/todetailpo/{id}/{supp}/{cont}',[POReceiptController::class, 'toDetailPO'])->name('toDetailPO');
    });

    Route::group(['middleware'=>'can:po_approval'],function (){
        Route::get('poapproval/showinvoice', [POApprovalController::class, 'showInvoice'])->name('showInvoice');
        Route::resource('poapproval', POApprovalController::class);
        Route::get('searchpoinvc', [POApprovalController::class, 'searchpoinvc'])->name('searchpoinvc');
        Route::post('sendmailapproval', [POApprovalController::class, 'sendMailApproval'])->name('sendMailApproval');
        Route::get('browsehistory', [POApprovalController::class, 'browseHistSent'])->name('browseHistSent');
    });

    Route::group(['middleware'=>'can:po_browse'],function (){
        Route::get('/poreceiptbrw/toexcel', [POBrowseController::class, 'exportexcel'])->name('exportExcel');
        Route::resource('poreceiptbrw', POBrowseController::class);
    });

    Route::group(['middleware'=>'can:receipt_unplanned'],function(){
        Route::resource('rcptunplanned', RcptUnplannedController::class);
    });

    

    Route::group(['middleware'=>'can:sj_create'],function(){
        Route::get('suratjalan/createbrowse', [SuratJalanController::class, 'createBrowse'])->name('createBrowse');
        Route::resource('suratjalan', SuratJalanController::class);
        Route::get('searchso',[SuratJalanController::class, 'searchso'])->name('searchSO');
    });

    Route::group(['middleware'=>'can:sj_browse'],function(){
        Route::get('browsesj',[SuratJalanController::class, 'browsesj'])->name('browseSJ');
        Route::get('browsesj/editjsbrowse/{id}',[SuratJalanController::class, 'editjsbrowse'])->name('editSJBrowse');
        Route::post('browsesj/deletejsbrowse/{id}',[SuratJalanController::class, 'deletejsbrowse'])->name('deleteSJBrowse');
        Route::get('browsesj/viewjsbrowse/{id}',[SuratJalanController::class, 'viewjsbrowse'])->name('viewSJBrowse');

        Route::get('browsesj/changesjbrowse/{id}', [SuratJalanController::class, 'changesjbrowse'])->name('changeSJBrowse');
        Route::get('browsesj/searchchangesj', [SuratJalanController::class, 'searchchangesj'])->name('searchChangeSJ');
        Route::get('browsesj/dispchangesj', [SuratJalanController::class, 'dispchangesj'])->name('dispChangeSJ');
        Route::post('browsesj/updatechangesj', [SuratJalanController::class, 'updatechangesj'])->name('updateChangeSJ');
        Route::get('browsesj/sjtoexcel', [SuratJalanController::class, 'sjtoexcel'])->name('sjtoexcel');
    });

    Route::group(['middleware'=>'can:sj_confirm'],function(){
        Route::get('sjconfirm/sjtoexcel', [SuratJalanConfirmController::class, 'sjtoexcel'])->name('sjconfirmexcel');
        Route::resource('sjconfirm', SuratJalanConfirmController::class);
    });

    

    Route::group(['middleware'=>'can:stock_item'],function(){
        Route::resource('stockitm', StockItemController::class);
    });

    Route::group(['middleware'=>'can:hutang_cust'],function(){
        Route::resource('hutangcust', HutangCustController::class);
    });


    Route::group(['middleware'=>'can:view_item'],function(){
        Route::resource('viewitem', ItemStockController::class);
        Route::get('viewitem/detailitem/{id}/{dom}',[ItemStockController::class, 'getdetail'])->name('getDetailItem');
    });

    Route::group(['middleware'=>'can:view_dashboard'],function(){
        Route::resource('dashboard',DashboardController::class);
        Route::get('getallsj',[DashboardController::class, 'getallsj'])->name('getAllSJ');
        Route::get('getallpartsj',[DashboardController::class, 'getallpartsj'])->name('getAllPartSJ');
        Route::get('getstokitemlokasi',[DashboardController::class, 'getstokitemlokasi'])->name('getStokItemLokasi');

        Route::get('detailsj/{bulan}/{tahun}', [DashboardController::class, 'detailsj'])->name('detailSJ');
        Route::get('detailsjpart/{part}', [DashboardController::class, 'detailsjpart'])->name('detailSJPart');
        Route::get('detailinvoice/{bulan}', [DashboardController::class, 'detailinvoice'])->name('detailInvoice');
        Route::get('detailhutang/{bulan}', [DashboardController::class, 'detailhutang'])->name('detailHutang');
        Route::get('detailstokitem/{lokasi}/{tipe}', [DashboardController::class, 'detailstokitem'])->name('detailStokItem');
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

        // Supplier Masters
        //================================
        Route::resource('suppmstr', SuppMstrController::class);
        //================================

        // Location Masters
        //================================
        Route::resource('locmstr', LocMstrController::class);
        //================================

        // Site Masters
        //================================
        Route::resource('sitemstr', SiteMstrController::class);
        //================================

        // Domain Masters
        //================================
        Route::resource('domainmstr', DomainController::class);
        //================================

    });
});