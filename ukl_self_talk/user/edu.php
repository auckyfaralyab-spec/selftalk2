<?php
// pengaman
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include '../includes/koneksi.php'; 

// pencarian artikel
$keyword = "";
if (isset($_GET['cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['cari']);
}

// mengabil data artikel dari database sesuai 
if (!empty($keyword)) {
    $query = "SELECT * FROM edukasi WHERE judul LIKE '%$keyword%' OR isi LIKE '%$keyword%' ORDER BY edukasi_id DESC";
} else {
    $query = "SELECT * FROM edukasi ORDER BY edukasi_id DESC";
}

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edukasi & Self-Care Center - SelfTalk Skomda</title>
    
    <link rel="stylesheet" href="../asets/css includes/nav_atas.css">
    <link rel="stylesheet" href="../asets/css/edu.css">
</head>
<body>
<?php include '../includes/nav_atas.php'; ?>


<div class="container">
    <div class="header-edukasi">
        <h1>Edukasi & Self-Care Center 📖</h1>
        <p>Yuk, cari dan baca artikel kesehatan mental terpercaya pilihan Admin untuk mendukung hari-harimu di sekolah.</p>
    </div>

    <form action="" method="GET" class="search-form">
        <input type="text" name="cari" value="<?= htmlspecialchars($keyword); ?>" class="search-input" placeholder="Ketik kata kunci (misal: Overthinking, Cemas, Tidur)...">
        <a href="edukasi.php" class="btn-semua">Semua</a>
    </form>

    <div class="pustaka-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($edu = mysqli_fetch_assoc($result)): ?>
                
                <div class="card-artikel">
                    <span class="badge-kategori"><?= htmlspecialchars($edu['kategori_edu']); ?></span>
                    <h3 class="judul-artikel"><?= htmlspecialchars($edu['judul']); ?></h3>
                    <p class="isi-artikel"><?= nl2br(htmlspecialchars($edu['isi'])); ?></p>
                    
                    <div class="sumber-artikel">
                         Sumber resmi: <span class="sumber-link"><?= htmlspecialchars($edu['sumber']); ?></span>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            
            <div class="pustaka-kosong">
                <h3>Artikel Tidak Ditemukan 🔍</h3>
                <p>Maaf, kata kunci "<b><?= htmlspecialchars($keyword); ?></b>" tidak cocok dengan koleksi pustaka kami.</p>
                <a href="edukasi.php" class="btn-reset">Lihat Semua Artikel</a>
            </div>

        <?php endif; ?>
    </div>

</div>

<?php 

include '../includes/nav.php'; 
include '../includes/footer.php'; 
?>

</body>
</html>