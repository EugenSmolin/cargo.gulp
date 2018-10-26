<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 05.06.2018
 * Time: 15:07
 */

//require_once('check_key.php');

$_stat_action = "get_users_list";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');


$oPOSTData = json_decode(file_get_contents("php://input"));
/*
//Checks for user liability on production only
if(IS_PRODUCTION) {
	$iUserID = CheckAuth();
	if ($iUserID === false)
		DropWithUnAuth();
}
else
{
	$iUserID = 15;
}
*/
//keyword to search
$kw = $oPOSTData->data->q;
//limit of results
$limit = $oPOSTData->data->limit;
//offset of results
$offset = $oPOSTData->data->offset;
//column to sort
$sort_col = $oPOSTData->data->sort_col;
//order
$order = $oPOSTData->data->order;



//user class to call GetUsers method
//$fUser = new User();

//Establishing DB connection
$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);
//var_dump($mysqli); die;
// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();

// create User object
$cUser = new User();
/*
// trying to authenticate
if(IS_PRODUCTION) {
	$iAuth = $cUser->UserFromID( $mysqli, $iUserID );

// if no user
	if ( ( $iAuth != USER_OK ) or ( ! $cUser->objectOK ) ) {
		DropWithUnAuth();
	}
}
// check for id and admin
if(IS_PRODUCTION)
{
	if ((!$cUser->isAdmin) && ($cUser->userID != $oPOSTData->data->id))
		DropWithForbidden();
}
*/
//Checks and defaults:
if(!isset($sort_col) or $sort_col == ''){
	$sort_col = 'last_name';
}
if(!isset($order) or $order == ''){
	$order = 'ASC';
}
if($order != 'ASC' and $order != 'DESC'){
	DropWithBadRequest("illegal order specified");
}

if($sort_col != 'last_name' and
	$sort_col != 'email' and
	$sort_col != 'last_login' and
	$sort_col != 'admin' and
	$sort_col != 'approved'){
	DropWithBadRequest("illegal sorting specified");
}

//get users that satisfy given search keyword
$users = $cUser->GetUsers($mysqli, $kw, $limit, $offset, $sort_col, $order);
$satisfactoryUsers = $users['users'];
//var_dump($satisfactoryUsers);
//forming array of objects for being sent to frontend
if(isset($satisfactoryUsers) && $satisfactoryUsers != '' && $satisfactoryUsers != null) {
	foreach ( $satisfactoryUsers as $user ) {
		$temp['id'] = $user->userID;
		if ( ! $user->userIsJur ) {
			//$temp['name'] = $user->userLastName . ' ' . $user->userName . ' ' . $user->userSecondName;
			$temp['first_name']  = $user->userName;
			$temp['last_name']   = $user->userLastName;
			$temp['second_name'] = $user->userSecondName;
		} else {
			$temp['legal_name'] = $user->userJurName;
		}
		$temp['isLegal'] = $user->userIsJur;
		$temp['role']    = $user->isAdmin ? "admin" : "user";
		$temp['email']   = $user->userEMail;
		//$temp['isAdmin'] = $user->isAdmin;
		$temp['lastLogin']  = (!strtotime($user->lastLoginDateTime)) ? 0 : strtotime($user->lastLoginDateTime);
		$temp['isApproved'] = $user->isApproved;
		$finalUserList[]    = $temp;
	}
}
//var_dump($users['count']);
$res['users'] = (isset($finalUserList) && $finalUserList != '' && $finalUserList != null) ? $finalUserList : array();
$res['count'] = $users['count'];
//var_dump($res['count']);
//setting response code to OK

http_response_code(200);
header('Content-Type: application/json');
//returning JSON
print(json_encode($res));
?>