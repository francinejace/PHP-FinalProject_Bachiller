<?php
require_once '../utils/config.php';
require_once '../utils/functions.php';

if (!isSessionValid() || ($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../user/login.php");
    exit();
}

// Fetch all books directly from the database
$stmt = $pdo->query("SELECT * FROM books");
$books = $stmt->fetchAll();

include '../includes/header.php';
?>
<div class="container main-content fade-in">
    <h2 class="mb-4 title">Browse Books</h2>
    <div class="card">
        <h3 class="card-title mb-3">All Books</h3>
        <?php if (empty($books)): ?>
            <div class="alert alert-info">No books found.</div>
        <?php else: ?>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><?= htmlspecialchars($book['category']) ?></td>
                            <td><?= htmlspecialchars($book['description']) ?></td>
                            <td><?= htmlspecialchars($book['status']) ?></td>
                            <td>
                                <a href="view_book.php?book_id=<?= urlencode($book['book_id']) ?>" class="btn btn-info btn-sm">View</a>
                                <a href="edit_book.php?book_id=<?= urlencode($book['book_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_book.php?book_id=<?= urlencode($book['book_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                                <?php if (($book['available_copies'] ?? 1) > 0): ?>
                                    <a href="borrow_book.php?book_id=<?= urlencode($book['book_id']) ?>" class="btn btn-success btn-sm">Borrow</a>
                                <?php else: ?>
                                    <span class="text-muted">Not Available</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?> 