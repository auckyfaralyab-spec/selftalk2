<?php
// 1. pengaman
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/koneksi.php';

// cek status login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$pesan = "";

// logika menyipan data k dd
if (isset($_POST['kirim_konseling'])) {
    
    $session_id     = $_SESSION['id_user']; 
    
    // data
    $nama_lengkap   = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $kelas          = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $tanggal        = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $waktu          = mysqli_real_escape_string($koneksi, $_POST['waktu']);
    $cerita_singkat = mysqli_real_escape_string($koneksi, $_POST['cerita_singkat']);
    $status_jadwal  = "menunggu"; 

    $query = "INSERT INTO konseling (user_id, lapor_id, tanggal, waktu, kelas, nama_lengkap, cerita_singkat, status_jadwal) 
              VALUES ('$session_id', NULL, '$tanggal', '$waktu', '$kelas', '$nama_lengkap', '$cerita_singkat', '$status_jadwal')";

    //mngirim ke dd
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Permintaan jadwal konseling berhasil dikirim ke Guru BK!');
                window.location.href = 'home.php';
              </script>";
        exit();
    } else {
    
        die("Cobaan Database Gagal! Errornya adalah: " . mysqli_error($koneksi));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konseling Tatap Muka - SelfTalk</title>
    
    <link rel="stylesheet" href="../asets/css includes/nav_atas.css">
    <link rel="stylesheet" href="../asets/css/konseling.css"> 
</head>
<body>

    <?php include '../includes/nav_atas.php'; ?>

    <div class="kembali-lapor-container">
        <a href="lapor.php" class="back-link"> <img src="../asets/img/back.png" alt="Back" width="24"></a>

        
        <div class="header-konseling">
            <img src="../asets/img/kalender.png" alt="Icon Konseling" width="55" style="margin-bottom: 10px; object-fit: contain;">
            <h1>Konseling Tatap Muka</h1>
            <p>Jadwalkan waktu untuk berbicara langsung dengan Guru BK kami secara privat dan aman.</p>
        </div>

        <div class="kotak-form-konseling">

        <div class="alert-catatan">
            <img src="../asets/img/kalender.png" alt="Alert Icon" width="22" style="object-fit: contain; margin-top: 2px;">
            <p><b>Catatan:</b> Layanan tatap muka memerlukan identitas asli untuk keperluan penjadwalan dan tindak lanjut dari Guru BK.</p>
        </div>

        
            <form action="" method="POST">
                
                <div class="input">
                    <label>NAMA LENGKAP</label>
                    <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkapmu..." required>
                </div>


                <div class="input">
                    <label>KELAS / JURUSAN</label>
                    <input type="text" name="kelas" placeholder="Contoh: X SIJA 2" required>
                </div>


                <div class="flex-row">
                    <div class="input">
                        <label>TANGGAL PILIHAN</label>
                        <input type="date" name="tanggal" required>
                    </div>
                    <div class="input">
                        <label>WAKTU PILIHAN</label>
                        <input type="time" name="waktu" required>
                    </div>
                </div>


                <div class="input">
                    <label>SINGKAT CERITA (OPSIONAL)</label>
                    <textarea name="cerita_singkat" rows="4" placeholder="Apa yang ingin kamu diskusikan dengan Guru BK?"></textarea>
                </div>

                <button type="submit" name="kirim_konseling" class="btn-kirim-jadwal">
                    Kirim Permintaan Jadwal 
                </button>
            </form>
        </div>
    </div> <?php
        include '../includes/nav.php';    
        include '../includes/footer.php';  
    ?>

</body>
</html>