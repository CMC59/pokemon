<?php
ob_start();

include_once("header.php");
include_once("database.php");
include_once("navbar.php");
include_once("navbartype.php");
$dao = new DAO();

// Vérifier si la table "pokemons" est vide
if ($dao->isPokemonsTableEmpty()) {
    // Si elle est vide, utilisez la fonction insertPokemonsFromAPI
    $dao->insertPokemonsFromAPI();
    $dao->addTypesFromApi();
}

if (isset($_POST['pokemonInput'])) {
    $input = $_POST['pokemonInput'];
    $normalizedInput = ucfirst(strtolower(str_replace(' ', '', $input)));

    $pokemon = $dao->PokemonByIdOrName($normalizedInput);

    if ($pokemon) {
        // Affichage du Pokémon avec une carte CSS
        echo htmlspecialchars($dao->formatPokemonCard($pokemon), ENT_QUOTES, 'UTF-8');
    } else {
        // Création du Pokémon en base de données
        $dao->insertPokemon($normalizedInput, '', '', '', '', '');
        echo "Nouveau Pokémon créé avec l'ID ou le nom " . htmlspecialchars($normalizedInput, ENT_QUOTES, 'UTF-8') . ".";
    }
}

if (isset($_POST['generationSelect'])) {
    $selectedGeneration = $_POST['generationSelect'];
    $numberOfPokemon = $dao->countPokemonByGeneration($selectedGeneration);
    // Paramètres de pagination
    $resultsPerPage = 25; 
    $currentpage = isset($_POST['p']) ? $_POST['p'] : 1; 
    $offset = ($currentpage - 1) * $resultsPerPage;
    $pokemonList = $dao->getPokemonByGenerationWithPagination($selectedGeneration, $offset, $resultsPerPage);
    echo '<div class="pokemon-list">';
    foreach ($pokemonList as $pokemon) {
        echo $dao->formatPokemons($pokemon);
    }
    echo '</div>';
    $totalPages = ceil($numberOfPokemon / $resultsPerPage);
    echo '<div class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<form method="post" action="">';
        echo '<input type="hidden" name="generationSelect" value="' . $selectedGeneration . '"/>';
        echo '<input type="hidden" name="p" value="' . $i . '"/>';
        echo '<button type="submit">' . $i . '</button>';
        echo '</form>';
    }
    echo '</div>';
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
            echo htmlspecialchars($dao->formatPokemonCard($pokemon), ENT_QUOTES, 'UTF-8');
        } else {
            $dao->insertPokemon($normalizedInput, '', '', '', '', '');
            echo "Nouveau Pokémon créé avec l'ID ou le nom " . htmlspecialchars($normalizedInput, ENT_QUOTES, 'UTF-8') . ".";
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
ob_end_flush();
?>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<?php
include_once("footer.php");
?>
