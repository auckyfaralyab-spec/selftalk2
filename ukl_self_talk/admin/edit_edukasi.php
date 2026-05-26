<?php
session_start();
include '../includes/koneksi.php';


if (!isset($_SESSION['id_user'])) { 
    header("Location: ../login.php"); 
    exit; 
}

// ambil id
$id_artikel = mysqli_real_escape_string($koneksi, $_GET['id']);

// ambil data lama
$ambil_data = mysqli_query($koneksi, "SELECT * FROM edukasi WHERE edukasi_id = '$id_artikel'");
$data = mysqli_fetch_assoc($ambil_data);

// jika id tdk valid masuk ke halaman edukasi
if (!$data) {
    header("Location: edukasi.php");
    exit;
}

// tombol simpan logika
if (isset($_POST['proses_edit'])) {
    $judul_baru = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $kategori_baru = mysqli_real_escape_string($koneksi, $_POST['kategori_edu']);
    $isi_baru = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $sumber_baru = mysqli_real_escape_string($koneksi, $_POST['sumber']);

    
    $update = mysqli_query($koneksi, "UPDATE edukasi SET 
        judul = '$judul_baru', 
        kategori_edu = '$kategori_baru', 
        isi = '$isi_baru', 
        sumber = '$sumber_baru' 
        WHERE edukasi_id = '$id_artikel'");

    if ($update) {
        echo "<script>
                alert('Artikel berhasil diperbarui!');
                window.location='edukasi.php';
              </script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Artikel - SelfTalk</title>
    <link rel="stylesheet" href="../asets/css2/edit_edukasi.css">
</head>
<body>

    <header class="header-halaman">
        <a href="edukasi.php" class="tombol-kembali">
            <img src="../asets/img/back.png" alt="Kembali">
        </a>
        <h1 class="teks-judul">EDIT ARTIKEL</h1>
    </header>

    <main class="wadah-halaman">
        <div class="kotak-isian">
            <div class="info-grup">
                <h2 class="sub-judul">Perbarui Informasi Artikel</h2>
                <p class="deskripsi">Pastikan semua informasi sudah benar sebelum disimpan.</p>
            </div>

            <form method="POST" class="formulir-edit">
                <div class="grup-input">
                    <label>Judul Artikel</label>
                    <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']); ?>" required>
                </div>

                <div class="grup-input">
                    <label>Kategori Edukasi</label>
                    <input type="text" name="kategori_edu" value="<?= htmlspecialchars($data['kategori_edu']); ?>" required>
                </div>

                <div class="grup-input">
                    <label>Isi Lengkap Artikel</label>
                    <textarea name="isi" rows="10" required><?= htmlspecialchars($data['isi']); ?></textarea>
                </div>

                <div class="grup-input">
                    <label>Sumber Informasi / Penulis</label>
                    <input type="text" name="sumber" value="<?= htmlspecialchars($data['sumber']); ?>" required>
                </div>

                <div class="area-aksi">
                    <button type="submit" name="proses_edit" class="tombol-simpan">Simpan Perubahan</button>
                    <a href="edukasi.php" class="tombol-batal">Batal</a>
                </div>
            </form>
        </div>

        <?php include '../includes/footer.php'; ?>
    </main>

</body>
</html>