<?php
use App\Http\Controllers\PPH\DashboardController;
use App\Http\Controllers\CMS\cmsRoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PPH\LoginController;
use App\Http\Controllers\CMS\ParcelController;
use App\Http\Controllers\CMS\PickupDetailsController;
use App\Http\Controllers\PPH\BagController;
use App\Http\Controllers\CMS\SoftCopyController;
use App\Http\Controllers\PPH\GotogoSpeedPostController;

use App\Http\Controllers\PPH\GotogoSuperFastController;

use App\Http\Controllers\PPH\GotogoBusinessParcelController;

use App\Http\Controllers\PPH\GotogoRegisteredController;

use App\Http\Controllers\PPH\IndiaPostSpeedPostController;

use App\Http\Controllers\PPH\IndiaPostBusinessController;

use App\Http\Controllers\PPH\IndiaPostRegisteredController;
use App\Http\Controllers\PPH\MailToMailController;
use App\Http\Controllers\PPH\MailToFranchiseController;
use App\Http\Controllers\PPH\PphPaymentController;

Route::controller(LoginController::class)->group(function () {

    Route::match(['GET', 'POST'], '/login', 'login')->name('pph.login');
    Route::match(['GET', 'POST'], '/sendOtp', 'sendOtp')->name('pph.sendOtp');
    Route::match(['GET', 'POST'], '/verifyOtp', 'verifyOtp')->name('pph.verifyOtp');
    Route::match(['GET', 'POST'], '/register', 'register')->name('pph.register');
    Route::match(['GET', 'POST'], '/franchise/payment/store', 'paymentStore')->name('pph.payment.store');
    Route::match(['GET', 'POST'], '/verifyPhoneNumberOtp', 'verifyPhoneNumberOtp')->name('pph.verifyPhoneNumberOtp');
    Route::match(['GET', 'POST'], '/otpViewPage', 'otpViewPage')->name('pph.otpViewPage');
    Route::match(['GET', 'POST'], '/resendPhoneNumberOtp', 'resendPhoneNumberOtp')->name('pph.resendPhoneNumberOtp');
});



Route::view('/call', 'pph.auth.call');
Route::view('/call2', 'pph.auth.call2');



Route::group([

    'middleware' => ['pph']

], function () {


    Route::controller(LoginController::class)->group(function () {
        Route::match(['GET', 'POST'], '/logout', 'logout')->name('pph.logout');
        Route::match(['GET', 'POST'], '/profile', 'profile')->name('pph.profile');
        Route::post('profile/update/{id}', [LoginController::class, 'update'])->name('pph.profile.update');
    });


    Route::controller(DashboardController::class)->group(function () {

        Route::get('/dashboard', 'index')->name('pph.dashboard');
    });



    //Role & Permission

    // Route::controller(cmsRoleController::class)->group(function () {

    //     Route::get('/role/{id}', 'index')->where('id', '[0-9]+')->name('pph.role.index');

    //     Route::post('/role/create', 'role_create')->name('pph.role.create');

    //     Route::post('/permission/store/{id}', 'store_permission')->name('pph.store.permission');

    //     Route::post('/role/update/{id}', 'role_update')->name('pph.role.update');

    //     Route::get('/role/delete/{id}', 'role_delete')->name('pph.role.delete');

    //     //Role Users

    //     Route::get('/role/user', 'role_user')->name('pph.role.user');

    //     Route::post('/role/user/store', 'new_role_user')->name('pph.role.user.store');

    //     Route::post('/role/user/update/{id}', 'update_role_user')->name('pph.role.user.update');

    //     Route::get('/role/user/delete/{id}', 'delete_role_user')->name('pph.role.user.delete');

    //     Route::post('/role/user/status', 'status_role_user')->name('pph.role.user.status');
    // });



    // //PickupDetails  Management

    // Route::controller(PickupDetailsController::class)->prefix('pickup-details')->group(function () {

    //     Route::get('/index', 'index')->name('pph.pickup-details.index');

    //     Route::post('/store', 'store')->name('pph.pickup-details.store');

    //     Route::post('/details', 'details')->name('pph.pickup-details.details');

    //     Route::post('/update/{id}', 'update')->name('pph.pickup-details.update');

    //     Route::get('/delete/{id}', 'delete')->name('pph.pickup-details.delete');
    // });



    // //goto go speed post Management

    Route::controller(GotogoSpeedPostController::class)->prefix('go-speed-post')->group(function () {

        Route::get('/index', 'index')->name('pph.go-speed-post-parcel.index');

        Route::get('/create', 'create')->name('pph.go-speed-post-parcel.create');

        Route::post('/store', 'store')->name('pph.go-speed-post-parcel.store');

        Route::post('/storeByfile', 'storeByfile')->name('pph.go-speed-post-parcel.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('pph.go-speed-post-parcel.getUploadedFileData');

        Route::post('/excelUploadByPph', 'excelUploadByPph')->name('pph.go-speed-post-parcel.excelUploadByPph');

        Route::get('/delete/{id}', 'delete')->name('pph.go-speed-post-parcel.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('pph.go-speed-post-parcel.edit');

        Route::get('/view/{id}', 'view')->name('pph.go-speed-post-parcel.view');

        Route::get('/download-format', 'downloadFormat')->name('pph.go-speed-post-parcel.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('pph.go-speed-post-parcel.full-print');

        Route::get('/short-print', 'shortPrint')->name('pph.go-speed-post-parcel.short-print');

        Route::get('downloadTable', 'downloadTable')->name('pph.go-speed-post-parcel.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('pph.go-speed-post-parcel.trackOrder');
    });





    // //goto go superfast Management

    Route::controller(GotogoSuperFastController::class)->prefix('go-super-fast')->group(function () {

        Route::get('/index', 'index')->name('pph.go-super-fast-parcel.index');

        Route::get('/create', 'create')->name('pph.go-super-fast-parcel.create');

        Route::post('/store', 'store')->name('pph.go-super-fast-parcel.store');

        Route::post('/storeByfile', 'storeByfile')->name('pph.go-super-fast-parcel.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('pph.go-super-fast-parcel.getUploadedFileData');

        Route::post('/excelUploadByPph', 'excelUploadByPph')->name('pph.go-super-fast-parcel.excelUploadByPph');

        Route::get('/delete/{id}', 'delete')->name('pph.go-super-fast-parcel.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('pph.go-super-fast-parcel.edit');

        Route::get('/view/{id}', 'view')->name('pph.go-super-fast-parcel.view');

        Route::get('/download-format', 'downloadFormat')->name('pph.go-super-fast-parcel.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('pph.go-super-fast-parcel.full-print');

        Route::get('/short-print', 'shortPrint')->name('pph.go-super-fast-parcel.short-print');

        Route::get('downloadTable', 'downloadTable')->name('pph.go-super-fast-parcel.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('pph.go-super-fast-parcel.trackOrder');
    });



    // //goto go business parcel Management

    Route::controller(GotogoBusinessParcelController::class)->prefix('go-business-parcel')->group(function () {

        Route::get('/index', 'index')->name('pph.go-business-parcel.index');

        Route::get('/create', 'create')->name('pph.go-business-parcel.create');

        Route::post('/store', 'store')->name('pph.go-business-parcel.store');

        Route::post('/storeByfile', 'storeByfile')->name('pph.go-business-parcel.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('pph.go-business-parcel.getUploadedFileData');

        Route::post('/excelUploadByPph', 'excelUploadByPph')->name('pph.go-business-parcel.excelUploadByPph');

        Route::get('/delete/{id}', 'delete')->name('pph.go-business-parcel.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('pph.go-business-parcel.edit');

        Route::get('/view/{id}', 'view')->name('pph.go-business-parcel.view');

        Route::get('/download-format', 'downloadFormat')->name('pph.go-business-parcel.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('pph.go-business-parcel.full-print');

        Route::get('/short-print', 'shortPrint')->name('pph.go-business-parcel.short-print');

        Route::get('downloadTable', 'downloadTable')->name('pph.go-business-parcel.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('pph.go-business-parcel.trackOrder');
    });



    // //goto go registered

    Route::controller(GotogoRegisteredController::class)->prefix('go-registered')->group(function () {

        Route::get('/index', 'index')->name('pph.go-registered.index');

        Route::get('/create', 'create')->name('pph.go-registered.create');

        Route::post('/store', 'store')->name('pph.go-registered.store');

        Route::post('/storeByfile', 'storeByfile')->name('pph.go-registered.storeByfile');

        Route::post('/storeByfile', 'storeByfile')->name('pph.go-registered.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('pph.go-registered.getUploadedFileData');
        Route::post('/excelUploadByPph', 'excelUploadByPph')->name('pph.go-registered.excelUploadByPph');

        Route::get('/delete/{id}', 'delete')->name('pph.go-registered.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('pph.go-registered.edit');

        Route::get('/view/{id}', 'view')->name('pph.go-registered.view');

        Route::get('/download-format', 'downloadFormat')->name('pph.go-registered.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('pph.go-registered.full-print');

        Route::get('/short-print', 'shortPrint')->name('pph.go-registered.short-print');

        Route::get('downloadTable', 'downloadTable')->name('pph.go-registered.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('pph.go-registered.trackOrder');
    });



    // //india Post speed post

    Route::controller(IndiaPostSpeedPostController::class)->prefix('india-post-speed-post')->group(function () {

        Route::get('/index', 'index')->name('pph.india-post-speed-post.index');

        Route::get('/create', 'create')->name('pph.india-post-speed-post.create');

        Route::post('/store', 'store')->name('pph.india-post-speed-post.store');

        Route::post('/storeByfile', 'storeByfile')->name('pph.india-post-speed-post.storeByfile');

        Route::post('/storeByfile', 'storeByfile')->name('pph.india-post-speed-post.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('pph.india-post-speed-post.getUploadedFileData');

        Route::post('/excelUploadByPph', 'excelUploadByPph')->name('pph.india-post-speed-post.excelUploadByPph');

        Route::get('/delete/{id}', 'delete')->name('pph.india-post-speed-post.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('pph.india-post-speed-post.edit');

        Route::get('/view/{id}', 'view')->name('pph.india-post-speed-post.view');

        Route::get('/download-format', 'downloadFormat')->name('pph.india-post-speed-post.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('pph.india-post-speed-post.full-print');

        Route::get('/short-print', 'shortPrint')->name('pph.india-post-speed-post.short-print');

        Route::get('downloadTable', 'downloadTable')->name('pph.india-post-speed-post.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('pph.india-post-speed-post.trackOrder');
    });





    // //india Post business parcel

    Route::controller(IndiaPostBusinessController::class)->prefix('india-post-business')->group(function () {

        Route::get('/index', 'index')->name('pph.india-post-business.index');

        Route::get('/create', 'create')->name('pph.india-post-business.create');

        Route::post('/store', 'store')->name('pph.india-post-business.store');

        Route::post('/storeByfile', 'storeByfile')->name('pph.india-post-business.storeByfile');

        Route::post('/storeByfile', 'storeByfile')->name('pph.india-post-business.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('pph.india-post-business.getUploadedFileData');
        Route::post('/excelUploadByPph', 'excelUploadByPph')->name('pph.india-post-business.excelUploadByPph');
        Route::get('/delete/{id}', 'delete')->name('pph.india-post-business.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('pph.india-post-business.edit');

        Route::get('/view/{id}', 'view')->name('pph.india-post-business.view');

        Route::get('/download-format', 'downloadFormat')->name('pph.india-post-business.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('pph.india-post-business.full-print');

        Route::get('/short-print', 'shortPrint')->name('pph.india-post-business.short-print');

        Route::get('downloadTable', 'downloadTable')->name('pph.india-post-business.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('pph.india-post-business.trackOrder');
    });





    // //india Post registered letter

    Route::controller(IndiaPostRegisteredController::class)->prefix('india-post-registered')->group(function () {

        Route::get('/index', 'index')->name('pph.india-post-registered.index');

        Route::get('/create', 'create')->name('pph.india-post-registered.create');

        Route::post('/store', 'store')->name('pph.india-post-registered.store');

        Route::post('/storeByfile', 'storeByfile')->name('pph.india-post-registered.storeByfile');

        Route::post('/storeByfile', 'storeByfile')->name('pph.india-post-registered.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('pph.india-post-registered.getUploadedFileData');
        Route::post('/excelUploadByPph', 'excelUploadByPph')->name('pph.india-post-registered.excelUploadByPph');
        Route::get('/delete/{id}', 'delete')->name('pph.india-post-registered.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('pph.india-post-registered.edit');

        Route::get('/view/{id}', 'view')->name('pph.india-post-registered.view');

        Route::get('/download-format', 'downloadFormat')->name('pph.india-post-registered.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('pph.india-post-registered.full-print');

        Route::get('/short-print', 'shortPrint')->name('pph.india-post-registered.short-print');

        Route::get('downloadTable', 'downloadTable')->name('pph.india-post-registered.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('pph.india-post-registered.trackOrder');
    });







    // //bag  Management

    Route::controller(BagController::class)->prefix('bag')->group(function () {

        Route::get('/created-bag', 'showcreatedBags')->name('pph.bag.showCretedBags');
        Route::post('/created-bag-search', 'cretedBagsSearch')->name('pph.bag.cretedBagsSearch');
        Route::get('/received-bag', 'showRecievedBags')->name('pph.bag.showRecievedBags');
        Route::post('/received-bag-search', 'receivedBagsSearch')->name('pph.bag.receivedBagsSearch');
        Route::post('/getdata', 'getData')->name('pph.bag.getData');

        Route::match(['GET', 'POST'], '/assignParcel/{id}', 'assignParcel')->name('pph.bag.assignParcel');
        Route::match(['GET', 'POST'], '/scanFranchiseBagReceived/{service_type}', 'scanFranchiseBagReceived')->name('pph.bag.scanFranchiseBagReceived');
        Route::match(['GET', 'POST'], '/scanCMSBagReceived/{service_type}', 'scanCMSBagReceived')->name('pph.bag.scanCMSBagReceived');

        Route::match(['GET', 'POST'], '/removeParcel/{id}', 'removeParcel')->name('pph.bag.removeParcel');

        Route::get('/viewParcelfc/{id}', 'viewParcels_of_createdBag_for_franchise')->name('pph.bag.viewParcelfranchiseCreated');

        Route::get('/viewParcelcc/{id}', 'viewParcels_of_createdBag_for_cms')->name('pph.bag.viewParcelCMSCreated');

        Route::get('/viewParcelfr/{id}', 'viewParcels_of_receivedBag_from_franchise')->name('pph.bag.viewParcelfranchiseReceived');

        Route::get('/viewParcelcr/{id}', 'viewParcels_of_receivedBag_from_cms')->name('pph.bag.viewParcelCMSReceived');

        Route::post('/store', 'store')->name('pph.bag.store');

        Route::post('/details', 'details')->name('pph.bag.details');

        Route::post('/update/{id}', 'update')->name('pph.bag.update');
        Route::get('/delete/{id}', 'delete')->name('pph.bag.delete');
        Route::get('/printCMSCreatedBag', 'printCMSCreatedBag')->name('pph.bag.printCMSCreatedBag');
    });


    //mail to mail
    Route::controller(MailToMailController::class)->prefix('mail-to-mail')->group(function () {
        Route::get('/receivedMails', 'receivedMails')->name('pph.mailToMail.receivedMails');
        Route::get('/sentMails', 'sentMails')->name('pph.mailToMail.sentMails');
        Route::get('/create', 'create')->name('pph.mailToMail.create');
        Route::post('/store', 'store')->name('pph.mailToMail.store');
        Route::post('/storeByfile', 'storeByfile')->name('pph.mailToMail.storeByfile');
        Route::get('/downloadFormat', 'downloadFormat')->name('pph.mailToMail.downloadFormat');
        Route::get('/sentview/{id}', 'sentview')->name('pph.mailToMail.sentview');
        Route::get('/receivedview/{id}', 'receivedview')->name('pph.mailToMail.receivedview');
        Route::get('/delete/{id}', 'delete')->name('pph.mailToMail.delete');
    });


    //mail to franchise
    Route::controller(MailToFranchiseController::class)->prefix('mail-to-franchise')->group(function () {
        Route::get('/receivedMails', 'receivedMails')->name('pph.mailTofranhchise.receivedMails');
        Route::get('/sentMails', 'sentMails')->name('pph.mailTofranhchise.sentMails');
        Route::get('/create', 'create')->name('pph.mailTofranhchise.create');
        Route::post('/store', 'store')->name('pph.mailTofranhchise.store');
        Route::post('/storeByfile', 'storeByfile')->name('pph.mailTofranhchise.storeByfile');
        Route::get('/downloadFormat', 'downloadFormat')->name('pph.mailTofranhchise.downloadFormat');
        Route::get('/sentview/{id}', 'sentview')->name('pph.mailTofranhchise.sentview');
        Route::get('/receivedview/{id}', 'receivedview')->name('pph.mailTofranhchise.receivedview');
        Route::get('/delete/{id}', 'delete')->name('pph.mailTofranhchise.delete');
        Route::post('/assigntoDeliveryBoy', 'assigntoDeliveryBoy')->name('pph.mailTofranhchise.assigntoDeliveryBoy');
        Route::get('/viewAssignedMailParcel', 'viewAssignedMailParcel')->name('pph.mailTofranhchise.viewAssignedMailParcel');
        Route::get('/viewDeliveredMailParcel', 'viewDeliveredMailParcel')->name('pph.mailTofranhchise.viewDeliveredMailParcel');
        Route::get('/trackOrder/{id}', 'trackOrder')->name('pph.mail-to-franchise.trackOrder');

    });




    // //soft Copy parcel

    // Route::controller(SoftCopyController::class)->prefix('soft-copy-parcel')->group(function () {

    //     Route::get('/index', 'index')->name('pph.softCopyParcel.index');

    //     Route::post('/store', 'store')->name('pph.softCopyParcel.store');

    //     Route::get('/view/{id}', 'view')->name('pph.softCopyParcel.view');

    //     Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('pph.softCopyParcel.edit');

    //     Route::get('/delete/{id}', 'delete')->name('pph.softCopyParcel.delete');
    // });

    //FranchisePaymentController

    Route::controller(PphPaymentController::class)->prefix('payment')->group(function () {
        Route::get('/payment', 'index')->name('pph.PPH-payment.index');
        Route::match(['GET', 'POST'], '/store', 'store')->name('pph.PPH-payment.store');
        Route::get('/paymentHistory', 'paymentHistory')->name('pph.PPH-payment.paymentHistory');
        Route::get('/print/paymentHistory', 'printPaymentHistory')->name('pph.PPH-payment.printpaymentHistory');
        Route::get('/commission', 'commissionIndex')->name('pph.commission.index');
        Route::get('/printCommissionDetail', 'printCommissionDetail')->name('pph.commission.printCommissionDetail');
    });
});
