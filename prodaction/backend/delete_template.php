<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('check_key.php');

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/user_class.php";
require_once "./service/auth.php";
require_once "./service/ordtemplate_class.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

$iUserID = CheckAuth();
if ($iUserID === false)
	DropWithUnAuth();
	
// check if auth presence
//if ((!isset($oPOSTData->auth->login)) or (!isset($oPOSTData->auth->password)))
	//{
		//DropWithUnAuth();
	//}
	
if (!isset($oPOSTData->data->templateID))
	DropWithBadRequest("No mandatory parameters");

/// connect to DB
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

// fetch order
$oTemplate= new OrderTemplate();
$iTemplateRes = $oTemplate->TemplateFromID($mysqli,$oPOSTData->data->templateID);

if ($iTemplateRes != PARCEL_OK)
	DropWithNotFound();

if ((!$cUser->isAdmin) and ($oTemplate->iTemplateUserID != $cUser->userID))
	DropWithNotFound();
	
$oTemplate->DeleteTemplate($mysqli);

$mysqli->close();

ReturnSuccess();

