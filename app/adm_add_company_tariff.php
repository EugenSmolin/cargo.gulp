<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 31.07.2018
 * Time: 16:10
 */

$_stat_action = "save_transport";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');
include_once ('service/order_class.php');


$oPOSTData = json_decode(file_get_contents("php://input"));
include_once ('auth.php');

$tranId = intval($oPOSTData->data->companyId);
$newTariffs = $oPOSTData->data->newTariffs;
//var_dump($newTariffs); die;
//inserting new tariffs
if(isset($newTariffs) AND $newTariffs != '' AND $newTariffs != null){
	//var_dump($newTariffs); die;
	foreach($newTariffs as $newTariff) {
		$query  = "INSERT INTO " . DB_COMPANY_TARIFF_TABLE . " (`company_id`, `name`, `profit_coefficient`, `is_active`) VALUES ";
		$active = ( $newTariff->isActive ) ? 1 : 0;
		$query  .= "(" . $tranId . ", '" . $newTariff->name . "', " . $newTariff->profit . ", " . $active . "), ";
		$query  = rtrim( $query, ", " );
		$query  .= ";";
		$mysqli->query( $query );
	}
}
else DropWithServerError("not enough parameters supplied");

http_response_code(200);
header('Content-Type: application/json');
$success['status'] = "good";
print(json_encode($success));
