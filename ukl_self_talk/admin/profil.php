<?php
session_start();
include '../includes/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_admin = $_SESSION['id_user'];

// --- update profil admin ---
if (isset($_POST['update_profil'])) {
    $nama_baru  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email_baru = mysqli_real_escape_string($koneksi, $_POST['email']);
    
    // cek foto admin
    $check_q = mysqli_query($koneksi, "SELECT foto FROM admin WHERE admin_id = '$id_admin'");
    $current_admin = mysqli_fetch_assoc($check_q);
    $foto_sekarang = $current_admin['foto'];

    // update foto
    if (!empty($_FILES['foto']['name'])) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        
        $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $foto_baru = "avatar_admin_" . $id_admin . "_" . time() . "." . $ekstensi;
        $target_dir = "../asets/img/";

        if (move_uploaded_file($tmp_file, $target_dir . $foto_baru)) 
            
            {
            // hapus foto lama jika ada
            if (!empty($foto_sekarang) && file_exists($target_dir . $foto_sekarang)) {
                unlink($target_dir . $foto_sekarang);
            }
            $query_update = "UPDATE admin SET nama = '$nama_baru', email = '$email_baru', foto = '$foto_baru' WHERE admin_id = '$id_admin'";
        } else {
            $query_update = "UPDATE admin SET nama = '$nama_baru', email = '$email_baru' WHERE admin_id = '$id_admin'";
        }
    } else {
        $query_update = "UPDATE admin SET nama = '$nama_baru', email = '$email_baru' WHERE admin_id = '$id_admin'";
    }

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Profil Admin berhasil diperbarui!'); window.location='profil_admin.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}

// mengambil data admin terbaru
$query = "SELECT * FROM admin WHERE admin_id = '$id_admin'";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);

$foto_profil = !empty($user['foto']) ? '../asets/img/' . $user['foto'] : '../asets/img/default-avatar.png';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - Guru BK</title>
    <link rel="stylesheet" href="../asets/css2/profil.css">
</head>
<body>

<div class="container">
    
    <div class="header-profil">
        <a href="dashboard.php" class="btn-back">
            <img src="../asets/img/back.png" width="20" alt="Kembali">
        </a>
        <h2>Profil Admin</h2>
        <div style="width: 20px;"></div> 
    </div>

    <div class="utama">
        <div class="wrapper-foto">
            <?php if(!empty($user['foto'])): ?>
                <img src="<?= $foto_profil; ?>" class="gambar" alt="Foto Profil">
            <?php else: ?>
                <div class="avatar-inisial">
                    <?= strtoupper(substr($user['nama'] ?? 'A', 0, 1)); ?>
                </div>
            <?php endif; ?>

            <button class="btn-edit-foto" onclick="bukaModalEdit()">
                <img src="../asets/img/kamera.png" width="14" alt="Edit">
            </button>
        </div>
        <h3><?= htmlspecialchars($user['nama'] ?? 'Nama Admin'); ?></h3>
        <p class="sub-kelas">Guru Bimbingan Konseling</p> 
    </div>

    
    <div class="identitas">
        <div class="label-seksi">IDENTITAS GURU</div>
        
        <div class="baris-info">
            <div class="icon-info-box blue-soft">
                <img src="../asets/img/nip.png" width="18" alt="NIP">
            </div>
            <div class="detail-teks">
                <span>NOMOR INDUK PEGAWAI (NIP)</span>
                <strong><?= htmlspecialchars($user['nip'] ?? '-'); ?></strong>
            </div>
        </div>
        
        <div class="baris-info">
            <div class="icon-info-box blue-soft">
                <img src="../asets/img/emailadmin.png" width="18" alt="Email">
            </div>
            <div class="detail-teks">
                <span>EMAIL INSTITUSI</span>
                <strong class="email-teks"><?= htmlspecialchars($user['email'] ?? '-'); ?></strong>
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
            <h3>Ubah Profil Admin</h3>
            <span class="btn-close-modal" onclick="tutupModalEdit()">&times;</span>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="modal-input-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($user['nama'] ?? ''); ?>" required>
            </div>
            <div class="modal-input-group">
                <label>Email Resmi</label>
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
    window.onclick = function(event) {
        const modal = document.getElementById('modalEditProfil');
        if (event.target == modal) {
            modal.classList.remove('tampil');
        }
    }
</script>

</body>
</html>