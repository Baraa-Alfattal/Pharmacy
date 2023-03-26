<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PharmacyController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post("register", [UserController::class, "register"]);
Route::post("login", [UserController::class, "login"]);

Route::group(["middleware" => ["auth:sanctum"]], function(){

    Route::get("profile", [UserController::class, "profile"]);
    Route::post("update", [UserController::class, "update"]);
    Route::get("logout", [UserController::class, "logout"]);

    // course api routes
   
});

Route::post("ph_register", [PharmacyController::class, "register"]);
Route::post("ph_login", [PharmacyController::class, "login"]);

Route::group(["middleware" => ["auth:sanctum"]], function(){

    Route::get("ph_profile", [PharmacyController::class, "profile"]);
    Route::post("ph_update", [PharmacyController::class, "ph_update"]);
    Route::get("ph_logout", [PharmacyController::class, "logout"]);

    // medican api routes
    Route::post("add", [PharmacyController::class, "add"]);
    Route::get("total_medican", [PharmacyController::class, "totalmedicans"]);
    Route::get("deleteMedican/{id}", [PharmacyController::class, "deleteMedican"]);
    Route::post("update_medi_id/{id}", [PharmacyController::class, "update_medi_id"]);
    Route::post("update_medi", [PharmacyController::class, "update_medi"]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
