<?php



use App\Http\Controllers\deliveryBoy\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\deliveryBoy\LoginController;
use App\Http\Controllers\deliveryBoy\BagController;

use App\Http\Controllers\deliveryBoy\GotogoSpeedPostController;
use App\Http\Controllers\deliveryBoy\GotogoSuperFastController;
use App\Http\Controllers\deliveryBoy\GotogoBusinessParcelController;
use App\Http\Controllers\deliveryBoy\GotogoRegisteredController;
use App\Http\Controllers\deliveryBoy\IndiaPostSpeedPostController;
use App\Http\Controllers\deliveryBoy\IndiaPostBusinessController;
use App\Http\Controllers\deliveryBoy\IndiaPostRegisteredController;
use App\Http\Controllers\deliveryBoy\MailToFranchiseController;
use App\Http\Controllers\deliveryBoy\ParcelPaymentController;


Route::controller(LoginController::class)->group(function () {
    Route::match(['GET', 'POST'], '/login', 'login')->name('deliveryBoy.login');
    Route::match(['GET', 'POST'], '/sendOtp', 'sendOtp')->name('deliveryBoy.sendOtp');
    Route::match(['GET', 'POST'], '/verifyOtp', 'verifyOtp')->name('deliveryBoy.verifyOtp');
    Route::match(['GET', 'POST'], '/verifyPhoneNumberOtp', 'verifyPhoneNumberOtp')->name('deliveryBoy.verifyPhoneNumberOtp');
    Route::match(['GET', 'POST'], '/resendPhoneNumberOtp', 'resendPhoneNumberOtp')->name('deliveryBoy.resendPhoneNumberOtp');
    Route::match(['GET', 'POST'], '/register', 'register')->name('deliveryBoy.register');
    Route::match(['GET', 'POST'], '/otpViewPage', 'otpViewPage')->name('deliveryBoy.otpViewPage');
    
    Route::match(['GET', 'POST'], '/franchise_state', 'franchise_state')->name('deliveryBoy.franchise_state');
});
Route::post('profile/update/{id}', [LoginController::class, 'update'])->name('deliveryBoy.profile.update');


Route::group([

    'middleware' => ['deliveryBoyAuth']

], function () {

    Route::controller(LoginController::class)->group(function () {
        Route::match(['GET', 'POST'], '/logout', 'logout')->name('deliveryBoy.logout');
        Route::match(['GET', 'POST'], '/profile', 'profile')->name('deliveryBoy.profile');
    });

    Route::controller(DashboardController::class)->group(function () {

        Route::get('/dashboard', 'index')->name('deliveryBoy.dashboard');
    });



    //bag  Management

    Route::controller(BagController::class)->prefix('bag')->group(function () {

        Route::get('/received-bag', 'showRecievedBags')->name('deliveryBoy.bag.showRecievedBags');
        Route::post('/received-bag-search', 'receivedBagsSearch')->name('deliveryBoy.bag.receivedBagsSearch');
        Route::get('/viewParcelfr/{id}', 'viewParcels_of_receivedBag_from_franchise')->name('deliveryBoy.bag.viewParcelfranchiseReceived');
        Route::get('/showAllParcelToDeliver', 'showAllParcelToDeliver')->name('deliveryBoy.bag.showAllParcelToDeliver');
        Route::get('/showAllDeliveredParcel', 'showAllDeliveredParcel')->name('deliveryBoy.bag.showAllDeliveredParcel');
        Route::get('/sendOtp', 'sendOtp')->name('deliveryBoy.bag.sendOtp');
        Route::match(['GET', 'POST'], '/verifyOtp', 'verifyOtp')->name('deliveryBoy.bag.verifyOtp');
        Route::match(['GET', 'POST'], '/cancelDelivery', 'cancelDelivery')->name('deliveryBoy.bag.cancelDelivery');
        Route::match(['GET', 'POST'], '/pickupList', 'pickupList')->name('deliveryBoy.bag.pickupList');
    });



    //goto go speed post Management
    Route::controller(GotogoSpeedPostController::class)->prefix('go-speed-post')->group(function () {
        Route::get('/index', 'index')->name('deliveryBoy.go-speed-post-parcel.index');
        Route::get('/view/{id}', 'view')->name('deliveryBoy.go-speed-post-parcel.view');
    });


    //goto go superfast Management
    Route::controller(GotogoSuperFastController::class)->prefix('go-super-fast')->group(function () {
        Route::get('/index', 'index')->name('deliveryBoy.go-super-fast-parcel.index');
        Route::get('/create', 'create')->name('deliveryBoy.go-super-fast-parcel.create');
        Route::post('/store', 'store')->name('deliveryBoy.go-super-fast-parcel.store');
        Route::post('/storeByfile', 'storeByfile')->name('deliveryBoy.go-super-fast-parcel.storeByfile');
        Route::get('/delete/{id}', 'delete')->name('deliveryBoy.go-super-fast-parcel.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('deliveryBoy.go-super-fast-parcel.edit');
        Route::get('/view/{id}', 'view')->name('deliveryBoy.go-super-fast-parcel.view');
        Route::get('/download-format', 'downloadFormat')->name('deliveryBoy.go-super-fast-parcel.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('deliveryBoy.go-super-fast-parcel.full-print');
        Route::get('/short-print', 'shortPrint')->name('deliveryBoy.go-super-fast-parcel.short-print');
        Route::get('downloadTable', 'downloadTable')->name('deliveryBoy.go-super-fast-parcel.downloadTable');
    });

    //goto go business parcel Management
    Route::controller(GotogoBusinessParcelController::class)->prefix('go-business-parcel')->group(function () {
        Route::get('/index', 'index')->name('deliveryBoy.go-business-parcel.index');
        Route::get('/create', 'create')->name('deliveryBoy.go-business-parcel.create');
        Route::post('/store', 'store')->name('deliveryBoy.go-business-parcel.store');
        Route::post('/storeByfile', 'storeByfile')->name('deliveryBoy.go-business-parcel.storeByfile');
        Route::get('/delete/{id}', 'delete')->name('deliveryBoy.go-business-parcel.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('deliveryBoy.go-business-parcel.edit');
        Route::get('/view/{id}', 'view')->name('deliveryBoy.go-business-parcel.view');
        Route::get('/download-format', 'downloadFormat')->name('deliveryBoy.go-business-parcel.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('deliveryBoy.go-business-parcel.full-print');
        Route::get('/short-print', 'shortPrint')->name('deliveryBoy.go-business-parcel.short-print');
        Route::get('downloadTable', 'downloadTable')->name('deliveryBoy.go-business-parcel.downloadTable');
    });

    //goto go registered
    Route::controller(GotogoRegisteredController::class)->prefix('go-registered')->group(function () {
        Route::get('/index', 'index')->name('deliveryBoy.go-registered.index');
        Route::get('/create', 'create')->name('deliveryBoy.go-registered.create');
        Route::post('/store', 'store')->name('deliveryBoy.go-registered.store');
        Route::post('/storeByfile', 'storeByfile')->name('deliveryBoy.go-registered.storeByfile');
        Route::get('/delete/{id}', 'delete')->name('deliveryBoy.go-registered.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('deliveryBoy.go-registered.edit');
        Route::get('/view/{id}', 'view')->name('deliveryBoy.go-registered.view');
        Route::get('/download-format', 'downloadFormat')->name('deliveryBoy.go-registered.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('deliveryBoy.go-registered.full-print');
        Route::get('/short-print', 'shortPrint')->name('deliveryBoy.go-registered.short-print');
        Route::get('downloadTable', 'downloadTable')->name('deliveryBoy.go-registered.downloadTable');
    });

    //india Post speed post
    Route::controller(IndiaPostSpeedPostController::class)->prefix('india-post-speed-post')->group(function () {
        Route::get('/index', 'index')->name('deliveryBoy.india-post-speed-post.index');
        Route::get('/create', 'create')->name('deliveryBoy.india-post-speed-post.create');
        Route::post('/store', 'store')->name('deliveryBoy.india-post-speed-post.store');
        Route::post('/storeByfile', 'storeByfile')->name('deliveryBoy.india-post-speed-post.storeByfile');
        Route::get('/delete/{id}', 'delete')->name('deliveryBoy.india-post-speed-post.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('deliveryBoy.india-post-speed-post.edit');
        Route::get('/view/{id}', 'view')->name('deliveryBoy.india-post-speed-post.view');
        Route::get('/download-format', 'downloadFormat')->name('deliveryBoy.india-post-speed-post.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('deliveryBoy.india-post-speed-post.full-print');
        Route::get('/short-print', 'shortPrint')->name('deliveryBoy.india-post-speed-post.short-print');
        Route::get('downloadTable', 'downloadTable')->name('deliveryBoy.india-post-speed-post.downloadTable');
    });


    //india Post business parcel
    Route::controller(IndiaPostBusinessController::class)->prefix('india-post-business')->group(function () {
        Route::get('/index', 'index')->name('deliveryBoy.india-post-business.index');
        Route::get('/create', 'create')->name('deliveryBoy.india-post-business.create');
        Route::post('/store', 'store')->name('deliveryBoy.india-post-business.store');
        Route::post('/storeByfile', 'storeByfile')->name('deliveryBoy.india-post-business.storeByfile');
        Route::get('/delete/{id}', 'delete')->name('deliveryBoy.india-post-business.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('deliveryBoy.india-post-business.edit');
        Route::get('/view/{id}', 'view')->name('deliveryBoy.india-post-business.view');
        Route::get('/download-format', 'downloadFormat')->name('deliveryBoy.india-post-business.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('deliveryBoy.india-post-business.full-print');
        Route::get('/short-print', 'shortPrint')->name('deliveryBoy.india-post-business.short-print');
        Route::get('downloadTable', 'downloadTable')->name('deliveryBoy.india-post-business.downloadTable');
    });


    //india Post registered letter
    Route::controller(IndiaPostRegisteredController::class)->prefix('india-post-registered')->group(function () {
        Route::get('/index', 'index')->name('deliveryBoy.india-post-registered.index');
        Route::get('/create', 'create')->name('deliveryBoy.india-post-registered.create');
        Route::post('/store', 'store')->name('deliveryBoy.india-post-registered.store');
        Route::post('/storeByfile', 'storeByfile')->name('deliveryBoy.india-post-registered.storeByfile');
        Route::get('/delete/{id}', 'delete')->name('deliveryBoy.india-post-registered.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('deliveryBoy.india-post-registered.edit');
        Route::get('/view/{id}', 'view')->name('deliveryBoy.india-post-registered.view');
        Route::get('/download-format', 'downloadFormat')->name('deliveryBoy.india-post-registered.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('deliveryBoy.india-post-registered.full-print');
        Route::get('/short-print', 'shortPrint')->name('deliveryBoy.india-post-registered.short-print');
        Route::get('downloadTable', 'downloadTable')->name('deliveryBoy.india-post-registered.downloadTable');
    });



    Route::controller(MailToFranchiseController::class)->prefix('mail-to-franchise')->group(function () {
        Route::get('/receivedMails', 'receivedMails')->name('deliveryBoy.mailTofranhchise.receivedMails');
        Route::get('/sentMails', 'sentMails')->name('deliveryBoy.mailTofranhchise.sentMails');
        Route::get('/receivedview/{id}', 'receivedview')->name('deliveryBoy.mailTofranhchise.receivedview');

        Route::match(['GET', 'POST'], '/verifyOtp', 'verifyOtp')->name('deliveryBoy.mailTofranhchise.verifyOtp');
        Route::match(['GET', 'POST'], '/sendOtp', 'sendOtp')->name('deliveryBoy.mailTofranhchise.sendOtp');
        Route::match(['GET', 'POST'], '/cancelDelivery', 'cancelDelivery')->name('deliveryBoy.mailTofranhchise.cancelDelivery');

        Route::get('/viewAssignedMailParcel', 'viewAssignedMailParcel')->name('deliveryBoy.mailTofranhchise.viewAssignedMailParcel');
        Route::get('/viewDeliveredMailParcel', 'viewDeliveredMailParcel')->name('deliveryBoy.mailTofranhchise.viewDeliveredMailParcel');
    });

    Route::controller(ParcelPaymentController::class)->prefix('parcel-payment')->group(function () {
        Route::get('/index', 'index')->name('deliveryBoy.parcel-payment.index');
        Route::post('/store', 'store')->name('deliveryBoy.parcel-payment.store');

        Route::get('/commissionDetail', 'commissionDetail')->name('deliveryBoy.parcel-payment.commissionDetail');
        Route::get('/printCommissionDetail', 'printCommissionDetail')->name('deliveryBoy.parcel-payment.printCommissionDetail');

    });
});
