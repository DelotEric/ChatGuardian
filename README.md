# ChatGuardian — Prototype visuel

Cette branche contient un prototype statique (HTML/Blade + Bootstrap) et un squelette Laravel-ready (migrations, modèles, routes, contrôleurs) pour poser le style et la structure des premières pages :

- **Connexion** (maquette de l'écran de login Laravel classique)
- **Accueil / dashboard** avec quelques indicateurs et actions rapides
- **Gestion des bénévoles** (liste + modal d'ajout)

## Structure
- `resources/views/layouts/app.blade.php` : layout commun (header, navigation, footer)
- `resources/views/auth/login.blade.php` : page de connexion
- `resources/views/dashboard.blade.php` : accueil / aperçu rapide
- `resources/views/volunteers/index.blade.php` : vue liste des bénévoles
- `resources/views/cats/index.blade.php` : fiches chats avec formulaire rapide
- `resources/views/foster_families/index.blade.php` : familles d'accueil
- `resources/views/donations/index.blade.php` : dons et reçus
- `resources/views/feeding_points/index.blade.php` : points de nourrissage avec bénévoles
- `database/migrations/*.php` : tables chats, familles, séjours, bénévoles, dons, donateurs, points de nourrissage
- `app/Models/*` : modèles Eloquent avec relations
- `app/Http/Controllers/*` : contrôleurs minimalistes pour les formulaires
- `public/css/app.css` : palette et styles spécifiques ChatGuardian
- `public/js/app.js` : point d'entrée JS (placeholder)
- `routes/web.php` : exemples de routes si intégré dans un squelette Laravel

### Nouveautés
- Carte Leaflet intégrée sur la page "Points de nourrissage" (affiche les marqueurs depuis la base)
- Génération d'un PDF de contrat de famille d'accueil (barryvdh/laravel-dompdf)

### Intégration rapide
1. Installer Laravel et les dépendances PDF :
   ```bash
   composer require barryvdh/laravel-dompdf
   ```
2. Lancer les migrations puis les pages :
   ```bash
   php artisan migrate
   php artisan serve
   ```
3. Tester la génération de contrat :
   - Créer au moins une famille d'accueil via `/foster-families`
   - Télécharger le PDF : `/foster-families/{id}/contract`
4. Visualiser les points de nourrissage avec Leaflet : `/feeding-points`

## Prochaines étapes suggérées
1. Installer un vrai projet Laravel (Composer) et brancher ces vues sur l'auth Laravel.
2. Ajouter les migrations + modèles pour les bénévoles, chats, familles, dons.
3. Remplacer les données statiques par des données réelles + formulaires validés.
