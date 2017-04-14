ALTER TABLE `reproduksi` ADD `idpekerja` INT NULL AFTER `insert_date`;
ALTER TABLE `reproduksi` ADD INDEX(`idpekerja`);
ALTER TABLE `reproduksi` CHANGE `idpemakai` `idpemakai` INT(11) NULL, CHANGE `totalmodal` `totalmodal` DOUBLE NULL, CHANGE `keterangan` `keterangan` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;