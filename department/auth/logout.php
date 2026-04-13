<?php
/**
 * Department Head Logout
 * 
 * Destroys department session and redirects to login page.
 */

require_once __DIR__ . '/../includes/session.php';

// Log the logout action before destroying session
if (isDepartmentLoggedIn()) {
    try {
        $pdo = require __DIR__ . '/../includes/db.php';
        $stmt = $pdo->prepare('
            INSERT INTO audit_logs (actor_user_id, module_name, action_name, entity_type, entity_id, change_summary, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            getCurrentDepartmentId(),
            'department',
            'logout',
            'department_head',
            getCurrentDepartmentId(),
            'Department head logged out',
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
    } catch (Exception $e) {
        // Silently fail - don't interrupt logout
        error_log('Department logout audit error: ' . $e->getMessage());
    }
}

// Destroy session
destroyDepartmentSession();

// Redirect to login page
header('Location: login.php');
exit;
