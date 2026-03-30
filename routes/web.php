<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/jeux', [GameController::class, 'index'])->name('games.index');
Route::get('/jeux/search', [GameController::class, 'search'])->name('games.search');

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/commandes', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/emprunts', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/emprunts/nouveau', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/emprunts', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::patch('/emprunts/{borrowing}/retour', [BorrowingController::class, 'markReturned'])->name('borrowings.return');
    Route::post('/panier/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

Route::prefix('panier')->group(function (): void {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/ajouter', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/{game}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/{game}', [CartController::class, 'remove'])->name('cart.remove');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/utilisateurs', [AdminController::class, 'users'])->name('users.index');
    Route::patch('/utilisateurs/{user}/role', [AdminController::class, 'updateRole'])->name('users.role');
    Route::get('/jeux/create', [GameController::class, 'create'])->name('games.create');
    Route::post('/jeux', [GameController::class, 'store'])->name('games.store');
    Route::get('/jeux/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
    Route::put('/jeux/{game}', [GameController::class, 'update'])->name('games.update');
    Route::delete('/jeux/{game}', [GameController::class, 'destroy'])->name('games.destroy');
});
