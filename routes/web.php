<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['web', 'splade', 'verified'])->name('admin.')->group(function () {
    Route::get('admin/logs', [\TomatoPHP\TomatoLogs\Http\Controllers\LogsController::class, 'index'])->name('logs.index');
    Route::get('admin/logs/file/{record}', [\TomatoPHP\TomatoLogs\Http\Controllers\LogsController::class, 'file'])->name('logs.file');
    Route::get('admin/logs/{record}', [\TomatoPHP\TomatoLogs\Http\Controllers\LogsController::class, 'show'])->name('logs.show');
    Route::delete('admin/logs/{record}', [\TomatoPHP\TomatoLogs\Http\Controllers\LogsController::class, 'destroy'])->name('logs.destroy');
});
