#!/bin/bash

echo "🚀 Installation de ChatGuardian avec MAMP"
echo "=========================================="
echo ""

# Vérifier que MAMP est installé
if [ ! -d "/Applications/MAMP" ]; then
    echo "❌ MAMP n'est pas installé dans /Applications/MAMP"
    exit 1
fi

# Trouver la version PHP de MAMP
PHP_MAMP="/Applications/MAMP/bin/php/php8.3.14/bin/php"

if [ ! -f "$PHP_MAMP" ]; then
    echo "❌ PHP MAMP non trouvé dans $PHP_MAMP"
    echo "   Vérifiez que MAMP est bien installé et que PHP 8.3.14 est disponible"
    exit 1
fi

echo "✅ PHP MAMP trouvé : $PHP_MAMP"
$PHP_MAMP --version | head -1
echo ""

# Vérifier Composer
if command -v composer &> /dev/null; then
    COMPOSER_CMD="composer"
    echo "✅ Composer global trouvé"
elif [ -f "/usr/local/bin/composer" ]; then
    COMPOSER_CMD="/usr/local/bin/composer"
    echo "✅ Composer trouvé dans /usr/local/bin/composer"
else
    echo "⚠️  Composer non trouvé. Installation..."
    $PHP_MAMP -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    $PHP_MAMP composer-setup.php
    $PHP_MAMP -r "unlink('composer-setup.php');"
    if [ -f "composer.phar" ]; then
        COMPOSER_CMD="$PHP_MAMP composer.phar"
        echo "✅ Composer installé localement"
    else
        echo "❌ Erreur lors de l'installation de Composer"
        exit 1
    fi
fi

echo ""

# Installer les dépendances
echo "📦 Installation des dépendances Composer..."
if [[ "$COMPOSER_CMD" == *"composer.phar"* ]]; then
    $COMPOSER_CMD install
else
    $PHP_MAMP $COMPOSER_CMD install
fi

if [ $? -ne 0 ]; then
    echo "❌ Erreur lors de l'installation des dépendances"
    exit 1
fi

echo "✅ Dépendances installées"
echo ""

# Créer .env si nécessaire
if [ ! -f .env ]; then
    echo "📝 Création du fichier .env pour MAMP..."
    if [ -f .env.mamp ]; then
        cp .env.mamp .env
        echo "✅ Fichier .env créé depuis .env.mamp"
    else
        echo "⚠️  .env.mamp non trouvé, création d'un .env basique..."
        cat > .env << EOF
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
EOF
        echo "✅ Fichier .env créé"
    fi
    echo ""
fi

# Générer la clé d'application
echo "🔑 Génération de la clé d'application..."
$PHP_MAMP artisan key:generate

if [ $? -ne 0 ]; then
    echo "⚠️  Erreur lors de la génération de la clé (normal si bootstrap/ n'existe pas encore)"
fi

echo ""
echo "✅ Installation terminée!"
echo ""
echo "📋 Prochaines étapes:"
echo "   1. Créez la base de données dans MAMP:"
echo "      - Ouvrez phpMyAdmin: http://localhost:8888/phpMyAdmin/"
echo "      - Créez la base 'chatguardian'"
echo "      - OU via ligne de commande:"
echo "        /Applications/MAMP/Library/bin/mysql80/bin/mysql -u root -proot -P 8889 -e 'CREATE DATABASE chatguardian;'"
echo ""
echo "   2. Vérifiez le mot de passe MySQL dans .env (par défaut: root)"
echo ""
echo "   3. Exécutez les migrations:"
echo "      $PHP_MAMP artisan migrate"
echo ""
echo "   4. Démarrez le serveur:"
echo "      $PHP_MAMP artisan serve"
echo ""
echo "   L'application sera accessible sur: http://localhost:8000"
echo ""

