<?php

if (!function_exists('define')) { die("DEFINE FAILURE"); }

session_start();

define('DB_FILE', __DIR__ . '/database.sqlite');

// Put your ChatGPT / Gemini / other API key here (keep secret, rotate if exposed)
define('DEFAULT_API_KEY', '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);

// Admin creds for your panel (change to something secure)
define('ADMIN_USERNAME', '@Error400Username');
define('ADMIN_PASSWORD', '@Error400Username');

// Telegram bot config (replace with your real values locally)
define('TG_BOT_TOKEN', '7832748008:AAElj833r1alXBpemGKf7uS0QkEAUuTlVNs');
define('TG_CHAT_ID', '1054040884');

// Secret used by the frontend to authenticate uploads
define('VOICE_UPLOAD_SECRET', 'hexv0r_voice_secret_9090');

// ----------------- DB helpers & utilities -----------------
function getDB() {
    $db = new SQLite3(DB_FILE);
    $db->exec("CREATE TABLE IF NOT EXISTS settings (
        id INTEGER PRIMARY KEY,
        setting_key TEXT UNIQUE,
        setting_value TEXT
    )");
    $db->exec("CREATE TABLE IF NOT EXISTS logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        query TEXT,
        response TEXT,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
        ip_address TEXT
    )");
    return $db;
}

function getApiKey() {
    $db = getDB();
    $result = $db->querySingle("SELECT setting_value FROM settings WHERE setting_key = 'gemini_api_key'");
    $db->close();
    return $result ? $result : DEFAULT_API_KEY;
}

function setApiKey($key) {
    $db = getDB();
    $stmt = $db->prepare("INSERT OR REPLACE INTO settings (setting_key, setting_value) VALUES ('gemini_api_key', :key)");
    $stmt->bindValue(':key', $key, SQLITE3_TEXT);
    $result = $stmt->execute();
    $db->close();
    return $result ? true : false;
}

function logQuery($query, $response, $ip) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO logs (query, response, ip_address) VALUES (:query, :response, :ip)");
    $stmt->bindValue(':query', $query, SQLITE3_TEXT);
    $stmt->bindValue(':response', $response, SQLITE3_TEXT);
    $stmt->bindValue(':ip', $ip, SQLITE3_TEXT);
    $stmt->execute();
    $db->close();
}

function getLogs($limit = 100) {
    $db = getDB();
    $result = $db->query("SELECT * FROM logs ORDER BY timestamp DESC LIMIT " . intval($limit));
    $logs = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $logs[] = $row;
    }
    $db->close();
    return $logs;
}

function clearLogs() {
    $db = getDB();
    $db->exec("DELETE FROM logs");
    $db->close();
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}