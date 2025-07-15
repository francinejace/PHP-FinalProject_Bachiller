<?php
require_once '../utils/config.php';
require_once '../utils/functions.php';

if (!isSessionValid() || ($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../user/login.php");
    exit();
}

// Handle search, filter, and pagination
$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$filters = [];
if ($category) $filters['category'] = $category;
$filters['status'] = ['available', 'active'];

$bookData = searchBooks($search, $filters, $limit, $offset);
$books = $bookData['books'];
$total = $bookData['total'];
$totalPages = ceil($total / $limit);

// Get all categories for filter dropdown
$categories = getBookCategories();

include '../includes/header.php';
?>
<div class="container main-content fade-in">
    <h2 class="mb-4 title">Browse Books</h2>
    <form method="get" class="d-flex mb-4" style="gap:1rem; align-items:center;">
        <input type="text" name="search" class="form-input" placeholder="Search by title, author, or description" value="<?= htmlspecialchars($search) ?>" style="max-width:250px;">
        <select name="category" class="form-input" style="max-width:180px;">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['category']) ?>" <?= $category === $cat['category'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['category']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <nav class="pagination-nav mt-4">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li>
                        <a href="?search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>&page=<?= $i ?>" class="pagination-link<?= $i === $page ? ' active' : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?> 