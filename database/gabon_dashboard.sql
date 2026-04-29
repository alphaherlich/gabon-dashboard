-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 29 avr. 2026 à 14:04
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gabon_dashboard`
--

-- --------------------------------------------------------

--
-- Structure de la table `stat_indicator`
--

CREATE TABLE `stat_indicator` (
  `id` bigint(20) NOT NULL,
  `category` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `region` varchar(255) NOT NULL,
  `insight` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `stat_value` double NOT NULL,
  `stat_year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `stat_indicator`
--

INSERT INTO `stat_indicator` (`id`, `category`, `title`, `value`, `year`, `region`, `insight`, `created_at`, `updated_at`, `stat_value`, `stat_year`) VALUES
(1, 'ECONOMY', 'PIB', 1200, 2026, 'Libreville', '📉 Risque économique à Libreville', '2026-04-28 09:56:08', '2026-04-28 19:37:00', 15, 0),
(3, 'HEALTH', 'Hôpitaux', 50, 2026, 'Libreville', '🏥 Amélioration du système de santé', '2026-04-28 09:56:08', '2026-04-28 09:56:08', 0, 0),
(4, 'EDUCATION', 'Écoles', 120, 2025, 'Port-Gentil', '🎓 Expansion du système éducatif', '2026-04-28 09:56:08', '2026-04-28 09:56:08', 0, 0),
(5, 'ECONOMY', 'PIB', NULL, NULL, 'Libreville', '📉 Risque économique à Libreville', '2026-04-28 10:18:00', '2026-04-28 10:18:00', 12.5, 2025),
(6, 'économie', 'Santé', NULL, NULL, 'Port-gentil', '📊 Analyse indisponible pour cette catégorie', '2026-04-28 10:35:32', '2026-04-28 10:35:32', 1777777, 2025),
(7, 'éducation', 'hôpital', NULL, NULL, 'Port-gentil', '📊 Analyse indisponible pour cette catégorie', '2026-04-28 10:40:27', '2026-04-28 10:40:27', 19999999, 2024),
(8, 'HEALTH', 'hôpital', NULL, NULL, 'Libreville', '🏥 Système de santé performant', '2026-04-28 19:49:51', '2026-04-28 19:49:51', 187945, 2026);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin123', 'admin', '2026-04-28 09:56:07'),
(2, 'user', 'user123', 'user', '2026-04-28 09:56:07');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `stat_indicator`
--
ALTER TABLE `stat_indicator`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `stat_indicator`
--
ALTER TABLE `stat_indicator`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
