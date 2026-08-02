<!DOCTYPE html>
<html>
<head>
    <title>Test Realtime Notifikasi (SSE)</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 50px;">
    <h2>Uji Coba Notifikasi Realtime (Tahap 2)</h2>
    
    <div style="padding: 20px; border: 2px dashed #007bff; background: #f8f9fa;">
        <strong>Pesan Masuk Terakhir:</strong>
        <p id="kotak-notif" style="color: green; font-size: 20px; margin-top: 10px;">Menunggu notifikasi...</p>
    </div>

    <script>
        // 1. Menghubungkan antena
        const source = new EventSource('/sse-stream');

        // 2. Mendengarkan siaran
        source.onmessage = function(event) {
            document.getElementById('kotak-notif').innerText = event.data;
            console.log("Notifikasi ditarik:", event.data);
        };

        // 3. Tangani error / putus koneksi
        source.onerror = function() {
            document.getElementById('kotak-notif').innerText = "Sinyal terputus! Mencoba nyambung ulang...";
            document.getElementById('kotak-notif').style.color = "red";
        };
    </script>
</body>
</html>
