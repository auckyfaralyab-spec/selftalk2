<?php
session_start();
include '../includes/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$cek_kolom = mysqli_query($koneksi, "SHOW KEYS FROM cerita WHERE Key_name = 'PRIMARY'");
if ($cek_kolom && mysqli_num_rows($cek_kolom) > 0) {
    $data_kolom = mysqli_fetch_assoc($cek_kolom);
    $nama_kolom_id = $data_kolom['Column_name'];
} else {
    $nama_kolom_id = 'id'; 
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query_hapus = "DELETE FROM cerita WHERE $nama_kolom_id = '$id_hapus'";
    if (mysqli_query($koneksi, $query_hapus)) {
        echo "<script>alert('Postingan berhasil dihapus!'); window.location='kelola_postingan.php';</script>";
    }
}

if (isset($_POST['kirim_respon'])) {
    $id_cerita = mysqli_real_escape_string($koneksi, $_POST['id_cerita']);
    $isi_respon = mysqli_real_escape_string($koneksi, $_POST['isi_respon']);
    
    $query_respon = "INSERT INTO tanggapan (id_cerita, id_user, isi_tanggapan, tanggal_tanggapan) VALUES ('$id_cerita', '$id_user', '$isi_respon', NOW())";
    if (mysqli_query($koneksi, $query_respon)) {
        echo "<script>alert('Respon resmi berhasil dikirim!'); window.location='kelola_postingan.php';</script>";
    }
}

$query_cerita = mysqli_query($koneksi, "SELECT * FROM cerita ORDER BY $nama_kolom_id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderasi - SKOMDA MENTAL CARE</title>
    <link rel="stylesheet" href="../asets/css includes/nav_atas2.css">
    <link rel="stylesheet" href="../asets/css2/kelola_postingan.css">
</head>
<body>

    <?php include '../includes/nav_atas2.php'; ?>

    <div class="container">
        
        <div class="header-moderasi">
            <a href="dashboard.php" class="btn-back">
                <img src="../asets/img/back.png" class="btn-icon-back" alt="Kembali">
            </a>
            <h1 class="page-title">Halaman Moderasi</h1>
        </div>

        <div class="list-moderasi">
            <?php 
            if (mysqli_num_rows($query_cerita) > 0):
                while ($row = mysqli_fetch_assoc($query_cerita)):
                    $id_c = $row[$nama_kolom_id];

                    $q_cek_tanggapan = mysqli_query($koneksi, "SELECT * FROM tanggapan WHERE id_cerita = '$id_c'");
                    $has_respon = mysqli_num_rows($q_cek_tanggapan) > 0;
            ?>
                <div class="card-moderasi">
                    <div class="card-top-info">
                        <div class="info-left">
                            <span class="lbl-anonim">ANONIM</span>
                            <?php if (!$has_respon): ?>
                                <span class="lbl-status">Belum Ada Respon</span>
                            <?php else: ?>
                                <span class="lbl-status" style="background: #d1fae5; color: #10b981;">Sudah Direspon</span>
                            <?php endif; ?>
                        </div>
                        <div class="info-right">
                            <span class="lbl-date">
                                <?= isset($row['tanggal']) ? $row['tanggal'] : 'Baru saja'; ?>
                            </span>
                            <a href="kelola_postingan.php?aksi=hapus&id=<?= $id_c; ?>" class="btn-hapus-post" onclick="return confirm('Hapus curhatan ini?');">
                                <img src="../asets/img/hapus.png" class="btn-icon-img" alt="Hapus"> Hapus
                            </a>
                        </div>
                    </div>

                    <div class="content-box">
                        <p>"<?= htmlspecialchars($row['isi_cerita']); ?>"</p>
                    </div>

                    <div class="tanggapan-logs-box">
                        <h4 class="daftar-tanggapan-title">Daftar Tanggapan / Diskusi (<?= mysqli_num_rows($q_cek_tanggapan); ?>)</h4>
                        <?php 
                        if ($has_respon) {
                            while ($tanggapan = mysqli_fetch_assoc($q_cek_tanggapan)) {
                                echo "<p style='font-size: 13px; color: #475569; font-weight:500; margin-bottom:4px;'><strong>Guru BK:</strong> " . htmlspecialchars($tanggapan['isi_tanggapan']) . "</p>";
                            }
                        } else {
                            echo '<p class="text-tanggapan-kosong">Belum ada tanggapan apapun pada curhat ini.</p>';
                        }
                        ?>
                    </div>

                    <form action="kelola_postingan.php" method="POST" class="form-respon-section">
                        <input type="hidden" name="id_cerita" value="<?= $id_c; ?>">
                        <span class="lbl-kirim-saran">Kirim Saran / Respon Resmi Guru BK</span>
                        <div class="input-row">
                            <input type="text" name="isi_respon" class="input-respon" placeholder="Tulis nasehat, semangat, atau anjuran untuk siswa yang bercerita..." required>
                            <button type="submit" name="kirim_respon" class="btn-kirim-respon">
                                <img src="../asets/img/kirim.png" class="btn-icon-kirim" alt="Kirim"> Kirim Respon
                            </button>
                        </div>
                    </form>
                </div>
            <?php 
                endwhile;
            else:
                echo "<div class='empty-state'>Belum ada cerita publik dari siswa untuk dimoderasi.</div>";
            endif;
            ?>
        </div>

    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>