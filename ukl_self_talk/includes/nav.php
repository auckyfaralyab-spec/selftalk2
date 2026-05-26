<?php
// Mengambil nama file yang sedang dibuka (misal: lapor.php)
$halaman_sekarang = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="../asets/css%20includes/nav.css">

<nav class="nav-bawah">
    <a href="home.php" class="nav-item <?= ($halaman_sekarang == 'home.php') ? 'active' : ''; ?>">
        <img src="../asets/img/rumah.png" alt="Home">
        <span>Home</span>
    </a>

    <a href="lapor.php" class="nav-item <?= ($halaman_sekarang == 'lapor.php') ? 'active' : ''; ?>">
        <img src="../asets/img/lapor.png" alt="Lapor">
        <span>Lapor</span>
    </a>

    <a href="edu.php" class="nav-item <?= ($halaman_sekarang == 'edu.php') ? 'active' : ''; ?>">
        <img src="../asets/img/edu.png" alt="Edu">
        <span>Edu</span>
    </a>

    <a href="riwayat.php" class="nav-item <?= ($halaman_sekarang == 'riwayat.php') ? 'active' : ''; ?>">
        <img src="../asets/img/riwayat.png" alt="Riwayat">
        <span>Riwayat</span>
    </a>
</nav>