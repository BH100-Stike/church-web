<?php
session_start();
require_once '../includes/connect.php';
require_once '../includes/auth_functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate inputs
    if (empty($name)) $errors['name'] = 'Full name is required';
    if (empty($username)) $errors['username'] = 'Username is required';
    if (empty($email)) $errors['email'] = 'Email is required';
    if (empty($password)) $errors['password'] = 'Password is required';
    if ($password !== $confirm_password) $errors['confirm_password'] = 'Passwords do not match';

    // Check if username/email exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            $errors['general'] = 'Username or email already exists';
        }
    }

    // Create account if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO admin_users (name, username, email, password, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        if ($stmt->execute([$name, $username, $email, $hashed_password])) {
            $success = true;
            
            // Auto-login after successful registration
            if (attemptLogin($pdo, $username, $password)) {
                header('Location: dashboard.php');
                exit;
            }
        } else {
            $errors['general'] = 'Registration failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBCTY Admin - Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/signup.css">
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h2>Create Admin Account</h2>
            <p>Register for the CBCTY admin dashboard</p>
        </div>
        
        <div class="signup-body">
            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    Registration successful! Redirecting...
                    <div class="spinner-border spinner-border-sm ms-2"></div>
                </div>
            <?php else: ?>
                <form action="signup.php" method="POST" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                   id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                            <div class="invalid-feedback"><?= $errors['name'] ?? '' ?></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
                                   id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                            <div class="invalid-feedback"><?= $errors['username'] ?? '' ?></div>
                        </div>
                        <!-- <div class="form-text">3-20 characters (letters, numbers, _ or -)</div> -->
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                            <div class="invalid-feedback"><?= $errors['email'] ?? '' ?></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                   id="password" name="password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="bi bi-eye"></i>
                            </button>
                            <div class="invalid-feedback"><?= $errors['password'] ?? '' ?></div>
                        </div>
                        <div id="password-requirements" class="mt-2" style="display: none;">
                            <small class="text-muted">Password must contain:</small>
                            <ul class="list-unstyled ps-3 mt-1">
                                <li id="req-length" class="text-danger"><i class="bi bi-x-circle-fill"></i> 8+ characters</li>
                                <li id="req-upper" class="text-danger"><i class="bi bi-x-circle-fill"></i> 1 uppercase letter</li>
                                <li id="req-lower" class="text-danger"><i class="bi bi-x-circle-fill"></i> 1 lowercase letter</li>
                                <li id="req-number" class="text-danger"><i class="bi bi-x-circle-fill"></i> 1 number</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                                   id="confirm_password" name="confirm_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="bi bi-eye"></i>
                            </button>
                            <div class="invalid-feedback"><?= $errors['confirm_password'] ?? '' ?></div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-signup">Create Account</button>
                        <div class="text-center mt-3">
                            Already have an account? <a href="login.php" class="text-decoration-none">Sign In</a>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/signup.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>