<?php
include("header.php");
include("database.php");
include("navbar.php");

$dao = new DAO();
?>

<h1>Pokémon Details</h1>

<?php
$pokemonIdentifier = $_GET["pokemon"];
$pokemonDetails = is_numeric($pokemonIdentifier) ? $dao->PokemonById($pokemonIdentifier) : $dao->PokemonByName($pokemonIdentifier);

if (!empty($pokemonDetails)) {
    // Affichez les détails du Pokémon
    echo "<h2>{$pokemonDetails[0][2]}</h2>";
    echo "<p>ID: {$pokemonDetails[0][1]}</p>";
    echo "<img src='{$pokemonDetails[0][3]}' alt='{$pokemonDetails[0][2]}' />";
    
    // Afficher les types
    $types = json_decode($pokemonDetails[0][5], true);
    echo "<p>Types: ";
    foreach ($types as $type) {
        echo "<img src='{$type["image"]}' alt='{$type["name"]}' />";
    }
    echo "</p>";

    // Afficher les évolutions
    $evolutions = json_decode($pokemonDetails[0][7], true);
    if (!empty($evolutions)) {
        echo "<p>Evolutions: ";
        foreach ($evolutions as $evolution) {
            echo "<a href='pokemon.php?pokemon={$evolution["pokedexId"]}'>{$evolution["name"]}</a>, ";
        }
        echo "</p>";
    }

    // Afficher la pré-évolution
    $preEvolution = json_decode($pokemonDetails[0][8], true);
    if (!empty($preEvolution) && $preEvolution != "none") {
        echo "<p>Pre-evolution: <a href='pokemon.php?pokemon={$preEvolution["pokedexIdd"]}'>{$preEvolution["name"]}</a></p>";
    }
    
    // ... Autres détails
} else {
    echo "Pokémon non trouvé.";
}
?>

