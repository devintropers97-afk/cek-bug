<?php
define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
require_once CONFIG_PATH . 'env.php';

$token = $_GET['token'] ?? '';
if (!$token) {
    header('Location: /auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Reset Password</title>
<style>
body{font-family:Arial;background:linear-gradient(135deg,#0F3057,#1E5C99);min-height:100vh;display:flex;align-items:center;padding:20px}
.box{background:#fff;border-radius:20px;padding:40px;max-width:450px;width:100%;margin:0 auto;box-shadow:0 20px 60px rgba(0,0,0,0.3)}
h1{color:#FFB400;text-align:center;margin-bottom:30px}
label{display:block;margin-bottom:8px;color:#333;font-weight:600}
input{width:100%;padding:14px;border:2px solid #e0e0e0;border-radius:10px;font-size:16px;margin-bottom:20px}
input:focus{border-color:#FFB400;outline:none}
button{width:100%;padding:16px;background:#FFB400;color:#0F3057;border:none;border-radius:10px;font-size:16px;font-weight:700;cursor:pointer}
button:hover{background:#FFD700}
</style></head><body>
<div class="box">
<h1>🔑 Buat Password Baru</h1>
<form method="POST" action="/auth/handler.php">
<input type="hidden" name="action" value="reset-password">
<input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
<label>Password Baru</label>
<input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter">
<label>Konfirmasi Password</label>
<input type="password" name="password_confirm" required minlength="6" placeholder="Ketik ulang password">
<button type="submit">RESET PASSWORD</button>
</form>
</div>
</body></html>
