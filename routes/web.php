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
    return redirect()->intended('/hourlyEffTrackCFDash');
});

Route::get('/ierphome', function () {
    auth()->logout();
    return redirect()->intended('https://ierp.tk/home2');
})->name('/ierphome');

// Route::get('/productionHourlyDash', [DashboardController::class, 'productDashHourly'])->name('productDashHourly');
// Route::get('/productionHourlyDashAjax', [DashboardController::class, 'productDashHourlyAjax'])->name('productDashHourlyAjax');

Route::get('/hourlyEffTrackCFDash', [DashboardController::class, 'hourlyEfficientTrackCF'])->name('hourlyEffTrackCFDash');
Route::get('/hourlyEffTrackCFDashAjax', [DashboardController::class, 'hourlyEfficientTrackCFAjax'])->name('hourlyEffTrackCFDashAjax');

Route::get('/hourlyEffTrackPEDash', [DashboardController::class, 'hourlyEfficientTrackPE'])->name('hourlyEffTrackPEDash');
Route::get('/hourlyEffTrackPEDashAjax', [DashboardController::class, 'hourlyEfficientTrackPEAjax'])->name('hourlyEffTrackPEDashAjax');

Route::get('/dailyEffTrackCFDash', [DashboardController::class, 'dailyEfficientTrackCF'])->name('dailyEffTrackCFDash');
Route::get('/dailyEffTrackCFDashAjax', [DashboardController::class, 'dailyEfficientTrackCFAjax'])->name('dailyEffTrackCFDashAjax');

Route::get('/gethourlyEfficientCEDetails', [DashboardController::class, 'hourlyEfficientCEDetails'])->name('gethourlyEfficientCEDetails');
