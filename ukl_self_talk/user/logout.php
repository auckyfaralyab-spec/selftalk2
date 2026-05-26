<?php
session_start();
session_destroy();
header("Location: pilihan.php"); // Setelah logout, dia bakal mau buka pilihan.php
exit;
?>