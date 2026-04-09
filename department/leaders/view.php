<?php
/**
 * Department Leaders List View
 * 
 * Display all leadership roles assigned in the department.
 */

require_once __DIR__ . '/../includes/auth_check.php';

$pdo = require __DIR__ . '/../includes/db.php';
$departmentId = getCurrentDepartmentId();

$leaders = [];

try {
    // Fetch leaders for this department
    $stmt = $pdo->prepare('
        SELECT 
            id,
            leader_type,
            leader_name,
            email,
            phone,
            bio,
            is_active
        FROM department_leaders
        WHERE department_id = ?
        ORDER BY leader_type ASC
    ');
    $stmt->execute([$departmentId]);
    $leaders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    error_log('Leaders view error: ' . $e->getMessage());
}

$pageTitle = 'Department Leaders';
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

            <div class="page-container">
                <div class="page-header">
                    <h2>Department Leadership</h2>
                    <p>Manage leadership roles in your department</p>
                </div>

                <!-- Add Button -->
                <div class="toolbar">
                    <a href="/Cmain/department/leaders/add.php" class="btn btn-success">
                        <i class="fas fa-user-tie"></i> Add New Leader
                    </a>
                </div>

                <!-- Leaders Grid/Table -->
                <?php if (!empty($leaders)): ?>
                    <div class="leaders-grid">
                        <?php foreach ($leaders as $leader): ?>
                            <div class="leader-card">
                                <div class="leader-header">
                                    <h3><?php echo htmlspecialchars($leader['leader_name']); ?></h3>
                                    <span class="badge badge-<?php echo $leader['is_active'] ? 'success' : 'danger'; ?>">
                                        <?php echo $leader['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>

                                <div class="leader-info">
                                    <div class="info-item">
                                        <strong>Position:</strong>
                                        <span><?php echo htmlspecialchars($leader['leader_type']); ?></span>
                                    </div>
                                    <?php if ($leader['email']): ?>
                                        <div class="info-item">
                                            <strong>Email:</strong>
                                            <span><?php echo htmlspecialchars($leader['email']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($leader['phone']): ?>
                                        <div class="info-item">
                                            <strong>Phone:</strong>
                                            <span><?php echo htmlspecialchars($leader['phone']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($leader['bio']): ?>
                                        <div class="info-item">
                                            <strong>Bio:</strong>
                                            <p><?php echo htmlspecialchars($leader['bio']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="leader-actions">
                                    <a href="delete.php?id=<?php echo $leader['id']; ?>" class="btn-small btn-danger" onclick="return confirm('Remove this leader?');">
                                        <i class="fas fa-trash"></i> Remove
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="table-footer">
                        <p><?php echo count($leaders); ?> leader(s) in this department.</p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        No leaders assigned to this department yet.
                    </div>
                    <a href="/Cmain/department/leaders/add.php" class="btn btn-primary">
                        <i class="fas fa-user-tie"></i> Add Your First Leader
                    </a>
                <?php endif; ?>
            </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
