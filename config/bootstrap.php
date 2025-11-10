<?php
defined('SITUNEO_ACCESS') or die('Direct access not permitted');
date_default_timezone_set('Asia/Jakarta');
if (session_status() === PHP_SESSION_NONE) session_start();
$helpers = ['common', 'formatting', 'pricing'];
foreach ($helpers as $h) {
    $f = HELPERS_PATH . $h . '.php';
    if (file_exists($f)) require_once $f;
}
require_once CONFIG_PATH . 'database.php';
require_once CONFIG_PATH . 'constants.php';
