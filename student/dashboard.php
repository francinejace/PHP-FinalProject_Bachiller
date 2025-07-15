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

    <?php if ($flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> mb-4">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <div class="card mb-5">
        <h3 class="card-title mb-3">Add a Book</h3>
        <form method="post" action="add_book.php" class="d-grid" style="gap:1rem;">
            <div class="form-group">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Author</label>
                <input type="text" name="author" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" required></textarea>
            </div>
            <input type="hidden" name="status" value="available">
            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>
    </div>

    <div class="card">
        <h3 class="card-title mb-3">Available Books</h3>
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
