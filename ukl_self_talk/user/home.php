<?php
session_start();
include '../includes/koneksi.php';

// ========================================================
// menyesuikan kalaau ada yg blm login nnti suruh balik kee login
// ========================================================
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'siswa') {
    session_destroy();
    header("Location: ../login.php?role=siswa");
    exit;
}

$id_user = $_SESSION['id_user'];

// --- simpan emotional---
if (isset($_POST['kirim_emosi'])) {
    $mood    = mysqli_real_escape_string($koneksi, $_POST['emosi']);
    $catatan = mysqli_real_escape_string($koneksi, $_POST['cerita']);

    if (!empty($mood)) {
        $sql = "INSERT INTO emotion_logs (user_id, mood, catatan, tanggal) 
                VALUES ('$id_user', '$mood', '$catatan', NOW())";
        
        if (mysqli_query($koneksi, $sql)) {
            echo "<script>alert('Perasaan berhasil disimpan!'); window.location='home.php';</script>";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "<script>alert('Pilih mood kamu dulu!');</script>";
    }
}

// --- simpan curhatan---
if (isset($_POST['post_curhat'])) {
    $isi_cerita = mysqli_real_escape_string($koneksi, $_POST['isi_curhat']);
    $is_anonim = 1; 

    if (!empty($isi_cerita)) {
        $sql = "INSERT INTO cerita (user_id, isi_cerita, anonim, created_at) 
                VALUES ('$id_user', '$isi_cerita', '$is_anonim', NOW())";
        
        if (mysqli_query($koneksi, $sql)) {
            echo "<script>alert('Curhat anonim berhasil diposting!'); window.location='home.php';</script>";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Siswa</title>
    <?php include '../includes/nav_atas.php'; ?>
    <link rel="stylesheet" href="../asets/css includes/nav_atas.css">
    <link rel="stylesheet" href="../asets/css/home.css">
</head>
<body>


    <div class="container">
        <div class="awal">
            <h2>Halo, Siswa Skomda! </h2>
            <p style="font-size: 13px; color: #636e72;">Gunakan waktu sejenak untuk mengecek kondisi emosionalmu hari ini.</p>
        </div>

        <form action="" method="POST" class="emotional">
            <div style="font-weight: 600;">Emotional Check-in</div>
            
            <input type="hidden" name="emosi" id="inputEmosi" required>

            <div class="mood">
                <div class="mood-btn" onclick="pilihEmosi(this, 'Senang')">
                    <img src="../asets/img/senang.png">
                    <span>SENANG</span>
                </div>

                <div class="mood-btn" onclick="pilihEmosi(this, 'Sedih')">
                    <img src="../asets/img/sedih.png">
                    <span>SEDIH</span>
                </div>

                <div class="mood-btn" onclick="pilihEmosi(this, 'Cemas')">
                    <img src="../asets/img/cemas.png">
                    <span>CEMAS</span>
                </div>

                <div class="mood-btn" onclick="pilihEmosi(this, 'Marah')">
                    <img src="../asets/img/marah.png">
                    <span>MARAH</span>
                </div>

                <div class="mood-btn" onclick="pilihEmosi(this, 'Lelah')">
                    <img src="../asets/img/lelah.png">
                    <span>LELAH</span>
                </div>

                <div class="mood-btn" onclick="pilihEmosi(this, 'Biasa')">
                    <img src="../asets/img/biasa.png">
                    <span>BIASA</span>
                </div>
            </div>
        
            <textarea name="cerita" placeholder="Tuliskan di sini secara bebas..."></textarea>
            <button type="submit" name="kirim_emosi" class="btn-kirim">Kirim Perasaan</button>
        </form>

        <form action="" method="POST" class="curhat">
            <div style="font-weight: 600;"> Curhat Anonim</div>
            <textarea name="isi_curhat" placeholder="Bagikan ceritamu secara anonim di sini..." required></textarea>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                <span style="font-size: 10px; color: #b2bec3;">🔒 IDENTITAS AMAN</span>
                <button type="submit" name="post_curhat" class="btn-posting">Posting</button>
            </div>
        </form>
    </div>



    <script>
        function pilihEmosi(elemen, nilaiEmosi) {
            
            const semuaTombol = document.querySelectorAll('.mood-btn');
            
            
            semuaTombol.forEach(function(btn) {
                btn.classList.remove('active');
            });

            // menampilkn kelas aktif saat emoji yg ku klik saja
            elemen.classList.add('active');
            
            // masukan teks emosi
            document.getElementById('inputEmosi').value = nilaiEmosi;
        }
    </script>

    <?php
    include '../includes/nav.php';    
    include '../includes/footer.php';  
    ?>

</body>
</html>