<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BloodDonationController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\RecipientController;
use App\Http\Controllers\DonorController;
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
    Route::post('/request', [BloodRequestController::class, 'store'])->name('request.store');

    // Add this inside the auth middleware group
    Route::post('/donate/{bloodRequest}', [BloodRequestController::class, 'respond'])->name('donate.respond');

    // Donor creation routes
    Route::get('/donor/create', [DonorController::class, 'create'])->name('donor.create');
    Route::post('/donor', [DonorController::class, 'store'])->name('donor.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Recipient management routes
    Route::get('/recipients/create', [RecipientController::class, 'create'])->name('recipients.create');
    Route::post('/recipients', [RecipientController::class, 'store'])->name('recipients.store');
    Route::get('/recipients/{recipient}/edit', [RecipientController::class, 'edit'])->name('recipients.edit');
    Route::put('/recipients/{recipient}', [RecipientController::class, 'update'])->name('recipients.update');
});

require __DIR__.'/auth.php';
