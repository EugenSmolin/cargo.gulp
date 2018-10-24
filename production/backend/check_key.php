<?php

/*
 * @author Anton Dovgan <blackc.blackc@gmail.com>
 * 
 */

require_once "service/config.php";
require_once "service/service.php";

if (!isset($_SERVER['HTTP_API_KEY']))
	DropWithBadRequest('No API key');

$sAPIKEY = $_SERVER['HTTP_API_KEY'];

// connect to DB
$mysqli = new mysqli(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,KEYS_DBNAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError("DB error.");


$sKeyReq = 'SELECT * FROM ' . KEYS_TABLE . ' WHERE `keyname` = "' . $mysqli->real_escape_string($sAPIKEY) . '"';
$oKeyAnswer = $mysqli->query($sKeyReq);

if ($oKeyAnswer->num_rows < 1)
	DropWithUnAuth();

$oKeyRow = $oKeyAnswer->fetch_assoc();
$aKeyParts = explode(' ', $oKeyRow['description']);

	if ( $aKeyParts[0] == '!~!' ) {
		$bLog = false;
	} else {
		$bLog = true;
	}


?>
