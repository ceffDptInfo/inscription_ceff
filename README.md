# Guide d'installation

> **Important :** Avant de commencer, ouvrez votre terminal et placez-vous impérativement à la racine du projet pour exécuter toutes les commandes ci-dessous.

## Prérequis
Assurez-vous de disposer des éléments suivants sur votre environnement local :
* PHP et Composer
* Node.js et NPM
* Un serveur de base de données (MySQL/MariaDB)

## Étapes d'installation

**1. Récupération du projet**
Effectuez votre `git pull` ou `git clone` pour récupérer les derniers fichiers du dépôt.

**2. Installation des dépendances PHP**
Cette commande télécharge les paquets requis pour le fonctionnement de Laravel.
```bash
composer install
```

**3. Installation des dépendances front-end**
Cette commande installe les modules JavaScript nécessaires pour Vue.js et Inertia.
```bash
npm install
```

**4. Configuration de l'environnement**
Dupliquez le fichier d'exemple pour créer votre configuration locale. Ouvrez ensuite le fichier `.env` fraîchement créé pour y configurer l'accès à votre base de données locale.
```bash
cp .env.example .env
```

**5. Génération de la clé de sécurité**
Génère une clé unique essentielle au cryptage des sessions et des données de l'application.
```bash
php artisan key:generate
```

**6. Migration de la base de données**
Exécute les fichiers de migration pour structurer et créer l'ensemble des tables SQL dans votre base de données.
```bash
php artisan migrate
```

**7. Création du compte secrétariat**
Insère le compte utilisateur initial indispensable pour l'accès du secrétariat au panneau d'administration.
```bash
php artisan db:seed --class=SecretaireSeeder
```

**8. Création du lien symbolique de stockage**
Crée un lien symbolique reliant le dossier sécurisé de stockage au dossier public. Cette étape est obligatoire pour permettre à l'application d'afficher et de rendre téléchargeables les pièces jointes (photos, permis) téléversées par les candidats.
```bash
php artisan storage:link
```

**9. Compilation et lancement**
Compile les assets front-end puis démarre le serveur de développement local de Laravel.
```bash
npm run build
php artisan serve
```