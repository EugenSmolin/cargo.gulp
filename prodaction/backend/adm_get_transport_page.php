<?php


if(!$_GET['dev']=='y'){
require_once('check_key.php');

}

$_stat_action = "get_transport_page";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');


$oPOSTData = json_decode(file_get_contents("php://input"));

include_once ('auth.php');

foreach(glob($rundir . "/CALC_*.php") as $modname)
{
	include $modname;
}

if($_GET['dev']=='y'){
$tranId = 32;
} else {
$tranId = $oPOSTData->data->transportId;
}
$tran = $transports[$tranId];
$tran['id'] = $tranId;

$log_con = new mysqlii(DB_HOST, DB_LOG_LOGIN, DB_LOG_PASSWORD, DB_LOG_DB);

$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

$query = "SELECT * FROM " . DB_COMPANY_TABLE . " WHERE `id` = " . intval($tranId);
$res = $mysqli->query($query);
$row = $res->fetch_assoc();

$tran['name'] = $row['name'];
$tran['usePrefix'] = boolval($row['use_prefix']);
$tran['applicationPrefix'] = $row['application_prefix'];


//activity return
$query = "SELECT `is_active` FROM `" . DB_COMPANY_ACTIVITY . "` WHERE `company_id` = ". $tranId;
$res = $mysqli->query($query);
if($res->num_rows == 1){
	while($row = $res->fetch_assoc()) {
		$tran['canorder'] = ($row['is_active'] == 1) ? true : false;
	}
}

$query = "SELECT MAX(datetime) AS date FROM `".DB_WEB_LOG."` WHERE compId = " . $tranId ;

$res= $log_con->query($query);

while($row = $res->fetch_assoc()) {
	$date = $row['date'];
	//var_dump($row);
}
$tran['last_access_date'] = strtotime($date);
//phone, email and country
$query = "SELECT `country`, `phones`, `email`,`icon_url`, `site` FROM `". DB_COMPANY_TABLE . "` WHERE `id` = " . $tranId;
$res = $mysqli->query($query);
if($res->num_rows == 1)
{
    $row = $res->fetch_assoc();
    $tran['country'] = (isset($row['country'])) ? $row['country'] : "";
    $tran['email'] = (isset($row['email'])) ? $row['email'] : "";
    $tran['phones'] = (isset($row['phones'])) ? $row['phones'] : "";
    $tran['logo'] = (isset($row['icon_url'])) ? $row['icon_url'] : "";
    $tran['site'] = (isset($row['site'])) ? $row['site'] : $tran['site'];
}
$query = "SELECT `id`, `name` as description FROM `". DB_COMPANY_DOCUMENT_TYPE_TABLE ."` ORDER BY `id`" ;
$res = $mysqli->query($query);

$i = 0;
while($row[$i] = $res->fetch_assoc()) {
	$tran['lists']['document_types'][$i] = $row[$i];
	$tran['lists']['document_types'][$i]['id'] = intval($tran['lists']['document_types'][$i]['id']);
        $i++;
} 

$query = "SELECT `id`, `name` as description FROM `".DB_PAYMENT_TYPE."`  WHERE `id`>0 ORDER BY `id`" ;
$res = $mysqli->query($query);

$i = 0;
while($row[$i] = $res->fetch_assoc()) {
	$tran['lists']['payment_types'][$i] = $row[$i];
	$tran['lists']['payment_types'][$i]['id'] = intval($tran['lists']['payment_types'][$i]['id']);
        $i++;
} 

$query = "SELECT `id`, `name` as description FROM `".DB_PAYER_TYPE."` ORDER BY `id`" ;
$res = $mysqli->query($query);

$i = 0;
while($row[$i] = $res->fetch_assoc()) {
	$tran['lists']['payer_types'][$i] = $row[$i];
	$tran['lists']['payer_types'][$i]['id'] = intval($tran['lists']['payer_types'][$i]['id']);
        $i++;
} 

$query = "SELECT `id`, `name` as description, profit_coefficient, is_active as activity FROM `".DB_COMPANY_TARIFF_TABLE."` WHERE company_id =". $tranId ." ORDER BY `id`" ;
$res = $mysqli->query($query);

$i = 0;
while($row[$i] = $res->fetch_assoc()) {
	$tran['lists']['tariff_types'][$i] = $row[$i];
	$tran['lists']['tariff_types'][$i]['id'] = intval($tran['lists']['tariff_types'][$i]['id']);
	$tran['lists']['profit_coefficient'][$i]['id'] = floatval($tran['lists']['tariff_types'][$i]['profit_coefficient']);
	$tran['lists']['activity'][$i]['id'] = intval($tran['lists']['tariff_types'][$i]['activity']);
        $i++;
} 

$query = "SELECT document_type_id FROM `".DB_COMPANY_DOCUMENT_TABLE."` WHERE company_id = " . $tranId;
$res = $mysqli->query($query);

while($row = $res->fetch_assoc()) {   
	$document_activity[] = intval($row['document_type_id']);
}
if(!isset($document_activity) OR $document_activity == null OR $document_activity == '') $document_activity = array();

$tran['selected_options']['document_activity'] = $document_activity;

$query = "SELECT payer_id, payment_type_id as payment_id, discount_value FROM `".DB_PAYMENT_TYPE_DISCOUNT."` WHERE company_id =". $tranId ." ORDER BY payer_id";
$res = $mysqli->query($query);

$i = 0;
while($row[$i] = $res->fetch_assoc()) {
	$payment_type_discount[] = $row[$i];
	$payment_type_discount[$i]['payer_id'] = intval($payment_type_discount[$i]['payer_id']);
	$payment_type_discount[$i]['payment_id'] = intval($payment_type_discount[$i]['payment_id']);
	$payment_type_discount[$i]['discount_value'] = floatval($payment_type_discount[$i]['discount_value']);
	$i++;
}

$query = "SELECT `iso` AS `id`, `name_rus` AS `name` FROM `" . DB_COUNTRY_TABLE . "`;";
$res = $mysqli->query($query);
$tran['lists']['countries'] = array();
//var_dump($res,$mysqli, $query); die;
while($row = $res->fetch_assoc()) {
	$temp = array();
	$temp['data'] = $row['id'];
	$temp['value'] = $row['name'];
	$tran['lists']['countries'][] = $temp;
}

if(!isset($payment_type_discount) OR $payment_type_discount == null OR $payment_type_discount == '') $payment_type_discount = array();

$tran['selected_options']['payer_payment_discount_activity'] = $payment_type_discount;

http_response_code(200);
header('Content-Type: application/json');
print(json_encode($tran));

?>
