<?php
include('dbconnect.php');

// Handle verifikasi pembayaran
if (isset($_GET['verify'])) {
    $id = (int)$_GET['verify'];
    $query = "UPDATE payments SET status='verified' WHERE id=$id";
    $result = mysqli_query($DBConnect, $query);
    if ($result) {
        header('Location: verifikasiA.php');
        exit;
    } else {
        echo "Kesalahan saat memperbarui rekaman: " . mysqli_error($DBConnect);
    }
}

// Handle penolakan pembayaran
if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    $query = "UPDATE payments SET status='rejected' WHERE id=$id";
    $result = mysqli_query($DBConnect, $query);
    if ($result) {
        header('Location: verifikasiA.php');
        exit;
    } else {
        echo "Kesalahan saat memperbarui rekaman: " . mysqli_error($DBConnect);
    }
}

// Handle penghapusan pembayaran
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $query = "DELETE FROM payments WHERE id=$id";
    $result = mysqli_query($DBConnect, $query);
    if ($result) {
        header('Location: verifikasiA.php');
        exit;
    } else {
        echo "Kesalahan saat menghapus rekaman: " . mysqli_error($DBConnect);
    }
}

// Query untuk mengambil data payments dan reservations berdasarkan email, diurutkan berdasarkan created_at
$query = "SELECT p.id, p.customer_email, p.payment_proof, p.status, r.price, r.created_at
          FROM payments p
          INNER JOIN reservations r ON p.customer_email = r.email
          ORDER BY r.created_at ASC";

$payments = mysqli_query($DBConnect, $query);

mysqli_close($DBConnect);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #007bff; /* Warna navbar biru */
            padding: 10px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
        }

        .navbar img {
            height: 40px; /* Ukuran logo */
            margin-right: 10px; /* Jarak antara logo dan teks */
        }

        .navbar-text {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff; /* Warna header tabel biru */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e1e8f0;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn {
            padding: 5px 10px;
            text-decoration: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            display: inline-block;
            margin-right: 5px;
            border: none; /* Hapus border untuk tombol */
        }

        .btn-verify {
            background-color: #007bff; /* Warna tombol biru */
        }

        .btn-reject {
            background-color: #dc3545;
        }

        .btn-delete {
            background-color: #6c757d;
        }

        .btn-logout {
            background-color: #007bff; /* Warna tombol biru */
            margin-top: 10px;
            display: block;
            width: 100px;
            text-align: center;
        }

        .btn-logout:hover {
            background-color: #0056b3; /* Warna tombol biru saat hover */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-brand">
            <img src="logo2-removebg-preview.png" alt="Logo"> <!-- Ganti path_to_your_logo.jpg dengan path gambar logo Anda -->
            <span class="navbar-text">Indekos Aurin's</span>
        </div>
    </div>

    <div class="container">
        <h1>Verifikasi Pembayaran</h1>
        <table>
            <tr>
                <th>No</th>
                <th>Email</th>
                <th>Bukti Pembayaran</th>
                <th>Status</th>
                <th>Harga</th>
                <th>Created At</th> <!-- Kolom untuk menampilkan created_at -->
                <th>Aksi</th>
            </tr>
            <?php
            $no = 1; // Variabel untuk nomor urut
            while ($row = mysqli_fetch_assoc($payments)): ?>
            <tr>
                <td><?php echo $no++; ?></td> <!-- Menampilkan nomor urut -->
                <td><?php echo $row['customer_email']; ?></td>
                <td><a href="<?php echo $row['payment_proof']; ?>" target="_blank">Lihat</a></td>
                <td><?php echo $row['status']; ?></td>
                <td>Rp <?php echo number_format($row['price'], 0, ",", "."); ?></td>
                <td><?php echo $row['created_at']; ?></td> <!-- Menampilkan kolom created_at -->
                <td>
                    <a href="verifikasiA.php?verify=<?php echo $row['id']; ?>" class="btn btn-verify">Verifikasi</a>
                    <a href="verifikasiA.php?reject=<?php echo $row['id']; ?>" class="btn btn-reject">Tolak</a>
                    <a href="verifikasiA.php?delete=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="adminn.php" class="btn btn-logout">Keluar</a>
    </div>
</body>
</html>
