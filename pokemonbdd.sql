-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 10 déc. 2023 à 20:46
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pokemonbdd`
--

-- --------------------------------------------------------

--
-- Structure de la table `pokemons`
--

DROP TABLE IF EXISTS `pokemons`;
CREATE TABLE IF NOT EXISTS `pokemons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pokedexId` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sprite` varchar(255) NOT NULL,
  `apiTypes` json DEFAULT NULL,
  `apiGeneration` int NOT NULL,
  `apiEvolutions` json DEFAULT NULL,
  `apiPreEvolution` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `englishName` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `types`
--

INSERT INTO `types` (`id`, `name`, `image`, `englishName`) VALUES
(37, 'Normal', 'https://static.wikia.nocookie.net/pokemongo/images/f/fb/Normal.png', 'normal'),
(38, 'Combat', 'https://static.wikia.nocookie.net/pokemongo/images/3/30/Fighting.png', 'fighting'),
(39, 'Vol', 'https://static.wikia.nocookie.net/pokemongo/images/7/7f/Flying.png', 'flying'),
(40, 'Poison', 'https://static.wikia.nocookie.net/pokemongo/images/0/05/Poison.png', 'poison'),
(41, 'Sol', 'https://static.wikia.nocookie.net/pokemongo/images/8/8f/Ground.png', 'ground'),
(42, 'Roche', 'https://static.wikia.nocookie.net/pokemongo/images/0/0b/Rock.png', 'rock'),
(43, 'Insecte', 'https://static.wikia.nocookie.net/pokemongo/images/7/7d/Bug.png', 'bug'),
(44, 'Spectre', 'https://static.wikia.nocookie.net/pokemongo/images/a/ab/Ghost.png', 'ghost'),
(45, 'Acier', 'https://static.wikia.nocookie.net/pokemongo/images/c/c9/Steel.png', 'steel'),
(46, 'Feu', 'https://static.wikia.nocookie.net/pokemongo/images/3/30/Fire.png', 'fire'),
(47, 'Eau', 'https://static.wikia.nocookie.net/pokemongo/images/9/9d/Water.png', 'water'),
(48, 'Plante', 'https://static.wikia.nocookie.net/pokemongo/images/c/c5/Grass.png', 'grass'),
(49, 'Électrik', 'https://static.wikia.nocookie.net/pokemongo/images/2/2f/Electric.png', 'electric'),
(50, 'Psy', 'https://static.wikia.nocookie.net/pokemongo/images/2/21/Psychic.png', 'psychic'),
(51, 'Glace', 'https://static.wikia.nocookie.net/pokemongo/images/7/77/Ice.png', 'ice'),
(52, 'Dragon', 'https://static.wikia.nocookie.net/pokemongo/images/c/c7/Dragon.png', 'dragon'),
(53, 'Ténèbres', 'https://static.wikia.nocookie.net/pokemongo/images/0/0e/Dark.png', 'dark'),
(54, 'Fée', 'https://static.wikia.nocookie.net/pokemongo/images/4/43/Fairy.png', 'fairy');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
