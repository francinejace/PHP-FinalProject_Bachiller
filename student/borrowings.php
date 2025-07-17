<?php
require_once '../utils/config.php';
require_once '../utils/functions.php';

if (!isSessionValid() || ($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// Join borrowings with books using book_id
$stmt = $pdo->prepare("SELECT b.title, br.borrow_date, br.due_date, br.status FROM borrowings br JOIN books b ON br.book_id = b.book_id WHERE br.user_id = ?");
$stmt->execute([$user_id]);
$borrowings = $stmt->fetchAll();

include '../includes/header.php';
?>
<div class="container main-content fade-in">
    <h2 class="mb-4 title">My Borrowings</h2>
    <div class="card">
        <h3 class="card-title mb-3">Borrowed Books</h3>
        <?php if (empty($borrowings)): ?>
            <div class="alert alert-info">No borrowings found.</div>
        <?php else: ?>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrowings as $b): ?>
                        <tr>
                            <td><?= htmlspecialchars($b['title']) ?></td>
                            <td><?= htmlspecialchars($b['borrow_date']) ?></td>
                            <td><?= htmlspecialchars($b['due_date']) ?></td>
                            <td><?= htmlspecialchars($b['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?> 