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

// auth
$iUserID = CheckAuth();
if ($iUserID === false)
	DropWithUnAuth();

// parameters	
if (!isset($oPOSTData->data->id))
	DropWithBadRequest("No mandatory parameters");

/// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithUnAuth();

// create User object
$cUser = new User();

// trying to authenticate
$iAuth = $cUser->UserFromAuth($mysqli, $oPOSTData->auth->login, $oPOSTData->auth->password);

if ($iAuth != USER_OK)
	DropWithUnAuth();

// check for admin rights
if (!$cUser->isAdmin)
	DropWithForbidden();

$sDeleteDocumentURL = "DELETE FROM `" . DB_DOCUMENTS_TABLE . "` WHERE `id` = " . intval($oPOSTData->data->id);
$sDeleteDocumentResult = $mysqli->query($sDeleteDocumentURL);

if ($mysqli->affected_rows < 1)
	DropWithNotFound();
else
	ReturnSuccess();

?>
