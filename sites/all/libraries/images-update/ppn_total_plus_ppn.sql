ALTER TABLE `customer_order` ADD `ppn` DOUBLE NULL AFTER `printed`, ADD `total_plus_ppn` DOUBLE NULL AFTER `ppn`;