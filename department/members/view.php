<?php
/**
 * Department Members List View
 * 
 * Display all members assigned to the department with options to edit/delete.
 */

require_once __DIR__ . '/../includes/auth_check.php';

$pdo = require __DIR__ . '/../includes/db.php';
$departmentId = getCurrentDepartmentId();

$members = [];
$search = trim($_GET['search'] ?? '');
$searchParam = "%$search%";

try {
    // Fetch members for this department
    $query = '
        SELECT 
            m.id,
            m.member_code,
            m.first_name,
            m.last_name,
            m.phone,
            m.email,
            m.gender,
            dm.assigned_date,
            dm.notes
        FROM department_members dm
        JOIN members m ON dm.member_id = m.id
        WHERE dm.department_id = ?
    ';
    
    $params = [$departmentId];
    
    if (!empty($search)) {
        $query .= ' AND (m.first_name LIKE ? OR m.last_name LIKE ? OR m.phone LIKE ?)';
        $params = array_merge([$departmentId], [$searchParam, $searchParam, $searchParam]);
    }
    
    $query .= ' ORDER BY m.first_name ASC';
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    error_log('Members view error: ' . $e->getMessage());
}

$pageTitle = 'Department Members';
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

            <div class="page-container">
                <div class="page-header">
                    <h2>Department Members</h2>
                    <p>Manage members in your department</p>
                </div>

                <!-- Search and Add Button -->
                <div class="toolbar">
                    <form method="GET" class="search-form">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Search by name or phone..." 
                            value="<?php echo htmlspecialchars($search); ?>"
                            class="search-input"
                        >
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </form>
                    <a href="/Cmain/department/members/add.php" class="btn btn-success">
                        <i class="fas fa-user-plus"></i> Add New Member
                    </a>
                </div>

                <!-- Members Table -->
                <?php if (!empty($members)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Member Code</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Gender</th>
                                    <th>Assigned Date</th>
                                    <th>Role/Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($members as $member): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($member['member_code']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($member['phone'] ?? '—'); ?></td>
                                        <td><?php echo htmlspecialchars($member['email'] ?? '—'); ?></td>
                                        <td><?php echo htmlspecialchars($member['gender'] ?? '—'); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($member['assigned_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($member['notes'] ?? '—'); ?></td>
                                        <td class="actions">
                                            <a href="edit.php?id=<?php echo $member['id']; ?>" class="btn-small btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete.php?id=<?php echo $member['id']; ?>" class="btn-small btn-danger" onclick="return confirm('Remove this member from department?');" title="Remove">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-footer">
                        <p><?php echo count($members); ?> member(s) found.</p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <?php echo !empty($search) ? 'No members found matching your search.' : 'No members assigned to this department yet.'; ?>
                    </div>
                    <a href="/Cmain/department/members/add.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Add Your First Member
                    </a>
                <?php endif; ?>
            </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
