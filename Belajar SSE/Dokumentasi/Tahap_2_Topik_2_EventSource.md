# Tahap 2, Topik 2: EventSource di JavaScript (Sisi Frontend)

## 1. Tujuan
Setelah Laravel (Backend) siap memancarkan sinyal notifikasi melalui `StreamedResponse`, sekarang kita butuh "alat penangkap sinyal" di sisi Frontend (Browser) agar user bisa melihat notifikasinya. Alat penangkap ini bernama `EventSource`.

## 2. Kode Minimum (The "Hello World" of EventSource)
Berikut adalah kode JavaScript paling sederhana untuk mendengarkan notifikasi SSE:

```javascript
// 1. Buat koneksi baru (Menyalakan Radio)
// URL ini harus sama dengan route yang kita buat di Laravel
const eventSource = new EventSource('/sse-notifications');

// 2. Dengarkan pesan masuk (Mendengarkan Siaran)
eventSource.onmessage = function(event) {
    // 3. Ambil datanya dan tampilkan
    console.log("Notifikasi Baru Masuk: ", event.data);
    
    // Contoh jika ingin ditampilkan ke layar HTML:
    // document.getElementById('notif-box').innerText = event.data;
};

// 4. Tangani error jika koneksi terputus
eventSource.onerror = function(error) {
    console.error("Sinyal terputus! EventSource akan otomatis mencoba menyambung ulang...");
};
```

## 3. Penjelasan Baris per Baris (Wajib Paham!)
* `new EventSource('/url')`: Ini adalah fungsi bawaan asli dari browser (Chrome, Firefox, Safari). Tugas utamanya adalah membuka koneksi HTTP ke URL server dan **membiarkannya tetap terbuka**.
* `onmessage`: Ini adalah "telinga"-nya. Setiap kali Laravel mengirim teks berformat `data: ... \n\n`, fungsi `onmessage` ini akan otomatis aktif dan menangkap teks tersebut.
* `event.data`: Di sinilah isi pesan dari Laravel disimpan. Jika Laravel mengirim `data: Pesanan Lunas!\n\n`, maka isi `event.data` adalah string `"Pesanan Lunas!"`.
* `onerror`: **Ini fitur paling sakti dari EventSource.** Jika tiba-tiba internet user putus atau server *restart*, kamu tidak perlu pusing membuat kode rumit untuk *reconnect*. `EventSource` akan secara otomatis mencoba menyambung ulang ke server tanpa disuruh!

---

## 🚨 Emergency Mentor Answer Sheet (Contekan)
* **Tanya:** *Kenapa kamu milih pakai EventSource di JavaScript? Kenapa gak pakai Fetch API atau Axios biasa aja buat request ke URL itu?*
* **Jawab:** *Karena EventSource punya fitur bawaan Auto-Reconnect. Kalau server mati sebentar atau internet putus, dia bakal otomatis nyambung lagi. Selain itu, EventSource otomatis mem-parsing format 'data: ...' dari server, jadi kodenya jauh lebih bersih dibandingkan kalau kita pakai Fetch API yang harus di-parsing manual.*
