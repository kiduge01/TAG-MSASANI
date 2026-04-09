<?php
/**
 * Department Session Management
 * 
 * Handles session initialization and management for Department Heads.
 * Session data structure:
 *  $_SESSION['department_id']
 *  $_SESSION['head_name']
 *  $_SESSION['head_email']
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    // Set session name BEFORE starting session
    session_name('DEPT_SESSID');
    
    // Set secure cookie settings
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 3600, // 1 hour
        'path' => '/Cmain/department',
        'domain' => $_SERVER['HTTP_HOST'] ?? '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    // NOW start the session
    session_start();
}

/**
 * Check if department head is authenticated
 * 
 * @return bool True if authenticated
 */
function isDepartmentLoggedIn() {
    return isset($_SESSION['department_id']) && !empty($_SESSION['department_id']);
}

/**
 * Get current logged-in department ID
 * 
 * @return int|null Department ID or null if not logged in
 */
function getCurrentDepartmentId() {
    return $_SESSION['department_id'] ?? null;
}

/**
 * Get current department head name
 * 
 * @return string|null Head name or null if not logged in
 */
function getCurrentHeadName() {
    return $_SESSION['head_name'] ?? null;
}

/**
 * Get current department head email
 * 
 * @return string|null Head email or null if not logged in
 */
function getCurrentHeadEmail() {
    return $_SESSION['head_email'] ?? null;
}

/**
 * Set department session after successful login
 * 
 * @param int $departmentId
 * @param string $headName
 * @param string $headEmail
 * @return void
 */
function setDepartmentSession($departmentId, $headName, $headEmail) {
    // Regenerate session ID to prevent fixation attacks
    session_regenerate_id(true);
    
    $_SESSION['department_id'] = (int)$departmentId;
    $_SESSION['head_name'] = trim($headName);
    $_SESSION['head_email'] = trim($headEmail);
}

/**
 * Destroy department session (logout)
 * 
 * @return void
 */
function destroyDepartmentSession() {
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
    
    session_destroy();
}
