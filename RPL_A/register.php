<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4; /* Light gray background */
      color: #333333;
      margin: 0;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh; /* Center form vertically */
    }
    .container {
      width: 100%;
      max-width: 500px; /* Max width for the form */
      background-color: #ffffff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow */
    }
    h1 {
      color: #1e90ff; /* Bright blue */
      text-align: center;
      margin-bottom: 30px;
    }
    form {
      display: flex;
      flex-direction: column;
    }
    label {
      font-weight: bold;
      margin-bottom: 8px;
    }
    input[type="text"], input[type="password"] {
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #cccccc;
      border-radius: 6px;
      font-size: 16px;
    }
    button[type="reset"], button[type="submit"] {
      padding: 12px 0;
      margin-top: 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
      width: 100%; /* Full width for buttons */
    }
    button[type="reset"] {
      background-color: #cccccc;
      margin-right: 10px;
    }
    button[type="submit"] {
      background-color: #1e90ff;
      color: #ffffff;
    }
    button[type="reset"]:hover, button[type="submit"]:hover {
      opacity: 0.8;
    }
    .error-message {
      color: red;
      text-align: center;
      margin-top: 10px;
    }
    .login-link {
      text-align: center;
      margin-top: 20px;
      font-size: 16px;
    }
    .login-link a {
      color: #1e90ff;
      text-decoration: none;
    }
    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Registrasi</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <p>Silakan isi kolom di bawah ini untuk menyelesaikan registrasi Anda.</p> 
      <label for="nama">Nama:</label>
      <input type="text" id="nama" name="nama" maxlength="50" required>
      
      <label for="password">Kata Sandi:</label>
      <input type="password" id="password" name="password" maxlength="25" pattern="^(?=.*[!@#\$%^&*()_+}{:;'?\/><.,|\[\]~-])(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,25}$" title="Kata sandi harus mengandung setidaknya satu angka, satu huruf besar, satu huruf kecil, satu karakter spesial, dan panjang minimal 8 karakter." required>
      
      <label for="email">Email:</label>
      <input type="text" id="email" name="email" maxlength="35" required>
      
      <label for="phone">Nomor Telepon:</label>
      <input type="text" id="phone" name="phone" maxlength="12" required>
      
      <button type="reset" value="Reset">Reset</button>
      <button type="submit" name="submit">Kirim</button>
    </form>

    <?php
    // PHP validation and database handling code
    include('dbconnect.php');

    // Initialize error message variable
    $error_message = '';

    // Retrieve name, password, email, and phone number submitted by the user
    if (isset($_POST['submit'])) {
        $nama = mysqli_real_escape_string($DBConnect, $_POST['nama']);
        $password = $_POST['password']; // Password is not encrypted
        $email = mysqli_real_escape_string($DBConnect, $_POST['email']);
        $phone = mysqli_real_escape_string($DBConnect, $_POST['phone']);

        // Validate input
        if (empty($nama)) {
            $error_message .= "Silakan isi nama Anda.<br>";
        }
        if (empty($password)) {
            $error_message .= "Silakan isi kata sandi Anda.<br>";
        } elseif (!preg_match("/^(?=.*[!@#\$%^&*()_+}{:;'?\/><.,|\[\]~-])(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,25}$/", $password)) {
            $error_message .= "Kata sandi harus mengandung setidaknya satu angka, satu huruf besar, satu huruf kecil, satu karakter spesial, dan panjang minimal 8 karakter.<br>";
        }
        if (empty($email)) {
            $error_message .= "Silakan isi alamat email yang valid.<br>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message .= "Silakan isi alamat email yang valid.<br>";
        }
        if (empty($phone)) {
            $error_message .= "Silakan isi nomor telepon yang dapat dihubungi.<br>";
        } elseif (!is_numeric($phone)) {
            $error_message .= "Silakan isi nomor telepon yang valid.<br>";
        }

        // If no error message, proceed to save data to the database
        if (empty($error_message)) {
            $query = "INSERT INTO registrasi (nama, password, email, phone) VALUES ('$nama', '$password', '$email', '$phone')";
            $result = mysqli_query($DBConnect, $query);

            if ($result) {
                echo "<div style='text-align: center; margin-top: 10px;'>Terima kasih. Registrasi berhasil. Anda sekarang dapat <a href='login.php'>Masuk</a> di sini.</div>";
            } else {
                echo "<div class='error-message'>Registrasi gagal. Silakan coba lagi nanti.</div>";
            }
        } else {
            // Display error message if any
            echo '<div class="error-message">' . $error_message . '</div>';
        }

        // Close database connection
        mysqli_close($DBConnect);
    }
    ?>

    <div class="login-link">
      <b>Sudah terdaftar?</b> <a href="login.php">Masuk di sini</a>
    </div>
  </div>
</body>
</html>
