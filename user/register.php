<?php

require_once __DIR__ . "/../utils/config.php";
require_once __DIR__ . "/../utils/functions.php";

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = sanitizeInput($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    
    // Basic validation
    if (strlen($username) < 3) {
        $error_message = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        try {
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
            $stmt->execute([":username" => $username]);
            if ($stmt->fetchColumn() > 0) {
                $error_message = "Username already exists. Please choose a different one.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Get the role_id for 'student' (assuming 'student' role has id 3)
                $stmt_role = $pdo->prepare("SELECT id FROM roles WHERE name = 'student'");
                $stmt_role->execute();
                $role_id = $stmt_role->fetchColumn();

                if (!$role_id) {
                    throw new Exception("Student role not found in database.");
                }

                // Insert new user with default role 'student'
                $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role_id, full_name, email) VALUES (:username, :password_hash, :role_id, :full_name, :email)");
                $stmt->execute([
                    ":username" => $username,
                    ":password_hash" => $hashed_password,
                    ":role_id" => $role_id,
                    ":full_name" => "",
                    ":email" => ""
                ]);
                
                setFlashMessage("success", "Registration successful! You can now log in with your credentials.");
                header("Location: login.php");
                exit();
            }
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            $error_message = "Registration failed. Please try again later.";
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $error_message = "Registration failed. Please try again later.";
        }
    }
}

include __DIR__ . "/../includes/header.php";
?>

<div class="container">
    <div class="main-content" style="max-width: 500px; margin: 2rem auto;">
        <div class="card fade-in">
            <div class="card-header" style="text-align: center;">
                <h2 class="card-title">Create Account</h2>
                <p style="color: var(--text-light); margin: 0;">Join our library community today</p>
            </div>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success_message); ?>
                    <div style="margin-top: 1rem;">
                        <a href="login.php" class="btn btn-primary">Go to Login</a>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!$success_message): ?>
            <form method="post" action="register.php">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-input" name="username" placeholder="Choose a username" value="<?php echo isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : ""; ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-input" name="password" placeholder="Create a password (min. 6 characters)" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-input" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                    Create Account
                </button>
                
                <div style="text-align: center; color: var(--text-light);">
                    <p>Already have an account? <a href="login.php" style="color: var(--primary-brown); text-decoration: none; font-weight: 600;">Sign in here</a></p>
                    <p><a href="../index.php" style="color: var(--text-light); text-decoration: none;">← Back to Home</a></p>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>