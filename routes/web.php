<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth', 'role:admin,petugas')->group(function () {
    Route::resource('category', CategoryController::class)->only('index', 'store', 'update', 'destroy');

    Route::delete('book/bulk-delete', [BookController::class, 'bulkDelete'])->name('book.bulkDelete');
    Route::resource('book', BookController::class)->only('index', 'store', 'update', 'destroy', 'show');

    Route::patch('member/bulk-status', [MemberController::class, 'bulkUpdateStatus'])
        ->name('member.bulkUpdateStatus');
    Route::delete('member/bulk-delete', [MemberController::class, 'bulkDelete'])
        ->name('member.bulkDelete');
    Route::patch('member/{member}/status', [MemberController::class, 'updateStatus'])
        ->name('member.updateStatus');
    Route::get('member/{member}/pdf', [MemberController::class, 'printProfile'])
        ->name('member.pdf');

    Route::resource('member', MemberController::class)
        ->only('index', 'store', 'update', 'destroy', 'show');

    Route::patch('borrowing/{borrowing}/status', [BorrowingController::class, 'updateStatus'])
        ->name('borrowing.updateStatus');
    Route::resource('borrowing', BorrowingController::class)->only('index', 'store', 'show', 'destroy');

    Route::resource('return', ReturnController::class)->only('index', 'show', 'store');

    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
require __DIR__.'/auth.php';
