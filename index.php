<?php
include 'dbconnect.php'; // Pastikan file ini berada di direktori yang benar

// Menyimpan pesan dari pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $username = 'User'; // Nama pengguna default
    $message = mysqli_real_escape_string($DBConnect, $_POST['message']);
    $is_admin = 0; // Pesan dari pengguna

    $sql = "INSERT INTO chat_messages (username, message, is_admin) VALUES ('$username', '$message', $is_admin)";
    if (mysqli_query($DBConnect, $sql)) {
        // Pesan berhasil dikirim
    } else {
        echo "Error: " . mysqli_error($DBConnect);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kos-Kosan</title>
    <!-- CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .navbar {
            background-color: #0d6efd;
        }

        .navbar-brand img {
            max-height: 50px;
        }

        .navbar-nav .nav-link {
            color: #fff;
            margin: 0 10px;
        }

        .navbar-nav .nav-link:hover {
            color: #d4eaff;
        }

        .btn-primary,
        .btn-success {
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-success:hover {
            background-color: #117a38;
        }

        .section-title {
            font-weight: 700;
            color: #0d6efd;
            text-align: center;
            margin-bottom: 30px;
        }

        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-body {
            background-color: #fff;
        }

        .highlight {
            color: #0d6efd;
            font-weight: 700;
        }

        #chat-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        #chat-box {
            height: 300px;
            overflow-y: auto;
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .modal-dialog {
            max-width: 500px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <img src="logo2-removebg-preview.png" alt="Aurin's Kos Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fasilitas">Fasilitas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#maps">Peta</a>
                    </li>
                </ul>
                <div>
                    <a class="btn btn-primary me-2" href="login.php">Masuk</a>
                    <a class="btn btn-success" href="register.php">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Beranda Section -->
    <section id="home" class="pt-5 mt-5">
        <div class="container py-5">
            <h1 class="section-title">Selamat Datang di Aurin's Kos</h1>
            <p>Temukan kenyamanan tinggal di kos-kosan kami yang dilengkapi dengan berbagai fasilitas modern. Kami
                berkomitmen untuk memberikan pengalaman tinggal terbaik bagi para penghuni. Temukan kos-kosan yang
                sesuai dengan kebutuhan Anda hanya di sini.</p>
            <p class="highlight">Mengapa Memilih Kami?</p>
            <ul>
                <li>Lokasi Strategis: Dekat dengan pusat perbelanjaan, kampus, dan transportasi umum.</li>
                <li>Fasilitas Lengkap: Dari kamar mandi dalam hingga internet berkecepatan tinggi.</li>
                <li>Harga Terjangkau: Beragam pilihan kamar dengan harga yang murah.</li>
                <li>Keamanan Terjamin: Sistem keamanan 24 jam dengan CCTV dan petugas keamanan.</li>
            </ul>
        </div>
    </section>

    <!-- Fasilitas Section -->
    <section id="fasilitas" class="py-5">
        <div class="container">
            <h2 class="section-title">Fasilitas</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card">
                        <img src="kasur.jpg" class="card-img-top" alt="Kamar Tidur">
                        <div class="card-body">
                            <h5 class="card-title">Kamar Tidur Nyaman</h5>
                            <p class="card-text">Kasur empuk dan ranjang kokoh memberikan kenyamanan tidur maksimal.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="wc.jpg" class="card-img-top" alt="Kamar Mandi">
                        <div class="card-body">
                            <h5 class="card-title">Kamar Mandi Dalam</h5>
                            <p class="card-text">Kamar mandi modern yang memberikan privasi dan kenyamanan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="lemari.jpg" class="card-img-top" alt="Lemari">
                        <div class="card-body">
                            <h5 class="card-title">Lemari Luas</h5>
                            <p class="card-text">Ruang penyimpanan cukup untuk pakaian dan barang pribadi Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section id="kontak" class="py-5">
        <div class="container">
            <h2 class="section-title">Kontak Kami</h2>
            <p>Untuk informasi lebih lanjut atau pertanyaan, jangan ragu untuk menghubungi kami:</p>
            <ul>
                <li>Email: info@aurinskos.com</li>
                <li>Telepon: +62 123 456 789</li>
                <li>Alamat: Jl. Baung III No. 15B RT 004/RW 005 Pasarminggu, Kebagusan, Jakarta Selatan</li>
            </ul>
        </div>
    </section>

    <!-- Peta Section -->
    <section id="maps" class="py-5">
        <div class="container">
            <h2 class="section-title">Peta Lokasi</h2>
            <div id="map" style="height: 400px;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15819.077058196774!2d106.8596889!3d-6.2915393!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f37614c04d4f%3A0xb158be6fbd63d8fc!2sKost%20Aurin!5e0!3m2!1sid!2sid!4v1633047084234!5m2!1sid!2sid"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

    <!-- Chat -->
    <button id="chat-button" class="btn btn-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#chatModal">
        <i class="bi bi-chat-dots"></i>
    </button>

    <!-- Modal Chat -->
    <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel">Chat dengan Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="chat-box">
                        <!-- Pesan akan ditampilkan di sini -->
                    </div>
                    <form action="" method="POST">
                        <textarea name="message" class="form-control mt-2" rows="3" placeholder="Tulis pesan Anda"></textarea>
                        <button type="submit" name="submit" class="btn btn-primary mt-3">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk menampilkan pesan chat baru
        function loadMessages() {
            // Ambil pesan-pesan dari database
            fetch('get_messages.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('chat-box').innerHTML = data;
                    document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
                });
        }

        // Muat pesan setiap 3 detik
        setInterval(loadMessages, 3000);
    </script>
</body>

</html>

