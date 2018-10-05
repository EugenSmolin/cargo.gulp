<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 06.08.2018
 * Time: 15:53
 */

//require_once ('check_key.php');

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');
include_once ('service/order_class.php');

$oPOSTData = json_decode(file_get_contents("php://input"));
$limit = intval($oPOSTData->data->limit);
$offset = intval($oPOSTData->data->offset);


include_once ('auth.php');

include_once( 'error_msg_helper.php' );
$result = Array();
$result = GetErrorList($mysqli, $limit, $offset);

http_response_code(200);
header('Content-Type: application/json');
print(json_encode($result));

?>