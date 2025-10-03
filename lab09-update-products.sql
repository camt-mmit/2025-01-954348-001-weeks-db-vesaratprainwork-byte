ALTER TABLE `products` ADD `category_id` bigint unsigned AFTER `name`;

SET SQL_SAFE_UPDATES = 0;
UPDATE `products` SET `category_id` = (SELECT `id` FROM `categories` LIMIT 1);

UPDATE `products` SET `category_id` = (SELECT `id` FROM `categories` WHERE code = 'CT001' LIMIT 1)
WHERE `name` LIKE '%PHP%';

UPDATE `products` SET `category_id` = (SELECT `id` FROM `categories` WHERE code = 'CT002' LIMIT 1)
WHERE `name` LIKE '%JavaScript%';

UPDATE `products` SET `category_id` = (SELECT `id` FROM `categories` WHERE code = 'CT003' LIMIT 1)
WHERE `name` LIKE '%Python%';

ALTER TABLE `products` MODIFY `category_id` bigint unsigned NOT NULL;

ALTER TABLE `products`
ADD CONSTRAINT `products_category_id_foreign`
FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
ON DELETE RESTRICT ON UPDATE CASCADE;
