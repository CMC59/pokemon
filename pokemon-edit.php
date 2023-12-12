<?php
include_once("header.php");
include_once("database.php");
include_once("navbar.php");
include_once("navbartype.php");
$dao = new DAO();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["pokemon"])) {
    $pokemonIdentifier = $_GET["pokemon"];
    $pokemonDetails = is_numeric($pokemonIdentifier) ? $dao->PokemonById($pokemonIdentifier) : $dao->PokemonByName($pokemonIdentifier);

    if (!empty($pokemonDetails)) {
        // Affichage du message d'erreur si le Pokémon n'est pas trouvé
        ?>
        <h1>Editer le Pokémon</h1>

        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $pokemonDetails[0][1]; ?>" readonly>
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $pokemonDetails[0][2]; ?>">

            <button type="submit">Save Changes</button>
        </form>
        <?php
    } else {
        echo "Pokémon non trouvé.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"])) {
    $newName = $_POST["name"];
    $newId = $_POST["id"];

    // Utiliser des requêtes préparées pour la sécurité
    if ($dao->updatePokemonDetails($newId, $newName)) {
        header("Location: pokemon.php?pokemon=" . urlencode($newId) . "&updated=true");
    } else {
        header("Location: error.php");
    }
}

include_once("footer.php");
?>
