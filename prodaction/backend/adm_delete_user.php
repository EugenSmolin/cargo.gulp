<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 19.07.2018
 * Time: 10:57
 */

//require_once ('check_key.php');

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');
include_once ('service/order_class.php');

$oPOSTData = json_decode(file_get_contents("php://input"));
include_once ('auth.php');

$id = $oPOSTData->data->userId;
$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

$delUsr = new User();
$delUsr->UserFromID($mysqli, $id);

if($delUsr->DeleteUser($mysqli) == USER_OK) {
	http_response_code( 200 );
	header( 'Content-Type: application/json' );
	$success['status'] = "good";
	print( json_encode( $success ) );
}
else DropWithServerError();

?>