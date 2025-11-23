# 🔐 Système de rôles utilisateurs - ChatGuardian

## Vue d'ensemble

Le système de rôles permet de contrôler l'accès aux différentes fonctionnalités de l'application selon le rôle de l'utilisateur.

## Rôles disponibles

### 1. **Administrateur** (`admin`)
- ✅ Accès complet à toutes les fonctionnalités
- ✅ Gestion des utilisateurs (création, modification, suppression)
- ✅ Accès à toutes les données (chats, bénévoles, dons, etc.)
- ✅ Peut modifier les rôles des autres utilisateurs

### 2. **Gestionnaire** (`manager`)
- ✅ Accès en lecture/écriture aux données de l'application
- ✅ Peut créer, modifier et supprimer les chats, bénévoles, dons, etc.
- ❌ Ne peut pas gérer les utilisateurs
- ❌ Ne peut pas accéder à la section "Utilisateurs"

### 3. **Utilisateur** (`user`)
- ✅ Accès en lecture seule aux données
- ✅ Peut consulter les listes (chats, bénévoles, dons, etc.)
- ❌ Ne peut pas créer, modifier ou supprimer de données
- ❌ Ne peut pas gérer les utilisateurs

## Implémentation technique

### Migration
La colonne `role` a été ajoutée à la table `users` avec une valeur par défaut `'user'`.

```php
$table->enum('role', ['admin', 'manager', 'user'])->default('user');
```

### Modèle User
Le modèle `User` inclut :
- Constantes pour les rôles : `ROLE_ADMIN`, `ROLE_MANAGER`, `ROLE_USER`
- Méthodes de vérification : `isAdmin()`, `isManager()`, `isAdminOrManager()`
- Méthode statique : `getRoles()` pour obtenir la liste des rôles avec leurs libellés

### Middleware
Le middleware `EnsureUserHasRole` vérifie que l'utilisateur connecté possède l'un des rôles requis pour accéder à une route.

**Utilisation :**
```php
Route::middleware('role:admin')->group(function () {
    Route::resource('users', UserController::class);
});
```

### Protection des routes

#### Routes protégées par rôle admin
- `/users` - Gestion des utilisateurs (CRUD complet)

#### Routes accessibles à tous les utilisateurs authentifiés
- `/` - Dashboard
- `/volunteers` - Bénévoles
- `/cats` - Chats
- `/foster-families` - Familles d'accueil
- `/donations` - Dons
- `/feeding-points` - Points de nourrissage
- `/profile` - Profil utilisateur

### Interface utilisateur

#### Navigation
Le lien "Utilisateurs" dans la navigation n'est visible que pour les administrateurs.

#### Formulaires
Les formulaires de création et d'édition d'utilisateur incluent un champ de sélection du rôle.

#### Affichage
- La liste des utilisateurs affiche le rôle avec un badge coloré :
  - 🔴 **Administrateur** (badge rouge)
  - 🔵 **Gestionnaire** (badge bleu)
  - ⚪ **Utilisateur** (badge gris)

### Gestion des erreurs
Une page d'erreur 403 personnalisée est affichée lorsqu'un utilisateur tente d'accéder à une ressource pour laquelle il n'a pas les permissions.

## Utilisation

### Assigner un rôle à un utilisateur

#### Via l'interface
1. Se connecter en tant qu'administrateur
2. Aller dans "Utilisateurs"
3. Créer ou modifier un utilisateur
4. Sélectionner le rôle dans le formulaire

#### Via Tinker
```php
php artisan tinker
$user = App\Models\User::where('email', 'user@example.com')->first();
$user->role = 'admin';
$user->save();
```

### Vérifier le rôle d'un utilisateur
```php
if ($user->isAdmin()) {
    // Code pour les admins
}

if ($user->isManager()) {
    // Code pour les managers
}

if ($user->isAdminOrManager()) {
    // Code pour admins et managers
}
```

### Protéger une route par rôle
```php
// Dans routes/web.php
Route::middleware('role:admin,manager')->group(function () {
    // Routes accessibles aux admins et managers
});
```

### Vérifier le rôle dans une vue Blade
```blade
@if(Auth::check() && Auth::user()->isAdmin())
    <!-- Contenu visible uniquement aux admins -->
@endif
```

## Migration des utilisateurs existants

Lors de la migration, tous les utilisateurs existants ont reçu le rôle `'user'` par défaut. L'utilisateur `admin@chatguardian.fr` a été automatiquement mis à jour avec le rôle `'admin'`.

Pour mettre à jour d'autres utilisateurs :
```php
// Mettre à jour un utilisateur spécifique
App\Models\User::where('email', 'email@example.com')->update(['role' => 'admin']);

// Mettre à jour tous les utilisateurs existants en admin (à utiliser avec précaution)
App\Models\User::query()->update(['role' => 'admin']);
```

## Sécurité

- ✅ Les routes sont protégées au niveau du middleware
- ✅ Les formulaires valident le rôle avec les règles Laravel
- ✅ L'interface masque les fonctionnalités non autorisées
- ✅ Les erreurs 403 sont gérées proprement

## Prochaines améliorations possibles

- [ ] Ajouter des permissions granulaires (ex: peut-éditer-chats, peut-supprimer-dons)
- [ ] Historique des changements de rôles
- [ ] Notifications lors de changements de rôles
- [ ] Interface de gestion des permissions par rôle
- [ ] Audit log des actions selon les rôles

