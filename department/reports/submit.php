<?php
/**
 * Submit Department Report
 * 
 * Change report status from draft to submitted (ready for admin review).
 */

require_once __DIR__ . '/../includes/auth_check.php';

$pdo = require __DIR__ . '/../includes/db.php';
$departmentId = getCurrentDepartmentId();
$reportId = $_GET['id'] ?? null;

if (!$reportId || !is_numeric($reportId)) {
    header('Location: view.php');
    exit;
}

try {
    // Verify report exists and belongs to this department
    $stmt = $pdo->prepare('
        SELECT id, status, title
        FROM department_reports
        WHERE department_id = ? AND id = ?
    ');
    $stmt->execute([$departmentId, $reportId]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$report) {
        header('Location: view.php');
        exit;
    }

    // Only allow submit if status is draft
    if ($report['status'] !== 'draft') {
        header('Location: view-detail.php?id=' . $reportId . '&error=not_draft');
        exit;
    }

    // Update report status to submitted
    $stmt = $pdo->prepare('
        UPDATE department_reports
        SET status = "submitted", submitted_at = NOW()
        WHERE department_id = ? AND id = ?
    ');
    $stmt->execute([$departmentId, $reportId]);

    // Log action
    logDepartmentAction(
        $pdo,
        $departmentId,
        'report_submitted',
        $reportId,
        "Submitted report: " . $report['title']
    );

    // Redirect with success message
    header('Location: view.php?success=submitted');
    exit;

} catch (Exception $e) {
    error_log('Submit report error: ' . $e->getMessage());
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
            'report',
            $entityId,
            $summary,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
    } catch (Exception $e) {
        error_log('Audit log error: ' . $e->getMessage());
    }
}
