<?php
session_start();
include 'dbconnect.php';

// Ambil email dari sesi
$useremail = $_SESSION['useremail'];

// Ambil data dari tabel reservations berdasarkan email
$reservation_query = "SELECT id, room_id, start_date, end_date, price, email, created_at FROM reservations WHERE email = ?";
$stmt = $DBConnect->prepare($reservation_query);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$reservation_result = $stmt->get_result();
$reservation = $reservation_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = filter_input(INPUT_POST, 'room_id', FILTER_SANITIZE_NUMBER_INT);
    $email = $useremail; // Gunakan email dari sesi
    $status = "Pending";
    $terms = filter_input(INPUT_POST, 'terms', FILTER_SANITIZE_STRING);

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Alamat email tidak valid.";
    } elseif (!$terms) {
        $error_message = "Anda harus menyetujui syarat dan ketentuan.";
    } else {
        // Cek apakah kombinasi email dan room_id ada di tabel reservations
        $check_query = "SELECT * FROM reservations WHERE room_id = ? AND email = ?";
        $stmt = $DBConnect->prepare($check_query);
        $stmt->bind_param("is", $room_id, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Cek status bukti pembayaran
            $payment_status_query = "SELECT payment_proof FROM payments WHERE customer_email = ?";
            $stmt = $DBConnect->prepare($payment_status_query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $payment_result = $stmt->get_result();

            if ($payment_result->num_rows > 0) {
                $payment_row = $payment_result->fetch_assoc();
                $current_status = $payment_row['payment_proof'];

                // Izinkan pengiriman ulang jika bukti pembayaran kosong
                if (empty($current_status)) {
                    processPaymentSubmission($DBConnect, $email, $room_id, $status);
                } else {
                    $error_message = "Anda tidak dapat mengirim ulang bukti pembayaran karena status pembayaran Anda sedang dalam proses.";
                }
            } else {
                // Pengiriman pertama kali
                processPaymentSubmission($DBConnect, $email, $room_id, $status);
            }
        } else {
            $error_message = "Data tidak sesuai. Pastikan email dan room ID benar.";
        }
    }
}

function processPaymentSubmission($DBConnect, $email, $room_id, $status) {
    global $error_message, $success_message;

    // Validasi dan sanitasi file upload
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['payment_proof']['tmp_name'];
        $file_name = $_FILES['payment_proof']['name'];
        $file_size = $_FILES['payment_proof']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Ekstensi yang diperbolehkan
        $allowed_ext = array('jpg', 'jpeg', 'png', 'pdf');
        if (!in_array($file_ext, $allowed_ext)) {
            $error_message = "Ekstensi file tidak diizinkan. Hanya JPG, JPEG, PNG, dan PDF yang diizinkan.";
            return;
        }

        // Ukuran file maksimum 5MB
        if ($file_size > 5 * 1024 * 1024) {
            $error_message = "Ukuran file terlalu besar. Maksimal 5MB.";
            return;
        }

        // Nama file yang aman
        $file_name_new = uniqid('', true) . '.' . $file_ext;
        $file_path = 'uploads/' . $file_name_new;

        // Pindahkan file
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Masukkan data ke database
            $insert_query = "INSERT INTO payments (customer_email, payment_proof, status) VALUES (?, ?, ?)";
            $stmt = $DBConnect->prepare($insert_query);
            $stmt->bind_param("sss", $email, $file_path, $status);

            if ($stmt->execute()) {
                $success_message = "Bukti pembayaran berhasil dikirim.";
            } else {
                $error_message = "Error saat mengirim bukti pembayaran: " . $stmt->error;
            }
        } else {
            $error_message = "Error saat mengunggah file.";
        }
    } else {
        $error_message = "Silakan pilih file yang valid.";
    }
}

// Ambil data dari query string untuk timer
$room_id_query = $reservation['room_id'];
$email_query = $useremail;

if ($room_id_query && $email_query) {
    $reservation_query = "SELECT created_at FROM reservations WHERE room_id=? AND email=?";
    $stmt = mysqli_prepare($DBConnect, $reservation_query);
    mysqli_stmt_bind_param($stmt, 'is', $room_id_query, $email_query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $created_at);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Set waktu batas 24 jam dari created_at
    $limit_time = date('Y-m-d H:i:s', strtotime('+24 hours', strtotime($created_at)));
    $current_time = date('Y-m-d H:i:s');
    $is_expired = $current_time > $limit_time;
} else {
    $limit_time = date('Y-m-d H:i:s'); // Default ke waktu saat ini jika tidak ada query params
    $is_expired = false;
}

// Query untuk mereset status kamar jika bukti pembayaran tidak diunggah selama 24 jam
$updateQuery = "
  UPDATE rooms r
  JOIN reservations res ON r.room_id = res.room_id
  LEFT JOIN payments p ON res.email = p.customer_email
  SET r.status = 'available'
  WHERE p.payment_proof IS NULL
    AND TIMESTAMPDIFF(HOUR, res.created_at, NOW()) >= 24
";

// Query untuk menghapus entri di tabel reservations jika bukti pembayaran tidak diunggah selama 24 jam
$deleteQuery = "
  DELETE res FROM reservations res
  LEFT JOIN payments p ON res.email = p.customer_email
  WHERE p.payment_proof IS NULL
    AND TIMESTAMPDIFF(HOUR, res.created_at, NOW()) >= 24
";

$updateResult = mysqli_query($DBConnect, $updateQuery);
$deleteResult = mysqli_query($DBConnect, $deleteQuery);

if ($updateResult && $deleteResult) {
    $info_message = "Status kamar berhasil direset dan data reservations berhasil dihapus.";
} else {
    $info_message = "Error: " . mysqli_error($DBConnect);
}

mysqli_close($DBConnect);

// Format harga dengan prefix Rp
$formatted_price = 'Rp ' . number_format($reservation['price'], 0, ',', '.');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            width: 95%;
        }
        .form-group {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .form-group label {
            width: 30%;
            margin-right: 10px;
            color: #333;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="file"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-group .price-container {
            display: flex;
            align-items: center;
            width: 100%;
        }
        .form-group .price-container .price-label {
            background-color: #e9ecef;
            padding: 5px 10px;
            border-radius: 5px;
            margin-right: 10px;
            font-size: 16px;
            font-weight: bold;
            border: 1px solid #ced4da;
        }
        
        
        .btn-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .btn {
            flex: 1;
            padding: 10px;
            margin-top: 10px;
            text-align: center;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }
        .btn-submit {
            background-color: #28a745;
        }
        .btn-check {
            background-color: #007bff;
        }
        .btn-back {
            background-color: #dc3545;
        }
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 10px;
        }
        .success-message {
            color: #28a745;
            font-size: 14px;
            margin-top: 10px;
        }
        .info-message {
            color: #007bff;
            font-size: 14px;
            margin-top: 10px;
        }
        .timer {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: red;
        }
    </style>
    <script>
        let limitTime = new Date("<?php echo $limit_time; ?>").getTime();
        let countdownTimer;

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = limitTime - now;

            if (distance <= 0) {
                clearInterval(countdownTimer);
                document.getElementById("countdown").innerHTML = "Waktu Anda telah habis.";
                document.getElementById("payment-form").style.display = "none";
                document.getElementById("expired-message").style.display = "block";
            } else {
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("countdown").innerHTML = `${hours}h ${minutes}m ${seconds}s`;
            }
        }

        countdownTimer = setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
</head>
<body>
<div class="container">
        <h1>Konfirmasi Pembayaran</h1>
        <div id="countdown" class="timer"></div>
        <form id="payment-form" action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="room_id">Room ID:</label>
                <input type="text" id="room_id" name="room_id" value="<?php echo $reservation['room_id']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="text" id="start_date" name="start_date" value="<?php echo $reservation['start_date']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="text" id="end_date" name="end_date" value="<?php echo $reservation['end_date']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <div class="price-container">
                    <span class=""></span>
                    <input type="text" id="price" name="price" value="<?php echo $formatted_price; ?>" readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo $reservation['email']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="payment_proof">Upload Bukti Pembayaran:</label>
                <input type="file" id="payment_proof" name="payment_proof">
            </div>
            <div class="form-group">
                <input type="checkbox" id="terms" name="terms">
                <label for="terms">Saya setuju dengan syarat dan ketentuan.</label>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-submit">Kirim</button>
                <a href="booking.html" class="btn btn-back">Kembali ke Dashboard</a>
                <a href="status.php" class="btn btn-check">Periksa Status Pembayaran</a>
            </div>
            <?php if (isset($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <?php if (isset($success_message)) : ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($info_message)) : ?>
                <div class="info-message"><?php echo $info_message; ?></div>
            <?php endif; ?>
        </form>
        <div id="expired-message" class="info-message" style="display: none;">
            Waktu Anda telah habis. Silakan hubungi administrator untuk bantuan lebih lanjut.
        </div>
    </div>
</body>
</html>