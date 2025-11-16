<?php
/**
 * CBCTY Admin Authentication Functions
 * 
 * @package CBCTY Admin
 */

/**
 * Check if user is logged in
 * 
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn() {
    // Check all required session variables are set
    return isset(
        $_SESSION['admin_logged_in'], 
        $_SESSION['admin_id'],
        $_SESSION['admin_username'],
        $_SESSION['admin_email'],
        $_SESSION['admin_name']
    ) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Redirect to login page if not logged in
 */
function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        // Store current URL for redirect after login
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit;
    }
}

/**
 * Attempt to log in a user
 * 
 * @param PDO $pdo Database connection
 * @param string $username Username or email
 * @param string $password Password
 * @return bool True if login successful, false otherwise
 */
function attemptLogin($pdo, $username, $password) {
    // Prevent brute force attacks
    sleep(1); // Add small delay
    
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM admin_users 
            WHERE username = :username OR email = :username
            AND is_active = 1
            LIMIT 1
        ");
        
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent fixation
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION = [
                'admin_logged_in' => true,
                'admin_id' => $user['id'],
                'admin_username' => $user['username'],
                'admin_email' => $user['email'],
                'admin_name' => $user['name'],
                'admin_last_login' => time(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ];
            
            // Update last login time
            $update = $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
            $update->execute([$user['id']]);
            
            return true;
        }
        
        // Log failed attempt
        error_log("Failed login attempt for username: $username from IP: {$_SERVER['REMOTE_ADDR']}");
        return false;
        
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return false;
    }
}

/**
 * Log out the current user
 */
function adminLogout() {
    // Unset all session variables
    $_SESSION = [];
    
    // Delete session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), 
            '', 
            time() - 42000,
            $params["path"], 
            $params["domain"],
            $params["secure"], 
            $params["httponly"]
        );
    }
    
    // Destroy session
    session_destroy();
}

/**
 * Check if user session is still valid (same IP and browser)
 * 
 * @return bool True if session is valid, false otherwise
 */
function validateSession() {
    return (
        $_SESSION['ip_address'] === $_SERVER['REMOTE_ADDR'] &&
        $_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']
    );
}

/**
 * Redirect to intended URL after login
 */
function redirectToIntended() {
    if (isset($_SESSION['redirect_url'])) {
        $url = $_SESSION['redirect_url'];
        unset($_SESSION['redirect_url']);
        header("Location: $url");
        exit;
    }
    header('Location: dashboard.php');
    exit;
}
?>