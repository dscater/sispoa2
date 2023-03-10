ALTER TABLE `certificacions` ADD `total_cantidad` DOUBLE NOT NULL AFTER `mod_id`;
ALTER TABLE `certificacions` ADD `saldo_cantidad` DOUBLE NOT NULL AFTER `cantidad_usar`;
ALTER TABLE `certificacions` ADD `total` DECIMAL(24,2) NOT NULL AFTER `saldo_cantidad`;
ALTER TABLE `certificacions` ADD `saldo_total` DOUBLE(24,2) NOT NULL AFTER `presupuesto_usarse`;