# 🚀 Guide d'installation avec MAMP - ChatGuardian

MAMP est parfait pour ce projet ! Il fournit PHP 8.3.14 et MySQL, exactement ce dont nous avons besoin.

## ✅ Vérifications préalables

MAMP est déjà démarré et PHP 8.3.14 est disponible. Parfait !

## 📋 Configuration avec MAMP

### 1. Installer Composer

Composer n'est pas inclus dans MAMP, mais nous pouvons l'installer globalement :

```bash
# Télécharger et installer Composer
/Applications/MAMP/bin/php/php8.3.14/bin/php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
/Applications/MAMP/bin/php/php8.3.14/bin/php composer-setup.php
/Applications/MAMP/bin/php/php8.3.14/bin/php -r "unlink('composer-setup.php');"

# Déplacer Composer dans un endroit accessible
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Créer un alias pour utiliser PHP de MAMP avec Composer
echo 'alias composer="/Applications/MAMP/bin/php/php8.3.14/bin/php /usr/local/bin/composer"' >> ~/.zshrc
source ~/.zshrc
```

**OU** utiliser directement PHP de MAMP :

```bash
# Utiliser Composer directement avec PHP de MAMP
/Applications/MAMP/bin/php/php8.3.14/bin/php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
/Applications/MAMP/bin/php/php8.3.14/bin/php composer-setup.php
mv composer.phar composer
chmod +x composer
```

### 2. Configurer la base de données MySQL de MAMP

MAMP utilise MySQL sur le **port 8889** (par défaut).

1. Ouvrez phpMyAdmin via MAMP : http://localhost:8888/phpMyAdmin/
2. Créez la base de données `chatguardian`
3. OU utilisez la ligne de commande :

```bash
# Se connecter à MySQL de MAMP
/Applications/MAMP/Library/bin/mysql80/bin/mysql -u root -p -P 8889 -h 127.0.0.1

# Dans MySQL, créer la base de données
CREATE DATABASE chatguardian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 3. Configurer le fichier .env

Créez le fichier `.env` avec la configuration MAMP :

```env
APP_NAME=ChatGuardian
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=chatguardian
DB_USERNAME=root
DB_PASSWORD=root
```

**Note** : Le mot de passe MySQL par défaut de MAMP est généralement `root`.

### 4. Installer les dépendances

```bash
cd /Users/imac27/Desktop/PROJETS/ChatGuardian/ChatGuardian

# Utiliser PHP de MAMP avec Composer
/Applications/MAMP/bin/php/php8.3.14/bin/php /usr/local/bin/composer install

# OU si vous avez créé le fichier composer local :
./composer install
```

### 5. Générer la clé d'application

```bash
/Applications/MAMP/bin/php/php8.3.14/bin/php artisan key:generate
```

### 6. Exécuter les migrations

```bash
/Applications/MAMP/bin/php/php8.3.14/bin/php artisan migrate
```

### 7. Démarrer le serveur Laravel

```bash
/Applications/MAMP/bin/php/php8.3.14/bin/php artisan serve
```

L'application sera accessible sur : **http://localhost:8000**

## 🔧 Créer des alias pratiques

Ajoutez ces lignes à votre `~/.zshrc` pour faciliter l'utilisation :

```bash
# PHP MAMP
alias php-mamp="/Applications/MAMP/bin/php/php8.3.14/bin/php"

# Artisan avec PHP MAMP
alias artisan="php-mamp artisan"

# Composer avec PHP MAMP
alias composer-mamp="php-mamp /usr/local/bin/composer"
```

Puis rechargez : `source ~/.zshrc`

Avec ces alias, vous pourrez utiliser :
- `php-mamp artisan migrate`
- `composer-mamp install`

## 📝 Ports MAMP par défaut

- **Apache** : 8888
- **MySQL** : 8889
- **Laravel** : 8000 (via `php artisan serve`)

## ⚠️ Notes importantes

1. **Laravel utilise son propre serveur** : Vous n'avez pas besoin d'Apache de MAMP pour Laravel. Utilisez `php artisan serve`.

2. **MySQL doit être démarré** : Assurez-vous que MySQL est démarré dans MAMP.

3. **Permissions** : Si vous avez des erreurs de permissions sur `storage/` :
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

## 🆘 Dépannage

### Erreur "Class not found"
```bash
php-mamp artisan clear-compiled
php-mamp composer dump-autoload
```

### Erreur de connexion MySQL
- Vérifiez que MySQL est démarré dans MAMP
- Vérifiez le port (8889) dans `.env`
- Vérifiez le mot de passe (généralement `root`)

### Erreur "Command not found: composer"
Installez Composer comme indiqué à l'étape 1, ou utilisez directement :
```bash
/Applications/MAMP/bin/php/php8.3.14/bin/php composer.phar install
```

