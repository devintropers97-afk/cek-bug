<?php
define('SITUNEO_ACCESS', true);
define('ROOT_PATH', __DIR__ . '/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('HELPERS_PATH', ROOT_PATH . 'helpers/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('ASSETS_PATH', ROOT_PATH . 'assets/');

require_once CONFIG_PATH . 'env.php';
require_once CONFIG_PATH . 'bootstrap.php';
require_once CONFIG_PATH . 'routes.php';

Router::dispatch();
