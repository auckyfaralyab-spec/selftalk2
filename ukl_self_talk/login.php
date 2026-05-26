<?php
session_start();
include 'includes/koneksi.php';

// role guru atau siswa
$role_pilihan = isset($_GET['role']) ? $_GET['role'] : 'siswa';
$pesan_error = "";

// proses tombol msuk
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // --------------------------------------------------------
    // logika admin
    // --------------------------------------------------------
    if ($role_pilihan == 'guru') {
        $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE email = '$email'");
        
        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            
            if (password_verify($password, $data['password'])) {
                $_SESSION['id_user'] = $data['admin_id'];
                $_SESSION['nama']    = $data['nama'];
                $_SESSION['role']    = 'guru';
                
                header("Location: admin/dashboard.php");
                exit;
            } else {
                $pesan_error = "⚠️ Kata sandi Guru salah!";
            }
        } else {
            $pesan_error = "⚠️ Email Guru tidak terdaftar!";
        }




    // --------------------------------------------------------
    // logika user
    // --------------------------------------------------------
    } else {
        $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
        
        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            
            if (password_verify($password, $data['password'])) {
                $_SESSION['id_user'] = $data['id_user'];
                $_SESSION['nama']    = $data['nama'];
                $_SESSION['role']    = 'siswa';
                
                header("Location: user/home.php");
                exit;
            } else {
                $pesan_error = " Kata sandi Siswa salah!";
            }
        } else {
            $pesan_error = " Email Siswa tidak terdaftar!";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SelfTalk</title>
    <link rel="stylesheet" href="asets/css/login.css">
</head>


<body class="login-page"> 
    <div class="main-wrapper"> 
        <div class="container">
            <div class="logo">
                <img src="asets/img/logo selftalk 3.png" alt="Logo" width="150">
            </div>

            <?php if ($pesan_error != ""): ?>
                <p class="error-text" style="color: #dc2626; font-size: 14px; text-align: center; margin-bottom: 15px; font-weight: 600;"><?= $pesan_error; ?></p>
            <?php endif; ?>

            <h2>Selamat Datang</h2>
            <p class="subtitle">Masuk sebagai <?= ($role_pilihan == 'guru') ? 'Guru BK' : 'Siswa'; ?> SelfTalk</p>

            <form action="" method="POST">
                <div class="input-group">
                    <label>
                        <span><img src="asets/img/email.png" width="20" alt="Email"></span> Email
                    </label>
                    <input type="email" name="email" placeholder="<?= ($role_pilihan == 'guru') ? 'contoh@bkguru.sch.id' : 'contoh@student.telkom-sda.sch.id'; ?>" required>
                </div>

                <div class="input-group">
                    <label>
                        <span><img src="asets/img/sandi.png" width="20" alt="Password"></span> Kata Sandi
                    </label>
                    <input type="password" name="password" placeholder=". . . . ." required>
                </div>
                
                <div class="action-row">
                    <label style="visibility: hidden;"><input type="checkbox"> Ingat saya</label>
                    <a href="lupasandi.php" class="lupa-sandi">Lupa sandi?</a>
                </div>

                <button type="submit" class="btn-login">Masuk Sekarang →</button>
            </form>


            
            <div class="footer-text">
                <?php if ($role_pilihan !== 'guru') : ?>
                    Belum punya akun? <a href="register.php">Daftar Sekarang</a> 
                <?php else : ?>
                    <span style="color: #6b7280; font-size: 13px;">Hubungi Admin Pusat jika belum memiliki akun Guru.</span>
                <?php endif; ?>
                
                <br><br>
                <a href="pilihan.php" style="font-size: 12px; color: #6b7280; text-decoration: none;">← Kembali Pilih Akses</a>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

</body>
</html>