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

-- Listage de la structure de table project3. incidents
CREATE TABLE IF NOT EXISTS `incidents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bien_id` bigint unsigned NOT NULL,
  `contrat_id` bigint unsigned DEFAULT NULL,
  `locataire_id` bigint unsigned DEFAULT NULL,
  `gestionnaire_id` bigint unsigned NOT NULL,
  `categorie` enum('plomberie','electricite','climatisation','menuiserie','peinture','toiture','portail','jardin','autre') COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priorite` enum('basse','moyenne','haute','urgente') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'moyenne',
  `impact` enum('mineur','moyen','majeur','critique') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'moyen',
  `date_signalement` datetime NOT NULL,
  `date_intervention` datetime DEFAULT NULL,
  `date_resolution` datetime DEFAULT NULL,
  `prestataire_nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prestataire_telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `travaux_effectues` text COLLATE utf8mb4_unicode_ci,
  `cout_estime` decimal(12,2) DEFAULT NULL,
  `cout_reel` decimal(12,2) DEFAULT NULL,
  `charge_par` enum('proprietaire','locataire','agence') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('nouveau','en_attente','en_cours','resolu','annule','reporte') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nouveau',
  `photos` json DEFAULT NULL,
  `documents` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `historique` json DEFAULT NULL,
  `note_satisfaction` int DEFAULT NULL,
  `commentaire_satisfaction` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `incidents_numero_unique` (`numero`),
  KEY `incidents_contrat_id_foreign` (`contrat_id`),
  KEY `incidents_locataire_id_foreign` (`locataire_id`),
  KEY `incidents_gestionnaire_id_foreign` (`gestionnaire_id`),
  KEY `incidents_statut_priorite_index` (`statut`,`priorite`),
  KEY `incidents_bien_id_date_signalement_index` (`bien_id`,`date_signalement`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project3.incidents : 0 rows
DELETE FROM `incidents`;
/*!40000 ALTER TABLE `incidents` DISABLE KEYS */;
/*!40000 ALTER TABLE `incidents` ENABLE KEYS */;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
