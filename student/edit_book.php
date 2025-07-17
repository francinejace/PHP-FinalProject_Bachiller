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
    if (strtolower($status) === 'available') {
        $status = 'Available';
    }
    $publication_year = trim($_POST['publication_year'] ?? $book['publication_year']);

    // Capitalize each word except for articles, conjunctions, prepositions
    function smart_title_case($str) {
        $lower_exceptions = ['a','an','the','and','but','or','for','nor','on','at','to','by','with','of','in','from','as'];
        $words = explode(' ', strtolower($str));
        foreach ($words as $i => $word) {
            if ($i === 0 || !in_array($word, $lower_exceptions)) {
                $words[$i] = ucfirst($word);
            }
        }
        return implode(' ', $words);
    }
    $title = smart_title_case($title);
    $author = smart_title_case($author);
    $category = smart_title_case($category);

    // Set real publication years for known classics
    $known_years = [
        'To Kill A Mockingbird' => 1960,
        '1984' => 1949,
        'Pride And Prejudice' => 1813,
        'The Great Gatsby' => 1925,
        'Harry Potter And The Sorcerer Stone' => 1997,
        'Lord Of The Rings' => 1954,
        'The Origin Of Species' => 1859,
        'A Brief History Of Time' => 1988,
        'The Art Of War' => -500,
        'Sapiens' => 2011,
        'The Catcher In The Rye' => 1951,
        'Animal Farm' => 1945,
        'Brave New World' => 1932,
        'The Hobbit' => 1937,
        'Jane Eyre' => 1847,
        'Wuthering Heights' => 1847,
        'The Picture Of Dorian Gray' => 1890,
        'Dracula' => 1897,
        'Frankenstein' => 1818,
        'The Adventures Of Tom Sawyer' => 1876,
        'Adventures Of Huckleberry Finn' => 1884,
        'Little Women' => 1868,
        'The Secret Garden' => 1911,
        'Alice Adventures In Wonderland' => 1865,
        'The Lion, The Witch And The Wardrobe' => 1950,
        'Cosmos' => 1980,
        'Silent Spring' => 1962,
        'The Double Helix' => 1968,
        'The Diary Of A Young Girl' => 1947,
        'Night' => 1956,
        'The Guns Of August' => 1962,
        'A People History Of The United States' => 1980,
        'The Rise And Fall Of The Third Reich' => 1960,
        'Autobiography Of Malcolm X' => 1965,
        'Long Walk To Freedom' => 1994,
        'Steve Jobs' => 2011,
        'Einstein: His Life And Universe' => 2007,
        'Benjamin Franklin: An American Life' => 2003,
        'Oxford English Dictionary' => 1884,
        'Encyclopedia Britannica' => 1768,
        'Webster Dictionary' => 1828,
        'The Elements Of Style' => 1918,
        'Chicago Manual Of Style' => 1906,
        'Atomic Habits' => 2018
    ];
    $title_key = ucwords(strtolower($title));
    if (isset($known_years[$title_key])) {
        $publication_year = $known_years[$title_key];
    }

    if (!$title) $errors[] = 'Title is required.';
    if (!$author) $errors[] = 'Author is required.';
    if (!$category) $errors[] = 'Category is required.';
    if (!$description) $errors[] = 'Description is required.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE books SET title=?, author=?, category=?, description=?, status=?, publication_year=? WHERE id=?");
        $stmt->execute([$title, $author, $category, $description, $status, $publication_year, $book['id']]);
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