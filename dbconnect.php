<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "indekos";

// Menghubungkan ke server MySQL
$DBConnect = @mysqli_connect($servername, $username, $password, $database)
  or die ("<p>Tidak dapat terhubung ke server database.</p><p>Kode kesalahan ". mysqli_connect_errno().": ". mysqli_connect_error(). "</p>");


?>
