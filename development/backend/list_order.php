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
require_once "./service/order_class.php";

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

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
    DropWithServerError("DB error");

// input parameters
$bAllParcels = false;

$iTimestampFrom = (isset($oPOSTData->modifies->timestampFrom) ? $oPOSTData->modifies->timestampFrom : 0);
$iTimestampTo = (isset($oPOSTData->modifies->timestampTo) ? $oPOSTData->modifies->timestampTo : 0);
$aClients = (isset($oPOSTData->modifies->clientIDs) ? $oPOSTData->modifies->clientIDs : array());
$aOrders = (isset($oPOSTData->modifies->orderIDs) ? $oPOSTData->modifies->orderIDs : array());
$sCargoName = (isset($oPOSTData->modifies->cargoName) ? $oPOSTData->modifies->cargoName : "");
$sCargoFrom = (isset($oPOSTData->modifies->cargoFrom) ? $oPOSTData->modifies->cargoFrom : "");
$sCargoTo = (isset($oPOSTData->modifies->cargoTo) ? $oPOSTData->modifies->cargoTo : "");

$sSearchWord = (isset($oPOSTData->modifies->searchWord) ? $oPOSTData->modifies->searchWord : "");

// offset and limit
$iOffset = (isset($oPOSTData->modifies->offset) ? $oPOSTData->modifies->offset : 0);
$iLimit = (isset($oPOSTData->modifies->limit) ? $oPOSTData->modifies->limit : 0);


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
    $bAllParcels = false;

    if (isset($oPOSTData->data->clientIDs))
        $aClients = array($oUser->userID);
}


$oOrder = new Order();
$aOrders = $oOrder->OrdersFromSearch(
    $mysqli,
    $iTimestampFrom,
    $iTimestampTo,
    $aClients,
    $sCargoName,
    $sCargoFrom,
    $sCargoTo,
    $sSearchWord,
    $iLimit,
    $iOffset);

$iOrdersCount = $oOrder->OrdersCountFromSearch($mysqli, $iTimestampFrom, $iTimestampTo, $aClients, $sCargoName, $sCargoFrom, $sCargoTo,$sSearchWord);

if ($iOrdersCount < 0)
    $iOrdersCount = 0;

// compile out data
$aResultDataSet = array();
$aResults = array();
//print_r($
foreach($aOrders as $oOrderRes)
{
    $aResultTmp = array(
        "clientID" => intval($oOrderRes->iOrderClientID),
        "companyID" => intval($oOrderRes->iCompanyID),
        "orderTimestamp" => intval($oOrderRes->iOrderTimestamp),
        "cargoName" => $oOrderRes->sOrderCargoName,
        "cargoFrom" => $oOrderRes->sOrderCargoFrom,
        "cargoTo" => $oOrderRes->sOrderCargoTo,
        "cargoMethod" => $oOrderRes->sOrderCargoMethod,
        "cargoSite" => $oOrderRes->sCargoSite,
        "cargoWeight" => $oOrderRes->fOrderCargoWeight,
        "cargoVol" => $oOrderRes->fOrderCargoVol,
        "cargoWidth" => $oOrderRes->fOrderCargoWidth,
        "cargoHeight" => $oOrderRes->fOrderCargoHeight,
        "cargoLength" => $oOrderRes->fOrderCargoLength,
        "cargoPrice" => floatval($oOrderRes->fOrderCargoPrice),
        "cargoValue" => floatval($oOrderRes->fOrderCargoValue),
        "cargoDesiredDate" => $oOrderRes->sOrderDesiredDate,
        "cargoDeliveryDate" => $oOrderRes->sOrderDeliveryDate,

        "orderComment" => $oOrderRes->sOrderComment,
        "orderRecepientLegalEntity" => $oOrderRes->iOrderRecipientLegalEntity,
        "orderRecepientEmail" => $oOrderRes->sOrderRecipientEmail,
        "orderRecepientPhone" => $oOrderRes->sOrderRecipientPhone,
        "orderRecepientDocumentNumber" => $oOrderRes->sOrderRecipientDocumentNumber,
        "orderRecepientDocumentTypeId" => $oOrderRes->iOrderRecipientDocumentTypeId,
        "orderRecepientFirstName" => $oOrderRes->sOrderRecipientFirstName,
        "orderRecepientSecondName" => $oOrderRes->sOrderRecipientSecondName,
        "orderRecepientLastName" => $oOrderRes->sOrderRecipientLastName,
        "orderCompanyName" => $oOrderRes->sOrderRecipientCompanyName,
        "orderRecepientCompanyName" => $oOrderRes->sOrderRecipientCompanyFormName.' '.$oOrderRes->sOrderRecipientCompanyName,
        "orderRecepientCompanyFormId" => $oOrderRes->iOrderRecipientCompanyFormId,
        "orderRecepientCompanyFormName" => $oOrderRes->sOrderRecipientCompanyFormName,
        "orderRecepientInn" => $oOrderRes->iOrderRecipientCompanyInn,
        "orderTemperatureModeName" => $oOrderRes->sOrderTemperatureModeName,
        "orderDangerClassName" => $oOrderRes->sOrderDangerClassName,
        "orderTemperatureModeId" => $oOrderRes->sOrderTemperatureModeId,
        "orderDangerClassId" => $oOrderRes->sOrderDangerClassId,
        "orderGoodsName" => $oOrderRes->sOrderGoodsName,
        "paymentTypeName" =>  $oOrderRes->sPaymentTypeName,
        "id" => intval($oOrderRes->iOrderID)
    );
    $aResult = array_merge($aResultTmp, (array) $oOrderRes->oSerializedData);
    $aResults[] = $aResult;
}

$aResultDataSet = array(
    "success" => "success",
    "totalCount" => intval($iOrdersCount),
    "data" => $aResults
);

http_response_code(200);
header('Content-Type: application/json');
print(json_encode($aResultDataSet));

?>
