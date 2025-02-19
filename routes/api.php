<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WarehouseController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\InventoryMovementController;
use App\Http\Controllers\Api\SaleDetailsController;
use App\Http\Controllers\Api\StaffController;

// Rutas de autenticación
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('contacts', ContactController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('sales', SaleController::class);
    Route::apiResource('sales-details', SaleDetailsController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('payment-methods', PaymentMethodController::class);
    Route::apiResource('warehouses', WarehouseController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('inventory-movements', InventoryMovementController::class);
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('staff', StaffController::class);
});

// Ruta de prueba
Route::get('/ping', function () {
    return response()->json(['message' => 'API funcionando correctamente']);
});
