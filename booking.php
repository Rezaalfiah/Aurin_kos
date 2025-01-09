<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Diri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="file"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="file"]:focus,
        input[type="number"]:focus {
            border-color: #007bff;
        }

        input[type="file"]::-webkit-file-upload-button {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="file"]::-webkit-file-upload-button:hover {
            background: #0056b3;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .back-btn {
            background-color: #ccc;
            color: #333;
            display: inline-block;
            text-align: center;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #ddd;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Diri</h1>
        <?php
        // Include database connection
        include('dbconnect.php');

        // Initialize variables for error and success messages
        $error = '';
        $success = '';

        // Process form submission
        // Process form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitasi dan validasi input
            $nama = filter_var(trim($_POST['nama']), FILTER_SANITIZE_STRING);
            $alamat = filter_var(trim($_POST['alamat']), FILTER_SANITIZE_STRING);
            $pekerjaan = filter_var(trim($_POST['pekerjaan']), FILTER_SANITIZE_STRING);
            $no_hp = filter_var(trim($_POST['no_hp']), FILTER_SANITIZE_STRING);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        
            // Validasi format email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Alamat email tidak valid.";
            } else {
                // Cek jika email sudah terdaftar
                $check_email_query = "SELECT COUNT(*) FROM data_diri WHERE email = ?";
                $check_email_stmt = mysqli_prepare($DBConnect, $check_email_query);
                if ($check_email_stmt) {
                    mysqli_stmt_bind_param($check_email_stmt, 's', $email);
                    mysqli_stmt_execute($check_email_stmt);
                    mysqli_stmt_bind_result($check_email_stmt, $email_count);
                    mysqli_stmt_fetch($check_email_stmt);
                    mysqli_stmt_close($check_email_stmt);
        
                    if ($email_count > 0) {
                        // Email sudah ada, lakukan pembaruan
                        $query = "UPDATE data_diri SET nama = ?, alamat = ?, pekerjaan = ?, no_hp = ? WHERE email = ?";
                        $stmt = mysqli_prepare($DBConnect, $query);
                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, 'sssss', $nama, $alamat, $pekerjaan, $no_hp, $email);
                            $result = mysqli_stmt_execute($stmt);
                            if ($result) {
                                $success = "Data berhasil diperbarui.";
                            } else {
                                $error = "Gagal memperbarui data. Silakan coba lagi. Error: " . mysqli_error($DBConnect);
                            }
                            mysqli_stmt_close($stmt);
                        } else {
                            $error = "Gagal menyiapkan pernyataan SQL: " . mysqli_error($DBConnect);
                        }
                    } else {
                        // Jika email belum terdaftar, lakukan penyimpanan data seperti biasa
                        $allowed_extensions = array("pdf", "jpg", "jpeg");
                        $ktp_name = $_FILES['ktp']['name'];
                        $ktp_tmp = $_FILES['ktp']['tmp_name'];
                        $ktp_size = $_FILES['ktp']['size'];
        
                        $ktp_extension = strtolower(pathinfo($ktp_name, PATHINFO_EXTENSION));
                        if (!in_array($ktp_extension, $allowed_extensions)) {
                            $error = "Maaf, hanya file PDF dan JPG yang diizinkan.";
                        } elseif ($ktp_size > 2 * 1024 * 1024) { // 2MB max file size
                            $error = "Ukuran file KTP terlalu besar. Maksimal 2MB.";
                        } else {
                            // Tentukan direktori upload untuk file KTP
                            $upload_dir = "uploads/";
        
                            // Tentukan nama unik untuk file KTP
                            $ktp_new_name = uniqid() . "_" . basename($ktp_name);
        
                            // Pindahkan file KTP ke direktori upload
                            $ktp_path = $upload_dir . $ktp_new_name;
                            if (!move_uploaded_file($ktp_tmp, $ktp_path)) {
                                $error = "Gagal mengunggah file KTP.";
                            } else {
                                // Masukkan data ke dalam tabel data_diri
                                $query = "INSERT INTO data_diri (nama, alamat, pekerjaan, no_hp, email, ktp_name, ktp_path) 
                                          VALUES (?, ?, ?, ?, ?, ?, ?)";
        
                                $stmt = mysqli_prepare($DBConnect, $query);
                                if ($stmt) {
                                    mysqli_stmt_bind_param($stmt, 'sssssss', $nama, $alamat, $pekerjaan, $no_hp, $email, $ktp_name, $ktp_path);
        
                                    $result = mysqli_stmt_execute($stmt);
                                    if ($result) {
                                        $success = "Data berhasil disimpan.";
                                        // Redirect to room selection page upon successful submission
                                        mysqli_stmt_close($stmt);
                                        mysqli_close($DBConnect);
                                        header('Location: pemilihankmr.php');
                                        exit;
                                    } else {
                                        $error = "Gagal menyimpan data. Silakan coba lagi. Error: " . mysqli_error($DBConnect);
                                    }
                                    mysqli_stmt_close($stmt);
                                } else {
                                    $error = "Gagal menyiapkan pernyataan SQL: " . mysqli_error($DBConnect);
                                }
                            }
                        }
                    }
                } else {
                    $error = "Gagal memeriksa email: " . mysqli_error($DBConnect);
                }
            }
        
            mysqli_close($DBConnect);
        }

        ?>
        
        <!-- Form and messages -->
        <div>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
        </div>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars(isset($nama) ? $nama : ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <input type="text" id="alamat" name="alamat" value="<?php echo htmlspecialchars(isset($alamat) ? $alamat : ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="pekerjaan">Pekerjaan:</label>
                <input type="text" id="pekerjaan" name="pekerjaan" value="<?php echo htmlspecialchars(isset($pekerjaan) ? $pekerjaan : ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="no_hp">No HP:</label>
                <input type="text" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars(isset($no_hp) ? $no_hp : ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars(isset($email) ? $email : ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="ktp">Upload KTP (Max 2MB, PDF/JPG/JPEG):</label>
                <input type="file" id="ktp" name="ktp" accept=".pdf,.jpg,.jpeg" required>
            </div>
            <button type="submit">Submit</button>
        </form>
        
        <a href="booking.html" class="back-btn">Kembali</a>
    </div>
</body>
</html>
