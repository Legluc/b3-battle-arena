# Shifumi Tournament

Une application web complète de gestion de tournoi de **Pierre-Papier-Ciseaux (Shifumi)** construite avec **Symfony 7.4** et **API Platform**.

## Table des matières

- [Vue d'ensemble](#vue-densemble)
- [Fonctionnalités](#fonctionnalités)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Utilisation](#utilisation)
- [Architecture](#architecture)
- [API Endpoints](#api-endpoints)
- [Technologies](#technologies)

---

## Vue d'ensemble

Shifumi Tournament est une application permettant de :
- Créer et gérer des joueurs
- Organiser des rencontres (matches) entre joueurs
- Enregistrer les résultats et déterminer les gagnants
- Générer des rapports PDF des matches
- Accéder à une API REST complète pour intégrations externes

L'application utilise une architecture moderne avec une API REST via **API Platform** et une interface web classique avec **Twig** et **Stimulus**.

---

## Fonctionnalités

### Gestion des Joueurs
- ✅ Inscription des joueurs (nom, email, date de naissance)
- ✅ Validation de la majorité (18 ans minimum)
- ✅ Authentification JWT sécurisée
- ✅ Gestion des rôles (admin, utilisateur)
- ✅ Edition du profil joueur

### Gestion des Rencontres
- ✅ Création de rencontres entre 2 joueurs distincts
- ✅ Enregistrement des résultats
- ✅ Détermination automatique du gagnant
- ✅ Historique complet des matches

### Rapports
- ✅ Génération de PDF pour chaque rencontre
- ✅ Téléchargement des rapports

### API REST
- ✅ Endpoints complets pour joueurs, rencontres et résultats
- ✅ Sérialisation JSON avec groupes de normalisation
- ✅ Validation des données à l'entrée

---

## Prérequis

- **PHP 8.2+** (avec extensions ctype et iconv)
- **Composer** (gestionnaire de dépendances PHP)
- **MySQL** ou une base de données compatible (Doctrine ORM)
- **Node.js** (optionnel, pour asset mapper)
- **Docker** (optionnel, fourni via compose.yaml)

---

## Installation

### 1. Cloner le projet
```bash
git clone <repository-url>
cd shifumi_tournament
```

### 2. Installer les dépendances
```bash
composer install
```

### 3. Configurer l'environnement
Créer un fichier `.env.local` :
```bash
cp .env .env.local
```

Éditer `.env.local` et configurer :
- `DATABASE_URL` : URL de connexion à votre base de données
- `JWT_SECRET_KEY` : Clé secrète pour les tokens JWT
- `JWT_PUBLIC_KEY` : Clé publique pour les tokens JWT
- `MAILER_DSN` : Configuration du mailer (si nécessaire)

### 4. Créer la base de données
```bash
php bin/console doctrine:database:create
```

### 5. Exécuter les migrations
```bash
php bin/console doctrine:migrations:migrate
```

### 6. Lancer le serveur de développement
```bash
symfony serve
```

L'application sera accessible à `http://localhost:8000`

---

## Configuration

### Variables d'environnement importantes

| Variable | Description |
|----------|-------------|
| `DATABASE_URL` | Connexion à la base de données |
| `JWT_SECRET_KEY` | Clé privée JWT |
| `JWT_PUBLIC_KEY` | Clé publique JWT |
| `APP_ENV` | Environnement (`dev`, `prod`) |
| `APP_DEBUG` | Mode débogage |


---

## Utilisation

### Interface Web

1. **S'inscrire** : Accédez à `/register` pour créer un compte
2. **Se connecter** : Utilisez `/login` avec vos identifiants
3. **Consulter les joueurs** : Dashboard `/joueur`
4. **Créer une rencontre** : `/rencontre/new`
5. **Enregistrer un résultat** : `/resultat/new`

### API REST

#### Authentification JWT
```bash
POST /api/login_check
{
  "email": "joueur@example.com",
  "password": "password"
}
```

#### Récupérer les joueurs
```bash
GET /api/joueurs
Authorization: Bearer {JWT_TOKEN}
```

#### Créer une rencontre
```bash
POST /api/rencontres
Authorization: Bearer {JWT_TOKEN}
{
  "joueur1": "/api/joueurs/1",
  "joueur2": "/api/joueurs/2"
}
```

#### Enregistrer un résultat
```bash
POST /api/resultats
Authorization: Bearer {JWT_TOKEN}
{
  "rencontre": "/api/rencontres/1",
  "gagnant": "/api/joueurs/1"
}
```

---

## Architecture

### Structure du projet

```
shifumi_tournament/
├── src/
│   ├── Controller/          # Contrôleurs web et API
│   ├── Entity/              # Entités Doctrine (Joueur, Rencontre, Resultat)
│   ├── Repository/          # Repositories Doctrine
│   ├── Service/             # Services métier
│   ├── DTO/                 # Data Transfer Objects
│   ├── Form/                # Types de formulaire Symfony
│   └── Security/            # Configuration de sécurité
├── templates/               # Templates Twig
├── config/                  # Configuration Symfony
├── migrations/              # Migrations de base de données
├── assets/                  # Ressources frontend (CSS, JS)
├── tests/                   # Tests PHPUnit
└── public/                  # Dossier public (point d'entrée)
```

### Entités principales

#### Joueur
- `id` : Identifiant unique
- `nom` : Nom du joueur
- `mail` : Email unique
- `ddn` : Date de naissance (majeur obligatoire)
- `roles` : Rôles utilisateur (JSON)
- `password` : Mot de passe hashé

#### Rencontre
- `id` : Identifiant unique
- `joueur1` : Premier joueur
- `joueur2` : Deuxième joueur
- `gagnant` : Joueur gagnant (optionnel)
- `pdfLink` : Lien vers le rapport PDF

#### Resultat
- `id` : Identifiant unique
- `rencontre` : Référence à la rencontre
- `gagnant` : Joueur gagnant
- `dateResultat` : Date du résultat

---

## API Endpoints

### Joueurs
- `GET /api/joueurs` - Lister les joueurs
- `GET /api/joueurs/{id}` - Détails d'un joueur
- `POST /api/joueurs` - Créer un joueur
- `PUT /api/joueurs/{id}` - Modifier un joueur
- `DELETE /api/joueurs/{id}` - Supprimer un joueur

### Rencontres
- `GET /api/rencontres` - Lister les rencontres
- `GET /api/rencontres/{id}` - Détails d'une rencontre
- `POST /api/rencontres` - Créer une rencontre
- `PUT /api/rencontres/{id}` - Modifier une rencontre

### Résultats
- `GET /api/resultats` - Lister les résultats
- `GET /api/resultats/{id}` - Détails d'un résultat
- `POST /api/resultats` - Enregistrer un résultat

---

## Technologies

### Backend
- **Symfony 7.4** - Framework PHP
- **API Platform 4.2** - REST API automatisée
- **Doctrine ORM 3.6** - ORM et migrations
- **JWT Authentication** - Authentification sécurisée
- **Monolog** - Logging

### Frontend
- **Twig** - Moteur de templates
- **Stimulus 2.32** - Framework JavaScript
- **Asset Mapper** - Gestion des assets
- **dompdf 3.1** - Génération de PDF

### Outils
- **PHPUnit** - Tests unitaires
- **PHPStan** - Analyse statique
- **Docker Compose** - Conteneurisation
- **Doctrine Migrations** - Versionning BD

---

## Commandes utiles

```bash
# Console Symfony
php bin/console list

# Migrations
php bin/console doctrine:migrations:create
php bin/console doctrine:migrations:migrate

# Cache
php bin/console cache:clear
php bin/console cache:warmup

# Tests
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:migrations:migrate
php bin/phpunit

# Serveur de développement
symfony serve
```

---

## Licence

Propriétaire - Tous droits réservés

---

## Support

Pour toute question ou problème, consultez la documentation [Symfony](https://symfony.com/doc) ou [API Platform](https://api-platform.com/docs/).

---

**Bon tournoi de Shifumi !**
