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
    <!-- CSS Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="halut.css" rel="stylesheet">
    <style>
     body {
    background-color: #f8f9fa;
}

.navbar {
    background-color: #007bff;
}

.navbar-brand {
    padding: 0;
}

.navbar-brand img {
    max-height: 45px;
    max-width: 110px;
    width: auto;
    height: auto;
}

.navbar-brand,
.nav-link {
    color: #fff !important;
}

.nav-link:hover {
    color: #dfefff !important;
}

.btn-primary,
.btn-success {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover,
.btn-success:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.navbar-nav {
    flex: 1;
    justify-content: center;
}

.btn-primary,
.btn-success {
    margin-left: 10px;
}

.card {
    background-color: #fff;
    border: 1px solid #007bff;
    max-width: 300px; /* Atur lebar maksimal untuk kartu */
    margin: 0 auto; /* Pusatkan kartu di dalam container */
}

.card-body {
    background-color: #e9ecef;
}

.card-text {
    color: #007bff;
}

.card:hover {
    transform: scale(1.05);
    transition: transform 0.3s;
}

h1,
h2 {
    color: #007bff;
}

p,
ul,
li {
    color: #343a40;
}

#map {
    border: 2px solid #007bff;
}

.highlight {
    font-family: 'Roboto', sans-serif;
    font-weight: 700;
    color: #007bff;
}

.facilities {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.facility-item {
    width: 100%;
    margin-bottom: 20px;
}

.facility-item .card {
    width: 100%;
}

#chat-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
}

.modal-body {
    max-height: 400px;
}

.modal-dialog {
    max-width: 500px;
}

#chat-box {
    height: 300px;
    overflow-y: scroll;
    border: 1px solid #007bff;
    padding: 10px;
}

#chat-input {
    width: 100%;
    margin-top: 10px;
}

    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="#">
            <img src="logo2-removebg-preview.png" alt="Indekos Aurin's Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
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
            <div class="ml-auto">
                <a class="btn btn-primary" href="login.php">Masuk</a>
                <a class="btn btn-success" href="register.php">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Beranda Section -->
    <section id="home" class="pt-5 mt-5" data-aos="fade-up">
        <div class="container mt-4">
            <h1>Selamat Datang di Aurin's Kos</h1>
            <p>Temukan kenyamanan tinggal di kos-kosan kami yang dilengkapi dengan berbagai fasilitas modern. Kami
                berkomitmen untuk memberikan pengalaman tinggal yang terbaik bagi para penghuni. Temukan kos-kosan yang
                sesuai dengan kebutuhan Anda hanya di sini.</p>
            <p class="highlight">Mengapa Memilih Kami?</p>
            <ul>
                <li>Lokasi Strategis: Dekat dengan pusat perbelanjaan, kampus, dan transportasi umum.</li>
                <li>Fasilitas Lengkap: Dari kamar mandi dalam  hingga internet berkecepatan tinggi.</li>
                <li>Harga Terjangkau: Beragam pilihan kamar dengan harga yang murah.</li>
                <li>Keamanan Terjamin: Sistem keamanan 24 jam dengan CCTV dan petugas keamanan.</li>
            </ul>
            <p>Bergabunglah dengan komunitas kami dan rasakan kenyamanan tinggal di kos-kosan terbaik di kota ini.</p>
        </div>
    </section>

    <!-- Fasilitas Section -->
    <section id="fasilitas" class="pt-5 mt-5" data-aos="fade-up">
        <div class="container mt-4">
            <h2>Fasilitas</h2>
            <p>Kami menyediakan berbagai fasilitas unggulan untuk memastikan kenyamanan Anda selama tinggal di kos-kosan
                kami:</p>
            <div class="facilities">
                <div class="facility-item col-md-4 mb-4" data-aos="flip-left">
                    <div class="card shadow-sm">
                        <img src="kasur.jpg" class="card-img-top" alt="Kamar Tidur">
                        <div class="card-body">
                            <p class="card-text">Kamar Tidur Nyaman</p>
                            <p>KKamar ini dilengkapi dengan kasur dan ranjang berkualitas tinggi yang dirancang untuk memberikan kenyamanan maksimal. Kasur yang empuk dan mendukung akan memastikan tidur malam Anda nyenyak dan menyegarkan. 
                                Dengan ranjang yang kokoh dan nyaman, Anda dapat beristirahat dengan tenang setelah seharian beraktivitas.</p>
                        </div>
                    </div>
                </div>
                <div class="facility-item col-md-4 mb-4" data-aos="flip-left">
                    <div class="card shadow-sm">
                        <img src="wc.jpg" class="card-img-top" alt="wc">
                        <div class="card-body">
                            <p class="card-text">Kamar mandi</p>
                            <p>Kamar ini dilengkapi dengan kamar mandi dalam yang modern dan nyaman.
                                Dengan adanya kamar mandi dalam, Anda dapat menikmati privasi dan kenyamanan ekstra selama masa tinggal Anda..  
                            </p>
                        </div>
                    </div>
                </div>
                <div class="facility-item col-md-4 mb-4" data-aos="flip-left">
                    <div class="card shadow-sm">
                        <img src="lemari.jpg" class="card-img-top" alt="Wi-Fi">
                        <div class="card-body">
                            <p class="card-text">lemari </p>
                            <p>Kamar ini dilengkapi dengan fasilitas lemari yang luas dan modern, 
                                menyediakan ruang penyimpanan yang cukup untuk pakaian dan barang-barang pribadi Anda. 
                                Lemari ini dirancang dengan baik untuk menjaga barang-barang Anda tetap rapi dan terorganisir,
                                 memberikan kenyamanan ekstra selama masa tinggal Anda. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section id="kontak" class="pt-5 mt-5" data-aos="fade-up">
        <div class="container mt-4">
            <h2>Kontak Kami</h2>
            <p>Untuk informasi lebih lanjut atau pertanyaan, jangan ragu untuk menghubungi kami melalui:</p>
            <ul>
                <li>Email: info@aurinskos.com</li>
                <li>Telepon: +62 123 456 789</li>
                <li>Alamat: Jl.Baung lll No 15b Rt 004 rt 005 Pasarminggu, kebagusan Jakarta selatan </li>
            </ul>
        </div>
    </section>
    <!-- Peta Section -->
<section id="maps" class="pt-5 mt-5" data-aos="fade-up">
    <div class="container mt-4">
        <h2>Peta Lokasi</h2>
        <div id="map" style="height: 400px; width: 100%;">
            <!-- Embed Google Maps -->
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15819.07720445284!2d106.8334777!3d-6.304816!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69edf3e795125b%3A0xc089c57321ae049e!2sRPTRA%20Baung!5e0!3m2!1sid!2sid!4v1676403134302!5m2!1sid!2sid"
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

    

    <!-- Chat Button -->
    <button id="chat-button" class="btn btn-primary" data-toggle="modal" data-target="#chatModal">
        Chat
    </button>

    <!-- Chat Modal -->
    <div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="chatModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel">Live Chat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="chat-box">
                        <?php include 'chat_content.php'; // Load initial messages ?>
                    </div>
                    <textarea id="chat-input" rows="3" placeholder="Tulis pesan..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" id="send-button" class="btn btn-primary">Kirim</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();

        // Function to refresh chat messages
        function refreshChat() {
            var chatBox = document.getElementById('chat-box');
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'chat_content.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    chatBox.innerHTML = xhr.responseText;
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            };
            xhr.send();
        }

        // Function to send a message
        function sendMessage() {
            var messageInput = document.getElementById('chat-input');
            var message = messageInput.value.trim();

            if (message) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'user_chat.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        messageInput.value = '';
                        refreshChat(); // Refresh chat after sending a message
                    }
                };
                xhr.send('message=' + encodeURIComponent(message) + '&submit=1');
            }
        }

        // Bind send button click event
        document.getElementById('send-button').addEventListener('click', sendMessage);

        // Bind Enter key event to send message
        document.getElementById('chat-input').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });

        // Refresh chat every 3 seconds
        setInterval(refreshChat, 3000);

        
    </script>
    <script src="https://maps.app.goo.gl/BKiLWPBnQagksUUs7" async defer></script>
</body>

</html>