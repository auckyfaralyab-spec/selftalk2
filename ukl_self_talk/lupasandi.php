<?php
session_start();
include 'includes/koneksi.php';

$status = 'input'; // input
$error_msg = '';
$email_input = '';

if (isset($_POST['submit'])) {
    $email_input = mysqli_real_escape_string($koneksi, $_POST['email']);

    if (!empty($email_input)) {

        // cek email
        $query = "SELECT * FROM users WHERE email = '$email_input'";
        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // jika brhasil maka terkirim
            $status = 'terkirim';
        } else {
            // jika tdk berhasil maka input ulang
            $status = 'input';
            $error_msg = "Email tidak terdaftar di sistem SelfTalk Skomda!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Sandi - SelfTalk Skomda</title>
    <link rel="stylesheet" href="asets/css/lupasandi.css">
</head>
<body>


    <div class="container">
        
        <?php if ($status == 'terkirim') : ?>
            
            <div class="card-lupa-sandi">
                <div class="header-back">
                    <a href="login.php" class="btn-back">
                        <span>&#8592;</span> Kembali ke Login
                    </a>
                </div>

                <div class="ikon">
                    <div class="icon-amplop-wrapper" style="width: 70px; height: 70px; background-color: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <div class="icon-mail-blue" style="width: 32px; height: 32px; background-color: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 16px;">@</div>
                    </div>
                </div>

                <div class="text-section">
                    <h1>Email Terkirim!</h1>
                    <p>Kami telah mengirimkan instruksi pengaturan ulang kata sandi ke <strong style="color: #0f172a; display: block; margin-top: 5px;"><?= htmlspecialchars($email_input); ?></strong></p>
                </div>

                <a href="login.php" class="btn-submit" style="display: block; text-decoration: none; text-align: center; background-color: #f1f5f9; color: #1e293b; box-shadow: none; margin-top: 20px;">
                    Kembali ke Login
                </a>
            </div>

        <?php else : ?>

            <div class="card-lupa-sandi">
                <div class="header-back">
                    <a href="login.php" class="btn-back">
                        <span>&#8592;</span> Kembali ke Login
                    </a>
                </div>

                <div class="ikon">
                    <img src="asets/img/lupasandi.png" alt="sandi" class="main-icon">
                </div>

                <div class="text-section">
                    <h1>Lupa Sandi?</h1>
                    <p>Masukkan email siswa Anda untuk mengatur ulang kata sandi.</p>
                </div>

                <?php if (!empty($error_msg)) : ?>
                    <div class="error-box" style="color: #e11d48; font-size: 13px; font-weight: 600; text-align: left; margin-bottom: 15px; background-color: #fff1f2; padding: 12px 16px; border-radius: 12px;">
                         <?= $error_msg; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="input-group">
                        <label>Email Siswa</label>
                        <div class="input">
                            <span class="icon-mail">&#9993;</span>
                            <input type="email" name="email" value="<?= htmlspecialchars($email_input); ?>" placeholder="nama@student.telkom-sda.sch.id" required>
                        </div>
                    </div>

                    <button type="submit" name="submit" class="btn-submit">
                        Kirim Instruksi <span>&rarr;</span>
                    </button>
                </form>
            </div>

        <?php endif; ?>

        <footer class="footer-text">
            <p><strong>SelfTalk Skomda</strong></p>
            <p>&copy; 2026 SelfTalk. Media Internal Pendukung Kesejahteraan Siswa.</p>
        </footer>
    </div>

</body>
</html>