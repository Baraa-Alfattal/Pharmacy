<?php

use App\Http\Controllers\API\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PharmacyController;
use App\Http\Controllers\API\SaleController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TestController;

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

Route::group(["middleware" => ["auth:sanctum"]], function () {

    Route::get("profile", [UserController::class, "profile"]);
    Route::post("update", [UserController::class, "update"]);
    Route::get("logout", [UserController::class, "logout"]);
    Route::get('/add_cart', [UserController::class, "add_cart"]);

    // course api routes

});

Route::post("ph_register", [PharmacyController::class, "register"]);
Route::post("ph_login", [PharmacyController::class, "login"]);

Route::group(["middleware" => ["auth:sanctum"]], function () {

    Route::get("ph_profile", [PharmacyController::class, "profile"]);
    Route::post("ph_update", [PharmacyController::class, "ph_update"]);
    Route::get("ph_logout", [PharmacyController::class, "logout"]);

    // medican api routes
    Route::post("add", [PharmacyController::class, "add"]);
    Route::get("total_medican", [PharmacyController::class, "totalmedicans"]);
    Route::get("deleteMedican/{id}", [PharmacyController::class, "deleteMedican"]);
    Route::post("update_medi_id/{id}", [PharmacyController::class, "update_medi_id"]);
    Route::post("update_medi", [PharmacyController::class, "update_medi"]);

    // product api routes
    Route::post("add_product", [ProductController::class, "add_product"]);
    Route::get("total_product", [ProductController::class, "totalproduct"]);
    Route::get("deleteProduct/{id}", [ProductController::class, "deleteProduct"]);
    Route::post("update_pro_id/{id}", [ProductController::class, "update_product_id"]);
    Route::post("update_product", [ProductController::class, "update_product"]);
    // cart api routes

    Route::controller(CartController::class)->group(function () {
        Route::get("cart", "index");
    });

    Route::post("add_sale", [SaleController::class, "sale"]);
    Route::post("add_sale_name", [SaleController::class, "sale_name"]);

    Route::post("search1", [PharmacyController::class, "search"]);


    Route::get("daily", [TestController::class, "daily_handle"]);
    Route::get("monthly", [TestController::class, "monthly_handle"]);


    Route::post("that_day", [TestController::class, "get_daily_earnings"]);
    Route::get("7day", [TestController::class, "get_7day"]);

    Route::post("add_notification", [UserController::class, "add_notification"]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
