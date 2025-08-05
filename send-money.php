<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Get user's wallet balance
$wallet = $db->getWalletByUserId($user_id);
$current_balance = $wallet['balance'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipient_email = sanitize($_POST['recipient_email']);
    $amount = floatval($_POST['amount']);
    $description = sanitize($_POST['description']);
    
    // Validation
    if (empty($recipient_email) || empty($amount)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($recipient_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($amount <= 0) {
        $error = 'Amount must be greater than 0.';
    } elseif ($amount > $current_balance) {
        $error = 'Insufficient balance. Your current balance is ' . formatCurrency($current_balance);
    } else {
        // Check if recipient exists
        $recipient = $db->getUserByEmail($recipient_email);
        
        if (!$recipient) {
            $error = 'Recipient not found. Please check the email address.';
        } elseif ($recipient['id'] == $user_id) {
            $error = 'You cannot send money to yourself.';
        } else {
            // Transfer money
            if ($db->transferMoney($user_id, $recipient['id'], $amount, $description)) {
                $success = 'Money sent successfully! Transaction ID: ' . generateTransactionId();
                // Update current balance for display
                $current_balance -= $amount;
            } else {
                $error = 'Transaction failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Money - PayClone</title>
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

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .header h1 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #666;
        }

        .balance-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .balance-info h3 {
            margin-bottom: 0.5rem;
        }

        .balance-amount {
            font-size: 2rem;
            font-weight: bold;
        }

        .send-form {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
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

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .amount-input {
            font-size: 1.5rem !important;
            font-weight: bold;
            text-align: center;
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

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
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

        .back-btn {
            display: inline-block;
            margin-bottom: 2rem;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        .quick-amounts {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .quick-amount {
            padding: 8px 16px;
            background: #f8f9fa;
            border: 2px solid #e1e5e9;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .quick-amount:hover {
            border-color: #667eea;
            background: #667eea;
            color: white;
        }

        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .quick-amounts {
                justify-content: center;
            }

            .balance-amount {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        
        <div class="header">
            <h1>Send Money</h1>
            <p>Transfer funds securely to friends and family</p>
        </div>
        
        <div class="success-message">
            <h4>✅ File-Based System Active!</h4>
            <p>All transactions are processed instantly without database setup.</p>
        </div>
        
        <div class="balance-info">
            <h3>Available Balance</h3>
            <div class="balance-amount"><?php echo formatCurrency($current_balance); ?></div>
        </div>
        
        <form class="send-form" method="POST" action="">
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="recipient_email">Recipient Email Address</label>
                <input type="email" id="recipient_email" name="recipient_email" required 
                       value="<?php echo isset($_POST['recipient_email']) ? htmlspecialchars($_POST['recipient_email']) : ''; ?>"
                       placeholder="Enter recipient's email address">
            </div>
            
            <div class="form-group">
                <label for="amount">Amount ($)</label>
                <div class="quick-amounts">
                    <span class="quick-amount" onclick="setAmount(10)">$10</span>
                    <span class="quick-amount" onclick="setAmount(25)">$25</span>
                    <span class="quick-amount" onclick="setAmount(50)">$50</span>
                    <span class="quick-amount" onclick="setAmount(100)">$100</span>
                </div>
                <input type="number" id="amount" name="amount" class="amount-input" 
                       min="0.01" step="0.01" max="<?php echo $current_balance; ?>" required
                       value="<?php echo isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : ''; ?>"
                       placeholder="0.00">
            </div>
            
            <div class="form-group">
                <label for="description">Description (Optional)</label>
                <textarea id="description" name="description" rows="3" 
                          placeholder="What's this payment for?"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>
            
            <button type="submit" class="btn" id="send-btn">Send Money</button>
        </form>
    </div>

    <script>
        // Quick amount selection
        function setAmount(amount) {
            document.getElementById('amount').value = amount;
            validateAmount();
        }

        // Amount validation
        function validateAmount() {
            const amount = parseFloat(document.getElementById('amount').value);
            const maxBalance = <?php echo $current_balance; ?>;
            const sendBtn = document.getElementById('send-btn');
            
            if (amount > maxBalance) {
                sendBtn.disabled = true;
                sendBtn.textContent = 'Insufficient Balance';
            } else if (amount <= 0 || isNaN(amount)) {
                sendBtn.disabled = true;
                sendBtn.textContent = 'Enter Valid Amount';
            } else {
                sendBtn.disabled = false;
                sendBtn.textContent = 'Send Money';
            }
        }

        // Event listeners
        document.getElementById('amount').addEventListener('input', validateAmount);

        // Form submission
        document.querySelector('.send-form').addEventListener('submit', function(e) {
            const amount = parseFloat(document.getElementById('amount').value);
            const maxBalance = <?php echo $current_balance; ?>;
            
            if (amount > maxBalance) {
                e.preventDefault();
                alert('Insufficient balance!');
                return false;
            }
            
            if (!confirm(`Are you sure you want to send $${amount.toFixed(2)}?`)) {
                e.preventDefault();
                return false;
            }
            
            document.getElementById('send-btn').textContent = 'Processing...';
            document.getElementById('send-btn').disabled = true;
        });

        // Auto-redirect after successful transaction
        <?php if ($success): ?>
        setTimeout(function() {
            window.location.href = 'dashboard.php';
        }, 3000);
        <?php endif; ?>
    </script>
</body>
</html>
