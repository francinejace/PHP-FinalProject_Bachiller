<?php
require_once '../utils/config.php';
require_once '../utils/functions.php';

if (!isSessionValid() || ($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../user/login.php");
    exit();
}

$book_id = isset($_GET['book_id']) ? $_GET['book_id'] : '';
if (!$book_id) {
    header('Location: browse_books.php?error=Invalid+book+ID');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

include '../includes/header.php';
?>
<div class="container main-content fade-in">
    <h2 class="mb-4 title">Book Details</h2>
    <?php if (!$book): ?>
        <div class="alert alert-danger">Book not found.</div>
    <?php else: ?>
    <div class="card mb-4" style="max-width:600px;margin:auto;">
        <div class="card-body">
            <h3 class="card-title mb-3"><?= htmlspecialchars($book['title']) ?></h3>
            <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($book['category']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($book['description']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($book['status']) ?></p>
            <p><strong>Publication Year:</strong> <?= htmlspecialchars($book['publication_year']) ?></p>
            <p><strong>Available Copies:</strong> <?= htmlspecialchars($book['available_copies'] ?? 'N/A') ?></p>
            <?php if (($book['available_copies'] ?? 1) > 0): ?>
                <a href="borrow_book.php?book_id=<?= urlencode($book['book_id']) ?>" class="btn btn-success">Borrow</a>
            <?php else: ?>
                <span class="text-muted">Not Available</span>
            <?php endif; ?>
            <a href="browse_books.php" class="btn btn-secondary ml-2">Back to List</a>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?> 