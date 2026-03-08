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

-- Listage de la structure de table project3. biens
CREATE TABLE IF NOT EXISTS `biens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('villa','appartement','studio','magasin','bureau','terrain') COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commune` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quartier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Abidjan',
  `nombre_pieces` int DEFAULT NULL,
  `nombre_chambres` int DEFAULT NULL,
  `nombre_salles_bain` int DEFAULT NULL,
  `superficie` decimal(10,2) DEFAULT NULL,
  `etage` int DEFAULT NULL,
  `meuble` tinyint(1) NOT NULL DEFAULT '0',
  `equipements` json DEFAULT NULL,
  `prix_loyer` decimal(12,2) NOT NULL,
  `prix_caution` decimal(12,2) DEFAULT NULL,
  `charges` decimal(12,2) NOT NULL DEFAULT '0.00',
  `nom_proprietaire` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone_proprietaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_proprietaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('disponible','occupe','maintenance','reserve') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'disponible',
  `photos` json DEFAULT NULL,
  `documents` json DEFAULT NULL,
  `gestionnaire_id` bigint unsigned NOT NULL,
  `date_acquisition` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `biens_reference_unique` (`reference`),
  KEY `biens_gestionnaire_id_foreign` (`gestionnaire_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project3.biens : 3 rows
DELETE FROM `biens`;
/*!40000 ALTER TABLE `biens` DISABLE KEYS */;
INSERT INTO `biens` (`id`, `reference`, `type`, `titre`, `description`, `adresse`, `commune`, `quartier`, `ville`, `nombre_pieces`, `nombre_chambres`, `nombre_salles_bain`, `superficie`, `etage`, `meuble`, `equipements`, `prix_loyer`, `prix_caution`, `charges`, `nom_proprietaire`, `telephone_proprietaire`, `email_proprietaire`, `statut`, `photos`, `documents`, `gestionnaire_id`, `date_acquisition`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(17, 'BIE-2026-0001', 'villa', 'VILLA DUPLEX', 'GCTDTBHGYCFVVWEYWEWAZXZQA12ECKYK', 'FRED N\'KAZA,272,2343', 'BINGERVILLE', 'SICOGI', 'ABIDJAN', 4, 3, 3, 150.00, 0, 0, '["Piscine", "Climatisation", "Wifi", "Balcon", "Cuisine Équipée"]', 500000.00, 1000000.00, 500000.00, 'KONE ZANA', '0123424423', 'konezana@gmail.com', 'occupe', '["biens/photos/ecy3XHmQ1dW7Qa4UmFgxiC6XTIxMgWLgHY9wOIUG.jpg", "biens/photos/AkpLQujmHzSq5X23brlyBoibGOpEhXXV4q9bY49o.webp", "biens/photos/Z35bonFWsfSXYQTgqd3zWtQQnmPFdGAjgNmb6ZUt.webp"]', '[]', 1, '2025-09-03', 'RAS', '2026-02-13 04:18:37', '2026-02-15 14:05:25', NULL),
	(20, 'BIE-2026-0018', 'studio', 'STUDIO AMÉRICAIN', 'DJNZCJHAGHE EUBVFUEYABVAUFUGBVFUGVUGAFVGUFVGUVAGUVGFUVAGUVGUFVUGFVGUVFGUVFUG', 'ST ANDRÉ, 253, 2373', 'COCODY', 'ANGRE CHATEAU', 'ABIDJAN', 1, NULL, 1, 70.00, NULL, 0, '["Climatisation", "Wifi", "Cuisine Équipée"]', 100000.00, 200000.00, 100000.00, 'NEOULO DERICK', '0239349434', 'neouloderick@gmail.com', 'disponible', '["biens/photos/l3rnDVMbzgUhOQXwWRmNFn7QDF3kLAJRrNFXd14o.jpg", "biens/photos/rmsAxqZY6YBl9p9eS848YTJw7mo1n4UGFHgNL9fJ.jpg", "biens/photos/GNWJ37sIZRaKQmI2SZxFm8G9937wqXRW6JcqUpx9.webp"]', '[]', 1, '2025-10-15', NULL, '2026-02-15 01:27:21', '2026-02-15 01:29:53', NULL),
	(21, 'BIE-2026-0021', 'bureau', 'BUREAU MODERNE ET SPACIEUX', 'DZZEKLNZVPINVPPZIVZNVZKZRNVPNV', 'RENAISSANCE,594,0234', 'PLATEAU', 'RÉPUBLIQUE', 'ABIDJAN', 3, NULL, 2, 75.00, NULL, 0, '["Groupe Élec.", "Climatisation", "Wifi", "Balcon"]', 25000.00, 500000.00, 150000.00, 'KOUADIO ANGE', '0788102530', 'kouadioange@gmail.com', 'disponible', '["biens/photos/5MFTyFKQp0Zjee7vB7ymzAsZEXn9j90fffFzYaBW.webp", "biens/photos/TOmuSOvZJxpK0tmJwu8RXsMpXe5oo2U6d4ovMfn0.webp", "biens/photos/pLG7QOf0do0APbPqTIibnRk2o7CyrQzR62TvrkEL.webp"]', '[]', 1, '2025-06-11', NULL, '2026-02-15 01:36:15', '2026-02-16 17:10:27', NULL);
/*!40000 ALTER TABLE `biens` ENABLE KEYS */;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
