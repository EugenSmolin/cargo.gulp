<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 11.09.2018
 * Time: 14:36
 */

// require_once('check_key.php');

$_stat_action = "save_user";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once('service/user_class.php');
header('Content-Type: application/json');

$oPOSTData = json_decode(file_get_contents("php://input"));
include_once ('auth.php');

$id = intval($oPOSTData->data->discountId);
$query = "DELETE FROM " . DB_USER_DISCOUNTS_TABLE . " WHERE `id` = " . $id;
$mysqli->query($query);

http_response_code(200);
$success['status'] = "good";
print(json_encode($success));

?>