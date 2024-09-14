<?php

use Illuminate\Support\Facades\Route;

Route::get('/',[\App\Http\Controllers\TestController::class, 'test']);
Route::post('/',[\App\Http\Controllers\TestController::class, 'testPost'])->excludedMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
