-- DDL para modelo de datos - Sistema de Postulación a Electivos
-- MariaDB 10+ / MySQL 8

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE;
SET SQL_MODE='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Table `users` (base Laravel)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `alumnos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `alumnos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `rut` VARCHAR(32) NOT NULL,
  `curso` VARCHAR(64) NOT NULL,
  `nivel` ENUM('3','4') NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alumnos_rut_unique` (`rut`),
  KEY `alumnos_user_id_index` (`user_id`),
  CONSTRAINT `alumnos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `roles` (opcional, estilo Spatie)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `guard_name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(255) NOT NULL,
  `model_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `areas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `areas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `areas_nombre_unique` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `sectors`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sectors` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `area_id` BIGINT UNSIGNED NOT NULL,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sectors_area_id_nombre_unique` (`area_id`,`nombre`),
  KEY `sectors_area_id_index` (`area_id`),
  CONSTRAINT `sectors_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `electivos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `electivos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `area_id` BIGINT UNSIGNED NOT NULL,
  `sector_id` BIGINT UNSIGNED NOT NULL,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `pdf_url` VARCHAR(1024) NULL,
  `nivel` ENUM('3','4','both') NOT NULL DEFAULT 'both',
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `electivos_area_id_index` (`area_id`),
  KEY `electivos_sector_id_index` (`sector_id`),
  CONSTRAINT `electivos_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `electivos_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `procesos_postulacion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `procesos_postulacion` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `estado` ENUM('active','closed') NOT NULL DEFAULT 'active',
  `max_total` INT UNSIGNED NOT NULL DEFAULT 3,
  `max_por_area` INT UNSIGNED NOT NULL DEFAULT 2,
  `inicio` DATETIME NULL DEFAULT NULL,
  `termino` DATETIME NULL DEFAULT NULL,
  `restringir_a_grupos` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `postulacion_alumnos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `postulacion_alumnos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `proceso_id` BIGINT UNSIGNED NOT NULL,
  `alumno_id` BIGINT UNSIGNED NOT NULL,
  `estado` ENUM('open','finalizado','locked') NOT NULL DEFAULT 'open',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `postulacion_alumnos_proceso_alumno_unique` (`proceso_id`,`alumno_id`),
  KEY `postulacion_alumnos_proceso_id_index` (`proceso_id`),
  KEY `postulacion_alumnos_alumno_id_index` (`alumno_id`),
  CONSTRAINT `postulacion_alumnos_proceso_id_foreign` FOREIGN KEY (`proceso_id`) REFERENCES `procesos_postulacion` (`id`) ON DELETE CASCADE,
  CONSTRAINT `postulacion_alumnos_alumno_id_foreign` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `selecciones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `selecciones` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `postulacion_alumno_id` BIGINT UNSIGNED NOT NULL,
  `electivo_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selecciones_postulacion_electivo_unique` (`postulacion_alumno_id`,`electivo_id`),
  KEY `selecciones_postulacion_alumno_id_index` (`postulacion_alumno_id`),
  KEY `selecciones_electivo_id_index` (`electivo_id`),
  CONSTRAINT `selecciones_postulacion_alumno_id_foreign` FOREIGN KEY (`postulacion_alumno_id`) REFERENCES `postulacion_alumnos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `selecciones_electivo_id_foreign` FOREIGN KEY (`electivo_id`) REFERENCES `electivos` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `grupos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `grupos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grupos_nombre_unique` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `grupo_alumno`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `grupo_alumno` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `grupo_id` BIGINT UNSIGNED NOT NULL,
  `alumno_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grupo_alumno_grupo_alumno_unique` (`grupo_id`,`alumno_id`),
  KEY `grupo_alumno_grupo_id_index` (`grupo_id`),
  KEY `grupo_alumno_alumno_id_index` (`alumno_id`),
  CONSTRAINT `grupo_alumno_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grupo_alumno_alumno_id_foreign` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
