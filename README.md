# Projet Juice Shop Sécurisé

Ce projet est une reproduction sécurisée d'un site , divisée en deux parties distinctes : un back-end Symfony et un front-end Next.js.

L'objectif principal était de mettre en pratique les principes de sécurité web (authentification, contrôle d'accès, sécurisation des données) tout en construisant une application fonctionnelle.

## 🔧 Partie 1 : Back-End (Symfony)

### 📁 Dossier : juice-shop-backend/

### ✅ Technologies utilisées

Symfony 6

MySQL

Doctrine ORM

LexikJWTAuthenticationBundle (JWT)

Bcrypt pour le hashage des mots de passe

Annotations & Groups pour la sérialisation JSON

### 🔐 Fonctionnalités sécurisées

Authentification via JWT (connexion utilisateur)

Mots de passe hashés

Gestion des rôles : ROLE_USER / ROLE_ADMIN

Accès protégé par security.yaml

Filtres des données exposées via #[Groups()]

Accès restreint au panier / commandes par utilisateur

### 🧱 Entités créées

User : email, password, roles

Product : name, description, price

Cart : user, product, quantity

Order : user, products, totalPrice, status

### 🧭 Contrôleurs créés

ProductController : GET /products, DELETE (admin)

CartController : POST /cart/add, GET /cart

OrderController : POST /orders/create, GET /orders

AdminController : GET /admin/users (admin)

LexikJWT : POST /login (automatique)


## 🚀 Installation
#### Cloner le projet
git clone <repo-backend>
cd juice-shop-backend

#### Installer les dépendances
composer install

#### Configurer la base de données
cp .env .env.local
#### Modifier DATABASE_URL dans .env.local

#### Créer la base et les tables
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

#### Générer les clés JWT
php bin/console lexik:jwt:generate-keypair

#### Lancer le serveur Symfony
symfony server:start

## API disponible sur :
http://localhost:8000/api
