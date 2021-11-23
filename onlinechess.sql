-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 23 nov. 2021 à 12:36
-- Version du serveur :  5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `onlinechess`
--

-- --------------------------------------------------------

--
-- Structure de la table `game`
--

DROP TABLE IF EXISTS `game`;
CREATE TABLE IF NOT EXISTS `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `white` int(11) NOT NULL,
  `black` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=696 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `game`
--

INSERT INTO `game` (`id`, `white`, `black`) VALUES
(695, 1, 3),
(686, 1, 2),
(687, 1, 3),
(688, 1, 4),
(689, 1, 6),
(690, 5, 1),
(691, 1, 4),
(692, 1, 2),
(693, 1, 2),
(694, 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `gamedata`
--

DROP TABLE IF EXISTS `gamedata`;
CREATE TABLE IF NOT EXISTS `gamedata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game` int(11) NOT NULL,
  `history` char(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=226 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `gamedata`
--

INSERT INTO `gamedata` (`id`, `game`, `history`) VALUES
(222, 688, '5253'),
(223, 688, '5756'),
(225, 688, '3735'),
(224, 688, '7274');

-- --------------------------------------------------------

--
-- Structure de la table `player`
--

DROP TABLE IF EXISTS `player`;
CREATE TABLE IF NOT EXISTS `player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `player`
--

INSERT INTO `player` (`id`, `login`, `password`) VALUES
(1, 'player1', '5d2bbc279b5ce75815849d5e3f0533ec'),
(2, 'player2', '88e77ff74930f37010370c296d14737b'),
(3, 'player3', '1aa3814dca32e4c0b79a2ca047ef1db0'),
(4, 'player4', '12efaba7fd50f5c66bd295683c0ce2a7'),
(5, 'player5', 'c5aec8b7110bb97bf59ab1a06805ebdd');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
