<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SalesController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\AuthController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('customer')->group(function () {
        Route::get('/', [CustomerController::class, 'listCustomers']);
        Route::get('{id}', [CustomerController::class, 'getCustomer']);
        Route::post('/create', [CustomerController::class, 'createCustomer']);
        Route::put('update/{id}', [CustomerController::class, 'updateCustomer']);
        Route::delete('{id}', [CustomerController::class, 'deleteCustomer']);
    });

    Route::prefix('sales')->group(function () {
        Route::put('newOrder', [SalesController::class, 'newOrder']);
        Route::get('/', [SalesController::class, 'listOrders']);
    });

    Route::prefix('product')->group(function () {
        Route::post('create', [ProductController::class, 'creteProduct']);
        Route::put('update/{id}', [ProductController::class, 'updateProduct']);
        Route::delete('delete/{id}', [ProductController::class, 'deleteProduct']);
        Route::get('/', [ProductController::class, 'listProducts']);
    });


    Route::prefix('statistics')->group(function () {
        Route::get('CustomerMostVolume', [StatisticsController::class, 'getCustomerMostVolume']);
        Route::get('CustomerAverageSale', [StatisticsController::class, 'getCustomerMostAverageValue']);
        Route::get('CustomerFrequent', [StatisticsController::class, 'getCustomerFrequent']);
        Route::get('TotalSalesPerDay', [StatisticsController::class, 'getTotalSalesPerDay']);
    });
    
});

Route::fallback(function () {
    return response()->json(['message' => 'Route not found'], 404);
});

 



?> 
