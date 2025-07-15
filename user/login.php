<?php

require_once __DIR__ . "/../utils/config.php";
require_once __DIR__ . "/../utils/functions.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = sanitizeInput($_POST["username"]);
    $password = $_POST["password"]; // Password is not sanitized as it will be hashed

    try {
        $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE username = :username");
        $stmt->execute([":username" => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password_hash"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["last_activity"] = time();

            setFlashMessage("success", "Welcome back, " . htmlspecialchars($user["username"]) . "!");

            if ($user["role"] === "admin") {
                header("Location: ../admin/dashboard.php");
            } elseif ($user["role"] === "librarian") {
                header("Location: ../librarian/dashboard.php");
            } else {
                header("Location: ../student/dashboard.php");
            }
            exit();
        } else {
            $error_message = "Invalid username or password. Please try again.";
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        $error_message = "An error occurred during login. Please try again later.";
    }
}

include __DIR__ . "/../includes/header.php";
?>

<div class="container">
    <div class="main-content" style="max-width: 500px; margin: 2rem auto;">
        <div class="card fade-in">
            <div class="card-header" style="text-align: center;">
                <h2 class="card-title">Welcome Back</h2>
                <p style="color: var(--text-light); margin: 0;">Sign in to your account to continue</p>
            </div>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="login.php">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-input" name="username" placeholder="Enter your username" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-input" name="password" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                    Sign In
                </button>
                
                <div style="text-align: center; color: var(--text-light);">
                    <p>Don't have an account? <a href="register.php" style="color: var(--primary-brown); text-decoration: none; font-weight: 600;">Create one here</a></p>
                    <p><a href="../index.php" style="color: var(--text-light); text-decoration: none;">← Back to Home</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>


