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

$iUserID = CheckAuth();
if ($iUserID === false)
	DropWithUnAuth();
	
// check if auth presence
//if ((!isset($oPOSTData->auth->login)) or (!isset($oPOSTData->auth->password)))
	//{
		//DropWithUnAuth();
	//}
	
if (!isset($oPOSTData->data->orderID))
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
$oOrder= new Order();
$iOrderRes = $oOrder->OrderFromID($mysqli,$oPOSTData->data->orderID);

if ($iOrderRes != PARCEL_OK)
	DropWithNotFound();

if ((!$cUser->isAdmin) and ($oOrder->iOrderClientID != $cUser->userID))
	DropWithNotFound();
	
$oOrder->DeleteOrder($mysqli);

$mysqli->close();

ReturnSuccess();

?>
