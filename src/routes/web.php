<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 認証ルート（ゲストのみアクセス可能）
Route::middleware(['guest'])->group(function () {
    // ログイン
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // 会員登録
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// ログアウト（認証済みユーザーのみ）
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// 商品一覧画面（トップ画面）
Route::get('/', [ItemController::class, 'index'])->name('home');

// 検索機能
Route::get('/search', [ItemController::class, 'search'])->name('search');

// 商品詳細ルート（認証不要）
Route::get('/item/{item}', [ItemController::class, 'show'])->name('item.show');

// メール認証ルート（認証不要）
Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Route::post('/email/check', [VerificationController::class, 'check'])->name('verification.check');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::middleware(['auth'])->group(function () {
    // 商品出品ルート
    Route::get('/sell', [ItemController::class, 'create'])->name('sell');
    Route::post('/sell', [ItemController::class, 'store'])->name('sell.store');
    
    // マイページルート
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage');
    
    // マイページプロフィールルート（統合）
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.profile.update');
    
    // いいね・コメント機能（認証必須）
    Route::post('/item/{item}/like', [ItemController::class, 'toggleLike'])->name('item.like');
    Route::post('/item/{item}/comment', [ItemController::class, 'addComment'])->name('item.comment');
});
