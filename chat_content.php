<?php
include 'dbconnect.php'; // Pastikan file ini berada di direktori yang benar

// Ambil semua pesan
$sql = "SELECT * FROM chat_messages ORDER BY timestamp ASC"; // ASC untuk urutan kronologis
$result = mysqli_query($DBConnect, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='message'>";
        echo "<strong>" . htmlspecialchars($row['username']) . ":</strong> " . htmlspecialchars($row['message']);
        if ($row['is_admin']) {
            echo " <span style='color: red;'>(Admin)</span>";
        }
        echo "<br><small>" . $row['timestamp'] . "</small>";
        echo "</div>";
    }
} else {
    echo "Error fetching messages: " . mysqli_error($DBConnect);
    // Tambahkan debugging
    echo "<p>Query: $sql</p>";
}
?>
