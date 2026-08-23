<?php

use App\Http\Controllers\website\HomeController;
use App\Http\Controllers\website\WebPaymentController;
use App\Http\Controllers\api\user\PincodeController;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'website'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('website.index');
    Route::post('/getPinCodes', [HomeController::class, 'getPinCodes'])->name('website.getPinCodes');
    Route::post('/pickupDetailsStore', [HomeController::class, 'pickupDetailsStore'])->name('website.pickupDetailsStore');
    Route::get('/about', [HomeController::class, 'about'])->name('website.about');
    Route::get('/contact', [HomeController::class, 'contact'])->name('website.contact');
    Route::get('/services', [HomeController::class, 'services'])->name('website.services');
    // Route::get('/trackholder', [HomeController::class, 'trackOrder'])->name('website.trackholder');
    Route::get('/ocean-freight-forwarding', [HomeController::class, 'ocean'])->name('website.oceanfreightforwarding');
    Route::get('/air-freight-forwarding', [HomeController::class, 'air'])->name('website.airfreightforwarding');
    Route::get('/faq', [HomeController::class, 'faq'])->name('website.faq');
    Route::get('/ecommerce', [HomeController::class, 'ecommerce'])->name('website.ecommerce');
    Route::get('/logistic', [HomeController::class, 'logistic'])->name('website.logistic');
    Route::get('/track-shipment', [HomeController::class, 'shipment'])->name('website.track');
    Route::get('/delivery', [HomeController::class, 'insta'])->name('website.insta');
    Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('website.privacy-policy');
    Route::get('/agreement', [HomeController::class, 'agreement'])->name('website.agreement');
    Route::get('/terms', [HomeController::class, 'terms'])->name('website.terms');
    Route::get('/road-freight', [HomeController::class, 'roadFreight'])->name('website.roadFreight');
    Route::get('/login', [HomeController::class, 'login'])->name('website.login');
    Route::get('/register', [HomeController::class, 'register'])->name('website.register');
    Route::get('/deleteUser', [HomeController::class, 'deleteUser'])->name('website.deleteUser');
    Route::post('/user/deleteUserAccount', [HomeController::class, 'deleteUserAccount'])->name('website.deleteUserAccount');


    Route::get('/deleteDelivery', [HomeController::class, 'deleteDelivery'])->name('website.deleteDelivery');
    Route::post('/user/deleteDeliveryAccount', [HomeController::class, 'deleteDeliveryAccount'])->name('website.deleteDeliveryAccount');




    // Payment
    Route::get('/pay', [WebPaymentController::class, 'index'])->name('website.pay');
    Route::match(['GET', 'POST'], '/store', [WebPaymentController::class, 'store'])->name('website.payment.store');
    Route::get('/print/paymentHistory', [WebPaymentController::class, 'printPaymentHistory'])->name('website.payment.printpaymentHistory');

    // End Payment

    Route::get('/trackOrder', [HomeController::class, 'trackOrder'])->name('website.trackOrder');
    Route::match(['GET', 'POST'], '/trackOrder', [HomeController::class, 'trackOrder'])->name('website.trackholder');
    Route::match(['GET', 'POST'], '/franchiseByCode', [HomeController::class, 'franchiseByCode'])->name('website.franchiseByCode');
    Route::match(['GET', 'POST'], '/franchiseByPhone', [HomeController::class, 'franchiseByPhone'])->name('website.franchiseByPhone');
    Route::match(['GET', 'POST'], '/franchiseByPin', [HomeController::class, 'franchiseByPin'])->name('website.franchiseByPin');
    Route::match(['GET', 'POST'], '/e2hTrackOrder', [HomeController::class, 'e2hTrackOrder'])->name('website.e2hTrackOrder');
    Route::get('/receivedDate', [HomeController::class, 'receivedDate'])->name('website.receivedDate');


    Route::post('/contact-us-store', [HomeController::class, 'store'])->name('websiteMessage.store');
    Route::match(['GET', 'POST'], '/nearestfranchiseByPincode/{pincode}', [HomeController::class, 'nearestfranchiseByPincode'])->name('website.nearestfranchiseByPincode');

    Route::match(['GET', 'POST'], '/testmail', [HomeController::class, 'testmail'])->name('website.testmail');
    Route::match(['GET', 'POST'], '/register/pcakage', [HomeController::class, 'registerPackage'])->name('website.register.pcakage');
        Route::post('/getPrice', [PincodeController::class, 'getPrice'])->name('website.getPrice');
});
