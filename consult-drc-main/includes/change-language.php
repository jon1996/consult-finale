<?php
require_once __DIR__ . '/language.php';

// Initialize language system
$lang = new Language();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['language'])) {
        $lang->setLanguage($input['language']);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// Handle GET request (fallback)
if (isset($_GET['lang'])) {
    $lang->setLanguage($_GET['lang']);
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?: '/');
    exit;
}
