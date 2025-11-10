<?php
defined('SITUNEO_ACCESS') or die('Direct access not permitted');
class Router {
    public static function dispatch() {
        $uri = trim($_GET['url'] ?? '', '/');
        if (empty($uri)) {
            require_once PUBLIC_PATH . 'index.php';
            return;
        }
        $file = PUBLIC_PATH . $uri . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
        $root = ROOT_PATH . $uri . '.php';
        if (file_exists($root)) {
            require_once $root;
            return;
        }
        http_response_code(404);
        require_once ROOT_PATH . '404.php';
    }
}
