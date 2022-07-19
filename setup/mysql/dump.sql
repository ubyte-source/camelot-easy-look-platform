-- --------------------------------------------------------
-- S.O. server:                  Linux
-- HeidiSQL Versione:            11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dump della struttura del database easy_look_platform
CREATE DATABASE IF NOT EXISTS `easy_look_platform` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `easy_look_platform`;

-- Dump della struttura di tabella easy_look_platform.project
CREATE TABLE IF NOT EXISTS `project` (
  `id_project` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `project_name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `id_project_application` int(10) unsigned NOT NULL,
  `project_header` text COLLATE utf8_unicode_ci,
  `project_footer` text COLLATE utf8_unicode_ci,
  `project_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `project_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_project`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella easy_look_platform.project_cascade_style_sheet
CREATE TABLE IF NOT EXISTS `project_cascade_style_sheet` (
  `id_project_cascade_style_sheet` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_project` int(8) unsigned NOT NULL,
  `project_cascade_style_sheet_text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `project_cascade_style_sheet_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `project_cascade_style_sheet_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_project_cascade_style_sheet`) USING BTREE,
  KEY `FOREGIN` (`id_project`) USING BTREE,
  CONSTRAINT `project_css` FOREIGN KEY (`id_project`) REFERENCES `project` (`id_project`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3438 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella easy_look_platform.project_dependencies
CREATE TABLE IF NOT EXISTS `project_dependencies` (
  `id_project_dependencies` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_project` int(8) unsigned NOT NULL,
  `project_dependencies_url` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `project_dependencies_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `project_dependencies_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_project_dependencies`),
  KEY `FOREIGN` (`id_project`),
  CONSTRAINT `project_dependencies` FOREIGN KEY (`id_project`) REFERENCES `project` (`id_project`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18563 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella easy_look_platform.project_hyper_text_markup_language
CREATE TABLE IF NOT EXISTS `project_hyper_text_markup_language` (
  `id_project_hyper_text_markup_language` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_project` int(8) unsigned NOT NULL,
  `project_hyper_text_markup_language_text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `project_hyper_text_markup_language_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `project_hyper_text_markup_language_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_project_hyper_text_markup_language`) USING BTREE,
  KEY `FOREGIN` (`id_project`),
  CONSTRAINT `project_html` FOREIGN KEY (`id_project`) REFERENCES `project` (`id_project`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17071 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella easy_look_platform.project_javascript
CREATE TABLE IF NOT EXISTS `project_javascript` (
  `id_project_javascript` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_project` int(8) unsigned NOT NULL,
  `project_javascript_text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `project_javascript_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `project_javascript_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_project_javascript`) USING BTREE,
  KEY `FOREGIN` (`id_project`) USING BTREE,
  CONSTRAINT `project_js` FOREIGN KEY (`id_project`) REFERENCES `project` (`id_project`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3505 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella easy_look_platform.user
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `_key` tinytext CHARACTER SET utf8,
  `user_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`) USING BTREE,
  UNIQUE KEY `UNIQUE` (`_key`(32)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=38377205 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella easy_look_platform.user_setting
CREATE TABLE IF NOT EXISTS `user_setting` (
  `id_user_setting` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(8) unsigned NOT NULL,
  `application` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `module` tinytext COLLATE utf8_unicode_ci,
  `view` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `widget` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `value` json NOT NULL,
  `user_setting_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_setting_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user_setting`) USING BTREE,
  UNIQUE KEY `UNIQUE` (`id_user`,`application`(32),`module`(32),`view`(32),`widget`(32)) USING BTREE,
  CONSTRAINT `user_setting` FOREIGN KEY (`id_user`) REFERENCES `energia_europa_imq`.`user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=187093 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di vista easy_look_platform.view_project
-- Creazione di una tabella temporanea per risolvere gli errori di dipendenza della vista
CREATE TABLE `view_project` (
	`id_project` INT(8) UNSIGNED NOT NULL,
	`project_name` TINYTEXT NOT NULL COLLATE 'utf8_unicode_ci',
	`project_header` TEXT NULL COLLATE 'utf8_unicode_ci',
	`project_footer` TEXT NULL COLLATE 'utf8_unicode_ci',
	`id_project_application` INT(10) UNSIGNED NOT NULL,
	`project_created` TIMESTAMP NOT NULL,
	`project_updated` TIMESTAMP NOT NULL,
	`project_dependencies` JSON NULL,
	`project_hyper_text_markup_language` LONGTEXT NULL COLLATE 'utf8mb4_bin',
	`project_javascript` JSON NULL,
	`project_cascade_style_sheet` JSON NULL
) ENGINE=MyISAM;

-- Dump della struttura di vista easy_look_platform.view_project_cascade_style_sheet
-- Creazione di una tabella temporanea per risolvere gli errori di dipendenza della vista
CREATE TABLE `view_project_cascade_style_sheet` (
	`id_project` INT(8) UNSIGNED NOT NULL,
	`project_cascade_style_sheet` JSON NULL
) ENGINE=MyISAM;

-- Dump della struttura di vista easy_look_platform.view_project_dependencies
-- Creazione di una tabella temporanea per risolvere gli errori di dipendenza della vista
CREATE TABLE `view_project_dependencies` (
	`id_project` INT(8) UNSIGNED NOT NULL,
	`project_dependencies` JSON NULL
) ENGINE=MyISAM;

-- Dump della struttura di vista easy_look_platform.view_project_hyper_text_markup_language
-- Creazione di una tabella temporanea per risolvere gli errori di dipendenza della vista
CREATE TABLE `view_project_hyper_text_markup_language` (
	`id_project` INT(8) UNSIGNED NOT NULL,
	`project_hyper_text_markup_language` LONGTEXT NULL COLLATE 'utf8mb4_bin'
) ENGINE=MyISAM;

-- Dump della struttura di vista easy_look_platform.view_project_javascript
-- Creazione di una tabella temporanea per risolvere gli errori di dipendenza della vista
CREATE TABLE `view_project_javascript` (
	`id_project` INT(8) UNSIGNED NOT NULL,
	`project_javascript` JSON NULL
) ENGINE=MyISAM;

-- Dump della struttura di vista easy_look_platform.view_project
-- Rimozione temporanea di tabella e creazione della struttura finale della vista
DROP TABLE IF EXISTS `view_project`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `view_project` AS select `project`.`id_project` AS `id_project`,`project`.`project_name` AS `project_name`,`project`.`project_header` AS `project_header`,`project`.`project_footer` AS `project_footer`,`project`.`id_project_application` AS `id_project_application`,`project`.`project_created` AS `project_created`,`project`.`project_updated` AS `project_updated`,coalesce(`view_project_dependencies`.`project_dependencies`,cast('[]' as json)) AS `project_dependencies`,coalesce(`view_project_hyper_text_markup_language`.`project_hyper_text_markup_language`,cast('[]' as json)) AS `project_hyper_text_markup_language`,coalesce(`view_project_javascript`.`project_javascript`,cast('[]' as json)) AS `project_javascript`,coalesce(`view_project_cascade_style_sheet`.`project_cascade_style_sheet`,cast('[]' as json)) AS `project_cascade_style_sheet` from ((((`project` left join `view_project_dependencies` on((`project`.`id_project` = `view_project_dependencies`.`id_project`))) left join `view_project_hyper_text_markup_language` on((`project`.`id_project` = `view_project_hyper_text_markup_language`.`id_project`))) left join `view_project_javascript` on((`project`.`id_project` = `view_project_javascript`.`id_project`))) left join `view_project_cascade_style_sheet` on((`project`.`id_project` = `view_project_cascade_style_sheet`.`id_project`)));

-- Dump della struttura di vista easy_look_platform.view_project_cascade_style_sheet
-- Rimozione temporanea di tabella e creazione della struttura finale della vista
DROP TABLE IF EXISTS `view_project_cascade_style_sheet`;
CREATE ALGORITHM=TEMPTABLE SQL SECURITY INVOKER VIEW `view_project_cascade_style_sheet` AS select `project_cascade_style_sheet`.`id_project` AS `id_project`,json_arrayagg(json_object('id_project_cascade_style_sheet',`project_cascade_style_sheet`.`id_project_cascade_style_sheet`,'id_project',`project_cascade_style_sheet`.`id_project`,'project_cascade_style_sheet_text',`project_cascade_style_sheet`.`project_cascade_style_sheet_text`,'project_cascade_style_sheet_created',`project_cascade_style_sheet`.`project_cascade_style_sheet_created`,'project_cascade_style_sheet_updated',`project_cascade_style_sheet`.`project_cascade_style_sheet_updated`)) AS `project_cascade_style_sheet` from `project_cascade_style_sheet` group by `project_cascade_style_sheet`.`id_project`;

-- Dump della struttura di vista easy_look_platform.view_project_dependencies
-- Rimozione temporanea di tabella e creazione della struttura finale della vista
DROP TABLE IF EXISTS `view_project_dependencies`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `view_project_dependencies` AS select `project_dependencies`.`id_project` AS `id_project`,json_arrayagg(json_object('id_project_dependencies',`project_dependencies`.`id_project_dependencies`,'id_project',`project_dependencies`.`id_project`,'project_dependencies_url',`project_dependencies`.`project_dependencies_url`,'project_dependencies_created',`project_dependencies`.`project_dependencies_created`,'project_dependencies_updated',`project_dependencies`.`project_dependencies_updated`)) AS `project_dependencies` from `project_dependencies` group by `project_dependencies`.`id_project`;

-- Dump della struttura di vista easy_look_platform.view_project_hyper_text_markup_language
-- Rimozione temporanea di tabella e creazione della struttura finale della vista
DROP TABLE IF EXISTS `view_project_hyper_text_markup_language`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `view_project_hyper_text_markup_language` AS select `project_hyper_text_markup_language`.`id_project` AS `id_project`,concat('[',group_concat(json_object('id_project_hyper_text_markup_language',`project_hyper_text_markup_language`.`id_project_hyper_text_markup_language`,'id_project',`project_hyper_text_markup_language`.`id_project`,'project_hyper_text_markup_language_text',`project_hyper_text_markup_language`.`project_hyper_text_markup_language_text`,'project_hyper_text_markup_language_created',`project_hyper_text_markup_language`.`project_hyper_text_markup_language_created`,'project_hyper_text_markup_language_updated',`project_hyper_text_markup_language`.`project_hyper_text_markup_language_updated`) order by `project_hyper_text_markup_language`.`id_project_hyper_text_markup_language` ASC separator ','),']') AS `project_hyper_text_markup_language` from `project_hyper_text_markup_language` group by `project_hyper_text_markup_language`.`id_project`;

-- Dump della struttura di vista easy_look_platform.view_project_javascript
-- Rimozione temporanea di tabella e creazione della struttura finale della vista
DROP TABLE IF EXISTS `view_project_javascript`;
CREATE ALGORITHM=TEMPTABLE SQL SECURITY INVOKER VIEW `view_project_javascript` AS select `project_javascript`.`id_project` AS `id_project`,json_arrayagg(json_object('id_project_javascript',`project_javascript`.`id_project_javascript`,'id_project',`project_javascript`.`id_project`,'project_javascript_text',`project_javascript`.`project_javascript_text`,'project_javascript_created',`project_javascript`.`project_javascript_created`,'project_javascript_updated',`project_javascript`.`project_javascript_updated`)) AS `project_javascript` from `project_javascript` group by `project_javascript`.`id_project`;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
