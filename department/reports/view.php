<?php
/**
 * Department Reports View
 * 
 * Display all reports created by the department.
 */

require_once __DIR__ . '/../includes/auth_check.php';

$pdo = require __DIR__ . '/../includes/db.php';
$departmentId = getCurrentDepartmentId();

$reports = [];
$statusFilter = trim($_GET['status'] ?? '');

try {
    // Fetch reports for this department
    $query = '
        SELECT 
            id,
            title,
            report_date,
            category,
            status,
            submitted_at,
            reviewed_at,
            created_at
        FROM department_reports
        WHERE department_id = ?
    ';
    
    $params = [$departmentId];
    
    if (!empty($statusFilter)) {
        $query .= ' AND status = ?';
        $params[] = $statusFilter;
    }
    
    $query .= ' ORDER BY created_at DESC';
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    error_log('Reports view error: ' . $e->getMessage());
}

$pageTitle = 'Department Reports';
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

            <div class="page-container">
                <div class="page-header">
                    <h2>Department Reports</h2>
                    <p>Manage and submit department reports</p>
                </div>

                <!-- Filters and Actions -->
                <div class="toolbar">
                    <form method="GET" class="filter-form">
                        <select name="status">
                            <option value="">All Reports</option>
                            <option value="draft" <?php echo $statusFilter === 'draft' ? 'selected' : ''; ?>>Drafts</option>
                            <option value="submitted" <?php echo $statusFilter === 'submitted' ? 'selected' : ''; ?>>Submitted</option>
                            <option value="approved" <?php echo $statusFilter === 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo $statusFilter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>

                    <a href="/Cmain/department/reports/create.php" class="btn btn-success">
                        <i class="fas fa-file-plus"></i> New Report
                    </a>
                </div>

                <!-- Reports List -->
                <?php if (!empty($reports)): ?>
                    <div class="reports-list">
                        <?php foreach ($reports as $report): ?>
                            <div class="report-card">
                                <div class="report-header">
                                    <h3><?php echo htmlspecialchars($report['title']); ?></h3>
                                    <span class="badge badge-<?php echo getStatusColor($report['status']); ?>">
                                        <?php echo ucfirst($report['status']); ?>
                                    </span>
                                </div>

                                <div class="report-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('M d, Y', strtotime($report['report_date'])); ?>
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-tag"></i>
                                        <?php echo htmlspecialchars($report['category']); ?>
                                    </span>
                                    <?php if ($report['submitted_at']): ?>
                                        <span class="meta-item">
                                            <i class="fas fa-check-circle"></i>
                                            Submitted: <?php echo date('M d, Y', strtotime($report['submitted_at'])); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="report-actions">
                                    <a href="view-detail.php?id=<?php echo $report['id']; ?>" class="btn-small btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <?php if ($report['status'] === 'draft'): ?>
                                        <a href="submit.php?id=<?php echo $report['id']; ?>" class="btn-small btn-success">
                                            <i class="fas fa-paper-plane"></i> Submit
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="table-footer">
                        <p><?php echo count($reports); ?> report(s) found.</p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        No reports yet.
                    </div>
                    <a href="/Cmain/department/reports/create.php" class="btn btn-primary">
                        <i class="fas fa-file-plus"></i> Create Your First Report
                    </a>
                <?php endif; ?>
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
