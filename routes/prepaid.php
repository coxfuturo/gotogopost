<?php

use App\Http\Controllers\customer\DashboardController;
use App\Http\Controllers\PrepaidController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\customer\LoginController;
use App\Http\Controllers\customer\BookingController;
use App\Http\Controllers\customer\PaymentController;

Route::group([
    'middleware' => ['prepaid']
], function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/prepaid/dashboard', 'prepaid')->name('prepaid.dashboard');
    });

    Route::controller(PrepaidController::class)->group(function () {
        Route::get('/prepaid/profile', 'showProfile')->name('prepaid.profile');
        Route::get('/booking/downloadTableForCreatedTable', 'downloadTableForCreatedTable')
        ->name('prepaid.booking.downloadTableForCreatedTable');
        Route::get('/booking/short-print', 'shortPrintForCreatedParcel')
        ->name('prepaid.booking.shortPrintForCreatedParcel');            
        Route::get('/booking/{type}', 'index')
        ->name('prepaid.booking.index');
    });
});