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
require_once "./service/score_class.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

$iUserID = CheckAuth();
if ($iUserID === false)
	DropWithUnAuth();
	
// check if data enough
if (!isset($oPOSTData->modifies->companyID) or !isset($oPOSTData->modifies->score))
		{
			DropWithBadRequest("Not enough parameters");
		}

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();

// create Score object
$oScore = new Score();

$iResult = $oScore->NewScoreFromParameters($mysqli,$iUserID,$oPOSTData->modifies->score,
			$oPOSTData->modifies->comment,$oPOSTData->modifies->companyID);

if ($iResult == USER_OK)
	{
		$aResultOut = array(
					"success" => "success"
				);
	}
else
	DropWithServerError();
	
http_response_code(200);		
header('Content-Type: application/json');
print(json_encode($aResultOut));

?>
