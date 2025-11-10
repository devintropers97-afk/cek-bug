# 📦 PANDUAN INSTALASI SITUNEO DIGITAL

## File yang Sudah Siap Upload:
**SITUNEO-READY-TO-UPLOAD.zip** (22 KB)

---

## 🚀 LANGKAH INSTALASI

### STEP 1: Upload ke cPanel

1. Login ke **cPanel** hosting Anda
2. Buka **File Manager**
3. Masuk ke folder `public_html` (atau folder root domain Anda)
4. Klik **Upload**
5. Pilih file **SITUNEO-READY-TO-UPLOAD.zip**
6. Tunggu sampai upload selesai
7. Klik kanan file ZIP → **Extract**
8. Hapus file ZIP setelah berhasil di-extract

### STEP 2: Konfigurasi Database

1. Login ke **phpMyAdmin**
2. Pilih database: `nrrskfvk_situneo_digital`
3. Klik tab **SQL**
4. Buka file `database/batch2-migration.sql` dari extract
5. Copy semua isi file SQL tersebut
6. Paste ke phpMyAdmin SQL editor
7. Klik **Go** / **Jalankan**
8. Harus sukses create 3 tables:
   - `password_resets`
   - `email_verifications`
   - `login_attempts`

### STEP 3: Konfigurasi .env (PENTING!)

1. Buka file `.env` di File Manager
2. Edit sesuai dengan konfigurasi hosting Anda:

```env
DB_HOST=localhost
DB_NAME=nrrskfvk_situneo_digital
DB_USER=[username_database_anda]
DB_PASS=[password_database_anda]
SMTP_HOST=[smtp_host_anda]
SMTP_USER=[email_smtp_anda]
SMTP_PASS=[password_smtp_anda]
```

3. **Save** file

### STEP 4: Test Website!

**✅ Test Login:**
- Buka: https://situneo.my.id/auth/login.php
- Login dengan:
  - Email: `admin@situneo.my.id`
  - Password: `Admin123!`
- Harus berhasil login (redirect ke dashboard)

**✅ Test Register:**
- Buka: https://situneo.my.id/auth/register.php
- Daftar akun baru (Client atau Partner)
- Cek email untuk verification link

**✅ Test Forgot Password:**
- Buka: https://situneo.my.id/auth/forgot-password.php
- Masukkan email
- Cek email untuk reset link

---

## ✅ PERUBAHAN YANG SUDAH DILAKUKAN

### Bug Fix:
- ✅ **Fixed:** Error "Direct access not permitted" pada semua halaman auth
- ✅ **Fixed:** Menambahkan security check `SITUNEO_ACCESS` di semua file auth

### File Structure:
```
public_html/
├── .env                    ← Konfigurasi environment
├── .htaccess              ← URL rewrite rules
├── index.php              ← Homepage
├── 403.php, 404.php, 500.php, 503.php  ← Error pages
├── auth/                  ← Authentication pages (7 files)
│   ├── login.php         ✅ FIXED
│   ├── register.php      ✅ FIXED
│   ├── handler.php       ✅ FIXED
│   ├── logout.php        ✅ FIXED
│   ├── forgot-password.php  ✅ FIXED
│   ├── reset-password.php   ✅ FIXED
│   └── verify-email.php     ✅ FIXED
├── config/                ← Configuration files
│   ├── env.php
│   ├── bootstrap.php
│   ├── database.php
│   ├── constants.php
│   └── routes.php
├── core/                  ← Core classes
│   ├── Auth.php          ← Authentication system
│   └── User.php          ← User model
├── database/              ← Database migrations
│   └── batch2-migration.sql
├── helpers/               ← Helper functions
│   ├── common.php
│   ├── formatting.php
│   └── pricing.php
└── public/                ← Public assets
    └── index.php
```

---

## 🔧 TROUBLESHOOTING

### ❌ "Direct access not permitted"
**Sudah diperbaiki!** Semua file auth sudah ada security check.

### ❌ "Class Auth not found"
**Solusi:** Pastikan file `core/Auth.php` sudah diupload dengan benar.

### ❌ "Table doesn't exist"
**Solusi:** Jalankan file SQL migration di phpMyAdmin (lihat STEP 2).

### ❌ Email tidak terkirim
**Solusi:**
1. Cek konfigurasi SMTP di file `.env`
2. Pastikan SMTP credentials benar
3. Test dengan tool SMTP tester di cPanel

### ❌ Error 500 - Internal Server Error
**Solusi:**
1. Cek PHP version (minimal PHP 7.4)
2. Cek file permissions (folder: 755, file: 644)
3. Cek error log di cPanel

### ❌ Redirect loop
**Solusi:** Clear browser cookies & cache, kemudian coba lagi.

---

## 📋 FITUR YANG SUDAH JALAN

✅ **Login System**
- Email/password authentication
- Remember me (30 days)
- Rate limiting (max 5 attempts/15 min)
- Auto-redirect based on role

✅ **Register System**
- Client & Partner registration
- Email verification required
- Referral code system
- Password strength validation

✅ **Security**
- CSRF protection
- Password hashing (bcrypt)
- Session hijacking prevention
- IP & User-Agent tracking
- XSS & SQL injection prevention

✅ **Password Management**
- Forgot password flow
- Reset via email token
- Token expiry (1 hour)

---

## 📞 SUPPORT

Jika ada masalah saat instalasi:

📧 Email: vins@situneo.my.id
📱 WhatsApp: +62 831-7386-8915

---

© 2025 PT SITUNEO DIGITAL SOLUSI INDONESIA
