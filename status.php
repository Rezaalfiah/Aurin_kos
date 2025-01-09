<?php
session_start(); // Start the session to access session variables

// Menghubungkan ke server MySQL
$servername = "localhost";
$username = "root";
$password = "";
$database = "indekos";

// Koneksi ke database
$DBConnect = @mysqli_connect($servername, $username, $password, $database)
    or die ("<p>Tidak dapat terhubung ke server database.</p><p>Kode kesalahan ". mysqli_connect_errno().": ". mysqli_connect_error(). "</p>");

// Inisialisasi variabel status_message
$status_message = '';

// Cek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil nilai email dari form
    $email = $_POST['email'];
    
    // Persiapan kueri SQL untuk mendapatkan status pembayaran terbaru berdasarkan email
    $stmt = $DBConnect->prepare("SELECT status FROM payments WHERE customer_email=? ORDER BY created_at DESC LIMIT 1");

    // Periksa kueri SQL
    if ($stmt === false) {
        die('Prepare failed: ' . $DBConnect->error);
    }

    // Binding parameter email ke kueri SQL
    $stmt->bind_param("s", $email);
    
    // Eksekusi kueri
    $stmt->execute();

    // Binding hasil kueri ke variabel status
    $stmt->bind_result($status);
    
    // Ambil hasil dari kueri
    if ($stmt->fetch()) {
        $status_message = "Status pembayaran untuk $email adalah $status";
    } else {
        $status_message = "Tidak ditemukan pembayaran dengan email: $email";
    }
    
    // Tutup prepared statement
    $stmt->close();
    
    // Tutup koneksi database
    mysqli_close($DBConnect);
}

// Ambil email dari session
$email = isset($_SESSION['useremail']) ? $_SESSION['useremail'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cek Status Pembayaran</title>
    <style>
        /* CSS styles untuk halaman */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        form {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            background: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input[type="text"] {
            width: calc(100% - 22px); /* Penyesuaian lebar input dengan padding */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            text-decoration: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .btn-submit {
            background-color: #007bff;
            border: 1px solid #007bff;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .btn-back {
            background-color: #6c757d;
            border: 1px solid #6c757d;
            margin-left: 10px;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        .message {
            text-align: center;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .message-info {
            background-color: #cce5ff;
            color: #004085;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cek Status Pembayaran</h1>
        <?php if (!empty($status_message)): ?>
            <div class="message message-info"><?php echo $status_message; ?></div>
        <?php endif; ?>
        <form action="status.php" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <button type="submit" class="btn btn-submit">Cek Status</button>
            <a href="pembayaran.php" class="btn btn-back">Kembali</a>
        </form>
    </div>
</body>
</html>
