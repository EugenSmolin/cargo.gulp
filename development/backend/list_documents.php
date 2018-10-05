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

// check auth
$iUserID = CheckAuth();
if ($iUserID === false)
	DropWithUnAuth();

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithUnAuth();

// request

$sDocsRequest = "SELECT *, UNIX_TIMESTAMP(timestamp) AS u_time FROM `" . DB_DOCUMENTS_TABLE . "` WHERE 1";
$oDocsAnswer = $mysqli->query($sDocsRequest);

if ($mysqli->error)
	DropWithServerError();

$aResultDataSet = array();

while($aRow = $oDocsAnswer->fetch_assoc())
	{
		$aResultDataSet[] = array(
				"id" => intval($aRow["id"]),
				"timestamp" => intval($aRow["u_time"]),
				"name" => $aRow["name"],
				"companyID" => intval($aRow["companyId"]),
				"content" => $aRow["content"]
			);
	}

// compile out data

$aResultOut = array(
			"success" => "success",
			//"totalCount" => intval($iTotalCount),
			"data" => $aResultDataSet
		);

http_response_code(200);		
header('Content-Type: application/json');
print(json_encode($aResultOut));

?>
