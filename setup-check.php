<?php
// Database Setup Checker
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - PayClone</title>
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
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .content {
            padding: 2rem;
        }

        .step {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 0 10px 10px 0;
        }

        .step h3 {
            color: #667eea;
            margin-bottom: 1rem;
        }

        .code-block {
            background: #2d3748;
            color: #e2e8f0;
            padding: 1rem;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 1rem 0;
            overflow-x: auto;
        }

        .success {
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px 5px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        .credentials-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
        }

        .credentials-box h4 {
            color: #856404;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛠️ PayClone Database Setup</h1>
            <p>Follow these steps to set up your database</p>
        </div>
        
        <div class="content">
            <div class="step">
                <h3>Step 1: Check Your Database Server</h3>
                <p>Make sure your database server (MySQL/MariaDB) is running:</p>
                <ul style="margin: 1rem 0; padding-left: 2rem;">
                    <li>If using XAMPP: Start Apache and MySQL</li>
                    <li>If using WAMP: Start all services</li>
                    <li>If using MAMP: Start servers</li>
                </ul>
            </div>

            <div class="step">
                <h3>Step 2: Create Database</h3>
                <p>Open phpMyAdmin (usually at <code>http://localhost/phpmyadmin</code>) and:</p>
                <ol style="margin: 1rem 0; padding-left: 2rem;">
                    <li>Click "New" to create a new database</li>
                    <li>Name it: <strong>paypal_clone</strong></li>
                    <li>Set collation to: <strong>utf8mb4_general_ci</strong></li>
                    <li>Click "Create"</li>
                </ol>
            </div>

            <div class="step">
                <h3>Step 3: Import Database Structure</h3>
                <p>In phpMyAdmin:</p>
                <ol style="margin: 1rem 0; padding-left: 2rem;">
                    <li>Select your "paypal_clone" database</li>
                    <li>Click "Import" tab</li>
                    <li>Choose the <strong>database.sql</strong> file</li>
                    <li>Click "Go" to import</li>
                </ol>
            </div>

            <div class="step">
                <h3>Step 4: Update Database Credentials</h3>
                <p>Edit the <strong>db.php</strong> file and update these lines:</p>
                <div class="code-block">
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Your database username
define('DB_PASS', '');            // Your database password
define('DB_NAME', 'paypal_clone'); // Your database name
                </div>
                
                <div class="credentials-box">
                    <h4>Common Database Credentials:</h4>
                    <p><strong>XAMPP:</strong> Username: root, Password: (empty)</p>
                    <p><strong>WAMP:</strong> Username: root, Password: (empty)</p>
                    <p><strong>MAMP:</strong> Username: root, Password: root</p>
                    <p><strong>Live Server:</strong> Use your hosting provider's credentials</p>
                </div>
            </div>

            <div class="step">
                <h3>Step 5: Test Connection</h3>
                <p>Click the button below to test your database connection:</p>
                <a href="test-connection.php" class="btn">Test Database Connection</a>
            </div>

            <div class="step">
                <h3>Step 6: Access Your Application</h3>
                <p>Once everything is set up:</p>
                <a href="index.php" class="btn">Go to PayClone Homepage</a>
                <a href="login.php" class="btn">Login Page</a>
                <a href="signup.php" class="btn">Sign Up Page</a>
            </div>
        </div>
    </div>
</body>
</html>
