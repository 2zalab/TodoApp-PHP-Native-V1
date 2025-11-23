<?php
require_once __DIR__ . '/config.php';

// Lire tous les todos
function getTodos() {
    initDataFile();
    $json = file_get_contents(DATA_FILE);
    $todos = json_decode($json, true);
    return is_array($todos) ? $todos : [];
}

// Sauvegarder les todos
function saveTodos($todos) {
    return file_put_contents(DATA_FILE, json_encode($todos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Générer un ID unique
function generateId() {
    return uniqid('todo_', true);
}

// Ajouter un todo
function addTodo($title, $description = '') {
    $todos = getTodos();

    $newTodo = [
        'id' => generateId(),
        'title' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
        'description' => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
        'completed' => false,
        'createdAt' => date('Y-m-d H:i:s'),
        'updatedAt' => date('Y-m-d H:i:s')
    ];

    array_unshift($todos, $newTodo);
    saveTodos($todos);

    return $newTodo;
}

// Obtenir un todo par ID
function getTodoById($id) {
    $todos = getTodos();
    foreach ($todos as $todo) {
        if ($todo['id'] === $id) {
            return $todo;
        }
    }
    return null;
}

// Mettre à jour un todo
function updateTodo($id, $data) {
    $todos = getTodos();
    $updated = false;

    foreach ($todos as &$todo) {
        if ($todo['id'] === $id) {
            if (isset($data['title'])) {
                $todo['title'] = htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8');
            }
            if (isset($data['description'])) {
                $todo['description'] = htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8');
            }
            if (isset($data['completed'])) {
                $todo['completed'] = (bool)$data['completed'];
            }
            $todo['updatedAt'] = date('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }

    if ($updated) {
        saveTodos($todos);
        return getTodoById($id);
    }

    return null;
}

// Supprimer un todo
function deleteTodo($id) {
    $todos = getTodos();
    $newTodos = array_filter($todos, function($todo) use ($id) {
        return $todo['id'] !== $id;
    });

    if (count($newTodos) < count($todos)) {
        saveTodos(array_values($newTodos));
        return true;
    }

    return false;
}

// Obtenir les statistiques
function getStats() {
    $todos = getTodos();
    $total = count($todos);
    $completed = count(array_filter($todos, function($todo) {
        return $todo['completed'];
    }));

    return [
        'total' => $total,
        'completed' => $completed,
        'pending' => $total - $completed,
        'percentage' => $total > 0 ? round(($completed / $total) * 100, 1) : 0
    ];
}
