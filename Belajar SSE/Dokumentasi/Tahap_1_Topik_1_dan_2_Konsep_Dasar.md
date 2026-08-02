# Tahap 1, Topik 1 & 2: Konsep Dasar Web Biasa vs Server-Sent Events (SSE)

## 1. Masalah yang Kita Hadapi (Web Biasa)
Normalnya, web bekerja menggunakan sistem **Request / Response**.
* **Cara kerja:** Browser meminta data (Request), server membalas (Response), lalu koneksi langsung diputus.
* **Kekurangan:** Jika ada "Notifikasi Baru", server tidak bisa memberi tahu browser secara otomatis. User harus me-refresh halaman atau sistem harus melakukan *Polling* (bertanya terus-menerus ke server). Ini sangat membebani kinerja server.

**Visualisasi Web Biasa:**
```text
Browser                                  Server
   | ---- 1. Minta Data (Request) -->      |
   | <--- 2. Kasih Data (Response) --      |
   x (Koneksi Terputus / Selesai)          x
```

## 2. Solusinya: Server-Sent Events (SSE)
SSE adalah cara agar server tidak langsung memutuskan koneksi setelah merespons.
* **Cara kerja:** Sistem berlangganan (Subscribe). Browser me-request sekali, lalu koneksi dibiarkan terbuka. Server akan secara otomatis mendorong (push) data notifikasi ke browser kapan pun data itu tersedia, tanpa perlu refresh.
* **Kelebihan:** Sangat ringan dan efisien karena komunikasi hanya searah (dari Server ke Client). Tidak seberat WebSockets.

**Visualisasi SSE:**
```text
Browser                                  Server
   | ---- 1. Langganan SSE (Req) --->      |
   | <--- 2. Terbuka (Koneksi ON) ---      |
   | <--- Data 1: "Notifikasi A" ----      |
   | <--- Data 2: "Notifikasi B" ----      |
   | (Berlanjut terus menerus...)          |
```

## 3. Emergency Mentor Answer Sheet (Contekan 1 Menit)
* **Apa itu SSE?** Teknologi yang memungkinkan server mengirim data terus-menerus ke browser melalui satu koneksi HTTP yang dibiarkan terbuka.
* **Kenapa kita pakai?** Agar notifikasi realtime langsung muncul di layar user tanpa browser harus terus-menerus me-refresh atau bertanya (request) ke server.
* **Kelebihan vs WebSocket:** Lebih ringan dan mudah di-setup karena ini hanya komunikasi searah (server ke client) di atas protokol HTTP biasa.
