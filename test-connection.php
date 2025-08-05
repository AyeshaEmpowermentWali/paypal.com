<?php
// Test database connection
$host = 'localhost';
$user = 'root';  // Change this to your database username
$pass = '';      // Change this to your database password
$dbname = 'paypal_clone';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test if tables exist
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $success = true;
    $message = "Database connection successful!";
    $table_count = count($tables);
    
} catch(PDOException $e) {
    $success = false;
    $message = $e->getMessage();
    $tables = [];
    $table_count = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test - PayClone</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: <?php echo $success ? 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)' : 'linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%)'; ?>;
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .content {
            padding: 2rem;
        }

        .status {
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .info-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
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

        .table-list {
            columns: 2;
            column-gap: 2rem;
            margin: 1rem 0;
        }

        .table-list li {
            margin-bottom: 0.5rem;
            break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo $success ? '✅ Connection Successful!' : '❌ Connection Failed'; ?></h1>
            <p><?php echo $success ? 'Your database is ready to use' : 'Please check your configuration'; ?></p>
        </div>
        
        <div class="content">
            <div class="status <?php echo $success ? 'success' : 'error'; ?>">
                <h3><?php echo $success ? 'Great! Everything is working.' : 'Connection Error'; ?></h3>
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>

            <?php if ($success): ?>
                <div class="info-box">
                    <h4>Database Information:</h4>
                    <p><strong>Host:</strong> <?php echo $host; ?></p>
                    <p><strong>Database:</strong> <?php echo $dbname; ?></p>
                    <p><strong>Tables Found:</strong> <?php echo $table_count; ?></p>
                    
                    <?php if ($table_count > 0): ?>
                        <h4 style="margin-top: 1rem;">Available Tables:</h4>
                        <ul class="table-list">
                            <?php foreach ($tables as $table): ?>
                                <li>✓ <?php echo htmlspecialchars($table); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="color: #856404; margin-top: 1rem;">
                            <strong>Warning:</strong> No tables found. Please import the database.sql file.
                        </p>
                    <?php endif; ?>
                </div>
                
                <div style="text-align: center;">
                    <a href="index.php" class="btn">Go to PayClone</a>
                    <a href="login.php" class="btn">Login</a>
                </div>
            <?php else: ?>
                <div class="info-box">
                    <h4>Troubleshooting Steps:</h4>
                    <ol>
                        <li>Check if your database server is running</li>
                        <li>Verify database credentials in db.php</li>
                        <li>Make sure the database "paypal_clone" exists</li>
                        <li>Import the database.sql file</li>
                    </ol>
                </div>
                
                <div style="text-align: center;">
                    <a href="setup-check.php" class="btn">Setup Guide</a>
                    <a href="test-connection.php" class="btn">Test Again</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
