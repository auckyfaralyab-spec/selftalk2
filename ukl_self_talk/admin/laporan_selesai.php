<?php
session_start();
include '../includes/koneksi.php';

if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit; }

$data_lapor = mysqli_query($koneksi, "SELECT * FROM lapor WHERE status = 'Selesai' ORDER BY lapor_id DESC");
$data_konseling = mysqli_query($koneksi, "SELECT * FROM konseling WHERE status_jadwal = 'Selesai' ORDER BY konseling_id DESC");
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Selesai</title>
    <link rel="stylesheet" href="../asets/css2/laporan_selesai.css">
</head>
<body>


    <header class="head-halaman">
        <a href="dashboard.php" class="kembali">
            <img src="../asets/img/back.png" alt="Kembali">
        </a>
        <h1 class="judul-halaman">LAPORAN SELESAI</h1>
    </header>


    <main class="container">
        <section class="kotak-laporan">
            <h2 class="sub-judul"><img src="../asets/img/lapor.png" class="ikon-judul" alt="ikon"> Riwayat Pengaduan</h2>
            <table class="tabel-data">
                <thead><tr><th>No</th><th>Nama</th><th>Kategori</th><th>Tanggal</th><th>Status</th></tr></thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($data_lapor)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_terlibat']); ?></td>
                        <td><?= htmlspecialchars($row['kategori']); ?></td>
                        <td><?= date('d M Y', strtotime($row['tgl_kejadian'])); ?></td>
                        <td><span class="label-status">Selesai</span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        
        <section class="kotak-laporan">
            <h2 class="sub-judul"><img src="../asets/img/konseling.png" class="ikon-judul" alt="ikon"> Riwayat Konseling</h2>
            <table class="tabel-data">
                <thead><tr><th>No</th><th>Nama</th><th>Jadwal</th><th>Status</th></tr></thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($data_konseling)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                        <td><?= $row['tanggal']; ?></td>
                        <td><span class="label-status">Selesai</span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <?php include '../includes/footer.php'; ?>
    </main>
</body>
</html>