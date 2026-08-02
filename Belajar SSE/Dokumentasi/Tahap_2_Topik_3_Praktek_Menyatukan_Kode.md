# Tahap 2, Topik 3: Praktek Menyatukan Kode (Frontend + Backend)

## 1. Tujuan
Kita akan melihat bagaimana kode `StreamedResponse` (Backend) dan `EventSource` (Frontend) bekerja sama di dalam satu project Laravel secara nyata.

## 2. Langkah Implementasi
Di dalam project Laravel, kita hanya perlu menyentuh dua file:

### A. File `routes/web.php` (Sisi Backend)
Kita tambahkan dua rute (URL) baru. 
1. Route untuk menampilkan halaman web (Tampilan UI).
2. Route khusus untuk memancarkan notifikasi SSE (Stream).

```php
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Route;

// 1. Route untuk menampilkan halaman HTML/JavaScript
Route::get('/tes-notif', function () {
    return view('sse-test'); // Akan memanggil file resources/views/sse-test.blade.php
});

// 2. Route khusus pemancar SSE (Yang sudah kita pelajari)
Route::get('/sse-stream', function () {
    return new StreamedResponse(function () {
        while (true) {
            $waktu = date('H:i:s');
            echo "data: Notifikasi Server waktu: $waktu \n\n";
            
            if (ob_get_level() > 0) ob_flush();
            flush();
            
            sleep(2); // Kirim update setiap 2 detik
        }
    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
    ]);
});
```

### B. File `resources/views/sse-test.blade.php` (Sisi Frontend)
Ini adalah halaman web sederhana tempat user melihat notifikasi.

```html
<!DOCTYPE html>
<html>
<head>
    <title>Test Realtime Notifikasi (SSE)</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 50px;">
    <h2>Uji Coba Notifikasi Realtime</h2>
    
    <div style="padding: 20px; border: 2px dashed #007bff; background: #f8f9fa;">
        <strong>Pesan Masuk Terakhir:</strong>
        <!-- Di sinilah teks dari server akan muncul -->
        <p id="kotak-notif" style="color: green; font-size: 20px;">Menunggu notifikasi...</p>
    </div>

    <script>
        // 1. Menghubungkan antena EventSource ke URL pemancar Laravel
        const source = new EventSource('/sse-stream');

        // 2. Mendengarkan siaran
        source.onmessage = function(event) {
            // 3. Mengubah teks di HTML sesuai data dari server
            document.getElementById('kotak-notif').innerText = event.data;
            console.log("Notifikasi ditarik:", event.data);
        };

        source.onerror = function() {
            document.getElementById('kotak-notif').innerText = "Sinyal terputus! Mencoba nyambung ulang...";
            document.getElementById('kotak-notif').style.color = "red";
        };
    </script>
</body>
</html>
```

## 3. Kesimpulan Alur
1. User membuka halaman `/tes-notif` di browser.
2. File HTML dimuat, lalu JavaScript mengeksekusi `new EventSource('/sse-stream')`.
3. Browser secara otomatis melakukan request di belakang layar ke `/sse-stream`.
4. Laravel menerima request tersebut, dan mengirim waktu terbaru setiap 2 detik.
5. JavaScript `onmessage` langsung menangkap waktu tersebut dan mengganti tulisan di layar tanpa refresh!
