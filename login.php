<?php
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        // Get user from database
        $user = $db->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            
            if ($remember) {
                setcookie('remember_user', $user['id'], time() + (86400 * 30), '/');
            }
            
            $success = 'Login successful! Redirecting...';
            header('refresh:2;url=dashboard.php');
        } else {
            $error = 'Invalid email or password. Try: john@example.com / password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PayClone</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .login-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .login-form {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .alert-success {
            background: #efe;
            color: #363;
            border: 1px solid #cfc;
        }

        .demo-credentials {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .demo-credentials:hover {
            background: #bbdefb;
        }

        .demo-credentials h4 {
            color: #1976d2;
            margin-bottom: 0.5rem;
        }

        .demo-credentials p {
            margin: 0.25rem 0;
            color: #333;
        }

        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .back-home:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e1e5e9;
        }

        .signup-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .back-home {
                position: relative;
                top: auto;
                left: auto;
                display: inline-block;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-home">← Back to Home</a>
    
    <div class="login-container">
        <div class="login-header">
            <h1>🔐 PayClone Login</h1>
            <p>100% Working - No Database Required!</p>
        </div>
        
        <form class="login-form" method="POST">
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="demo-credentials" onclick="fillDemo()">
                <h4>🎯 Click Here - Auto Fill Demo Login</h4>
                <p><strong>Email:</strong> john@example.com</p>
                <p><strong>Password:</strong> password</p>
                <p style="font-size: 0.8rem; color: #666; margin-top: 0.5rem;">
                    ✨ Click this box to auto-fill credentials
                </p>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">🚀 Sign In Now</button>
            
            <div class="signup-link">
                Don't have an account? <a href="signup.php">Sign up here</a>
            </div>
        </form>
    </div>

    <script>
        function fillDemo() {
            document.getElementById('email').value = 'john@example.com';
            document.getElementById('password').value = 'password';
        }
        
        // Auto redirect if success
        <?php if ($success): ?>
        setTimeout(function() {
            window.location.href = 'dashboard.php';
        }, 2000);
        <?php endif; ?>
    </script>
</body>
</html>
