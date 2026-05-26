<?php
session_start();
include '../includes/koneksi.php';

if (!isset($_SESSION['id_user'])) 
    { header("Location: ../login.php"); exit; }

$data_edukasi = mysqli_query($koneksi, "SELECT * FROM edukasi ORDER BY edukasi_id DESC");
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edukasi</title>
    <link rel="stylesheet" href="../asets/css2/edukasi.css">
</head>
<body>


    <header class="head-halaman">
        <a href="dashboard.php" class="kembali">
            <img src="../asets/img/back.png" alt="Kembali">
        </a>
        <h1 class="judul-halaman">EDUKASI</h1>
    </header>


    <main class="container">
        <section class="kotak-laporan">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="sub-judul">
                    <img src="../asets/img/edukasi.png" class="ikon-judul" alt="Edukasi"> 
                    Daftar Artikel
                </h2>
                <a href="tambah_edukasi.php" class="btn-selesai" style="background:#6366f1;">+ Tambah</a>
            </div>



            <table class="tabel-data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Sumber</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($data_edukasi)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['judul']); ?></td>
                        <td><?= htmlspecialchars($row['kategori_edu']); ?></td>
                        <td><?= htmlspecialchars($row['sumber']); ?></td>
                        <td>
                            <a href="edit_edukasi.php?id=<?= $row['edukasi_id']; ?>" style="color: #6366f1; text-decoration: none;">Edit</a> | 
                            <a href="hapus_edukasi.php?id=<?= $row['edukasi_id']; ?>" style="color: #ef4444; text-decoration: none;" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        
        <?php include '../includes/footer.php'; ?>
    </main>
</body>
</html>