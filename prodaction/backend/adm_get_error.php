<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 07.08.2018
 * Time: 12:50
 */

//require_once ('check_key.php');

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');
include_once ('service/order_class.php');

$oPOSTData = json_decode(file_get_contents("php://input"));
$id = intval($oPOSTData->data->msgId);

include_once ('auth.php');
include_once( 'error_msg_helper.php' );

$ids = Array();
$ids[] = $id;
SetRead($mysqli, $ids);

$result = GetMessage($mysqli, $id);
http_response_code(200);
header('Content-Type: application/json');
print(json_encode($result));

?>