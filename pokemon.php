<?php
include("header.php");
include("database.php");
include("navbar.php");

$dao = new DAO();
?>

<h1>Pokémon Details</h1>

<?php

$pokemonIdentifier = $_GET["pokemon"];
$pokemonDetails = is_numeric($pokemonIdentifier)
    ? $dao->PokemonById($pokemonIdentifier)
    : $dao->PokemonByName($pokemonIdentifier);

if (!empty($pokemonDetails)) {
    // Affichez les détails du Pokémon
    echo "<h2>{$pokemonDetails[0][2]}</h2>";
    echo "<p>ID: {$pokemonDetails[0][1]}</p>";
    echo "<img src='{$pokemonDetails[0][3]}' alt='{$pokemonDetails[0][2]}' />";

    // ... Autres détails
} else {
    echo "Pokémon non trouvé.";
}
?>

<?php
include("footer.php");
?>
