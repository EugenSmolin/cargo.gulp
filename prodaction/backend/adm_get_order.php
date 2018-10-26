<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 06.06.2018
 * Time: 16:14
 */

//require_once('check_key.php');

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/user_class.php";
require_once "./adm_auth.php";
require_once "./service/order_class.php";
require_once "services.php";


// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

//$iUserID =15;
/*
if(IS_PRODUCTION) {
	$iUserID = CheckAuth();
	if ($iUserID === false)
		DropWithUnAuth();
}
ELSE
{
	$iUserID =15;
}
*/
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

//var_dump($iOrderID); die;

// check auth and rights
// create User object
$oUser = new User();

// trying to authenticate
$iAuth = $oUser->UserFromID($mysqli, $iUserID);

//if (($iAuth != USER_OK) or (!$oUser->objectOK))
//	DropWithUnAuth();

//var_dump($iOrderID); die;


$oOrder = new Order();
$oOrder->OrderFromID($mysqli, $iOrderID);
//var_dump($oOrder); die;

if(!IS_PRODUCTION)
{
	//echo $aOrder;
}

if ($aOrder != PARCEL_OK)
	DropWithNotFound();
/*
// if not admin
if ((!$oUser->isAdmin) && ($oOrder->iOrderClientID != $oUser->userID))
{
	DropWithForbidden();
}
*/
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

//var_dump($oOrderRes); die;

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

	"orderOrigPrice" => floatval($oOrderRes->fOrderCargoOrigPrice),
	"orderPaid" => floatval($oOrderRes->fOrderCargoPaid),

	"cargoDesiredDate" => strtotime($oOrderRes->sOrderDesiredDate),
	"cargoDeliveryDate" => strtotime($oOrderRes->sOrderDeliveryDate),

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
	//"sOrderPayedName"=>  $oOrderRes->sPayedName,
	"id" => intval($oOrderRes->iOrderID),
	"orderStatus" =>($oOrderRes->fOrderStatusID),
	"orderCompanyInternalNumber" => $oOrderRes->sCompanyInternalNumber

);

$res['orderInfo'] = $aResults;
$res['currencies'] = $activeCurrencies;
$res['lists']['payments'] = Array();
$temp['id'] = 0;
$temp['name'] = "Не оплачен";
$res['lists']['payments'][] = $temp;

$temp['id'] = 1;
$temp['name'] = "Оплачен";
$res['lists']['payments'][] = $temp;

//$temp['id'] = 2;
//$temp['name'] = "Оплата перевозчику";
//$res['lists']['payments'][] = $temp;


$temp = $mysqli->query('SELECT * FROM `'.DB_ORDER_STATUS_TABLE.'`');
$states = array();
while($row = $temp->fetch_assoc()) {
	$state[$row['id']] = $row['name'];
	$states += $state;
}

$temp = $mysqli->query('SELECT * FROM `'.DB_PAYMENT_TYPE.'`');
$payTypes = array();
while($row = $temp->fetch_assoc()) {
	$payType[$row['id']] = $row['name'];
	$payTypes += $payType;
}

$temp = $mysqli->query('SELECT * FROM `'.DB_TEMPERATURE_MODE_TABLE.'`');
$temters = array();
while($row = $temp->fetch_assoc()) {
	$mode[$row['id']] = $row['name'];
    $temters += $mode;
}

$temp = $mysqli->query('SELECT * FROM  `'.DB_DANGER_CLASS_TABLE.'`');
$dangers = array();
while($row = $temp->fetch_assoc()) {
	$danger[$row['id']] = $row['name'];
	$dangers += $danger;
}

//статусы
$temp = $mysqli->query('SELECT * FROM `'.DB_STATESLIST_TABLE.'`');
$stats = array();
while($row = $temp->fetch_assoc()) {
	$stat[$row['id']] = $row['name'];
	$stats += $stat;
}

$res['orderStates'] = $states;
$res['payTypes'] = $payTypes;
$res['temperatureModes'] = $temters;
$res['dangerClasses'] = $dangers;

http_response_code(200);
header('Content-Type: application/json');
print(json_encode($res));
?>