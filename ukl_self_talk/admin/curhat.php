<?php
session_start();
include '../includes/koneksi.php';


if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// deteksi kolom id , mengambil query cerita
$cek_kolom = mysqli_query($koneksi, "SHOW KEYS FROM cerita WHERE Key_name = 'PRIMARY'");
if ($cek_kolom && mysqli_num_rows($cek_kolom) > 0) {
    $data_kolom = mysqli_fetch_assoc($cek_kolom);
    $nama_kolom_id = $data_kolom['Column_name'];
} else {
    $nama_kolom_id = 'id'; 
}

// 3. ambil data curhatan
$query_all_curhat = "SELECT * FROM cerita ORDER BY $nama_kolom_id DESC";
$result_curhat = mysqli_query($koneksi, $query_all_curhat);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Curhat - SKOMDA MENTAL CARE</title>

    
    <link rel="stylesheet" href="../asets/css includes/nav_atas2.css">
    <link rel="stylesheet" href="../asets/css2/curhat.css">
</head>
<body>

    <div class="curhat-header">
        <a href="dashboard.php" class="btn-kembali">
            <img src="../asets/img/back.png" class="icon-kembali-img" alt="Kembali">
        </a>
        <h2>CURHAT</h2>
    </div>

    <div class="curhat-container">
        <?php 
        if (mysqli_num_rows($result_curhat) > 0):
            while ($row = mysqli_fetch_assoc($result_curhat)):
                $tanggal_tampil = isset($row['created_at']) ? date('d/m/Y, H.i.s', strtotime($row['created_at'])) : 'Baru saja';
        ?>
                <div class="curhat-card">
                    <div class="card-meta-row">
                        <span class="tag-anon">ANONIM</span>
                        <span class="text-tanggal"><?= $tanggal_tampil; ?></span>
                    </div>
                    <p class="curhat-isi-text">
                        "<?= htmlspecialchars($row['isi_cerita']); ?>"
                    </p>
                </div>
        <?php 
            endwhile;
        else:
            echo "<div class='curhat-card' style='text-align: center; color: #94a3b8;'>Belum ada data curhatan siswa yang masuk.</div>";
        endif;
        ?>
    </div>

    
</body>
</html>