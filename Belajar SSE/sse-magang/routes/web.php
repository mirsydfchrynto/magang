<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use Symfony\Component\HttpFoundation\StreamedResponse;

Route::get('/tes-notif', function () {
    return view('sse-test');
});

Route::get('/sse-stream', function () {
    return new StreamedResponse(function () {
        while (true) {
            $waktu = date('H:i:s');
            echo "data: Notifikasi Server waktu: $waktu \n\n";
            
            if (ob_get_level() > 0) ob_flush();
            flush();
            
            sleep(2);
        }
    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
    ]);
});
