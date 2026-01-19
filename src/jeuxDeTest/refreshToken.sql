CREATE TABLE `refresh_tokens` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `refresh_token` VARCHAR(128) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `valid` TINYINT(1) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    `expires_at` DATETIME NOT NULL,
    PRIMARY KEY(`id`),
    UNIQUE INDEX `UNIQ_1D8A9F89C74F2179` (`refresh_token`),
    INDEX `IDX_1D8A9F89F5B7AF75` (`username`)
) ENGINE=InnoDB;
