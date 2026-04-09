<?php
/**
 * Delete Member from Department
 * 
 * Removes a member from the department (does not delete from members table).
 */

require_once __DIR__ . '/../includes/auth_check.php';

$pdo = require __DIR__ . '/../includes/db.php';
$departmentId = getCurrentDepartmentId();
$memberId = $_GET['id'] ?? null;

if (!$memberId || !is_numeric($memberId)) {
    header('Location: view.php');
    exit;
}

try {
    // Verify member belongs to this department
    $stmt = $pdo->prepare('
        SELECT m.first_name, m.last_name
        FROM department_members dm
        JOIN members m ON dm.member_id = m.id
        WHERE dm.department_id = ? AND m.id = ?
    ');
    $stmt->execute([$departmentId, $memberId]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$member) {
        header('Location: view.php');
        exit;
    }

    // Delete the department_members link (not the member itself)
    $stmt = $pdo->prepare('
        DELETE FROM department_members
        WHERE department_id = ? AND member_id = ?
    ');
    $stmt->execute([$departmentId, $memberId]);

    // Log action
    logDepartmentAction(
        $pdo,
        $departmentId,
        'member_removed',
        $memberId,
        "Removed member from department: " . $member['first_name'] . ' ' . $member['last_name']
    );

    // Redirect with success message
    header('Location: view.php?success=1');
    exit;

} catch (Exception $e) {
    error_log('Delete member error: ' . $e->getMessage());
    header('Location: view.php?error=1');
    exit;
}

/**
 * Log department action
 */
function logDepartmentAction($pdo, $departmentId, $action, $entityId, $summary) {
    try {
        $stmt = $pdo->prepare('
            INSERT INTO audit_logs (actor_id, module, action, entity, entity_id, summary, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $departmentId,
            'department',
            $action,
            'member',
            $entityId,
            $summary,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
    } catch (Exception $e) {
        error_log('Audit log error: ' . $e->getMessage());
    }
}
