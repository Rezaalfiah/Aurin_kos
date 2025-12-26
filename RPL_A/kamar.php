<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kamar yang Terbooking</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #ffffff; /* Warna background putih */
            color: #333333; /* Warna teks gelap */
            padding-top: 20px;
        }

        .container {
            max-width: 900px; /* Lebar maksimum kontainer */
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .table {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .navbar {
            background-color: #007bff; /* Warna navbar biru */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: #ffffff; /* Warna teks navbar putih */
            font-weight: bold;
        }

        .navbar-brand img {
            height: 40px; /* Ukuran logo */
            margin-right: 10px;
        }

        .navbar-toggler {
            border-color: #ffffff; /* Warna garis toggle putih */
        }

        .navbar-toggler-icon {
            background-color: #ffffff; /* Warna ikon toggle putih */
        }

        .navbar-nav .nav-link {
            color: #ffffff; /* Warna teks link navbar putih */
        }

        .navbar-nav .nav-link:hover {
            color: #ffffff; /* Warna teks link navbar putih */
        }

        .btn-primary {
            background-color: #007bff; /* Warna background tombol biru */
            border-color: #007bff; /* Warna border tombol biru */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Warna background tombol biru saat dihover */
            border-color: #0056b3; /* Warna border tombol biru saat dihover */
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">
            <img src="logo2-removebg-preview.png" alt="Logo"> <!-- Ganti path_to_your_logo.jpg dengan path gambar logo Anda -->
            Indekos Aurin's
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <!-- Tidak ada link "Home" di sini -->
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-5 mb-4">Data Kamar yang Terbooking</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Room ID</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">End Date</th>
                        <th scope="col">Price</th>
                        <th scope="col">Customer Email</th>
                        <th scope="col">Created At</th> <!-- Tambahkan kolom created_at di sini -->
                        <th scope="col">Action</th> <!-- Kolom untuk tombol aksi -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Koneksi ke database
                    $servername = "localhost"; // Ganti dengan nama server database Anda
                    $username = "root"; // Ganti dengan username database Anda
                    $password = ""; // Ganti dengan password database Anda
                    $dbname = "indekos"; // Ganti dengan nama database Anda

                    // Buat koneksi
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Periksa koneksi
                    if ($conn->connect_error) {
                        die("Koneksi gagal: " . $conn->connect_error);
                    }

                    // Check jika form dikirim untuk menghapus
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']) && isset($_POST['room_id'])) {
                        // Escape input untuk mencegah SQL Injection
                        $room_id = $conn->real_escape_string($_POST['room_id']);

                        // Query untuk menghapus data berdasarkan room_id
                        $sql_delete = "DELETE FROM reservations WHERE room_id = '$room_id'";

                        if ($conn->query($sql_delete) === TRUE) {
                            echo "<div class='alert alert-success' role='alert'>
                                    Data berhasil dihapus.
                                  </div>";
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>
                                    Error: " . $sql_delete . "<br>" . $conn->error . "
                                  </div>";
                        }
                    }

                    // Query untuk mengambil data kamar yang sudah terbooking, diurutkan berdasarkan created_at secara ascending
                    $sql_select = "SELECT * FROM reservations ORDER BY created_at ASC";

                    $result = $conn->query($sql_select);

                    // Counter untuk nomor urut
                    $counter = 1;

                    // Check jika terdapat data
                    if ($result->num_rows > 0) {
                        // Output data dari setiap baris
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $counter . "</td>";
                            echo "<td>" . $row["room_id"] . "</td>";
                            echo "<td>" . $row["start_date"] . "</td>";
                            echo "<td>" . $row["end_date"] . "</td>";
                            echo "<td>Rp " . number_format($row["price"], 0, ",", ".") . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["created_at"] . "</td>"; // Menampilkan kolom created_at
                            echo "<td>
                                    <form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                                        <input type='hidden' name='room_id' value='" . $row["room_id"] . "'>
                                        <button type='submit' name='delete' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>Hapus</button>
                                    </form>
                                </td>";
                            echo "</tr>";
                            $counter++; // Inkrementasi counter
                        }
                    } else {
                        echo "<tr><td colspan='8'>Tidak ada data kamar yang terbooking.</td></tr>";
                    }

                    // Tutup koneksi database
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        <a href="adminn.php" class="btn btn-primary mt-3">Kembali</a> <!-- Tombol kembali ke halaman admin.html -->
    </div>

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
