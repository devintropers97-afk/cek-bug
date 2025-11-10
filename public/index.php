<?php
defined('SITUNEO_ACCESS') or define('SITUNEO_ACCESS', true);
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '/');
    define('CONFIG_PATH', ROOT_PATH . 'config/');
    require_once CONFIG_PATH . 'env.php';
}
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SITUNEO DIGITAL - Website Rp 350rb</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root{--blue:#1E5C99;--dark:#0F3057;--gold:#FFB400}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Arial,sans-serif;background:var(--dark);color:#fff}
.hero{min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:2rem}
h1{font-size:clamp(2rem,5vw,4rem);color:var(--gold);font-weight:900;margin-bottom:1rem}
.price{font-size:clamp(2rem,4vw,3rem);color:var(--gold);font-weight:900;margin:1rem 0}
.btn-gold{background:var(--gold);color:var(--dark);padding:1rem 3rem;border-radius:50px;text-decoration:none;font-weight:700;display:inline-block;transition:all 0.3s}
.btn-gold:hover{background:#FFD700;transform:translateY(-3px);box-shadow:0 10px 30px rgba(255,180,0,0.4);color:var(--dark)}
.nib{position:fixed;bottom:20px;right:20px;background:var(--gold);color:var(--dark);padding:15px 25px;border-radius:15px;font-weight:700;z-index:1000;animation:pulse 2s infinite}
@keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.05)}}
.wa{position:fixed;bottom:100px;right:20px;background:#25D366;color:#fff;width:60px;height:60px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:30px;text-decoration:none;z-index:1000;animation:bounce 2s infinite}
@keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
</style>
</head>
<body>
<div class="hero">
<div>
<h1>🚀 SITUNEO DIGITAL</h1>
<p style="font-size:clamp(1rem,3vw,1.5rem);margin:1rem 0">Website Era Baru untuk Bisnis Indonesia</p>
<div class="price">Rp 350,000 / Halaman</div>
<p style="font-size:clamp(1rem,2vw,1.2rem);margin:1rem 0">FREE DEMO 24 JAM - Lihat Dulu, Bayar Kalau Cocok!</p>
<a href="https://wa.me/<?= env('WHATSAPP_NUMBER') ?>?text=Halo%20SITUNEO" class="btn-gold">💬 CHAT WHATSAPP</a>
<p style="margin-top:2rem;opacity:0.9">✅ 500+ Customer | ✅ NIB Resmi | ✅ Garansi 100%</p>
</div>
</div>
<div class="nib">🏆 NIB: <?= env('COMPANY_NIB') ?></div>
<a href="https://wa.me/<?= env('WHATSAPP_NUMBER') ?>" class="wa"><i class="bi bi-whatsapp"></i></a>
</body>
</html>
