# 📝 TodoList Mobile - PHP Native V1

Application todolist moderne et responsive optimisée pour mobile, développée en PHP natif sans framework.

## ✨ Fonctionnalités

- ✅ Ajouter des tâches avec titre et description
- ✅ Marquer les tâches comme terminées
- ✅ Supprimer des tâches
- ✅ Filtrer par statut (Toutes / En cours / Terminées)
- ✅ Statistiques en temps réel
- ✅ Interface responsive optimisée mobile
- ✅ Stockage JSON (pas de base de données requise)
- ✅ API REST complète
- ✅ Animations fluides et design moderne

## 🚀 Installation

### Prérequis

- PHP 7.4 ou supérieur
- Serveur web (Apache, Nginx, ou PHP built-in server)
- Extensions PHP : json (généralement activée par défaut)

### Installation rapide

1. **Cloner ou télécharger le projet**

```bash
git clone [url-du-repo]
cd TodoApp-PHP-Native-V1
```

2. **Donner les permissions d'écriture au dossier data**

```bash
chmod 777 data
```

3. **Lancer le serveur**

#### Option 1 : Serveur PHP intégré (développement)

```bash
php -S localhost:8000
```

Accédez à l'application : http://localhost:8000

#### Option 2 : Apache/Nginx (production)

Configurez votre virtual host pour pointer vers le répertoire du projet.

## 📁 Structure du projet

```
TodoApp-PHP-Native-V1/
├── api/
│   ├── todos.php          # API REST pour les todos
│   └── stats.php          # API pour les statistiques
├── css/
│   └── style.css          # Styles responsive
├── js/
│   └── app.js             # JavaScript avec AJAX
├── includes/
│   ├── config.php         # Configuration
│   └── functions.php      # Fonctions utilitaires
├── data/
│   └── todos.json         # Stockage des données (créé automatiquement)
├── index.php              # Page principale
├── .htaccess              # Configuration Apache
└── README.md              # Ce fichier
```

## 🔌 API REST

### Endpoints disponibles

#### GET /api/todos.php
Récupère toutes les tâches

**Réponse :**
```json
{
  "success": true,
  "message": "Todos récupérés",
  "data": [...]
}
```

#### GET /api/todos.php?id={id}
Récupère une tâche spécifique

#### POST /api/todos.php
Crée une nouvelle tâche

**Body :**
```json
{
  "title": "Ma tâche",
  "description": "Description optionnelle"
}
```

#### PUT /api/todos.php
Met à jour une tâche

**Body :**
```json
{
  "id": "todo_xxx",
  "title": "Nouveau titre",
  "completed": true
}
```

#### DELETE /api/todos.php
Supprime une tâche

**Body :**
```json
{
  "id": "todo_xxx"
}
```

#### GET /api/stats.php
Récupère les statistiques

**Réponse :**
```json
{
  "success": true,
  "data": {
    "total": 10,
    "completed": 5,
    "pending": 5,
    "percentage": 50
  }
}
```

## 🎨 Personnalisation

### Modifier les couleurs

Éditez les variables CSS dans `css/style.css` :

```css
:root {
    --primary-color: #4a90e2;
    --success-color: #4caf50;
    --danger-color: #f44336;
    /* ... */
}
```

### Changer le stockage

Par défaut, l'application utilise JSON. Pour utiliser une base de données :

1. Modifiez `includes/functions.php`
2. Remplacez les fonctions `getTodos()` et `saveTodos()` par des requêtes SQL

## 📱 Optimisations Mobile

- Interface tactile optimisée
- Viewport configuré pour mobile
- Animations et transitions fluides
- Design responsive (breakpoint à 480px)
- Pas de zoom involontaire sur les inputs
- Support des gestes tactiles

## 🔒 Sécurité

- Échappement HTML automatique
- Protection XSS
- Headers de sécurité (via .htaccess)
- Validation des entrées
- Protection du dossier data
- Pas d'injection SQL (utilisation de JSON)

## 🐛 Dépannage

### Erreur "Permission denied" sur data/
```bash
chmod 777 data
```

### Les todos ne se sauvegardent pas
Vérifiez que PHP a les permissions d'écriture :
```bash
ls -la data/
```

### L'API ne répond pas
Vérifiez que mod_rewrite est activé :
```bash
a2enmod rewrite
service apache2 restart
```

## 📝 License

MIT License - Libre d'utilisation

## 👨‍💻 Auteur

Application développée en PHP natif - Version 1.0.0

## 🚧 Roadmap

- [ ] Mode hors-ligne (PWA)
- [ ] Catégories de tâches
- [ ] Dates d'échéance
- [ ] Priorités
- [ ] Recherche de tâches
- [ ] Export/Import JSON
- [ ] Mode sombre/clair
- [ ] Multi-utilisateurs avec authentification
