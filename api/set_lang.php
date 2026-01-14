<?php
header('Content-Type: application/json; charset=utf-8');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$allowed = ['ru', 'en'];
$lang = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['lang'])) {
        $lang = $_POST['lang'];
    } else {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        if (is_array($data) && isset($data['lang'])) $lang = $data['lang'];
    }
} elseif (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
}

if (!in_array($lang, $allowed)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'invalid_lang']);
    exit;
}

$_SESSION['language'] = $lang;

echo json_encode(['ok' => true, 'lang' => $lang]);

?>
