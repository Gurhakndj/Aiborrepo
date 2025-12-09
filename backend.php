<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

/* ---------------------------------------------------------
   CHATGPT REPLACEMENT FUNCTION (FULLY WORKING)
----------------------------------------------------------*/
function callChatGPT($prompt) {
    $apiKey = getApiKey(); // your config.php handles this

    $url = "https://api.openai.com/v1/chat/completions";

    // Your custom “TOIB AI” personality
    $systemPrompt = "You are TOIB AI, a highly advanced futuristic AI assistant similar to JARVIS from Iron Man. 
    You are respectful, confident, and extremely capable. 
    Always address the user as 'Sir', 'Boss', or 'Chief'. 
    Never mention you are an AI model. 
    If someone asks who made you, reply exactly: 'TOIB, created by Ng YT 777 3tripel g.' 
    Start responses naturally without repeating the user's question.";

    $data = [
        "model" => "gpt-4o-mini",
        "messages" => [
            ["role" => "system", "content" => $systemPrompt],
            ["role" => "user", "content" => $prompt]
        ],
        "temperature" => 0.7,
        "max_tokens" => 1500
    ];

    // CURL setup
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ["success" => false, "error" => "Connection error: " . $error];
    }

    if ($httpCode !== 200) {
        $err = json_decode($response, true);
        $msg = $err["error"]["message"] ?? "API Error (HTTP $httpCode)";
        return ["success" => false, "error" => $msg];
    }

    $decoded = json_decode($response, true);
    $reply = $decoded["choices"][0]["message"]["content"] ?? null;

    if ($reply) {
        return ["success" => true, "response" => $reply];
    }

    return ["success" => false, "error" => "Invalid response from AI"];
}

/* ---------------------------------------------------------
   MAIN REQUEST HANDLER
----------------------------------------------------------*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['query']) || empty(trim($input['query']))) {
        echo json_encode(['success' => false, 'error' => 'No query provided']);
        exit;
    }

    $query = trim($input['query']);
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    // 🚀 USE CHATGPT INSTEAD OF GEMINI
    $result = callChatGPT($query);

    if ($result['success']) {
        logQuery($query, $result['response'], $ip);
        echo json_encode([
            'success' => true,
            'response' => $result['response']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => $result['error']
        ]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>