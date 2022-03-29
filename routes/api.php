<?php

use App\Http\Controllers\API\APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Jika yes approve
route::get('/apiapprovalinvoice/yes/{ponbr}/{invcnbr}',[APIController::class,'approvedInvcYes']);

//jika no approve
route::get('/apiapprovalinvoice/no/{ponbr}/{invcnbr}',[APIController::class,'approvedInvcNo']);