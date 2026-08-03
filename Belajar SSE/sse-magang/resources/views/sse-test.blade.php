<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi Realtime</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; background: #f0f2f5; }
        h2 { color: #333; }
        .kotak-notif { 
            background: white; 
            border-left: 5px solid #007bff; 
            padding: 15px; 
            margin-bottom: 10px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-radius: 4px;
        }
        .waktu { color: gray; font-size: 0.8em; margin-top: 5px; display: block;}
    </style>
</head>
<body>
    <h2>Kotak Masuk Notifikasi (Arsitektur Rapi)</h2>
    <p style="color: gray;">Menunggu notifikasi dari server (tiap 2 detik)...</p>

    <!-- Di sinilah daftar notifikasi akan dimunculkan -->
    <div id="daftar-notif"></div>

    <script>
        // 1. Membuka koneksi ke Controller Laravel
        const source = new EventSource('/sse-stream');
        const wadahNotif = document.getElementById('daftar-notif');

        // 2. Mendengarkan data
        source.onmessage = function(event) {
            // Karena dari Controller kita kirim JSON, kita parse dulu di sini
            const dataServer = JSON.parse(event.data);

            // Bikin elemen HTML baru untuk menaruh notifikasinya
            const kotakBaru = document.createElement('div');
            kotakBaru.className = 'kotak-notif';
            
            // Masukkan pesan dan waktunya
            kotakBaru.innerHTML = `
                <strong>Ada Pesan:</strong> ${dataServer.pesan}
                <span class="waktu">${dataServer.waktu}</span>
            `;

            // Tampilkan kotak baru di bagian paling atas (prepend)
            wadahNotif.prepend(kotakBaru);
        };

        source.onerror = function() {
            console.error("Sinyal terputus, mencoba menyambung ulang...");
        };
    </script>
</body>
</html>
