<?php

// Include configuration and functions
require_once '../utils/config.php';
require_once '../utils/functions.php';

// Require admin authentication
requireAuth('admin');

// Get flash messages
$flashMessages = getFlashMessages();

// Get basic statistics
$stats = [
    'total_books' => 0,
    'total_users' => 0,
    'active_borrowings' => 0,
    'overdue_books' => 0
];

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM books WHERE status = 'active'");
    $stmt->execute();
    $stats['total_books'] = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'student'");
    $stmt->execute();
    $stats['total_users'] = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM borrowings WHERE return_date IS NULL");
    $stmt->execute();
    $stats['active_borrowings'] = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM borrowings WHERE return_date IS NULL AND due_date < CURDATE()");
    $stmt->execute();
    $stats['overdue_books'] = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Failed to get statistics: " . $e->getMessage());
}

// Include header
include '../includes/header.php';
?>

<!-- Flash Messages -->
<?php if (!empty($flashMessages)): ?>
<div class="flash-messages">
    <?php foreach ($flashMessages as $message): ?>
    <div class="flash-message flash-<?php echo htmlspecialchars($message['type']); ?>" role="alert">
        <?php echo htmlspecialchars($message['text']); ?>
        <button type="button" class="flash-close">&times;</button>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="container">
    <div class="admin-header">
        <h2>Admin Dashboard</h2>
        <div class="admin-actions">
            <a href="../user/logout.php" class="btn btn-outline">Logout</a>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['total_books']; ?></div>
            <div class="stat-label">Total Books</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['total_users']; ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['active_borrowings']; ?></div>
            <div class="stat-label">Active Borrowings</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['overdue_books']; ?></div>
            <div class="stat-label">Overdue Books</div>
        </div>
    </div>
    
    <div class="dashboard-content">
        <p>Welcome to the admin dashboard, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Administrator'); ?>.</p>
        
        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="manage_books.php" class="btn btn-primary">Manage Books</a>
            <a href="manage_users.php" class="btn btn-primary">Manage Users</a>
            <a href="borrowings.php" class="btn btn-primary">View Borrowings</a>
            <a href="reports.php" class="btn btn-primary">Reports</a>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #eee;
}

.admin-header h2 {
    color: #8B4513;
    margin: 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    border-left: 4px solid #8B4513;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #8B4513;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.dashboard-content {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 2rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #8B4513;
    color: white;
}

.btn-outline {
    background: transparent;
    color: #8B4513;
    border: 2px solid #8B4513;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.flash-messages {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 1000;
    max-width: 400px;
}

.flash-message {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    margin-bottom: 0.5rem;
    border-radius: 6px;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.flash-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.flash-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.flash-close {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0;
    margin-left: 1rem;
    opacity: 0.7;
}

.flash-close:hover {
    opacity: 1;
}
</style>

<?php include '../includes/footer.php'; ?>
