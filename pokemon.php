<?php
include_once("header.php");
include_once("database.php");
include_once("navbar.php");
include_once("navbartype.php");
$dao = new DAO();
?>
<div class="container">
    <h1 class="h1">Pokémon Details</h1>

    <?php
    $pokemonIdentifier = $_GET["pokemon"];
    $pokemonDetails = is_numeric($pokemonIdentifier) ? $dao->PokemonById($pokemonIdentifier) : $dao->PokemonByName($pokemonIdentifier);

    if (!empty($pokemonDetails)) {
        // Affichez les détails du Pokémon
        echo "<h2 class='pokemon-details'>{$pokemonDetails[0][2]}</h2>";
        echo "<p>ID: {$pokemonDetails[0][1]}</p>";
        echo "<img class='pokemon-img' src='{$pokemonDetails[0][3]}' alt='{$pokemonDetails[0][2]}' />";
        echo "<a href='pokemon-edit.php?pokemon={$pokemonDetails[0][1]}'><button class='edit-button'>Editer le Pokémon</button></a>";

        // Afficher les types
        $types = json_decode($pokemonDetails[0][5], true);
        if ($types == null or $types == []) {
            echo "Erreur : types invalides.";
        } else {
            echo "<p class='pokemon-details'>Types: ";
            foreach ($types as $type) {
                echo "<img src='{$type["image"]}' alt='{$type["name"]}' />";
            }
            echo "</p>";
        }


        // Afficher les évolutions
        $evolutions = json_decode($pokemonDetails[0][7], true);
        if (!empty($evolutions)) {
            echo "<p class='pokemon-details'>Evolutions: ";
            foreach ($evolutions as $evolution) {
                $evolutionDetails = $dao->PokemonById($evolution["pokedexId"]);
                echo "<a class='link' href='pokemon.php?pokemon={$evolutionDetails[0][1]}'>{$evolutionDetails[0][2]}";
                echo "<img src='{$evolutionDetails[0][4]}' alt='{$evolutionDetails[0][2]}'></a>";
            }
            echo "</p>";
        }

        // Afficher la pré-évolution
        $preEvolution = json_decode($pokemonDetails[0][8], true);
        if (!empty($preEvolution) && $preEvolution != "none") {
            $preEvolutionDetails = $dao->PokemonById($preEvolution["pokedexIdd"]);
            echo "<p class='pokemon-details'>Pre-evolution: <a class='link' href='pokemon.php?pokemon={$preEvolutionDetails[0][1]}'>{$preEvolutionDetails[0][2]}";
            echo "<img src='{$preEvolutionDetails[0][4]}' alt='{$preEvolutionDetails[0][2]}'></a></p>";
        }

        // ... Autres détails
    } else {
        echo "<p class='pokemon-details'>Pokémon non trouvé.</p>";
    }
    ?>
</div>

<?php
include_once("footer.php");
?>
