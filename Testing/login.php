<?php
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}

$error_message = '';

// Proses login
if ($_POST) {
    include 'config.php'; // File konfigurasi database

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = 'Username dan password harus diisi!';
    } else {
        try {
            // Query untuk mencari user - TAMBAH FIELD ROLE
            $stmt = $pdo->prepare("SELECT id, username, password, nama_lengkap, role FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Login berhasil
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['user_role'] = $user['role']; // TAMBAH INI

                header('Location: dashboard.php');
                exit();
            } else {
                $error_message = 'Username atau password salah!';
            }
        } catch (PDOException $e) {
            $error_message = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring ZI Aceh</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">

</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo-container">
                <img src="LOGO BPS.png" alt="Logo BPS" style="background: none; padding: 0;">
            </div>
            <div class="login-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="login-title">MONITORING ZI ACEH</div>
            <div class="login-subtitle">Sistem Monitoring Zona Integritas</div>
        </div>

        <div class="login-body">
            <?php if ($error_message): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="loginForm">
                <div class="form-group">
                    <label>
                        <i class="fas fa-user"></i> Username
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="username" placeholder="Masukkan username Anda" required
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" id="password" placeholder="Masukkan password Anda"
                            required>
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                </div>

                <button type="submit" class="login-btn" id="loginBtn">
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk ke Sistem
                    </span>
                    <span class="loading">
                        <i class="fas fa-spinner"></i>
                        Memproses...
                    </span>
                </button>
            </form>

            <div class="login-footer">
                <i class="fas fa-shield-alt"></i>
                Sistem keamanan terjamin dan terenkripsi
            </div>
        </div>
    </div>
</body>
<script src="js/login.js"></script>

</html>