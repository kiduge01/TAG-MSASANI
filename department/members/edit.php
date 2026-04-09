<?php
/**
 * Edit Department Member
 * 
 * Edit member details within a department.
 */

require_once __DIR__ . '/../includes/auth_check.php';

$pdo = require __DIR__ . '/../includes/db.php';
$departmentId = getCurrentDepartmentId();
$memberId = $_GET['id'] ?? null;

if (!$memberId || !is_numeric($memberId)) {
    header('Location: view.php');
    exit;
}

$error = '';
$member = null;

try {
    // Verify member belongs to this department
    $stmt = $pdo->prepare('
        SELECT m.* 
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

} catch (Exception $e) {
    error_log('Edit member fetch error: ' . $e->getMessage());
    header('Location: view.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $dateOfBirth = trim($_POST['date_of_birth'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // Validation
    if (empty($firstName) || empty($lastName)) {
        $error = 'First name and last name are required.';
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        try {
            // Store old values for audit
            $oldValues = [
                'first_name' => $member['first_name'],
                'last_name' => $member['last_name'],
                'phone' => $member['phone'],
                'email' => $member['email'],
                'gender' => $member['gender'],
                'date_of_birth' => $member['date_of_birth'],
                'address' => $member['address']
            ];

            // Update member
            $stmt = $pdo->prepare('
                UPDATE members SET
                    first_name = ?,
                    last_name = ?,
                    phone = ?,
                    email = ?,
                    gender = ?,
                    date_of_birth = ?,
                    address = ?
                WHERE id = ?
            ');

            $stmt->execute([
                $firstName,
                $lastName,
                $phone ?: null,
                $email ?: null,
                $gender ?: null,
                $dateOfBirth ?: null,
                $address ?: null,
                $memberId
            ]);

            // Log action
            $newValues = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $phone,
                'email' => $email,
                'gender' => $gender,
                'date_of_birth' => $dateOfBirth,
                'address' => $address
            ];

            logDepartmentAction(
                $pdo,
                $departmentId,
                'member_updated',
                $memberId,
                "Updated member: $firstName $lastName",
                $oldValues,
                $newValues
            );

            // Redirect with success message
            header('Location: view.php?success=1');
            exit;

        } catch (Exception $e) {
            error_log('Update member error: ' . $e->getMessage());
            $error = 'Failed to update member. Please try again.';
        }
    }
}

$pageTitle = 'Edit Member';
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

            <div class="page-container">
                <div class="page-header">
                    <h2>Edit Member</h2>
                    <p><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?> (<?php echo htmlspecialchars($member['member_code']); ?>)</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name <span class="required">*</span></label>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                required
                                value="<?php echo htmlspecialchars($_POST['first_name'] ?? $member['first_name']); ?>"
                            >
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name <span class="required">*</span></label>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                required
                                value="<?php echo htmlspecialchars($_POST['last_name'] ?? $member['last_name']); ?>"
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                value="<?php echo htmlspecialchars($_POST['phone'] ?? $member['phone'] ?? ''); ?>"
                            >
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="<?php echo htmlspecialchars($_POST['email'] ?? $member['email'] ?? ''); ?>"
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender">
                                <option value="">Select...</option>
                                <option value="Male" <?php echo ($_POST['gender'] ?? $member['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($_POST['gender'] ?? $member['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo ($_POST['gender'] ?? $member['gender'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input 
                                type="date" 
                                id="date_of_birth" 
                                name="date_of_birth"
                                value="<?php echo htmlspecialchars($_POST['date_of_birth'] ?? $member['date_of_birth'] ?? ''); ?>"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Home Address</label>
                        <textarea 
                            id="address" 
                            name="address" 
                            rows="3"
                        ><?php echo htmlspecialchars($_POST['address'] ?? $member['address'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Member
                        </button>
                        <a href="view.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<?php

/**
 * Log department action with old/new values
 */
function logDepartmentAction($pdo, $departmentId, $action, $entityId, $summary, $oldValues = null, $newValues = null) {
    try {
        $stmt = $pdo->prepare('
            INSERT INTO audit_logs (actor_id, module, action, entity, entity_id, old_values, new_values, summary, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $departmentId,
            'department',
            $action,
            'member',
            $entityId,
            $oldValues ? json_encode($oldValues) : null,
            $newValues ? json_encode($newValues) : null,
            $summary,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
    } catch (Exception $e) {
        error_log('Audit log error: ' . $e->getMessage());
    }
}
