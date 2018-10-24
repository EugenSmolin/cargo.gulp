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
//require_once "./service/user_class.php";
//require_once "./service/order_class.php";

// check POST body
//$oPOSTData = json_decode(file_get_contents("php://input"));

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();

// fetch states
$sStatesQuery = "SELECT * FROM `" . DB_STATESLIST_TABLE . "` WHERE 1";
$oStatesAnswer = $mysqli->query($sStatesQuery);

// compile out data

$aResultDataSet = array();

foreach($oStatesAnswer as $oState)
	{
		$aResultDataSet[] = array(
				"id" => $oState["id"],
				"statusName" => $oState["name"]
			);
	}

$aResultOut = array(
			"success" => "success",
			"data" => $aResultDataSet
		);

http_response_code(200);		
header('Content-Type: application/json');
print(json_encode($aResultOut));

?>
