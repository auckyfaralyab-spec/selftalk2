<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_log = $_SESSION['id_user']; 

// mengambil cerita dan m mengambil mood dari data emotional jika ada relasinya
$query_cerita = "SELECT c.*, COALESCE(e.mood, 'biasa') AS status_mood 
                 FROM cerita c 
                 LEFT JOIN emotion_logs e ON c.user_id = e.user_id AND DATE(c.created_at) = DATE(e.tanggal)
                 WHERE c.user_id = '$id_log' 
                 ORDER BY c.cerita_id DESC";
$result_cerita = mysqli_query($koneksi, $query_cerita);

// mengambil data laporan bullying
$query_laporan = "SELECT lapor.*, users.nama 
                  FROM lapor 
                  JOIN users ON lapor.id_user = users.id_user 
                  WHERE lapor.id_user = '$id_log' 
                  ORDER BY lapor.lapor_id DESC";
$result_laporan = mysqli_query($koneksi, $query_laporan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Jejakmu - SelfTalk Skomda</title>
    <link rel="stylesheet" href="../asets/css includes/nav_atas.css">
    <link rel="stylesheet" href="../asets/css/riwayat.css">
    
</head>
<body>
<?php include '../includes/nav_atas.php'; ?>

<div class="container">

    <div class="header-riwayat">
        <h1>Riwayat Jejakmu <img src="../asets/img/riwayat.png" alt="Ikon" width="24"></h1>
        <p>Melihat kembali perjalanan dan keberanianmu untuk bersuara.</p>
    </div>

    <div class="title">
        <img src="../asets/img/logoregis.png" alt="Ikon" width="18"> Cerita yang Kamu Bagikan
    </div>

    <div class="riwayat-konten">
        <?php if ($result_cerita && mysqli_num_rows($result_cerita) > 0): ?>
            <?php while ($cerita = mysqli_fetch_assoc($result_cerita)): ?>
                <?php 
                    $id_c = $cerita['cerita_id'];
                    // ambil tanggapan  dari BK
                    $q_res = mysqli_query($koneksi, "SELECT * FROM tanggapan WHERE id_cerita = '$id_c'");
                    $res = mysqli_fetch_assoc($q_res);
                    
                
                    $raw_mood = !empty($cerita['status_mood']) ? trim($cerita['status_mood']) : 'biasa';
                    $file_mood = strtolower($raw_mood);
                    $label_mood = ucfirst($file_mood);
                ?>
                <div class="card-cerita">
                    <div class="card-cerita-top">
                        <div class="mood-container">
                            <img src="../asets/img/<?php echo $file_mood; ?>.png" class="img-mood-status" onerror="this.src='../asets/img/biasa.png'">
                            <span class="mood-text-label"><?php echo htmlspecialchars($label_mood); ?></span>
                        </div>
                        <div class="waktu">
                            <?php echo isset($cerita['created_at']) ? date('d/m/Y, H.i', strtotime($cerita['created_at'])) : 'Baru saja'; ?>
                        </div>
                    </div>
                    
                    <p class="isi-curhat-teks">"<?php echo htmlspecialchars($cerita['isi_cerita']); ?>"</p>

                    <div class="sub-box-balasan-bk">
                        <h5 class="title-balasan-bk">
                            <img src="../asets/img/logoregis.png" alt="" width="14"> RESPON HANGAT GURU BK:
                        </h5>
                        <?php if ($res): ?>
                            <p class="teks-balasan-bk"><?php echo htmlspecialchars($res['isi_tanggapan']); ?></p>
                        <?php else: ?>
                            <p class="teks-balasan-kosong">Guru BK sedang membaca ceritamu, tunggu sebentar ya...</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="pesan-kosong">Belum ada cerita yang dibagikan.</div>
        <?php endif; ?>
    </div>

    <div class="title">
        <img src="../asets/img/lapor.png" alt="Ikon" width="18"> Status Laporan Bullying
    </div>

    <div class="riwayat-konten">
        <?php if ($result_laporan && mysqli_num_rows($result_laporan) > 0): ?>
            <?php while ($lapor = mysqli_fetch_assoc($result_laporan)): ?>
                <div class="card-laporan">
                    <div class="info-lapor">
                        <h4><?php echo htmlspecialchars($lapor['kategori']); ?></h4>
                        <p>
                            <img src="../asets/img/user.png" alt="" width="14"> 
                            Anonym / <?php echo htmlspecialchars($lapor['nama']); ?>
                        </p>
                        <span><?php echo htmlspecialchars($lapor['tgl_kejadian']); ?></span>
                    </div>
                    <div class="badge-status <?php echo strtolower($lapor['status']); ?>">
                        <?php echo htmlspecialchars($lapor['status']); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="pesan-kosong">Belum ada riwayat laporan pengaduan.</div>
        <?php endif; ?>
    </div>

</div>

<?php 
include '../includes/nav.php'; 
include '../includes/footer.php'; 
?>

</body>
</html>