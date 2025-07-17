<?php
require_once '../utils/config.php';
require_once '../utils/functions.php';

if (!isSessionValid() || ($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../user/login.php");
    exit();
}

$book_id_str = isset($_GET['book_id']) ? $_GET['book_id'] : '';
if (!$book_id_str) {
    header('Location: browse_books.php?error=Invalid+book+ID');
    exit();
}

// Fetch the book by book_id (string)
$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$book_id_str]);
$book = $stmt->fetch();

if (!$book) {
    header('Location: browse_books.php?error=Book+not+found');
    exit();
}

// Delete the book using numeric id
$stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
$stmt->execute([$book['id']]);

header('Location: browse_books.php?message=Book+deleted+successfully');
exit(); 