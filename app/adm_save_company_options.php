<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 13.06.2018
 * Time: 10:48
 */

//require_once('check_key.php');

$_stat_action = "save_company_options";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');


$oPOSTData = json_decode(file_get_contents("php://input"));
include_once ('auth.php');

///*
////Checks for user liability on production only
//if(IS_PRODUCTION) {
//	$iUserID = CheckAuth();
//	if ($iUserID === false)
//		DropWithUnAuth();
//}
//else
//{
//	$iUserID = 15;
//}
//*/
////Establishing DB connection
//$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);
//
//// check DB connection
//if ($mysqli->connect_errno)
//	DropWithServerError();
//
//// create User object
//$cUser = new User();
///*
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
//*/
$companyOptions = $oPOSTData->data->options;
$company = $oPOSTData->data->company_id;

$query = "UPDATE `company_options` SET ";
foreach ( $companyOptions as $key => $value ) {
	//var_dump($key);
	if($key != 'id' and $key != 'company_id') {
		$query .= $key . " = " . $value . ", ";
	}
}

$query = rtrim($query, ', ');
$query .= " WHERE company_id = " . $company;

//echo $query;

if($mysqli->query($query)){
    http_response_code(200);
    header('Content-Type: application/json');
    $success['status'] = "good";
    print(json_encode($success));
}
else DropWithServerError("database error or malformed query");
?>
