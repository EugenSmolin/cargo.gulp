<?php

require_once('check_key.php');

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/user_class.php";
require_once "./service/ordtemplate_class.php";
require_once "./service/auth.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

$iUserID = CheckAuth();
if ($iUserID === false)
	DropWithUnAuth();

// check if auth and new pass presence
//if ((!isset($oPOSTData->auth->login)) or (!isset($oPOSTData->auth->password)))
//	{
//		DropWithUnAuth();
//	}

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError("DB error");

// create User object
$cUser = new User();

// trying to authenticate
$iAuth = $cUser->UserFromID($mysqli, $iUserID);

if ($iAuth != USER_OK)
	DropWithUnAuth();
	
// check for parameters presence
if (!isset($oPOSTData->modifies->userID) or !isset($oPOSTData->modifies->templateName))
	{
		DropWithBadRequest("Not enough or wrong parameters");
	}

// prepare data for parcel

if (!$cUser->isAdmin)
	$iUserID = $cUser->userID;
else
	$iUserID = $oPOSTData->modifies->userID;

// create order

$oNewTemplate = new OrderTemplate();
$iNewTemplateResult = $oNewTemplate->NewTemplateFromParameters($mysqli,
						$iUserID,
                                                $oPOSTData->modifies->cargoID,
                                                $oPOSTData->modifies->templateName,
						$oPOSTData->modifies->cargoName,
						$oPOSTData->modifies->cargoFrom,
						$oPOSTData->modifies->cargoTo,
						$oPOSTData->modifies->cargoWeight,
						$oPOSTData->modifies->cargoVol,
						$oPOSTData->modifies->cargoLength,
						$oPOSTData->modifies->cargoWidth,
						$oPOSTData->modifies->cargoHeight,
						$oPOSTData->modifies->cargoValue,
						$oPOSTData->modifies->cargoMethod,
						$oPOSTData->modifies->cargoSite,
						$oPOSTData->modifies->comment);

$mysqli->close();

switch($iNewTemplateResult)
	{
		case PARCEL_NO_PARAMS:
			DropWithBadRequest("Not enough parameters");
		case PARCEL_DB_ERROR:
			DropWithServerError();
		case PARCEL_EXISTS:
			DropWithServerError("Template already exists");
		default:
			{
				//$oFinance = new Finance();
				//$oFinance->NewOperation($mysqli,0-$oPOSTData->modifies->cargoPrice, $iClientID, $iNewOrderResult, "Задолженность");
				ReturnSuccess(array("id" => $iNewTemplateResult));
			}
	}

?>