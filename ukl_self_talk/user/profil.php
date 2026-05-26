<?php
session_start();
include '../includes/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_siswa = $_SESSION['id_user'];

// --- update profil ---
if (isset($_POST['update_profil'])) {
    $nama_baru  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email_baru = mysqli_real_escape_string($koneksi, $_POST['email']);
    
    // info user  untuk cek foto lama
    $check_q = mysqli_query($koneksi, "SELECT foto_profil FROM users WHERE id_user = '$id_siswa'");
    $current_user = mysqli_fetch_assoc($check_q);
    $foto_sekarang = $current_user['foto_profil'];

    // kelola update profil
    if (!empty($_FILES['foto']['name'])) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        
        
        $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $foto_baru = "avatar_" . $id_siswa . "_" . time() . "." . $ekstensi;
        $target_dir = "../asets/img/";

        if (move_uploaded_file($tmp_file, $target_dir . $foto_baru)) 
            
            {
            // hps file lama 
            if (!empty($foto_sekarang) && file_exists($target_dir . $foto_sekarang)) {
                unlink($target_dir . $foto_sekarang);
            }
            $query_update = "UPDATE users SET nama = '$nama_baru', email = '$email_baru', foto_profil = '$foto_baru' WHERE id_user = '$id_siswa'";
        } else {
            $query_update = "UPDATE users SET nama = '$nama_baru', email = '$email_baru' WHERE id_user = '$id_siswa'";
        }
    } else {
        $query_update = "UPDATE users SET nama = '$nama_baru', email = '$email_baru' WHERE id_user = '$id_siswa'";
    }

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profil.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}


// mengambil data user terbaru
$query = "SELECT * FROM users WHERE id_user = '$id_siswa'";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);

$foto_profil = !empty($user['foto_profil']) ? '../asets/img/' . $user['foto_profil'] : '../asets/img/default-avatar.png';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa</title>
    <link rel="stylesheet" href="../asets/css/profil.css">
</head>
<body>

<div class="container">
    
    <div class="header-profil">
        <a href="home.php" class="btn-back">
            <img src="../asets/img/back.png" width="20" alt="Kembali">
        </a>
        <h2>Profil Siswa</h2>
        <div style="width: 20px;"></div> 
    </div>

    <div class="utama">
        <div class="wrapper-foto">
            <?php if(!empty($user['foto_profil'])): ?>
                <img src="<?= $foto_profil; ?>" class="gambar" alt="Foto Profil">
            <?php else: ?>
                <div class="avatar-inisial">
                    <?= strtoupper(substr($user['nama'] ?? 'S', 0, 1)); ?>
                </div>
            <?php endif; ?>
            

            <button class="btn-edit-foto" onclick="bukaModalEdit()">
                <img src="../asets/img/kamera.png" width="14" alt="Edit">
            </button>
        </div>
        <h3><?= htmlspecialchars($user['nama'] ?? 'Nama Siswa'); ?></h3>
        <p class="sub-kelas">Siswa Kelompok Belajar</p> 
    </div>


    <div class="identitas">
        <div class="label-seksi">IDENTITAS SISWA</div>
        

        <div class="baris-info">
            <div class="icon-info-box merah-soft">
                <img src="../asets/img/nis.png" width="18" alt="NIS">
            </div>
            <div class="detail-teks">
                <span>NOMOR INDUK SISWA (NIS)</span>
                <strong><?= htmlspecialchars($user['nis'] ?? '-'); ?></strong>
            </div>
        </div>


        
        <div class="baris-info">
            <div class="icon-info-box merah-soft">
                <img src="../asets/img/email.png" width="18" alt="Email">
            </div>
            <div class="detail-teks">
                <span>EMAIL INSTITUSI</span>
                <strong class="email-teks"><?= htmlspecialchars($user['email'] ?? '-'); ?></strong>
            </div>
        </div>

        <div class="baris-info">
            <div class="icon-info-box merah-soft">
                <img src="../asets/img/kelas.png" width="18" alt="Kelas">
            </div>
            <div class="detail-teks">
                <span>KELAS SEKARANG</span>
                <strong><?= htmlspecialchars($user['kelas'] ?? 'X SIJA 2'); ?></strong>
            </div>
        </div>
    </div>



    <div class="menu-profil">
        <a href="../logout.php" class="item-menu-link keluar-aplikasi">
            <div class="kiri-menu">
                <div class="bg-icon-menu merah-logout-bg">
                    <img src="../asets/img/logout.png" width="18" alt="Logout">
                </div>
                <div class="detail-menu">
                    <h4 class="teks-logout">Keluar Sesi Aplikasi</h4>
                </div>
            </div>
        </a>
    </div>
</div>


<div id="modalEditProfil" class="modal-layout">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Ubah Profil</h3>
            <span class="btn-close-modal" onclick="tutupModalEdit()">&times;</span>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="modal-input-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($user['nama'] ?? ''); ?>" required>
            </div>
            <div class="modal-input-group">
                <label>Email Siswa</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>" required>
            </div>
            <div class="modal-input-group">
                <label>Foto Profil Baru (Opsional)</label>
                <input type="file" name="foto" accept="image/*">
            </div>
            <button type="submit" name="update_profil" class="btn-simpan-modal">Simpan Perubahan</button>
        </form>
    </div>
</div>



<script>

    function bukaModalEdit() {
        document.getElementById('modalEditProfil').classList.add('tampil');
    }
    function tutupModalEdit() {
        document.getElementById('modalEditProfil').classList.remove('tampil');
    }
    // Menutup modal jika area luar box diklik
    window.onclick = function(event) {
        const modal = document.getElementById('modalEditProfil');
        if (event.target == modal) {
            modal.classList.remove('tampil');
        }
    }
</script>



</body>
</html>