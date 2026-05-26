<?php

session_start();


$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// mghilangkan smua dr server
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout </title>
    
    <link rel="stylesheet" href="asets/css/logout.css">
    
    <script>
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 2000);
    </script>
</head>
<body>

<div class="logout">
    <div class="loader-space">
        <div class="ring-spin"></div>
        <span class="check-icon">✓</span>
    </div>
    <h3>Sesi Selesai</h3>
    <p>Kamu berhasil keluar dari sistem. Mengalihkan kembali ke halaman utama masuk...</p>
</div>

</body>
</html>