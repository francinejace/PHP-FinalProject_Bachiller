<?php
session_start();
session_destroy();
include __DIR__ . '/../includes/header.php';
?>
<div class="container main-content fade-in" style="max-width: 500px; margin: 4rem auto;">
    <div class="card" style="text-align: center;">
        <div class="card-header">
            <h2 class="card-title mb-4">You have been logged out.</h2>
        </div>
        <div class="card-body">
            <a href="/user/login.php" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">Login Again</a>
            <a href="/index.php" class="btn btn-secondary" style="width: 100%;">Back to Home</a>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
