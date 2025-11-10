<?php
// Bootstrap
define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('HELPERS_PATH', ROOT_PATH . 'helpers/');

require_once CONFIG_PATH . 'env.php';
require_once CONFIG_PATH . 'bootstrap.php';

$auth = Auth::getInstance();
$action = $_POST['action'] ?? '';

if ($action === 'login') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    $result = $auth->login($email, $password, $remember);
    
    if ($result['success']) {
        $_SESSION['success'] = $result['message'];
        header('Location: /' . $result['user']['role']);
    } else {
        $_SESSION['error'] = $result['message'];
        header('Location: /auth/login.php');
    }
    exit;
}

elseif ($action === 'register') {
    $data = [
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'full_name' => $_POST['full_name'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'role' => $_POST['role'] ?? 'client',
        'referral_code' => $_POST['referral_code'] ?? ''
    ];
    
    $result = $auth->register($data);
    
    if ($result['success']) {
        $_SESSION['success'] = $result['message'];
        header('Location: /auth/login.php');
    } else {
        $_SESSION['error'] = $result['message'];
        header('Location: /auth/register.php');
    }
    exit;
}

elseif ($action === 'forgot-password') {
    $email = $_POST['email'] ?? '';
    $result = $auth->requestPasswordReset($email);
    
    $_SESSION['success'] = $result['message'];
    header('Location: /auth/forgot-password.php');
    exit;
}

elseif ($action === 'reset-password') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $result = $auth->resetPassword($token, $password);
    
    if ($result['success']) {
        $_SESSION['success'] = $result['message'];
        header('Location: /auth/login.php');
    } else {
        $_SESSION['error'] = $result['message'];
        header('Location: /auth/reset-password.php?token=' . $token);
    }
    exit;
}

header('Location: /auth/login.php');
exit;
