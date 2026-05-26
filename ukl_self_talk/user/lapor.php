<?php
session_start();
include '../includes/koneksi.php';


if (!isset($_SESSION['nama'])) {
    header("Location: ../login.php");
    exit();
}

$pesan = "";

// proses tombol kirim laporan
if (isset($_POST['kirim_laporan'])) {
    
    $session_id   = $_SESSION['id_user']; 
    
    // mengambil data
    $nama_pelapor = mysqli_real_escape_string($koneksi, $_POST['nama_pelapor']); 
    $kategori     = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $tanggal      = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $detail       = mysqli_real_escape_string($koneksi, $_POST['detail']);
    $status_lap   = "menunggu"; 

    
    $query = "INSERT INTO lapor (id_user, nama_terlibat, kategori, tgl_kejadian, detail, status) 
              VALUES ('$session_id', '$nama_pelapor', '$kategori', '$tanggal', '$detail', '$status_lap')";

    // proses ke dd
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Laporan berhasil dikirim ke Guru BK!');
                window.location.href = 'riwayat.php';
              </script>";
        exit();
    } else {
        $error_db = mysqli_error($koneksi);
        $pesan = "<script>alert('Gagal mengirim laporan. Error: $error_db');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Kejadian - SelfTalk Skomda</title>
    
    <?php include '../includes/nav_atas.php'; ?>
    <link rel="stylesheet" href="../asets/css includes/nav_atas.css">
    <link rel="stylesheet" href="../asets/css/lapor.css">
</head>
<body>

<div class="container">
    <?= $pesan; ?>

    <div class="header-laporan">
        <h1>Lapor Kejadian <img src="../asets/img/lapor.png" width="30"></h1>
        <p>Laporkan perundungan yang terjadi di lingkungan SMK Telkom Sidoarjo atau di media sosial. Laporan ini bersifat rahasia.</p>
    </div>

    <div class="kotak-form-lapor">
        <form action="" method="POST">
            <div class="row-input">
                <div class="group-input">
                    <label>NAMA PELAPOR / KORBAN</label>
                    <input type="text" name="nama_pelapor" value="<?= htmlspecialchars($_SESSION['nama']); ?>" required class="input-editable">
                </div>
                <div class="pilihan-input">
                    <label>KATEGORI PERUNDUNGAN</label>
                    <select name="kategori" required>
                        <option value="">Pilih kategori...</option>
                        <option value="Fisik">Fisik (Pukulan, dorongan, dll)</option>
                        <option value="Verbal">Verbal (Hinaan, julukan, dll)</option>
                        <option value="Sosial">Sosial (Pengucilan, fitnah)</option>
                        <option value="Cyber">Cyber (Medsos, chat)</option>
                    </select>
                </div>
            </div>

            <div class="pilihan-input">
                <label>TANGGAL KEJADIAN</label>
                <input type="date" name="tanggal" required>
            </div>

            <div class="pilihan-input">
                <label>DETAIL KEJADIAN</label>
                <textarea name="detail" placeholder="Jelaskan apa yang terjadi, lokasi, and siapa saja yang terlibat jika memungkinkan..." required></textarea>
            </div>

            <button type="submit" name="kirim_laporan" class="btn-kirim-lapor">
                Kirim Laporan ke Guru BK
            </button>
        </form>
        <p class="disclaimer">*LAPORANMU DILINDUNGI OLEH KEBIJAKAN PRIVASI SMK TELKOM SIDOARJO.</p>
    </div>


    <a href="konseling.php" class="card-konseling">
        <div class="isi-card">
            <img src="../asets/img/logoregis.png" width="30">
            <span>Butuh Konseling Tatap Muka? <b>Klik di sini</b></span>
        </div>
        
    </a>
</div>

<?php 
include '../includes/nav.php'; 
include '../includes/footer.php'; 
?>

</body>
</html>