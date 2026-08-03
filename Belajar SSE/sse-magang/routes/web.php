<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

// Arsitektur yang rapi: Route hanya untuk mendaftarkan URL, 
// logika programnya dipindah ke Controller.
Route::get('/tes-notif', [NotificationController::class, 'index']);
Route::get('/sse-stream', [NotificationController::class, 'stream']);
