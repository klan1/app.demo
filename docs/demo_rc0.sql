-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema k1app_demo
-- -----------------------------------------------------
-- k1.lib implementation demo app

-- -----------------------------------------------------
-- Schema k1app_demo
--
-- k1.lib implementation demo app
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `k1app_demo` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci ;
SHOW WARNINGS;
USE `k1app_demo` ;

-- -----------------------------------------------------
-- Table `k1app_demo`.`agencies`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `k1app_demo`.`agencies` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `k1app_demo`.`agencies` (
  `agency_id` INT UNSIGNED NOT NULL COMMENT 'show-update:no,show-list:no,show-export:yes',
  `agency_id_type` ENUM('CC', 'NIT') NULL,
  `agency_name` VARCHAR(100) NOT NULL COMMENT 'label-field:yes',
  `agency_logo` VARCHAR(255) NULL,
  `agency_datetime_in` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'show-create:no,show-read:no,show-update:no,show-list:no,show-export:yes',
  PRIMARY KEY (`agency_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `k1app_demo`.`departments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `k1app_demo`.`departments` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `k1app_demo`.`departments` (
  `dep_id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'show-create:no,show-read:no,show-update:no,show-list:no,show-export:yes',
  `agency_id` INT UNSIGNED NOT NULL COMMENT 'show-create:no,show-update:no,show-related:no',
  `dep_name` VARCHAR(60) NULL COMMENT 'label-field:yes',
  `dep_description` VARCHAR(60) NULL,
  `departments_datetime_in` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'show-create:no,show-read:no,show-update:no,show-list:no,show-export:yes',
  PRIMARY KEY (`dep_id`, `agency_id`),
  CONSTRAINT `fk_departments_agencies1`
    FOREIGN KEY (`agency_id`)
    REFERENCES `k1app_demo`.`agencies` (`agency_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_departments_agencies1_idx` ON `k1app_demo`.`departments` (`agency_id` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `k1app_demo`.`job_titles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `k1app_demo`.`job_titles` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `k1app_demo`.`job_titles` (
  `job_title_id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'show-create:no,show-read:no,show-update:no,show-list:no,show-export:yes',
  `dep_id` INT UNSIGNED NOT NULL COMMENT 'show-create:no,show-update:no,show-related:no',
  `agency_id` INT UNSIGNED NOT NULL COMMENT 'show-create:no,show-update:no,show-related:no',
  `job_title_name` VARCHAR(60) NOT NULL COMMENT 'label-field:yes',
  `job_title_description` TEXT NULL,
  `job_titles_datetime_in` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'show-create:no,show-read:no,show-update:no,show-list:no,show-export:yes',
  PRIMARY KEY (`job_title_id`, `dep_id`, `agency_id`),
  CONSTRAINT `fk_job_titles_departments1`
    FOREIGN KEY (`dep_id` , `agency_id`)
    REFERENCES `k1app_demo`.`departments` (`dep_id` , `agency_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_job_titles_departments1_idx` ON `k1app_demo`.`job_titles` (`dep_id` ASC, `agency_id` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `k1app_demo`.`locations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `k1app_demo`.`locations` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `k1app_demo`.`locations` (
  `location_id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'show-create:no,show-read:no,show-update:no,show-list:no,show-export:yes',
  `agency_id` INT UNSIGNED NOT NULL COMMENT 'show-create:no,show-update:no,show-related:no',
  `location_name` VARCHAR(60) NOT NULL COMMENT 'label-field:yes',
  `department` VARCHAR(60) NULL,
  `city` VARCHAR(60) NULL,
  `location_description` VARCHAR(60) NULL,
  `location_datetime_in` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'show-create:no,show-read:no,show-update:no,show-list:no,show-export:yes',
  PRIMARY KEY (`location_id`),
  CONSTRAINT `fk_locations_agencies1`
    FOREIGN KEY (`agency_id`)
    REFERENCES `k1app_demo`.`agencies` (`agency_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_locations_agencies1_idx` ON `k1app_demo`.`locations` (`agency_id` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `k1app_demo`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `k1app_demo`.`users` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `k1app_demo`.`users` (
  `user_login` VARCHAR(20) NOT NULL COMMENT 'show-update:no,show-list:no,show-export:yes',
  `user_level` ENUM('god', 'admin', 'user', 'client', 'guest') NOT NULL,
  `location_id` INT UNSIGNED NOT NULL COMMENT 'show-create:no',
  `job_title_id` INT UNSIGNED NOT NULL,
  `dep_id` INT UNSIGNED NOT NULL,
  `agency_id` INT UNSIGNED NOT NULL,
  `user_legal_id` VARCHAR(60) NULL COMMENT 'show-list:no,show-export:yes',
  `user_legal_id_type` ENUM('CC') NOT NULL COMMENT 'show-list:no,show-export:yes',
  `user_names` VARCHAR(60) NOT NULL COMMENT 'label-field:yes',
  `user_last_names` VARCHAR(60) NOT NULL COMMENT 'label-field:yes',
  `user_birthday` DATE NULL COMMENT 'show-list:no,show-export:yes',
  `user_sex` ENUM('male', 'female') NOT NULL COMMENT 'show-list:no,show-export:yes',
  `user_phone_work` VARCHAR(10) NULL,
  `user_phone_personal` VARCHAR(10) NULL COMMENT 'show-list:no,show-export:yes',
  `user_email` VARCHAR(60) NULL,
  `user_address` VARCHAR(60) NULL COMMENT 'show-list:no,show-export:yes',
  `user_password` VARCHAR(32) NULL COMMENT 'show-read:no,show-update:no,show-list:no,validation:password',
  `user_avatar` VARCHAR(60) NULL COMMENT 'show-list:no,validation:file-upload,file-max-size:50k,file-type:image',
  `user_datetime_in` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'show-create:no,show-read:no,show-update:no,show-list:no,show-export:yes',
  PRIMARY KEY (`user_login`),
  CONSTRAINT `fk_users_job_titles1`
    FOREIGN KEY (`job_title_id` , `dep_id` , `agency_id`)
    REFERENCES `k1app_demo`.`job_titles` (`job_title_id` , `dep_id` , `agency_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_users_locations1`
    FOREIGN KEY (`location_id`)
    REFERENCES `k1app_demo`.`locations` (`location_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_users_job_titles1_idx` ON `k1app_demo`.`users` (`job_title_id` ASC, `dep_id` ASC, `agency_id` ASC);

SHOW WARNINGS;
CREATE INDEX `fk_users_locations1_idx` ON `k1app_demo`.`users` (`location_id` ASC);

SHOW WARNINGS;
USE `k1app_demo` ;

-- -----------------------------------------------------
-- Placeholder table for view `k1app_demo`.`view_users_complete_data`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `k1app_demo`.`view_users_complete_data` (`user_login` INT, `user_level` INT, `user_names` INT, `user_last_names` INT, `user_email` INT, `user_password` INT, `user_avatar` INT, `location_id` INT, `location_name` INT, `department` INT, `city` INT, `agency_id` INT, `agency_name` INT, `agency_logo` INT, `dep_id` INT, `dep_name` INT, `job_title_id` INT, `job_title_name` INT);
SHOW WARNINGS;

-- -----------------------------------------------------
-- View `k1app_demo`.`view_users_complete_data`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `k1app_demo`.`view_users_complete_data` ;
SHOW WARNINGS;
DROP TABLE IF EXISTS `k1app_demo`.`view_users_complete_data`;
SHOW WARNINGS;
USE `k1app_demo`;
CREATE  OR REPLACE VIEW `view_users_complete_data` AS 

select `users`.`user_login` AS `user_login`,`users`.`user_level` AS `user_level`,`users`.`user_names` AS `user_names`,`users`.`user_last_names` AS `user_last_names`,`users`.`user_email` AS `user_email`,`users`.`user_password` AS `user_password`,`users`.`user_avatar` AS `user_avatar`,`locations`.`location_id` AS `location_id`,`locations`.`location_name` AS `location_name`,`locations`.`department` AS `department`,`locations`.`city` AS `city`,`agencies`.`agency_id` AS `agency_id`,`agencies`.`agency_name` AS `agency_name`,`agencies`.`agency_logo` AS `agency_logo`,`departments`.`dep_id` AS `dep_id`,`departments`.`dep_name` AS `dep_name`,`job_titles`.`job_title_id` AS `job_title_id`,`job_titles`.`job_title_name` AS `job_title_name` from ((((`users` join `agencies` on((`users`.`agency_id` = `agencies`.`agency_id`))) join `locations` on(((`users`.`agency_id` = `locations`.`agency_id`) and (`users`.`location_id` = `locations`.`location_id`)))) join `departments` on(((`users`.`agency_id` = `departments`.`agency_id`) and (`users`.`dep_id` = `departments`.`dep_id`)))) join `job_titles` on(((`users`.`agency_id` = `job_titles`.`agency_id`) and (`users`.`dep_id` = `job_titles`.`dep_id`) and (`users`.`job_title_id` = `job_titles`.`job_title_id`))));
SHOW WARNINGS;
USE `k1app_demo`;

DELIMITER $$

USE `k1app_demo`$$
DROP TRIGGER IF EXISTS `k1app_demo`.`agencies_BEFORE_INSERT` $$
SHOW WARNINGS$$
USE `k1app_demo`$$
CREATE DEFINER = CURRENT_USER TRIGGER `k1app_demo`.`agencies_BEFORE_INSERT` BEFORE INSERT ON `agencies` FOR EACH ROW
BEGIN
	SET NEW.agency_datetime_in = NOW();
END
$$

SHOW WARNINGS$$

USE `k1app_demo`$$
DROP TRIGGER IF EXISTS `k1app_demo`.`departments_BEFORE_INSERT` $$
SHOW WARNINGS$$
USE `k1app_demo`$$
CREATE DEFINER = CURRENT_USER TRIGGER `k1app_demo`.`departments_BEFORE_INSERT` BEFORE INSERT ON `departments` FOR EACH ROW
BEGIN
	SET NEW.departments_datetime_in = NOW();
END
$$

SHOW WARNINGS$$

USE `k1app_demo`$$
DROP TRIGGER IF EXISTS `k1app_demo`.`job_titles_BEFORE_INSERT` $$
SHOW WARNINGS$$
USE `k1app_demo`$$
CREATE DEFINER = CURRENT_USER TRIGGER `k1app_demo`.`job_titles_BEFORE_INSERT` BEFORE INSERT ON `job_titles` FOR EACH ROW
BEGIN
	SET NEW.job_titles_datetime_in = NOW();
END
$$

SHOW WARNINGS$$

USE `k1app_demo`$$
DROP TRIGGER IF EXISTS `k1app_demo`.`locations_BEFORE_INSERT` $$
SHOW WARNINGS$$
USE `k1app_demo`$$
CREATE DEFINER = CURRENT_USER TRIGGER `k1app_demo`.`locations_BEFORE_INSERT` BEFORE INSERT ON `locations` FOR EACH ROW
BEGIN
	SET NEW.location_datetime_in = NOW();
END
$$

SHOW WARNINGS$$

USE `k1app_demo`$$
DROP TRIGGER IF EXISTS `k1app_demo`.`users_BEFORE_INSERT` $$
SHOW WARNINGS$$
USE `k1app_demo`$$
CREATE DEFINER = CURRENT_USER TRIGGER `k1app_demo`.`users_BEFORE_INSERT` BEFORE INSERT ON `users` FOR EACH ROW
BEGIN
	SET NEW.user_datetime_in = NOW();
END
$$

SHOW WARNINGS$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `k1app_demo`.`agencies`
-- -----------------------------------------------------
START TRANSACTION;
USE `k1app_demo`;
INSERT INTO `k1app_demo`.`agencies` (`agency_id`, `agency_id_type`, `agency_name`, `agency_logo`, `agency_datetime_in`) VALUES (16123123, 'CC', 'Klan1', NULL, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `k1app_demo`.`departments`
-- -----------------------------------------------------
START TRANSACTION;
USE `k1app_demo`;
INSERT INTO `k1app_demo`.`departments` (`dep_id`, `agency_id`, `dep_name`, `dep_description`, `departments_datetime_in`) VALUES (1, 16123123, 'IT', 'Tech related', DEFAULT);
INSERT INTO `k1app_demo`.`departments` (`dep_id`, `agency_id`, `dep_name`, `dep_description`, `departments_datetime_in`) VALUES (2, 16123123, 'Digital agency', 'All about digital work', DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `k1app_demo`.`job_titles`
-- -----------------------------------------------------
START TRANSACTION;
USE `k1app_demo`;
INSERT INTO `k1app_demo`.`job_titles` (`job_title_id`, `dep_id`, `agency_id`, `job_title_name`, `job_title_description`, `job_titles_datetime_in`) VALUES (1, 1, 16123123, 'CEO', NULL, DEFAULT);
INSERT INTO `k1app_demo`.`job_titles` (`job_title_id`, `dep_id`, `agency_id`, `job_title_name`, `job_title_description`, `job_titles_datetime_in`) VALUES (2, 2, 16123123, 'Agency director', NULL, DEFAULT);
INSERT INTO `k1app_demo`.`job_titles` (`job_title_id`, `dep_id`, `agency_id`, `job_title_name`, `job_title_description`, `job_titles_datetime_in`) VALUES (3, 1, 16123123, 'IT Support', NULL, DEFAULT);
INSERT INTO `k1app_demo`.`job_titles` (`job_title_id`, `dep_id`, `agency_id`, `job_title_name`, `job_title_description`, `job_titles_datetime_in`) VALUES (4, 2, 16123123, 'Designer', NULL, DEFAULT);
INSERT INTO `k1app_demo`.`job_titles` (`job_title_id`, `dep_id`, `agency_id`, `job_title_name`, `job_title_description`, `job_titles_datetime_in`) VALUES (5, 2, 16123123, 'Copy', NULL, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `k1app_demo`.`locations`
-- -----------------------------------------------------
START TRANSACTION;
USE `k1app_demo`;
INSERT INTO `k1app_demo`.`locations` (`location_id`, `agency_id`, `location_name`, `department`, `city`, `location_description`, `location_datetime_in`) VALUES (1, 16123123, 'Oficina principal', 'Valle', 'Cali', 'San Fernando, Calle 4 # 35-40 ', DEFAULT);
INSERT INTO `k1app_demo`.`locations` (`location_id`, `agency_id`, `location_name`, `department`, `city`, `location_description`, `location_datetime_in`) VALUES (2, 16123123, 'Personal externo', 'Valle', 'Cali', NULL, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `k1app_demo`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `k1app_demo`;
INSERT INTO `k1app_demo`.`users` (`user_login`, `user_level`, `location_id`, `job_title_id`, `dep_id`, `agency_id`, `user_legal_id`, `user_legal_id_type`, `user_names`, `user_last_names`, `user_birthday`, `user_sex`, `user_phone_work`, `user_phone_personal`, `user_email`, `user_address`, `user_password`, `user_avatar`, `user_datetime_in`) VALUES ('tester-god', 'god', 2, 3, 1, 16123123, '666', 'CC', 'App God', 'Tester', NULL, DEFAULT, NULL, NULL, NULL, NULL, '3608cace8ae8ff8670022cff4f38f358', NULL, DEFAULT);
INSERT INTO `k1app_demo`.`users` (`user_login`, `user_level`, `location_id`, `job_title_id`, `dep_id`, `agency_id`, `user_legal_id`, `user_legal_id_type`, `user_names`, `user_last_names`, `user_birthday`, `user_sex`, `user_phone_work`, `user_phone_personal`, `user_email`, `user_address`, `user_password`, `user_avatar`, `user_datetime_in`) VALUES ('tester-admin', 'admin', 2, 3, 1, 16123123, '555', 'CC', 'App Admin', 'Tester', NULL, DEFAULT, NULL, NULL, NULL, NULL, '3608cace8ae8ff8670022cff4f38f358', NULL, DEFAULT);
INSERT INTO `k1app_demo`.`users` (`user_login`, `user_level`, `location_id`, `job_title_id`, `dep_id`, `agency_id`, `user_legal_id`, `user_legal_id_type`, `user_names`, `user_last_names`, `user_birthday`, `user_sex`, `user_phone_work`, `user_phone_personal`, `user_email`, `user_address`, `user_password`, `user_avatar`, `user_datetime_in`) VALUES ('tester-user', 'user', 2, 3, 1, 16123123, '444', 'CC', 'App User', 'Tester', NULL, DEFAULT, NULL, NULL, NULL, NULL, 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, DEFAULT);
INSERT INTO `k1app_demo`.`users` (`user_login`, `user_level`, `location_id`, `job_title_id`, `dep_id`, `agency_id`, `user_legal_id`, `user_legal_id_type`, `user_names`, `user_last_names`, `user_birthday`, `user_sex`, `user_phone_work`, `user_phone_personal`, `user_email`, `user_address`, `user_password`, `user_avatar`, `user_datetime_in`) VALUES ('tester-client', 'client', 2, 3, 1, 16123123, '333', 'CC', 'App Client', 'Tester', NULL, DEFAULT, NULL, NULL, NULL, NULL, 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, DEFAULT);
INSERT INTO `k1app_demo`.`users` (`user_login`, `user_level`, `location_id`, `job_title_id`, `dep_id`, `agency_id`, `user_legal_id`, `user_legal_id_type`, `user_names`, `user_last_names`, `user_birthday`, `user_sex`, `user_phone_work`, `user_phone_personal`, `user_email`, `user_address`, `user_password`, `user_avatar`, `user_datetime_in`) VALUES ('tester-guest', 'guest', 2, 3, 1, 16123123, '222', 'CC', 'App Guest', 'Tester', NULL, DEFAULT, NULL, NULL, NULL, NULL, 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, DEFAULT);

COMMIT;

