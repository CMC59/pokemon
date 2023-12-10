# Projet Pokémon API - PHP et MySQL

![9fb125f1fedc8cc62ab5b20699ebd87d](https://github.com/CMC59/pokemon/assets/76819554/73674742-dd57-4bb5-a94a-7a61c17ce30b)

## Description du Projet

Bienvenue dans mon projet scolaire ! J'ai plongé dans l'univers captivant des Pokémon en utilisant l'API PokeBuildAPI (https://pokebuildapi.fr/api/v1).
Mon objectif principal était de récupérer des informations détaillées sur les Pokémon et de les stocker de manière organisée dans une base de données MySQL.

Mon application propose des fonctionnalités variées qui rendent l'exploration des Pokémon encore plus passionnante. Vous avez la possibilité de visualiser les Pokémon triés par génération, d'effectuer des recherches précises en utilisant l'ID ou le nom d'un Pokémon, et même de créer de nouveaux enregistrements dans la base de données si cela s'avère nécessaire.

## Fonctionnalités

- **Affichage des Pokémon par Génération :** Explorez les Pokémon classés par génération (1 à 8).
- **Recherche par ID ou Nom :** Cherchez un Pokémon spécifique par son ID ou son nom.
- **Gestion de la Base de Données :** Les Pokémon sont récupérés de l'API et stockés en base de données MySQL. Si un Pokémon n'existe pas en base, il est créé.
- **Voir les évolutions et pré-évolutions :** Après avoir recherché un pokémon, tu as la possibilité de voir son évolution et sa pré-évol en bas. En cliquant sur les liens tu seras rediriger vers l'évol ou la pré-évol.
- **Supprimer un Pokémon :** Supprimez un Pokémon existant dans la base de données.
- **Modifier un Pokémon :** Après avoir recherché un Pokémon, vous avez la possibilité de modifer le nom du Pokémon (on s'est limité à ça mais il y a déjà une fonction prête pour update plus de choses).
- **Selectionner par Type :** En cliquant sur l'une des icônes "Type", vous afficherez les Pokémons du "Type" souhaité.
## Utilisation des Langages

Ce projet a été développé en utilisant les technologies suivantes :

- **PHP :** Le côté serveur du projet est principalement développé en PHP pour gérer les logiques métier et l'interaction avec la base de données MySQL.
- **MySQL :** La base de données utilise MySQL pour stocker de manière organisée les informations détaillées sur les Pokémon.
- **HTML :** Les pages web sont construites en utilisant HTML pour définir la structure et le contenu.
- **CSS :** Les styles visuels de l'application sont stylisés à l'aide de CSS pour offrir une expérience utilisateur agréable.

## Configuration

1. Importez la structure de la base de données en utilisant le fichier fourni `pokemonbdd.sql`.

2. Modifiez les paramètres de connexion à la base de données dans le fichier `database.php`.

```php
// database.php
<?php
        $pdo = new PDO('mysql:host=SERVERADRESS;dbname=DATABASENAME', 'USERNAME', 'PASSWORD');
?>

