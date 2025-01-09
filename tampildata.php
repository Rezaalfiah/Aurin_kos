<?php
include('dbconnect.php');

// Function to delete data
if (isset($_GET['delete_email'])) {
    $delete_email = $_GET['delete_email'];
    $delete_query = "DELETE FROM data_diri WHERE email='$delete_email'";
    if (mysqli_query($DBConnect, $delete_query)) {
        header('Location: tampildata.php'); // Redirect after deletion
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($DBConnect);
    }
}

// Query to retrieve data from data_diri table
$query = "SELECT * FROM data_diri";
$result = mysqli_query($DBConnect, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Diri Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9f4fb;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #007bff; /* Warna navbar biru */
            padding: 10px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
        }

        .navbar img {
            height: 40px; /* Ukuran logo */
        }

        .container {
            max-width: 1200px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #0056b3;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e1e8f0;
        }

        a {
            color: #0056b3;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin: 2px 0;
            font-size: 14px;
            font-weight: 400;
            text-align: center;
            cursor: pointer;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .btn-edit {
            color: #fff;
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-delete {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-back {
            background-color: #0056b3;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="#" class="navbar-brand">
            <img src="logo2-removebg-preview.png" alt="Logo"> <!-- Ganti path_to_your_logo.jpg dengan path gambar logo Anda -->
            Indekos Aurin's
        </a>
    </div>

    <div class="container">
        <h1>Data Diri Pengguna</h1>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Pekerjaan</th>
                    <th>No HP</th>
                    <th>Email</th>
                    <th>KTP</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                        <td><?php echo htmlspecialchars($row['pekerjaan']); ?></td>
                        <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($row['ktp_path']); ?>" target="_blank">Lihat KTP</a></td>
                        <td>
                            <a href="edit.php?email=<?php echo urlencode($row['email']); ?>" class="btn btn-edit">Edit</a>
                            <a href="tampildata.php?delete_email=<?php echo urlencode($row['email']); ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="adminn.php" class="btn btn-back">Kembali</a>
    </div>
</body>
</html>

<?php
mysqli_close($DBConnect);
?>
