<?php
include("header.php");
include("database.php");
include("navbar.php");

$dao = new DAO();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["pokemon"])) {
    $pokemonIdentifier = $_GET["pokemon"];
    $pokemonDetails = is_numeric($pokemonIdentifier) ? $dao->PokemonById($pokemonIdentifier) : $dao->PokemonByName($pokemonIdentifier);

    if (!empty($pokemonDetails)) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"])) {
            $newName = $_POST["name"];
            $newId = $_POST["id"];
            if ($dao->updatePokemonDetails($pokemonDetails[0][1], $newName, $newId)) {
                header("Location: pokemon.php?pokemon=" . urlencode($pokemonDetails[0][1]) . "&updated=true");
                exit();
            } else {
                header("Location: error.php");
                exit();
            }
        }

        if (isset($_GET["updated"]) && $_GET["updated"] === "true") {
            header("Location: pokemon.php?pokemon=" . urlencode($pokemonDetails[0][1]));
            exit();
        }

        ?>
        <h1>Editer le Pokémon</h1>

        <form method="post" action="">
            <input type="text" name="id" value="<?php echo $pokemonDetails[0][1]; ?>">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $pokemonDetails[0][2]; ?>">

            <button type="submit">Save Changes</button>
        </form>
        <?php
    } else {
        echo "Pokémon non trouvé.";
    }
}
include("footer.php");
?>
