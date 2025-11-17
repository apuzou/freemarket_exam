<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Fortifyが自動的にログイン・会員登録のルートを登録するため、カスタムルートは削除
// ログアウトはFortifyのルートを使用
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/search', [ItemController::class, 'search'])->name('search');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('item.show');

Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
Route::get('/email/verify-code', [VerificationController::class, 'showCodeInput'])->name('verification.show-code');
Route::post('/email/verify-code', [VerificationController::class, 'verifyCode'])->name('verification.verify-code');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::middleware(['auth'])->group(function () {
    Route::get('/sell', [ItemController::class, 'create'])->name('sell');
    Route::post('/sell', [ItemController::class, 'store'])->name('sell.store');

    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage');

    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.profile.update');

    Route::get('/purchase/{item}', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/success/{item}', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/cancel/{item}', [PurchaseController::class, 'cancel'])->name('purchase.cancel');

    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
    Route::patch('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

    Route::post('/item/{item}/like', [ItemController::class, 'toggleLike'])->name('item.like');
    Route::post('/item/{item}/comment', [ItemController::class, 'addComment'])->name('item.comment');
});
