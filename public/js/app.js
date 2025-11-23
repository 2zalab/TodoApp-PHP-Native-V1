// Configuration
const API_URL = '/api/todos';
const STATS_URL = '/api/stats';

// Get CSRF token
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

// État de l'application
let todos = [];
let currentFilter = 'all';

// Éléments DOM
const todoForm = document.getElementById('todoForm');
const todoTitle = document.getElementById('todoTitle');
const todoDescription = document.getElementById('todoDescription');
const todosList = document.getElementById('todosList');
const todosContainer = document.getElementById('todosContainer');
const loading = document.getElementById('loading');
const emptyState = document.getElementById('emptyState');
const toast = document.getElementById('toast');
const filterBtns = document.querySelectorAll('.filter-btn');

// Stats
const totalTodosEl = document.getElementById('totalTodos');
const completedTodosEl = document.getElementById('completedTodos');
const pendingTodosEl = document.getElementById('pendingTodos');

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    loadTodos();
    setupEventListeners();
});

// Configuration des écouteurs d'événements
function setupEventListeners() {
    // Soumission du formulaire
    todoForm.addEventListener('submit', handleAddTodo);

    // Filtres
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            currentFilter = btn.dataset.filter;
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            renderTodos();
        });
    });
}

// Charger les todos
async function loadTodos() {
    try {
        showLoading();
        const response = await fetch(API_URL);
        const data = await response.json();

        if (data.success) {
            todos = data.data;
            renderTodos();
            updateStats();
        } else {
            showToast('Erreur lors du chargement', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur de connexion', 'error');
    } finally {
        hideLoading();
    }
}

// Ajouter un todo
async function handleAddTodo(e) {
    e.preventDefault();

    const title = todoTitle.value.trim();
    const description = todoDescription.value.trim();

    if (!title) {
        showToast('Le titre est requis', 'error');
        return;
    }

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ title, description })
        });

        const data = await response.json();

        if (data.success) {
            todos.unshift(data.data);
            renderTodos();
            updateStats();
            todoForm.reset();
            showToast('Tâche ajoutée avec succès', 'success');
        } else {
            showToast(data.error || 'Erreur lors de l\'ajout', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur de connexion', 'error');
    }
}

// Toggle completed
async function toggleTodo(id) {
    const todo = todos.find(t => t.id === id);
    if (!todo) return;

    try {
        const response = await fetch(`${API_URL}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({
                completed: !todo.completed
            })
        });

        const data = await response.json();

        if (data.success) {
            todo.completed = !todo.completed;
            renderTodos();
            updateStats();
            showToast(
                todo.completed ? 'Tâche terminée ✓' : 'Tâche réactivée',
                'success'
            );
        } else {
            showToast(data.error || 'Erreur lors de la mise à jour', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur de connexion', 'error');
    }
}

// Supprimer un todo
async function deleteTodo(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
        return;
    }

    try {
        const response = await fetch(`${API_URL}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        });

        const data = await response.json();

        if (data.success) {
            todos = todos.filter(t => t.id !== id);
            renderTodos();
            updateStats();
            showToast('Tâche supprimée', 'success');
        } else {
            showToast(data.error || 'Erreur lors de la suppression', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur de connexion', 'error');
    }
}

// Afficher les todos
function renderTodos() {
    // Filtrer les todos
    let filteredTodos = todos;

    if (currentFilter === 'completed') {
        filteredTodos = todos.filter(t => t.completed);
    } else if (currentFilter === 'pending') {
        filteredTodos = todos.filter(t => !t.completed);
    }

    // Vérifier si la liste est vide
    if (filteredTodos.length === 0) {
        todosList.innerHTML = '';
        emptyState.style.display = 'block';
        return;
    }

    emptyState.style.display = 'none';

    // Créer le HTML
    todosList.innerHTML = filteredTodos.map(todo => `
        <li class="todo-item ${todo.completed ? 'completed' : ''}" data-id="${todo.id}">
            <div class="todo-header">
                <input
                    type="checkbox"
                    class="todo-checkbox"
                    ${todo.completed ? 'checked' : ''}
                    onchange="toggleTodo('${todo.id}')"
                >
                <div class="todo-content">
                    <div class="todo-title">${escapeHtml(todo.title)}</div>
                    ${todo.description ? `
                        <div class="todo-description">${escapeHtml(todo.description)}</div>
                    ` : ''}
                </div>
            </div>
            <div class="todo-meta">
                <span class="todo-date">
                    🕐 ${formatDate(todo.created_at)}
                </span>
                <div class="todo-actions">
                    <button
                        class="btn-icon btn-delete"
                        onclick="deleteTodo('${todo.id}')"
                        title="Supprimer"
                    >
                        🗑️
                    </button>
                </div>
            </div>
        </li>
    `).join('');
}

// Mettre à jour les statistiques
function updateStats() {
    const total = todos.length;
    const completed = todos.filter(t => t.completed).length;
    const pending = total - completed;

    totalTodosEl.textContent = total;
    completedTodosEl.textContent = completed;
    pendingTodosEl.textContent = pending;
}

// Utilitaires
function showLoading() {
    loading.style.display = 'block';
    todosList.innerHTML = '';
    emptyState.style.display = 'none';
}

function hideLoading() {
    loading.style.display = 'none';
}

function showToast(message, type = 'success') {
    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000); // différence en secondes

    if (diff < 60) {
        return 'À l\'instant';
    } else if (diff < 3600) {
        const minutes = Math.floor(diff / 60);
        return `Il y a ${minutes} min`;
    } else if (diff < 86400) {
        const hours = Math.floor(diff / 3600);
        return `Il y a ${hours}h`;
    } else if (diff < 604800) {
        const days = Math.floor(diff / 86400);
        return `Il y a ${days}j`;
    } else {
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: 'short'
        });
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Service Worker pour PWA (optionnel)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {
            // Service worker non disponible, continuer normalement
        });
    });
}
