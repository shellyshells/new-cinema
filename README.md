# CinemaPHP – Application de réservation de places

Application web de réservation de places de cinéma en PHP pur, architecture MVC, sans framework

## Prérequis

- PHP 8.0+
- MySQL 5.7+ / MariaDB
- Serveur web (Apache, Nginx) ou `php -S` pour le développement

## Installation

### 1. Base de données

#### Windows (XAMPP, WAMP, ou MySQL Installer)

**Avec XAMPP/WAMP :**
1. Démarrer MySQL depuis le panneau de contrôle
2. Ouvrir un terminal (cmd ou PowerShell) et naviguer vers le dossier MySQL :
```cmd
cd C:\xampp\mysql\bin
mysql -u root < chemin\vers\sql\schema.sql
```
Ou utiliser phpMyAdmin : `http://localhost/phpmyadmin` → Importer → `sql/schema.sql`

**Avec MySQL Installer :**
```cmd
mysql -u root -p < sql/schema.sql
```
Entrer le mot de passe défini lors de l'installation.

**Dépannage Windows :** Si `mysql` n'est pas reconnu, ajouter le dossier `bin` de MySQL au PATH système, ou utiliser le chemin complet :
```cmd
"C:\Program Files\MySQL\MySQL Server 8.0\\bin\mysql" -u root -p < sql/schema.sql
```

---

#### Linux - Option A : Si vous connaissez votre mot de passe MySQL
```bash
mysql -u root -p < sql/schema.sql
```

#### Linux - Option B : Sur Ubuntu/Debian (auth_socket)
Si `mysql -u root -p` échoue avec `ERROR 1698 (28000): Access denied`, MySQL utilise l'authentification système :
```bash
sudo mysql < sql/schema.sql
```

#### Linux - Option C : Créer un utilisateur dédié (recommandé)
```bash
sudo mysql
```
Puis dans MySQL :
```sql
CREATE USER 'cinema'@'localhost' IDENTIFIED BY 'cinema123';
CREATE DATABASE IF NOT EXISTS cinema;
GRANT ALL PRIVILEGES ON cinema.* TO 'cinema'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```
Ensuite importer le schéma :
```bash
mysql -u cinema -p < sql/schema.sql
# Mot de passe : cinema123
```

#### Dépannage Linux : Réinitialiser le mot de passe root MySQL
Si vous avez oublié le mot de passe root :
```bash
# 1. Arrêter MySQL
sudo systemctl stop mysql

# 2. Créer le dossier socket s'il n'existe pas
sudo mkdir -p /var/run/mysqld
sudo chown mysql:mysql /var/run/mysqld

# 3. Démarrer en mode sans authentification
sudo mysqld_safe --skip-grant-tables &

# 4. Attendre 3-4 secondes, puis se connecter
mysql -u root
```
Dans MySQL, exécuter une commande à la fois :
```sql
FLUSH PRIVILEGES;
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'nouveaumotdepasse';
EXIT;
```
Ensuite redémarrer MySQL :
```bash
# 5. Redémarrer MySQL normalement
sudo pkill mysqld
sudo systemctl start mysql

# 6. Tester la connexion
mysql -u root -p
# Entrer : nouveaumotdepasse
```

Ou via **phpMyAdmin** (tous OS) : importer le fichier `sql/schema.sql`.

### 2. Configuration

Créer un fichier `.env` à la racine du projet :

```
DB_HOST=localhost
DB_NAME=cinema
DB_USER=cinema
DB_PASS=cinema123
```

Ou modifier directement `config/database.php` :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'cinema');
define('DB_USER', 'root');
define('DB_PASS', 'votre_mot_de_passe');
```

### 3. Lancer le serveur

```bash
# Développement avec le serveur intégré PHP
php -S localhost:8000

# Puis ouvrir http://localhost:8000
```

## Comptes par défaut

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| Admin | admin@cinema.com | password |

> **Note :** Le mot de passe `password` est le hash bcrypt par défaut de Laravel. Changez-le via l'interface.
> Vous pouvez aussi créer un compte admin manuellement avec `password_hash('votremotdepasse', PASSWORD_BCRYPT)`.

## Structure du projet

```
cinema/
├── index.php              # Front controller (routeur)
├── config/
│   └── database.php       # Configuration BDD
├── sql/
│   └── schema.sql         # Script de création de la BDD
└── app/
    ├── models/
    │   ├── Database.php
    │   ├── UserModel.php
    │   ├── MovieModel.php
    │   ├── ScreeningModel.php
    │   ├── ReservationModel.php
    │   └── RoomModel.php
    ├── controllers/
    │   ├── AuthController.php
    │   ├── MovieController.php
    │   ├── ReservationController.php
    │   └── AdminController.php
    └── views/
        ├── layouts/
        │   ├── header.php
        │   └── footer.php
        ├── auth/
        │   ├── login.php
        │   ├── register.php
        │   └── profile.php
        ├── movies/
        │   ├── index.php
        │   └── show.php
        ├── reservations/
        │   ├── index.php
        │   └── create.php
        └── admin/
            ├── movies.php
            ├── rooms.php
            ├── screenings.php
            ├── reservations.php
            └── users.php
```

## Fonctionnalités

### Utilisateur
- Inscription / Connexion / Déconnexion
- Option "Se souvenir de moi" (cookie 30 jours)
- Expiration de session après 30 min d'inactivité
- Modification et suppression de compte
- Consultation des films à l'affiche
- Voir les séances d'un film
- Réserver 1 à 10 places par séance
- Consulter et annuler ses réservations

### Administrateur
- Ajouter / modifier / supprimer des films
- Gérer les salles (nom et capacité différente par salle)
- Gérer les séances (assignation d'une salle par séance)
- Voir et supprimer toutes les réservations
- Gérer les utilisateurs

## Sécurité

- Mots de passe hashés avec `password_hash` (bcrypt)
- Requêtes préparées PDO (protection injection SQL)
- Échappement des sorties avec `htmlspecialchars` (protection XSS)
- Gestion des rôles (admin / utilisateur)
- Expiration de session par inactivité
