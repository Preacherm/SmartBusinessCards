<?php
$sql = "ALTER TABLE `" . $config['db']['pre'] . "transaction` ADD COLUMN IF NOT EXISTS `currency_code` VARCHAR(3) NULL DEFAULT NULL AFTER `base_amount`";
if (!mysqli_query($mysqli,$sql)) {
    echo("Error description: " . mysqli_error($mysqli));
}
$sql = "ALTER TABLE `".$config['db']['pre']."transaction` ADD COLUMN IF NOT EXISTS `details` TEXT NULL DEFAULT NULL AFTER `taxes_ids`";

if (!mysqli_query($mysqli,$sql)) {
    echo("Error description: " . mysqli_error($mysqli));
}