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
use App\Http\Controllers\franchise\FranchiseRegisterController;
use App\Http\Controllers\FranchiseRegistrationController;






// routes/web.php mein
Route::get('/debug-prepaid-user', function() {
    $user = auth()->guard('prepaid')->user();
    
    if (!$user) {
        return "Not logged in as prepaid";
    }
    
    return [
        'user_id' => $user->id,
        'phone' => $user->phone,
        'email' => $user->email,
        'route_exists' => Route::has('prepaid.profile'),
        'current_route' => Route::currentRouteName()
    ];
})->middleware('auth:prepaid');










// Bulk Import Routes
Route::get('/india-post-speed-post/bulk-import', [IndiaPostBulkImportController::class, 'index'])
    ->name('franchise.india-post-speed-post.bulk-import');
    
Route::post('/india-post-speed-post/bulk-upload', [IndiaPostBulkImportController::class, 'processBulkUpload'])
    ->name('franchise.india-post-speed-post.bulk-upload');
    
Route::get('/india-post-speed-post/download-updated-format', [IndiaPostBulkImportController::class, 'downloadUpdatedFormat'])
    ->name('franchise.india-post-speed-post.download-updated-format');
	
Route::post('/india-post-speed-post/process-batch',[IndiaPostSpeedPostController::class, 'processBatch']
)   ->name('franchise.india-post-speed-post.process-batch');

Route::post('confirm-store-excel', [IndiaPostSpeedPostController::class, 'confirmAndStoreExcel'])
        ->name('confirm.store.excel');
		
Route::get('/prepaid/booking/short-print', [PrepaidController::class, 'shortPrintForCreatedParcel'])
    ->name('prepaid.booking.shortPrintForCreatedParcel');
    
    
Route::post('/storeByfile', [IndiaPostSpeedPostController::class, 'storeByFile'])
    ->name('franchise.india-post-speed-post.storeByfile');