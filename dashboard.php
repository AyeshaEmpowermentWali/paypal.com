<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user information
$user = $db->getUserById($user_id);

// Get wallet information
$wallet = $db->getWalletByUserId($user_id);

// Get recent transactions
$recent_transactions = $db->getTransactionsByUserId($user_id, 10);

// Get transaction statistics
$total_sent = $db->getTotalSent($user_id);
$total_received = $db->getTotalReceived($user_id);
$total_transactions = $db->getTotalTransactions($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PayClone</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 0 2rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .user-details h3 {
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .user-details p {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .sidebar-nav {
            padding: 2rem 0;
        }

        .nav-item {
            display: block;
            padding: 1rem 2rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }

        .nav-item i {
            margin-right: 1rem;
            width: 20px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
        }

        .dashboard-header {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .dashboard-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .dashboard-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .success-banner {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .success-banner h3 {
            margin-bottom: 0.5rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.balance {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-card.income {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .stat-card.expense {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Quick Actions */
        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .quick-actions h2 {
            margin-bottom: 1.5rem;
            color: #333;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            border-color: #667eea;
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .action-icon {
            font-size: 1.5rem;
        }

        /* Recent Transactions */
        .recent-transactions {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .recent-transactions h2 {
            margin-bottom: 1.5rem;
            color: #333;
        }

        .transaction-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .transaction-icon.sent {
            background: #ff6b6b;
        }

        .transaction-icon.received {
            background: #51cf66;
        }

        .transaction-details h4 {
            margin-bottom: 0.25rem;
            color: #333;
        }

        .transaction-details p {
            color: #666;
            font-size: 0.9rem;
        }

        .transaction-amount {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .transaction-amount.sent {
            color: #ff6b6b;
        }

        .transaction-amount.received {
            color: #51cf66;
        }

        .no-transactions {
            text-align: center;
            color: #666;
            padding: 2rem;
        }

        /* Logout Button */
        .logout-btn {
            position: absolute;
            bottom: 2rem;
            left: 2rem;
            right: 2rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-header {
                padding: 1.5rem;
            }

            .dashboard-header h1 {
                font-size: 1.5rem;
            }
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card,
        .quick-actions,
        .recent-transactions {
            animation: fadeInUp 0.6s ease forwards;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.3s;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <button class="mobile-menu-toggle" onclick="toggleSidebar()">☰</button>
        
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">💳 PayClone</div>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($user['first_name'], 0, 1)); ?>
                    </div>
                    <div class="user-details">
                        <h3><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item active">
                    <span>📊</span> Dashboard
                </a>
                <a href="send-money.php" class="nav-item">
                    <span>💸</span> Send Money
                </a>
                <a href="#" class="nav-item">
                    <span>💰</span> Request Money
                </a>
                <a href="#" class="nav-item">
                    <span>📋</span> Transactions
                </a>
                <a href="#" class="nav-item">
                    <span>👛</span> Wallet
                </a>
                <a href="#" class="nav-item">
                    <span>👤</span> Profile
                </a>
                <a href="#" class="nav-item">
                    <span>⚙️</span> Settings
                </a>
            </nav>
            
            <a href="logout.php" class="logout-btn">
                <span>🚪</span> Logout
            </a>
        </aside>
        
        <main class="main-content">
            <div class="success-banner">
                <h3>🎉 System Working Perfectly!</h3>
                <p>File-based database system - No MySQL setup required!</p>
            </div>
            
            <div class="dashboard-header">
                <h1>Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
                <p>Here's what's happening with your account today.</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card balance">
                    <div class="stat-icon">💰</div>
                    <div class="stat-value"><?php echo formatCurrency($wallet['balance']); ?></div>
                    <div class="stat-label">Available Balance</div>
                </div>
                
                <div class="stat-card income">
                    <div class="stat-icon">📈</div>
                    <div class="stat-value"><?php echo formatCurrency($total_received); ?></div>
                    <div class="stat-label">Total Received</div>
                </div>
                
                <div class="stat-card expense">
                    <div class="stat-icon">📉</div>
                    <div class="stat-value"><?php echo formatCurrency($total_sent); ?></div>
                    <div class="stat-label">Total Sent</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">🔄</div>
                    <div class="stat-value"><?php echo $total_transactions; ?></div>
                    <div class="stat-label">Total Transactions</div>
                </div>
            </div>
            
            <div class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="actions-grid">
                    <a href="send-money.php" class="action-btn">
                        <div class="action-icon">💸</div>
                        <div>
                            <h3>Send Money</h3>
                            <p>Transfer funds instantly</p>
                        </div>
                    </a>
                    <a href="#" class="action-btn">
                        <div class="action-icon">💰</div>
                        <div>
                            <h3>Request Money</h3>
                            <p>Ask for payment</p>
                        </div>
                    </a>
                    <a href="#" class="action-btn">
                        <div class="action-icon">➕</div>
                        <div>
                            <h3>Add Funds</h3>
                            <p>Top up your wallet</p>
                        </div>
                    </a>
                    <a href="#" class="action-btn">
                        <div class="action-icon">💳</div>
                        <div>
                            <h3>Withdraw</h3>
                            <p>Transfer to bank</p>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="recent-transactions">
                <h2>Recent Transactions</h2>
                <?php if (empty($recent_transactions)): ?>
                    <div class="no-transactions">
                        <p>No transactions yet. Start by sending or receiving money!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($recent_transactions as $transaction): ?>
                        <div class="transaction-item">
                            <div class="transaction-info">
                                <div class="transaction-icon <?php echo $transaction['transaction_direction']; ?>">
                                    <?php echo $transaction['transaction_direction'] == 'sent' ? '↗️' : '↙️'; ?>
                                </div>
                                <div class="transaction-details">
                                    <h4>
                                        <?php echo $transaction['transaction_direction'] == 'sent' ? 'Sent to' : 'Received from'; ?>
                                        <?php echo htmlspecialchars($transaction['other_party'] ?? 'Unknown'); ?>
                                    </h4>
                                    <p><?php echo timeAgo($transaction['created_at']); ?></p>
                                </div>
                            </div>
                            <div class="transaction-amount <?php echo $transaction['transaction_direction']; ?>">
                                <?php echo $transaction['transaction_direction'] == 'sent' ? '-' : '+'; ?>
                                <?php echo formatCurrency($transaction['amount']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
