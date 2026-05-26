<?php
session_start();
include '../includes/koneksi.php';

if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit; }

// update status data
if (isset($_GET['aksi']) && $_GET['aksi'] == 'ubah') {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $tipe = $_GET['tipe'];
    if ($tipe == 'lapor') { mysqli_query($koneksi, "UPDATE lapor SET status = 'Selesai' WHERE lapor_id = '$id'"); }
    else { mysqli_query($koneksi, "UPDATE konseling SET status_jadwal = 'Selesai' WHERE konseling_id = '$id'"); }
    echo "<script>alert('Berhasil!'); window.location='laporan_masuk.php';</script>";
}

$res_lapor = mysqli_query($koneksi, "SELECT * FROM lapor WHERE status = 'menunggu' OR status IS NULL ORDER BY lapor_id DESC");
$res_konsel = mysqli_query($koneksi, "SELECT * FROM konseling WHERE status_jadwal = 'menunggu' OR status_jadwal IS NULL ORDER BY konseling_id DESC");
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Masuk</title>
    <link rel="stylesheet" href="../asets/css2/laporan_masuk.css">
</head>
<body>

    <header class="header-nav">
        <a href="dashboard.php" class="btn-back">
            <img src="../asets/img/back.png" alt="Kembali">
        </a>
        <h1 class="nav-title">LAPORAN MASUK</h1>
    </header>

    <main class="container">
        <section class="wrapper-laporan">
            <h2 class="main-title"><img src="../asets/img/lapor.png" class="title-icon" alt="icon"> Pengaduan Siswa</h2>
            <table class="data-table">
                <thead><tr><th>No</th><th>Nama</th><th>Kategori</th><th>Detail</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($res_lapor)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_terlibat']); ?></td>
                        <td><?= htmlspecialchars($row['kategori']); ?></td>
                        <td><?= htmlspecialchars($row['detail']); ?></td>
                        <td><span class="badge-status"><?= $row['status'] ?? 'menunggu'; ?></span></td>
                        <td><a href="?aksi=ubah&tipe=lapor&id=<?= $row['lapor_id']; ?>" class="btn-selesai">Selesai</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        
        <section class="wrapper-laporan">
            <h2 class="main-title"><img src="../asets/img/konseling.png" class="title-icon" alt="icon"> Konseling Masuk</h2>
            <table class="data-table">
                <thead><tr><th>No</th><th>Nama</th><th>Jadwal</th><th>Cerita</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($res_konsel)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                        <td><?= $row['tanggal']; ?> <?= $row['waktu']; ?></td>
                        <td><?= htmlspecialchars($row['cerita_singkat']); ?></td>
                        <td><span class="badge-status"><?= $row['status_jadwal'] ?? 'menunggu'; ?></span></td>
                        <td><a href="?aksi=ubah&tipe=konseling&id=<?= $row['konseling_id']; ?>" class="btn-selesai">Selesai</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <?php include '../includes/footer.php'; ?>
    </main>
</body>
</html>