<?php
/**
 * Created by PhpStorm.
 * User: Andrii
 * Date: 06.11.2017
 * Time: 02:03
 */

require_once "./service/config.php";
require_once "./service/service.php";

$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

$query = "ALTER TABLE `accounts`
	CHANGE COLUMN `fio` `first_name` VARCHAR(255) NULL COLLATE 'utf8_unicode_ci' AFTER `id`;
    
    ALTER TABLE `accounts`
	ADD COLUMN `second_name` VARCHAR(255) NULL AFTER `first_name`;

    ALTER TABLE `accounts`
	ADD COLUMN `last_name` VARCHAR(255) NOT NULL AFTER `second_name`;";

$oSearchResult = $mysqli->query($query);
/*
$query="SELECT *, unix_timestamp(givenDate) AS u_givenDate FROM `" . DB_USERS_TABLE . "` ORDER BY id DESC LIMIT 10";

$oSearchResult = $mysqli->query($query);
if ($mysqli->affected_rows > 0) {
    while($oRow = $oSearchResult->fetch_assoc()) {

        echo $oRow["id"], ' ', $oRow["email"], '<br>';
    }
}
*/