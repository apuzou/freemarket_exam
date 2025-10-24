<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/search', [ItemController::class, 'search'])->name('search');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('item.show');

Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Route::post('/email/check', [VerificationController::class, 'check'])->name('verification.check');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::middleware(['auth'])->group(function () {
    Route::get('/sell', [ItemController::class, 'create'])->name('sell');
    Route::post('/sell', [ItemController::class, 'store'])->name('sell.store');

    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage');

    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.profile.update');

    Route::get('/purchase/{item}', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');

    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
    Route::patch('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

    Route::post('/item/{item}/like', [ItemController::class, 'toggleLike'])->name('item.like');
    Route::post('/item/{item}/comment', [ItemController::class, 'addComment'])->name('item.comment');
});
