<?php
/**
 * Department Finance Records View
 * 
 * Display all financial transactions (income and expenses) for the department.
 */

require_once __DIR__ . '/../includes/auth_check.php';

$pdo = require __DIR__ . '/../includes/db.php';
$departmentId = getCurrentDepartmentId();

$records = [];
$search = trim($_GET['search'] ?? '');
$type = trim($_GET['type'] ?? '');
$fromDate = trim($_GET['from_date'] ?? '');
$toDate = trim($_GET['to_date'] ?? '');

try {
    // Build query
    $query = '
        SELECT 
            id,
            type,
            category,
            amount,
            description,
            created_at,
            created_by
        FROM finance_entries
        WHERE department_id = ? AND deleted_at IS NULL
    ';
    
    $params = [$departmentId];
    
    if ($type && in_array($type, ['income', 'expense'])) {
        $query .= ' AND type = ?';
        $params[] = $type;
    }
    
    if (!empty($search)) {
        $query .= ' AND (category LIKE ? OR description LIKE ?)';
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if (!empty($fromDate)) {
        $query .= ' AND DATE(created_at) >= ?';
        $params[] = $fromDate;
    }
    
    if (!empty($toDate)) {
        $query .= ' AND DATE(created_at) <= ?';
        $params[] = $toDate;
    }
    
    $query .= ' ORDER BY created_at DESC';
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate totals
    $totalIncome = 0;
    $totalExpense = 0;
    foreach ($records as $record) {
        if ($record['type'] === 'income') {
            $totalIncome += $record['amount'];
        } else {
            $totalExpense += $record['amount'];
        }
    }
    $balance = $totalIncome - $totalExpense;

} catch (Exception $e) {
    error_log('Finance view error: ' . $e->getMessage());
}

$pageTitle = 'Finance Records';
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

            <div class="page-container">
                <div class="page-header">
                    <h2>Finance Records</h2>
                    <p>Income and Expense Tracking</p>
                </div>

                <!-- Summary Cards -->
                <div class="finance-summary-bar">
                    <div class="summary-card income">
                        <span class="label">Total Income</span>
                        <span class="amount"><?php echo formatCurrency($totalIncome); ?></span>
                    </div>
                    <div class="summary-card expense">
                        <span class="label">Total Expenses</span>
                        <span class="amount"><?php echo formatCurrency($totalExpense); ?></span>
                    </div>
                    <div class="summary-card balance">
                        <span class="label">Balance</span>
                        <span class="amount" style="color: <?php echo $balance >= 0 ? '#28a745' : '#dc3545'; ?>;">
                            <?php echo formatCurrency($balance); ?>
                        </span>
                    </div>
                </div>

                <!-- Filters and Actions -->
                <div class="toolbar">
                    <form method="GET" class="filter-form">
                        <div class="filter-group">
                            <select name="type">
                                <option value="">All Records</option>
                                <option value="income" <?php echo $type === 'income' ? 'selected' : ''; ?>>Income Only</option>
                                <option value="expense" <?php echo $type === 'expense' ? 'selected' : ''; ?>>Expenses Only</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Search category or description..." 
                                value="<?php echo htmlspecialchars($search); ?>"
                            >
                        </div>
                        <div class="filter-group">
                            <input 
                                type="date" 
                                name="from_date" 
                                value="<?php echo htmlspecialchars($fromDate); ?>"
                            >
                            <span>to</span>
                            <input 
                                type="date" 
                                name="to_date" 
                                value="<?php echo htmlspecialchars($toDate); ?>"
                            >
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>

                    <div class="action-buttons">
                        <a href="/Cmain/department/finance/add_income.php" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> Income
                        </a>
                        <a href="/Cmain/department/finance/add_expense.php" class="btn btn-warning">
                            <i class="fas fa-minus-circle"></i> Expense
                        </a>
                    </div>
                </div>

                <!-- Records Table -->
                <?php if (!empty($records)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($records as $record): ?>
                                    <tr class="record-row record-<?php echo htmlspecialchars($record['type']); ?>">
                                        <td><?php echo date('M d, Y', strtotime($record['created_at'])); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $record['type'] === 'income' ? 'success' : 'danger'; ?>">
                                                <?php echo ucfirst($record['type']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($record['category']); ?></td>
                                        <td><?php echo htmlspecialchars($record['description'] ?? '—'); ?></td>
                                        <td class="amount <?php echo $record['type']; ?>">
                                            <?php echo ($record['type'] === 'income' ? '+ ' : '- ') . formatCurrency($record['amount']); ?>
                                        </td>
                                        <td class="actions">
                                            <a href="delete.php?id=<?php echo $record['id']; ?>" class="btn-small btn-danger" onclick="return confirm('Delete this record?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-footer">
                        <p><?php echo count($records); ?> record(s) found.</p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        No financial records yet.
                    </div>
                    <div class="action-buttons">
                        <a href="/Cmain/department/finance/add_income.php" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> Record Income
                        </a>
                        <a href="/Cmain/department/finance/add_expense.php" class="btn btn-warning">
                            <i class="fas fa-minus-circle"></i> Record Expense
                        </a>
                    </div>
                <?php endif; ?>
            </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<?php

/**
 * Format currency for display
 */
function formatCurrency($amount) {
    return 'Tsh ' . number_format($amount, 2);
}
