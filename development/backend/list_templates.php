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
	DropWithServerError("DB error");

// input parameters
$bAllTemplates = true;

$iTimestampFrom = (isset($oPOSTData->data->timestampFrom) ? $oPOSTData->data->timestampFrom : 0);
$iTimestampTo = (isset($oPOSTData->data->timestampTo) ? $oPOSTData->data->timestampTo : 0);
$aUsers = (isset($oPOSTData->data->userIDs) ? $oPOSTData->data->userIDs : array());
$aTemplateIDs = (isset($oPOSTData->data->templateIDs) ? $oPOSTData->data->templateIDs : array());
$aCargos = (isset($oPOSTData->data->companyIDs) ? $oPOSTData->data->companyIDs : array());
$sCargoName = (isset($oPOSTData->data->cargoName) ? $oPOSTData->data->cargoName : "");
$sCargoFrom = (isset($oPOSTData->data->cargoFrom) ? $oPOSTData->data->cargoFrom : "");
$sCargoTo = (isset($oPOSTData->data->cargoTo) ? $oPOSTData->data->cargoTo : "");

//print_r($aTemplateIDs);

// offset and limit
$iOffset = (isset($oPOSTData->data->offset) ? $oPOSTData->data->offset : 0);
$iLimit = (isset($oPOSTData->data->limit) ? $oPOSTData->data->limit : 0);

// check auth and rights
// create User object
$oUser = new User();

// trying to authenticate
$iAuth = $oUser->UserFromID($mysqli, $iUserID);

if (($iAuth != USER_OK) or (!$oUser->objectOK))
	DropWithUnAuth();

// if not admin
if (!$oUser->isAdmin)
	{
		$bAllTemplates = false;
		//if (isset($oPOSTData->data->userIDs))
		$aUsers = array($oUser->userID);
	}
	//print_r($aUsers);		
// request
$oTemplates = new OrderTemplate();
$aTemplates = $oTemplates->TemplatesFromSearch($mysqli, $aTemplateIDs, $iTimestampFrom, $iTimestampTo, $aUsers, $sCargoName, $sCargoFrom, $sCargoTo,
				$iOffset, $iLimit);

$iTemplatesCount = $oTemplates->TemplatesCountFromSearch($mysqli, $aTemplateIDs, $iTimestampFrom, $iTimestampTo, $aUsers, $sCargoName, $sCargoFrom, $sCargoTo);

if ($iOrdersCount < 0)
	$iOrdersCount = 0;

// compile out data
$aResultDataSet = array();
$aResults = array();
//print_r($
foreach($aTemplates as $oTemplateRes)
	{
		$aResults[] = array(
			"userID" => intval($oTemplateRes->iTemplateUserID),
                        "companyID" => intval($oTemplateRes->iTemplateCompanyID),
			"templateTimestamp" => intval($oTemplateRes->iTemplateTimestamp),
			"cargoName" => $oTemplateRes->sTemplateOrderCargoName,
			"cargoFrom" => $oTemplateRes->sTemplateOrderCargoFrom,
			"cargoTo" => $oTemplateRes->sTemplateOrderCargoTo,
			"cargoMethod" => $oTemplateRes->sTemplateOrderCargoMethod,
			"cargoSite" => $oTemplateRes->sTemplateCargoSite,
			"cargoWeight" => $oTemplateRes->fTemplateOrderCargoWeight,
			"cargoVol" => $oTemplateRes->fTemplateOrderCargoVol,
			"cargoWidth" => $oTemplateRes->fTemplateOrderCargoWidth,
			"cargoHeight" => $oTemplateRes->fTemplateOrderCargoHeight,
			"cargoLength" => $oTemplateRes->fTemplateOrderCargoLength,
			"cargoValue" => floatval($oTemplateRes->fTemplateOrderCargoValue),
			"comment" => $oTemplateRes->sTemplateComment,
			"id" => intval($oTemplateRes->iTemplateID)
			);
	}

$aResultDataSet = array(
			"success" => "success",
			"totalCount" => intval($iTemplatesCount),
			"data" => $aResults
		);

http_response_code(200);		
header('Content-Type: application/json');
print(json_encode($aResultDataSet));

?>