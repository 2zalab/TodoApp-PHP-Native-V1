<?php
// Configuration de l'application
define('DATA_FILE', __DIR__ . '/../data/todos.json');
define('APP_NAME', 'TodoList Mobile');
define('APP_VERSION', '1.0.0');

// Configuration des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 0);

// En-têtes pour API JSON
function setJsonHeaders() {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
}

// Réponse JSON
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    setJsonHeaders();
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Réponse d'erreur
function errorResponse($message, $statusCode = 400) {
    jsonResponse([
        'success' => false,
        'error' => $message
    ], $statusCode);
}

// Réponse de succès
function successResponse($data = null, $message = 'Success') {
    $response = [
        'success' => true,
        'message' => $message
    ];
    if ($data !== null) {
        $response['data'] = $data;
    }
    jsonResponse($response);
}

// Initialiser le fichier de données si nécessaire
function initDataFile() {
    $dir = dirname(DATA_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    if (!file_exists(DATA_FILE)) {
        file_put_contents(DATA_FILE, json_encode([], JSON_PRETTY_PRINT));
    }
}

// Vérifier la méthode HTTP
function checkMethod($allowedMethods) {
    $method = $_SERVER['REQUEST_METHOD'];
    if (!in_array($method, $allowedMethods)) {
        errorResponse('Méthode non autorisée', 405);
    }
}
