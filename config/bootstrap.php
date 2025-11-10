<?php
defined('SITUNEO_ACCESS') or die('Direct access not permitted');
date_default_timezone_set('Asia/Jakarta');
if (session_status() === PHP_SESSION_NONE) session_start();

// Load helpers
$helpers = ['common', 'formatting', 'pricing'];
foreach ($helpers as $h) {
    $f = HELPERS_PATH . $h . '.php';
    if (file_exists($f)) require_once $f;
}

// Load config files
require_once CONFIG_PATH . 'database.php';
require_once CONFIG_PATH . 'constants.php';

// Load core classes
if (defined('CORE_PATH')) {
    $coreClasses = ['User', 'Auth'];
    foreach ($coreClasses as $class) {
        $f = CORE_PATH . $class . '.php';
        if (file_exists($f)) require_once $f;
    }
}
