<?php



use App\Http\Controllers\customer\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\customer\LoginController;
use App\Http\Controllers\customer\BookingController;
use App\Http\Controllers\customer\PaymentController;


Route::controller(LoginController::class)->group(function () {
    Route::match(['GET', 'POST'], '/', 'login')->name('customer.login');
    Route::match(['GET', 'POST'], '/sendOtp', 'sendOtp')->name('customer.sendOtp');
    Route::match(['GET', 'POST'], '/verifyOtp', 'verifyOtp')->name('customer.verifyOtp');
    Route::match(['GET', 'POST'], '/register', 'register')->name('customer.register');
    Route::match(['GET', 'POST'], '/franchise/payment/store', 'paymentStore')->name('customer.payment.store');
    Route::match(['GET', 'POST'], '/verifyPhoneNumberOtp', 'verifyPhoneNumberOtp')->name('customer.verifyPhoneNumberOtp');
    Route::match(['GET', 'POST'], '/otpViewPage', 'otpViewPage')->name('customer.otpViewPage');
    Route::match(['GET', 'POST'], '/resendPhoneNumberOtp', 'resendPhoneNumberOtp')->name('customer.resendPhoneNumberOtp');
    Route::match(['GET', 'POST'], '/location', 'getLocation')->name('customer.get.location');
});


Route::group([

    'middleware' => ['customer']

], function () {

    Route::controller(LoginController::class)->group(function () {
        Route::match(['GET', 'POST'], '/logout', 'logout')->name('customer.logout');
        Route::match(['GET', 'POST'], '/profile', 'profile')->name('customer.profile');
        Route::match(['GET', 'POST'], 'profile/update/{id}', 'update')->name('customer.profile.update');
    });

    Route::controller(DashboardController::class)->group(function () {

        Route::get('/dashboard', 'index')->name('customer.dashboard');
    });

     Route::controller(BookingController::class)->group(function () {
         Route::get('/booking/cod', 'index')->name('customer.booking.parcel.cod');
         Route::get('/booking/prepaids', 'prepaids')->name('customer.booking.parcel.prepaids');
         Route::get('/downloadTableForCreatedTable', 'downloadTableForCreatedTable')->name('customer.parcel.downloadTableForCreatedTable');
         Route::get('/view/{id}', 'view')->name('customer.parcel.view');
         Route::get('/full-print/{id}', 'fullPrint')->name('customer.parcel.full-print');
         Route::get('/trackOrder/{id}', 'trackOrder')->name('customer.parcel.trackOrder');
          Route::get('/booking/short-print', 'shortPrintForCreatedParcel')->name('customer.booking.shortPrintForCreatedParcel');
    });
       
       Route::controller(PaymentController::class)->prefix('payment')->group(function () {
        Route::get('/', 'index')->name('customer.payment.index');
        Route::get('/recharge', 'recharge_index')->name('customer.payment.recharge.index');
    });
});


