<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 21.08.2018
 * Time: 12:34
 */

//require_once ('check_key.php');

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');
include_once ('service/order_class.php');
include_once ('auth.php');
include_once( 'service/discount_class.php' );

$oPOSTData = json_decode(file_get_contents("php://input"));

$isActive = boolval($oPOSTData->data->isActive);
$discountId = intval($oPOSTData->data->discountId);
$dis = new Discount();
$dis->ChangeStatus($mysqli, $isActive, $discountId);

http_response_code(200);
header('Content-Type: application/json');
$success['status'] = "good";
print(json_encode($success));

?>