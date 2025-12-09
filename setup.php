<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre style='background: #0a0a0f; color: #00f5ff; padding: 30px; font-family: monospace; min-height: 100vh;'>";
echo "========================================\n";
echo "  TOIB AI - SETUP WIZARD\n";
echo "========================================\n\n";

$success = true;

echo "[CHECKING] PHP Version... ";
if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
    echo "OK (PHP " . PHP_VERSION . ")\n";
} else {
    echo "WARNING - PHP 7.4+ recommended (Current: " . PHP_VERSION . ")\n";
}

echo "[CHECKING] SQLite3 Extension... ";
if (extension_loaded('sqlite3')) {
    echo "OK\n";
} else {
    echo "MISSING - SQLite3 extension required!\n";
    $success = false;
}

echo "[CHECKING] cURL Extension... ";
if (extension_loaded('curl')) {
    echo "OK\n";
} else {
    echo "MISSING - cURL extension required for API calls!\n";
    $success = false;
}

echo "[CHECKING] Session Support... ";
if (function_exists('session_start')) {
    echo "OK\n";
} else {
    echo "MISSING - Session support required!\n";
    $success = false;
}

echo "\n[SETUP] Creating database...\n";

try {
    $dbFile = __DIR__ . '/database.sqlite';
    $db = new SQLite3($dbFile);
    
    $db->exec("CREATE TABLE IF NOT EXISTS settings (
        id INTEGER PRIMARY KEY,
        setting_key TEXT UNIQUE,
        setting_value TEXT
    )");
    echo "  - Settings table: OK\n";
    
    $db->exec("CREATE TABLE IF NOT EXISTS logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        query TEXT,
        response TEXT,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
        ip_address TEXT
    )");
    echo "  - Logs table: OK\n";
    
    $result = $db->querySingle("SELECT setting_value FROM settings WHERE setting_key = 'gemini_api_key'");
    if (!$result) {
        $defaultKey = 'AIzaSyAfzdkRV8LtS7Ns6eBStj2mwZ8-vCJgaYw';
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('gemini_api_key', :key)");
        $stmt->bindValue(':key', $defaultKey, SQLITE3_TEXT);
        $stmt->execute();
        echo "  - Default API key set: OK\n";
    } else {
        echo "  - API key already configured: OK\n";
    }
    
    $db->close();
    
    chmod($dbFile, 0666);
    echo "  - Database permissions: OK\n";
    
} catch (Exception $e) {
    echo "  - ERROR: " . $e->getMessage() . "\n";
    $success = false;
}

echo "\n[CHECKING] File permissions...\n";
$files = ['index.php', 'backend.php', 'adminpanel.php', 'config.php'];
foreach ($files as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "  - $file: OK\n";
    } else {
        echo "  - $file: MISSING\n";
        $success = false;
    }
}

echo "\n========================================\n";
if ($success) {
    echo "  SETUP COMPLETED SUCCESSFULLY!\n";
    echo "========================================\n\n";
    echo "Your TOIB AI system is ready.\n\n";
    echo "Quick Links:\n";
    echo "  - Main Interface: <a href='index.php' style='color: #8000ff;'>index.php</a>\n";
    echo "  - Admin Panel: <a href='adminpanel.php' style='color: #8000ff;'>adminpanel.php</a>\n\n";
    echo "Admin Credentials:\n";
    echo "  Username: @Error400Username\n";
    echo "  Password: DM @Error400Username\n\n";
    echo "IMPORTANT: Delete this setup.php file after installation!\n";
} else {
    echo "  SETUP FAILED - FIX ERRORS ABOVE\n";
    echo "========================================\n";
}

echo "</pre>";
?>
