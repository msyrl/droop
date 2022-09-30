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

Route::group([
    'middleware' => ['auth']
], function () {
    Route::view('/', 'welcome');
    Route::view('/dashboard', 'welcome');

    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->can(\App\Enums\PermissionEnum::view_users());
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->can(\App\Enums\PermissionEnum::create_user());
    Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->can(\App\Enums\PermissionEnum::create_user());
    Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show'])->can(\App\Enums\PermissionEnum::view_users());
    Route::put('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->can(\App\Enums\PermissionEnum::update_user());
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->can(\App\Enums\PermissionEnum::delete_user());

    Route::get('/roles', [\App\Http\Controllers\RoleController::class, 'index'])->can(\App\Enums\PermissionEnum::view_roles());
    Route::get('/roles/create', [\App\Http\Controllers\RoleController::class, 'create'])->can(\App\Enums\PermissionEnum::create_role());
    Route::post('/roles', [\App\Http\Controllers\RoleController::class, 'store'])->can(\App\Enums\PermissionEnum::create_role());
    Route::get('/roles/{role}', [\App\Http\Controllers\RoleController::class, 'show'])->can(\App\Enums\PermissionEnum::view_roles());
    Route::put('/roles/{role}', [\App\Http\Controllers\RoleController::class, 'update'])->can(\App\Enums\PermissionEnum::update_role());
    Route::delete('/roles/{role}', [\App\Http\Controllers\RoleController::class, 'destroy'])->can(\App\Enums\PermissionEnum::delete_role());

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index']);
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update']);
    Route::get('/profile/password', [\App\Http\Controllers\ProfilePasswordController::class, 'index']);
    Route::put('/profile/password', [\App\Http\Controllers\ProfilePasswordController::class, 'update']);

    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->can(\App\Enums\PermissionEnum::view_products());
    Route::get('/products/create', [\App\Http\Controllers\ProductController::class, 'create'])->can(\App\Enums\PermissionEnum::manage_products());
});
