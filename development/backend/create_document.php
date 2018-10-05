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

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

$iUserID = CheckAuth();
if ($iUserID === false)
	DropWithUnAuth();

if (!isset($oPOSTData->modifies->name) or !isset($oPOSTData->modifies->content) or !isset($oPOSTData->modifies->companyID))
	DropWithBadRequest("No mandatory data");

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithUnAuth();

// create User object
$cUser = new User();

// trying to authenticate
$iAuth = $cUser->UserFromID($mysqli, $iUserID);

if ($iAuth != USER_OK)
	DropWithUnAuth();

// need to be admin
if (!$cUser->isAdmin)
	DropWithForbidden();

// prepare data
$sDocumentName = $mysqli->real_escape_string($oPOSTData->modifies->name);
$sDocumentContent = $mysqli->real_escape_string(base64_decode($oPOSTData->modifies->content));
$iDocumentCompanyID = (isset($oPOSTData->modifies->companyID) ? intval($oPOSTData->modifies->companyID) : 0);
//$iDocumentPacketParcels = ($oPOSTData->modifies->packetParcels ? 1 : 0);


// lets write
$sAddDocumentQuery = "INSERT INTO `" . DB_DOCUMENTS_TABLE . "` (`name`, `content`, `companyId`) VALUES (\"" . $sDocumentName . 
						"\", \"" . $sDocumentContent. "\", " . $iDocumentCompanyID . ")";
$mysqli->query($sAddDocumentQuery);

//print($mysqli->error);
//exit(0);
//============

if ($mysqli->error)
	{
		$mysqli->close();
		DropWithServerError("DB error.");
	}
else if ($mysqli->affected_rows > 0)
	{
		$mysqli->close();
		ReturnSuccess();
	}
else
	{
		$mysqli->close();
		DropWithServerError("Unknown");
	}

?>
