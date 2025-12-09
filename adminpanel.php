<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: adminpanel.php');
            exit;
        } else {
            $error = 'Invalid credentials. Access denied.';
        }
    }
    
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: adminpanel.php');
        exit;
    }
    
    if (isset($_POST['update_api_key']) && isAdminLoggedIn()) {
        $newKey = trim($_POST['api_key'] ?? '');
        if (!empty($newKey)) {
            if (setApiKey($newKey)) {
                $success = 'API Key updated successfully!';
            } else {
                $error = 'Failed to update API key.';
            }
        } else {
            $error = 'Please enter a valid API key.';
        }
    }
    
    if (isset($_POST['clear_logs']) && isAdminLoggedIn()) {
        clearLogs();
        $success = 'All logs cleared successfully!';
    }
}

$logs = isAdminLoggedIn() ? getLogs(100) : [];
$currentApiKey = isAdminLoggedIn() ? getApiKey() : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - TOIB AI</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Rajdhani:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-cyan: #00f5ff;
            --primary-blue: #0080ff;
            --primary-purple: #8000ff;
            --dark-bg: #0a0a0f;
            --darker-bg: #050508;
            --glass-bg: rgba(0, 245, 255, 0.05);
            --danger: #ff3232;
            --success: #00ff88;
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: var(--darker-bg);
            color: #fff;
            min-height: 100vh;
            padding: 20px;
        }

        .grid-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                linear-gradient(90deg, rgba(0, 245, 255, 0.02) 1px, transparent 1px),
                linear-gradient(rgba(0, 245, 255, 0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 0;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: var(--glass-bg);
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 20px;
        }

        .header h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            background: linear-gradient(135deg, var(--primary-cyan), var(--primary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.6);
        }

        .card {
            background: var(--glass-bg);
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }

        .card h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.3rem;
            color: var(--primary-cyan);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(0, 245, 255, 0.2);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 1rem;
            color: #fff;
            background: rgba(0, 245, 255, 0.05);
            border: 2px solid rgba(0, 245, 255, 0.3);
            border-radius: 10px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-cyan);
            box-shadow: 0 0 20px rgba(0, 245, 255, 0.2);
        }

        .btn {
            padding: 15px 30px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            color: var(--darker-bg);
            background: linear-gradient(135deg, var(--primary-cyan), var(--primary-blue));
        }

        .btn-primary:hover {
            box-shadow: 0 0 30px rgba(0, 245, 255, 0.5);
            transform: translateY(-2px);
        }

        .btn-danger {
            color: #fff;
            background: linear-gradient(135deg, var(--danger), #ff6b6b);
        }

        .btn-danger:hover {
            box-shadow: 0 0 30px rgba(255, 50, 50, 0.5);
            transform: translateY(-2px);
        }

        .btn-secondary {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .alert-error {
            background: rgba(255, 50, 50, 0.1);
            border: 1px solid rgba(255, 50, 50, 0.3);
            color: #ff6b6b;
        }

        .alert-success {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid rgba(0, 255, 136, 0.3);
            color: var(--success);
        }

        .api-key-display {
            background: rgba(0, 0, 0, 0.3);
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
            margin-bottom: 20px;
            border: 1px solid rgba(0, 245, 255, 0.2);
        }

        .logs-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .logs-table th,
        .logs-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(0, 245, 255, 0.1);
        }

        .logs-table th {
            background: rgba(0, 245, 255, 0.1);
            font-family: 'Orbitron', sans-serif;
            font-size: 0.85rem;
            color: var(--primary-cyan);
            text-transform: uppercase;
        }

        .logs-table td {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .logs-table tr:hover {
            background: rgba(0, 245, 255, 0.05);
        }

        .query-cell {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .response-cell {
            max-width: 400px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .timestamp-cell {
            white-space: nowrap;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
        }

        .no-logs {
            text-align: center;
            padding: 40px;
            color: rgba(255, 255, 255, 0.5);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-cyan);
            text-decoration: none;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #fff;
        }

        .login-container {
            max-width: 400px;
            margin: 100px auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(0, 245, 255, 0.05);
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
        }

        .stat-value {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            color: var(--primary-cyan);
            margin-bottom: 5px;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .actions-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .logs-table {
                display: block;
                overflow-x: auto;
            }

            .actions-row {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="grid-overlay"></div>

    <div class="container">
        <?php if (!isAdminLoggedIn()): ?>
        <div class="login-container">
            <div class="card">
                <h2>Admin Access</h2>
                
                <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary" style="width: 100%;">Login</button>
                </form>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="index.php" class="back-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Back to AI Interface
                </a>
            </div>
        </div>
        <?php else: ?>
        <a href="index.php" class="back-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back to AI Interface
        </a>

        <div class="header">
            <h1>TOIB AI - Admin Panel</h1>
            <p>System Configuration and Monitoring</p>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo count($logs); ?></div>
                <div class="stat-label">Total Queries</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo count(array_unique(array_column($logs, 'ip_address'))); ?></div>
                <div class="stat-label">Unique Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">Active</div>
                <div class="stat-label">System Status</div>
            </div>
        </div>

        <div class="card">
            <h2>Gemini API Configuration</h2>
            
            <div class="api-key-display">
                <strong>Current API Key:</strong><br>
                <?php echo htmlspecialchars(substr($currentApiKey, 0, 20) . '...' . substr($currentApiKey, -10)); ?>
            </div>

            <form method="POST">
                <div class="form-group">
                    <label>New API Key</label>
                    <input type="text" name="api_key" class="form-control" placeholder="Enter new Gemini API key">
                </div>
                <button type="submit" name="update_api_key" class="btn btn-primary">Update API Key</button>
            </form>
        </div>

        <div class="card">
            <h2>System Logs</h2>
            
            <div class="actions-row" style="margin-bottom: 20px;">
                <form method="POST" style="display: inline;">
                    <button type="submit" name="clear_logs" class="btn btn-danger" onclick="return confirm('Are you sure you want to clear all logs?');">Clear All Logs</button>
                </form>
                <form method="POST" style="display: inline;">
                    <button type="submit" name="logout" class="btn btn-secondary">Logout</button>
                </form>
            </div>

            <?php if (empty($logs)): ?>
            <div class="no-logs">No logs recorded yet.</div>
            <?php else: ?>
            <table class="logs-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Query</th>
                        <th>Response</th>
                        <th>IP Address</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo $log['id']; ?></td>
                        <td class="query-cell" title="<?php echo htmlspecialchars($log['query']); ?>">
                            <?php echo htmlspecialchars(substr($log['query'], 0, 50)); ?>...
                        </td>
                        <td class="response-cell" title="<?php echo htmlspecialchars($log['response']); ?>">
                            <?php echo htmlspecialchars(substr($log['response'], 0, 60)); ?>...
                        </td>
                        <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                        <td class="timestamp-cell"><?php echo $log['timestamp']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
