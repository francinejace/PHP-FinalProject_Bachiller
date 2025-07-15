<?php
declare(strict_types=1);
require_once BASE_PATH . '/bootstrap.php';
require_once UTILS_PATH . '/auth.util.php';
require_once UTILS_PATH . '/config.php';

// Initialize session
Auth::init();

// Use $pdo from config.php for MySQL
$pdo = $config['pdo'];

$action = $_REQUEST['action'] ?? null;

// --- LOGIN ---
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = trim($_POST['username'] ?? '');
    $passwordInput = trim($_POST['password'] ?? '');
    if (Auth::login($pdo, $usernameInput, $passwordInput)) {
        $user = Auth::user();

        if ($user["role"] == "team lead") {
            header('Location: /pages/users/index.php');
        } else {
            header('Location: /index.php');
        }
        exit;
    } else {
        header('Location: /pages/login/index.php?error=Invalid%Credentials');
        exit;
    }
}

// --- LOGOUT ---
elseif ($action === 'logout') {
    Auth::init();
    Auth::logout();
    header('Location: /pages/login/index.php');
    exit;
}

// If no valid action, redirect to login
header('Location: /pages/login/index.php');
exit;
