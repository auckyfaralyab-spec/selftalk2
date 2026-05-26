<?php
session_start();
include '../includes/koneksi.php';

if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit; }

// logika kalau tombol simpan ditekan
if (isset($_POST['simpan_data'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori_edu']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $sumber = mysqli_real_escape_string($koneksi, $_POST['sumber']);

    $insert = mysqli_query($koneksi, "INSERT INTO edukasi (judul, kategori_edu, isi, sumber) 
                                      VALUES ('$judul', '$kategori', '$isi', '$sumber')");

    if ($insert) {
        echo "<script>
                alert('Artikel berhasil ditambahkan!');
                window.location='edukasi.php';
              </script>";
    } else {
        echo "<script>alert('Gagal menambahkan data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Artikel - SelfTalk</title>
    <link rel="stylesheet" href="../asets/css2/tambah_edukasi.css">
</head>
<body>

    <header class="header-halaman">
        <a href="edukasi.php" class="tombol-kembali">
            <img src="../asets/img/back.png" alt="Kembali">
        </a>
        <h1 class="teks-judul">TAMBAH ARTIKEL</h1>
    </header>

    <main class="container">
        <div class="kotak-isian">
            <div class="info-grup">
                <h2 class="sub-judul">Buat Artikel Baru</h2>
                <p class="deskripsi">Isi formulir di bawah ini untuk menambah edukasi baru bagi siswa.</p>
            </div>

            <form method="POST" class="formulir-edit">
                <div class="grup-input">
                    <label>Judul Artikel</label>
                    <input type="text" name="judul" placeholder="Masukkan judul artikel" required>
                </div>

                <div class="grup-input">
                    <label>Kategori Edukasi</label>
                    <input type="text" name="kategori_edu" placeholder="Contoh: Kesehatan Mental" required>
                </div>

                <div class="grup-input">
                    <label>Isi Lengkap Artikel</label>
                    <textarea name="isi" rows="10" placeholder="Tulis konten artikel di sini..." required></textarea>
                </div>

                <div class="grup-input">
                    <label>Sumber Informasi</label>
                    <input type="text" name="sumber" placeholder="Masukkan nama sumber/penulis" required>
                </div>

                <div class="area-aksi">
                    <button type="submit" name="simpan_data" class="tombol-simpan">Simpan Artikel</button>
                    <a href="edukasi.php" class="tombol-batal">Batal</a>
                </div>
            </form>
        </div>

        <?php include '../includes/footer.php'; ?>
    </main>

</body>
</html>