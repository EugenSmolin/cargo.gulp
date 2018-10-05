<?php

/*
 * @author Anton Dovgan <blackc.blackc@gmail.com>
 * 
 * @param string	JSON in POST body with parameters
 * 
 * @return string	JSON with results
 * 
 */

//require_once('check_key.php');

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/user_class.php";
require_once "./service/auth.php";
require_once "./service/finance_class.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));
//var_dump($oPOSTData);//
$iUserID = CheckAuth();
if(IS_PRODUCTION) {
	if ( $iUserID === false ) {
		DropWithUnAuth();
	}
}
// check if data enough
if (
	//!isset($oPOSTData->data->vkID) and 
	!isset($oPOSTData->data->defaultEmail) 
	and !isset($oPOSTData->data->defaultPhone)
	// and !isset($oPOSTData->data->passportNum)
	)
		{
			DropWithBadRequest("Not enough parameters");
		}

// check if auth presence
//if ((!isset($oPOSTData->auth->login)) and (!isset($oPOSTData->auth->password)))
	//DropWithUnAuth();

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();

// create User object
$cUser = new User();

// trying to authenticate
$iAuth = $cUser->UserFromID($mysqli, $iUserID);
if(IS_PRODUCTION) {
	if ( ( $iAuth != USER_OK ) or ( ! $cUser->objectOK ) or ( ! $cUser->isAdmin ) ) {
		DropWithUnAuth();
	}
}
$iTotalCount = $cUser->UsersCountFromSearch($mysqli,$oPOSTData->data->defaultPhone, $oPOSTData->data->defaultEmail
								//, $oPOSTData->data->vkID,
								//$oPOSTData->data->passportNum
								);

$aUsers = $cUser->UsersFromSearch($mysqli,$oPOSTData->data->defaultPhone, $oPOSTData->data->defaultEmail
								//, $oPOSTData->data->vkID,
								//$oPOSTData->data->passportNum
								, $oPOSTData->data->limit, $oPOSTData->data->offset);

// compile out data

$aResultDataSet = array();

foreach($aUsers as $oUser)
	{
		$oFinance = new Finance();
		$fBalance = $oFinance->UserBalance($mysqli, $oUser->userID);
		
		$aResultDataSet[] = array(
				"id" => $oUser->userID,
				"formerlyName" => $oUser->userName,
				"defaultAddress" => $oUser->userAddress,
            	"defaultAddressCell" => $oUser->userAddressCell,
				"defaultEmail" => $oUser->userEMail,
				"defaultPhone" => $oUser->userPhone,
				"balance" => $fBalance,
				"isAdmin" => $oUser->isAdmin
			);
	}

$aResultOut = array(
			"success" => "success",
			"totalCount" => intval($iTotalCount),
			"data" => $aResultDataSet
		);

http_response_code(200);		
header('Content-Type: application/json');
print(json_encode($aResultOut));

?>
