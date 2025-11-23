#!/bin/bash

echo "🚀 Installation de ChatGuardian"
echo "================================"
echo ""

# Vérifier PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP n'est pas installé. Veuillez installer PHP 8.1+ d'abord."
    echo "   Sur macOS: brew install php"
    exit 1
fi

PHP_VERSION=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
echo "✅ PHP $PHP_VERSION détecté"

# Vérifier Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer n'est pas installé."
    echo "   Installez-le depuis: https://getcomposer.org/download/"
    exit 1
fi

echo "✅ Composer détecté"
echo ""

# Installer les dépendances
echo "📦 Installation des dépendances Composer..."
composer install

if [ $? -ne 0 ]; then
    echo "❌ Erreur lors de l'installation des dépendances"
    exit 1
fi

echo "✅ Dépendances installées"
echo ""

# Créer .env si nécessaire
if [ ! -f .env ]; then
    echo "📝 Création du fichier .env..."
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        echo "⚠️  .env.example non trouvé, création d'un .env basique..."
        cat > .env << EOF
APP_NAME=ChatGuardian
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chatguardian
DB_USERNAME=root
DB_PASSWORD=
EOF
    fi
    echo "✅ Fichier .env créé"
    echo ""
fi

# Générer la clé d'application
echo "🔑 Génération de la clé d'application..."
php artisan key:generate

echo ""
echo "✅ Installation terminée!"
echo ""
echo "📋 Prochaines étapes:"
echo "   1. Configurez votre base de données dans le fichier .env"
echo "   2. Créez la base de données: mysql -u root -p -e 'CREATE DATABASE chatguardian;'"
echo "   3. Exécutez les migrations: php artisan migrate"
echo "   4. Démarrez le serveur: php artisan serve"
echo ""

