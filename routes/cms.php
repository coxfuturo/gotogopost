<?php



use App\Http\Controllers\CMS\DashboardController;

use App\Http\Controllers\CMS\cmsRoleController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CMS\LoginController;

use App\Http\Controllers\CMS\ParcelController;

use App\Http\Controllers\CMS\PickupDetailsController;

use App\Http\Controllers\CMS\BagController;

use App\Http\Controllers\CMS\SoftCopyController;

use App\Http\Controllers\CMS\GotogoSpeedPostController;

use App\Http\Controllers\CMS\GotogoSuperFastController;

use App\Http\Controllers\CMS\GotogoBusinessParcelController;

use App\Http\Controllers\CMS\GotogoRegisteredController;

use App\Http\Controllers\CMS\IndiaPostSpeedPostController;

use App\Http\Controllers\CMS\IndiaPostBusinessController;

use App\Http\Controllers\CMS\IndiaPostRegisteredController;

use App\Http\Controllers\CMS\MailToMailController;
use App\Http\Controllers\CMS\MailToFranchiseController;
use App\Http\Controllers\CMS\CMSPaymentController;

Route::controller(LoginController::class)->group(function () {
    Route::match(['GET', 'POST'], '/login', 'login')->name('cms.login');
    Route::match(['GET', 'POST'], '/sendOtp', 'sendOtp')->name('cms.sendOtp');
    Route::match(['GET', 'POST'], '/verifyOtp', 'verifyOtp')->name('cms.verifyOtp');
    Route::match(['GET', 'POST'], '/register', 'register')->name('cms.register');
    Route::match(['GET', 'POST'], '/cms/payment/store', 'paymentStore')->name('cms.payment.store');
    Route::match(['GET', 'POST'], '/verifyPhoneNumberOtp', 'verifyPhoneNumberOtp')->name('cms.verifyPhoneNumberOtp');
    Route::match(['GET', 'POST'], '/otpViewPage', 'otpViewPage')->name('cms.otpViewPage');
    Route::match(['GET', 'POST'], '/resendPhoneNumberOtp', 'resendPhoneNumberOtp')->name('cms.resendPhoneNumberOtp');
});
Route::post('/locations', [LoginController::class, 'getLocation'])->name('cms.get.locations');

Route::group([

    'middleware' => ['cms']

], function () {

    Route::controller(LoginController::class)->group(function () {
        Route::match(['GET', 'POST'], '/logout', 'logout')->name('cms.logout');
        Route::match(['GET', 'POST'], '/profile', 'profile')->name('cms.profile');
        Route::post('profile/update/{id}', [LoginController::class, 'update'])->name('cms.profile.update');
        
    });
   

    Route::controller(DashboardController::class)->group(function () {

        Route::get('/dashboard', 'index')->name('cms.dashboard');
    });


    //Role & Permission

    Route::controller(cmsRoleController::class)->group(function () {

        Route::get('/role/{id}', 'index')->where('id', '[0-9]+')->name('cms.role.index');

        Route::post('/role/create', 'role_create')->name('cms.role.create');

        Route::post('/permission/store/{id}', 'store_permission')->name('cms.store.permission');

        Route::post('/role/update/{id}', 'role_update')->name('cms.role.update');

        Route::get('/role/delete/{id}', 'role_delete')->name('cms.role.delete');

        //Role Users

        Route::get('/role/user', 'role_user')->name('cms.role.user');

        Route::post('/role/user/store', 'new_role_user')->name('cms.role.user.store');

        Route::post('/role/user/update/{id}', 'update_role_user')->name('cms.role.user.update');

        Route::get('/role/user/delete/{id}', 'delete_role_user')->name('cms.role.user.delete');

        Route::post('/role/user/status', 'status_role_user')->name('cms.role.user.status');
    });


    //Parcel Ticket Management

    Route::controller(ParcelController::class)->prefix('parcel')->group(function () {

        Route::get('/index', 'index')->name('cms.parcel.index');

        Route::get('/create', 'create')->name('cms.parcel.create');

        Route::post('/store', 'store')->name('cms.parcel.store');

        Route::get('/delete/{id}', 'delete')->name('cms.parcel.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('cms.parcel.edit');

        Route::get('/view/{id}', 'view')->name('cms.parcel.view');
    });



    //PickupDetails  Management

    Route::controller(PickupDetailsController::class)->prefix('pickup-details')->group(function () {

        Route::get('/index', 'index')->name('cms.pickup-details.index');

        Route::post('/store', 'store')->name('cms.pickup-details.store');

        Route::post('/details', 'details')->name('cms.pickup-details.details');

        Route::post('/update/{id}', 'update')->name('cms.pickup-details.update');

        Route::get('/delete/{id}', 'delete')->name('cms.pickup-details.delete');
    });





    //goto go speed post Management

    Route::controller(GotogoSpeedPostController::class)->prefix('go-speed-post')->group(function () {

        Route::get('/index', 'index')->name('cms.go-speed-post-parcel.index');

        Route::get('/create', 'create')->name('cms.go-speed-post-parcel.create');

        Route::post('/store', 'store')->name('cms.go-speed-post-parcel.store');

        Route::post('/storeByfile', 'storeByfile')->name('cms.go-speed-post-parcel.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('cms.go-speed-post-parcel.getUploadedFileData');

        Route::post('/excelUploadByCms', 'excelUploadByCms')->name('cms.go-speed-post-parcel.excelUploadByCms');

        Route::get('/delete/{id}', 'delete')->name('cms.go-speed-post-parcel.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('cms.go-speed-post-parcel.edit');

        Route::get('/view/{id}', 'view')->name('cms.go-speed-post-parcel.view');

        Route::get('/download-format', 'downloadFormat')->name('cms.go-speed-post-parcel.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('cms.go-speed-post-parcel.full-print');

        Route::get('/short-print', 'shortPrint')->name('cms.go-speed-post-parcel.short-print');

        Route::get('downloadTable', 'downloadTable')->name('cms.go-speed-post-parcel.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('cms.go-speed-post-parcel.trackOrder');
    });





    //goto go superfast Management

    Route::controller(GotogoSuperFastController::class)->prefix('go-super-fast')->group(function () {

        Route::get('/index', 'index')->name('cms.go-super-fast-parcel.index');

        Route::get('/create', 'create')->name('cms.go-super-fast-parcel.create');

        Route::post('/store', 'store')->name('cms.go-super-fast-parcel.store');

        Route::post('/storeByfile', 'storeByfile')->name('cms.go-super-fast-parcel.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('cms.go-super-fast-parcel.getUploadedFileData');

        Route::post('/excelUploadByCms', 'excelUploadByCms')->name('cms.go-super-fast-parcel.excelUploadByCms');


        Route::get('/delete/{id}', 'delete')->name('cms.go-super-fast-parcel.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('cms.go-super-fast-parcel.edit');

        Route::get('/view/{id}', 'view')->name('cms.go-super-fast-parcel.view');

        Route::get('/download-format', 'downloadFormat')->name('cms.go-super-fast-parcel.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('cms.go-super-fast-parcel.full-print');

        Route::get('/short-print', 'shortPrint')->name('cms.go-super-fast-parcel.short-print');

        Route::get('downloadTable', 'downloadTable')->name('cms.go-super-fast-parcel.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('cms.go-super-fast-parcel.trackOrder');
    });



    //goto go business parcel Management

    Route::controller(GotogoBusinessParcelController::class)->prefix('go-business-parcel')->group(function () {

        Route::get('/index', 'index')->name('cms.go-business-parcel.index');

        Route::get('/create', 'create')->name('cms.go-business-parcel.create');

        Route::post('/store', 'store')->name('cms.go-business-parcel.store');

        Route::post('/storeByfile', 'storeByfile')->name('cms.go-business-parcel.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('cms.go-business-parcel.getUploadedFileData');

        Route::post('/excelUploadByCms', 'excelUploadByCms')->name('cms.go-business-parcel.excelUploadByCms');

        Route::get('/delete/{id}', 'delete')->name('cms.go-business-parcel.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('cms.go-business-parcel.edit');

        Route::get('/view/{id}', 'view')->name('cms.go-business-parcel.view');

        Route::get('/download-format', 'downloadFormat')->name('cms.go-business-parcel.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('cms.go-business-parcel.full-print');

        Route::get('/short-print', 'shortPrint')->name('cms.go-business-parcel.short-print');

        Route::get('downloadTable', 'downloadTable')->name('cms.go-business-parcel.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('cms.go-business-parcel.trackOrder');
    });



    //goto go registered

    Route::controller(GotogoRegisteredController::class)->prefix('go-registered')->group(function () {

        Route::get('/index', 'index')->name('cms.go-registered.index');

        Route::get('/create', 'create')->name('cms.go-registered.create');

        Route::post('/store', 'store')->name('cms.go-registered.store');

        Route::post('/storeByfile', 'storeByfile')->name('cms.go-registered.storeByfile');

        Route::post('/storeByfile', 'storeByfile')->name('cms.go-registered.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('cms.go-registered.getUploadedFileData');
        Route::post('/excelUploadByCms', 'excelUploadByCms')->name('cms.go-registered.excelUploadByCms');

        Route::get('/delete/{id}', 'delete')->name('cms.go-registered.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('cms.go-registered.edit');

        Route::get('/view/{id}', 'view')->name('cms.go-registered.view');

        Route::get('/download-format', 'downloadFormat')->name('cms.go-registered.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('cms.go-registered.full-print');

        Route::get('/short-print', 'shortPrint')->name('cms.go-registered.short-print');

        Route::get('downloadTable', 'downloadTable')->name('cms.go-registered.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('cms.go-registered.trackOrder');
    });



    //india Post speed post

    Route::controller(IndiaPostSpeedPostController::class)->prefix('india-post-speed-post')->group(function () {

        Route::get('/index', 'index')->name('cms.india-post-speed-post.index');

        Route::get('/create', 'create')->name('cms.india-post-speed-post.create');

        Route::post('/store', 'store')->name('cms.india-post-speed-post.store');

        Route::post('/storeByfile', 'storeByfile')->name('cms.india-post-speed-post.storeByfile');

        Route::post('/storeByfile', 'storeByfile')->name('cms.india-post-speed-post.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('cms.india-post-speed-post.getUploadedFileData');

        Route::post('/excelUploadByCms', 'excelUploadByCms')->name('cms.india-post-speed-post.excelUploadByCms');

        Route::get('/delete/{id}', 'delete')->name('cms.india-post-speed-post.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('cms.india-post-speed-post.edit');

        Route::get('/view/{id}', 'view')->name('cms.india-post-speed-post.view');

        Route::get('/download-format', 'downloadFormat')->name('cms.india-post-speed-post.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('cms.india-post-speed-post.full-print');

        Route::get('/short-print', 'shortPrint')->name('cms.india-post-speed-post.short-print');

        Route::get('downloadTable', 'downloadTable')->name('cms.india-post-speed-post.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('cms.india-post-speed-post.trackOrder');

        Route::post('/parcel/count', 'GetTotalAmount')->name('cms.india-post-speed-post.parcel.count');
    });





    //india Post business parcel

    Route::controller(IndiaPostBusinessController::class)->prefix('india-post-business')->group(function () {

        Route::get('/index', 'index')->name('cms.india-post-business.index');

        Route::get('/create', 'create')->name('cms.india-post-business.create');

        Route::post('/store', 'store')->name('cms.india-post-business.store');

        Route::post('/storeByfile', 'storeByfile')->name('cms.india-post-business.storeByfile');

        Route::post('/storeByfile', 'storeByfile')->name('cms.india-post-business.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('cms.india-post-business.getUploadedFileData');
        Route::post('/excelUploadByCms', 'excelUploadByCms')->name('cms.india-post-business.excelUploadByCms');
        Route::get('/delete/{id}', 'delete')->name('cms.india-post-business.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('cms.india-post-business.edit');

        Route::get('/view/{id}', 'view')->name('cms.india-post-business.view');

        Route::get('/download-format', 'downloadFormat')->name('cms.india-post-business.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('cms.india-post-business.full-print');

        Route::get('/short-print', 'shortPrint')->name('cms.india-post-business.short-print');

        Route::get('downloadTable', 'downloadTable')->name('cms.india-post-business.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('cms.india-post-business.trackOrder');

        Route::post('/parcel/count', 'GetTotalAmount')->name('cms.india-post-business.parcel.count');
    });



    //india Post registered letter

    Route::controller(IndiaPostRegisteredController::class)->prefix('india-post-registered')->group(function () {

        Route::get('/index', 'index')->name('cms.india-post-registered.index');

        Route::get('/create', 'create')->name('cms.india-post-registered.create');

        Route::post('/store', 'store')->name('cms.india-post-registered.store');

        Route::post('/storeByfile', 'storeByfile')->name('cms.india-post-registered.storeByfile');

        Route::post('/storeByfile', 'storeByfile')->name('cms.india-post-registered.storeByfile');
        Route::post('/getUploadedFileData', 'getUploadedFileData')->name('cms.india-post-registered.getUploadedFileData');
        Route::post('/excelUploadByCms', 'excelUploadByCms')->name('cms.india-post-registered.excelUploadByCms');
        Route::get('/delete/{id}', 'delete')->name('cms.india-post-registered.delete');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('cms.india-post-registered.edit');

        Route::get('/view/{id}', 'view')->name('cms.india-post-registered.view');

        Route::get('/download-format', 'downloadFormat')->name('cms.india-post-registered.downloadForamt');

        Route::get('/full-print/{id}', 'fullPrint')->name('cms.india-post-registered.full-print');

        Route::get('/short-print', 'shortPrint')->name('cms.india-post-registered.short-print');

        Route::get('downloadTable', 'downloadTable')->name('cms.india-post-registered.downloadTable');

        Route::get('/trackOrder/{id}', 'trackOrder')->name('cms.india-post-registered.trackOrder');
    });






    //bag  Management

    Route::controller(BagController::class)->prefix('bag')->group(function () {

        Route::get('/created-bag', 'showcreatedBags')->name('cms.bag.showCretedBags');
        Route::post('/created-bag-search', 'cretedBagsSearch')->name('cms.bag.cretedBagsSearch');
        Route::get('/received-bag', 'showRecievedBags')->name('cms.bag.showRecievedBags');
        Route::post('/received-bag-search', 'receivedBagsSearch')->name('cms.bag.receivedBagsSearch');
        Route::post('/getdata', 'getData')->name('cms.bag.getData');

        Route::match(['GET', 'POST'], '/assignParcel/{id}', 'assignParcel')->name('cms.bag.assignParcel');
        Route::match(['GET', 'POST'], '/scanFranchiseBagReceived/{service_type}', 'scanFranchiseBagReceived')->name('cms.bag.scanFranchiseBagReceived');
        Route::match(['GET', 'POST'], '/scanCMSBagReceived/{service_type}', 'scanCMSBagReceived')->name('cms.bag.scanCMSBagReceived');

        Route::match(['GET', 'POST'], '/removeParcel/{id}', 'removeParcel')->name('cms.bag.removeParcel');

        Route::get('/viewParcelfc/{id}', 'viewParcels_of_createdBag_for_franchise')->name('cms.bag.viewParcelfranchiseCreated');

        Route::get('/viewParcelcc/{id}', 'viewParcels_of_createdBag_for_cms')->name('cms.bag.viewParcelCMSCreated');

        Route::get('/viewParcelfr/{id}', 'viewParcels_of_receivedBag_from_franchise')->name('cms.bag.viewParcelfranchiseReceived');

        Route::get('/viewParcelcr/{id}', 'viewParcels_of_receivedBag_from_cms')->name('cms.bag.viewParcelCMSReceived');

        Route::post('/store', 'store')->name('cms.bag.store');

        Route::post('/details', 'details')->name('cms.bag.details');

        Route::post('/update/{id}', 'update')->name('cms.bag.update');

        Route::get('/delete/{id}', 'delete')->name('cms.bag.delete');

        Route::get('/printFranchiseCreatedBag', 'printFranchiseCreatedBag')->name('cms.bag.printFranchiseCreatedBag');
        Route::get('/printPPHCreatedBag', 'printPPHCreatedBag')->name('cms.bag.printPPHCreatedBag');
    });




    //soft Copy parcel

    Route::controller(SoftCopyController::class)->prefix('soft-copy-parcel')->group(function () {

        Route::get('/index', 'index')->name('cms.softCopyParcel.index');

        Route::post('/store', 'store')->name('cms.softCopyParcel.store');

        Route::get('/view/{id}', 'view')->name('cms.softCopyParcel.view');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('cms.softCopyParcel.edit');

        Route::get('/delete/{id}', 'delete')->name('cms.softCopyParcel.delete');
    });


    //mail to mail
    Route::controller(MailToMailController::class)->prefix('mail-to-mail')->group(function () {
        Route::get('/receivedMails', 'receivedMails')->name('cms.mailToMail.receivedMails');
        Route::get('/sentMails', 'sentMails')->name('cms.mailToMail.sentMails');
        Route::get('/create', 'create')->name('cms.mailToMail.create');
        Route::post('/store', 'store')->name('cms.mailToMail.store');
        Route::post('/storeByfile', 'storeByfile')->name('cms.mailToMail.storeByfile');
        Route::get('/downloadFormat', 'downloadFormat')->name('cms.mailToMail.downloadFormat');
        Route::get('/sentview/{id}', 'sentview')->name('cms.mailToMail.sentview');
        Route::get('/receivedview/{id}', 'receivedview')->name('cms.mailToMail.receivedview');
        Route::get('/delete/{id}', 'delete')->name('cms.mailToMail.delete');
    });


    //mail to franchise
    Route::controller(MailToFranchiseController::class)->prefix('mail-to-franchise')->group(function () {
        Route::get('/receivedMails', 'receivedMails')->name('cms.mailTofranhchise.receivedMails');
        Route::get('/sentMails', 'sentMails')->name('cms.mailTofranhchise.sentMails');
        Route::get('/create', 'create')->name('cms.mailTofranhchise.create');
        Route::post('/store', 'store')->name('cms.mailTofranhchise.store');
        Route::post('/storeByfile', 'storeByfile')->name('cms.mailTofranhchise.storeByfile');
        Route::get('/downloadFormat', 'downloadFormat')->name('cms.mailTofranhchise.downloadFormat');
        Route::get('/sentview/{id}', 'sentview')->name('cms.mailTofranhchise.sentview');
        Route::get('/receivedview/{id}', 'receivedview')->name('cms.mailTofranhchise.receivedview');
        Route::get('/delete/{id}', 'delete')->name('cms.mailTofranhchise.delete');
        Route::post('/assigntoDeliveryBoy', 'assigntoDeliveryBoy')->name('cms.mailTofranhchise.assigntoDeliveryBoy');
        Route::get('/viewAssignedMailParcel', 'viewAssignedMailParcel')->name('cms.mailTofranhchise.viewAssignedMailParcel');
        Route::get('/viewDeliveredMailParcel', 'viewDeliveredMailParcel')->name('cms.mailTofranhchise.viewDeliveredMailParcel');
        Route::get('/trackOrder/{id}', 'trackOrder')->name('cms.mail-to-franchise.trackOrder');

    });

    Route::controller(CMSPaymentController::class)->prefix('cms-payment')->group(function () {
        Route::get('/payment', 'index')->name('cms.cms-payment.index');
        Route::match(['GET', 'POST'], '/store', 'store')->name('cms.cms-payment.store');
        Route::get('/paymentHistory', 'paymentHistory')->name('cms.cms-payment.paymentHistory');
        Route::get('/print/paymentHistory', 'printPaymentHistory')->name('cms.cms-payment.printpaymentHistory');
        Route::get('/commission', 'commissionIndex')->name('cms.commission.index');
        Route::get('/printCommissionDetail', 'printCommissionDetail')->name('cms.commission.printCommissionDetail');
    });
});
