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
    } 
    if (isset($_POST['generationSelect'])) {
        $selectedGeneration = $_POST['generationSelect'];
        $numberOfPokemon = $dao->countPokemonByGeneration($selectedGeneration);
        echo "Il y a $numberOfPokemon Pokémon dans la génération $selectedGeneration.";
        var_dump($numberOfPokemon);
    
        // Paramètres de pagination
        $resultsPerPage = 25; // Nombre de résultats par page
        $currentpage = isset($_GET['p']) ? $_GET['p'] : 1; // Page actuelle, par défaut 1
    
        // Calcul de l'offset en fonction de la page actuelle
        $offset = ($currentpage - 1) * $resultsPerPage;
    
        echo "<br>Debug: Selected Generation: $selectedGeneration, Current Page: $currentpage, Offset: $offset, ResultsPerPage: $resultsPerPage";
    
        // Récupération des résultats pour la page actuelle
        $pokemonList = $dao->getPokemonByGenerationWithPagination($selectedGeneration, $offset, $resultsPerPage);
    
        // Affichage de la liste des Pokémon
        echo '<div class="pokemon-list">';
        foreach ($pokemonList as $pokemon) {
            // Utilisez la fonction formatPokemons pour formater la carte du Pokémon
            echo $dao->formatPokemons($pokemon);
        }
        echo '</div>';
    
        // Affichage des liens de pagination
        $totalPages = ceil($numberOfPokemon / $resultsPerPage);
    
        echo '<div class="pagination">';
        for ($i = 1; $i <= $totalPages; $i++) {
            $params = $_GET;
            $params['p'] = $i;
            $params['generationSelect'] = $selectedGeneration;
            $paginationLink = '?' . http_build_query($params);
            echo '<a href="' . $paginationLink . '">' . $i . '</a>';
        }
        
        echo '</div>';
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
