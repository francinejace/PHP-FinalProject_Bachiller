<?php
declare(strict_types=1);
require_once BASE_PATH . '/bootstrap.php';
require_once UTILS_PATH . '/userView.util.php';
require_once UTILS_PATH . '/config.php';

// Initialize session
Auth::init();

// Use $pdo from config.php for MySQL
$pdo = $config['pdo'];

return UsersDatabase::viewAll($pdo);