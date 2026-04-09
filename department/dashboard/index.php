<?php
/**
 * Department Head Dashboard
 * 
 * Displays overview statistics and quick access to department features.
 */

require_once __DIR__ . '/../includes/auth_check.php';

$pdo = require __DIR__ . '/../includes/db.php';
$departmentId = getCurrentDepartmentId();
$headName = getCurrentHeadName();
$departmentName = null;

try {
    // Get department info
    $stmt = $pdo->prepare('
        SELECT id, name, description, created_at, is_active
        FROM departments
        WHERE id = ?
    ');
    $stmt->execute([$departmentId]);
    $department = $stmt->fetch(PDO::FETCH_ASSOC);
    $departmentName = $department['name'] ?? 'Department';

    // Get total members count
    $stmt = $pdo->prepare('
        SELECT COUNT(*) as count
        FROM department_members
        WHERE department_id = ?
    ');
    $stmt->execute([$departmentId]);
    $memberCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Get total leaders count
    $stmt = $pdo->prepare('
        SELECT COUNT(*) as count
        FROM department_leaders
        WHERE department_id = ?
    ');
    $stmt->execute([$departmentId]);
    $leaderCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Get finance summary
    $stmt = $pdo->prepare('
        SELECT 
            SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income,
            SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense
        FROM finance_entries
        WHERE department_id = ? AND deleted_at IS NULL
    ');
    $stmt->execute([$departmentId]);
    $financeData = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalIncome = $financeData['total_income'] ?? 0;
    $totalExpense = $financeData['total_expense'] ?? 0;
    $balance = $totalIncome - $totalExpense;

    // Get pending reports count
    $stmt = $pdo->prepare('
        SELECT COUNT(*) as count
        FROM department_reports
        WHERE department_id = ? AND status = "draft"
    ');
    $stmt->execute([$departmentId]);
    $draftReportCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Get submitted reports count
    $stmt = $pdo->prepare('
        SELECT COUNT(*) as count
        FROM department_reports
        WHERE department_id = ? AND status = "submitted"
    ');
    $stmt->execute([$departmentId]);
    $submittedReportCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

} catch (Exception $e) {
    error_log('Dashboard error: ' . $e->getMessage());
    $memberCount = 0;
    $leaderCount = 0;
    $totalIncome = 0;
    $totalExpense = 0;
    $balance = 0;
    $draftReportCount = 0;
    $submittedReportCount = 0;
}

$pageTitle = 'Dashboard';
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

            <div class="page-container">
                <div class="page-header">
                    <h2>Welcome, <?php echo htmlspecialchars($headName); ?></h2>
                    <p><?php echo htmlspecialchars($departmentName); ?> Department</p>
                </div>

                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <!-- Members Card -->
                    <div class="stat-card">
                        <div class="stat-icon members-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo number_format($memberCount, 0); ?></div>
                            <div class="stat-label">Total Members</div>
                            <a href="/Cmain/department/members/view.php" class="stat-link">View Members →</a>
                        </div>
                    </div>

                    <!-- Leaders Card -->
                    <div class="stat-card">
                        <div class="stat-icon leaders-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo number_format($leaderCount, 0); ?></div>
                            <div class="stat-label">Department Leaders</div>
                            <a href="/Cmain/department/leaders/view.php" class="stat-link">View Leaders →</a>
                        </div>
                    </div>

                    <!-- Balance Card -->
                    <div class="stat-card">
                        <div class="stat-icon finance-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" style="color: <?php echo $balance >= 0 ? '#28a745' : '#dc3545'; ?>;">
                                <?php echo formatCurrency($balance); ?>
                            </div>
                            <div class="stat-label">Account Balance</div>
                            <a href="/Cmain/department/finance/view.php" class="stat-link">View Finance →</a>
                        </div>
                    </div>

                    <!-- Reports Card -->
                    <div class="stat-card">
                        <div class="stat-icon reports-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo number_format($draftReportCount + $submittedReportCount, 0); ?></div>
                            <div class="stat-label">Pending Reports</div>
                            <a href="/Cmain/department/reports/view.php" class="stat-link">View Reports →</a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="content-section">
                    <h3>Quick Actions</h3>
                    <div class="quick-actions">
                        <a href="/Cmain/department/members/add.php" class="action-btn">
                            <i class="fas fa-user-plus"></i>
                            <span>Add New Member</span>
                        </a>
                        <a href="/Cmain/department/leaders/add.php" class="action-btn">
                            <i class="fas fa-user-tie"></i>
                            <span>Add Leader</span>
                        </a>
                        <a href="/Cmain/department/finance/add_income.php" class="action-btn">
                            <i class="fas fa-plus-circle"></i>
                            <span>Record Income</span>
                        </a>
                        <a href="/Cmain/department/finance/add_expense.php" class="action-btn">
                            <i class="fas fa-minus-circle"></i>
                            <span>Record Expense</span>
                        </a>
                        <a href="/Cmain/department/reports/create.php" class="action-btn">
                            <i class="fas fa-file-plus"></i>
                            <span>Create Report</span>
                        </a>
                    </div>
                </div>

                <!-- Finance Summary -->
                <div class="content-section">
                    <h3>Finance Summary</h3>
                    <div class="finance-summary">
                        <div class="summary-item income">
                            <div class="summary-label">Total Income</div>
                            <div class="summary-value"><?php echo formatCurrency($totalIncome); ?></div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-label">Total Expenses</div>
                            <div class="summary-value"><?php echo formatCurrency($totalExpense); ?></div>
                        </div>
                        <div class="summary-item balance">
                            <div class="summary-label">Current Balance</div>
                            <div class="summary-value" style="color: <?php echo $balance >= 0 ? '#28a745' : '#dc3545'; ?>;">
                                <?php echo formatCurrency($balance); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Department Info -->
                <div class="content-section">
                    <h3>Department Information</h3>
                    <div class="info-panel">
                        <div class="info-item">
                            <strong>Department Name:</strong>
                            <span><?php echo htmlspecialchars($department['name'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Description:</strong>
                            <span><?php echo htmlspecialchars($department['description'] ?? 'No description'); ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Status:</strong>
                            <span class="badge badge-<?php echo $department['is_active'] ? 'success' : 'danger'; ?>">
                                <?php echo $department['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <strong>Created:</strong>
                            <span><?php echo date('F d, Y', strtotime($department['created_at'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<?php

/**
 * Format currency for display
 */
function formatCurrency($amount) {
    return 'Tsh ' . number_format($amount, 0);
}
