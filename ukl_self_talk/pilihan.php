<?php 
session_start();
// HAPUS SEMUA LOGIKA HEADER LOCATION DI SINI
// HAPUS SEMUA LOGIKA IF ISSET GET ACTION DI SINI
include 'includes/koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Akses - SelfTalk Skomda</title>
    <link rel="stylesheet" href="asets/css/pilihan.css">
</head>
<body>


<div class="container">
    <div class="judul-halaman">
        <h2>Pilih Akses Masuk</h2>
        <p>Silakan pilih identitas kamu untuk masuk ke sistem</p>
    </div>


    <div class="pilihan-akses">
        <a href="login.php?role=guru" class="kartu-role role-guru">
            <div class="buletan-ikon">
                <img src="asets/img/teacher 2.png" alt="Ikon Guru">
            </div>
            <h3>Guru BK</h3>
        </a>




        <a href="login.php?role=siswa" class="kartu-role role-siswa">
            <div class="buletan-ikon">
                <img src="asets/img/siswa.png" alt="Ikon Siswa">
            </div>
            <h3>Siswa</h3>
        </a>
    </div>

    
    <section class="halaman-kutipan">
        <div class="kotak-kutipan">
            <p class="teks-kutipan">"Menciptakan lingkungan sekolah yang nyaman dan bebas perundungan adalah tanggung jawab kita bersama."</p>
            <p class="penulis-kutipan">- Tim Bimbingan Konseling SMK Telkom Sidoarjo</p>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</div>

</body>
</html>