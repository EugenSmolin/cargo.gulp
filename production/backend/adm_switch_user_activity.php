<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 01.08.2018
 * Time: 14:16
 */

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once('service/user_class.php');
header('Content-Type: application/json');

$oPOSTData = json_decode(file_get_contents("php://input"));
include_once ('auth.php');

$userId = intval($oPOSTData->data->userId);
$isActive = boolval($oPOSTData->data->isActive);

$activeInt = ($isActive) ? 1 : 0;
$numlocks = 1;

if(!$isActive) {
	$query = "UPDATE " . DB_USERS_TABLE . " SET `approved` = " . $activeInt . ", `email` = CONCAT(`email`, '_" . $numlocks . "') WHERE `id` = " . $userId;
}
else{
	$query = "SELECT `email` FROM ". DB_USERS_TABLE ." WHERE `id` = ".$userId;
	$res = $mysqli->query($query);
	$row = $res->fetch_assoc();
	$email = $row['email'];
	$domain = preg_match('/@{1}.+/', $email);
	$email = preg_replace('/@{1}.+/', '', $email);
	//var_dump($domain); die;
	$domain = preg_replace('/_{1}\d+/', '', $domain);
	//var_dump($email); die;
	$email .= $domain;
	$query = "UPDATE " . DB_USERS_TABLE . " SET `is_approved` = " . $activeInt . ", `email` = " . $email . " WHERE `id` = " . $userId;
}
$res   = $mysqli->query( $query );
if ($res->affected_rows == 0) {
	DropWithServerError( "malformed query" );
}
?>