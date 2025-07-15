<?php
/**
 * Library Management System Configuration
 * 
 * Main configuration file for database connection and system settings
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'library_system';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    die('Database connection failed.');
}

define('BASE_URL', '/');
define('ADMIN_EMAIL', getenv('ADMIN_EMAIL') ?: 'admin@example.com');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
define('SESSION_TIMEOUT', 3600); // 1 hour session timeout
define('CSRF_TOKEN_NAME', 'csrf_token');

// System settings
if (!defined('BOOKS_PER_PAGE')) {
    define('BOOKS_PER_PAGE', 10);
}
if (!defined('MAX_BORROW_DAYS')) {
    define('MAX_BORROW_DAYS', 14);
}
if (!defined('FINE_PER_DAY')) {
    define('FINE_PER_DAY', 10.00);
}
if (!defined('MAX_BOOKS_PER_USER')) {
    define('MAX_BOOKS_PER_USER', 3);
}

// File upload settings
if (!defined('UPLOAD_DIR')) {
    define('UPLOAD_DIR', __DIR__ . '/../uploads/');
}

// Create upload directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

/**
 * Create database tables if they don't exist
 */
function createTablesIfNotExist($pdo) {
    try {
        // Create users table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                role ENUM('admin', 'librarian', 'student') DEFAULT 'student',
                full_name VARCHAR(100),
                email VARCHAR(100),
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
        
        // Create books table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS books (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                author VARCHAR(255) NOT NULL,
                isbn VARCHAR(20),
                category VARCHAR(100),
                description TEXT,
                quantity INT DEFAULT 1,
                available_quantity INT DEFAULT 1,
                status ENUM('active', 'inactive', 'archived') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
        
        // Create borrowings table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS borrowings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                book_id INT NOT NULL,
                borrow_date DATE NOT NULL,
                due_date DATE NOT NULL,
                return_date DATE NULL,
                fine_amount DECIMAL(10,2) DEFAULT 0.00,
                status ENUM('borrowed', 'returned', 'overdue') DEFAULT 'borrowed',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
            )
        ");
        
        // Create default admin user if none exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
            $pdo->prepare("
                INSERT INTO users (username, password_hash, role, full_name, email) 
                VALUES ('admin', ?, 'admin', 'System Administrator', 'admin@library.com')
            ")->execute([$admin_password]);
        }
        
    } catch (PDOException $e) {
        error_log("Failed to create tables: " . $e->getMessage());
    }
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Check if user session is valid
 */
function isSessionValid() {
    if (!isset($_SESSION["user_id"]) || !isset($_SESSION["last_activity"])) {
        return false;
    }
    
    // Check session timeout
    if (time() - $_SESSION["last_activity"] > SESSION_TIMEOUT) {
        session_destroy();
        return false;
    }
    
    // Update last activity time
    $_SESSION["last_activity"] = time();
    return true;
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map("sanitizeInput", $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, "UTF-8");
}

/**
 * Validate email format
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Hash password securely
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password against hash
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}




// Define LibraX constant
define("LibraX", "Library System");


