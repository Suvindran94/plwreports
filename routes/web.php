<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('/');

Route::get('autologin', function () {
    $user = $_GET['id'];
    Auth::loginUsingId($user, true);
    return redirect()->intended('/productionDailyReportDash');
});

Route::get('/ierphome', function () {
    auth()->logout();
    return redirect()->intended('https://ierp.tk/home2');
})->name('/ierphome');

Route::get('/productionHourlyDash', [DashboardController::class, 'productDashHourly'])->name('productDashHourly');
Route::get('/productionHourlyDashAjax', [DashboardController::class, 'productDashHourlyAjax'])->name('productDashHourlyAjax');

Route::get('/productionDailyReportDash', [DashboardController::class, 'productDashDailyReport'])->name('productionDailyReportDash');
Route::get('/productionDailyReportDashAjax', [DashboardController::class, 'productDashDailyReportAjax'])->name('productionDailyReportDashAjax');


Route::get('/dailyEffTrackDash', [DashboardController::class, 'dailyEfficientTrack'])->name('dailyEffTrackDash');
Route::get('/dailyEffTrackDashAjax', [DashboardController::class, 'dailyEfficientTrackAjax'])->name('dailyEffTrackDashAjax');

