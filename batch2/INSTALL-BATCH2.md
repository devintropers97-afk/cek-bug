# PANDUAN INSTALASI BATCH 2

## Cara Install:

### STEP 1: Upload Files

1. Download & extract **SITUNEO-BATCH2.zip**
2. Upload ke server via cPanel File Manager
3. Merge dengan files Batch 1 yang sudah ada

**Struktur setelah merge:**
```
public_html/
├── auth/              ← NEW! (7 files)
├── core/              ← Updated (2 new files)
├── database/          ← NEW! (migration SQL)
├── config/            ← Existing
├── helpers/           ← Existing
├── public/            ← Existing
└── index.php          ← Existing
```

### STEP 2: Setup Database

1. Login phpMyAdmin
2. Pilih database: nrrskfvk_situneo_digital
3. Klik tab SQL
4. Copy-paste isi file: **database/batch2-migration.sql**
5. Klik Go
6. Harus sukses create 3 tables baru

### STEP 3: Test!

**Test Login:**
1. Buka: https://situneo.my.id/auth/login.php
2. Login dengan:
   - Email: admin@situneo.my.id
   - Password: Admin123!
3. Harus berhasil login (redirect ke /admin)

**Test Register:**
1. Buka: https://situneo.my.id/auth/register.php
2. Daftar akun baru (Client atau Partner)
3. Cek email untuk verification link
4. Klik link verifikasi
5. Login dengan akun baru

**Test Forgot Password:**
1. Buka: https://situneo.my.id/auth/forgot-password.php
2. Masukkan email
3. Cek email untuk reset link
4. Klik link, buat password baru
5. Login dengan password baru

### Troubleshooting:

**❌ Class Auth not found**
Solusi: Pastikan file core/Auth.php sudah diupload

**❌ Table doesn't exist**
Solusi: Run migration SQL di phpMyAdmin

**❌ Email tidak terkirim**
Solusi: Cek SMTP settings di .env file

**❌ Error redirect loop**
Solusi: Clear browser cookies & cache

### Support:

Email: vins@situneo.my.id
WhatsApp: +62 831-7386-8915

© PT SITUNEO DIGITAL SOLUSI INDONESIA
