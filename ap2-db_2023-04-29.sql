-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 29 avr. 2023 à 19:05
-- Version du serveur :  8.0.23
-- Version de PHP : 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ap2`
--

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `idreservation` int NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL,
  `idsalle` int NOT NULL,
  `iduser` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`idreservation`, `date`, `heure`, `idsalle`, `iduser`) VALUES
(10, '2023-04-19', '11:00:00', 3, 1),
(11, '2023-04-14', '08:00:00', 2, 1),
(12, '2023-04-21', '16:00:00', 1, 1),
(13, '2023-04-22', '12:00:00', 3, 1),
(14, '2023-04-22', '12:00:00', 5, 1),
(15, '2023-04-16', '10:00:00', 1, 2),
(16, '2023-04-16', '10:00:00', 4, 2),
(17, '2023-04-16', '09:00:00', 4, 1),
(18, '2023-04-16', '09:00:00', 3, 1),
(19, '2023-04-22', '12:00:00', 2, 1),
(20, '2023-04-19', '15:00:00', 1, 1),
(21, '2023-04-15', '15:00:00', 2, 2),
(22, '2023-04-17', '14:00:00', 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `idsalle` int NOT NULL,
  `nomsalle` varchar(50) NOT NULL,
  `idtype` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`idsalle`, `nomsalle`, `idtype`) VALUES
(1, 'Es1', 2),
(2, 'Es2', 2),
(3, 'Amphi', 1),
(4, 'Es3', 2),
(5, 'Es4', 3);

-- --------------------------------------------------------

--
-- Structure de la table `typesalle`
--

CREATE TABLE `typesalle` (
  `idtype` int NOT NULL,
  `nomtype` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `typesalle`
--

INSERT INTO `typesalle` (`idtype`, `nomtype`) VALUES
(1, 'Amphithéâtre '),
(2, 'Reunion'),
(3, 'Multimedia');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `iduser` int NOT NULL,
  `Login` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`iduser`, `Login`, `Password`) VALUES
(1, 'Zyzz', '1234'),
(2, 'JeanCyril', '4567');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`idreservation`),
  ADD KEY `reservation_ibfk_1` (`idsalle`),
  ADD KEY `iduser` (`iduser`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`idsalle`),
  ADD KEY `idtype` (`idtype`);

--
-- Index pour la table `typesalle`
--
ALTER TABLE `typesalle`
  ADD PRIMARY KEY (`idtype`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `idreservation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`idsalle`) REFERENCES `salle` (`idsalle`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `salle`
--
ALTER TABLE `salle`
  ADD CONSTRAINT `salle_ibfk_1` FOREIGN KEY (`idtype`) REFERENCES `typesalle` (`idtype`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
