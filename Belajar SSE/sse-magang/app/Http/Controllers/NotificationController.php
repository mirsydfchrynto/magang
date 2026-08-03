<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NotificationController extends Controller
{
    /**
     * Menampilkan Halaman Frontend (HTML/View)
     */
    public function index()
    {
        return view('sse-test');
    }

    /**
     * Memancarkan data SSE ke Frontend (API)
     */
    public function stream()
    {
        return new StreamedResponse(function () {
            // Loop abadi ini menahan koneksi agar tidak pernah terputus (ditutup) oleh PHP.
            while (true) {
                
                // -------------------------------------------------------------
                // DI DUNIA NYATA:
                // Di sinilah kamu biasanya mengambil data dari Database (misal tabel `notifications`)
                // $notifBaru = Notification::where('is_read', false)->get();
                // -------------------------------------------------------------

                // Karena ini latihan, kita simulasikan mengirim data buatan
                $data = [
                    'waktu' => date('H:i:s'),
                    'pesan' => 'Halo, ini notifikasi dari Controller!'
                ];

                // Kirim data dalam format JSON agar di JavaScript lebih gampang diolah
                echo 'data: ' . json_encode($data) . "\n\n";

                // Membuang data ke browser saat ini juga (mencegah ditimbun di memori)
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

                // -------------------------------------------------------------
                // JAWABAN UNTUK PERTANYAANMU TENTANG "SLEEP(2)":
                // -------------------------------------------------------------
                // Kenapa ada sleep 2 detik?
                // Karena kalau tidak di-sleep, perulangan `while (true)` ini akan berjalan
                // jutaan kali dalam 1 detik. Itu akan membuat CPU Server jebol (100% Usage).
                // Dengan sleep(2), kita menyuruh server bernapas selama 2 detik sebelum
                // mengecek database lagi. Ini disebut teknik "Polling di dalam Server".
                sleep(2); 
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }
}
