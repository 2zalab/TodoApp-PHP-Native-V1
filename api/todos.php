<?php
require_once __DIR__ . '/../includes/functions.php';

// Gérer les requêtes OPTIONS pour CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    setJsonHeaders();
    http_response_code(200);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// GET - Lire tous les todos ou un seul
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $todo = getTodoById($_GET['id']);
        if ($todo) {
            successResponse($todo, 'Todo récupéré');
        } else {
            errorResponse('Todo non trouvé', 404);
        }
    } else {
        $todos = getTodos();
        successResponse($todos, 'Todos récupérés');
    }
}

// POST - Créer un nouveau todo
elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['title']) || trim($input['title']) === '') {
        errorResponse('Le titre est requis');
    }

    $title = trim($input['title']);
    $description = isset($input['description']) ? trim($input['description']) : '';

    $newTodo = addTodo($title, $description);
    successResponse($newTodo, 'Todo créé avec succès');
}

// PUT - Mettre à jour un todo
elseif ($method === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['id'])) {
        errorResponse('ID requis');
    }

    $updatedTodo = updateTodo($input['id'], $input);

    if ($updatedTodo) {
        successResponse($updatedTodo, 'Todo mis à jour');
    } else {
        errorResponse('Todo non trouvé', 404);
    }
}

// DELETE - Supprimer un todo
elseif ($method === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['id'])) {
        errorResponse('ID requis');
    }

    $deleted = deleteTodo($input['id']);

    if ($deleted) {
        successResponse(null, 'Todo supprimé');
    } else {
        errorResponse('Todo non trouvé', 404);
    }
}

else {
    errorResponse('Méthode non autorisée', 405);
}
