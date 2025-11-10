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

$auth = Auth::getInstance();
if ($auth->check()) {
    header('Location: /' . $auth->user()['role']);
    exit;
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar - SITUNEO DIGITAL</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',Arial,sans-serif;background:linear-gradient(135deg,#0F3057,#1E5C99);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.auth-container{width:100%;max-width:500px}
.auth-box{background:#fff;border-radius:20px;padding:40px;box-shadow:0 20px 60px rgba(0,0,0,0.3)}
.auth-header{text-align:center;margin-bottom:30px}
.auth-header h1{color:#FFB400;font-size:2.5em;margin-bottom:10px;font-weight:900}
.auth-header p{color:#666;font-size:1.1em}
.alert-error{padding:15px;border-radius:10px;margin-bottom:20px;background:#fee;color:#c33;border:2px solid #fcc}
.role-selector{display:flex;gap:15px;justify-content:center;margin-bottom:25px}
.role-option{flex:1;padding:15px;border:2px solid #e0e0e0;border-radius:10px;cursor:pointer;text-align:center;transition:all 0.3s}
.role-option:hover{border-color:#FFB400}
.role-option.active{border-color:#FFB400;background:#FFF9E6}
.role-option input{display:none}
.form-group{margin-bottom:20px}
.form-group label{display:block;margin-bottom:8px;color:#333;font-weight:600;font-size:14px}
.form-group input{width:100%;padding:14px;border:2px solid #e0e0e0;border-radius:10px;font-size:16px}
.form-group input:focus{border-color:#FFB400;outline:none}
.form-group small{color:#666;font-size:12px;display:block;margin-top:5px}
.btn-primary{width:100%;padding:16px;background:linear-gradient(135deg,#FFB400,#FFD700);color:#0F3057;border:none;border-radius:10px;font-size:16px;font-weight:700;cursor:pointer}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(255,180,0,0.3)}
.auth-footer{text-align:center;margin-top:25px;font-size:14px;color:#666}
.auth-footer a{color:#1E5C99;text-decoration:none;font-weight:600}
#referralGroup{display:none}
</style>
</head>
<body>
<div class="auth-container">
<div class="auth-box">
<div class="auth-header">
<h1>🚀 DAFTAR AKUN</h1>
<p>Bergabung dengan SITUNEO DIGITAL</p>
</div>

<?php if($error): ?>
<div class="alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="/auth/handler.php">
<input type="hidden" name="action" value="register">

<div class="role-selector">
<label class="role-option active" onclick="selectRole('client')">
<input type="radio" name="role" value="client" checked>
<div style="font-size:2em;margin-bottom:5px">👤</div>
<div style="font-weight:600">Client</div>
</label>
<label class="role-option" onclick="selectRole('partner')">
<input type="radio" name="role" value="partner">
<div style="font-size:2em;margin-bottom:5px">🤝</div>
<div style="font-weight:600">Partner</div>
</label>
</div>

<div class="form-group">
<label>Nama Lengkap</label>
<input type="text" name="full_name" required placeholder="Nama lengkap Anda">
</div>

<div class="form-group">
<label>Email</label>
<input type="email" name="email" required placeholder="nama@email.com">
</div>

<div class="form-group">
<label>No. WhatsApp</label>
<input type="tel" name="phone" placeholder="08xxxxxxxxxx">
<small>Format: 08xxxxxxxxxx</small>
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter">
<small>Minimal 6 karakter</small>
</div>

<div class="form-group" id="referralGroup">
<label>Kode Referral (Opsional)</label>
<input type="text" name="referral_code" placeholder="SITXXXXXX">
<small>Jika Anda direferensikan oleh partner</small>
</div>

<div style="margin-bottom:20px">
<label style="display:flex;align-items:start;gap:8px;font-size:14px;cursor:pointer">
<input type="checkbox" required style="margin-top:3px">
<span>Saya setuju dengan <a href="#" style="color:#1E5C99">Syarat & Ketentuan</a></span>
</label>
</div>

<button type="submit" class="btn-primary">DAFTAR SEKARANG</button>
</form>

<div class="auth-footer">
Sudah punya akun? <a href="/auth/login.php">Login di sini</a>
</div>
</div>
</div>

<script>
function selectRole(role) {
    document.querySelectorAll('.role-option').forEach(opt => opt.classList.remove('active'));
    event.currentTarget.classList.add('active');
    document.querySelector('input[name="role"][value="' + role + '"]').checked = true;
    document.getElementById('referralGroup').style.display = role === 'partner' ? 'block' : 'none';
}
</script>
</body>
</html>
