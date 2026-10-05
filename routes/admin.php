<?php

use App\Http\Controllers\admin\AdminRoleController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\FranchiseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\SupportTicketController;
use App\Http\Controllers\CityStateController;
use App\Http\Controllers\admin\cmsController;
use App\Http\Controllers\admin\userController;
use App\Http\Controllers\admin\ParcelController;
use App\Http\Controllers\admin\PickupDetailsController;
use App\Http\Controllers\admin\PostalRatesController;
use App\Http\Controllers\admin\IndiaPostBRRatesController;
use App\Http\Controllers\admin\GotogoPostalRatesController;
use App\Http\Controllers\admin\BarcodeUploadController;
use App\Http\Controllers\admin\FranchiseDailyBookingReportController;
use App\Http\Controllers\admin\CommissionController;
use App\Http\Controllers\admin\IndiaPostCommissionController;
use App\Http\Controllers\admin\PPHController;
use App\Http\Controllers\admin\DeliveryBoyController;
use App\Http\Controllers\admin\LinkController;
use App\Http\Controllers\admin\WebPaymentController;
use App\Http\Controllers\admin\PaymentController;
use App\Http\Controllers\admin\IndiaPostBarcodeController;
use App\Http\Controllers\admin\AllotedBarcodeController;
use App\Http\Controllers\admin\ECustomerController;
use App\Http\Controllers\admin\MManagerController;

// use App\Http\Controllers\user\userController;


Route::controller(LoginController::class)->group(function () {
    Route::match(['GET', 'POST'], '/login', 'login')->name('admin.login');
    Route::match(['GET', 'POST'], '/profile', 'profile')->name('admin.profile');
});
Route::controller(CityStateController::class)->group(function () {
    Route::get('/city-state/{pincode}', 'getCityState')->name('admin.getCityState');
    Route::get('/city-state/gotogo/{pincode}', 'gotogoCityState')->name('admin.gotogo.city-state');
     Route::get('gotogo-pincode', 'gotogoPincode')->name('admin.gotogo.pincode.index');
     Route::get('gotogo-state', 'getState')->name('admin.gotogo.getState');
     Route::post('gotogo-state/status', 'status')->name('admin.gotogo.pincode.status');
});

Route::group([
    'middleware' => ['adminAuth']
], function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('admin.dashboard');
        Route::get('website-message','websiteMessageIndex')->name('websideMessage.index');
        Route::get('/website-message-delete/{id}','websiteMessageDelete')->name('websideMessage.delete');

    });

    //Role & Permission
    Route::controller(AdminRoleController::class)->group(function () {
        Route::get('/role/{id}', 'index')->where('id', '[0-9]+')->name('admin.role.index');
        Route::post('/role/create', 'role_create')->name('admin.role.create');
        Route::post('/role/update/{id}', 'role_update')->name('admin.role.update');
        Route::get('/role/delete/{id}', 'role_delete')->name('admin.role.delete');
        Route::post('/permission/store/{id}', 'store_permission')->name('admin.store.permission');

        //Role Users
        Route::get('/role/user', 'role_user')->name('admin.role.user');
        Route::post('/role/user/store', 'new_role_user')->name('admin.role.user.store');
        Route::post('/role/user/update/{id}', 'update_role_user')->name('admin.role.user.update');
        Route::get('/role/user/delete/{id}', 'delete_role_user')->name('admin.role.user.delete');
        Route::post('/role/user/status', 'status_role_user')->name('admin.role.user.status');
    });

    //Franchise Management
   Route::controller(FranchiseController::class)
    ->prefix('franchise')
    ->group(function () {

        Route::get('/index', 'index')->name('admin.franchise.index');
        Route::match(['GET', 'POST'], '/commissions', 'commissions')->name('admin.franchise.commissions');
        Route::match(['GET', 'POST'], '/create', 'create')->name('admin.franchise.create');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('admin.franchise.edit');
        Route::match(['GET', 'POST'], '/delelete/{id}', 'delete')->name('admin.franchise.delete');
        Route::post('/status', 'status')->name('admin.franchise.status');
        Route::post('/serviceStatus', 'serviceStatus')->name('admin.franchise.serviceStatus');
        Route::get('/view/{id}', 'view')->name('admin.franchise.view');
        Route::get('/chat/{id}', 'chat')->name('admin.franchise.chat');
        Route::get('/commissionDetail/{id}', 'commissionDetail')->name('admin.franchise.commissionDetail');
        Route::match(['GET', 'POST'], '/creditDetails/{id}', 'creditDetails')->name('admin.franchise.creditDetails');
        Route::match(['GET', 'POST'], '/securityDetails/{id}', 'securityDetails')->name('admin.franchise.securityDetails');
        Route::get('/printCommissionDetail/{id}', 'printCommissionDetail')->name('admin.franchise.printCommissionDetail');
        Route::get('/paymentHistory/{membertype}', 'paymentHistory')->name('admin.franchise.paymentHistory');
        Route::get('/gotogo/paymentHistory/{membertype}', 'gotogopaymentHistory')->name('admin.franchise.gotogo.paymentHistory');
        Route::get('/created/paymentHistory/{membertype}', 'createdpaymentHistory')->name('admin.franchise.created.paymentHistory');
        Route::get('/india/paymentHistory/{membertype}', 'indiapaymentHistory')->name('admin.franchise.india.paymentHistory');
        Route::get('/print/paymentHistory', 'printPaymentHistory')->name('admin.franchise.printpaymentHistory');

        Route::get('/payment/credit/{membertype}', 'paymentCreate')->name('admin.franchise.payment.create');
        Route::match(['GET', 'POST'], 'paymentCreate/store', 'store')->name('admin.franchise-payment.store');
         Route::get('/view/{id}', 'view')->name('admin.franchise.view');
         Route::post('/view/{id}/commission', 'saveFranchiseCommission')->name('admin.franchise.commission.save');

        // -------------------------
        //   Correct Service Routes
        // -------------------------
        Route::get('/service/go-speed-post-parcel/{id}', 'speed_post_parcel')
            ->name('admin.franchise.service.go-speed-post-parcel');

        Route::get('/service/go-business-parcel/{id}', 'bussiness_parcel')
            ->name('admin.franchise.service.go-business-parcel');

        Route::get('/service/go-registered/{id}', 'registered')
            ->name('admin.franchise.service.go-registered');

        Route::get('/service/india-post-speed-post/{id}', 'india_post_speed')
            ->name('admin.franchise.service.india-post-speed-post');

        Route::get('/service/india-post-business/{id}', 'bussiness_post')
            ->name('admin.franchise.service.india-post-business');

            Route::get('/service/india-post-business/air', 'bussiness_post_air')
            ->name('admin.franchise.service.india-post-business.air');
    });


    //CMS Management
    Route::controller(cmsController::class)->prefix('cms')->group(function () {
        Route::get('/index', 'index')->name('admin.cms.index');
        Route::match(['GET', 'POST'], '/commissions', 'commissions')->name('admin.cms.commissions');
        Route::match(['GET', 'POST'], '/create', 'create')->name('admin.cms.create');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('admin.cms.edit');
        Route::post('/status', 'status')->name('admin.cms.status');
        Route::post('/serviceStatus', 'serviceStatus')->name('admin.cms.serviceStatus');
        Route::get('/view/{id}', 'view')->name('admin.cms.view');
        Route::get('/update/{id}', 'edit')->name('admin.cms.update');
        Route::get('/delete/{id}', 'delete')->name('admin.cms.delete');
        Route::get('/commissionDetail/{id}', 'commissionDetail')->name('admin.cms.commissionDetail');
        Route::get('/printCommissionDetail/{id}', 'printCommissionDetail')->name('admin.cms.printCommissionDetail');
        Route::get('/paymentHistory/{membertype}', 'paymentHistory')->name('admin.cms.paymentHistory');
        Route::get('/print/paymentHistory', 'printPaymentHistory')->name('admin.cms.printpaymentHistory');
    });

    //PPH Management
    Route::controller(PPHController::class)->prefix('pph')->group(function () {
        Route::get('/index', 'index')->name('admin.pph.index');
        Route::match(['GET', 'POST'], '/create', 'create')->name('admin.pph.create');
        Route::match(['GET', 'POST'], '/commissions', 'commissions')->name('admin.pph.commissions');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('admin.pph.edit');
        Route::post('/status', 'status')->name('admin.pph.status');
        Route::post('/serviceStatus', 'serviceStatus')->name('admin.pph.serviceStatus');
        Route::get('/view/{id}', 'view')->name('admin.pph.view');
        Route::get('/update/{id}', 'edit')->name('admin.pph.update');
        Route::get('/delete/{id}', 'delete')->name('admin.pph.delete');
        Route::get('/commissionDetail/{id}', 'commissionDetail')->name('admin.pph.commissionDetail');
        Route::get('/printCommissionDetail/{id}', 'printCommissionDetail')->name('admin.pph.printCommissionDetail');
        Route::get('/paymentHistory/{membertype}', 'paymentHistory')->name('admin.pph.paymentHistory');
        Route::get('/print/paymentHistory', 'printPaymentHistory')->name('admin.pph.printpaymentHistory');
    });



    //Delivery boy Management
    Route::controller(DeliveryBoyController::class)->prefix('deliveryBoy')->group(function () {
        Route::get('/index', 'index')->name('admin.deliveryBoy.index');
        Route::match(['GET', 'POST'], '/create', 'create')->name('admin.deliveryBoy.create');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('admin.deliveryBoy.edit');
        Route::post('/status', 'status')->name('admin.deliveryBoy.status');
        Route::post('/serviceStatus', 'serviceStatus')->name('admin.deliveryBoy.serviceStatus');
        Route::get('/view/{id}', 'view')->name('admin.deliveryBoy.view');
        Route::get('/update/{id}', 'edit')->name('admin.deliveryBoy.update');
        Route::get('/delete/{id}', 'delete')->name('admin.deliveryBoy.delete');
        Route::match(['GET', 'POST'], '/commissions', 'commissions')->name('admin.deliveryBoy.commissions');
        Route::get('/commissionDetail/{id}', 'commissionDetail')->name('admin.deliveryBoy.commissionDetail');
        Route::get('/printCommissionDetail/{id}', 'printCommissionDetail')->name('admin.deliveryBoy.printCommissionDetail');
    });

    //user Management
    Route::controller(userController::class)->prefix('user')->group(function () {
        Route::get('/index', 'index')->name('admin.user.index');
        Route::post('/store', 'store')->name('admin.user.store');
        Route::post('/status', 'status_update')->name('admin.user.status');
        Route::post('/update/{id}', 'update')->name('admin.user.update');
        Route::get('/delete/{id}', 'delete')->name('admin.user.delete');
        Route::get('/paymentHistory/{membertype}', 'paymentHistory')->name('admin.user.paymentHistory');
        Route::get('/print/paymentHistory', 'printPaymentHistory')->name('admin.user.printpaymentHistory');
    });
    //Support Ticket Management
    Route::controller(SupportTicketController::class)->prefix('support-ticket')->group(function () {
        Route::get('/index', 'index')->name('admin.support-ticket.index');
        Route::Post('/ticket/store', 'store')->name('admin.support-ticket.store');
        Route::Post('/ticket/update/{id}', 'updateChat')->name('admin.support-ticket.update');
        Route::get('/ticket/delete/{id}', 'delete')->name('admin.support-ticket.delete');
        Route::get('support-ticket-detail/{id}', 'getDetails')->name('admin.support-ticket.get_details');
        Route::post('send', 'chatStore')->name('admin.support-ticket.send');
        Route::post('/chat/update/{id}', 'updateChat')->name('admin.support-ticket.updateChat');
        Route::get('/chat/delete/{id}', 'deleteChat')->name('admin.support-ticket.deleteChat');
        Route::get('clear', 'clearAll')->name('admin.support-ticket.clear');
    });

    //Parcel Ticket Management
    Route::controller(ParcelController::class)->prefix('parcel')->group(function () {
        Route::get('/index', 'index')->name('admin.parcel.index');
        Route::get('/create', 'create')->name('admin.parcel.create');
        Route::post('/store', 'store')->name('admin.parcel.store');
        Route::get('/delete/{id}', 'delete')->name('admin.parcel.delete');
        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')->name('admin.parcel.edit');
        Route::get('/view/{id}', 'view')->name('admin.parcel.view');
        
    });
        
    //PickupDetails  Management
    Route::controller(PickupDetailsController::class)->prefix('pickup-details')->group(function () {
        Route::get('/index', 'index')->name('admin.pickup-details.index');
        Route::post('/store', 'store')->name('admin.pickup-details.store');
        Route::post('/details', 'details')->name('admin.pickup-details.details');
        Route::post('/update/{id}', 'update')->name('admin.pickup-details.update');
        Route::get('/delete/{id}', 'delete')->name('admin.pickup-details.delete');
    });

Route::controller(ECustomerController::class)
    ->prefix('e-customer')
    ->group(function () {

        Route::get('/index', 'index')
            ->name('admin.e-customer.index');

        Route::match(['GET', 'POST'], '/create', 'create')
            ->name('admin.e-customer.create');

        Route::match(['GET', 'POST'], '/edit/{id}', 'edit')
            ->name('admin.e-customer.edit');

        Route::post('/store', 'store')
            ->name('admin.e-customer.store');

        Route::post('/update/{id}', 'update')
            ->name('admin.e-customer.update');

        Route::post('/status', 'status')
            ->name('admin.e-customer.status');

        Route::get('/delete/{id}', 'delete')
            ->name('admin.e-customer.delete');

        Route::get('/view/{id}', 'view')
    ->name('admin.e-customer.view');
    Route::match(['GET', 'POST'], '/securityDetails/{id}', 'securityDetails')
    ->name('admin.e-customer.securityDetails');
    Route::post('/commission/save/{id}', 'saveCommission')
    ->name('admin.e_customer.commission.save');

    Route::post('/service-status', 'serviceStatus')
            ->name('admin.e-customer.serviceStatus');


        // -------------------------
        // Business Bulk Services
        // -------------------------

        Route::get(
            '/service/go-speed-post-parcel/{id}',
            'speed_post_parcel'
        )->name('admin.e-customer.service.go-speed-post-parcel');

        Route::get(
            '/service/go-business-parcel/{id}',
            'bussiness_parcel'
        )->name('admin.e-customer.service.go-business-parcel');

        Route::get(
            '/service/go-registered/{id}',
            'registered'
        )->name('admin.e-customer.service.go-registered');

        Route::get(
            '/service/india-post-speed-post/{id}',
            'india_post_speed'
        )->name('admin.e-customer.service.india-post-speed-post');

        Route::get(
            '/service/india-post-business/{id}',
            'bussiness_post'
        )->name('admin.e-customer.service.india-post-business');

        Route::get(
            '/service/india-post-business/air/{id}',
            'bussiness_post_air'
        )->name('admin.e-customer.service.india-post-business.air');
    });


// Sales Marketing Management
Route::controller(MManagerController::class)->prefix('m-manager')->group(function () {
    Route::get('/index', 'index')
        ->name('admin.m_manager.index');

    // Create
    Route::match(['GET', 'POST'], '/create', 'create')
        ->name('admin.m_manager.create');

    // Store
    Route::post('/store', 'store')
        ->name('admin.m_manager.store');

    // Edit
    Route::match(['GET', 'POST'], '/edit/{id}', 'edit')
        ->name('admin.m_manager.edit');

    // Update
    Route::post('/update/{id}', 'update')
        ->name('admin.m_manager.update');

    // View
    Route::get('/view/{id}', 'view')
        ->name('admin.m_manager.view');

    // Status
    Route::post('/status', 'status')
        ->name('admin.m_manager.status');

    // Advance Amount
    Route::match(['GET', 'POST'], '/security-details/{id}', 'securityDetails')
        ->name('admin.m_manager.securityDetails');

    // Credit Amount
    Route::match(['GET', 'POST'], '/credit-details/{id}', 'creditDetails')
        ->name('admin.m_manager.creditDetails');

    // Delete
    Route::get('/delete/{id}', 'delete')
        ->name('admin.m_manager.delete');

        Route::post('/commission/save/{id}', 'saveCommission')
    ->name('admin.m_manager.commission.save');

    Route::post('/service-status', 'serviceStatus')
    ->name('admin.m_manager.serviceStatus');
});

    //Postal Rates Management
    Route::controller(PostalRatesController::class)->prefix('postal-rates')->group(function () {
        Route::get('/index', 'index')->name('admin.postal-rates.index');
        Route::get('/create', 'create')->name('admin.postal-rates.create');
        Route::post('/store', 'store')->name('admin.postal-rates.store');
        Route::match(['GET', 'POST'], '/edit', 'edit')->name('admin.postal-rates.edit');
        Route::get('/delete/{id}', 'delete')->name('admin.postal-rates.delete');
    });

    //India Post Parcel Contractual
    Route::controller(IndiaPostBRRatesController::class)->prefix('india-post-br-rate')->group(function () {
        Route::get('/index', 'index')->name('admin.india-post-br-rate.index');
        Route::get('/create', 'create')->name('admin.india-post-br-rate.create');
        Route::post('/store', 'store')->name('admin.india-post-br-rate.store');
        Route::match(['GET', 'POST'], '/edit', 'edit')->name('admin.india-post-br-rate.edit');
        Route::get('/delete/{id}', 'delete')->name('admin.india-post-br-rate.delete');
    });


    //gotogo Postal Rates Management
    Route::controller(GotogoPostalRatesController::class)->prefix('gotogo-postal-rates')->group(function () {
        Route::get('/index', 'index')->name('admin.gotogo-postal-rates.index');
        Route::get('/create', 'create')->name('admin.gotogo-postal-rates.create');
        Route::post('/store', 'store')->name('admin.gotogo-postal-rates.store');
        Route::match(['GET', 'POST'], '/edit', 'edit')->name('admin.gotogo-postal-rates.edit');
        Route::get('/delete/{id}', 'delete')->name('admin.gotogo-postal-rates.delete');
    });


    //Barcode Upload Management
    Route::controller(BarcodeUploadController::class)->prefix('barcode-upload')->group(function () {
        Route::get('/index', 'index')->name('admin.barcode-upload.index');
        Route::get('/create', 'create')->name('admin.barcode-upload.create');
        Route::post('/franchiseStore', 'franchiseBarcodeStore')->name('admin.franchise-barcode-upload.store');
        Route::post('/cmsStore', 'cmsBarcodeStore')->name('admin.cms-barcode-upload.store');
        Route::post('/pphStore', 'pphBarcodeStore')->name('admin.pph-barcode-upload.store');
        Route::match(['GET', 'POST'], '/edit', 'edit')->name('admin.barcode-upload.edit');
        Route::get('/delete/{id}', 'delete')->name('admin.barcode-upload.delete');
    });

    // Alloted Barcodes
    Route::controller(AllotedBarcodeController::class)->prefix('alloted-barcode')->group(function () {
        Route::get('/franchiseParcel', 'franchiseParcel')->name('admin.alloted-barcode.franchiseParcel');
        Route::get('/cphParcel', 'cphParcel')->name('admin.alloted-barcode.cphParcel');
        Route::get('/pphParcel', 'pphParcel')->name('admin.alloted-barcode.pphParcel');
        Route::get('/franchiseBag', 'franchiseBag')->name('admin.alloted-barcode.franchiseBag');
        Route::get('/cphBag', 'cphBag')->name('admin.alloted-barcode.cphBag');
        Route::get('/pphBag', 'pphBag')->name('admin.alloted-barcode.pphBag');
    });

    //Daily Booking report
    Route::controller(FranchiseDailyBookingReportController::class)->prefix('daily-booking')->group(function () {
        Route::get('/index', 'allFranchise')->name('admin.daily-booking-report.all');
        Route::get('/index/{id}', 'eachFranchise')->name('admin.daily-booking-report.each');
    });

    //Commission table
    Route::controller(CommissionController::class)->prefix('commission')->group(function () {
        Route::get('/index', 'index')->name('admin.commission.index');
        Route::get('/create', 'create')->name('admin.commission.create');
        Route::post('/store', 'store')->name('admin.commission.store');
        Route::match(['GET', 'POST'], '/edit', 'edit')->name('admin.commission.edit');
        Route::get('/delete/{id}', 'delete')->name('admin.commission.delete');
         Route::get('/franchise', 'franchiseCommission')->name('admin.commission.franchise');
    Route::post('/franchise/save', 'saveFranchiseCommission')->name('admin.commission.franchise.save');

    });

    // India post commission
    Route::controller(IndiaPostCommissionController::class)->prefix('india-post-commission')->group(function () {
        Route::get('/index', 'index')->name('admin.india-post-commission.index');
        Route::get('/create', 'create')->name('admin.india-post-commission.create');
        Route::post('/store', 'store')->name('admin.india-post-commission.store');
        Route::match(['GET', 'POST'], '/edit', 'edit')->name('admin.india-post-commission.edit');
        Route::get('/delete/{id}', 'delete')->name('admin.india-post-commission.delete');
    });



    //Link
    Route::controller(LinkController::class)->prefix('link')->group(function () {
        Route::get('/gotogoLinks', 'gotogoLinks')->name('admin.link.gotogo');
        Route::get('/indiaPostLinks', 'indiaPostLinks')->name('admin.link.indiaPost');
        Route::get('/create', 'create')->name('admin.link.create');
        Route::post('/gotogoLink', 'gotogoLink')->name('admin.gotogoLink.store');
        Route::post('/indiaPostLink', 'indiaPostLink')->name('admin.indiaPostLink.store');

        Route::match(['GET', 'POST'], '/edit', 'edit')->name('admin.link.edit');
        Route::get('/delete/{id}', 'delete')->name('admin.link.delete');
    });

    Route::controller(WebPaymentController::class)->prefix('web')->group(function () {
        Route::get('/paymentHistory/{membertype}', 'paymentHistory')->name('admin.web.paymentHistory');
        Route::get('/print/paymentHistory', 'printPaymentHistory')->name('admin.web.printpaymentHistory');
    });

    Route::controller(PaymentController::class)->prefix('payment')->group(function () {
        Route::get('/registration/amount', 'index')->name('admin.payment.amount');
        Route::post('/registration/amount/update', 'store')->name('admin.payment.amount.store');
    });



    Route::get('/indiapostbarcodes', [IndiaPostBarcodeController::class, 'index'])->name('indiapostbarcodes.index');
    Route::post('/indiapostbarcodes', [IndiaPostBarcodeController::class, 'store'])->name('indiapostbarcodes.store');
    Route::get('/indiapostbarcodes/next', [IndiaPostBarcodeController::class, 'getNextBarcode'])->name('indiapostbarcodes.next');
    Route::get('/indiapostbarcodes/{id}/edit', [IndiaPostBarcodeController::class, 'edit'])->name('indiapostbarcodes.edit');
    //Route::post('/indiapostbarcodes/{id}/update', [IndiaPostBarcodeController::class, 'update'])->name('indiapostbarcodes.update');
    Route::post('indiapostbarcodes/{id}/update', [IndiaPostBarcodeController::class, 'update'])->name('indiapostbarcodes.update');



    Route::get('indiapostbarcodes/{id}/delete', [IndiaPostBarcodeController::class, 'destroy'])->name('indiapostbarcodes.destroy');
});

