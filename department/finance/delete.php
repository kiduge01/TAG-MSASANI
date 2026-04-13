<?php
/**
 * Delete Finance Record
 * 
 * Soft delete a finance transaction (mark as deleted).
 */

require_once __DIR__ . '/../includes/auth_check.php';

$pdo = require __DIR__ . '/../includes/db.php';
$departmentId = getCurrentDepartmentId();
$recordId = $_GET['id'] ?? null;

if (!$recordId || !is_numeric($recordId)) {
    header('Location: view.php');
    exit;
}

try {
    // Verify record belongs to this department
    $stmt = $pdo->prepare('
        SELECT id, type, category, amount
        FROM finance_entries
        WHERE department_id = ? AND id = ?
    ');
    $stmt->execute([$departmentId, $recordId]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        header('Location: view.php');
        exit;
    }

    // Soft delete the record (mark as deleted)
    $stmt = $pdo->prepare('
        UPDATE finance_entries
        SET deleted_at = NOW()
        WHERE department_id = ? AND id = ?
    ');
    $stmt->execute([$departmentId, $recordId]);

    // Log action
    logDepartmentAction(
        $pdo,
        $departmentId,
        'finance_deleted',
        $recordId,
        "Deleted " . $record['type'] . ": " . $record['category'] . " - Tsh " . number_format($record['amount'], 2)
    );

    // Redirect with success message
    header('Location: view.php?success=1');
    exit;

} catch (Exception $e) {
    error_log('Delete finance error: ' . $e->getMessage());
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
            'finance',
            $entityId,
            $summary,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
    } catch (Exception $e) {
        error_log('Audit log error: ' . $e->getMessage());
    }
}
