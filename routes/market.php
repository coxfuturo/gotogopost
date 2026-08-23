<?php
use App\Http\Controllers\market\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\market\LoginController;
use App\Http\Controllers\market\BookingController;
use App\Http\Controllers\market\CustomerController;
use App\Http\Controllers\market\CommissionController;
use App\Http\Controllers\market\RateCalculator;


Route::controller(LoginController::class)->group(function () {
    Route::match(['GET', 'POST'], '/', 'login')->name('market.login');
   // Route::match(['GET', 'POST'], '/sendOtp', 'sendOtp')->name('market.sendOtp');
   // Route::match(['GET', 'POST'], '/verifyOtp', 'verifyOtp')->name('market.verifyOtp');
    Route::match(['GET', 'POST'], '/register', 'register')->name('market.register');
    Route::match(['GET', 'POST'], '/franchise/payment/store', 'paymentStore')->name('market.payment.store');
   // Route::match(['GET', 'POST'], '/verifyPhoneNumberOtp', 'verifyPhoneNumberOtp')->name('market.verifyPhoneNumberOtp');
    //Route::match(['GET', 'POST'], '/otpViewPage', 'otpViewPage')->name('market.otpViewPage');
    //Route::match(['GET', 'POST'], '/resendPhoneNumberOtp', 'resendPhoneNumberOtp')->name('market.resendPhoneNumberOtp');
    Route::match(['GET', 'POST'], '/location', 'getLocation')->name('market.get.location');
});


Route::group([

    'middleware' => ['market']

], function () {

    Route::controller(LoginController::class)->group(function () {
        Route::match(['GET', 'POST'], '/logout', 'logout')->name('market.logout');
        Route::match(['GET', 'POST'], '/profile', 'profile')->name('market.profile');
        Route::match(['GET', 'POST'], 'profile/update/{id}', 'update')->name('market.profile.update');
    });

    Route::controller(DashboardController::class)->group(function () {

        Route::get('/dashboard', 'index')->name('market.dashboard');
    });

    Route::controller(CustomerController::class)->group(function () {

        Route::get('/customer', 'index')->name('market.customer.index');
        Route::get('/customer/create', 'create')->name('market.customer.create');
        Route::post('/customer/store', 'store')->name('market.customer.store');
        Route::get('/customer/edit/{id}', 'edit')->name('market.customer.edit');
        Route::post('/customer/update/{id}', 'update')->name('market.customer.update');
        Route::get('/customer/view/{id}/{type}', 'view')->name('market.customer.view.index');
        Route::get('/customer/export', 'export')->name('market.customer.export');
        Route::get('/customer/export/parcel', 'parcelExport')->name('market.customer.export.parcel');
        Route::post('/customer/parcel/cancel/{id}/{type}', 'cancel')->name('market.customer.parcel.cancel');
        Route::get('/customer/parcel/cancel/report/{id}/{type}', 'cancelReport')->name('market.customer.parcel.cancel.report');
        Route::get('/customer/export/parcel/cancel', 'parcelExportCancel')->name('market.customer.export.parcel.cancel');
        Route::get('/customer/parcel/details/{id}/{type}', 'details')->name('market.customer.parcel.details');
        Route::get('/customer/parcel/trackOrder/{id}/{type}', 'trackOrder')->name('market.customer.parcel.trackOrder');
    });

     Route::controller(BookingController::class)->group(function () {
         Route::get('/booking/cod', 'index')->name('market.booking.parcel.cod');
         Route::get('/booking/prepaids', 'prepaids')->name('market.booking.parcel.prepaids');
         Route::get('/downloadTableForCreatedTable', 'downloadTableForCreatedTable')->name('market.parcel.downloadTableForCreatedTable');
         Route::get('/view/{id}', 'view')->name('market.parcel.view');
         Route::get('/full-print/{id}', 'fullPrint')->name('market.parcel.full-print');
         Route::get('/trackOrder/{id}', 'trackOrder')->name('market.parcel.trackOrder');
    });

     Route::controller(CommissionController::class)->group(function () {
         Route::get('/commission/index', 'index')->name('market.commission.index');
         Route::get('/commission/printCommissionDetail', 'printCommissionDetail')->name('market.commission.printCommissionDetail');
    });


});