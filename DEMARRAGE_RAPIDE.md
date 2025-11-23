# 🚀 Démarrage Rapide - ChatGuardian

## ⚠️ Important

Cette application est un **prototype Laravel partiel**. Il manque certains fichiers de configuration Laravel essentiels (bootstrap/, config/, storage/, etc.).

## 📋 Deux options d'installation

### Option 1 : Installation complète avec Composer (Recommandée)

Cette méthode crée un projet Laravel complet et y intègre votre code existant.

```bash
# 1. Créer un nouveau projet Laravel dans un dossier temporaire
cd /tmp
composer create-project laravel/laravel chatguardian-temp

# 2. Copier votre code existant dans le nouveau projet
cd chatguardian-temp

# Copier les dossiers personnalisés
cp -r /Users/imac27/Desktop/PROJETS/ChatGuardian/ChatGuardian/app/* app/
cp -r /Users/imac27/Desktop/PROJETS/ChatGuardian/ChatGuardian/database/migrations/* database/migrations/
cp -r /Users/imac27/Desktop/PROJETS/ChatGuardian/ChatGuardian/resources/views/* resources/views/
cp -r /Users/imac27/Desktop/PROJETS/ChatGuardian/ChatGuardian/routes/web.php routes/
cp -r /Users/imac27/Desktop/PROJETS/ChatGuardian/ChatGuardian/public/css public/
cp -r /Users/imac27/Desktop/PROJETS/ChatGuardian/ChatGuardian/public/js public/

# 3. Remplacer le composer.json
cp /Users/imac27/Desktop/PROJETS/ChatGuardian/ChatGuardian/composer.json composer.json

# 4. Réinstaller les dépendances
composer install

# 5. Configurer .env
cp .env.example .env
php artisan key:generate

# 6. Configurer la base de données dans .env puis:
php artisan migrate

# 7. Démarrer le serveur
php artisan serve
```

### Option 2 : Installation dans le dossier actuel

Si vous préférez travailler dans le dossier actuel, vous devez d'abord installer PHP et Composer, puis exécuter :

```bash
# Installer les dépendances (cela créera vendor/ et certains fichiers manquants)
composer install

# Si des erreurs apparaissent, vous devrez peut-être créer manuellement:
# - bootstrap/app.php
# - config/ (tous les fichiers de config)
# - storage/ et ses sous-dossiers
# - etc.
```

## 🔧 Prérequis à installer

### Sur macOS avec Homebrew :

```bash
# Installer Homebrew (si pas déjà installé)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Installer PHP
brew install php

# Installer Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"

# Installer MySQL
brew install mysql
brew services start mysql
```

## 📝 Configuration de la base de données

1. Créer la base de données :
```bash
mysql -u root -p
CREATE DATABASE chatguardian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

2. Configurer `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chatguardian
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

3. Exécuter les migrations :
```bash
php artisan migrate
```

## ✅ Vérification

Une fois installé, accédez à : **http://localhost:8000**

Pages disponibles :
- `/` - Dashboard
- `/volunteers` - Bénévoles
- `/cats` - Chats
- `/foster-families` - Familles d'accueil
- `/donations` - Dons
- `/feeding-points` - Points de nourrissage

## 🆘 Problèmes courants

### "Class not found"
```bash
composer dump-autoload
```

### Erreurs de permissions
```bash
chmod -R 775 storage bootstrap/cache
```

### Base de données non trouvée
Vérifiez que MySQL est démarré :
```bash
brew services list
brew services start mysql
```

