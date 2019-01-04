<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 13.08.2018
 * Time: 12:34
 */

//require_once ('check_key.php');

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');
include_once ('service/order_class.php');
$oPOSTData = json_decode(file_get_contents("php://input"));
include_once ('auth.php');

//time filters
//start and end dates
$dateStart = date(DATE_MYSQL_FORMAT, intval($oPOSTData->data->dateStart));
$dateEnd = date(DATE_MYSQL_FORMAT, intval($oPOSTData->data->dateEnd));
//"day", "week", "month"
$group =  strtoupper($oPOSTData->data->groupBy);

//main filters
$companies = $oPOSTData->data->filter->companies;
$categories = $oPOSTData->data->filter->categories;
$countries = $oPOSTData->data->filter->countries;
$names = $oPOSTData->data->filter->names;

if(!isset($dateStart) AND !isset($dateEnd)){
	$dateEnd = date("Y-m-d");
	$dateStart = date("Y-m-d", strtotime($dateEnd . ' - 7 days'));
}

if($group != "DAY" AND $group != "WEEK" AND $group != "MONTH") 	$group = "DAY";

$lists = array();

include_once( 'service/discount_class.php' );
$d = new Discount();
$lists = $d->GetAdvancedLists($mysqli);

$result = array();
$result['lists'] = $lists;

$discountData = array();
$discountData['fittingIds'];
//base query
$query = "SELECT id FROM `". DB_USER_DISCOUNTS_TABLE . "` dis ";
$query .= (isset($companies)) ? " INNER JOIN " . DB_USER_DISCOUNT_COMPANIES . " c ON dis.id = c.discount_id " : "";
$query .= (isset($companies)) ? " INNER JOIN " . DB_USER_DISCOUNT_COUNTRIES . " cou ON dis.id = cou.discount_id " : "";
//flag, becomes true on first WHERE clause occasion, so AND can be used afterwards
$flag = false;
//applying company filters
if(isset($companies)){
	if($flag){
		$query .= " AND ";
	}
	else {
		$query .= " WHERE ";
		$flag = true;
	}
	$query .= " ( ";
	foreach($companies as $company) {
		$query .= " c.company_id = " . intval( $company ) . " OR ";
	}
	$query = rtrim($query, " OR ");
	$query .= ") ";
}

//applying category filters
if(isset($categories)){
	if($flag){
		$query .= " AND ";
	}
	else {
		$query .= " WHERE ";
		$flag = true;
	}
	$query .= " ( ";
	$query .= " `cat_id` = " . intval($category);
	$query .= ") ";
}

//applying country filters
if(isset($countries)){
	if($flag){
		$query .= " AND ";
	}
	else {
		$query .= " WHERE ";
		$flag = true;
	}
	$query .= " ( ";
	foreach($countries as $country) {
		$query .= " cou.country_code = '" . $mysqli->real_escape_string($country) . "' OR ";
	}
	$query = rtrim($query, " OR ");
	$query .= ") ";
}

//test query
$test = "SELECT o.*, od.value as od_value FROM " . DB_ORDERS_TABLE . " o INNER JOIN `order_discount` od ON od.order_id  = o.id";

$res = $mysqli->query($query);

//TODO: test values, fix with actual ones!
while($row = $res->fetch_assoc()){
	$discountData['fittingIds'][] = intval($row['id']);
}

$discountData = array();

for($i = 0; $i < 30; $i++){
	$temp = Array();
	$endDate = mktime(23, 59, 59, date("m"), date("d") - $i, date("Y"));
	$temp['date'] = mktime(0, 0, 0, date("m"), date("d") - $i, date("Y"));
	$temp['total'] = 0;
	$query = "SELECT SUM(`cargo_price`) AS full FROM " . DB_ORDERS_TABLE ." WHERE 
timestamp BETWEEN '" . date(DATE_MYSQL_FORMAT, $temp['date']) . "' AND '" . date(DATE_MYSQL_FORMAT, $endDate) . "'";
	$res = $mysqli->query($query);
	if($row = $res->fetch_assoc()){
		$temp['total'] += $row['full'];
	}
	$temp['totalDiscount'] = 0;
	$temp['discounts'] = array();
	$query = "SELECT d.id, d.name as d_name, od.value AS od_value, 
			  o.timestamp as o_date 
			  FROM ". DB_ORDER_DISCOUNT_TABLE . " od 
			  INNER JOIN " . DB_USER_DISCOUNTS_TABLE . " d ON od.discount_id = d.id
			  INNER JOIN " . DB_ORDERS_TABLE . " o ON o.id = od.order_id".
	" WHERE o.timestamp BETWEEN '" . date(DATE_MYSQL_FORMAT, $temp['date']) . "' AND '" . date(DATE_MYSQL_FORMAT, $endDate) . "'";
	$res = $mysqli->query($query);
	while($row = $res->fetch_assoc()){
		$dis = array();
		$dis['name'] = $row['d_name'];
		$dis['value'] = $row['od_value'];
		$temp['totalDiscount'] += floatval($dis['value']);
		$temp['discounts'][] = $dis;
	}
	$discountData[] = $temp;
}

$result['discountData'] = $discountData;

http_response_code(200);
header('Content-Type: application/json');
print(json_encode($result));


?>