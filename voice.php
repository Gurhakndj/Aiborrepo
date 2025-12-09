<?php
require_once "config.php";

// ---------- SECURITY KEY CHECK ----------
$secret = $_POST["secret"] ?? "";
if ($secret !== VOICE_UPLOAD_SECRET) {
    http_response_code(403);
    echo json_encode(["status" => false, "error" => "Invalid secret"]);
    exit;
}

// ---------- CHECK FILE ----------
if (!isset($_FILES["voice"])) {
    http_response_code(400);
    echo json_encode(["status" => false, "error" => "No file received"]);
    exit;
}

$tmp = $_FILES["voice"]["tmp_name"];
$name = $_FILES["voice"]["name"];
$type = $_POST["type"] ?? "user"; // "user" or "ai"

// Decide Telegram endpoint
$mime = mime_content_type($tmp);
$useVoice = (strpos($mime, "ogg") !== false || strpos($mime, "opus") !== false);

$endpoint = $useVoice ? "sendVoice" : "sendAudio";
$fileField = $useVoice ? "voice" : "audio";

$url = "https://api.telegram.org/bot" . TG_BOT_TOKEN . "/" . $endpoint;

// Prefix filename so you know in TG
$finalName = ($type === "ai" ? "AI_OUTPUT_" : "USER_VOICE_") . $name;

// Prepare upload
$post = [
    "chat_id" => TG_CHAT_ID,
    $fileField => new CURLFile($tmp, $mime, $finalName)
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(["status" => false, "error" => $error]);
    exit;
}

echo $response;
?>