<?php
include 'dbconnect.php'; // Pastikan file ini berada di direktori yang benar

// Menyimpan balasan dari admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $username = 'Admin';
    $message = mysqli_real_escape_string($DBConnect, $_POST['message']);
    $is_admin = 1; // Pesan dari admin

    $sql = "INSERT INTO chat_messages (username, message, is_admin) VALUES ('$username', '$message', $is_admin)";
    if (mysqli_query($DBConnect, $sql)) {
        // Pesan berhasil dikirim
    } else {
        echo "Error: " . mysqli_error($DBConnect);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indekos Aurin's</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f0f5f9;
            color: #333333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            position: relative; /* To position the chat widget relative to the body */
        }

        .navbar {
            background-color: #007bff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: #ffffff;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: flex;
            justify-content: center;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 20px;
            max-width: 100%;
        }

        .card {
            min-width: 300px;
            max-width: 300px;
            height: 300px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #007bff;
        }

        .card-text {
            color: #666666;
        }

        #chat-box {
            width: 100%;
            height: 300px;
            border: 1px solid #ccc;
            overflow-y: scroll;
            padding: 10px;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }

        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .user-message {
            background-color: #e0f7fa;
            text-align: left;
            border-left: 5px solid #00bcd4;
        }

        .admin-message {
            background-color: #ffe0b2;
            text-align: right;
            border-right: 5px solid #ff9800;
        }

        form {
            margin-top: 10px;
        }

        textarea {
            width: calc(100% - 22px);
            padding: 10px;
            box-sizing: border-box;
        }

        /* Chat Widget Styles */
        #chat-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #007bff;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        #chat-widget:hover {
            background-color: #0056b3;
        }

        #chat-window {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            max-height: 400px;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: none; /* Hidden by default */
            flex-direction: column;
            z-index: 1000;
        }

        #chat-header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #chat-header h5 {
            margin: 0;
        }

        #chat-close {
            background: none;
            border: none;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
        }

        #chat-close:hover {
            color: #ddd;
        }

        #chat-box {
            padding: 10px;
            flex: 1;
            overflow-y: auto;
        }

        form {
            display: flex;
            flex-direction: column;
            margin: 10px;
        }

        textarea {
            width: calc(100% - 20px);
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="#">
            <img src="logo2-removebg-preview.png" alt="Logo"> <!-- Ganti path_to_your_logo.jpg dengan path gambar logo Anda -->
            Indekos Aurin's
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Selamat Datang, Admin
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="index.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content Section -->
    <div class="container mt-5">
        <div class="card">
            <a href="kamar.php" class="card-link">
                <div class="card-body">
                    <h5 class="card-title">Data Kamar</h5>
                    <p class="card-text">Jelajahi informasi lengkap mengenai kamar-kamar yang tersedia dan detailnya di
                        sini.</p>
                </div>
            </a>
        </div>

        <div class="card">
            <a href="tampildata.php" class="card-link">
                <div class="card-body">
                    <h5 class="card-title">Data Penghuni</h5>
                    <p class="card-text">Lihat data penghuni yang sedang menginap di Indekos Aurin's dan detail
                        informasinya.</p>
                </div>
            </a>
        </div>

        <div class="card">
            <a href="verifikasiA.php" class="card-link">
                <div class="card-body">
                    <h5 class="card-title">Pembayaran</h5>
                    <p class="card-text">Verifikasi dan kelola pembayaran penghuni Indekos Aurin's dengan mudah dan
                        aman.</p>
                </div>
            </a>
        </div>

        <!-- Add more cards as needed -->

    </div>

    <!-- Chat Widget Button -->
    <div id="chat-widget">
        <span>Chat</span>
    </div>

    <!-- Chat Window -->
    <div id="chat-window">
        <div id="chat-header">
            <h5>Live Chat</h5>
            <button id="chat-close">&times;</button>
        </div>
        <div id="chat-box">
            <?php
            // Ambil semua pesan dari database
            $sql = "SELECT * FROM chat_messages ORDER BY timestamp ASC"; // ASC untuk urutan kronologis
            $result = mysqli_query($DBConnect, $sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='message ";
                    echo $row['is_admin'] ? "admin-message" : "user-message";
                    echo "'>";
                    echo "<strong>" . htmlspecialchars($row['username']) . ":</strong> " . htmlspecialchars($row['message']);
                    echo "<br><small>" . htmlspecialchars($row['timestamp']) . "</small>";
                    echo "</div>";
                }
            } else {
                echo "Error fetching messages: " . mysqli_error($DBConnect);
            }
            ?>
        </div>
        <form method="post" action="">
            <textarea name="message" placeholder="Type your reply" required></textarea><br>
            <input type="submit" name="submit" value="Send">
        </form>
    </div>

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        const chatWidget = document.getElementById('chat-widget');
        const chatWindow = document.getElementById('chat-window');
        const chatClose = document.getElementById('chat-close');

        chatWidget.addEventListener('click', () => {
            chatWindow.style.display = chatWindow.style.display === 'none' || chatWindow.style.display === '' ? 'flex' : 'none';
        });

        chatClose.addEventListener('click', () => {
            chatWindow.style.display = 'none';
        });

        function refreshChat() {
            var chatBox = document.getElementById('chat-box');
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'admin_chat_content.php', true); // Load chat content for admin
            xhr.onload = function () {
                if (xhr.status === 200) {
                    chatBox.innerHTML = xhr.responseText;
                    chatBox.scrollTop = chatBox.scrollHeight;
                } else {
                    console.error('Failed to fetch chat content:', xhr.status, xhr.statusText);
                }
            };
            xhr.send();
        }

        setInterval(refreshChat, 3000); // Refresh every 3 seconds
    </script>
</body>

</html>
