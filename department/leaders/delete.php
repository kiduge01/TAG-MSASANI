<?php
/**
 * Delete Department Leader
 * 
 * Removes a leadership role from the department.
 */

require_once __DIR__ . '/../includes/auth_check.php';

$pdo = require __DIR__ . '/../includes/db.php';
$departmentId = getCurrentDepartmentId();
$leaderId = $_GET['id'] ?? null;

if (!$leaderId || !is_numeric($leaderId)) {
    header('Location: view.php');
    exit;
}

try {
    // Verify leader belongs to this department
    $stmt = $pdo->prepare('
        SELECT id, leader_name
        FROM department_leaders
        WHERE department_id = ? AND id = ?
    ');
    $stmt->execute([$departmentId, $leaderId]);
    $leader = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$leader) {
        header('Location: view.php');
        exit;
    }

    // Delete the leader
    $stmt = $pdo->prepare('
        DELETE FROM department_leaders
        WHERE department_id = ? AND id = ?
    ');
    $stmt->execute([$departmentId, $leaderId]);

    // Log action
    logDepartmentAction(
        $pdo,
        $departmentId,
        'leader_removed',
        $leaderId,
        "Removed leader: " . $leader['leader_name']
    );

    // Redirect with success message
    header('Location: view.php?success=1');
    exit;

} catch (Exception $e) {
    error_log('Delete leader error: ' . $e->getMessage());
    header('Location: view.php?error=1');
    exit;
}

/**
 * Log department action
 */
function logDepartmentAction($pdo, $departmentId, $action, $entityId, $summary) {
    try {
        $stmt = $pdo->prepare('
            INSERT INTO audit_logs (actor_user_id, module_name, action_name, entity_type, entity_id, change_summary, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $departmentId,
            'department',
            $action,
            'leader',
            $entityId,
            $summary,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
    } catch (Exception $e) {
        error_log('Audit log error: ' . $e->getMessage());
    }
}
