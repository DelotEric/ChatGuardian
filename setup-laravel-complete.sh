#!/bin/bash

echo "🚀 Configuration complète de Laravel pour ChatGuardian"
echo "======================================================"
echo ""

PHP_MAMP="/Applications/MAMP/bin/php/php8.3.14/bin/php"
PROJECT_DIR="/Users/imac27/Desktop/PROJETS/ChatGuardian/ChatGuardian"
COMPOSER_CMD="$PHP_MAMP $PROJECT_DIR/composer.phar"

# Vérifier que nous sommes dans le bon dossier
if [ ! -f "composer.json" ]; then
    echo "❌ Erreur: composer.json non trouvé. Exécutez ce script depuis le dossier du projet."
    exit 1
fi

echo "📦 Étape 1: Création d'un projet Laravel temporaire..."
cd /tmp
rm -rf laravel-temp 2>/dev/null
$COMPOSER_CMD create-project laravel/laravel laravel-temp --prefer-dist --no-interaction

if [ $? -ne 0 ]; then
    echo "❌ Erreur lors de la création du projet Laravel"
    exit 1
fi

echo "✅ Projet Laravel créé"
echo ""

echo "📋 Étape 2: Copie de votre code existant..."
PROJECT_DIR="/Users/imac27/Desktop/PROJETS/ChatGuardian/ChatGuardian"

# Copier les dossiers personnalisés
echo "  - Copie des modèles..."
cp -r $PROJECT_DIR/app/Models/* laravel-temp/app/Models/ 2>/dev/null

echo "  - Copie des contrôleurs..."
cp -r $PROJECT_DIR/app/Http/Controllers/* laravel-temp/app/Http/Controllers/ 2>/dev/null

echo "  - Copie des migrations..."
cp -r $PROJECT_DIR/database/migrations/* laravel-temp/database/migrations/ 2>/dev/null

echo "  - Copie des vues..."
cp -r $PROJECT_DIR/resources/views/* laravel-temp/resources/views/ 2>/dev/null

echo "  - Copie des routes..."
cp $PROJECT_DIR/routes/web.php laravel-temp/routes/web.php

echo "  - Copie des assets..."
cp -r $PROJECT_DIR/public/css laravel-temp/public/ 2>/dev/null
cp -r $PROJECT_DIR/public/js laravel-temp/public/ 2>/dev/null

echo "  - Copie de composer.json..."
# Ne pas écraser le composer.json du projet Laravel, mais mettre à jour les dépendances si nécessaire
# Le projet Laravel 12 est déjà installé avec les bonnes dépendances

echo "✅ Code copié"
echo ""

echo "📦 Étape 3: Mise à jour des dépendances..."
cd laravel-temp
# Mettre à jour composer.json pour ajouter les dépendances manquantes si nécessaire
$COMPOSER_CMD require laravel/sanctum --no-interaction 2>/dev/null || echo "Sanctum déjà présent ou optionnel"
$COMPOSER_CMD dump-autoload

if [ $? -ne 0 ]; then
    echo "❌ Erreur lors de l'installation des dépendances"
    exit 1
fi

echo "✅ Dépendances installées"
echo ""

echo "📋 Étape 4: Copie vers le projet final..."
cd $PROJECT_DIR

# Sauvegarder les fichiers existants
mkdir -p backup 2>/dev/null
cp -r app backup/ 2>/dev/null
cp -r database backup/ 2>/dev/null
cp -r resources backup/ 2>/dev/null
cp -r routes backup/ 2>/dev/null

# Copier la structure Laravel complète
echo "  - Copie de la structure Laravel..."
cp -r /tmp/laravel-temp/bootstrap .
cp -r /tmp/laravel-temp/config .
cp -r /tmp/laravel-temp/storage .
cp -r /tmp/laravel-temp/tests .
cp -r /tmp/laravel-temp/vendor .
cp /tmp/laravel-temp/.gitignore . 2>/dev/null
cp /tmp/laravel-temp/phpunit.xml . 2>/dev/null

# Restaurer votre code
echo "  - Restauration de votre code..."
cp -r backup/app/* app/
cp -r backup/database/migrations/* database/migrations/
cp -r backup/resources/* resources/
cp backup/routes/web.php routes/web.php

echo "✅ Structure Laravel complète installée"
echo ""

echo "🔑 Étape 5: Configuration..."
if [ -f .env ]; then
    echo "  - Fichier .env existe déjà"
else
    cp /tmp/laravel-temp/.env.example .env
    # Mettre à jour avec la config MAMP
    sed -i '' 's/DB_PORT=3306/DB_PORT=8889/' .env
    sed -i '' 's/DB_DATABASE=laravel/DB_DATABASE=chatguardian/' .env
    sed -i '' 's/DB_PASSWORD=/DB_PASSWORD=root/' .env
    echo "  - Fichier .env créé et configuré pour MAMP"
fi

$PHP_MAMP artisan key:generate --force

echo ""
echo "✅ Configuration terminée!"
echo ""
echo "📋 Prochaines étapes:"
echo "   1. Exécutez les migrations:"
echo "      $PHP_MAMP artisan migrate"
echo ""
echo "   2. Démarrez le serveur:"
echo "      $PHP_MAMP artisan serve"
echo ""
echo "   3. Accédez à l'application: http://localhost:8000"
echo ""
echo "💡 Note: Vos fichiers originaux sont sauvegardés dans le dossier 'backup/'"
echo ""

