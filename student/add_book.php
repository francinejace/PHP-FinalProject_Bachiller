<?php
require_once '../utils/config.php';
require_once '../utils/functions.php';

if (!isSessionValid() || ($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../user/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = 'available';
    $errors = [];

    if (!$title) $errors[] = 'Title is required.';
    if (!$author) $errors[] = 'Author is required.';
    if (!$category) $errors[] = 'Category is required.';
    if (!$description) $errors[] = 'Description is required.';

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO books (title, author, category, description, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $author, $category, $description, $status]);
            setFlashMessage('Book added successfully!', 'success');
            header('Location: dashboard.php');
            exit();
        } catch (PDOException $e) {
            $errors[] = 'Failed to add book: ' . $e->getMessage();
        }
    }
}

include '../includes/header.php';
?>
<div class="container main-content fade-in">
    <h2 class="mb-4 title">Add Book</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-4">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="card mb-5">
        <form method="post" class="d-grid" style="gap:1rem;">
            <div class="form-group">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-input" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Author</label>
                <input type="text" name="author" class="form-input" required value="<?= htmlspecialchars($_POST['author'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-input" required value="<?= htmlspecialchars($_POST['category'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>
            <input type="hidden" name="status" value="available">
            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?> 