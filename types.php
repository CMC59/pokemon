<?php
include_once("header.php");
include_once("database.php");
include_once("navbar.php");
$dao = new DAO();

function generatePokemonGrid($pokemons, $selectedType, $page, $perPage) {
    $startIndex = ($page - 1) * $perPage;
    $endIndex = $startIndex + $perPage;
    
    $htmlContent = '<div class="pokemon-container">';
    $i = 0;
    
    foreach (array_slice($pokemons, $startIndex, $perPage) as $pokemon) {
        $htmlContent .= '<div class="pokemon-card">';
        $htmlContent .= '<h2>' . htmlspecialchars($pokemon['name'], ENT_QUOTES, 'UTF-8') . '</h2>';
        $htmlContent .= '<img class="imaagee" src="' . htmlspecialchars($pokemon['image'], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($pokemon['name'], ENT_QUOTES, 'UTF-8') . '">';
        $htmlContent .= '<p>Numéro Pokedex : ' . htmlspecialchars($pokemon['pokedexId'], ENT_QUOTES, 'UTF-8') . '</p>';
        $htmlContent .= '<p>Type : ' . htmlspecialchars($selectedType, ENT_QUOTES, 'UTF-8') . '</p>';
        $htmlContent .= '</div>';
        
        $i++;
        if ($i % 5 == 0) {
            $htmlContent .= '</div><div class="pokemon-container">';
        }
    }
    
    $htmlContent .= '</div>';
    return $htmlContent;
}

if (isset($_GET['type'])) {
    $selectedType = $_GET['type'];

    $pokemons = $dao->getPokemonsByType($selectedType);

    $perPage = 25;
    $page = isset($_GET['page']) ? max(1, $_GET['page']) : 1;

    $pokemonHtml = generatePokemonGrid($pokemons, $selectedType, $page, $perPage);

    echo $pokemonHtml;

    $totalPages = ceil(count($pokemons) / $perPage);
    echo '<div class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<a href="?type=' . $selectedType . '&page=' . $i . '">' . $i . '</a>';
    }
    echo '</div>';
}

include_once("footer.php");
?>
