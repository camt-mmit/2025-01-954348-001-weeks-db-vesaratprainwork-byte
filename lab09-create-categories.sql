CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_code_unique` (`code`),
  KEY `categories_created_at_index` (`created_at`),
  KEY `categories_updated_at_index` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories`
(`created_at`, `updated_at`, `code`, `name`, `description`)
VALUES
(CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 'CT001', 'PHP', 'PHP category'),
(CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 'CT002', 'JavaScript', 'JavaScript category'),
(CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 'CT003', 'Python', 'Python category');
