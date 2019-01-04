<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 07.08.2018
 * Time: 15:07
 */

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');
include_once ('service/order_class.php');
include_once( 'service/discount_class.php' );
$oPOSTData = json_decode(file_get_contents("php://input"));
include_once ('auth.php');

$discountId = intval($oPOSTData->data->discountId);

$discount = array();
$dis = new Discount();
$discount = $dis->GetSingleDiscount($mysqli, $discountId);
$end['discount'] = $discount;

//lists for dropdowns
$lists = $dis->GetListsForDiscount($mysqli);

$end['lists'] = $lists;
http_response_code(200);
header('Content-Type: application/json');
print(json_encode($end));

?>