<?php
session_start();
include 'dbconnect.php'; // Pastikan file dbconnect.php sudah di-include

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['useremail'])) {
    header("Location: login.html");
    exit;
}

$email = $_SESSION['useremail']; // Get email from session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['room_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Menghitung durasi inap dalam hari
    $datetime1 = new DateTime($start_date);
    $datetime2 = new DateTime($end_date);
    $interval = $datetime1->diff($datetime2);
    $duration = $interval->days;

    $price_per_month = 750000; // Harga per bulan
    $price = ceil($duration / 30) * $price_per_month; // Total harga berdasarkan durasi inap dalam bulan

    // Validasi email di tabel data_diri
    $email_check_query = "SELECT COUNT(*) FROM data_diri WHERE email=?";
    $stmt = mysqli_prepare($DBConnect, $email_check_query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $email_count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($email_count == 0) {
        echo '<script>alert("Email tidak ditemukan di data diri.");</script>';
    } else {
        // Cek apakah email sudah pernah memesan sebelumnya
        $email_check_query = "SELECT COUNT(*) FROM reservations WHERE email=?";
        $stmt = mysqli_prepare($DBConnect, $email_check_query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $email_count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($email_count > 0) {
            echo '<script>alert("Email ini sudah digunakan untuk pemesanan sebelumnya.");</script>';
        } else {
            // Memasukkan reservasi ke dalam tabel reservations, termasuk email
            $insert_reservation = "INSERT INTO reservations (room_id, start_date, end_date, price, email) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($DBConnect, $insert_reservation);
            mysqli_stmt_bind_param($stmt, 'issss', $room_id, $start_date, $end_date, $price, $email);
            
            // Check if the reservation insertion is successful
            $reservation_success = mysqli_stmt_execute($stmt);

            // Update status kamar menjadi telah dipesan
            $update_room = "UPDATE rooms SET is_booked=1 WHERE id=?";
            $stmt = mysqli_prepare($DBConnect, $update_room);
            mysqli_stmt_bind_param($stmt, 'i', $room_id);
            $room_update_success = mysqli_stmt_execute($stmt);

            // Redirect or show error message based on success
            if ($reservation_success && $room_update_success) {
                // Save selected room_id in session
                $_SESSION['selected_room_id'] = $room_id;

                // Redirect to pembayaran.php upon successful booking
                header('Location: pembayaran.php');
                exit;
            } else {
                // Display an error message if booking fails
                echo '<script>alert("Gagal melakukan pemesanan. Silakan coba lagi.");</script>';
            }
        }
    }
}

// Ambil data kamar dan cek apakah sudah dipesan
$rooms = mysqli_query($DBConnect, "SELECT r.*, 
    (SELECT COUNT(*) FROM reservations res WHERE res.room_id = r.id AND res.end_date >= CURDATE()) AS active_reservations 
    FROM rooms r");

// Dapatkan tanggal hari ini untuk validasi tanggal
$today = date('Y-m-d');
$selected_room_id = isset($_SESSION['selected_room_id']) ? $_SESSION['selected_room_id'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilihan Kamar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eaf2f8; /* Biru muda */
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #0275d8; /* Biru */
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .rooms {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px; /* Spasi antar kamar */
            width: 100%;
            max-width: 900px;
        }

        .room-label {
            display: inline-block;
            width: 100px; /* Lebar kamar */
            height: 100px; /* Tinggi kamar */
            text-align: center;
            line-height: 100px;
            border: 2px solid #4CAF50;
            cursor: pointer;
            position: relative;
            transition: background-color 0.3s ease, transform 0.2s ease;
            background-image: linear-gradient(to right bottom, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.6)), url('room.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            font-size: 18px; /* Ukuran font */
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }

        .room-label.booked {
            background-color: #ff6666;
            cursor: not-allowed;
        }

        .room-label input {
            display: none;
        }

        .room-label.selected::after {
            content: '\2713'; /* Tanda centang Unicode */
            font-size: 30px;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-group {
            margin-top: 20px;
            width: 100%;
            max-width: 400px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #0275d8; /* Biru */
            font-weight: bold;
        }

        input[type="date"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #0275d8; /* Biru */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:disabled {
            background-color: #b0bec5; /* Abu-abu biru */
            cursor: not-allowed;
        }

        button:hover:enabled {
            background-color: #025aa5; /* Biru lebih gelap */
            transform: scale(1.05);
        }

        .price-info {
            text-align: center;
            margin-top: 20px;
        }

        .price-info span {
            font-size: 18px;
            font-weight: bold;
            color: #0275d8; /* Biru */
        }

        .checkout-info {
            text-align: center;
            margin-top: 10px;
            font-size: 16px;
            color: #666;
        }

        .back-button {
            text-align: center;
            margin-top: 20px;
        }

        .back-button a {
            text-decoration: none;
        }

        .back-button button {
            padding: 10px 20px;
            background-color: #0275d8; /* Biru */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-button button:hover {
            background-color: #025aa5; /* Biru lebih gelap */
        }

        .note {
            background-color: #cce5ff; /* Biru muda */
            border: 1px solid #b8daff; /* Biru lebih terang */
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            font-size: 14px;
            color: #004085; /* Biru gelap */
            text-align: center;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <h1>Pemilihan Kamar</h1>
    <div class="container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="rooms">
            <?php
                $count = 0;
                while ($room = mysqli_fetch_assoc($rooms)) {
                    if ($count == 4) {
                        echo '<div style="flex-basis: 100%; height: 0;"></div>'; // Baris kedua setelah 4 kamar
                    }
                    $is_booked = ($room['active_reservations'] > 0);
                    echo '<label class="room-label' . ($is_booked ? ' booked' : '') . '">';
                    echo '<input type="radio" name="room_id" value="' . $room['id'] . '"' . ($is_booked ? ' disabled' : '') . '>';
                    echo $room['room_number'];
                    echo '</label>';
                    $count++;
                }
                ?>

            </div>
            <div class="form-group">
                <label for="start_date">Tanggal Check-In</label>
                <input type="date" id="start_date" name="start_date" min="<?php echo $today; ?>" required>
            </div>
            <div class="form-group">
                <label for="end_date">Tanggal Check-Out</label>
                <input type="date" id="end_date" name="end_date" min="<?php echo $today; ?>" required>
            </div>
            <div class="price-info">
                <span>Total Harga:</span> <span id="price">0</span> IDR
            </div>
            <div class="checkout-info">
                <p id="checkout-info"></p>
            </div>
            <button type="submit" id="submit-button" disabled>Pilih Kamar</button>
        </form>
        <div class="back-button">
            <a href="booking.html">
                <button>Kembali ke dashboard</button>
            </a>
        </div>
    </div>

    <script>
        // Hitung harga berdasarkan durasi inap
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const priceSpan = document.getElementById('price');
        const checkoutInfo = document.getElementById('checkout-info');
        const submitButton = document.getElementById('submit-button');

        const pricePerMonth = 750000; // Harga per bulan

        function calculatePrice() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (startDate && endDate && startDate <= endDate) {
                const timeDiff = endDate - startDate;
                const days = timeDiff / (1000 * 3600 * 24) + 1;
                const months = Math.ceil(days / 30);
                const totalPrice = months * pricePerMonth;

                priceSpan.textContent = totalPrice.toLocaleString(); // Format ribuan
                checkoutInfo.textContent = `Durasi inap: ${days} hari (${months} bulan)`;
                submitButton.disabled = false; // Enable submit button
            } else {
                priceSpan.textContent = '0';
                checkoutInfo.textContent = '';
                submitButton.disabled = true; // Disable submit button
            }
        }

        startDateInput.addEventListener('change', calculatePrice);
        endDateInput.addEventListener('change', calculatePrice);

        // Select room
        const roomLabels = document.querySelectorAll('.room-label');

        roomLabels.forEach(label => {
            label.addEventListener('click', function() {
                if (!this.classList.contains('booked')) {
                    roomLabels.forEach(lbl => lbl.classList.remove('selected'));
                    this.classList.add('selected');
                    submitButton.disabled = false;
                }
            });
        });

        // Auto-fill selected room_id if available
        const selectedRoomId = "<?php echo $selected_room_id; ?>";
        if (selectedRoomId) {
            const selectedRoomLabel = document.querySelector(`.room-label[data-room-id="${selectedRoomId}"]`);
            if (selectedRoomLabel) {
                selectedRoomLabel.click();
            }
        }
    </script>
</body>
</html>
