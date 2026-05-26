<?php
session_start();
include '../includes/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// ngeck pk dari cerita
$cek_kolom = mysqli_query($koneksi, "SHOW KEYS FROM cerita WHERE Key_name = 'PRIMARY'");
$nama_kolom_id = ($cek_kolom && mysqli_num_rows($cek_kolom) > 0) ? mysqli_fetch_assoc($cek_kolom)['Column_name'] : 'id'; 

// proses nyimpan
if (isset($_POST['kirim_respon'])) {
    $id_cerita = mysqli_real_escape_string($koneksi, $_POST['id_cerita']);
    $isi_respon = mysqli_real_escape_string($koneksi, $_POST['isi_respon']);
    
    // ngcek apakah curhatan ini sudah pernah direspon sebelumnya
    $q_cek = mysqli_query($koneksi, "SELECT * FROM tanggapan WHERE id_cerita = '$id_cerita'");
    if (mysqli_num_rows($q_cek) > 0) {
        // kalau ada nnti ke update
        $query_aksi = "UPDATE tanggapan SET isi_tanggapan = '$isi_respon', id_user = '$id_user', tanggal_tanggapan = NOW() WHERE id_cerita = '$id_cerita'";
    } else {
        // Jkalau belum maka nambah baru
        $query_aksi = "INSERT INTO tanggapan (id_cerita, id_user, isi_tanggapan, tanggal_tanggapan) VALUES ('$id_cerita', '$id_user', '$isi_respon', NOW())";
    }
    
    if (mysqli_query($koneksi, $query_aksi)) {
        echo "<script>alert('Respon berhasil disimpan!'); window.location='realtime.php';</script>";
        exit;
    }
}

// logika mood
$filter_mood = isset($_GET['mood']) ? mysqli_real_escape_string($koneksi, $_GET['mood']) : 'Semua';
$counts = ['Semua' => 0, 'Senang' => 0, 'Sedih' => 0, 'Cemas' => 0, 'Marah' => 0, 'Lelah' => 0, 'Biasa' => 0];

// hitung total semua cerita
$q_all = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM cerita");
if ($q_all) $counts['Semua'] = mysqli_fetch_assoc($q_all)['total'];

// hitung per masing mood
$q_mood = mysqli_query($koneksi, "SELECT mood, COUNT(*) as total FROM cerita GROUP BY mood");
if ($q_mood) {
    while ($r = mysqli_fetch_assoc($q_mood)) {
        $m_name = ucfirst(strtolower($r['mood']));
        if (array_key_exists($m_name, $counts)) $counts[$m_name] = $r['total'];
    }
}

// Ambil data cerita berdasarkan filter
$query_realtime = ($filter_mood != 'Semua') ? "SELECT * FROM cerita WHERE mood = '$filter_mood' ORDER BY $nama_kolom_id DESC" : "SELECT * FROM cerita ORDER BY $nama_kolom_id DESC";
$sql_realtime = mysqli_query($koneksi, $query_realtime);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realtime Pantauan - SKOMDA MENTAL CARE</title>
    <link rel="stylesheet" href="../asets/css includes/nav_atas2.css">
    <link rel="stylesheet" href="../asets/css2/realtime.css">
    
</head>
<body>

    <?php include '../includes/nav_atas2.php'; ?>

    <div class="container-realtime">
        
        <div class="header-top-bar">
            <a href="dashboard.php" class="back-arrow-btn">
                <img src="../asets/img/back.png" alt="Kembali">
            </a>
            <h2>REALTIME</h2>
        </div>

        <div class="banner-gradient-box">
            <div class="live-indicator-badge">
                <span class="blinking-dot"></span> PANTAUAN BERJALAN
            </div>
            <h1>Kondisi Perasaan & Kisah Siswa</h1>
            <p>Halaman ini menampilkan curhatan siswa terbaru diakumulasikan dengan emosi/perasaan yang mereka tentukan saat bercerita.</p>
        </div>

        <div class="filter-wrapper-card">
            <span class="filter-instruction">SARING BERDASARKAN PERASAAN SISWA:</span>
            <div class="filter-flex-layout">
                <?php foreach($counts as $mood_name => $total_count): ?>
                    <a href="realtime.php?mood=<?= $mood_name; ?>" class="btn-mood-item <?= $filter_mood == $mood_name ? 'active' : ''; ?>">
                        <img src="../asets/img/<?= strtolower($mood_name); ?>.png" class="img-icon-mood" onerror="this.style.display='none'">
                        <?= $mood_name == 'Semua' ? 'Semua Cerita' : $mood_name; ?> (<?= $total_count; ?>)
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="main-feeds-stream">
            <?php 
            if ($sql_realtime && mysqli_num_rows($sql_realtime) > 0):
                while ($row = mysqli_fetch_assoc($sql_realtime)):
                    $id_c = $row[$nama_kolom_id];
                    
                    // ambil tanggapan jika sudah ada
                    $q_tanggapan = mysqli_query($koneksi, "SELECT * FROM tanggapan WHERE id_cerita = '$id_c'");
                    $tanggapan = mysqli_fetch_assoc($q_tanggapan);
                    $sudah_respon = !empty($tanggapan);
            ?>
                <div class="feed-post-card">
                    <div class="feed-card-header">
                        <div class="header-left-side">
                            <span class="icon-avatar-anonim">👤</span>
                            <span class="text-name-anonim">ANONIM</span>
                            <span class="badge-status-respon <?= $sudah_respon ? 'responded' : 'pending'; ?>">
                                <?= $sudah_respon ? 'SUDAH DIRESPOND' : 'MENUNGGU MASUKAN BK'; ?>
                            </span>
                        </div>
                        <span class="text-timestamp-post">
                            <?= isset($row['tanggal']) ? $row['tanggal'] : '13/5/2026, 21.22.03'; ?>
                        </span>
                    </div>

                    <div class="feed-card-body-text">
                        <p>"<?= htmlspecialchars($row['isi_cerita']); ?>"</p>
                    </div>

                    <div class="feed-card-comment-section">
                        <h4 class="comment-section-title">KOMENTAR & HUBUNGAN TERJALIN (<?= $sudah_respon ? '1' : '0'; ?>)</h4>
                        <?php if ($sudah_respon): ?>
                            <div class="logged-comment-box">
                                <p><strong>Guru BK:</strong> <?= htmlspecialchars($tanggapan['isi_tanggapan']); ?></p>
                            </div>
                        <?php else: ?>
                            <p class="text-no-comment">Belum ada diskusi atau tanggapan pada curhat ini.</p>
                        <?php endif; ?>
                    </div>

                    <form action="realtime.php" method="POST" class="inline-response-form">
                        <input type="hidden" name="id_cerita" value="<?= $id_c; ?>">
                        <div class="input-action-group">
                            <input type="text" name="isi_respon" class="input-text-field-inline" 
                                   value="<?= $sudah_respon ? htmlspecialchars($tanggapan['isi_tanggapan']) : ''; ?>" 
                                   placeholder="Ketik respon dukungan hangat sebagai Guru BK..." required>
                            <button type="submit" name="kirim_respon" class="btn-submit-action-inline">
                                <?= $sudah_respon ? 'Perbarui Respon' : 'Kirim Masukan'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            <?php 
                endwhile;
            else:
                echo "<div class='empty-state-card-box'>Tidak ada curhatan siswa dengan kategori perasaan ini.</div>";
            endif;
            ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>