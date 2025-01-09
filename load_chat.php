<?php
include 'dbconnect.php';

$sql = "SELECT * FROM chat_messages ORDER BY timestamp DESC LIMIT 10"; // Menampilkan 10 pesan terbaru
$result = mysqli_query($DBConnect, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $username = $row['username'];
    $message = $row['message'];
    $is_admin = $row['is_admin'];

    if ($is_admin) {
        echo "<div class='chat-message admin-message'><strong>Admin:</strong> $message</div>";
    } else {
        echo "<div class='chat-message user-message'><strong>$username:</strong> $message</div>";
    }
}
?>
