<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [App\Http\Controllers\Api\auth\Authcontroller::class, 'login'])->name('api.login');


Route::middleware('auth:api')->group(function () {
    // Admin & CS - full customer management (CRUD)
    Route::prefix('/customer')->middleware('role:Admin,CS')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\PelangganController::class, 'index'])->name('api.customer.index');
        Route::post('/store', [App\Http\Controllers\Api\PelangganController::class, 'store'])->name('api.customer.store');
        Route::get('/data', [App\Http\Controllers\Api\PelangganController::class, 'data'])->name('api.customer.data');
        Route::get('/{id}/edit', [App\Http\Controllers\Api\PelangganController::class, 'edit'])->name('api.customer.edit');
        Route::put('/{id}', [App\Http\Controllers\Api\PelangganController::class, 'update'])->name('api.customer.update');
        Route::delete('/{id}', [App\Http\Controllers\Api\PelangganController::class, 'destroy'])->name('api.customer.destroy');
    });

    Route::prefix('/ticket')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\TicketController::class, 'index'])
            ->name('api.ticket.index')
            ->middleware('role:Admin,CS,NOC');

        Route::get('/data', [App\Http\Controllers\Api\TicketController::class, 'data'])
            ->name('api.ticket.data')
            ->middleware('role:Admin,CS,NOC');

        Route::get('/{id}/view', [App\Http\Controllers\Api\TicketController::class, 'view'])
            ->name('api.ticket.view')
            ->middleware('role:Admin,CS,NOC');

        Route::post('/store', [App\Http\Controllers\Api\TicketController::class, 'store'])
            ->name('api.ticket.store')
            ->middleware('role:Admin,CS');

        Route::get('/{id}/edit', [App\Http\Controllers\Api\TicketController::class, 'edit'])
            ->name('api.ticket.edit')
            ->middleware('role:Admin,NOC');

        Route::put('/{id}', [App\Http\Controllers\Api\TicketController::class, 'update'])
            ->name('api.ticket.update')
            ->middleware('role:Admin,NOC');

        Route::delete('/{id}', [App\Http\Controllers\Api\TicketController::class, 'destroy'])
            ->name('api.ticket.destroy')
            ->middleware('role:Admin,NOC');
    });
});
