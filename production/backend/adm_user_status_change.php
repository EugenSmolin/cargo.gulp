<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 06.06.2018
 * Time: 13:52
 */

//require_once('check_key.php');

$_stat_action = "set_user_status";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');


$oPOSTData = json_decode(file_get_contents("php://input"));

//Checks for user liability on production only
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
include_once ('auth.php');

if(!isset($oPOSTData->data->mode) or !isset($oPOSTData->data->val) or !isset($oPOSTData->data->userId)) {
	DropWithBadRequest("not enough arguments supplied");
}

if($oPOSTData->data->val != 0 and $oPOSTData->data->val != 1) DropWithBadRequest("illegal value");

$mode = $oPOSTData->data->mode;
$val = $oPOSTData->data->val;
$userId = $oPOSTData->data->userId;

if($cUser->SetJurOrApproved($mysqli, $userId, $val, $mode)) {
	http_response_code(200);
    header('Content-Type: application/json');
	$success['status'] = "good";
	print(json_encode($success));
}
else DropWithServerError();

?>