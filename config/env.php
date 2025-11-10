<?php
defined('SITUNEO_ACCESS') or die('Direct access not permitted');
function env($key, $default = null) {
    static $vars = null;
    if ($vars === null) {
        $vars = [];
        $file = ROOT_PATH . '.env';
        if (file_exists($file)) {
            foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
                list($k, $v) = explode('=', $line, 2);
                $vars[trim($k)] = trim($v);
            }
        }
    }
    return isset($vars[$key]) ? $vars[$key] : $default;
}
