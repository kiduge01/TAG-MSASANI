        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3><?php echo isset($departmentName) ? htmlspecialchars($departmentName) : 'Department'; ?></h3>
            </div>
            
            <nav class="sidebar-menu">
                <a href="/Cmain/department/dashboard/index.php" 
                   class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) === 'index.php' && strpos($_SERVER['PHP_SELF'], 'dashboard') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Members Section -->
                <div class="menu-section">
                    <div class="menu-title">Members</div>
                    <a href="/Cmain/department/members/view.php" 
                       class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], '/members/') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i>
                        <span>View Members</span>
                    </a>
                    <a href="/Cmain/department/members/add.php" 
                       class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) === 'add.php' && strpos($_SERVER['PHP_SELF'], 'members') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-user-plus"></i>
                        <span>Add Member</span>
                    </a>
                </div>

                <!-- Leaders Section -->
                <div class="menu-section">
                    <div class="menu-title">Leaders</div>
                    <a href="/Cmain/department/leaders/view.php" 
                       class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], '/leaders/') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-crown"></i>
                        <span>View Leaders</span>
                    </a>
                    <a href="/Cmain/department/leaders/add.php" 
                       class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) === 'add.php' && strpos($_SERVER['PHP_SELF'], 'leaders') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-user-tie"></i>
                        <span>Add Leader</span>
                    </a>
                </div>

                <!-- Finance Section -->
                <div class="menu-section">
                    <div class="menu-title">Finance</div>
                    <a href="/Cmain/department/finance/view.php" 
                       class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], '/finance/') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-wallet"></i>
                        <span>Finance Records</span>
                    </a>
                    <a href="/Cmain/department/finance/add_income.php" 
                       class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) === 'add_income.php') ? 'active' : ''; ?>">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Income</span>
                    </a>
                    <a href="/Cmain/department/finance/add_expense.php" 
                       class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) === 'add_expense.php') ? 'active' : ''; ?>">
                        <i class="fas fa-minus-circle"></i>
                        <span>Add Expense</span>
                    </a>
                </div>

                <!-- Reports Section -->
                <div class="menu-section">
                    <div class="menu-title">Reports</div>
                    <a href="/Cmain/department/reports/view.php" 
                       class="menu-item <?php echo (strpos($_SERVER['PHP_SELF'], '/reports/') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-file-alt"></i>
                        <span>View Reports</span>
                    </a>
                    <a href="/Cmain/department/reports/create.php" 
                       class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) === 'create.php' && strpos($_SERVER['PHP_SELF'], 'reports') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-file-plus"></i>
                        <span>Create Report</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
