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

-- Listage de la structure de table project3. contrats
CREATE TABLE IF NOT EXISTS `contrats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bien_id` bigint unsigned NOT NULL,
  `locataire_id` bigint unsigned NOT NULL,
  `gestionnaire_id` bigint unsigned NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `duree_mois` int NOT NULL,
  `date_signature` date DEFAULT NULL,
  `loyer_mensuel` decimal(12,2) NOT NULL,
  `caution` decimal(12,2) NOT NULL,
  `charges_mensuelles` decimal(12,2) NOT NULL DEFAULT '0.00',
  `frais_agence` decimal(12,2) NOT NULL DEFAULT '0.00',
  `jour_paiement` int NOT NULL DEFAULT '1',
  `mode_paiement` enum('especes','virement','cheque','autre') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'especes',
  `animaux_autorises` tinyint(1) NOT NULL DEFAULT '0',
  `sous_location_autorisee` tinyint(1) NOT NULL DEFAULT '0',
  `conditions_particulieres` text COLLATE utf8mb4_unicode_ci,
  `etat_lieux_entree` json DEFAULT NULL,
  `date_etat_lieux_entree` date DEFAULT NULL,
  `etat_lieux_sortie` json DEFAULT NULL,
  `date_etat_lieux_sortie` date DEFAULT NULL,
  `signature_locataire` text COLLATE utf8mb4_unicode_ci,
  `signature_proprietaire` text COLLATE utf8mb4_unicode_ci,
  `signature_gestionnaire` text COLLATE utf8mb4_unicode_ci,
  `renouvellement_automatique` tinyint(1) NOT NULL DEFAULT '0',
  `preavis_jours` int NOT NULL DEFAULT '90',
  `statut` enum('brouillon','actif','expire','resilie','archive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'brouillon',
  `date_resiliation` date DEFAULT NULL,
  `motif_resiliation` text COLLATE utf8mb4_unicode_ci,
  `fichier_pdf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documents_annexes` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contrats_numero_unique` (`numero`),
  KEY `contrats_bien_id_foreign` (`bien_id`),
  KEY `contrats_locataire_id_foreign` (`locataire_id`),
  KEY `contrats_gestionnaire_id_foreign` (`gestionnaire_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project3.contrats : 1 rows
DELETE FROM `contrats`;
/*!40000 ALTER TABLE `contrats` DISABLE KEYS */;
INSERT INTO `contrats` (`id`, `numero`, `bien_id`, `locataire_id`, `gestionnaire_id`, `date_debut`, `date_fin`, `duree_mois`, `date_signature`, `loyer_mensuel`, `caution`, `charges_mensuelles`, `frais_agence`, `jour_paiement`, `mode_paiement`, `animaux_autorises`, `sous_location_autorisee`, `conditions_particulieres`, `etat_lieux_entree`, `date_etat_lieux_entree`, `etat_lieux_sortie`, `date_etat_lieux_sortie`, `signature_locataire`, `signature_proprietaire`, `signature_gestionnaire`, `renouvellement_automatique`, `preavis_jours`, `statut`, `date_resiliation`, `motif_resiliation`, `fichier_pdf`, `documents_annexes`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(8, 'CONT-2026-0001', 17, 6, 1, '2026-02-13', '2026-04-13', 2, '2026-02-13', 500000.00, 1000000.00, 0.00, 500000.00, 12, 'virement', 1, 0, NULL, NULL, '2026-02-13', NULL, NULL, NULL, NULL, NULL, 1, 90, 'actif', NULL, NULL, NULL, NULL, NULL, '2026-02-13 04:22:30', '2026-02-14 01:06:03', NULL);
/*!40000 ALTER TABLE `contrats` ENABLE KEYS */;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
