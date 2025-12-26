<?php
include('dbconnect.php');

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $query = "SELECT * FROM data_diri WHERE email='$email'";
    $result = mysqli_query($DBConnect, $query);

    if (!$result) {
        die("Query error: " . mysqli_error($DBConnect));
    }

    $row = mysqli_fetch_assoc($result);
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $nama = mysqli_real_escape_string($DBConnect, $_POST['nama']);
    $alamat = mysqli_real_escape_string($DBConnect, $_POST['alamat']);
    $pekerjaan = mysqli_real_escape_string($DBConnect, $_POST['pekerjaan']);
    $no_hp = mysqli_real_escape_string($DBConnect, $_POST['no_hp']);

    // Handling file upload for KTP
    $ktp_name = $_FILES['ktp']['name'];
    $ktp_content = file_get_contents($_FILES['ktp']['tmp_name']);
    $ktp_path = $_FILES['ktp']['name'];
    move_uploaded_file($_FILES['ktp']['tmp_name'], 'uploads/' . $ktp_path);

    $update_query = "UPDATE data_diri SET nama='$nama', alamat='$alamat', pekerjaan='$pekerjaan', no_hp='$no_hp', ktp_name='$ktp_name', ktp_content=?, ktp_path='$ktp_path' WHERE email='$email'";

    // Prepare statement
    $stmt = mysqli_prepare($DBConnect, $update_query);
    mysqli_stmt_bind_param($stmt, "s", $ktp_content);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: tampildata.php'); // Redirect to the list page after updating
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($DBConnect);
    }
} else {
    header('Location: tampildata.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9f4fb;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 20px;
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
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Data</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <input type="text" id="alamat" name="alamat" value="<?php echo htmlspecialchars($row['alamat']); ?>" required>
            </div>
            <div class="form-group">
                <label for="pekerjaan">Pekerjaan:</label>
                <input type="text" id="pekerjaan" name="pekerjaan" value="<?php echo htmlspecialchars($row['pekerjaan']); ?>" required>
            </div>
            <div class="form-group">
                <label for="no_hp">No HP:</label>
                <input type="text" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($row['no_hp']); ?>" required>
            </div>
            <div class="form-group">
                <label for="ktp">Upload KTP:</label>
                <input type="file" id="ktp" name="ktp" accept=".pdf,.jpg,.jpeg">
            </div>
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>

<?php
mysqli_close($DBConnect);
?>
