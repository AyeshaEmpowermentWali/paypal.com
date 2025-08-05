<?php
// Start session first
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Simple file-based database system
class Database {
    private $users;
    private $wallets;
    private $transactions;
    
    public function __construct() {
        // Initialize with hardcoded data - no file operations needed
        $this->initializeData();
    }
    
    private function initializeData() {
        // Sample users - always available
        $this->users = [
            [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'phone' => '+1234567890',
                'date_of_birth' => '1990-01-01',
                'address' => '123 Main St',
                'city' => 'New York',
                'country' => 'USA',
                'postal_code' => '10001',
                'is_verified' => true,
                'account_status' => 'active',
                'created_at' => '2024-01-01 10:00:00'
            ],
            [
                'id' => 2,
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane@example.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'phone' => '+1234567891',
                'date_of_birth' => '1992-05-15',
                'address' => '456 Oak Ave',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'postal_code' => '90001',
                'is_verified' => true,
                'account_status' => 'active',
                'created_at' => '2024-01-01 10:00:00'
            ],
            [
                'id' => 3,
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'email' => 'mike@example.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'phone' => '+1234567892',
                'date_of_birth' => '1988-12-10',
                'address' => '789 Pine St',
                'city' => 'Chicago',
                'country' => 'USA',
                'postal_code' => '60601',
                'is_verified' => true,
                'account_status' => 'active',
                'created_at' => '2024-01-01 10:00:00'
            ]
        ];
        
        // Sample wallets
        $this->wallets = [
            ['id' => 1, 'user_id' => 1, 'balance' => 1500.00, 'currency' => 'USD'],
            ['id' => 2, 'user_id' => 2, 'balance' => 2300.50, 'currency' => 'USD'],
            ['id' => 3, 'user_id' => 3, 'balance' => 850.75, 'currency' => 'USD']
        ];
        
        // Sample transactions
        $this->transactions = [
            [
                'id' => 1,
                'transaction_id' => 'TXN001',
                'sender_id' => 1,
                'receiver_id' => 2,
                'amount' => 100.00,
                'transaction_type' => 'send',
                'status' => 'completed',
                'description' => 'Payment for services',
                'created_at' => '2024-01-01 08:00:00'
            ],
            [
                'id' => 2,
                'transaction_id' => 'TXN002',
                'sender_id' => 2,
                'receiver_id' => 3,
                'amount' => 250.00,
                'transaction_type' => 'send',
                'status' => 'completed',
                'description' => 'Freelance payment',
                'created_at' => '2024-01-01 09:00:00'
            ],
            [
                'id' => 3,
                'transaction_id' => 'TXN003',
                'sender_id' => 3,
                'receiver_id' => 1,
                'amount' => 75.50,
                'transaction_type' => 'send',
                'status' => 'completed',
                'description' => 'Dinner split',
                'created_at' => '2024-01-01 11:00:00'
            ]
        ];
        
        // Store in session for persistence
        if (!isset($_SESSION['app_users'])) {
            $_SESSION['app_users'] = $this->users;
        } else {
            $this->users = $_SESSION['app_users'];
        }
        
        if (!isset($_SESSION['app_wallets'])) {
            $_SESSION['app_wallets'] = $this->wallets;
        } else {
            $this->wallets = $_SESSION['app_wallets'];
        }
        
        if (!isset($_SESSION['app_transactions'])) {
            $_SESSION['app_transactions'] = $this->transactions;
        } else {
            $this->transactions = $_SESSION['app_transactions'];
        }
    }
    
    // User methods
    public function getUserByEmail($email) {
        foreach ($this->users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }
    
    public function getUserById($id) {
        foreach ($this->users as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }
        return null;
    }
    
    public function createUser($userData) {
        $nextId = count($this->users) + 1;
        $userData['id'] = $nextId;
        $userData['created_at'] = date('Y-m-d H:i:s');
        $userData['is_verified'] = false;
        $userData['account_status'] = 'active';
        
        $this->users[] = $userData;
        $_SESSION['app_users'] = $this->users;
        
        // Create wallet for new user
        $this->createWallet($nextId);
        
        return $nextId;
    }
    
    // Wallet methods
    public function getWalletByUserId($userId) {
        foreach ($this->wallets as $wallet) {
            if ($wallet['user_id'] == $userId) {
                return $wallet;
            }
        }
        return null;
    }
    
    public function createWallet($userId) {
        $nextId = count($this->wallets) + 1;
        $wallet = [
            'id' => $nextId,
            'user_id' => $userId,
            'balance' => 0.00,
            'currency' => 'USD'
        ];
        
        $this->wallets[] = $wallet;
        $_SESSION['app_wallets'] = $this->wallets;
        
        return $nextId;
    }
    
    public function updateWalletBalance($userId, $newBalance) {
        foreach ($this->wallets as &$wallet) {
            if ($wallet['user_id'] == $userId) {
                $wallet['balance'] = $newBalance;
                break;
            }
        }
        $_SESSION['app_wallets'] = $this->wallets;
    }
    
    // Transaction methods
    public function createTransaction($transactionData) {
        $nextId = count($this->transactions) + 1;
        $transactionData['id'] = $nextId;
        $transactionData['created_at'] = date('Y-m-d H:i:s');
        
        $this->transactions[] = $transactionData;
        $_SESSION['app_transactions'] = $this->transactions;
        
        return $nextId;
    }
    
    public function getTransactionsByUserId($userId, $limit = 10) {
        $userTransactions = [];
        
        foreach ($this->transactions as $transaction) {
            if ($transaction['sender_id'] == $userId || $transaction['receiver_id'] == $userId) {
                if ($transaction['sender_id'] == $userId) {
                    $transaction['transaction_direction'] = 'sent';
                    $otherUser = $this->getUserById($transaction['receiver_id']);
                } else {
                    $transaction['transaction_direction'] = 'received';
                    $otherUser = $this->getUserById($transaction['sender_id']);
                }
                
                if ($otherUser) {
                    $transaction['other_party'] = $otherUser['first_name'] . ' ' . $otherUser['last_name'];
                }
                
                $userTransactions[] = $transaction;
            }
        }
        
        return array_slice($userTransactions, 0, $limit);
    }
    
    public function getTotalSent($userId) {
        $total = 0;
        foreach ($this->transactions as $transaction) {
            if ($transaction['sender_id'] == $userId && $transaction['status'] == 'completed') {
                $total += $transaction['amount'];
            }
        }
        return $total;
    }
    
    public function getTotalReceived($userId) {
        $total = 0;
        foreach ($this->transactions as $transaction) {
            if ($transaction['receiver_id'] == $userId && $transaction['status'] == 'completed') {
                $total += $transaction['amount'];
            }
        }
        return $total;
    }
    
    public function getTotalTransactions($userId) {
        $count = 0;
        foreach ($this->transactions as $transaction) {
            if (($transaction['sender_id'] == $userId || $transaction['receiver_id'] == $userId) && $transaction['status'] == 'completed') {
                $count++;
            }
        }
        return $count;
    }
    
    public function transferMoney($senderId, $receiverId, $amount, $description = '') {
        $senderWallet = $this->getWalletByUserId($senderId);
        $receiverWallet = $this->getWalletByUserId($receiverId);
        
        if (!$senderWallet || !$receiverWallet || $senderWallet['balance'] < $amount) {
            return false;
        }
        
        $this->updateWalletBalance($senderId, $senderWallet['balance'] - $amount);
        $this->updateWalletBalance($receiverId, $receiverWallet['balance'] + $amount);
        
        $transactionData = [
            'transaction_id' => 'TXN' . strtoupper(uniqid()),
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'amount' => $amount,
            'transaction_type' => 'send',
            'status' => 'completed',
            'description' => $description
        ];
        
        $this->createTransaction($transactionData);
        return true;
    }
}

// Helper functions
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    if ($time < 60) return 'just now';
    if ($time < 3600) return floor($time/60) . ' minutes ago';
    if ($time < 86400) return floor($time/3600) . ' hours ago';
    return floor($time/86400) . ' days ago';
}

// Initialize database
$db = new Database();
?>
