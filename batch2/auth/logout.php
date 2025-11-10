<?php
// Bootstrap
define('ROOT_PATH', dirname(__DIR__) . '/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('HELPERS_PATH', ROOT_PATH . 'helpers/');

require_once CONFIG_PATH . 'env.php';
require_once CONFIG_PATH . 'bootstrap.php';

$auth = Auth::getInstance();
$auth->logout();

$_SESSION['success'] = 'Anda telah logout';
header('Location: /auth/login.php');
exit;
