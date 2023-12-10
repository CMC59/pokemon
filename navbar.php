<nav>
    <h1>Pokémon Search</h1>
    <form action="index.php" method="post">
        <label for="pokemonInput">Recherchez un Pokémon :</label>
        <input type="text" id="pokemonInput" name="pokemonInput" placeholder="Entrez un ID ou un nom">
        <input type="submit" value="Rechercher">
    </form>

    <form action="index.php" method="post">
        <label for="generationSelect">Sélectionnez une génération :</label>
        <select id="generationSelect" name="generationSelect">
            <option value="1">Génération 1</option>
            <option value="2">Génération 2</option>
            <option value="3">Génération 3</option>
            <option value="4">Génération 4</option>
            <option value="5">Génération 5</option>
            <option value="6">Génération 6</option>
            <option value="7">Génération 7</option>
            <option value="8">Génération 8</option>
        </select>
        <input type="submit" value="Afficher par Génération">
    </form>
    <form action="types.php" method="get"> <!-- Ajout d'une action -->
        <?php
        include_once("database.php");
        // Dans votre classe DAO, ajoutez la méthode getAllTypeIcons
        function getAllTypeIcons()
        {
            $dao = new DAO(); // Assurez-vous que vous instanciez la classe DAO
            $bdd = $dao->connexion(); // Utilisez la méthode de connexion de votre classe DAO
            $reponse = $bdd->query("SELECT id, name, image FROM types"); // Modification de la requête
            return $reponse->fetchAll(PDO::FETCH_ASSOC);
        }

        // Récupérer les icônes des types depuis la base de données
        $typeIcons = getAllTypeIcons();

        // Afficher les icônes dans le menu avec des liens vers types.php
        foreach ($typeIcons as $type) {
            echo '<a href="types.php?type=' . $type['name'] . '">'; // Utilisation de name
            echo '<img src="' . $type['image'] . '" class="type-icon" alt="' . $type['name'] . '">';
            echo '</a>';
        }
        ?>
    </form>

</nav>
