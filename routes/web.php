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


Route::get('/', [App\Http\Controllers\LandingPage\IndexController::class, 'index'])->name('home');

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth', 'prefix' => 'page/user', 'as' => 'user.'], function () {
    Route::get('dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

    Route::get('change-password', [App\Http\Controllers\User\DashboardController::class, 'changePasswordForm'])->name('form-change-password');
    Route::post('change-password', [App\Http\Controllers\User\DashboardController::class, 'changePasswordProcess'])->name('process-change-password');

    // Method
    Route::resource('method', App\Http\Controllers\User\LA\LAMethodController::class, ["only" => ['index', 'store', 'update', 'destroy']]);
    Route::get('method/index/get-data', [App\Http\Controllers\User\LA\LAMethodController::class, 'getData'])->name('method.index.get-data');
    Route::post('method/select/get-data', [App\Http\Controllers\User\LA\LAMethodController::class, 'getDataSelect'])->name('method.select.get-data');
    Route::post('method/trash/restore', [App\Http\Controllers\User\LA\LAMethodController::class, 'restore'])->name('method.trash.restore');
    Route::delete('method/trash/delete-permanent', [App\Http\Controllers\User\LA\LAMethodController::class, 'deletePermanent'])->name('method.trash.delete-permanent');

    // Activity
    Route::resource('activity', App\Http\Controllers\User\LA\LAActivityController::class, ["only" => ['index', 'store', 'update', 'destroy']]);
    Route::get('activity/index/get-data', [App\Http\Controllers\User\LA\LAActivityController::class, 'getData'])->name('activity.index.get-data');
    Route::get('activity/index/get-table-view', [App\Http\Controllers\User\LA\LAActivityController::class, 'getTableView'])->name('activity.index.get-table-view');
    Route::post('activity/trash/restore', [App\Http\Controllers\User\LA\LAActivityController::class, 'restore'])->name('activity.trash.restore');
    Route::delete('activity/trash/delete-permanent', [App\Http\Controllers\User\LA\LAActivityController::class, 'deletePermanent'])->name('activity.trash.delete-permanent');

});
