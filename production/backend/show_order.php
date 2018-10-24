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
require_once "./service/order_class.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

//$iUserID =15;

if(IS_PRODUCTION) {
    $iUserID = CheckAuth();
    if ($iUserID === false)
        DropWithUnAuth();
}
ELSE
{
    $iUserID =15;
}
// check if auth presence 
//if ((!isset($oPOSTData->auth->login)) or (!isset($oPOSTData->auth->password)))
//{
//DropWithUnAuth();
//}

if (!isset($oPOSTData->data->orderID))
    DropWithBadRequest("Not enough parameters");

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

$mysqli->query('SET NAMES utf8; SET CHARACTER SET utf8;');

// check DB connection
if ($mysqli->connect_errno)
    DropWithServerError("DB error");

// input parameters
$iOrderID = intval($oPOSTData->data->orderID);

// check auth and rights
// create User object
$oUser = new User();

// trying to authenticate
$iAuth = $oUser->UserFromID($mysqli, $iUserID);

if (($iAuth != USER_OK) or (!$oUser->objectOK))
    DropWithUnAuth();

$oOrder = new Order();
$aOrder = $oOrder->OrderFromID($mysqli, $iOrderID);

if(!IS_PRODUCTION)
{
    echo $aOrder;
}

if ($aOrder != PARCEL_OK)
    DropWithNotFound();

// if not admin
if ((!$oUser->isAdmin) && ($oOrder->iOrderClientID != $oUser->userID))
{
    DropWithForbidden();
}

// compile out data
$aResultDataSet = array();
$aResults = array();
//print_r($
//foreach($aOrders as $oOrderRes)
//	{
$oOrderRes = $oOrder;

$aSerialized = $oOrderRes->oSerializedData;

$paymentTypeName = '';

if($oOrderRes->iPaymentTypeID<11)
{
    $paymentTypeName = $oOrderRes->sPaymentTypeName;
}
else
{
    if($oOrderRes->iPaymentTypeID==11)
    {
        $paymentTypeName = "Оплата при отправлении";
    }
    if($oOrderRes->iPaymentTypeID==12)
    {
        $paymentTypeName = "Оплата при получени";
    }
}

$aResults = array(
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

    "cargoVolUnitName" => $oOrderRes->sCargoVolUnitName,
    "cargoWeightUnitName" => $oOrderRes->sCargoWeightUnitName,

    "orderCompanyName" => $oOrderRes->sOrderCargoName,

    "orderComment" => $oOrderRes->sOrderComment,

    "orderRecepientLegalEntity" => $oOrderRes->iOrderRecipientLegalEntity,

    "orderRecepientEmail" => $oOrderRes->sOrderRecipientEmail,
    "orderRecepientPhone" => $oOrderRes->sOrderRecipientPhone,
    "orderRecepientAddress" =>  $oOrderRes->sOrderRecipientAddress,
    "isArrivalWithCourier" =>  $oOrderRes->isArrivalWithCourier,

    "orderRecepientDocumentNumber" => $oOrderRes->sOrderRecipientDocumentNumber,
    "orderRecepientDocumentType" => $oOrderRes->sOrderRecipientDocumentType,
    "orderRecepientFirstName" => $oOrderRes->sOrderRecipientFirstName,
    "orderRecepientSecondName" => $oOrderRes->sOrderRecipientSecondName,
    "orderRecepientLastName" => $oOrderRes->sOrderRecipientLastName,
    "orderRecepientFullName" => $oOrderRes->sOrderRecipientFullName,

    "orderRecepientCompanyName" => $oOrderRes->sOrderRecipientCompanyName,
    "orderRecepientCompanyForm" => $oOrderRes->sOrderRecipientCompanyFormName,
    "orderRecepientCompanyInn" => $oOrderRes->iOrderRecipientCompanyInn,
    "orderRecepientCompanyEmail" => $oOrderRes->sOrderRecipientCompanyEmail,
    "orderRecepientCompanyPhone" => $oOrderRes->sOrderRecipientCompanyPhone,
    "orderRecepientCompanyContactFirstName" => $oOrderRes->sOrderRecipientCompanyContactFirstName,
    "orderRecepientCompanyContactSecondName" => $oOrderRes->sOrderRecipientCompanyContactSecondName,

    "orderSenderLegalEntity" => $oOrderRes->iOrderSenderLegalEntity,
    "isDerivalWithCourier" =>  $oOrderRes->isDerivalWithCourier,

    "orderSenderEmail" => $oOrderRes->sOrderSenderEmail,
    "orderSenderPhone" => $oOrderRes->sOrderSenderPhone,
    "orderSenderAddress" =>  $oOrderRes->sOrderSenderAddress,
    "orderSenderDocumentNumber" => $oOrderRes->sOrderSenderDocumentNumber,
    "orderSenderDocumentType" => $oOrderRes->sOrderSenderDocumentType,
    "orderSenderFirstName" => $oOrderRes->sOrderSenderFirstName,
    "orderSenderSecondName" => $oOrderRes->sOrderSenderSecondName,
    "orderSenderLastName" => $oOrderRes->sOrderSenderLastName,
    "orderSenderFullName" => $oOrderRes->sOrderSenderFullName,

    "orderSenderCompanyName" => $oOrderRes->sOrderSenderCompanyName,
    "orderSenderCompanyForm" => $oOrderRes->sOrderSenderCompanyFormName,
    "orderSenderCompanyInn" => $oOrderRes->iOrderSenderCompanyInn,
    "orderSenderCompanyEmail" => $oOrderRes->sOrderSenderCompanyEmail,
    "orderSenderCompanyPhone" => $oOrderRes->sOrderSenderCompanyPhone,
    "orderSenderCompanyContactFirstName" => $oOrderRes->sOrderSenderCompanyContactFirstName,
    "orderSenderCompanyContactSecondName" => $oOrderRes->sOrderSenderCompanyContactSecondName,

    "orderTemperatureModeName" => $oOrderRes->sOrderTemperatureModeName,
    "orderDangerClassName" => $oOrderRes->sOrderDangerClassName,

    "orderTemperatureModeId" => $oOrderRes->sOrderTemperatureModeId,
    "orderDangerClassId" => $oOrderRes->sOrderDangerClassId,
    "orderGoodsName" => $oOrderRes->sOrderGoodsName,
    "paymentTypeID" =>  $oOrderRes->iPaymentTypeID,
    "paymentTypeName" =>  $paymentTypeName,
    "payerTypeName" =>  $oOrderRes->sPayerTypeName,
    "isOrderPayed"=>  $oOrderRes->iPayed,
    "sOrderPayedName"=>  $oOrderRes->sPayedName,
    "id" => intval($oOrderRes->iOrderID)

);
$aTotal = array_merge($aResults,(array) $aSerialized);

http_response_code(200);
header('Content-Type: application/json');
print(json_encode($aTotal));

?>
