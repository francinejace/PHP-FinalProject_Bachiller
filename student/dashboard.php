<?php
require_once '../utils/config.php';
require_once '../utils/functions.php';

if (!isSessionValid() || ($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../user/login.php");
    exit();
}

// Handle flash messages
$flash = $_SESSION['student_flash'] ?? null;
unset($_SESSION['student_flash']);

// Fetch all books (show up to 100 for now)
$bookData = searchBooks('', ['status' => 'available'], 100, 0);
$books = $bookData['books'];

include '../includes/header.php';
?>
<div class="container main-content fade-in">
    <h2 class="mb-4 title">Student Dashboard</h2>
    <p class="mb-4">Welcome, <b><?= htmlspecialchars($_SESSION['username']) ?></b></p>

    <!-- Quick Actions Section -->
    <section class="quick-actions-section mb-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="browse_books.php" class="quick-action-btn" aria-label="Browse available books">
                        <span class="action-icon" aria-hidden="true">📚</span>
                        <span class="action-text">Browse Books</span>
                    </a>
                    <a href="borrowings.php" class="quick-action-btn" aria-label="View my borrowings">
                        <span class="action-icon" aria-hidden="true">📖</span>
                        <span class="action-text">My Borrowings</span>
                    </a>
                    <a href="add_book.php" class="quick-action-btn" aria-label="Add new book">
                        <span class="action-icon" aria-hidden="true">➕</span>
                        <span class="action-text">Add Book</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php if ($flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> mb-4">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <!-- Show all books in the database -->
    <div class="card">
        <h3 class="card-title mb-3">All Books</h3>
        <?php
        // Fetch all books (show up to 100 for now, regardless of status)
        $bookData = searchBooks('', [], 100, 0);
        $books = $bookData['books'];
        ?>
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
                        <th>Action</th>
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
                                <form method="post" action="borrow_book.php" style="display:inline;">
                                    <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']) ?>">
                                    <button type="submit" class="btn btn-success">Borrow</button>
                                </form>
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
