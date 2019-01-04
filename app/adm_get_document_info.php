<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 14.06.2018
 * Time: 11:40
 */

//require_once('check_key.php');

$_stat_action = "get_document_info";

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
//Establishing DB connection
$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

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

$documentId = $oPOSTData->data->document_id;

if(isset($documentId)){
	$query = "SELECT * FROM ". DB_DOCUMENTS_TABLE . " WHERE id = " . $documentId;
	$res = $mysqli->query($query);

	if(!$res) DropWithServerError("db error or malformed query");

	while($row = $res->fetch_assoc()) {
		$doc = $row;
	}

	http_response_code(200);
    header('Content-Type: application/json');
	print(json_encode($doc));
}

else DropWithBadRequest("illegal arguments");

?>
