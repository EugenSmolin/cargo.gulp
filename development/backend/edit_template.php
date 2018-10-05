<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('check_key.php');

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/auth.php";
require_once "./service/user_class.php";
require_once "./service/ordtemplate_class.php";

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

// check mandatory parameters
if (!isset($oPOSTData->data->templateID))
    DropWithBadRequest ("No mandatory parameters");

// fetch req'd template
$oTemplate = new OrderTemplate();
$iResult = $oTemplate->TemplateFromID($mysqli, $oPOSTData->data->templateID);

if ($iResult != USER_OK)
    DropWithNotFound ();

// check admin rights and template owner
if (($cUser->userID != $oTemplate->iTemplateUserID) and (!$cUser->isAdmin))
    DropWithForbidden ();

if (isset($oPOSTData->modifies->templateName))
        $oTemplate->sTemplateName = $mysqli->real_escape_string($oPOSTData->modifies->templateName);

if (isset($oPOSTData->modifies->cargoID))
        $oTemplate->iTemplateCompanyID = intval($oPOSTData->modifies->cargoID);

if (isset($oPOSTData->modifies->cargoName))
        $oTemplate->sTemplateOrderCargoName = $mysqli->real_escape_string($oPOSTData->modifies->cargoName);

if (isset($oPOSTData->modifies->cargoFrom))
        $oTemplate->sTemplateOrderCargoFrom = $mysqli->real_escape_string($oPOSTData->modifies->cargoFrom);

if (isset($oPOSTData->modifies->cargoTo))
        $oTemplate->sTemplateOrderCargoTo = $mysqli->real_escape_string($oPOSTData->modifies->cargoTo);

if (isset($oPOSTData->modifies->cargoWeight))
        $oTemplate->fTemplateOrderCargoWeight = floatval($oPOSTData->modifies->cargoWeight);

if (isset($oPOSTData->modifies->cargoVol))
        $oTemplate->fTemplateOrderCargoVol = floatval($oPOSTData->modifies->cargoVol);

if (isset($oPOSTData->modifies->cargoLength))
        $oTemplate->fTemplateOrderCargoLength = floatval($oPOSTData->modifies->cargoLength);

if (isset($oPOSTData->modifies->cargoWidth))
        $oTemplate->fTemplateOrderCargoWidth = floatval($oPOSTData->modifies->cargoWidth);

if (isset($oPOSTData->modifies->cargoHeight))
        $oTemplate->fTemplateOrderCargoHeight = floatval($oPOSTData->modifies->cargoHeight);

if (isset($oPOSTData->modifies->cargoValue))
        $oTemplate->fTemplateOrderCargoValue = floatval($oPOSTData->modifies->cargoValue);

if (isset($oPOSTData->modifies->cargoMethod))
        $oTemplate->sTemplateOrderCargoMethod = $mysqli->real_escape_string($oPOSTData->modifies->cargoMethod);

if (isset($oPOSTData->modifies->cargoSite))
        $oTemplate->sTemplateCargoSite = $mysqli->real_escape_string($oPOSTData->modifies->cargoSite);

if (isset($oPOSTData->modifies->comment))
        $oTemplate->sTemplateComment = $mysqli->real_escape_string($oPOSTData->modifies->comment);

$iResult = $oTemplate->SaveTemplate($mysqli);
//print($iResult);
$mysqli->close();

if ($iResult == USER_OK)
        ReturnSuccess();
else
        DropWithServerError("DB error or no changes");

exit(0);