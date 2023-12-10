<?php
include_once("header.php");
include_once("database.php");
include_once("navbar.php");
$dao = new DAO();

// Dans votre script principal (types.php) avant d'afficher les Pokémon
if (isset($_GET['type'])) {
    $selectedType = $_GET['type'];
    // var_dump($selectedType);
    // Récupérez les Pokémon du type sélectionné depuis la base de données
    $pokemons = $dao->getPokemonsByType($selectedType);
    // var_dump($pokemons);

// Affichez les Pokémon du type sélectionné
echo '<div class="pokemon-container">';
foreach ($pokemons as $pokemon) {
    echo '<div class="pokemon-card">';
    echo '<h2>' . $pokemon['name'] . '</h2>';
    echo '<img src="' . $pokemon['image'] . '" alt="' . $pokemon['name'] . '">';
    echo '<p>Numéro Pokedex : ' . $pokemon['pokedexId'] . '</p>';
    echo '<p>Type : ' . $selectedType . '</p>'; // Vous pouvez afficher le type ici
    echo '</div>';
}
echo '</div>';
}
include_once("footer.php");
?>

