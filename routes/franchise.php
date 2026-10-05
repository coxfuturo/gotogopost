<?php

use App\Http\Controllers\franchise\FranchiseRoleController;
use App\Http\Controllers\franchise\DashboardController;
use App\Http\Controllers\franchise\DeliveryBoyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\franchise\LoginController;
use App\Http\Controllers\franchise\ParcelController;
use App\Http\Controllers\franchise\GotogoSpeedPostController;
use App\Http\Controllers\franchise\GotogoSuperFastController;
use App\Http\Controllers\franchise\GotogoBusinessParcelController;
use App\Http\Controllers\franchise\GotogoRegisteredController;
use App\Http\Controllers\franchise\IndiaPostSpeedPostController;
use App\Http\Controllers\franchise\IndiaPostBusinessController;
use App\Http\Controllers\franchise\IndiaPostRegisteredController;
use App\Http\Controllers\franchise\PickupDetailsController;
use App\Http\Controllers\franchise\SupportTicketController;
use App\Http\Controllers\franchise\BagController;
use App\Http\Controllers\franchise\RateCalculator;
use App\Http\Controllers\franchise\ServiceAvailability;
use App\Http\Controllers\franchise\SoftCopyController;
use App\Http\Controllers\franchise\MailToMailController;
use App\Http\Controllers\franchise\MailToFranchiseController;
use App\Http\Controllers\franchise\DailyBookingReportController;
use App\Http\Controllers\franchise\ParcelPaymentController;
use App\Http\Controllers\franchise\FranchisePaymentController;
use App\Http\Controllers\franchise\FranchiseCommissionController;
use App\Http\Controllers\franchise\ComboController;
use App\Http\Controllers\franchise\RegisterController;


Route::controller(LoginController::class)->group(function () {
    Route::match(['GET', 'POST'], '/login', 'login')->name('franchise.login');
    Route::match(['GET', 'POST'], '/sendOtp', 'sendOtp')->name('franchise.sendOtp');
    Route::match(['GET', 'POST'], '/verifyOtp', 'verifyOtp')->name('franchise.verifyOtp');
    Route::match(['GET', 'POST'], '/register', 'register')->name('franchise.register');
    Route::match(['GET', 'POST'], '/franchise/payment/store', 'paymentStore')->name('franchise.payment.store');
    Route::match(['GET', 'POST'], '/verifyPhoneNumberOtp', 'verifyPhoneNumberOtp')->name('franchise.verifyPhoneNumberOtp');
    Route::match(['GET', 'POST'], '/otpViewPage', 'otpViewPage')->name('franchise.otpViewPage');
   
    
});



 Route::get('/receipt/download/{type}/{id}', [ParcelController::class, 'downloadReceipt'])->name('franchise.parcel.receipt.download');

 
Route::post('location', [LoginController::class, 'getLocation'])->name('franchise.get.location');
Route::get('combo', [ComboController::class, 'index'])->name('franchise.combo.index');
Route::post('combo/store', [ComboController::class, 'store'])->name('franchise.combo.store');
Route::match(['GET', 'POST'], '/combo/otpViewPage', [ComboController::class, 'otpViewPage'])->name('franchise.combo.otpViewPage');
Route::match(['GET', 'POST'], '/combo/verifyPhoneNumberOtp', [ComboController::class,'verifyPhoneNumberOtp'])->name('franchise.combo.verifyPhoneNumberOtp');
Route::match(['GET', 'POST'], '/combo/resendPhoneNumberOtp', [ComboController::class,'resendPhoneNumberOtp'])->name('franchise.combo.resendPhoneNumberOtp');
Route::match(['GET', 'POST'], '/combo/location', [ComboController::class, 'getLocation'])->name('franchise.get.combo.locations');
Route::match(['GET', 'POST'], '/combo/franchise/payment/store', [ComboController::class, 'paymentStore'])->name('franchise.combo.payment.store');

//attachment download
Route::controller(MailToMailController::class)->group(function () {
    Route::get('/downloadAttachment/{mail_code}/{phone}', 'downloadAttachment')->name('mailToMail.downloadAttachment');
    Route::get('/downloadAttachmentView/{mail_code}/{phone}', 'downloadAttachmentView')->name('mailToMail.downloadAttachmentView');
});


Route::controller(PickupDetailsController::class)->prefix('pickup-enquiry')->group(function () {
    Route::post('/store', 'store')->name('franchise.pickup-details.store');
    Route::get('/test', 'test')->name('franchise.test');
});




Route::group([
    'middleware' => ['franchiseAuth']
], function () {


    Route::controller(LoginController::class)->group(function () {
        Route::get('logout', [LoginController::class, 'logout'])->name('franchise.logout');
        Route::get('profile', [LoginController::class, 'profile'])->name('franchise.profile');
        Route::post('profile/update/{id}', [LoginController::class, 'update'])->name('franchise.profile.update');
    });


    Route::controller(PickupDetailsController::class)->prefix('pickup-enquiry')->group(function () {
        Route::post('/store', 'store')->name('franchise.pickup-details.store');
        Route::get('/test', 'test')->name('franchise.test');
    });


    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('franchise.dashboard');
    });
	
	// Preview route
Route::post('/preview/excel', [IndiaPostSpeedPostController::class, 'previewExcel'])
    ->name('franchise.india-post-speed-post.previewByfile');

// Upload route  
Route::post('/storeByfile', [IndiaPostSpeedPostController::class, 'storeByFile'])
    ->name('franchise.india-post-speed-post.storeByfile');
	
	
	Route::post('/india-post-speed-post/confirm-excel', [IndiaPostSpeedPostController::class, 'confirmAndStoreExcel'])
    ->name('franchise.india-post-speed-post.confirm-excel');
	
	
	
	
	Route::post('/india-post-speed-post/store-by-file', [IndiaPostSpeedPostController::class, 'storeByFile'])->name('franchise.india-post-speed-post.storeByfile');
Route::post('/india-post-speed-post/confirm-excel', [IndiaPostSpeedPostController::class, 'confirmAndStoreExcel'])->name('franchise.india-post-speed-post.confirm-excel');
	
	// routes/web.php
Route::post('india-post-speed-post/preview', [IndiaPostSpeedPostController::class, 'previewByfile'])
    ->name('franchise.india-post-speed-post.previewByfile');

    //Role & Permission
    Route::controller(FranchiseRoleController::class)->group(function () {
        Route::get('/role/{id}', 'index')->where('id', '[0-9]+')->name('franchise.role.index');
        Route::post('/role/create', 'role_create')->name('franchise.role.create');
        Route::post('/permission/store/{id}', 'store_permission')->name('franchise.store.permission');
        Route::post('/role/update/{id}', 'role_update')->name('franchise.role.update');
        Route::get('/role/delete/{id}', 'role_delete')->name('franchise.role.delete');
        //Role Users
        Route::get('/role/user', 'role_user')->name('franchise.role.user');
        Route::post('/role/user/store', 'new_role_user')->name('franchise.role.user.store');
        Route::post('/role/user/update/{id}', 'update_role_user')->name('franchise.role.user.update');
        Route::get('/role/user/delete/{id}', 'delete_role_user')->name('franchise.role.user.delete');
        Route::post('/role/user/status', 'status_role_user')->name('franchise.role.user.status');
    });

    //Support Boy Management
    Route::controller(SupportTicketController::class)->prefix('support-ticket')->group(function () {
        Route::get('/index', 'index')->name('franchise.support-ticket.index');
        Route::post('/store', 'store')->name('franchise.support-ticket.store');
        Route::post('/update/{id}', 'update')->name('franchise.support-ticket.update');
        Route::get('/delete/{id}', 'delete')->name('franchise.support-ticket.delete');
        Route::get('support-ticket-detail/{id}', 'getDetails')->name('franchise.support-ticket.get_details');
        Route::post('send', 'chatStore')->name('franchise.support-ticket.send');
        Route::post('/chat/update/{id}', 'updateChat')->name('franchise.support-ticket.updateChat');
        Route::get('/chat/delete/{id}', 'deleteChat')->name('franchise.support-ticket.deleteChat');
        Route::get('clear', 'clearAll')->name('franchise.support-ticket.clear');
    });

    //Delivery Ticket Management
    Route::controller(DeliveryBoyController::class)->prefix('delboy')->group(function () {
        Route::get('/index', 'index')->name('franchise.delboy.index');
        Route::match(['GET', 'POST'], '/create', 'create')->name('franchise.delboy.create');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('franchise.delboy.edit');
        Route::get('/delete/{id}', 'delete')->name('franchise.delboy.delete');
        Route::post('/status', 'status')->name('franchise.delboy.status');
        Route::get('/view/{id}', 'view')->name('franchise.delboy.view');
        Route::get('/view/detail/{id}/{service_type}', 'viewDetails')->name('franchise.delboy.view.detail');
    });

    //Parcel Management
    Route::controller(ParcelController::class)->prefix('parcel')->group(function () {
        Route::get('/index/{id}', 'index')->name('franchise.parcel.index');
        Route::get('/create/{id}', 'create')->name('franchise.parcel.create');
        Route::post('/store', 'store')->name('franchise.parcel.store');
        Route::post('/storeByfile', 'storeByfile')->name('franchise.parcel.storeByfile');
        Route::get('/delete/{id}', 'delete')->name('franchise.parcel.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('franchise.parcel.edit');
        Route::get('/view/{id}', 'view')->name('franchise.parcel.view');
        Route::get('/download-format', 'downloadFormat')->name('franchise.parcel.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('franchise.parcel.full-print');
        Route::get('/short-print/{id}', 'shortPrint')->name('franchise.parcel.short-print');
        Route::get('downloadTable/{id}', 'downloadTable')->name('franchise.parcel.downloadTable');
    });


    //goto go speed post Management
    Route::controller(GotogoSpeedPostController::class)->prefix('go-speed-post')->group(function () {
        Route::get('/index', 'index')->name('franchise.go-speed-post-parcel.index');
        Route::get('/create', 'create')->name('franchise.go-speed-post-parcel.create');
        Route::post('/store', 'store')->name('franchise.go-speed-post-parcel.store');
        Route::post('/storeByfile', 'storeByfile')->name('franchise.go-speed-post-parcel.storeByfile');
        Route::get('/showReceivedParcelList', 'showReceivedParcelList')->name('franchise.go-speed-post-parcel.showReceivedParcelList');
        Route::post('/excelUploadByFranchise', 'excelUploadByFranchise')->name('franchise.go-speed-post-parcel.excelUploadByFranchise');
        Route::get('/downloadTableOfReceivedParcel', 'downloadTableOfReceivedParcel')->name('franchise.go-speed-post-parcel.downloadTableOfReceivedParcel');
        Route::get('/shortPrintForReceivedParcel', 'shortPrintForReceivedParcel')->name('franchise.go-speed-post-parcel.shortPrintForReceivedParcel');
        Route::get('/delete/{id}', 'delete')->name('franchise.go-speed-post-parcel.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('franchise.go-speed-post-parcel.edit');
        Route::get('/view/{id}', 'view')->name('franchise.go-speed-post-parcel.view');
        Route::get('/download-format', 'downloadFormat')->name('franchise.go-speed-post-parcel.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('franchise.go-speed-post-parcel.full-print');
        Route::get('/short-print', 'shortPrintForCreatedParcel')->name('franchise.go-speed-post-parcel.shortPrintForCreatedParcel');
        Route::get('/downloadTableForCreatedTable', 'downloadTableForCreatedTable')->name('franchise.go-speed-post-parcel.downloadTableForCreatedTable');
        Route::match(['GET', 'POST'], '/assignBarcode', 'assignBarcode')->name('franchise.go-speed-post-parcel.assignBarcode');
        Route::match(['GET', 'POST'], '/showPrice', 'showPrice')->name('franchise.go-speed-post-parcel.showPrice');
        Route::match(['GET', 'POST'], '/allprint', 'allprint')->name('franchise.go-speed-post.allprint');
        Route::get('/trackOrder/{id}', 'trackOrder')->name('franchise.go-speed-post-parcel.trackOrder');
        Route::post('/email/details', 'emailDetails')->name('franchise.email.pickup-details.details');
        Route::post('/mobile/details', 'mobileDetails')->name('franchise.mobile.pickup-details.details');
        Route::post('/parcel/count', 'GetTotalAmount')->name('franchise.go-speed-post.parcel.count');
        
    });

        Route::post('booking/details', [GotogoSpeedPostController::class, 'getBookingDetails'])->name('franchise.booking.get.details');
        

    //goto go superfast Management
    Route::controller(GotogoSuperFastController::class)->prefix('go-super-fast')->group(function () {
        Route::get('/index', 'index')->name('franchise.go-super-fast-parcel.index');
        Route::get('/create', 'create')->name('franchise.go-super-fast-parcel.create');
        Route::post('/store', 'store')->name('franchise.go-super-fast-parcel.store');
        Route::post('/storeByfile', 'storeByfile')->name('franchise.go-super-fast-parcel.storeByfile');
        Route::get('/showReceivedParcelList', 'showReceivedParcelList')->name('franchise.go-super-fast-parcel.showReceivedParcelList');
        Route::post('/excelUploadByFranchise', 'excelUploadByFranchise')->name('franchise.go-super-fast-parcel.excelUploadByFranchise');
        Route::get('/downloadTableOfReceivedParcel', 'downloadTableOfReceivedParcel')->name('franchise.go-super-fast-parcel.downloadTableOfReceivedParcel');
        Route::get('/shortPrintForReceivedParcel', 'shortPrintForReceivedParcel')->name('franchise.go-super-fast-parcel.shortPrintForReceivedParcel');
        Route::get('/delete/{id}', 'delete')->name('franchise.go-super-fast-parcel.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('franchise.go-super-fast-parcel.edit');
        Route::get('/view/{id}', 'view')->name('franchise.go-super-fast-parcel.view');
        Route::get('/download-format', 'downloadFormat')->name('franchise.go-super-fast-parcel.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('franchise.go-super-fast-parcel.full-print');
        Route::get('/short-print', 'shortPrintForCreatedParcel')->name('franchise.go-super-fast-parcel.shortPrintForCreatedParcel');
        Route::get('/downloadTableForCreatedTable', 'downloadTableForCreatedTable')->name('franchise.go-super-fast-parcel.downloadTableForCreatedTable');
        Route::match(['GET', 'POST'], '/assignBarcode', 'assignBarcode')->name('franchise.go-super-fast-parcel.assignBarcode');
        Route::match(['GET', 'POST'], '/showPrice', 'showPrice')->name('franchise.go-super-fast-parcel.showPrice');
        
        
    });

    //goto go business parcel Management
    Route::controller(GotogoBusinessParcelController::class)->prefix('go-business-parcel')->group(function () {
        Route::get('/index', 'index')->name('franchise.go-business-parcel.index');
        Route::get('/create', 'create')->name('franchise.go-business-parcel.create');
        Route::post('/store', 'store')->name('franchise.go-business-parcel.store');
        Route::post('/storeByfile', 'storeByfile')->name('franchise.go-business-parcel.storeByfile');
        Route::get('/showReceivedParcelList', 'showReceivedParcelList')->name('franchise.go-business-parcel.showReceivedParcelList');
        Route::post('/excelUploadByFranchise', 'excelUploadByFranchise')->name('franchise.go-business-parcel.excelUploadByFranchise');
        Route::get('/downloadTableOfReceivedParcel', 'downloadTableOfReceivedParcel')->name('franchise.go-business-parcel.downloadTableOfReceivedParcel');
        Route::get('/shortPrintForReceivedParcel', 'shortPrintForReceivedParcel')->name('franchise.go-business-parcel.shortPrintForReceivedParcel');
        Route::get('/delete/{id}', 'delete')->name('franchise.go-business-parcel.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('franchise.go-business-parcel.edit');
        Route::get('/view/{id}', 'view')->name('franchise.go-business-parcel.view');
        Route::get('/download-format', 'downloadFormat')->name('franchise.go-business-parcel.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('franchise.go-business-parcel.full-print');
        Route::get('/short-print', 'shortPrintForCreatedParcel')->name('franchise.go-business-parcel.shortPrintForCreatedParcel');
        Route::get('/downloadTableForCreatedTable', 'downloadTableForCreatedTable')->name('franchise.go-business-parcel.downloadTableForCreatedTable');
        Route::match(['GET', 'POST'], '/assignBarcode', 'assignBarcode')->name('franchise.go-business-parcel.assignBarcode');
        Route::match(['GET', 'POST'], '/showPrice', 'showPrice')->name('franchise.go-business-parcel.showPrice');
        Route::get('/trackOrder/{id}', 'trackOrder')->name('franchise.go-business-parcel.trackOrder');
        Route::match(['GET', 'POST'], '/allprint', 'allprint')->name('franchise.go-business-parcel.allprint');
        Route::post('/express/details', 'expressDetails')->name('franchise.express.pickup-details.details');
        Route::match(['GET', 'POST'], '/booking/details', 'getBookingDetails')->name('franchise.go-business-parcel.booking.get.details');
        Route::post('/parcel/count', 'GetTotalAmount')->name('franchise.go-business-parcel.parcel.count');
    });

    //goto go registered
    Route::controller(GotogoRegisteredController::class)->prefix('go-registered')->group(function () {
        Route::get('/index', 'index')->name('franchise.go-registered.index');
        Route::get('/create', 'create')->name('franchise.go-registered.create');
        Route::post('/store', 'store')->name('franchise.go-registered.store');
        Route::post('/storeByfile', 'storeByfile')->name('franchise.go-registered.storeByfile');
        Route::get('/showReceivedParcelList', 'showReceivedParcelList')->name('franchise.go-registered.showReceivedParcelList');
        Route::post('/excelUploadByFranchise', 'excelUploadByFranchise')->name('franchise.go-registered.excelUploadByFranchise');
        Route::get('/downloadTableOfReceivedParcel', 'downloadTableOfReceivedParcel')->name('franchise.go-registered.downloadTableOfReceivedParcel');
        Route::get('/shortPrintForReceivedParcel', 'shortPrintForReceivedParcel')->name('franchise.go-registered.shortPrintForReceivedParcel');
        Route::get('/delete/{id}', 'delete')->name('franchise.go-registered.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('franchise.go-registered.edit');
        Route::get('/view/{id}', 'view')->name('franchise.go-registered.view');
        Route::get('/download-format', 'downloadFormat')->name('franchise.go-registered.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('franchise.go-registered.full-print');
        Route::get('/short-print', 'shortPrintForCreatedParcel')->name('franchise.go-registered.shortPrintForCreatedParcel');
        Route::get('/downloadTableForCreatedTable', 'downloadTableForCreatedTable')->name('franchise.go-registered.downloadTableForCreatedTable');
        Route::match(['GET', 'POST'], '/assignBarcode', 'assignBarcode')->name('franchise.go-registered.assignBarcode');
        Route::match(['GET', 'POST'], '/showPrice', 'showPrice')->name('franchise.go-registered.showPrice');
        Route::get('/trackOrder/{id}', 'trackOrder')->name('franchise.go-registered.trackOrder');
        Route::match(['GET', 'POST'], '/allprint', 'allprint')->name('franchise.go-registered.allprint');
        Route::match(['GET', 'POST'], '/booking/details', 'getBookingDetails')->name('franchise.go-registered.booking.get.details');
        Route::post('/mobile/details', 'mobileDetails')->name('franchise.go-registered.mobile.pickup-details.details');
          Route::post('/email/details', 'emailDetails')->name('franchise.go-registered.email.pickup-details.details');
          Route::post('/parcel/count', 'GetTotalAmount')->name('franchise.go-registered.parcel.count');
    });

    //india Post speed post
    Route::controller(IndiaPostSpeedPostController::class)->prefix('india-post-speed-post')->group(function () {
        Route::get('/index', 'index')->name('franchise.india-post-speed-post.index');
        Route::get('/create', 'create')->name('franchise.india-post-speed-post.create');
        Route::post('/store', 'store')->name('franchise.india-post-speed-post.store');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('franchise.india-post-speed-post.edit');
        Route::get('/view/{id}', 'view')->name('franchise.india-post-speed-post.view');
        Route::get('/delete/{id}', 'delete')->name('franchise.india-post-speed-post.delete');

        // Printing & Download
        Route::get('/full-print/{id}', 'fullPrint')->name('franchise.india-post-speed-post.full-print');
        Route::get('/short-print', 'shortPrintForCreatedParcel')->name('franchise.india-post-speed-post.shortPrintForCreatedParcel');
        Route::get('/short-label', 'shortLabelForCreatedParcel')->name('franchise.india-post-speed-post.shortLabelForCreatedParcel');
        Route::get('/downloadTableForCreatedTable', 'downloadTableForCreatedTable')->name('franchise.india-post-speed-post.downloadTableForCreatedTable');
        Route::get('/download-format', 'downloadFormat')->name('franchise.india-post-speed-post.downloadForamt');
        Route::match(['GET', 'POST'], '/allprint', 'allprint')->name('franchise.india-post-speed-post.allprint');

        // Booking / Express / Parcel Count
        Route::match(['GET', 'POST'], '/assignBarcode', 'assignBarcode')->name('franchise.india-post-speed-post.assignBarcode');
        Route::match(['GET', 'POST'], '/showPrice', 'showPrice')->name('franchise.india-post-speed-post.showPrice');
        Route::get('/trackOrder/{id}', 'trackOrder')->name('franchise.india-post-speed-post.trackOrder');
        Route::match(['GET', 'POST'], '/booking/details', 'getBookingDetails')->name('franchise.india-post-speed-post.booking.get.details');
        Route::post('/express/details', 'expressDetails')->name('franchise.india-post-speed-post.express.pickup-details.details');
        Route::post('/parcel/count', 'GetTotalAmount')->name('franchise.india-post-speed-post.parcel.count');

        // Cancel
        Route::post('/parcel/cancel/{id}', 'cancel')->name('franchise.india-post-speed-post.cancel');
        Route::get('/cancel', 'cancel_index')->name('franchise.india-post-speed-post.cancel.index');
        Route::get('/short-print/cancel', 'shortPrintForCreatedParcelCancel')->name('franchise.india-post-speed-post.shortPrintForCreatedParcel.cancel');
        Route::get('/short-label/cancel', 'shortLabelForCreatedParcelCancel')->name('franchise.india-post-speed-post.shortLabelForCreatedParcel.cancel');
        Route::get('/downloadTableForCreatedTable/cancel', 'downloadTableForCreatedTableCancel')->name('franchise.india-post-speed-post.downloadTableForCreatedTable.cancel');

        // Excel Upload & Preview
        Route::post('/excelUploadByFranchise', 'excelUploadByFranchise')->name('franchise.india-post-speed-post.excelUploadByFranchise');
        Route::post('/storeByfile', 'storeByfile')->name('franchise.india-post-speed-post.storeByfile');
        Route::post('/preview/excel', 'previewByFile')->name('franchise.india-post-speed-post.previewByfile');
         Route::get('/preview/result/{key}', 'getPreviewResult')->name('franchise.india-post-speed-post.preview.result');
         Route::get('/preview/check/{key}', 'getPreviewResult')->name('franchise.india-post-speed-post.preview-check');

         Route::get('/preview-check/{franchiseId}', 'checkPreview')->name('franchise.india-post-speed-post.preview.check');
        Route::delete('/preview-delete/{franchiseId}', 'deletePreview')->name('franchise.india-post-speed-post.preview.delete');

        Route::get('/excel_import/index', 'excel_import_index')->name('franchise.india-post-speed-post.excel_import.index');
        Route::post('/excel_import/store', 'excel_import_store')->name('franchise.india-post-speed-post.excel_import.store');



        // Received Parcel
        Route::get('/showReceivedParcelList', 'showReceivedParcelList')->name('franchise.india-post-speed-post.showReceivedParcelList');
        Route::get('/downloadTableOfReceivedParcel', 'downloadTableOfReceivedParcel')->name('franchise.india-post-speed-post.downloadTableOfReceivedParcel');
        Route::get('/shortPrintForReceivedParcel', 'shortPrintForReceivedParcel')->name('franchise.india-post-speed-post.shortPrintForReceivedParcel');
});

    //india Post business parcel
    Route::controller(IndiaPostBusinessController::class)->prefix('india-post-business')->group(function () {
        Route::get('/index', 'index')->name('franchise.india-post-business.index');
        Route::get('/air', 'air')->name('franchise.india-post-business.air');
        Route::get('/create', 'create')->name('franchise.india-post-business.create');
        Route::post('/store', 'store')->name('franchise.india-post-business.store');
        Route::post('/storeByfile', 'storeByfile')->name('franchise.india-post-business.storeByfile');
        Route::get('/showReceivedParcelList', 'showReceivedParcelList')->name('franchise.india-post-business.showReceivedParcelList');
        Route::post('/excelUploadByFranchise', 'excelUploadByFranchise')->name('franchise.india-post-business.excelUploadByFranchise');
        Route::get('/downloadTableOfReceivedParcel', 'downloadTableOfReceivedParcel')->name('franchise.india-post-business.downloadTableOfReceivedParcel');
        Route::get('/shortPrintForReceivedParcel', 'shortPrintForReceivedParcel')->name('franchise.india-post-business.shortPrintForReceivedParcel');
        Route::get('/delete/{id}', 'delete')->name('franchise.india-post-business.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('franchise.india-post-business.edit');
        Route::get('/view/{id}', 'view')->name('franchise.india-post-business.view');
        Route::get('/download-format', 'downloadFormat')->name('franchise.india-post-business.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('franchise.india-post-business.full-print');
        Route::get('/short-print', 'shortPrintForCreatedParcel')->name('franchise.india-post-business.shortPrintForCreatedParcel');
         Route::get('/short-label', 'shortLabelForCreatedParcel')->name('franchise.india-post-business.shortLabelForCreatedParcel');
        Route::get('/downloadTableForCreatedTable', 'downloadTableForCreatedTable')->name('franchise.india-post-business.downloadTableForCreatedTable');
        Route::match(['GET', 'POST'], '/assignBarcode', 'assignBarcode')->name('franchise.india-post-business.assignBarcode');
        Route::match(['GET', 'POST'], '/showPrice', 'showPrice')->name('franchise.india-post-business.showPrice');
        Route::get('/trackOrder/{id}', 'trackOrder')->name('franchise.india-post-business.trackOrder');
        Route::match(['GET', 'POST'], '/allprint', 'allprint')->name('franchise.india-post-business.allprint');
         Route::match(['GET', 'POST'], '/booking/details', 'getBookingDetails')->name('franchise.india-post-business.booking.get.details');
         Route::post('/mobile/details', 'mobileDetails')->name('franchise.india-post-business.mobile.pickup-details.details');
          Route::post('/email/details', 'emailDetails')->name('franchise.india-post-business.email.pickup-details.details');
          Route::post('/parcel/count', 'GetTotalAmount')->name('franchise.india-post-business.parcel.count');

        //   Cancel
        Route::post('/parcel/cancel/{id}', 'cancel')->name('franchise.india-post-business.cancel');
        Route::get('/cancel', 'cancel_index')->name('franchise.india-post-business.cancel.index');
        Route::get('/short-print/cancel', 'shortPrintForCreatedParcelCancel')->name('franchise.india-post-business.shortPrintForCreatedParcel.cancel');
        Route::get('/short-label/cancel', 'shortLabelForCreatedParcelCancel')->name('franchise.india-post-business.shortLabelForCreatedParcel.cancel');
        Route::get('/downloadTableForCreatedTable/cancel', 'downloadTableForCreatedTableCancel')->name('franchise.india-post-business.downloadTableForCreatedTable.cancel');
    });


    //india Post registered letter
    Route::controller(IndiaPostRegisteredController::class)->prefix('india-post-registered')->group(function () {

        Route::get('/index', 'index')->name('franchise.india-post-registered.index');
        Route::get('/create', 'create')->name('franchise.india-post-registered.create');
        Route::post('/store', 'store')->name('franchise.india-post-registered.store');
        Route::post('/storeByfile', 'storeByfile')->name('franchise.india-post-registered.storeByfile');
        Route::get('/showReceivedParcelList', 'showReceivedParcelList')->name('franchise.india-post-registered.showReceivedParcelList');
        Route::post('/excelUploadByFranchise', 'excelUploadByFranchise')->name('franchise.india-post-registered.excelUploadByFranchise');
        Route::get('/downloadTableOfReceivedParcel', 'downloadTableOfReceivedParcel')->name('franchise.india-post-registered.downloadTableOfReceivedParcel');
        Route::get('/shortPrintForReceivedParcel', 'shortPrintForReceivedParcel')->name('franchise.india-post-registered.shortPrintForReceivedParcel');
        Route::get('/delete/{id}', 'delete')->name('franchise.india-post-registered.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('franchise.india-post-registered.edit');
        Route::get('/view/{id}', 'view')->name('franchise.india-post-registered.view');
        Route::get('/download-format', 'downloadFormat')->name('franchise.india-post-registered.downloadForamt');
        Route::get('/full-print/{id}', 'fullPrint')->name('franchise.india-post-registered.full-print');
        Route::get('/short-print', 'shortPrintForCreatedParcel')->name('franchise.india-post-registered.shortPrintForCreatedParcel');
        Route::get('/downloadTableForCreatedTable', 'downloadTableForCreatedTable')->name('franchise.india-post-registered.downloadTableForCreatedTable');
        Route::match(['GET', 'POST'], '/assignBarcode', 'assignBarcode')->name('franchise.india-post-registered.assignBarcode');
        Route::match(['GET', 'POST'], '/showPrice', 'showPrice')->name('franchise.india-post-registered.showPrice');
        Route::get('/trackOrder/{id}', 'trackOrder')->name('franchise.india-post-registered.trackOrder');
    });


    //PickupDetails  Management
    Route::controller(PickupDetailsController::class)->prefix('pickup-enquiry')->group(function () {
        Route::get('/index', 'index')->name('franchise.pickup-details.index');
        Route::post('/details', 'details')->name('franchise.pickup-details.details');
        Route::post('/cod/details', 'codDetails')->name('franchise.cod.pickup-details.details');
        Route::post('/update/{id}', 'update')->name('franchise.pickup-details.update');
        Route::get('/delete/{id}', 'delete')->name('franchise.pickup-details.delete');

        Route::put('franchise/pickup-details/update-status/{id}', 'updateStatus')->name('franchise.pickup-details.updateStatus');
        Route::put('franchise/pickup-details/assign-delivery-boy/{id}', 'assignDeliveryBoy')->name('franchise.pickup-details.assignDeliveryBoy');
    });

    Route::get('/franchise/failed-download/{filename}', function ($filename) {
    $path = storage_path($filename);
    if (file_exists($path)) {
        return response()->download($path)->deleteFileAfterSend(true);
    }
    abort(404);
})->name('franchise.failed-download');


    //bag  Management
    Route::controller(BagController::class)->prefix('bag')->group(function () {
        Route::get('/created-bag', 'showcreatedBags')->name('franchise.bag.showCretedBags');
        Route::post('/created-bag-search', 'cretedBagsSearch')->name('franchise.bag.cretedBagsSearch');
        Route::get('/received-bag', 'showRecievedBags')->name('franchise.bag.showRecievedBags');
        Route::post('/received-bag-search', 'receivedBagsSearch')->name('franchise.bag.receivedBagsSearch');
        Route::match(['GET', 'POST'], '/scanBagReceived/{id}', 'scanBagReceived')->name('franchise.bag.scanBagReceived');
        Route::post('/getdata', 'getData')->name('franchise.bag.getData');
        Route::match(['GET', 'POST'], '/assignParcel/{id}', 'assignParcel')->name('franchise.bag.assignParcel');
        Route::match(['GET', 'POST'], '/removeParcel/{id}', 'removeParcel')->name('franchise.bag.removeParcel');
        Route::get('/viewParcelcc/{id}', 'viewParcels_of_createdBag_for_cms')->name('franchise.bag.viewParcelCMSCreated');
        Route::get('/viewParceldc/{id}', 'viewParcels_of_createdBag_for_deliveryBoy')->name('franchise.bag.viewParcelDeliveryBoyCreated');
        Route::get('/viewParcelcr/{id}', 'viewParcels_of_receivedBag_from_cms')->name('franchise.bag.viewParcelCMSReceived');
        Route::post('/store', 'store')->name('franchise.bag.store');
        Route::post('/details', 'details')->name('franchise.bag.details');
        Route::post('/update/{id}', 'update')->name('franchise.bag.update');
        Route::get('/delete/{id}', 'delete')->name('franchise.bag.delete');
        Route::get('/showAllDeliveredParcel', 'showAllDeliveredParcel')->name('franchise.bag.showAllDeliveredParcel');
        Route::get('/printCMSCreatedBag', 'printCMSCreatedBag')->name('franchise.bag.printCMSCreatedBag');
    });

    //soft Copy parcel
    Route::controller(SoftCopyController::class)->prefix('soft-copy-parcel')->group(function () {
        Route::get('/index', 'index')->name('franchise.softCopyParcel.index');
        Route::get('/create', 'create')->name('franchise.softCopyParcel.create');
        Route::post('/store', 'store')->name('franchise.softCopyParcel.store');
        Route::post('/storeByfile', 'storeByfile')->name('franchise.softCopyParcel.storeByfile');
        Route::get('/view/{id}', 'view')->name('franchise.softCopyParcel.view');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('franchise.softCopyParcel.edit');
        Route::get('/delete/{id}', 'delete')->name('franchise.softCopyParcel.delete');
        Route::get('/download-format', 'downloadFormat')->name('franchise.softCopyParcel.downloadForamt');
    });

    //mail to mail
    Route::controller(MailToMailController::class)->prefix('mail-to-mail')->group(function () {
        Route::get('/receivedMails', 'receivedMails')->name('franchise.mailToMail.receivedMails');
        Route::get('/sentMails', 'sentMails')->name('franchise.mailToMail.sentMails');
        Route::get('/attachmentReport', 'attachmentReport')->name('franchise.mailToMail.attachmentReport');
        Route::get('/create', 'create')->name('franchise.mailToMail.create');
        Route::post('/store', 'store')->name('franchise.mailToMail.store');
        Route::post('/storeByfile', 'storeByfile')->name('franchise.mailToMail.storeByfile');
        Route::get('/downloadFormat', 'downloadFormat')->name('franchise.mailToMail.downloadFormat');
        Route::get('/sentview/{id}', 'sentview')->name('franchise.mailToMail.sentview');
        Route::get('/receivedview/{id}', 'receivedview')->name('franchise.mailToMail.receivedview');
        Route::get('/delete/{id}', 'delete')->name('franchise.mailToMail.delete');
        Route::get('/receivedDate/{id}', 'receivedDate')->name('franchise.mailToMail.receivedDate');
    });


    //mail to franchise
    Route::controller(MailToFranchiseController::class)->prefix('mail-to-franchise')->group(function () {
        Route::get('/receivedMails', 'receivedMails')->name('franchise.mailTofranhchise.receivedMails');
        Route::get('/sentMails', 'sentMails')->name('franchise.mailTofranhchise.sentMails');
        Route::get('/attachmentReport', 'attachmentReport')->name('franchise.mailTofranhchise.attachmentReport');
        Route::get('/create', 'create')->name('franchise.mailTofranhchise.create');
        Route::post('/store', 'store')->name('franchise.mailTofranhchise.store');
        Route::post('/storeByfile', 'storeByfile')->name('franchise.mailTofranhchise.storeByfile');
        Route::get('/downloadFormat', 'downloadFormat')->name('franchise.mailTofranhchise.downloadFormat');
        Route::get('/sentview/{id}', 'sentview')->name('franchise.mailTofranhchise.sentview');
        Route::get('/receivedview/{id}', 'receivedview')->name('franchise.mailTofranhchise.receivedview');
        Route::get('/delete/{id}', 'delete')->name('franchise.mailTofranhchise.delete');
        Route::post('/assigntoDeliveryBoy', 'assigntoDeliveryBoy')->name('franchise.mailTofranhchise.assigntoDeliveryBoy');
        Route::get('/viewAssignedMailParcel', 'viewAssignedMailParcel')->name('franchise.mailTofranhchise.viewAssignedMailParcel');
        Route::get('/viewUnAssignedMailParcel', 'viewUnAssignedMailParcel')->name('franchise.mailTofranhchise.viewUnAssignedMailParcel');
        Route::get('/viewDeliveredMailParcel', 'viewDeliveredMailParcel')->name('franchise.mailTofranhchise.viewDeliveredMailParcel');
        Route::get('/trackOrder/{id}', 'trackOrder')->name('franchise.mail-to-franchise.trackOrder');
    });

    //Rate Calculator
    Route::controller(RateCalculator::class)->prefix('rate-calculator')->group(function () {
        Route::get('/index', 'index')->name('franchise.rateCalculator.index');
        Route::post('/calculate', 'calculate')->name('franchise.rateCalculator.calculate');
        Route::get('/distance', 'distance')->name('franchise.rateCalculator.distance');
    });
    //Service Availability
    Route::controller(ServiceAvailability::class)->prefix('service-availability')->group(function () {
        Route::match(['GET', 'POST'], '/index', 'index')->name('franchise.availability.index');
    });

    //Daily Booking report
    Route::controller(DailyBookingReportController::class)->prefix('daily-booking-report')->group(function () {
        Route::get('/index', 'index')->name('franchise.daily-booking-report.index');
    });


    //ParcelPaymentController
    Route::controller(ParcelPaymentController::class)->prefix('parcel-payment')->group(function () {
        Route::get('/payment', 'index')->name('franchise.parcel-payment.index');
        Route::post('/payment', 'store')->name('franchise.parcel-payment.store');
    });


    //FranchisePaymentController
    Route::controller(FranchisePaymentController::class)->prefix('franchise-payment')->group(function () {
        Route::get('/payment/{type}', 'index')->name('franchise.franchise-payment.index');
        Route::match(['GET', 'POST'], '/store', 'store')->name('franchise.franchise-payment.store');
        Route::get('/paymentHistory', 'paymentHistory')->name('franchise.franchise-payment.paymentHistory');
        Route::get('/print/paymentHistory', 'printPaymentHistory')->name('franchise.franchise-payment.printpaymentHistory');
        Route::get('/credit/payment', 'creditPayment')->name('franchise.franchise-payment.credit');
    });

    Route::controller(FranchiseCommissionController::class)->prefix('commission')->group(function () {
        Route::get('/commission', 'index')->name('franchise.commission.index');
        Route::get('/printCommissionDetail', 'printCommissionDetail')->name('franchise.commission.printCommissionDetail');
        
    });

    // web.php
    

});
