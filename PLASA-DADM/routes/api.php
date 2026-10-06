<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DutyOfficerController;
use App\Http\Controllers\MilitaryPersonnelController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------------
// Public routes (no auth required)
// ---------------------------------------------------------------------------

Route::get('/health', [SystemController::class, 'health']);
Route::get('/temperature', [SystemController::class, 'temperature']);
Route::get('/system-info', [SystemController::class, 'systemInfo']);

// Documents — read
Route::get('/documents', [DocumentController::class, 'index']);
Route::get('/documents/stream', [StreamController::class, 'documents']);
Route::get('/documents/view-state', [DocumentController::class, 'getViewState']);

// Notices — read
Route::get('/notices', [NoticeController::class, 'index']);

// Duty Officers — read
Route::get('/duty-officers', [DutyOfficerController::class, 'index']);
Route::get('/duty-officers/stream', [StreamController::class, 'dutyOfficers']);

// Military — read
Route::get('/military-personnel', [MilitaryPersonnelController::class, 'index']);

// Auth (public)
Route::post('/admin/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::get('/admin/session', [AuthController::class, 'session']);
Route::get('/admin/check', [AuthController::class, 'check']);

// Public status + cache checks (used by display TVs, no auth)
Route::get('/status', [SystemController::class, 'publicStatus']);
Route::post('/check-plasa-pages', [UploadController::class, 'checkPlasaPages']);
Route::get('/check-escala-cache/{escalId}', [UploadController::class, 'checkEscalaCache'])
    ->where('escalId', '[a-zA-Z0-9_-]+');
Route::post('/save-escala-cache', [UploadController::class, 'saveEscalaCache']);
Route::get('/proxy-pdf', [UploadController::class, 'proxyPdf']);
Route::post('/cache-plasa-page', [UploadController::class, 'cachePlasaPage'])
    ->middleware('throttle:30,1');

// ---------------------------------------------------------------------------
// Protected routes (auth.session middleware)
// ---------------------------------------------------------------------------

Route::middleware('auth.session')->group(function () {
    // Auth
    Route::post('/admin/logout', [AuthController::class, 'logout']);
    Route::get('/admin/users', [AuthController::class, 'listUsers']);
    Route::post('/admin/users', [AuthController::class, 'createUser']);
    Route::put('/admin/users/{id}', [AuthController::class, 'updateUser']);

    // Documents — write
    Route::post('/documents', [DocumentController::class, 'store']);
    Route::put('/documents/{id}', [DocumentController::class, 'update']);
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);
    Route::post('/documents/view-state', [DocumentController::class, 'saveViewState']);

    // Notices — write
    Route::post('/notices', [NoticeController::class, 'store']);
    Route::put('/notices/{id}', [NoticeController::class, 'update']);
    Route::delete('/notices/{id}', [NoticeController::class, 'destroy']);

    // Military — write
    Route::post('/military-personnel', [MilitaryPersonnelController::class, 'store']);
    Route::put('/military-personnel/{id}', [MilitaryPersonnelController::class, 'update']);
    Route::delete('/military-personnel/{id}', [MilitaryPersonnelController::class, 'destroy']);

    // Duty Officers — write
    Route::put('/duty-officers', [DutyOfficerController::class, 'update']);

    // Upload
    Route::post('/upload-pdf', [UploadController::class, 'uploadPdf']);
    Route::post('/upload-plasa-page', [UploadController::class, 'uploadPlasaPage']);
    Route::delete('/upload/{type}/{file}', [UploadController::class, 'delete'])
        ->where(['type' => '[a-z-]+', 'file' => '.+']);
    Route::get('/upload/list/{type}', [UploadController::class, 'list']);
    Route::get('/upload/config', [UploadController::class, 'config']);
    Route::post('/upload/cleanup', [UploadController::class, 'cleanup']);
    Route::put('/upload/{type}/{file}/meta', [UploadController::class, 'updateMeta'])
        ->where(['type' => '[a-z-]+', 'file' => '.+']);

    // Legacy delete/list routes used by the React frontend
    Route::delete('/delete-pdf/{filepath}', [UploadController::class, 'deletePdf'])
        ->where('filepath', '.+');
    Route::get('/list-pdfs', [UploadController::class, 'listPdfs']);
    Route::post('/clear-cache', [UploadController::class, 'clearImageCache']);

    // System — protected
    Route::get('/system/status', [SystemController::class, 'status']);
    Route::post('/system/cache/clear', [SystemController::class, 'clearCache']);
    Route::get('/system/logs', [SystemController::class, 'logs']);
});
