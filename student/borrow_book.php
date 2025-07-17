<?php
require_once '../utils/config.php';
require_once '../utils/functions.php';

if (!isSessionValid() || ($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$book_id) {
    header('Location: browse_books.php?error=Invalid+book+ID');
    exit();
}

$book = getBookById($book_id);
if (!$book || ($book['available_copies'] ?? 0) < 1) {
    header('Location: browse_books.php?error=Book+not+available');
    exit();
}

// Set due date (e.g., 14 days from now)
$borrow_date = date('Y-m-d');
$due_date = date('Y-m-d', strtotime('+14 days'));

// Insert borrowing record
$pdo->prepare('INSERT INTO borrowings (user_id, book_id, borrow_date, due_date, status) VALUES (?, ?, ?, ?, ?)')
    ->execute([$user_id, $book_id, $borrow_date, $due_date, 'active']);

// Update available copies
$pdo->prepare('UPDATE books SET available_copies = available_copies - 1 WHERE id = ?')
    ->execute([$book_id]);

header('Location: borrowings.php?message=Book+borrowed+successfully');
exit; 