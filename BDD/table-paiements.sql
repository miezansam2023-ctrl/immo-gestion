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

-- Listage de la structure de table project3. paiements
CREATE TABLE IF NOT EXISTS `paiements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contrat_id` bigint unsigned NOT NULL,
  `locataire_id` bigint unsigned NOT NULL,
  `bien_id` bigint unsigned NOT NULL,
  `gestionnaire_id` bigint unsigned NOT NULL,
  `type` enum('loyer','caution','charges','eau','electricite','frais_agence','reparation','penalite','autre') COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode_debut` date DEFAULT NULL,
  `periode_fin` date DEFAULT NULL,
  `mois_annee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `montant_du` decimal(12,2) NOT NULL,
  `montant_paye` decimal(12,2) NOT NULL,
  `reste_a_payer` decimal(12,2) NOT NULL DEFAULT '0.00',
  `date_echeance` date NOT NULL,
  `date_paiement` date DEFAULT NULL,
  `mode_paiement` enum('especes','virement','cheque','autre') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_paiement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('en_attente','paye','partiel','retard','annule') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `jours_retard` int NOT NULL DEFAULT '0',
  `penalite` decimal(12,2) NOT NULL DEFAULT '0.00',
  `numero_quittance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fichier_quittance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quittance_generee` tinyint(1) NOT NULL DEFAULT '0',
  `date_generation_quittance` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `fichier_recu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `paiements_numero_unique` (`numero`),
  KEY `paiements_locataire_id_foreign` (`locataire_id`),
  KEY `paiements_bien_id_foreign` (`bien_id`),
  KEY `paiements_gestionnaire_id_foreign` (`gestionnaire_id`),
  KEY `paiements_statut_date_echeance_index` (`statut`,`date_echeance`),
  KEY `paiements_contrat_id_periode_debut_index` (`contrat_id`,`periode_debut`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table project3.paiements : 1 rows
DELETE FROM `paiements`;
/*!40000 ALTER TABLE `paiements` DISABLE KEYS */;
INSERT INTO `paiements` (`id`, `numero`, `contrat_id`, `locataire_id`, `bien_id`, `gestionnaire_id`, `type`, `periode_debut`, `periode_fin`, `mois_annee`, `montant_du`, `montant_paye`, `reste_a_payer`, `date_echeance`, `date_paiement`, `mode_paiement`, `reference_paiement`, `statut`, `jours_retard`, `penalite`, `numero_quittance`, `fichier_quittance`, `quittance_generee`, `date_generation_quittance`, `description`, `notes`, `fichier_recu`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(9, 'PAY-699116F8651BE', 8, 6, 17, 1, 'loyer', NULL, NULL, 'February 2026', 500000.00, 500000.00, 0.00, '2026-02-01', '2026-02-11', 'especes', NULL, 'paye', 0, 0.00, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-02-15 00:44:40', '2026-02-15 00:44:40', NULL);
/*!40000 ALTER TABLE `paiements` ENABLE KEYS */;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
