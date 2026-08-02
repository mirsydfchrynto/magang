# Tahap 2, Topik 1: StreamedResponse di Laravel (Sisi Backend)

## 1. Tujuan
Kita ingin membuat sebuah "Endpoint" (URL) di Laravel, misalnya `/sse-notifications`. Jika Frontend mengakses URL ini, Laravel tidak akan langsung membalas dan menutup koneksi, melainkan menahan koneksi dan membalas dengan format khusus (Event Stream).

## 2. Kode Minimum (The "Hello World" of SSE)
Berikut adalah bentuk paling sederhana dan murni dari SSE di Laravel menggunakan `StreamedResponse`:

```php
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Route;

Route::get('/sse-notifications', function () {
    return new StreamedResponse(function () {
        
        // 1. Looping tak terbatas untuk menjaga koneksi tetap hidup
        while (true) {
            
            // 2. Format Data Spesifik SSE (Harus persis seperti ini)
            echo "data: Ada notifikasi baru!\n\n";
            
            // 3. Paksa PHP mengirim data SEKARANG juga (Flush)
            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();
            
            // 4. Tidur (Jeda) 2 detik sebelum mengirim pesan lagi
            sleep(2);
        }
        
    }, 200, [
        // 5. Header Wajib SSE
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
    ]);
});
```

## 3. Penjelasan Baris per Baris (Wajib Paham!)
* `'Content-Type' => 'text/event-stream'`: Ini adalah **Kunci Utama**. Header ini memberi tahu browser: *"Hei browser, ini bukan halaman web biasa, ini aliran data terus-menerus. Tolong jangan ditutup koneksinya!"*
* `'Connection' => 'keep-alive'`: Memaksa jaringan server agar tidak memutuskan hubungan.
* `while (true)`: Karena kita ingin server *standby* terus menerus mengirim/menunggu data, kita butuh perulangan tanpa batas. (Catatan: Di aplikasi perusahaan beneran, bagian ini akan dihubungkan dengan antrean seperti Redis, tapi cara kerjanya persis sama).
* `echo "data: ... \n\n"`: SSE memiliki aturan penulisan baku. Pesan **WAJIB** diawali teks `data: ` dan diakhiri dengan dua kali garis baru `\n\n`. Kalau kamu lupa `\n\n`, browser tidak akan bisa membacanya.
* `ob_flush(); flush();`: PHP normalnya punya kebiasaan "menimbun" data (buffering) sampai penuh sebelum dikirim ke pengguna. Perintah *flush* ini menginstruksikan PHP: *"Jangan ditimbun! Berapapun data yang ada, langsung buang dan kirim ke browser sekarang juga!"*

---

## 🚨 Emergency Mentor Answer Sheet (Contekan)
* **Tanya:** *Kenapa kamu harus pakai ob_flush() dan flush() di kodenya?*
* **Jawab:** *Karena secara bawaan PHP akan mem-buffer (menimbun) ouput sampai kodenya selesai berjalan seluruhnya. Fungsi flush memaksa PHP untuk mengirim potongan teks (notifikasi) saat itu juga ke browser tanpa harus menunggu proses (loop) selesai.*
