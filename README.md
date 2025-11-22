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
- `resources/views/cats/show.blade.php` : fiche chat détaillée (identité, séjours, galerie photos)
- `resources/views/foster_families/index.blade.php` : familles d'accueil
- `resources/views/donations/index.blade.php` : dons et reçus
- `resources/views/donors/index.blade.php` : gestion des donateurs
- `resources/views/feeding_points/index.blade.php` : points de nourrissage avec bénévoles
- `resources/views/stocks/index.blade.php` : suivi des stocks (croquettes, litière, soins, matériel)
- `resources/views/users/index.blade.php` : gestion des comptes utilisateurs (admin-only)
- `database/migrations/*.php` : tables chats, familles, séjours, bénévoles, dons, donateurs, points de nourrissage
- `database/migrations/2024_01_01_000008_create_cat_photos_table.php` : photos associées aux chats (max 3)
- `database/migrations/2024_01_01_000009_create_stock_items_table.php` : articles de stock avec seuil de réappro
- `app/Models/*` : modèles Eloquent avec relations
- `app/Http/Controllers/*` : contrôleurs minimalistes pour les formulaires
- `public/css/app.css` : palette et styles spécifiques ChatGuardian
- `public/js/app.js` : point d'entrée JS (placeholder)
- `routes/web.php` : exemples de routes si intégré dans un squelette Laravel

### Nouveautés
- Carte Leaflet intégrée sur la page "Points de nourrissage" (affiche les marqueurs depuis la base)
- Génération d'un PDF de contrat de famille d'accueil (barryvdh/laravel-dompdf)
- Reçus fiscaux en PDF pour chaque don et export CSV des dons/donateurs (liste donateurs via `/donors/export`)
- Envoi d'un reçu fiscal par email au donateur (PDF en pièce jointe depuis la liste des dons)
- Écran dédié aux donateurs (création, édition, suppression protégée si dons liés)
- Réinitialisation de mot de passe Laravel (envoi d'email + formulaire de nouveau mot de passe)
- Galerie photo par chat avec téléversement (3 Mo max par image, 3 photos par profil) et suppression
- Gestion des séjours : ajout d'un passage en famille d'accueil et clôture avec résultat/notes
- Édition complète d'une fiche chat (statut, santé) et mise à jour du statut directement lors de l'ajout ou la clôture d'un séjour
- Édition/suppression des bénévoles, familles d'accueil, dons et points de nourrissage via formulaires préremplis (modals) et
  contrôles de rôle admin
- Module stocks : inventaire des fournitures (cartes d'alerte, tableau, formulaires d'ajout/édition/suppression) et page HTML de
  démonstration
- Alerte email stocks faibles : bouton sur la page stocks, commande `stocks:alert` (planifiée à 06h50) pour prévenir les admins/bénévoles
- Administration des utilisateurs : création/édition/suppression des comptes et rôles (admin, bénévole, famille) et maquette HTML dédiée
- Dashboard enrichi : graphiques Chart.js pour la répartition des statuts des chats et l'évolution mensuelle des dons (données réelles)
- Profil association éditable : page `/settings/organization` (admin) pour saisir coordonnées/SIRET/IBAN/BIC, injectés automatiquement dans les reçus fiscaux PDF, contrats et emails + maquette statique `public/settings.html`.
- Suivi vétérinaire par chat : visites, coûts, notes et pièces jointes (PDF/image) avec formulaire d'ajout/édition/suppression depuis la fiche.
- Export CSV des visites vétérinaires par chat (admin/bénévole) et KPIs dédiés sur le tableau de bord (coûts et visites du mois + dernières visites).
- Gestion des adoptions : enregistrement d'un adoptant, génération du contrat PDF, mise à jour du statut du chat et KPIs/dernières adoptions sur le dashboard.
- Recherche globale : barre de recherche dans la navigation (connecté) pour retrouver rapidement chats, familles, bénévoles et donateurs.
- Fiche PDF par chat + export CSV complet des chats (statut, santé, séjours, adoption) pour partager ou sauvegarder les dossiers.
- Journal d'activités : traçabilité des actions clés (création/édition de chat, séjours, visites véto, dons, reçus envoyés) visible sur le dashboard et la fiche chat.
- Export CSV des bénévoles et des familles d'accueil pour diffuser facilement listes et coordonnées.
- Rappels de suivi par chat : création/édition/suppression de rappels (vaccins, suivi adoption, visites véto) avec tableau dédié sur la fiche chat, cartes sur le dashboard et données démo.
- Vue consolidée des rappels : écran `/reminders` (admin/bénévole) avec filtres statut/échéance/type, actions de clôture et lien direct vers chaque fiche chat, plus maquette statique `public/reminders.html`.
- Envoi d'un email récapitulatif des rappels (aujourd'hui, 7 prochains jours ou en retard) pour les admins et bénévoles depuis `/reminders`.
- Agenda centralisé : page `/calendar` (admin/bénévole) avec vue FullCalendar des rappels et visites véto, navigation rapide (aujourd'hui, mois précédent/suivant) et liste des 10 prochains événements, plus maquette statique `public/calendar.html` et export ICS.
- Commande Artisan `reminders:digest` et planification quotidienne à 07h pour envoyer automatiquement le récapitulatif des rappels.
- Journal d'activités dédié : écran `/activities` (admin/bénévole) avec filtres, pagination et export CSV, plus maquette statique `public/activities.html`.

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
3. Alimenter la base avec un jeu de données démo (inclut 3 utilisateurs de rôle admin/bénévole/famille) :
   ```bash
   php artisan db:seed
   ```
    (Les seeders ajoutent aussi quelques photos fictives de chats pour la démo, un inventaire de base pour les stocks et un profil d'association par défaut modifiable.)
   Identifiants prêts à l'emploi :
   - admin@chatguardian.test / **password** (admin)
   - benevole@chatguardian.test / **password** (bénévole)
   - famille@chatguardian.test / **password** (famille d'accueil)
4. Configurer l'envoi d'emails pour la réinitialisation de mot de passe (Mailhog/SMTP) dans `.env` :
   ```bash
   MAIL_MAILER=smtp
   MAIL_HOST=mailhog # ou votre serveur SMTP
   MAIL_PORT=1025    # ou 587/465 selon le fournisseur
   MAIL_FROM_ADDRESS=no-reply@chatguardian.test
   MAIL_FROM_NAME="ChatGuardian"
   ```
5. Créer le lien de stockage public pour les photos uploadées :
   ```bash
   php artisan storage:link
   ```
6. Se connecter via `/login` puis accéder au tableau de bord `/` (toutes les routes sont protégées par auth).
7. Tester la génération de contrat :
   - Créer au moins une famille d'accueil via `/foster-families`
   - Télécharger le PDF : `/foster-families/{id}/contract`
8. Générer un reçu fiscal PDF :
   - Enregistrer un don via `/donations`
   - Télécharger le reçu : `/donations/{id}/receipt`
9. Exporter un CSV des dons : `/donations/export`
10. Visualiser les points de nourrissage avec Leaflet : `/feeding-points`
11. Charger des photos pour un chat (admin ou bénévole) :
    - Ouvrir la fiche : `/cats/{id}`
    - Téléverser jusqu'à 3 images, supprimer au besoin
12. Enregistrer un séjour et le clôturer :
   - Dans la fiche chat, choisir une famille active, la date d'entrée/sortie, résultat et notes
   - Mettre à jour le statut du chat (libre, en famille, adopté, décédé) en même temps
   - Clore un séjour en cours directement depuis le tableau (date de sortie + résultat + statut final)
13. Modifier une fiche chat :
   - Dans la section « Identité & santé », bouton « Modifier » pour mettre à jour le profil
   - Champs couverts : nom, sexe, date de naissance, statut, stérilisation, vaccination, FIV/FELV, notes
14. Réinitialiser un mot de passe :
   - Demander un lien : `/forgot-password`
   - Ouvrir le lien reçu, saisir le nouveau mot de passe : `/reset-password/{token}`
15. Suivre les stocks (admin/bénévole en lecture, admin en édition) :
   - Ouvrir l'inventaire : `/stocks`
   - Ajouter ou éditer un article avec quantité/unité, seuil d'alerte, localisation et notes
16. Gérer les utilisateurs (admin) :
   - Liste et métriques : `/users`
   - Créer un compte avec rôle, modifier email/nom/role, réinitialiser le mot de passe, supprimer un autre compte
17. Envoyer un reçu fiscal par email :
   - Vérifier que le donateur possède un email
   - Dans `/donations`, cliquer sur **Email** pour envoyer le reçu PDF en pièce jointe (le statut « Email envoyé » sera mis à jour)
18. Enregistrer des visites vétérinaires :
   - Ouvrir une fiche chat : `/cats/{id}`
   - Renseigner la date, le motif, le montant et (optionnel) un document PDF ou une photo (admin/bénévole)
   - Modifier ou supprimer une visite via le tableau dédié
   - Exporter l'historique des visites en CSV : bouton **Export CSV** dans la section santé
19. Finaliser une adoption :
   - Ouvrir la fiche chat : `/cats/{id}`
   - Dans la section Adoption (admin), saisir les coordonnées de l'adoptant et la participation
   - Télécharger le contrat PDF via le bouton **Télécharger le contrat**
20. Lancer une recherche transversale :
   - Utiliser la barre de recherche dans le header (connecté)
   - Saisir un nom (chat, famille, bénévole, donateur) puis valider pour voir les résultats agrégés
21. Exporter la base chats :
   - Bouton **Exporter CSV** depuis `/cats` (admin/bénévole)
22. Télécharger la fiche PDF d'un chat :
   - Bouton **Fiche PDF** sur la fiche `/cats/{id}` (admin/bénévole/famille) ou accès direct `/cats/{id}/profile.pdf`
23. Consulter le journal d'activités :
   - Accès global depuis le tableau de bord (section « Journal des activités »)
   - Historique ciblé sur chaque fiche chat (section « Journal du chat »)
24. Planifier des rappels :
   - Ouvrir une fiche chat puis utiliser le formulaire « Programmer un rappel » (admin/bénévole)
   - Suivre les rappels en attente via la section dédiée sur la fiche ou la carte « Rappels à venir » du dashboard
25. Envoyer un email récapitulatif des rappels (admin/bénévole) :
   - Aller sur `/reminders`, choisir la plage (7 prochains jours, aujourd'hui ou en retard)
   - Cliquer sur **Envoyer par email** pour diffuser le récap aux admins/bénévoles disposant d'un email
26. Automatiser l'envoi du récapitulatif des rappels :
   - Commande manuelle : `php artisan reminders:digest today|week|overdue`
   - Planification incluse : la commande `reminders:digest today` est programmée chaque jour à 07h via le scheduler Laravel (voir `app/Console/Kernel.php`).
   - Alerte stocks faibles : la commande `stocks:alert` est planifiée à 06h50 pour prévenir les admins/bénévoles si des articles passent sous seuil.
   - Activer le scheduler côté serveur : tâche cron toutes les minutes `* * * * * php /chemin/vers/artisan schedule:run >> /dev/null 2>&1`.
27. Consulter et exporter le journal d'activités :
   - Accès : `/activities` (admin/bénévole) avec filtres (action, sujet, utilisateur, période)
   - Export CSV filtré : `/activities/export`
   - Maquette statique : `public/activities.html`
28. Exporter les listes bénévoles et familles d'accueil (admin) :
   - Bénévoles : bouton **Exporter CSV** depuis `/volunteers` (fichier `benevoles_YYYYMMDD_HHMMSS.csv`)
   - Familles d'accueil : bouton **Exporter CSV** depuis `/foster-families` (fichier `familles_accueil_YYYYMMDD_HHMMSS.csv`)

Le dashboard affiche également les dépenses vétérinaires et le nombre de visites du mois, ainsi qu'un aperçu des dernières visites enregistrées.

Le dashboard (`/`) s'appuie désormais sur les statistiques réelles (chats par statut, dons du mois, familles, bénévoles, points de nourrissage) et affiche les derniers chats/dons ajoutés.

### Rôles et accès (prototype)

Les utilisateurs de démonstration disposent d'un champ `role` simple. Les pages et actions clés sont filtrées côté contrôleur et dans la navigation :

| Rôle       | Accès principaux                                    |
|------------|-----------------------------------------------------|
| admin      | Dashboard, chats (création), bénévoles, familles, dons, reçus PDF, export CSV, points de nourrissage (création) |
| benevole   | Dashboard, chats (création), familles (lecture), points de nourrissage (lecture), carte Leaflet                |
| famille    | Dashboard, liste des chats (lecture)               |

Les contrôleurs appellent `authorizeRoles()` pour renvoyer un 403 si le rôle ne correspond pas. Les liens de navigation et les actions rapides s'ajustent en conséquence.

## Prochaines étapes suggérées
1. Installer un vrai projet Laravel (Composer) et brancher ces vues sur l'auth Laravel.
2. Ajouter les migrations + modèles pour les bénévoles, chats, familles, dons.
3. Remplacer les données statiques par des données réelles + formulaires validés.
