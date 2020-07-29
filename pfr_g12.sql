-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 21 juil. 2020 à 17:57
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pfr_g12`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20200721155403', '2020-07-21 17:54:38', 339);

-- --------------------------------------------------------

--
-- Structure de la table `profil`
--

CREATE TABLE `profil` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `profil`
--

INSERT INTO `profil` (`id`, `libelle`) VALUES
(1, 'ADMIN'),
(2, 'FORMATEUR'),
(3, 'CM');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `profil_id`, `username`, `password`, `avatar`, `prenom`, `nom`, `email`, `statut`) VALUES
(1, 1, 'admin1', '$argon2id$v=19$m=65536,t=4,p=1$MEg0ejBRWXFFaURCbkx6OQ$MsCDfw1zKmdnZ6cX/FsWQzy8xe5CmOSqyHanj7UbxmU', 'https://lorempixel.com/640/480/cats/?79066', 'Thibault', 'Delannoy', 'rene.jacquot@example.net', 'on'),
(2, 1, 'admin2', '$argon2id$v=19$m=65536,t=4,p=1$Mm9GSFdhWUt0VXBLbHpqTA$8KwmE809dsXprnA/gHh5zWpCb2aOov/+N1KOdB/bMOo', 'https://lorempixel.com/640/480/cats/?72435', 'Victoire', 'Rocher', 'giraud.augustin@example.com', 'on'),
(3, 1, 'admin3', '$argon2id$v=19$m=65536,t=4,p=1$RzNZaU5teFA1WEY1eGZDSw$SGtnxpdwabtWHPHQnHVVIE1yZ/21KJ/pCvibzfpRZts', 'https://lorempixel.com/640/480/cats/?91317', 'Margot', 'Royer', 'hoarau.thibault@example.org', 'on'),
(4, 2, 'formateur1', '$argon2id$v=19$m=65536,t=4,p=1$UUF6LmRnbzlEVmVYbWVrbw$Gfq+0BqeJ+BCkNtYz5Dze5q2Y/Nz1d1p3Ei3gDoUB8Y', 'https://lorempixel.com/640/480/cats/?67490', 'Corinne', 'Ferreira', 'payet.aimee@example.com', 'on'),
(5, 2, 'formateur2', '$argon2id$v=19$m=65536,t=4,p=1$L091NGlWTDc0UEs4ZGZSSg$pYReUZcTOd3KRFS+MiF/8Ta3fFwocFO/u3s47wHNFec', 'https://lorempixel.com/640/480/cats/?15586', 'Daniel', 'Denis', 'jcordier@example.com', 'on'),
(6, 2, 'formateur3', '$argon2id$v=19$m=65536,t=4,p=1$ZGcyNExhZHhibVVnSmM5OQ$XXighiu0+S5QY6udUH/aMLfnoh+gQrmPwT6UHnizP7A', 'https://lorempixel.com/640/480/cats/?91488', 'Mathilde', 'Fernandez', 'nathalie.germain@example.com', 'on'),
(7, 3, 'cm1', '$argon2id$v=19$m=65536,t=4,p=1$NXhQTTZQbWVkQkNETFRINg$V/xEGDz2Ii/wM0frPC/2NGCcYQFfdzLqo/ZSzM8b+GE', 'https://lorempixel.com/640/480/cats/?92586', 'Rémy', 'Guibert', 'jean74@example.org', 'on'),
(8, 3, 'cm2', '$argon2id$v=19$m=65536,t=4,p=1$SWFRSGF1NElHcEdkWDJpMg$Kf/Sh3aZGMEBPDULc3TxY99kbpUzbitANogAyzJVFi8', 'https://lorempixel.com/640/480/cats/?57078', 'Alice', 'Pineau', 'deschamps.claire@example.org', 'on'),
(9, 3, 'cm3', '$argon2id$v=19$m=65536,t=4,p=1$U1FVMGRPLzYwaU9zNTZXbg$YWVIhW7HZw3JJchI56hua1n+4b41ALKw2q6kRd8H+g8', 'https://lorempixel.com/640/480/cats/?32265', 'Cécile', 'Verdier', 'olivier61@example.com', 'on');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD KEY `IDX_8D93D649275ED078` (`profil_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `profil`
--
ALTER TABLE `profil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649275ED078` FOREIGN KEY (`profil_id`) REFERENCES `profil` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
