<?php
/**
 * Department Head Login Process
 * 
 * Handles form submission, validates credentials, and sets session.
 */

require_once __DIR__ . '/../includes/session.php';

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Get database connection
$pdo = require __DIR__ . '/../includes/db.php';

// Get form inputs
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validate inputs
if (empty($email) || empty($password)) {
    header('Location: login.php?error=required');
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: login.php?error=invalid');
    exit;
}

try {
    // Query for department by email
    $stmt = $pdo->prepare('
        SELECT id, name, head_email, password, is_active
        FROM departments
        WHERE head_email = ? AND is_active = 1
    ');
    
    $stmt->execute([strtolower($email)]);
    $department = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if department exists and verify password
    if ($department && password_verify($password, $department['password'])) {
        // Password is correct - set session
        setDepartmentSession(
            $department['id'],
            $department['name'],
            $department['head_email']
        );

        // Log successful login (optional - for audit trail)
        logDepartmentLogin($pdo, $department['id'], true);

        // Redirect to dashboard
        header('Location: /Cmain/department/dashboard/index.php');
        exit;
    } else {
        // Invalid credentials
        logDepartmentLogin($pdo, null, false, $email);
        header('Location: login.php?error=invalid');
        exit;
    }

} catch (Exception $e) {
    error_log('Department login error: ' . $e->getMessage());
    header('Location: login.php?error=db_error');
    exit;
}

/**
 * Log department head login attempts (for audit trail)
 * 
 * @param PDO $pdo
 * @param int|null $departmentId
 * @param bool $success
 * @param string|null $email
 */
function logDepartmentLogin($pdo, $departmentId, $success, $email = null) {
    try {
        $stmt = $pdo->prepare('
            INSERT INTO audit_logs (actor_id, module, action, entity, entity_id, summary, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            $departmentId,
            'department',
            $success ? 'login_success' : 'login_failed',
            'department_head',
            $departmentId,
            $success ? 'Department head logged in' : 'Failed login attempt for: ' . ($email ?? 'unknown'),
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
    } catch (Exception $e) {
        // Silently fail - don't interrupt login process if audit logging fails
        error_log('Department login audit error: ' . $e->getMessage());
    }
}
