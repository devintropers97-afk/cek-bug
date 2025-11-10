<?php
/**
 * Authentication Class
 * Handles login, register, logout, password management
 */

class Auth {
    private static $instance = null;
    private $db;
    
    private function __construct() {
        $this->db = get_db();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Register new user
     */
    public function register($data) {
        try {
            // Validate
            if (empty($data['email']) || empty($data['password']) || empty($data['full_name'])) {
                return ['success' => false, 'message' => 'Semua field wajib diisi'];
            }
            
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Email tidak valid'];
            }
            
            if (strlen($data['password']) < 6) {
                return ['success' => false, 'message' => 'Password minimal 6 karakter'];
            }
            
            // Check if email exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$data['email']]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Email sudah terdaftar'];
            }
            
            // Hash password
            $hashed = password_hash($data['password'], PASSWORD_BCRYPT);
            
            // Insert user
            $role = isset($data['role']) ? $data['role'] : 'client';
            $stmt = $this->db->prepare("
                INSERT INTO users (email, password, role, full_name, phone) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['email'],
                $hashed,
                $role,
                $data['full_name'],
                $data['phone'] ?? null
            ]);
            
            $user_id = $this->db->lastInsertId();
            
            // If partner, create partner record
            if ($role === 'partner') {
                $referral_code = $this->generateReferralCode();
                $stmt = $this->db->prepare("
                    INSERT INTO partners (user_id, referral_code) 
                    VALUES (?, ?)
                ");
                $stmt->execute([$user_id, $referral_code]);
                
                // Check if has referrer
                if (!empty($data['referral_code'])) {
                    $this->linkReferrer($user_id, $data['referral_code']);
                }
            }
            
            // Create verification token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
            $stmt = $this->db->prepare("
                INSERT INTO email_verifications (user_id, token, expires_at) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$user_id, $token, $expires]);
            
            // Send verification email
            $this->sendVerificationEmail($data['email'], $data['full_name'], $token);
            
            return [
                'success' => true, 
                'message' => 'Registrasi berhasil! Silakan cek email untuk verifikasi.',
                'user_id' => $user_id
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }
    
    /**
     * Login user
     */
    public function login($email, $password, $remember = false) {
        try {
            // Log attempt
            $this->logLoginAttempt($email, false);
            
            // Check rate limit
            if ($this->isRateLimited($email)) {
                return ['success' => false, 'message' => 'Terlalu banyak percobaan login. Coba lagi dalam 15 menit.'];
            }
            
            // Get user
            $stmt = $this->db->prepare("
                SELECT * FROM users 
                WHERE email = ? AND is_active = 1
            ");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return ['success' => false, 'message' => 'Email atau password salah'];
            }
            
            // Verify password
            if (!password_verify($password, $user['password'])) {
                return ['success' => false, 'message' => 'Email atau password salah'];
            }
            
            // Check email verification
            if (!$user['email_verified']) {
                return [
                    'success' => false, 
                    'message' => 'Email belum diverifikasi. Silakan cek email Anda.',
                    'need_verification' => true
                ];
            }
            
            // Log successful attempt
            $this->logLoginAttempt($email, true);
            
            // Create session
            $this->createSession($user['id'], $remember);
            
            return [
                'success' => true,
                'message' => 'Login berhasil!',
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'full_name' => $user['full_name']
                ]
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        // Delete session from DB
        if (isset($_SESSION['session_id'])) {
            $stmt = $this->db->prepare("DELETE FROM sessions WHERE id = ?");
            $stmt->execute([$_SESSION['session_id']]);
        }
        
        // Destroy PHP session
        session_destroy();
        
        return ['success' => true, 'message' => 'Logout berhasil'];
    }
    
    /**
     * Check if user is logged in
     */
    public function check() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        // Validate session in DB
        if (isset($_SESSION['session_id'])) {
            $stmt = $this->db->prepare("
                SELECT id FROM sessions 
                WHERE id = ? AND user_id = ? AND expires_at > NOW()
            ");
            $stmt->execute([$_SESSION['session_id'], $_SESSION['user_id']]);
            
            if (!$stmt->fetch()) {
                $this->logout();
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get current user
     */
    public function user() {
        if (!$this->check()) {
            return null;
        }
        
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    
    /**
     * Request password reset
     */
    public function requestPasswordReset($email) {
        try {
            $stmt = $this->db->prepare("SELECT id, full_name FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if (!$user) {
                // Don't reveal if email exists
                return ['success' => true, 'message' => 'Jika email terdaftar, link reset password telah dikirim'];
            }
            
            // Generate token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $stmt = $this->db->prepare("
                INSERT INTO password_resets (user_id, token, expires_at) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$user['id'], $token, $expires]);
            
            // Send email
            $this->sendPasswordResetEmail($email, $user['full_name'], $token);
            
            return ['success' => true, 'message' => 'Link reset password telah dikirim ke email Anda'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan'];
        }
    }
    
    /**
     * Reset password with token
     */
    public function resetPassword($token, $new_password) {
        try {
            // Validate token
            $stmt = $this->db->prepare("
                SELECT user_id FROM password_resets 
                WHERE token = ? AND expires_at > NOW() AND used = 0
            ");
            $stmt->execute([$token]);
            $reset = $stmt->fetch();
            
            if (!$reset) {
                return ['success' => false, 'message' => 'Token tidak valid atau sudah kadaluarsa'];
            }
            
            // Update password
            $hashed = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed, $reset['user_id']]);
            
            // Mark token as used
            $stmt = $this->db->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
            $stmt->execute([$token]);
            
            return ['success' => true, 'message' => 'Password berhasil direset. Silakan login dengan password baru.'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan'];
        }
    }
    
    /**
     * Verify email with token
     */
    public function verifyEmail($token) {
        try {
            $stmt = $this->db->prepare("
                SELECT user_id FROM email_verifications 
                WHERE token = ? AND expires_at > NOW() AND verified_at IS NULL
            ");
            $stmt->execute([$token]);
            $verification = $stmt->fetch();
            
            if (!$verification) {
                return ['success' => false, 'message' => 'Token tidak valid atau sudah kadaluarsa'];
            }
            
            // Update user
            $stmt = $this->db->prepare("UPDATE users SET email_verified = 1 WHERE id = ?");
            $stmt->execute([$verification['user_id']]);
            
            // Mark as verified
            $stmt = $this->db->prepare("
                UPDATE email_verifications 
                SET verified_at = NOW() 
                WHERE token = ?
            ");
            $stmt->execute([$token]);
            
            return ['success' => true, 'message' => 'Email berhasil diverifikasi! Silakan login.'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan'];
        }
    }
    
    // ============================================================
    // PRIVATE HELPER METHODS
    // ============================================================
    
    private function createSession($user_id, $remember = false) {
        $session_id = bin2hex(random_bytes(32));
        $expires = $remember ? strtotime('+30 days') : strtotime('+24 hours');
        
        $stmt = $this->db->prepare("
            INSERT INTO sessions (id, user_id, ip_address, user_agent, expires_at) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $session_id,
            $user_id,
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? '',
            date('Y-m-d H:i:s', $expires)
        ]);
        
        $_SESSION['user_id'] = $user_id;
        $_SESSION['session_id'] = $session_id;
        
        if ($remember) {
            setcookie('remember_token', $session_id, $expires, '/', '', true, true);
        }
    }
    
    private function logLoginAttempt($email, $success) {
        $stmt = $this->db->prepare("
            INSERT INTO login_attempts (email, ip_address, user_agent, success) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $email,
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? '',
            $success ? 1 : 0
        ]);
    }
    
    private function isRateLimited($email) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as attempts 
            FROM login_attempts 
            WHERE email = ? 
            AND success = 0 
            AND created_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)
        ");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        
        return $result['attempts'] >= 5;
    }
    
    private function generateReferralCode() {
        do {
            $code = 'SIT' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $stmt = $this->db->prepare("SELECT id FROM partners WHERE referral_code = ?");
            $stmt->execute([$code]);
        } while ($stmt->fetch());
        
        return $code;
    }
    
    private function linkReferrer($user_id, $referral_code) {
        $stmt = $this->db->prepare("SELECT id FROM partners WHERE referral_code = ?");
        $stmt->execute([$referral_code]);
        $referrer = $stmt->fetch();
        
        if ($referrer) {
            // Check if referrer is SPV or has supervisor
            $stmt = $this->db->prepare("
                SELECT id, is_spv, is_manager, supervisor_id, manager_id 
                FROM partners WHERE id = ?
            ");
            $stmt->execute([$referrer['id']]);
            $referrer_details = $stmt->fetch();
            
            $supervisor_id = $referrer_details['is_spv'] ? $referrer['id'] : $referrer_details['supervisor_id'];
            $manager_id = $referrer_details['is_manager'] ? $referrer['id'] : $referrer_details['manager_id'];
            
            $stmt = $this->db->prepare("
                UPDATE partners 
                SET supervisor_id = ?, manager_id = ? 
                WHERE user_id = ?
            ");
            $stmt->execute([$supervisor_id, $manager_id, $user_id]);
        }
    }
    
    private function sendVerificationEmail($email, $name, $token) {
        $link = env('APP_URL') . '/auth/verify-email.php?token=' . $token;
        $subject = 'Verifikasi Email - SITUNEO DIGITAL';
        $message = "
            <h2>Halo $name!</h2>
            <p>Terima kasih telah mendaftar di SITUNEO DIGITAL.</p>
            <p>Klik link berikut untuk verifikasi email Anda:</p>
            <p><a href='$link' style='background:#FFB400;color:#0F3057;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block'>Verifikasi Email</a></p>
            <p>Link berlaku selama 24 jam.</p>
            <p>Jika Anda tidak mendaftar, abaikan email ini.</p>
            <br>
            <p>Salam,<br><strong>PT SITUNEO DIGITAL SOLUSI INDONESIA</strong></p>
        ";
        
        send_email($email, $subject, $message);
    }
    
    private function sendPasswordResetEmail($email, $name, $token) {
        $link = env('APP_URL') . '/auth/reset-password.php?token=' . $token;
        $subject = 'Reset Password - SITUNEO DIGITAL';
        $message = "
            <h2>Halo $name!</h2>
            <p>Kami menerima permintaan untuk reset password akun Anda.</p>
            <p>Klik link berikut untuk membuat password baru:</p>
            <p><a href='$link' style='background:#FFB400;color:#0F3057;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block'>Reset Password</a></p>
            <p>Link berlaku selama 1 jam.</p>
            <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            <br>
            <p>Salam,<br><strong>PT SITUNEO DIGITAL SOLUSI INDONESIA</strong></p>
        ";
        
        send_email($email, $subject, $message);
    }
}
