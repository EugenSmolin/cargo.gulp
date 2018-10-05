<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 07.08.2018
 * Time: 15:07
 */

//require_once ('check_key.php');

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');
include_once ('service/order_class.php');
$oPOSTData = json_decode(file_get_contents("php://input"));
include_once ('auth.php');
require_once ('service/discount_class.php');

//Parameters
//main info
$id = (isset($oPOSTData->data->discount->id) AND
    intval($oPOSTData->data->discount->id) > 0) ? intval($oPOSTData->data->discount->id) : null;
$name = $oPOSTData->data->discount->discountName;
$value = floatval($oPOSTData->data->discount->value);
$category = intval($oPOSTData->data->discount->discountCategory);
$dateStart = $oPOSTData->data->discount->dateStart;

$isForever = boolval($oPOSTData->data->discount->isForever);
$dateEnd = (isset($oPOSTData->data->discount->dateEnd)) ? $oPOSTData->data->discount->dateEnd : MAX_TIMESTAMP;
$dateEnd = ($isForever) ? MAX_TIMESTAMP : $oPOSTData->data->discount->dateEnd;

$usePromo = boolval($oPOSTData->data->discount->usePromo);
$promo = ($usePromo) ? $oPOSTData->data->discount->promo : "";
$isActive = boolval($oPOSTData->data->discount->isActive);
$userId = intval($oPOSTData->data->discount->userId);

if(isset($userId) AND $userId != null AND $userId != '') $category = 3;


$dis = new Discount();
$dis->id = $id;
$dis->name = $name;
$dis->value = $value;
$dis->category = $category;
$dis->dateStart = $dateStart;
$dis->dateEnd = $dateEnd;
$dis->usePromo = $usePromo;
$dis->promo = $promo;
$dis->isForever = $isForever;
$dis->isActive = $isActive;

if(!isset($userId))
$dis->SaveDiscount($mysqli, $oPOSTData->data->discount->countries, $oPOSTData->data->discount->companies);
else $dis->SaveDiscount($mysqli,
    $oPOSTData->data->discount->countries,
    $oPOSTData->data->discount->companies,
    intval($userId));

http_response_code(200);
header('Content-Type: application/json');
$success['status'] = "good";
print(json_encode($success));
?>