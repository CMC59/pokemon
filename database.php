<?php
class DAO
{
    public function __construct()
    {
    }

    public function connexion()
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=pokemonbdd', 'root', '');
        return $pdo;
    }

    // Fonction pour insérer un nouveau Pokémon dans la base de données s'il n'existe pas déjà
    public function insertPokemon($pokedexId, $name, $image, $sprite, $apiTypes, $apiGeneration, $apiEvolutions, $apiPreEvolution)
        {
            $bdd = $this->connexion();
            
            // Vérifier si le Pokémon n'existe pas déjà dans la base de données
            $reponse = $bdd->prepare("
                INSERT INTO pokemons(pokedexId, name, image, sprite, apiTypes, apiGeneration, apiEvolutions, apiPreEvolution) 
                SELECT ?, ?, ?, ?, ?, ?, ?, ? 
                FROM DUAL 
                WHERE NOT EXISTS (SELECT 1 FROM pokemons WHERE pokedexId = ?)
            ");
            $reponse->execute([$pokedexId, $name, $image, $sprite, $apiTypes, $apiGeneration, $apiEvolutions, $apiPreEvolution, $pokedexId]);
        }

    public function isPokemonsTableEmpty()
    {
        $bdd = $this->connexion();
        $reponse = $bdd->query("SELECT COUNT(*) FROM pokemons");
        $count = $reponse->fetchColumn();
        $reponse->closeCursor();

        return ($count == 0);
    }
    
    public function insertPokemonsFromAPI()
    {
        $data = file_get_contents("https://pokebuildapi.fr/api/v1/pokemon");
        $decoded_data = json_decode($data);

        if ($decoded_data && is_array($decoded_data)) {
            foreach ($decoded_data as $pokemon) {
                // Extraire les données nécessaires
                $pokedexId = $pokemon->pokedexId;
                $name = $pokemon->name;
                $image = $pokemon->image;
                $sprite = $pokemon->sprite;
                $apiTypes = json_encode($pokemon->apiTypes);
                $apiGeneration = $pokemon->apiGeneration;
                $apiEvolutions = json_encode($pokemon->apiEvolutions); 
                $apiPreEvolution = json_encode($pokemon->apiPreEvolution);

                // Appeler la fonction InsertPokemon pour insérer les données dans la base de données
                $this->insertPokemon($pokedexId, $name, $image, $sprite, $apiTypes, $apiGeneration, $apiEvolutions, $apiPreEvolution);
            }
        }
    }

    // Fonction pour mettre à jour un Pokémon existant dans la base de données
    public function updatePokemon($pokedexId, $name, $image, $sprite, $apiTypes, $apiGeneration, $apiEvolutions, $apiPreEvolution)
    {
        $bdd = $this->connexion();
        
        $reponse = $bdd->prepare("UPDATE pokemons SET name = ?, image = ?, sprite = ?, apiTypes = ?, apiGeneration = ?, apiEvolutions = ?, apiPreEvolution = ? WHERE pokedexId = ?");
        $reponse->execute([$name, $image, $sprite, $apiTypes, $apiGeneration, $apiEvolutions, $apiPreEvolution, $pokedexId]);
        
    }

    public function updatePokemonDetails($pokedexId, $newName)
    {
        $bdd = $this->connexion();
        $reponse = $bdd->prepare("UPDATE pokemons SET name=? WHERE pokedexId=?");
        $reponse->execute([$newName, $pokedexId]);
    
        return $reponse->rowCount() > 0; // Vérifiez le nombre de lignes affectées
    }
    


    // Fonction pour obtenir le nombre total d'enregistrements dans la table des pokémons
    public function getTotalRecords()
    {
        $bdd = $this->connexion();
        $reponse = $bdd->query("SELECT COUNT(*) FROM pokemons");
        $totalRecords = $reponse->fetchColumn();
        return $totalRecords;
    }


    public function listPokemons()
    {
        $bdd = $this->connexion();
        $reponse = $bdd->prepare("SELECT pokedexId, name, sprite, image FROM pokemons");
        $reponse->execute();
        $lst = [];
        while ($ligne = $reponse->fetch()) {
            $lst[] = [$ligne[0], $ligne[1], $ligne[2], $ligne[3]];
        }
        $reponse->closeCursor();
        return $lst;
    }
    


    public function PokemonById($pokedexId)
	{
		$bdd = $this->connexion();
		$reponse = $bdd->prepare("SELECT * from pokemons where pokedexId= ?");
		$reponse->execute([$pokedexId]);
		$lst = [];
		while ($ligne = $reponse->fetch()) {
			$lst[] = [$ligne[0], $ligne[1], $ligne[2], $ligne[3], $ligne[4], $ligne[5], $ligne[6], $ligne[7], $ligne[8]];
		}
		$reponse->closeCursor();
		return $lst;
	}

    public function PokemonByName($name)
    {
        $bdd = $this->connexion();
        $reponse = $bdd->prepare("SELECT * from pokemons where name= ?");
        $reponse->execute([$name]);
        $lst = [];
        while ($ligne = $reponse->fetch()) {
            $lst[] = [$ligne[0], $ligne[1], $ligne[2], $ligne[3], $ligne[4], $ligne[5], $ligne[6], $ligne[7], $ligne[8]];
        }
        $reponse->closeCursor();
        return $lst;
    }


    public function PokemonByIdOrName($input)
    {
        // Vérifier si l'entrée est un nombre (ID) ou une chaîne de caractères (nom)
        if (is_numeric($input)) {
            $pokemon = $this->PokemonById($input);
        } else {
            $pokemon = $this->PokemonByName($input);
        }
    
        // If the Pokémon doesn't exist, fetch it from the API and insert it
        if (empty($pokemon)) {
            $apiPokemon = $this->getPokemonFromApi($input);
    
            if ($apiPokemon) {
                // Extract necessary data from the API response
                $pokedexId = isset($apiPokemon->pokedexId) ? $apiPokemon->pokedexId : null;
                $name = isset($apiPokemon->name) ? $apiPokemon->name : null;
                $image = isset($apiPokemon->image) ? $apiPokemon->image : null;
                $sprite = isset($apiPokemon->sprite) ? $apiPokemon->sprite : null;
                $apiTypes = isset($apiPokemon->apiTypes) ? json_encode($apiPokemon->apiTypes) : null;
                $apiGeneration = isset($apiPokemon->apiGeneration) ? $apiPokemon->apiGeneration : null;
                $apiEvolutions = isset($apiPokemon->apiEvolutions) ? json_encode($apiPokemon->apiEvolutions) : null;
                $apiPreEvolution = isset($apiPokemon->apiPreEvolution) ? json_encode($apiPokemon->apiPreEvolution) : null;
    
                // Insert the Pokémon into the database
                $this->insertPokemon($pokedexId, $name, $image, $sprite, $apiTypes, $apiGeneration, $apiEvolutions, $apiPreEvolution);
    
                // Retrieve the Pokémon from the database after insertion
                $pokemon = $this->PokemonById($pokedexId);
            }
        }
    
        return $pokemon;
    }

    public function deletePokemon($pokemonId)
    {
        $bdd = $this->connexion();
        $reponse = $bdd->prepare("DELETE FROM pokemons WHERE pokedexId = ?");
        $reponse->execute([$pokemonId]);
        return $reponse->rowCount() > 0;
    }
    

    public function getPokemonByGeneration($generation)
    {
        $bdd = $this->connexion();
        $reponse = $bdd->prepare("SELECT * FROM pokemons WHERE apiGeneration = ?");
        $reponse->execute([$generation]);
        $pokemonList = $reponse->fetchAll(PDO::FETCH_ASSOC);
        $reponse->closeCursor();
        return $pokemonList;
    }

    public function countPokemonByGeneration($generation)
{
    $bdd = $this->connexion();
    $reponse = $bdd->prepare("SELECT COUNT(pokedexId) as total FROM pokemons WHERE apiGeneration = ?");
    $reponse->execute([$generation]);
    $result = $reponse->fetchColumn();
    $reponse->closeCursor();

    // Renvoie directement la valeur du COUNT(pokedexId)
    return $result;
}


        public function getPokemonByGenerationWithPagination($generation, $offset, $limit)
    {
        $bdd = $this->connexion();
        $reponse = $bdd->prepare("SELECT * FROM pokemons WHERE apiGeneration = ? LIMIT ?, ?");
        $reponse->bindParam(1, $generation, PDO::PARAM_INT);
        $reponse->bindParam(2, $offset, PDO::PARAM_INT);
        $reponse->bindParam(3, $limit, PDO::PARAM_INT);
        $reponse->execute();

        $pokemonList = $reponse->fetchAll(PDO::FETCH_ASSOC);
        $reponse->closeCursor();

        return $pokemonList;
    }


    // Fonction pour formater une carte Pokemon
    function formatPokemonCard($pokemon)
    {
        return "
            <div class='pokemon-card'>
                <img src='{$pokemon[0][3]}' alt='{$pokemon[0][2]}' />
                <h2>{$pokemon[0][2]}</h2>
                <p>ID: {$pokemon[0][1]}</p>
            </div>";
    }

    function formatPokemons($pokemon)
    {
        return "
            <div class='pokemon-card'>
                <img class='imaagee' src='{$pokemon['image']}' alt='{$pokemon['name']}' />
                <h2>{$pokemon['name']}</h2>
                <p>ID: {$pokemon['pokedexId']}</p>
                <form method='post' action='index.php'><input type='hidden' name='pokemon_id' value='{$pokemon['pokedexId']}' />
                <button type='submit'>Supprimer</button>
                </form>
            </div>
        ";
    }

    public function checkIfPokemonExists($pokedexId)
    {
        $bdd = $this->connexion();
        $reponse = $bdd->prepare("SELECT COUNT(*) FROM pokemons WHERE pokedexId = ?");
        $reponse->execute([$pokedexId]);
        $count = $reponse->fetchColumn();
        $reponse->closeCursor();

        return ($count == 1);
    }

    public function getPokemonFromApi($pokedexId)
    {
        $apiUrl = "https://pokebuildapi.fr/api/v1/pokemon/$pokedexId";
        
        // Utilisation de @ pour supprimer les erreurs et file_get_contents
        $apiResponse = @file_get_contents($apiUrl);
    
        // Vérifier si la réponse est vide ou non
        if ($apiResponse === false || empty($apiResponse)) {
            echo("ce pokémon n'a jamais existé dans l'univers pokémon, ");
            echo("saisir un nombre entre 1 et 898");
        }
        // Décoder la réponse JSON
        $decodedData = json_decode($apiResponse);
        // Vérifier si l'objet JSON est null ou s'il contient une propriété "error"
        if ($decodedData === null || isset($decodedData->error)) {
            return null;
        }
    
        return $decodedData;
    }
    
    public function getPokemonsByType($typeName)
    {
        $bdd = $this->connexion();
        
        // Utiliser JSON_CONTAINS pour filtrer les Pokémon par type
        $query = "SELECT p.*
                FROM pokemons p
                JOIN types t ON JSON_CONTAINS(p.apiTypes, CAST('[{\"name\": \"$typeName\"}]' AS JSON), '$')
                WHERE t.name = ?";
        
        $stmt = $bdd->prepare($query);
        $stmt->execute([$typeName]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Dans votre classe DAO, ajoutez la méthode getAllTypes
    public function getAllTypes()
    {
        $bdd = $this->connexion();
        $reponse = $bdd->query("SELECT * FROM types");
        return $reponse->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Méthode pour ajouter les types depuis l'API si ils n'existent pas
    public function addTypesFromApi()
    {
        $bdd = $this->connexion();

        // Récupérer la liste des types depuis l'API
        $apiUrl = 'https://pokebuildapi.fr/api/v1/types';
        $typesFromApi = json_decode(file_get_contents($apiUrl), true);

        // Parcourir les types et les ajouter dans la table si ils n'existent pas
        foreach ($typesFromApi as $type) {
            $englishName = $type['englishName'];

            // Vérifier si le type existe déjà dans la table
            $query = "SELECT COUNT(*) AS count FROM types WHERE englishName = ?";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$englishName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] == 0) {
                // Le type n'existe pas, l'ajouter à la table
                $queryInsert = "INSERT INTO types (englishName) VALUES (?)";
                $stmtInsert = $bdd->prepare($queryInsert);
                $stmtInsert->execute([$englishName]);
            }
        }
    }
}
?>
