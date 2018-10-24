<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 12.06.2018
 * Time: 15:18
 */

//require_once('check_key.php');

$_stat_action = "save_transport";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once( 'service/user_class.php' );
include_once( 'service/order_class.php' );


$oPOSTData = json_decode( file_get_contents( "php://input" ) );
include_once( 'auth.php' );


$tranId    = intval( $oPOSTData->data->companyId );
$documents = $oPOSTData->data->documents;
$tariffs   = $oPOSTData->data->tariffs;
$discounts = $oPOSTData->data->discounts;
$canorder  = boolval( $oPOSTData->data->canOrder );
$email     = $oPOSTData->data->email;
$phone     = $oPOSTData->data->phones;
$country   = $oPOSTData->data->country;
$site      = $oPOSTData->data->site;
$name      = $oPOSTData->data->name;

//prefix data
$applicationPrefix = $oPOSTData->data->applicationPrefix;
$usePrefix         = $oPOSTData->data->usePrefix;

//updating general data
$query = "UPDATE " . DB_COMPANY_TABLE . " SET " .
         "`name` = '" . $mysqli->real_escape_string( $name ) . "', " .
         "`email` = '" . $mysqli->real_escape_string( $email ) . "', " .
         "`phones` = '" . $mysqli->real_escape_string( $phone ) . "', " .
         "`country` = '" . $mysqli->real_escape_string( $country ) . "', " .
         "`application_prefix` = '" . $mysqli->real_escape_string( $applicationPrefix ) . "', " .
         "`use_prefix` = " . intval( $usePrefix ) . ", " .
         "`site` = '" . $mysqli->real_escape_string( $site ) . "' " .
         "WHERE `id` = " . $tranId;
$mysqli->query( $query );

//updating activity
//deleting previous
$query = "DELETE FROM " . DB_COMPANY_ACTIVITY . " WHERE `company_id` = " . $tranId;
$mysqli->query( $query );
//adding new
$query = "INSERT INTO " . DB_COMPANY_ACTIVITY . " (`company_id`, `is_active`) VALUES (" . $tranId . ", ";
$query .= ( $canorder ) ? 1 : 0;
$query .= ");";
$mysqli->query( $query );
//updating company documents
//deleting prev entries

$query = "DELETE FROM " . DB_COMPANY_DOCUMENT_TABLE . " WHERE `company_id` = " . $tranId;
$mysqli->query( $query );


//inserting new document options
if ( count( $documents ) > 0 ) {
	$query = "INSERT INTO " . DB_COMPANY_DOCUMENT_TABLE . " (`company_id`, `document_type_id`) VALUES ";
	foreach ( $documents as $doc ) {
		$query .= "(" . $tranId . ", " . intval( $doc ) . "), ";
	}
	$query = rtrim( $query, ", " );
	$query .= ";";
}

$mysqli->query( $query );

//discounts
//deleting prev entries:
$query = "DELETE FROM " . DB_PAYMENT_TYPE_DISCOUNT . " WHERE `company_id` = " . $tranId;
$res   = $mysqli->query( $query );

$query = "INSERT INTO " . DB_PAYMENT_TYPE_DISCOUNT . " (`company_id`, `payment_type_id`,`discount_value`, `payer_id`) VALUES ";
foreach ( $discounts as $dis ) {
	$query .= "(" . $tranId . ", " . intval( $dis->payment_id ) . ", " . floatval( $dis->discount_value ) . ", " . floatval( $dis->payer_id ) . "), ";
}
$query = rtrim( $query, ", " );
$query .= ";";
$mysqli->query( $query );

//tariffs
$tariffsQ = "SELECT `id` FROM`" . DB_COMPANY_TARIFF_TABLE . "`  WHERE `company_id` = " . intval( $tranId );
$res      = $mysqli->query( $tariffsQ );

$i = 0;
while ( $tariff[ $i ] = $res->fetch_assoc() ) {
	$tariffQuery = "UPDATE `" . DB_COMPANY_TARIFF_TABLE . "` " .
	               "SET " .
	               "`profit_coefficient` = " . floatval( $tariffs[ $i ]->profit_coefficient ) . ", " .
	               "`is_active` = " . intval( $tariffs[ $i ]->activity ) . " " .
	               "WHERE `company_id` = " . intval( $tranId ) . " AND `id`= " . $tariff[ $i ]['id'];
	$mysqli->query( $tariffQuery );
	$i ++;
}


http_response_code( 200 );
header( 'Content-Type: application/json' );
$success['status'] = "good";
print( json_encode( $success ) );

?>