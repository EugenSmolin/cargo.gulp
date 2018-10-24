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
require_once "./service/auth.php";
require_once "./service/user_class.php";
require_once "./service/score_class.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

$iUserID = CheckAuth();
if ($iUserID === false)
	DropWithUnAuth();
	
// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithUnAuth();

// create User object
$cUser = new User();

// trying to authenticate
$iAuth = $cUser->UserFromID($mysqli, $iUserID);

if (($iAuth != USER_OK) or (!$cUser->objectOK))
	DropWithUnAuth();

// check params
if (!isset($oPOSTData->data->scoreID))
	DropWithBadRequest("Not enough parameters.");

// create Score object
$oScore = new Score();

$iResult = $oScore->ScoreFromID($mysqli,$oPOSTData->data->scoreID);

if ($iResult != USER_OK)
	DropWithNotFound();

$iScoreUserID = $oScore->scoreUserID;

// check for admin rights
if ((!$cUser->isAdmin) and ($iScoreUserID != $iUserID))
	DropWithForbidden();

// all ok, deleting

$oScore->DeleteScore($mysqli);

$mysqli->close();

ReturnSuccess();

?>
