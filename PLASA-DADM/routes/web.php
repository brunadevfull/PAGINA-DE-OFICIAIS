<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Upload files served outside DocumentRoot — must go through PHP.
// Registered here (no /api prefix) to match stored URLs (/uploads/{type}/{file}).
Route::get('/uploads/{type}/{file}', [UploadController::class, 'serve'])
    ->where(['type' => '[a-z-]+', 'file' => '.+']);

// React SPA catch-all: serve index.html for all non-API client-side routes
Route::get('/{any}', fn() => response()->file(public_path('index.html')))
    ->where('any', '.*');
