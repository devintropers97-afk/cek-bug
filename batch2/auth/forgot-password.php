<?php
define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
require_once CONFIG_PATH . 'env.php';

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Lupa Password</title>
<style>
body{font-family:Arial;background:linear-gradient(135deg,#0F3057,#1E5C99);min-height:100vh;display:flex;align-items:center;padding:20px}
.box{background:#fff;border-radius:20px;padding:40px;max-width:450px;width:100%;margin:0 auto;box-shadow:0 20px 60px rgba(0,0,0,0.3)}
h1{color:#FFB400;text-align:center;margin-bottom:10px}
p{text-align:center;color:#666;margin-bottom:30px}
.alert-success{background:#efe;color:#0a0;padding:15px;border-radius:10px;margin-bottom:20px;border:2px solid #cfc}
label{display:block;margin-bottom:8px;color:#333;font-weight:600}
input{width:100%;padding:14px;border:2px solid #e0e0e0;border-radius:10px;font-size:16px;margin-bottom:20px}
input:focus{border-color:#FFB400;outline:none}
button{width:100%;padding:16px;background:#FFB400;color:#0F3057;border:none;border-radius:10px;font-size:16px;font-weight:700;cursor:pointer}
button:hover{background:#FFD700}
.links{text-align:center;margin-top:20px}
.links a{color:#1E5C99;text-decoration:none;font-weight:600}
</style></head><body>
<div class="box">
<h1>🔐 Lupa Password?</h1>
<p>Masukkan email Anda, kami akan kirim link reset password</p>
<?php if($success): ?>
<div class="alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<form method="POST" action="/auth/handler.php">
<input type="hidden" name="action" value="forgot-password">
<label>Email</label>
<input type="email" name="email" required placeholder="nama@email.com">
<button type="submit">KIRIM LINK RESET</button>
</form>
<div class="links">
<a href="/auth/login.php">← Kembali ke Login</a>
</div>
</div>
</body></html>
