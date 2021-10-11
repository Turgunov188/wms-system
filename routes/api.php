<?php

use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductMaterialController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarehouseController;
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

Route::prefix('v1')->group(function (){
    Route::apiResource('units', UnitController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('materials', MaterialController::class);
    Route::apiResource('product-materials', ProductMaterialController::class);
    Route::apiResource('warehouses', WarehouseController::class);
    Route::post('warehouse-productions', [WarehouseController::class, 'production']);

});
