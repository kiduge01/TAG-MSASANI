<?php
/**
 * View Report Details
 * 
 * Display full details of a specific report.
 */

require_once __DIR__ . '/../includes/auth_check.php';

$pdo = require __DIR__ . '/../includes/db.php';
$departmentId = getCurrentDepartmentId();
$reportId = $_GET['id'] ?? null;

if (!$reportId || !is_numeric($reportId)) {
    header('Location: view.php');
    exit;
}

$report = null;

try {
    // Fetch report
    $stmt = $pdo->prepare('
        SELECT 
            id,
            title,
            description,
            report_date,
            category,
            status,
            submitted_at,
            reviewed_by,
            reviewed_at,
            review_notes,
            created_at
        FROM department_reports
        WHERE department_id = ? AND id = ?
    ');
    $stmt->execute([$departmentId, $reportId]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$report) {
        header('Location: view.php');
        exit;
    }

} catch (Exception $e) {
    error_log('Report detail error: ' . $e->getMessage());
    header('Location: view.php');
    exit;
}

$success = $_GET['success'] ?? '';
$pageTitle = 'Report Details';
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

            <div class="page-container">
                <div class="page-header">
                    <h2><?php echo htmlspecialchars($report['title']); ?></h2>
                    <span class="badge badge-<?php echo getStatusColor($report['status']); ?>">
                        <?php echo ucfirst($report['status']); ?>
                    </span>
                </div>

                <?php if ($success === '1'): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Report saved successfully!
                    </div>
                <?php endif; ?>

                <!-- Report Metadata -->
                <div class="report-metadata">
                    <div class="meta-row">
                        <span class="label">Report Date:</span>
                        <span><?php echo date('F d, Y', strtotime($report['report_date'])); ?></span>
                    </div>
                    <div class="meta-row">
                        <span class="label">Category:</span>
                        <span><?php echo htmlspecialchars($report['category']); ?></span>
                    </div>
                    <div class="meta-row">
                        <span class="label">Status:</span>
                        <span class="badge badge-<?php echo getStatusColor($report['status']); ?>">
                            <?php echo ucfirst($report['status']); ?>
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="label">Created:</span>
                        <span><?php echo date('M d, Y H:i', strtotime($report['created_at'])); ?></span>
                    </div>
                    <?php if ($report['submitted_at']): ?>
                        <div class="meta-row">
                            <span class="label">Submitted:</span>
                            <span><?php echo date('M d, Y H:i', strtotime($report['submitted_at'])); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($report['reviewed_at']): ?>
                        <div class="meta-row">
                            <span class="label">Reviewed:</span>
                            <span><?php echo date('M d, Y H:i', strtotime($report['reviewed_at'])); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Report Content -->
                <div class="report-content">
                    <h3>Content</h3>
                    <div class="content-box">
                        <?php echo nl2br(htmlspecialchars($report['description'])); ?>
                    </div>
                </div>

                <!-- Admin Review Notes (if reviewed) -->
                <?php if ($report['review_notes']): ?>
                    <div class="alert alert-info">
                        <h4>Admin Review Notes</h4>
                        <p><?php echo nl2br(htmlspecialchars($report['review_notes'])); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="report-actions">
                    <?php if ($report['status'] === 'draft'): ?>
                        <a href="submit.php?id=<?php echo $report['id']; ?>" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Submit to Admin
                        </a>
                    <?php endif; ?>
                    <a href="view.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Reports
                    </a>
                </div>
            </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<?php

/**
 * Get status color for badge
 */
function getStatusColor($status) {
    $colors = [
        'draft' => 'secondary',
        'submitted' => 'info',
        'approved' => 'success',
        'rejected' => 'danger'
    ];
    return $colors[$status] ?? 'secondary';
}
