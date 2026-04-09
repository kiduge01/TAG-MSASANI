<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Department Management'; ?></title>
    <link rel="stylesheet" href="/Cmain/department/assets/css/department.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="department-container">
        <!-- Top Navigation Bar -->
        <nav class="navbar">
            <div class="navbar-brand">
                <h1>Department Management</h1>
            </div>
            <div class="navbar-user">
                <span class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo isset($headName) ? htmlspecialchars($headName) : 'Head'; ?></span>
                </span>
                <a href="/Cmain/department/auth/logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </nav>

        <div class="main-wrapper">
