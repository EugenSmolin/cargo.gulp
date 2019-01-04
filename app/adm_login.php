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
require_once "./service/finance_class.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

//trigger_error('!!! ololo');
// check if auth presence
$log = $oPOSTData->auth->login;
if ((!isset($oPOSTData->auth->login)) or (!isset($oPOSTData->auth->password)))
	DropWithUnAuth();

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();
// create User object
$cUser = new User();

// trying to authenticate
$iAuth = $cUser->UserFromAuth($mysqli, $oPOSTData->auth->login, $oPOSTData->auth->password);
//trigger_error("here");
if (($iAuth != USER_OK) or (!$cUser->objectOK))
	DropWithUnAuth();

$sSessionString = random_str(250);
$iIP = ip2long($_SERVER['REMOTE_ADDR']);

// create session

$sSessionReq = "INSERT INTO `" . DB_SESSIONS_TABLE . "` (`session_id`,`addr`, `uid`) VALUES (\"" . $sSessionString . "\", " . $iIP . ", " .
               intval($cUser->userID) . ")";
$oRes = $mysqli->query($sSessionReq);
//trigger_error($mysqli->error);
if ($oRes->affected_rows < 0)
	DropWithUnAuth();

// compile out data

$aResultDataSet = array();

$oFinance = new Finance();
$fBalance = $oFinance->UserBalance($mysqli, $cUser->userID);

$aResultDataSet = array(
	"id" => $cUser->userID,
	"formerlyName" => $cUser->userName,
	"defaultAddress" => $cUser->userAddress,
	"defaultEmail" => $cUser->userEMail,
	"defaultPhone" => $cUser->userPhone,
	"balance" => $fBalance,
	"isAdmin" => $cUser->isAdmin,
	"sessionString" => $sSessionString
);

$aResultOut = array(
	"success" => "success",
	"data" => $aResultDataSet
);

setcookie('admin_session', $sSessionString, time() + 3600, "/");
setcookie('user-name', $cUser->userName, time() + 3600, "/");
setcookie('user-id', $cUser->userID, time() + 3600, "/");

http_response_code(200);
header('Content-Type: application/json');
print(json_encode($aResultOut));

?>
