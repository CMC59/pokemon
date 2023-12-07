<?php
include("header.php");
include("database.php");
include("navbar.php");
$dao = new DAO();

// Vérifier si la table "pokemons" est vide
if ($dao->isPokemonsTableEmpty()) {
    // Si elle est vide, utilisez la fonction insertPokemonsFromAPI
    $dao->insertPokemonsFromAPI();
}
    if (isset($_POST['pokemonInput'])) {
        $input = $_POST['pokemonInput'];
        $normalizedInput = ucfirst(strtolower(str_replace(' ', '', $input)));

        $pokemon = $dao->PokemonByIdOrName($normalizedInput);

        if ($pokemon) {
            // Affichage du Pokémon avec une carte CSS
            echo $dao->formatPokemonCard($pokemon);
        } else {
            // Création du Pokémon en base de données
            $dao->insertPokemon($normalizedInput, '', '', '', '', '');
            echo "Nouveau Pokémon créé avec l'ID ou le nom $normalizedInput.";
        }
    } elseif (isset($_POST['generationSelect'])) {
        $selectedGeneration = $_POST['generationSelect'];
        $pokemonList = $dao->getPokemonByGeneration($selectedGeneration);
        // Affichage de la liste des Pokémon
        echo '<div class="pokemon-list">';
        foreach ($pokemonList as $pokemon) {
            // Utilisez la fonction formatPokemons pour formater la carte du Pokémon
            echo $dao->formatPokemons($pokemon);
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $input = $_POST["pokemonInput"];
        $pokemon = $dao->PokemonByIdOrName($input);

        if (!empty($pokemon)) {
            header("Location: pokemon.php?pokemon=" . urlencode($input));
            exit();
        }
    }
    function handleSearch($dao)
    {
        if (isset($_POST['pokemonInput'])) {
            $input = $_POST['pokemonInput'];
            $normalizedInput = ucfirst(strtolower(str_replace(' ', '', $input)));
    
            $pokemon = $dao->PokemonByIdOrName($normalizedInput);
    
            if ($pokemon) {
                echo $dao->formatPokemonCard($pokemon);
            } else {
                $dao->insertPokemon($normalizedInput, '', '', '', '', '');
                echo "Nouveau Pokémon créé avec l'ID ou le nom $normalizedInput.";
            }
        }
    }
    
    function handleDeletion($dao)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pokemon_id"])) {
            $pokemonId = $_POST["pokemon_id"];
            
            if ($dao->deletePokemon($pokemonId)) {
                echo "Pokémon supprimé avec succès.";
            } else {
                echo "La suppression du Pokémon a échoué. Veuillez réessayer.";
            }
        }
    }
    
    handleSearch($dao);
    handleDeletion($dao);
    ?>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<?php
include("footer.php");
?>
