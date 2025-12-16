<?php

use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::patch('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::patch('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('consumers')->name('consumers.')->group(function () {
        Route::get('/', [ConsumerController::class, 'index'])->name('index');
        Route::post('/', [ConsumerController::class, 'store'])->name('store');
        Route::patch('/{consumer}', [ConsumerController::class, 'update'])->name('update');
        Route::delete('/{consumer}', [ConsumerController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        Route::get('/search', [TransactionController::class, 'search'])->name('search');
        Route::get('/search-consumer', [TransactionController::class, 'searchConsumer'])->name('searchConsumer');
        Route::get('/report', [TransactionController::class, 'report'])->name('report');
        Route::get('/{transaction}/detail', [TransactionController::class, 'show'])->name('detail');
        Route::get('/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('receipt');
    });
});

require __DIR__.'/auth.php';
