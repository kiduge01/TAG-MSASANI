<?php
/**
 * Department Head Login Page
 * 
 * Simple login form for Department Heads to access their department dashboard.
 */

require_once __DIR__ . '/../includes/session.php';

// If already logged in, redirect to dashboard
if (isDepartmentLoggedIn()) {
    header('Location: /Cmain/department/dashboard/index.php');
    exit;
}

$error = '';
if (isset($_GET['error'])) {
    $errors = [
        'invalid' => 'Invalid email or password.',
        'department_inactive' => 'Department is inactive. Please contact administrator.',
        'db_error' => 'Database connection error. Please try again.',
        'required' => 'Email and password are required.'
    ];
    $error = $errors[$_GET['error']] ?? 'An error occurred.';
}

$success = '';
if (isset($_GET['success']) && $_GET['success'] === '1') {
    $success = 'Password updated successfully. Please login.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Head - Login | Church CMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
            max-width: 1200px;
            gap: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            background: white;
            min-height: 600px;
        }

        /* LEFT SIDE - BRANDING */
        .login-left {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
        }

        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 50px;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: rgba(212, 175, 55, 0.1);
            border: 2px solid #d4af37;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #d4af37;
            margin-bottom: 20px;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.2);
        }

        .logo-section h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .logo-section p {
            font-size: 14px;
            color: #d4af37;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .features-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 40px;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(212, 175, 55, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d4af37;
            flex-shrink: 0;
            font-size: 18px;
        }

        .feature-text h3 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
            color: white;
        }

        .feature-text p {
            font-size: 13px;
            color: #b0b0b0;
            line-height: 1.5;
        }

        .footer-text {
            font-size: 12px;
            color: #999;
            text-align: center;
            border-top: 1px solid rgba(212, 175, 55, 0.2);
            padding-top: 30px;
        }

        /* RIGHT SIDE - LOGIN FORM */
        .login-right {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #f8f9fa;
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-header h2 {
            font-size: 28px;
            color: #1a1a2e;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-header p {
            font-size: 14px;
            color: #666;
        }

        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
        }

        .tab-btn {
            padding: 12px 20px;
            border: none;
            background: transparent;
            color: #999;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            position: relative;
            top: 2px;
        }

        .tab-btn.active {
            color: #d4af37;
            border-bottom-color: #d4af37;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            color: #1a1a2e;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: #d4af37;
            font-size: 16px;
            pointer-events: none;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px 14px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input::placeholder {
            color: #999;
        }

        .form-group input:focus {
            outline: none;
            border-color: #d4af37;
            background: white;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
        }

        .form-group input:hover:not(:focus) {
            border-color: #d0d0d0;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .alert.error {
            background: #fff5f5;
            color: #c00;
            border-left: 4px solid #ff4444;
        }

        .alert.success {
            background: #f0fdf4;
            color: #22c55e;
            border-left: 4px solid #22c55e;
        }

        .alert i {
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-content strong {
            display: block;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .btn-login {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #d4af37 0%, #c9a227 100%);
            color: #1a1a2e;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
            background: linear-gradient(135deg, #e5c158 0%, #d4af37 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .forgot-password {
            text-align: center;
            font-size: 13px;
        }

        .forgot-password a {
            color: #d4af37;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .forgot-password a:hover {
            color: #1a1a2e;
            text-decoration: underline;
        }

        .security-note {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #999;
            margin-top: 20px;
            justify-content: center;
        }

        .security-note i {
            color: #d4af37;
            font-size: 14px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .login-left {
                padding: 40px 30px;
                display: none;
            }

            .login-right {
                padding: 40px 30px;
            }

            .form-header h2 {
                font-size: 24px;
            }

            .logo-section h1 {
                font-size: 24px;
            }

            .logo-icon {
                width: 60px;
                height: 60px;
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- LEFT SIDE -->
        <div class="login-left">
            <div>
                <div class="logo-section">
                    <div class="logo-icon">
                        <i class="fas fa-church"></i>
                    </div>
                    <h1>Church CMS</h1>
                    <p>Manage • Grow • Serve</p>
                </div>

                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Member Management</h3>
                            <p>Track families, groups & attendance</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Finance & Budgets</h3>
                            <p>Income, expenses & approvals</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-days"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Events & Calendar</h3>
                            <p>Schedule & coordinate activities</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Communication</h3>
                            <p>Messages & notifications</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-text">
                <p>Powerful tools for department management</p>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="login-right">
            <div class="form-header">
                <h2>Welcome back</h2>
                <p>Sign in to continue</p>
            </div>

            <div class="tab-buttons">
                <button class="tab-btn active">
                    <i class="fas fa-envelope" style="margin-right: 8px;"></i>Email
                </button>
                <button class="tab-btn">
                    <i class="fas fa-phone" style="margin-right: 8px;"></i>Phone
                </button>
            </div>

            <?php if ($error): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="alert-content">
                        <strong>Login Failed</strong>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i>
                    <div class="alert-content">
                        <strong>Success!</strong>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="login_process.php" id="loginForm">
                <div class="form-group">
                    <label for="email">EMAIL ADDRESS</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="you@example.com" 
                            required
                            autocomplete="email"
                            autofocus
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Enter your password" 
                            required
                            autocomplete="current-password"
                        >
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>

                <div class="forgot-password">
                    <a href="/Cmain/public/index.php">
                        <i class="fas fa-arrow-left" style="margin-right: 6px;"></i>
                        Back to Main System
                    </a>
                </div>
            </form>

            <div class="security-note">
                <i class="fas fa-lock-open"></i>
                Secure, encrypted connection
            </div>
        </div>
    </div>
</body>
</html> 