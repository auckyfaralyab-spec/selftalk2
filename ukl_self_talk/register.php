<?php
include 'includes/koneksi.php';

$pesan = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $nis      = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $kelas    = mysqli_real_escape_string($koneksi, $_POST['kelas']); // Ambil data kelas
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = 'user'; 

    // mngecek email ,nis
    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email' OR nis = '$nis'");
    
    if (mysqli_num_rows($cek_user) > 0) {
        $pesan = "⚠️ Email atau NIS sudah terdaftar!";
        $status = "gagal";
    } else {


        // kelas
        $query = "INSERT INTO users (nama, email, nis, kelas, password, role) 
                  VALUES ('$nama', '$email', '$nis', '$kelas', '$password', '$role')";
        
        if (mysqli_query($koneksi, $query)) {
            $pesan = "Berhasil! Silakan masuk ke akunmu.";
            $status = "berhasil";
        } else {
            $pesan = " Ada masalah pas daftar: " . mysqli_error($koneksi);
            $status = "gagal";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - SelfTalk</title>

    <link rel="stylesheet" href="asets/css/register.css">
</head>


<body class="halaman-daftar">
    <div class="main-wrapper"> 
        
        <div class="kotak-regis">
            <div class="area-logo">
                <img src="asets/img/logoregis.png" alt="Logo SelfTalk">
            </div>


            <h2>Buat Akun</h2>
            <p class="tagline">Ceritakan perasaanmu dengan aman di sini.</p>

            <?php if ($pesan != ""): ?>
                <div class="notif-box <?= $status; ?>">
                    <?= $pesan; ?>
                </div>
            <?php endif; ?>



            <form action="" method="POST">
                <div class="baris-input">
                    <label>Nama Lengkap</label>
                    <div class="input-fokus">
                        <img src="asets/img/profil.png" width="18">
                        <input type="text" name="nama" placeholder="Nama lengkap kamu" required>
                    </div>
                </div>



                <div class="baris-input">
                    <label>Email Siswa</label>
                    <div class="input-fokus">
                        <img src="asets/img/email.png" width="18">
                        <input type="email" name="email" placeholder="contoh@student.telkom-sda.sch.id" required>
                    </div>
                </div>

                
                <div class="baris-input">
                    <label>NIS (Nomor Induk Siswa)</label>
                    <div class="input-fokus">
                        <img src="asets/img/nis.png" width="18">
                        <input type="text" name="nis" placeholder="Nomor Induk Siswa" required>
                    </div>
                </div>

                <div class="baris-input">
                    <label>Kelas</label>
                    <div class="input-fokus">
                        <img src="asets/img/kelas.png" width="18">
                        <input type="text" name="kelas" placeholder="Contoh: X SIJA 2" required>
                    </div>
                </div>

                <div class="baris-input">
                    <label>Kata Sandi</label>
                    <div class="input-fokus">
                        <img src="asets/img/sandi.png" width="18">
                        <input type="password" name="password" placeholder="Minimal 8 karakter" required>
                    </div>
                </div>

                <button type="submit" class="kirim">Daftar Sekarang →</button>
            </form>

            <div class="link-pindah">
                Sudah punya akun? <a href="login.php">Masuk di sini</a>
            </div>
        </div>

    </div> 

    <?php include 'includes/footer.php'; ?>

</body>
</html>