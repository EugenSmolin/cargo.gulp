<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 07.06.2018
 * Time: 14:13
 */

//require_once('check_key.php');

$_stat_action = "get_users_list";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');
include_once ('service/order_class.php');


$oPOSTData = json_decode(file_get_contents("php://input"));
/*
//Checks for user liability on production only
if(IS_PRODUCTION) {
	$iUserID = CheckAuth();
	if ($iUserID === false)
		DropWithUnAuth();
}
else
{
	$iUserID = 15;
}
*/
//Establishing DB connection
$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();

// create User object
$cUser = new User();
/*
// trying to authenticate
if(IS_PRODUCTION) {
	$iAuth = $cUser->UserFromID( $mysqli, $iUserID );

// if no user
	if ( ( $iAuth != USER_OK ) or ( ! $cUser->objectOK ) ) {
		DropWithUnAuth();
	}
}

// check for id and admin
if(IS_PRODUCTION)
{
	if ((!$cUser->isAdmin) && ($cUser->userID != $oPOSTData->data->id))
		DropWithForbidden();
}
*/
//search keyword
$keyword = $oPOSTData->data->keyword;
//pagination offset
$offset = $oPOSTData->data->offset;
//pagination limit
$limit = $oPOSTData->data->limit;
//order, ascending or descending
$order = $oPOSTData->data->order;
//column by which to sort
$sort_col = $oPOSTData->data->sort_col;
//start date of sorting
//end date of sorting

$singleUserid = $oPOSTData->data->singleUserId;


if(!isset($oPOSTData->data->startDate)
   or !isset($oPOSTData->data->endDate)
   or $oPOSTData->data->startDate == ''
   or $oPOSTData->data->endDate == ''
   or $oPOSTData->data->startDate == 0 or $oPOSTData->data->endDate == 0){
	$endDate ='';
	$startDate = '';
}
else {
	$endDate = date(DATE_MYSQL_FORMAT, $oPOSTData->data->endDate);
	$startDate = date(DATE_MYSQL_FORMAT, $oPOSTData->data->startDate);

}
//print($order);
$tr = array();

//var_dump($transports); die;

//foreach($transports as $transportNumber => $transport)
//{
//	$tr['id'] = $transportNumber;
//	$tr['name'] = $transport['name'];
//}
if(!isset($order) or $order == '') {
	$order = 'DESC';
}

if($order != 'ASC' and $order != 'DESC') DropWithBadRequest("illegal order specified");

if(!isset($sort_col) or $sort_col == '') {
	$sort_col = 'timestamp';
}

$ord = new Order();

$ords = $ord->SearchOrders($mysqli, $limit, $offset, $order, $sort_col, $keyword, $startDate, $endDate, $singleUserid);

$query = 'SELECT * FROM `'.DB_ORDER_STATUS_TABLE.'`';

$temp = $mysqli->query($query);
$states = array();
//var_dump($query); die;

while($row = $temp->fetch_assoc()) {
	$state[$row['id']] = $row['name'];
	$states += $state;
}

//foreach ($ords['orders'] as $_order){
//	$_order->company = $tr[$_order['companyID']]->name;
//}

$ords['statuses'] = $states;
http_response_code(200);
header('Content-Type: application/json');
print(json_encode($ords));

?>