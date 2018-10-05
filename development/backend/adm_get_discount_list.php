<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 08.08.2018
 * Time: 15:20
 */

//require_once ('check_key.php');

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');
include_once ('service/order_class.php');
include_once ('auth.php');
include_once( 'service/discount_class.php' );

$oPOSTData = json_decode(file_get_contents("php://input"));

$limit = intval($oPOSTData->data->limit);
$offset = intval($oPOSTData->data->offset);
$sortCol = $oPOSTData->data->sortCol; //(?)
$activity = strtoupper($oPOSTData->data->activity);
$order = $oPOSTData->data->order;
$keyword = (!isset($oPOSTData->data->keyword) OR $oPOSTData->data->keyword == null OR $oPOSTData->data->keyword == '') ? '' : $oPOSTData->data->keyword;
$discounts = array();
$dis = new Discount();

if($order != 'ASC' AND $order != 'DESC') $order = 'DESC';

if(!isset($activity) OR $activity == null OR $activity == '') $activity = "ALL";

//get total count of discounts
$result['count'] = $dis->GetCount($mysqli, $keyword, false);

$discounts = $dis->GetDiscounts($mysqli, $limit, $offset, $activity, $keyword, $sortCol, $order);

$result['discounts'] = $discounts;
http_response_code(200);
header('Content-Type: application/json');
print(json_encode($result));


?>