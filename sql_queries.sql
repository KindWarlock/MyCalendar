-- Tables creation

CREATE TABLE `tasks`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100),
    `type_id` TINYINT UNSIGNED,
    `place` VARCHAR(40),
    `dt` TIMESTAMP,
    `duration` SMALLINT UNSIGNED,
    `comment` TEXT,
    `status` BOOL
    );

CREATE TABLE `types`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(30)
    );

-- Types insertion

INSERT `types`(`name`)
VALUES ('Встреча'), ('Звонок'), ('Совещание'), ('Дело');
