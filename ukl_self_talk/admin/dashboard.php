
<?php
session_start();
include '../includes/koneksi.php';


if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}


$id_user = $_SESSION['id_user'];

// deteksi id admin otomatis
$cek_kolom = mysqli_query($koneksi, "SHOW KEYS FROM cerita WHERE Key_name = 'PRIMARY'");
if ($cek_kolom && mysqli_num_rows($cek_kolom) > 0) {
    $data_kolom = mysqli_fetch_assoc($cek_kolom);
    $nama_kolom_id = $data_kolom['Column_name'];
} else {
    $nama_kolom_id = 'id'; 
}


// logika moderasi hapus curhat
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    
    $query_hapus = "DELETE FROM cerita WHERE $nama_kolom_id = '$id_hapus'";
    if (mysqli_query($koneksi, $query_hapus)) {
        echo "<script>alert('Postingan berhasil dihapus!'); window.location='dashboard.php';</script>";
    }
}

// ==========================================================================
// sikronasi data
// ==========================================================================

// menghitung data curhat
$q_curhat = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM cerita");
$d_curhat = mysqli_fetch_assoc($q_curhat);

// menghitung data laporan masuk
$q_lapor_masuk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM lapor WHERE status != 'Selesai' OR status IS NULL");
$d_lapor_masuk = mysqli_fetch_assoc($q_lapor_masuk);

// menghitung data konseling
$q_konsel_masuk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM konseling WHERE status_jadwal != 'Selesai' OR status_jadwal IS NULL");
$d_konsel_masuk = ($q_konsel_masuk) ? mysqli_fetch_assoc($q_konsel_masuk) : ['total' => 0];

// menggabungkan laporan dn konseling
$d_laporan['total'] = $d_lapor_masuk['total'] + $d_konsel_masuk['total'];


// meghitung data laporan selesai
$q_lapor_selesai = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM lapor WHERE status = 'Selesai'");
$d_lapor_selesai_data = mysqli_fetch_assoc($q_lapor_selesai);

// menghitug data konseling selesai
$q_konsel_selesai = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM konseling WHERE status_jadwal = 'Selesai'");
$d_konsel_selesai_data = ($q_konsel_selesai) ? mysqli_fetch_assoc($q_konsel_selesai) : ['total' => 0];

//menggabungkan laporan dn konseling selesai
$d_laporan_selesai['total'] = $d_lapor_selesai_data['total'] + $d_konsel_selesai_data['total'];


// data dari edukasi
$q_edukasi = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM edukasi"); 
$d_edukasi = mysqli_fetch_assoc($q_edukasi);


// ==========================================================================
//  perhitungan mood siswa datanya
// ==========================================================================
$q_total_mood = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM emotion_logs");
$d_total_mood = mysqli_fetch_assoc($q_total_mood);
$total_mood = ($d_total_mood['total'] > 0) ? $d_total_mood['total'] : 1; 

$q_cemas = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM emotion_logs WHERE mood='Cemas'");
$d_cemas = mysqli_fetch_assoc($q_cemas);
$persen_cemas = round(($d_cemas['total'] / $total_mood) * 100);

$q_sedih = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM emotion_logs WHERE mood='Sedih'");
$d_sedih = mysqli_fetch_assoc($q_sedih);
$persen_sedih = round(($d_sedih['total'] / $total_mood) * 100);

$q_senang = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM emotion_logs WHERE mood='Senang'");
$d_senang = mysqli_fetch_assoc($q_senang);
$persen_senang = round(($d_senang['total'] / $total_mood) * 100);




// menentukn mood dominan
$mood_array = ['Cemas' => $persen_cemas, 'Sedih' => $persen_sedih, 'Senang' => $persen_senang];
arsort($mood_array); 
$mood_dominan = key($mood_array);

// ambil data feed curhat terbaru
$query_feed = "SELECT * FROM cerita ORDER BY $nama_kolom_id DESC LIMIT 2";
$result_feed = mysqli_query($koneksi, $query_feed);
?>

  
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SKOMDA MENTAL CARE</title>
    <link rel="stylesheet" href="../asets/css includes/nav_atas2.css">
    <link rel="stylesheet" href="../asets/css2/dashboard.css">
</head>
<body>

   <?php include '../includes/nav_atas2.php'; ?>

    <div class="container">
        
        <div class="awal">
            <h2>Selamat Pagi, Guru BK!</h2>
            <p>Berikut adalah ringkasan aktivitas siswa hari ini.</p>
        </div>

        <div class="curhat">
            
            <a href="curhat.php" class="stat-card">
                <img src="../asets/img/curhat.png" class="card-icon-img" alt="Curhat">
                <span class="stat-label">Total Curhat</span>
                <span class="stat-value"><?= $d_curhat['total']; ?></span>
            </a>

            <a href="laporan_masuk.php" class="laporan">
                <img src="../asets/img/laporanmasuk.png" class="card-icon-img" alt="Laporan">
                <span class="stat-label">Laporan Masuk</span>
                <span class="stat-value"><?= $d_laporan['total']; ?></span>
            </a>
            
            <a href="laporan_selesai.php" class="laporan-selesai">
                <img src="../asets/img/laporanselesai.png" class="card-icon-img" alt="Selesai">
                <span class="stat-label">Laporan Selesai</span>
                <span class="stat-value"><?= $d_laporan_selesai['total']; ?></span>
            </a>
            
            <a href="edukasi.php" class="edukasi">
                <img src="../asets/img/edukasi.png" class="card-icon-img" alt="Edukasi">
                <span class="stat-label">Konten Edukasi</span>
                <span class="stat-value"><?= $d_edukasi['total']; ?></span>
            </a>

        </div>

        <div class="mood">
            <div class="section-header-row">
                <h3 class="section-title">Sebaran Mood Siswa</h3>
                <a href="realtime.php" class="badge-realtime" style="text-decoration: none; cursor: pointer;">REAL-TIME</a>
            </div>
            
            
            <div class="mood-content-split">
                <div class="mood-bars-list">
                    
                    <div class="mood-bar-item">
                        <div class="mood-bar-info">
                            <span class="mood-label-with-img">
                                <img src="../asets/img/lelah.png" class="mood-inline-img" alt="Cemas"> CEMAS
                            </span>
                            <span><?= $persen_cemas; ?>%</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-fill fill-cemas" style="width: <?= $persen_cemas; ?>%;"></div>
                        </div>
                    </div>
                    
                    <div class="mood-bar-item">
                        <div class="mood-bar-info">
                            <span class="mood-label-with-img">
                                <img src="../asets/img/sedih.png" class="mood-inline-img" alt="Sedih"> SEDIH
                            </span>
                            <span><?= $persen_sedih; ?>%</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-fill fill-sedih" style="width: <?= $persen_sedih; ?>%;"></div>
                        </div>
                    </div>
                    
                    <div class="mood-bar-item">
                        <div class="mood-bar-info">
                            <span class="mood-label-with-img">
                                <img src="../asets/img/senang.png" class="mood-inline-img" alt="Senang"> SENANG
                            </span>
                            <span><?= $persen_senang; ?>%</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-fill fill-senang" style="width: <?= $persen_senang; ?>%;"></div>
                        </div>
                    </div>
                    
                </div>
                

                <div class="mood-dominan-card">
                    <span class="mood-dominan-title">Mood Dominan</span>
                    <div class="mood-dominan-text">Siswa cenderung <strong><?= $mood_dominan; ?></strong> hari ini.</div>
                </div>
            </div>
        </div>

        <div>
            <div class="moderasi-section-header">
                <h3 class="section-title">Moderasi Feeds (Terbaru)</h3>
                <a href="kelola_postingan.php" class="btn-link-kelola">Kelola Postingan</a>
            </div>
            <br>
            
            
            <div class="feeds-moderasi-list">
                <?php 
                if (mysqli_num_rows($result_feed) > 0):
                    while ($feed = mysqli_fetch_assoc($result_feed)):
                        $id_c = $feed[$nama_kolom_id]; 
                ?>
                    <div class="feed-box-card">
                        <div class="feed-card-top">
                            <span class="lbl-anon">ANONIM</span>
                            <p class="feed-card-body-text">"<?= htmlspecialchars($feed['isi_cerita']); ?>"</p>
                        </div>
                        <div class="feed-card-bottom">
                            <span>Baru saja</span>
                            <a href="dashboard.php?aksi=hapus&id=<?= $id_c; ?>" class="btn-action-delete" onclick="return confirm('Hapus curhatan ini?');">Hapus</a>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                    echo "<p style='grid-column: span 2; text-align:center; color:#94a3b8; padding:20px;'>Belum ada cerita publik anonim dari siswa hari ini.</p>";
                endif;
                ?>
            </div>
        </div>

        <?php include '../includes/footer.php'; ?>

    </div>

</body>
</html>

```