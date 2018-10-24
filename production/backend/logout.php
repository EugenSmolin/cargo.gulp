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

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

if (!CheckAuth())
	DropWithUnAuth();

$sSessionString = $_SERVER['HTTP_SESSION_STRING'];
$iIP = ip2long($_SERVER['REMOTE_ADDR']);

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();

// delete session

$sSessionDelReq = "DELETE FROM `" . DB_SESSIONS_TABLE . "` WHERE `session_id` = \"" . $sSessionString . "\" AND `addr` = " . $iIP;
$oRes = $mysqli->query($sSessionDelReq);

//print($sSessionDelReq);
//print($mysqli->error);
if ($oRes->affected_rows < 0)
	DropWithUnAuth();

$aResultOut = array(
			"success" => "success"
		);

http_response_code(200);		
header('Content-Type: application/json');
print(json_encode($aResultOut));

?>
