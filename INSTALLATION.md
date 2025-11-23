# Guide d'installation - ChatGuardian

Ce guide vous aidera à installer et tester l'application ChatGuardian en local.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé :

1. **PHP 8.1 ou supérieur**
   ```bash
   php --version
   ```

2. **Composer** (gestionnaire de dépendances PHP)
   ```bash
   composer --version
   ```

3. **MySQL/MariaDB** ou **PostgreSQL** (base de données)
   ```bash
   mysql --version
   # ou
   psql --version
   ```

4. **Node.js et npm** (pour les assets frontend)
   ```bash
   node --version
   npm --version
   ```

## Installation sur macOS

### 1. Installer PHP

Si PHP n'est pas installé, utilisez Homebrew :

```bash
# Installer Homebrew si nécessaire
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Installer PHP
brew install php

# Ajouter PHP au PATH (ajoutez cette ligne à ~/.zshrc)
echo 'export PATH="/opt/homebrew/opt/php/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
```

### 2. Installer Composer

```bash
# Télécharger et installer Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"

# Vérifier l'installation
composer --version
```

### 3. Installer MySQL

```bash
brew install mysql
brew services start mysql
```

## Étapes d'installation

### 1. Installer les dépendances PHP

```bash
cd /Users/imac27/Desktop/PROJETS/ChatGuardian/ChatGuardian
composer install
```

### 2. Configurer l'environnement

```bash
# Copier le fichier .env.example vers .env
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 3. Configurer la base de données

Éditez le fichier `.env` et modifiez les paramètres de base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chatguardian
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

Créez la base de données :

```bash
# Se connecter à MySQL
mysql -u root -p

# Dans MySQL, créer la base de données
CREATE DATABASE chatguardian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 4. Exécuter les migrations

```bash
php artisan migrate
```

### 5. Installer les dépendances frontend (optionnel)

```bash
npm install
npm run build
```

### 6. Démarrer le serveur de développement

```bash
php artisan serve
```

L'application sera accessible à l'adresse : **http://localhost:8000**

## Vérification de l'installation

1. Ouvrez votre navigateur et allez sur `http://localhost:8000`
2. Vous devriez voir le dashboard de ChatGuardian
3. Testez les différentes pages :
   - `/volunteers` - Gestion des bénévoles
   - `/cats` - Gestion des chats
   - `/foster-families` - Familles d'accueil
   - `/donations` - Dons
   - `/feeding-points` - Points de nourrissage

## Commandes utiles

```bash
# Voir toutes les routes
php artisan route:list

# Réinitialiser la base de données
php artisan migrate:fresh

# Créer un utilisateur (si l'authentification est configurée)
php artisan tinker
```

## Dépannage

### Erreur "Class not found"
```bash
composer dump-autoload
```

### Erreur de permissions sur storage
```bash
chmod -R 775 storage bootstrap/cache
```

### Erreur de connexion à la base de données
- Vérifiez que MySQL est démarré : `brew services list`
- Vérifiez les identifiants dans `.env`
- Testez la connexion : `mysql -u root -p`

## Structure de l'application

- `app/Models/` - Modèles Eloquent
- `app/Http/Controllers/` - Contrôleurs
- `database/migrations/` - Migrations de base de données
- `resources/views/` - Vues Blade
- `routes/web.php` - Routes web
- `public/` - Fichiers publics (CSS, JS)

## Prochaines étapes

1. Configurer l'authentification Laravel
2. Ajouter des seeders pour les données de test
3. Configurer les permissions de fichiers
4. Ajouter des tests unitaires

