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

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = trim($_POST['status'] ?? 'available');

    if (!$title) $errors[] = 'Title is required.';
    if (!$author) $errors[] = 'Author is required.';
    if (!$category) $errors[] = 'Category is required.';
    if (!$description) $errors[] = 'Description is required.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE books SET title=?, author=?, category=?, description=?, status=? WHERE id=?");
        $stmt->execute([$title, $author, $category, $description, $status, $book['id']]);
        header('Location: browse_books.php?message=Book+updated+successfully');
        exit();
    }
}

include '../includes/header.php';
?>
<div class="container main-content fade-in">
    <h2 class="mb-4 title">Edit Book</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-4">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="card mb-5" style="max-width:600px;margin:auto;">
        <form method="post" class="d-grid" style="gap:1rem;">
            <div class="form-group">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-input" required value="<?= htmlspecialchars($_POST['title'] ?? $book['title']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Author</label>
                <input type="text" name="author" class="form-input" required value="<?= htmlspecialchars($_POST['author'] ?? $book['author']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-input" required value="<?= htmlspecialchars($_POST['category'] ?? $book['category']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" required><?= htmlspecialchars($_POST['description'] ?? $book['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <input type="text" name="status" class="form-input" required value="<?= htmlspecialchars($_POST['status'] ?? $book['status']) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Book</button>
            <a href="browse_books.php" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?> 