# Ina Zaoui

Ce projet est le site web de la photographe Ina Zaoui. Sur ce site, vous pourrez retrouver les plus belles photographies de l'artiste ainsi que de ses partenaires.

## Pré-requis

- Php >= 8.0
- Composer
- Symfony
- Une application de gestion de base de données (MySQL privilégiée)

## Installation

### Composer

Il faut, une fois le code du répertoire téléchargé, installer les dépendances à l'aide de la commande : 

```bash
composer install
```

### Configuration de la base de donnée

Dans le fichier `.env`, ajouter à la ligne 28 l'url de la base de donnée que vous allez utiliser pour stocker les informations :

```bash
DATABASE_URL="UrlDeVotreDatabase"
```

Ensuite, lancez la commande qui permet de créer votre Base de données : 

```bash
php bin/console doctrine:database:create
```

Exécutez ensuite les migrations :

```bash
php bin/console doctrine:migrations:migrate
```

Puis chargez les fixtures afin d'avoir un jeu de données pour avoir une vus d'ensemble sur le site :

```bash
php bin/console doctrine:fixtures:load
```

## Usage 

Afin de lancer l'application, lancez la commande suivante :

```bash
symfony server:start
```

Vous pouvez maintenant vous rendre sur l'application via le lien qui viens d'apparaître dans votre terminal de commande.

### Tests

Afin d'exécuter les Tests, lancez la commande : 

```bash
php bin/phpunit
```

### Comment fonctionne l'application ?

L'application est un album photo géant où les visiteurs peuvent observer les plus belles photos des photographes autorisés à poster leurs images sur ce site.
Il est cependant impossible pour les visiteurs de se créer un compte et de poster leur photo.

Pour que nos utilisateurs aient le status "d'invité", il faut que l'admin lui crée un compte et lui fournisse ses identifiants et son mot de passe.

L'admin est la seule personne à pouvoir créer des albums, des comptes pour les invités, et à pouvoir bloquer ou supprimer les invités.

Au chargement des fixtures, un compte admin est créé, dont voici les identifiants : 

```bash
Nom d'utilisateur : Ina
Mot de passe : password
```