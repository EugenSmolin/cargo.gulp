<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 06.06.2018
 * Time: 17:01
 */

require_once('check_key.php');

$_stat_action = "get_users_list";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');
include_once ('service/order_class.php');

$oPOSTData = json_decode(file_get_contents("php://input"));
include_once ('auth.php');
////Checks for user liability on production only
//if(IS_PRODUCTION) {
//	$iUserID = CheckAuth();
//	if ($iUserID === false)
//		DropWithUnAuth();
//}
//else
//{
//	$iUserID = 15;
//}
//
////Establishing DB connection
//$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);
//
//// check DB connection
//if ($mysqli->connect_errno)
//	DropWithServerError();
//
//// create User object
//$cUser = new User();
//
//// trying to authenticate
//if(IS_PRODUCTION) {
//	$iAuth = $cUser->UserFromID( $mysqli, $iUserID );
//
//// if no user
//	if ( ( $iAuth != USER_OK ) or ( ! $cUser->objectOK ) ) {
//		DropWithUnAuth();
//	}
//}
//// check for id and admin
//if(IS_PRODUCTION)
//{
//	if ((!$cUser->isAdmin) && ($cUser->userID != $oPOSTData->data->id))
//		DropWithForbidden();
//}

$orderData = $oPOSTData->data->order;
//var_dump($orderData); die;
if(!isset($orderData->id)){
	DropWithBadRequest("no order specified");
}
$ord = new Order();
$ord->OrderFromID($mysqli, $orderData->id);
//var_dump($ord);
//order dates
if(isset($orderData->cargoDesiredDate))
$ord->sOrderDesiredDate = date(DATE_MYSQL_FORMAT, $orderData->cargoDesiredDate);

if(isset($orderData->cargoDeliveryDate))
$ord->sOrderDeliveryDate = date(DATE_MYSQL_FORMAT, $orderData->cargoDeliveryDate);

//order comment
if(isset($orderData->orderComment))
$ord->sOrderComment = $orderData->orderComment;

//order safety
if(isset($orderData->orderDangerClassId))
$ord->sOrderDangerClassId = $orderData->orderDangerClassId;

if(isset($orderData->orderTemperatureModeId))
$ord->sOrderTemperatureModeId = $orderData->orderTemperatureModeId;

//order dimensions
if(isset($orderData->cargoWeight))
	$ord->fOrderCargoWeight = $orderData->cargoWeight;
if(isset($orderData->cargoVol))
	$ord->fOrderCargoVol = $orderData->cargoVol;
if(isset($orderData->cargoWidth))
	$ord->fOrderCargoWidth = $orderData->cargoWidth;
if(isset($orderData->cargoHeight))
	$ord->fOrderCargoHeight = $orderData->cargoHeight;
if(isset($orderData->cargoLength))
	$ord->fOrderCargoLength = $orderData->cargoLength;
if(isset($orderData->cargoPrice))
	$ord->fOrderCargoPrice = $orderData->cargoPrice;
if(isset($orderData->cargoValue))
	$ord->fOrderCargoValue = $orderData->cargoValue;
if(isset($orderData->orderStatus))
	$ord->fOrderStatusID = $orderData->orderStatus;
if(isset($orderData->orderCompanyInternalNumber))
	$ord->sCompanyInternalNumber = $orderData->orderCompanyInternalNumber; //v
if(isset($orderData->paymentTypeID))
	$ord->iPaymentTypeID = $orderData->paymentTypeID;
if(isset($orderData->cargoName))
	$ord->sOrderCargoName = $orderData->cargoName;
if(isset($orderData->isOrderPayed))
	$ord->iPayed = $orderData->isOrderPayed;

if(isset($orderData->orderPaid))
	$ord->fOrderCargoPaid = $orderData->orderPaid;
//var_dump($ord); die;
if($ord->UpdateOrder($mysqli)) {
    header('Content-Type: application/json');
	http_response_code(200);
	$success['status'] = "good";
	print(json_encode($success));
}
else DropWithServerError("error saving data to DB")
?>