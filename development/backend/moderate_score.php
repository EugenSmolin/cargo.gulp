<?php

/*
 * @author Anton Dovgan <blackc.blackc@gmail.com>
 * 
 * @param string	JSON in POST body with parameters
 * 
 * @return string	JSON with results
 * 
 */

require_once('check_key.php');

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/user_class.php";
require_once "./service/auth.php";
require_once "./service/score_class.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

$iUserID = CheckAuth();
if ($iUserID === false)
	DropWithUnAuth();
	
// check if data enough
if (!isset($oPOSTData->modifies->moderationResult) or !isset($oPOSTData->data->scoreID))
		{
			DropWithBadRequest("Not enough parameters");
		}

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();

// check user rights
$oUser = new User();
$iResult = $oUser->UserFromID($mysqli,$iUserID);

if (($iResult != USER_OK) or (!$oUser->isAdmin))
	DropWithForbidden();

// create Score object
$oScore = new Score();

$iResult = $oScore->ScoreFromID($mysqli,$oPOSTData->data->scoreID);

if ($iResult != USER_OK)
	DropWithNotFound;

// all ok
$oScore->scoreModeratorID = $iUserID;
$oScore->scoreModerationTime = time();
$oScore->scoreModerationResult = $oPOSTData->modifies->moderationResult;

$iResult = $oScore->SaveScore($mysqli);

if ($iResult != USER_OK)
	DropWithServerError("DB error");
else
	ReturnSuccess();

?>
