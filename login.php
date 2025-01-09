<?php
session_start();
include('dbconnect.php');

// Function to logout user
function logout()
{
    $_SESSION = array();
    session_destroy();
    header("location: login.html");
    exit;
}

// Function to check last activity and auto-logout after 30 minutes of inactivity
function check_last_activity()
{
    $timeout = 1800; // 30 minutes in seconds

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        logout();
    }

    $_SESSION['last_activity'] = time();
}

// Check if user is logged in
function check_login_status()
{
    if (!isset($_SESSION['useremail'])) {
        logout();
    }
}

// Check last activity and auto-logout
check_last_activity();

// Display error message if there's any
$error_message = '';
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    if ($error === "invalidpwd") {
        $error_message = "Kata sandi salah. Silakan coba lagi.";
    }
}

// Process login form submission
if (isset($_POST['submit'])) {
    $email = strtolower($_POST['email']);
    $pwd = $_POST['pwd'];

    if (empty($email) || empty($pwd)) {
        $error_message = "Harap isi email dan kata sandi Anda.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Harap masukkan email yang valid dan coba lagi.";
    } else {
        // Prepared statement to prevent SQL Injection
        $SQLstring = "SELECT * FROM registrasi WHERE email = ?";
        $stmt = mysqli_prepare($DBConnect, $SQLstring);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $queryResult = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($queryResult) == 0) {
            $error_message = "Anggota tidak ditemukan. Harap daftar.";
        } else {
            $row = mysqli_fetch_assoc($queryResult);

            if ($pwd == $row['password']) {
                $_SESSION['useremail'] = $email;
                $_SESSION['last_activity'] = time(); // Record login time

                // Redirect based on user role
                if ($row['role'] == "admin") {
                    header("Location: adminn.php"); // Redirect admin to adminn.php
                    exit;
                } else {
                    header("Location: booking.html"); // Redirect other users to booking.html
                    exit;
                }
            } else {
                $error_message = "Kata sandi salah. Silakan coba lagi.";
            }
        }
    }
    mysqli_close($DBConnect);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center; /* Menyelaraskan konten horizontal ke tengah */
            align-items: center; /* Menyelaraskan konten vertical ke tengah */
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 20px; /* Increased border radius */
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            width: 350px; /* Adjusted width */
            max-width: 100%; /* Ensures responsiveness */
            margin: auto; /* Menengahkan form */
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            padding: 12px; /* Increased padding */
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px; /* Increased font size */
            width: 100%; /* Full width */
        }

        .button-container {
            margin-top: 15px;
            text-align: center;
        }

        button {
            padding: 12px 20px; /* Increased padding */
            margin-right: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px; /* Increased font size */
            width: auto; /* Adjust width as needed */
        }

        button[type="reset"] {
            background-color: #f0f0f0;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .new-member {
            font-size: 14px;
        }

        .new-member a {
            font-weight: bold;
            margin-left: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Masuk</h1>
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form method="post" action="login.php">
            <div>
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" maxlength="35">
            </div>
            <div>
                <label for="pwd">Sandi:</label>
                <input type="password" name="pwd" id="pwd" maxlength="25">
            </div>
            <div class="button-container">
                <button type="reset">Reset</button>
                <button type="submit" name="submit">Kirim</button>
            </div>
        </form>
        <div class="button-container">
            <div class="new-member">
                <b>Anggota Baru?</b> <a href="register.php">Daftar sekarang</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
