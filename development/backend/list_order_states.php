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

// check if auth and new pass presence
//if ((!isset($oPOSTData->auth->login)) or (!isset($oPOSTData->auth->password)))
//	{
//		DropWithUnAuth();
//	}

// check if data enough
if (!isset($oPOSTData->data->orderID))
		{
			DropWithBadRequest("Not enough eparameters");
		}
	
// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError("DB error");

// create User object
$cUser = new User();

// trying to authenticate
$iAuth = $cUser->UserFromID($mysqli, $iUserID);

if (($iAuth != USER_OK) or (!$cUser->objectOK))
	{
		$mysqli->close();
		DropWithUnAuth();
	}
	
$iOrderID = intval($oPOSTData->data->orderID);

// check if parcel and courier exists and we are parcel's courier
$oOrder = new Order();
$iOrderRes = $oOrder->OrderFromID($mysqli,$iOrderID);

if ($iOrderRes != PARCEL_OK)
	DropWithNotFound();	

// check if user is admin
if (!$cUser->isAdmin)
	if ($oOrder->iOrderClientID != $cUser->userID)
		DropWithForbidden();
		

$sNewStateQuery = "SELECT *, UNIX_TIMESTAMP(timestamp) as u_tm FROM `" . DB_EVENT_TABLE . "` WHERE `order_id` = " . $iOrderID;
$iNewStateRes = $mysqli->query($sNewStateQuery);

if ($mysqli->error)
	DropWithServerError("DB error");

$mysqli->close();

$oResultDataSet = array();

while($oRow = $iNewStateRes->fetch_assoc())
	{
		$oTemp = array(
				"id" => $oRow["id"],
				"timestamp" => $oRow["u_tm"],
				"state_id" => $oRow["operation_id"],
				"comment" => $oRow["comment"]
			);
		$oResultDataSet[] = $oTemp;
	}

ReturnSuccess(array("data" => $oResultDataSet));

?>
