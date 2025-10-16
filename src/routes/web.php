<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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

Route::middleware(['auth'])->group(function () {
    // マイページルート
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage');
    
    // マイページプロフィールルート（統合）
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.profile.update');
});
