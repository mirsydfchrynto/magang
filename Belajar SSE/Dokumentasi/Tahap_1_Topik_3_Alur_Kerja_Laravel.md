# Tahap 1, Topik 3: Alur SSE di Laravel (Gambaran Besar)

## 1. Dua Aktor Utama
Dalam sistem SSE, kita memiliki dua aktor utama yang saling bekerja sama, ibarat **Stasiun Radio** dan **Radio Mobil**.
1. **Frontend (Browser / JavaScript):** Bertugas sebagai pendengar (radio).
2. **Backend (Laravel):** Bertugas sebagai penyiar (stasiun radio).

## 2. Istilah Penting
* **EventSource (Sisi Frontend/JavaScript)**
  Ini adalah API bawaan browser. Fungsinya ibarat antena radio. Tugasnya adalah membuka koneksi ke URL tertentu, mendengarkan "siaran" dari server, dan secara otomatis akan mencoba menyambung ulang (reconnect) jika sinyal atau internet sempat terputus.

* **StreamedResponse (Sisi Backend/Laravel)**
  Ini adalah pemancar sinyal (tower radio) milik Laravel. Normalnya, Laravel akan langsung mematikan koneksi begitu selesai mengirim data (Response biasa). Namun, dengan `StreamedResponse`, kita memaksa Laravel untuk **menahan koneksi agar tetap terbuka** dan mengalirkan data (stream) sedikit demi sedikit.

## 3. Visualisasi Alur Kerja Laravel SSE

```text
       (JavaScript)                                      (Laravel)
     Browser / Frontend                               Controller Backend
             |                                                |
1. Buat "EventSource" --------------------------------> 2. Terima Request
   (Nyalakan Radio)       (Tuning ke URL /notifikasi)         |
             |                                                |
             | <--- 3. Buka StreamedResponse (Koneksi Ditahan) ---
             |                                                |
             | <--- 4. Data: {pesan: "Ada order baru"} ------- (Pesan masuk ke sistem)
             |                                                |
5. Tampilkan di layar <---------------------------------------|
             | <--- 6. Data: {pesan: "Pembayaran sukses"} ---- (Pesan masuk ke sistem)
             |                                                |
```

## Kesimpulan Topik Ini
Inti dari pembuatan notifikasi realtime menggunakan SSE di Laravel hanyalah: JavaScript membuka saluran menggunakan `EventSource`, dan Laravel menangkapnya lalu membalasnya dengan menggunakan `StreamedResponse`.
