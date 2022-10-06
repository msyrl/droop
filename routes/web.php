<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/auth/signin', 'auth.signin')->middleware('guest');
Route::post('/auth/signin', [\App\Http\Controllers\AuthController::class, 'signin'])->middleware('guest');
Route::post('/auth/signout', [\App\Http\Controllers\AuthController::class, 'signout'])->middleware('auth');

Route::view('/auth/register', 'auth.register')->middleware('guest');
Route::post('/auth/register', [\App\Http\Controllers\AuthController::class, 'register'])->middleware('guest');

Route::group([
    'middleware' => ['auth']
], function () {
    Route::view('/', 'welcome');
    Route::view('/dashboard', 'welcome');

    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->can(\App\Enums\PermissionEnum::view_users());
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->can(\App\Enums\PermissionEnum::manage_users());
    Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->can(\App\Enums\PermissionEnum::manage_users());
    Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show'])->can(\App\Enums\PermissionEnum::manage_users());
    Route::put('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->can(\App\Enums\PermissionEnum::manage_users());
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->can(\App\Enums\PermissionEnum::manage_users());

    Route::get('/roles', [\App\Http\Controllers\RoleController::class, 'index'])->can(\App\Enums\PermissionEnum::view_roles());
    Route::get('/roles/create', [\App\Http\Controllers\RoleController::class, 'create'])->can(\App\Enums\PermissionEnum::manage_roles());
    Route::post('/roles', [\App\Http\Controllers\RoleController::class, 'store'])->can(\App\Enums\PermissionEnum::manage_roles());
    Route::get('/roles/{role}', [\App\Http\Controllers\RoleController::class, 'show'])->can(\App\Enums\PermissionEnum::manage_roles());
    Route::put('/roles/{role}', [\App\Http\Controllers\RoleController::class, 'update'])->can(\App\Enums\PermissionEnum::manage_roles());
    Route::delete('/roles/{role}', [\App\Http\Controllers\RoleController::class, 'destroy'])->can(\App\Enums\PermissionEnum::manage_roles());

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index']);
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update']);
    Route::get('/profile/password', [\App\Http\Controllers\ProfilePasswordController::class, 'index']);
    Route::put('/profile/password', [\App\Http\Controllers\ProfilePasswordController::class, 'update']);

    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->can(\App\Enums\PermissionEnum::view_products());
    Route::get('/products/create', [\App\Http\Controllers\ProductController::class, 'create'])->can(\App\Enums\PermissionEnum::manage_products());
    Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store'])->can(\App\Enums\PermissionEnum::manage_products());
    Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->can(\App\Enums\PermissionEnum::manage_products());
    Route::put('/products/{product}', [\App\Http\Controllers\ProductController::class, 'update'])->can(\App\Enums\PermissionEnum::manage_products());
    Route::delete('/products/{product}', [\App\Http\Controllers\ProductController::class, 'destroy'])->can(\App\Enums\PermissionEnum::manage_products());

    Route::get('/sales-orders', [\App\Http\Controllers\SalesOrderController::class, 'index'])->can(\App\Enums\PermissionEnum::view_sales_orders());
    Route::get('/sales-orders/{salesOrder}', [\App\Http\Controllers\SalesOrderController::class, 'show'])->can(\App\Enums\PermissionEnum::view_sales_orders());
    Route::put('/sales-orders/{salesOrder}', [\App\Http\Controllers\SalesOrderController::class, 'update'])->can(\App\Enums\PermissionEnum::view_sales_orders());
    Route::get('/sales-orders/{salesOrder}/invoice', [\App\Http\Controllers\SalesOrderInvoiceController::class, 'index'])->can(\App\Enums\PermissionEnum::view_sales_orders());

    Route::get('/catalogs', [\App\Http\Controllers\CatalogController::class, 'index']);
    Route::get('/catalogs/{product}', [\App\Http\Controllers\CatalogController::class, 'show']);

    Route::get('/my/purchases', [\App\Http\Controllers\MyPurchaseController::class, 'index']);
    Route::get('/my/purchases/{purchaseId}', [\App\Http\Controllers\MyPurchaseController::class, 'show']);
    Route::get('/my/purchases/{purchaseId}/invoice', [\App\Http\Controllers\MyPurchaseInvoiceController::class, 'index']);

    Route::get('/my/cart', [\App\Http\Controllers\MyCartController::class, 'index']);
    Route::post('/my/cart', [\App\Http\Controllers\MyCartController::class, 'store']);
    Route::put('/my/cart', [\App\Http\Controllers\MyCartController::class, 'update']);
    Route::delete('/my/cart', [\App\Http\Controllers\MyCartController::class, 'destroy']);

    Route::get('/my/cart/checkout', [\App\Http\Controllers\MyCartCheckoutController::class, 'index']);
    Route::post('/my/cart/checkout', [\App\Http\Controllers\MyCartCheckoutController::class, 'store']);
});
