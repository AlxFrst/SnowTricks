# SnowTricks

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/5c74b7abd7db494cb0b2191f9ec70381)](https://app.codacy.com/gh/AlxFrst/SnowTricks/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## Description

## Prérequis

## Installation
1. Cloner le répertoir Github
```git clone https://github.com/AlxFrst/SnowTricks.git```

2. Installer les dépendances
```composer install```

3. Installer les dépendances npm
```npm install && npm run build```

4. Créer la base de données et les fixtures
```symfony console doctrine:database:create```
```symfony console make:migration```
```symfony console doctrine:migrations:migrate```
```symfony console doctrine:fixtures:load```

5. Lancer le serveur
```symfony server:start```

6. Lancer le service mail
```symfony console messenger:consume async -vv```

## Comptes de test
Nous avons créé des comptes de test pour vous faciliter la tâche. Vous pouvez vous connecter avec les comptes suivants :
-   **Utilisateur** : admin
-   **Mot de passe** : admin

-  **Utilisateur** : user
-   **Mot de passe** : user




