<?php
// Security check
define('SITUNEO_ACCESS', true);

// Bootstrap
define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('HELPERS_PATH', ROOT_PATH . 'helpers/');

require_once CONFIG_PATH . 'env.php';
require_once CONFIG_PATH . 'bootstrap.php';

// Check if already logged in
$auth = Auth::getInstance();
if ($auth->check()) {
    $user = $auth->user();
    header('Location: /' . $user['role']);
    exit;
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - SITUNEO DIGITAL</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',Arial,sans-serif;background:linear-gradient(135deg,#0F3057,#1E5C99);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.auth-container{width:100%;max-width:450px}
.auth-box{background:#fff;border-radius:20px;padding:40px;box-shadow:0 20px 60px rgba(0,0,0,0.3)}
.auth-header{text-align:center;margin-bottom:30px}
.auth-header h1{color:#FFB400;font-size:2.5em;margin-bottom:10px;font-weight:900}
.auth-header p{color:#666;font-size:1.1em}
.alert{padding:15px;border-radius:10px;margin-bottom:20px;display:flex;align-items:center;gap:10px}
.alert-error{background:#fee;color:#c33;border:2px solid #fcc}
.alert-success{background:#efe;color:#0a0;border:2px solid #cfc}
.form-group{margin-bottom:20px}
.form-group label{display:block;margin-bottom:8px;color:#333;font-weight:600;font-size:14px}
.form-group input{width:100%;padding:14px;border:2px solid #e0e0e0;border-radius:10px;font-size:16px;transition:all 0.3s}
.form-group input:focus{border-color:#FFB400;outline:none;box-shadow:0 0 0 3px rgba(255,180,0,0.1)}
.password-toggle{position:relative}
.password-toggle input{padding-right:45px}
.password-toggle button{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:18px;color:#666}
.form-options{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;font-size:14px}
.form-options label{display:flex;align-items:center;gap:6px;cursor:pointer}
.form-options a{color:#1E5C99;text-decoration:none;font-weight:600}
.form-options a:hover{color:#FFB400}
.btn-primary{width:100%;padding:16px;background:linear-gradient(135deg,#FFB400,#FFD700);color:#0F3057;border:none;border-radius:10px;font-size:16px;font-weight:700;cursor:pointer;transition:all 0.3s}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(255,180,0,0.3)}
.auth-footer{text-align:center;margin-top:25px;font-size:14px;color:#666}
.auth-footer a{color:#1E5C99;text-decoration:none;font-weight:600}
.auth-footer a:hover{color:#FFB400}
</style>
</head>
<body>
<div class="auth-container">
<div class="auth-box">
<div class="auth-header">
<h1>🚀 SITUNEO</h1>
<p>Masuk ke Akun Anda</p>
</div>

<?php if($error): ?>
<div class="alert alert-error">
<span>⚠️</span>
<span><?= htmlspecialchars($error) ?></span>
</div>
<?php endif; ?>

<?php if($success): ?>
<div class="alert alert-success">
<span>✓</span>
<span><?= htmlspecialchars($success) ?></span>
</div>
<?php endif; ?>

<form method="POST" action="/auth/handler.php">
<input type="hidden" name="action" value="login">

<div class="form-group">
<label for="email">Email</label>
<input type="email" id="email" name="email" required placeholder="nama@email.com" autocomplete="email">
</div>

<div class="form-group">
<label for="password">Password</label>
<div class="password-toggle">
<input type="password" id="password" name="password" required placeholder="Masukkan password" autocomplete="current-password">
<button type="button" onclick="togglePassword()">👁️</button>
</div>
</div>

<div class="form-options">
<label>
<input type="checkbox" name="remember" value="1">
<span>Ingat saya</span>
</label>
<a href="/auth/forgot-password.php">Lupa password?</a>
</div>

<button type="submit" class="btn-primary">MASUK</button>
</form>

<div class="auth-footer">
Belum punya akun? <a href="/auth/register.php">Daftar Sekarang</a>
</div>
</div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
