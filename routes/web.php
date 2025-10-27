<?php

use App\Http\Controllers\Auth\Authcontroller;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PelangganController;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('index ')->name('index.index');
// });

Route::get('/', [Authcontroller::class, 'login'])->name('login');
Route::post('/login', [Authcontroller::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [Authcontroller::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Admin & CS - full customer management (CRUD)
    Route::prefix('/customer')->middleware('role:Admin,CS')->group(function () {
        Route::get('/', [App\Http\Controllers\PelangganController::class, 'index'])->name('customer.index');
        Route::post('/store', [App\Http\Controllers\PelangganController::class, 'store'])->name('customer.store');
        Route::get('/data', [App\Http\Controllers\PelangganController::class, 'data'])->name('customer.data');
        Route::get('/{id}/edit', [App\Http\Controllers\PelangganController::class, 'edit'])->name('customer.edit');
        Route::put('/{id}', [App\Http\Controllers\PelangganController::class, 'update'])->name('customer.update');
        Route::delete('/{id}', [App\Http\Controllers\PelangganController::class, 'destroy'])->name('customer.destroy');
    });

    Route::prefix('/ticket')->group(function () {
        Route::get('/', [App\Http\Controllers\TicketController::class, 'index'])
            ->name('ticket.index')
            ->middleware('role:Admin,CS,NOC');

        Route::get('/data', [App\Http\Controllers\TicketController::class, 'data'])
            ->name('ticket.data')
            ->middleware('role:Admin,CS,NOC');

        Route::get('/{id}/view', [App\Http\Controllers\TicketController::class, 'view'])
            ->name('ticket.view')
            ->middleware('role:Admin,CS,NOC');

        Route::post('/store', [App\Http\Controllers\TicketController::class, 'store'])
            ->name('ticket.store')
            ->middleware('role:Admin,CS');

        Route::get('/{id}/edit', [App\Http\Controllers\TicketController::class, 'edit'])
            ->name('ticket.edit')
            ->middleware('role:Admin,NOC');

        Route::put('/{id}', [App\Http\Controllers\TicketController::class, 'update'])
            ->name('ticket.update')
            ->middleware('role:Admin,NOC');

        Route::delete('/{id}', [App\Http\Controllers\TicketController::class, 'destroy'])
            ->name('ticket.destroy')
            ->middleware('role:Admin,NOC');
    });
});
