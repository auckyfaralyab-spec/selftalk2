<?php
// Logika untuk mengambil inisial nama
if (isset($_SESSION['nama'])) {
    // Mengambil karakter pertama dan mengubahnya jadi huruf kapital
    $inisial = strtoupper(substr($_SESSION['nama'], 0, 1));
} else {
    // Jika belum login atau session kosong, tampilkan default '?' atau 'A'
    $inisial = "A";
}
?>
 
<link rel="stylesheet" href="asets/css includes/nav_atas.css">

<nav class="navbaratas">
    <div class="nav-kiri">
        <a href="home.php" class="logo-link">
            <img src="../asets/img/logo%20selftalk%203.png" alt="Logo" class="mini-logo">
            <span class="brand-name">SelfTalk</span>
        </a>
    </div>

    <div class="nav-kanan">
        <a href="profil.php" class="profile-button">
            <div class="user-initial"><?php echo $inisial; ?></div>
        </a>
    </div>
</nav>