<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TodoList Mobile - Laravel</title>

    <!-- PWA Meta Tags -->
    <meta name="description" content="Application de gestion de tâches mobile - TodoList">
    <meta name="theme-color" content="#4a90e2">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="TodoList">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- PWA Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icons/icon-512x512.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1>=� TodoList Mobile</h1>
            <div class="stats" id="stats">
                <span class="stat">
                    <strong id="totalTodos">0</strong>
                    <small>Total</small>
                </span>
                <span class="stat">
                    <strong id="completedTodos">0</strong>
                    <small>Termin�es</small>
                </span>
                <span class="stat">
                    <strong id="pendingTodos">0</strong>
                    <small>En cours</small>
                </span>
            </div>
        </header>

        <!-- Formulaire d'ajout -->
        <div class="add-todo-form">
            <form id="todoForm">
                <input
                    type="text"
                    id="todoTitle"
                    placeholder="Titre de la t�che..."
                    required
                    autocomplete="off"
                >
                <textarea
                    id="todoDescription"
                    placeholder="Description (optionnel)..."
                    rows="2"
                ></textarea>
                <button type="submit" class="btn btn-primary">
                    � Ajouter
                </button>
            </form>
        </div>

        <!-- Filtres -->
        <div class="filters">
            <button class="filter-btn active" data-filter="all">
                Toutes
            </button>
            <button class="filter-btn" data-filter="pending">
                En cours
            </button>
            <button class="filter-btn" data-filter="completed">
                Termin�es
            </button>
        </div>

        <!-- Liste des todos -->
        <div class="todos-container" id="todosContainer">
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Chargement...</p>
            </div>
            <div class="empty-state" id="emptyState" style="display: none;">
                <div class="empty-icon">=�</div>
                <h3>Aucune t�che</h3>
                <p>Ajoutez votre premi�re t�che pour commencer!</p>
            </div>
            <ul class="todos-list" id="todosList"></ul>
        </div>
    </div>

    <!-- Toast notifications -->
    <div class="toast" id="toast"></div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
