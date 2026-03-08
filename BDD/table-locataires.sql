-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.4.7 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.15.0.7171
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage de la structure de table project3. locataires
CREATE TABLE IF NOT EXISTS `locataires` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `civilite` enum('M','Mme','Mlle') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenoms` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `situation_matrimoniale` enum('celibataire','marie','divorce','veuf') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone_secondaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_precedente` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_piece` enum('cni','passeport','attestation_identite') COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_piece` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_delivrance_piece` date DEFAULT NULL,
  `date_expiration_piece` date DEFAULT NULL,
  `lieu_delivrance_piece` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profession` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employeur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_employeur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone_employeur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenus_mensuels` decimal(12,2) DEFAULT NULL,
  `personne_urgence_nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personne_urgence_telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personne_urgence_lien` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documents` json DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `gestionnaire_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `locataires_reference_unique` (`reference`),
  UNIQUE KEY `locataires_numero_piece_unique` (`numero_piece`),
  KEY `locataires_gestionnaire_id_foreign` (`gestionnaire_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project3.locataires : 2 rows
DELETE FROM `locataires`;
/*!40000 ALTER TABLE `locataires` DISABLE KEYS */;
INSERT INTO `locataires` (`id`, `reference`, `civilite`, `nom`, `prenoms`, `date_naissance`, `lieu_naissance`, `situation_matrimoniale`, `telephone`, `telephone_secondaire`, `email`, `adresse_precedente`, `type_piece`, `numero_piece`, `date_delivrance_piece`, `date_expiration_piece`, `lieu_delivrance_piece`, `profession`, `employeur`, `adresse_employeur`, `telephone_employeur`, `revenus_mensuels`, `personne_urgence_nom`, `personne_urgence_telephone`, `personne_urgence_lien`, `documents`, `photo`, `actif`, `notes`, `gestionnaire_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(6, 'LOC-2026-0001', 'M', 'YAO', 'WILLIAM', '2003-06-30', 'ABIDJAN', 'marie', '0173486283', NULL, 'yaowilliam@gmail.com', NULL, 'cni', 'CI0024339494', '2024-02-11', '2028-07-11', NULL, 'INFORMATICIEN', NULL, NULL, NULL, 1500000.00, 'WADJA YASMINE', '0788102530', 'conjoint', '["locataires/documents/poukwbvzwfRgfWaWBnehu4v6P3eCbdQ1uqpr83JK.pdf"]', 'locataires/photos/h2ELoLxFr22ofFMi9a0Dh2KYV25gEuV2FsBS8BOm.jpg', 0, NULL, 1, '2026-02-13 04:20:58', '2026-02-15 21:49:51', NULL),
	(9, 'LOC-2026-0007', 'M', 'YAO', 'STANISLAS', '2000-10-25', 'AGOU', 'divorce', '0505451235', NULL, 'yaostanislas@gmail.com', NULL, 'cni', 'CI003353613', '2022-03-14', '2032-03-14', NULL, 'MÉDECIN', NULL, NULL, NULL, 800000.00, 'KOFFI DELON', '0103448345', 'ami', '["locataires/documents/UFYoiTuOCvudYL94cVL3sKsFgll4ZQePQekmJhnz.pdf"]', 'locataires/photos/GlndM8Oe1i8UgyZho0Q0ZVpxMv6mzuqgeEYa18Ot.png', 0, NULL, 1, '2026-02-15 15:53:00', '2026-02-15 21:58:31', NULL);
/*!40000 ALTER TABLE `locataires` ENABLE KEYS */;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
