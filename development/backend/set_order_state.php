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
///	{
//		DropWithUnAuth();
//	}

// check if data enough
if (!isset($oPOSTData->data->orderID) or !isset($oPOSTData->modifies->stateNumber))
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

// check if user is admin
if (!$cUser->isAdmin)
	DropWithServerError();
		
// ok, set operation owner
$iOperationOwner = 0;

$iOrderID = intval($oPOSTData->data->orderID);
$iStateNumber = intval($oPOSTData->modifies->stateNumber);
$sStateComment = $mysqli->real_escape_string($oPOSTData->modifies->comment);
	
// check if parcel and courier exists and we are parcel's courier
$oOrder = new Order();
$iOrderRes = $oOrder->OrderFromID($mysqli,$iOrderID);

if ($iOrderRes != PARCEL_OK)
	DropWithNotFound();	

$sNewStateQuery = "INSERT INTO `" . DB_STATES_TABLE . "` (`order_id`, `operation_id`, `comment`) VALUES (" . $iOrderID . ", " .
					$iStateNumber . ", \"" . $sStateComment . "\")";
$iNewStateRes = $mysqli->query($sNewStateQuery);

if ($mysqli->affected_rows < 0)
	DropWithServerError("DB error " . $mysqli->error);

$mysqli->close();

ReturnSuccess();

?>
