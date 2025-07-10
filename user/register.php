<?php

require_once __DIR__ . "/../utils/config.php";
require_once __DIR__ . "/../utils/functions.php";

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = sanitizeInput($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $full_name = sanitizeInput($_POST["full_name"] ?? "");
    $email = sanitizeInput($_POST["email"] ?? "");
    
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
                
                // Insert new user with default role 'student'
                $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role, full_name, email) VALUES (:username, :password_hash, :role, :full_name, :email)");
                $stmt->execute([
                    ":username" => $username,
                    ":password_hash" => $hashed_password,
                    ":role" => "student",
                    ":full_name" => $full_name,
                    ":email" => $email
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
                <p style="color: #666; margin: 0;">Join our library community today</p>
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
            <form method="post" action="register.php" style="padding: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-input" name="full_name" placeholder="Enter your full name" value="<?php echo isset($_POST["full_name"]) ? htmlspecialchars($_POST["full_name"]) : ""; ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input" name="email" placeholder="Enter your email address" value="<?php echo isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : ""; ?>">
                </div>
                
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
                
                <div style="text-align: center; color: #666;">
                    <p>Already have an account? <a href="login.php" style="color: #8B4513; text-decoration: none; font-weight: 600;">Sign in here</a></p>
                    <p><a href="../index.php" style="color: #666; text-decoration: none;">← Back to Home</a></p>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    background: #f8f6f0;
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
}

.card-title {
    color: #8B4513;
    margin: 0;
    font-size: 1.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.form-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.form-input:focus {
    outline: none;
    border-color: #8B4513;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #8B4513;
    color: white;
}

.btn-primary:hover {
    background: #A0522D;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
}

.alert {
    padding: 1rem;
    border-radius: 6px;
    margin: 1rem 1.5rem;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<?php include __DIR__ . "/../includes/footer.php"; ?>