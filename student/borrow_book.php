<?php
require_once '../utils/config.php';
require_once '../utils/functions.php';

if (!isSessionValid() || ($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id_str = isset($_GET['book_id']) ? $_GET['book_id'] : '';

if (!$book_id_str) {
    header('Location: browse_books.php?error=Invalid+book+ID');
    exit();
}

// Fetch the book by book_id (string), get its numeric id
$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$book_id_str]);
$book = $stmt->fetch();

if (!$book || ($book['available_copies'] ?? 1) < 1) {
    header('Location: browse_books.php?error=Book+not+available');
    exit();
}

// Set due date (e.g., 14 days from now)
$borrow_date = date('Y-m-d');
$due_date = date('Y-m-d', strtotime('+14 days'));

// Insert borrowing record using numeric id
$pdo->prepare('INSERT INTO borrowings (user_id, book_id, borrow_date, due_date, status) VALUES (?, ?, ?, ?, ?)')
    ->execute([$user_id, $book['id'], $borrow_date, $due_date, 'active']);

// Update available copies using numeric id
$pdo->prepare('UPDATE books SET available_copies = available_copies - 1 WHERE id = ?')
    ->execute([$book['id']]);

header('Location: borrowings.php?message=Book+borrowed+successfully');
exit; 