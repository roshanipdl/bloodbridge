<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BloodDonationController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\RecipientController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\NotificationController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Blood donation routes
    Route::get('/donate', [BloodRequestController::class, 'index'])->name('donate');
    
    // Blood request routes
    Route::get('/request', [BloodRequestController::class, 'create'])->name('request');
    Route::get('/request/{bloodRequest}/edit', [BloodRequestController::class, 'edit'])->name('request.edit');
    Route::post('/request', [BloodRequestController::class, 'store'])->name('request.store');
    Route::put('/request/{bloodRequest}', [BloodRequestController::class, 'update'])->name('request.update');

    // Add this inside the auth middleware group
    Route::post('/donate/{bloodRequest}', [BloodRequestController::class, 'respond'])->name('donate.respond');
    Route::get('/requests/my', [BloodRequestController::class, 'myRequests'])->name('requests.my');

    // Donor routes
    Route::get('/donor/create', [DonorController::class, 'create'])->name('donor.create');
    Route::post('/donor', [DonorController::class, 'store'])->name('donor.store');
    Route::get('/donor/{donor}', [DonorController::class, 'show'])->name('donor.show');
    Route::get('/donor/{donor}', [DonorController::class, 'edit'])->name('donor.edit');
    Route::get('/donor/{donor}/history', [DonorController::class, 'history'])->name('donor.history');
    Route::put('/donor/{donor}', [DonorController::class, 'profileUpdate'])->name('donor.update');

    // Recipient routes
    Route::get('/recipients/my', [RecipientController::class, 'myRecipients'])->name('recipients.my');
    Route::get('/recipient/{recipient}/edit', [RecipientController::class, 'edit'])->name('recipient.edit');
    Route::put('/recipient/{recipient}', [RecipientController::class, 'update'])->name('recipient.update');
    Route::delete('/recipient/{recipient}', [RecipientController::class, 'destroy'])->name('recipient.destroy');
});

Route::middleware('auth')->group(function () {
    // Blood request routes
    Route::delete('/blood-request/{bloodRequest}', [BloodRequestController::class, 'destroy'])->name('blood.request.destroy');
    Route::get('/blood-request/{bloodRequest}/matching-donors', [BloodRequestController::class, 'viewMatchingDonors'])->name('blood.request.matching-donors');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/donor', [ProfileController::class, 'updateDonor'])->name('profile.update-donor');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Recipient management routes
    Route::get('/recipients/create', [RecipientController::class, 'create'])->name('recipients.create');
    Route::post('/recipients', [RecipientController::class, 'store'])->name('recipients.store');
    Route::get('/recipients/{recipient}/edit', [RecipientController::class, 'edit'])->name('recipients.edit');
    Route::put('/recipients/{recipient}', [RecipientController::class, 'update'])->name('recipients.update');
    Route::delete('/recipients/{recipient}', [RecipientController::class, 'destroy'])->name('recipient.destroy');
});

// Notification route
Route::get('/send-notification/{id}', [NotificationController::class, 'sendNotification'])->name('send.notification');

require __DIR__.'/auth.php';
