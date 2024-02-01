# SnowTricks

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/5c74b7abd7db494cb0b2191f9ec70381)](https://app.codacy.com/gh/AlxFrst/SnowTricks/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## Description
SnowTricks est un site communautaire qui permet aux utilisateurs de partager leurs figures de snowboard. Les utilisateurs peuvent s'inscrire et se connecter au site. Ils peuvent

## Prérequis
-  PHP 8.1
-  Composer
-  Symfony CLI
-  Node.js
-  Npm
-  MySQL

## Installation
1. Cloner le répertoir Github
```git clone https://github.com/AlxFrst/SnowTricks.git```

2. Configurer le fichier .env avec vos informations de base de données et vos identifiants Google pour les emails
```DATABASE_URL="mysql://user:password@host:port/database_name"```
```MAILER_DSN=gmail://email:password@default```
Lancer ensuite dans un terminal le serveur de mail
```symfony console messenger:consume async -vv```

2. Installer les dépendances
```composer install```

3. Installer les dépendances npm
```npm install && npm run build```

4. Créer la base de données et les fixtures
```symfony console doctrine:database:create```
```symfony console make:migration```
```symfony console doctrine:migrations:migrate```
```symfony console doctrine:fixtures:load```

5. Lancer le serveur dans un autre terminal
```symfony server:start```


## Comptes de test
Nous avons créé des comptes de test pour vous faciliter la tâche. Vous pouvez vous connecter avec les comptes suivants :
-   **Utilisateur** : admin
-   **Mot de passe** : admin

-  **Utilisateur** : user
-   **Mot de passe** : user