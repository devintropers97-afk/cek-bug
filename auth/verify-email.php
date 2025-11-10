<?php
// Security check
define('SITUNEO_ACCESS', true);

define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('HELPERS_PATH', ROOT_PATH . 'helpers/');

require_once CONFIG_PATH . 'env.php';
require_once CONFIG_PATH . 'bootstrap.php';

$token = $_GET['token'] ?? '';
$auth = Auth::getInstance();
$result = $auth->verifyEmail($token);

$_SESSION[$result['success'] ? 'success' : 'error'] = $result['message'];
header('Location: /auth/login.php');
exit;
