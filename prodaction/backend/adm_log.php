<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 26.06.2018
 * Time: 14:14
 */

$oPOSTData = json_decode(file_get_contents("php://input"));

if ($bLog)
{

// OS
	$_stat_os_id = isset($_SERVER["HTTP_X_OS_ID"]) ? $_SERVER["HTTP_X_OS_ID"] : "unknown";

// OS version
	$_stat_os_version = isset($_SERVER["HTTP_X_OS_VERSION"]) ? $_SERVER["HTTP_X_OS_VERSION"] : "unknown";

// device uid
	$_stat_device_id = isset($_SERVER["HTTP_X_DEVICE_ID"]) ? $_SERVER["HTTP_X_DEVICE_ID"] : "unknown";

// device info
	$_stat_device_info = isset($_SERVER["HTTP_X_DEVICE_INFO"]) ? $_SERVER["HTTP_X_DEVICE_INFO"] : "unknown";

// API version
	$_stat_api_ver = "1";

// client version
	$_stat_client_ver = isset($_SERVER["HTTP_X_CLIENT_VER"]) ? $_SERVER["HTTP_X_CLIENT_VER"] : "unknown";

// client build
	$_stat_client_build = isset($_SERVER["HTTP_X_CLIENT_BUILD"]) ? $_SERVER["HTTP_X_CLIENT_BUILD"] : "unknown";

// client country
	$_stat_client_country = isset($_SERVER["HTTP_X_CLIENT_COUNTRY"]) ? $_SERVER["HTTP_X_CLIENT_COUNTRY"] : "unknown";

// client language
	$_stat_client_language = isset($_POST["lang"]) ? $_POST["lang"] : "unknown";

// client http useragent
	$_stat_client_useragent = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "unknown";

// client ip address
	$_stat_client_ipaddr = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "unknown";

// action
//$_stat_action

	/*trigger_error(PHP_EOL . "OS " . $_stat_os_id . "; OS ver " . $_stat_os_version . "; DEVICE ID " . $_stat_device_id . "; API ver " . $_stat_api_ver .
		"; client ver " . $_stat_client_ver . "; client build " . $_stat_client_build . "; client country " . $_stat_client_country .
		"; device info " . $_stat_device_info . "; useragent " . $_stat_client_useragent . "; ipaddr " . $_stat_client_ipaddr . PHP_EOL);
	*/

	@$transportNum = intval($_POST['tNum']);
	@$cityFrom = $_POST['cargoFrom'];
	@$cityTo = $_POST['cargoTo'];
	@$weight = floatval($_POST['cW']);
	@$volume = floatval($_POST['cV']);
	@$insurancePrice = floatval($_POST['cInsP']);

	$cname = (isset($_POST["cName"]) ? $_POST["cName"] : "");
	$csite = (isset($_POST["cSite"]) ? $_POST["cSite"] : "");

// DB access
	require_once "service/config.php";

	$login = 'cargo_logger';
	$pass = 'hDoVnritJ8MN';
	$table = 'web_log';
	$database = 'cargo_log';
	$host = 'mariadb';
// here we write it all

//TODO: log database on a test srv
//if(IS_PRODUCTION) {
	$mysqlHandle = new mysqli(DB_HOST,DB_LOG_LOGIN,DB_LOG_PASSWORD,DB_LOG_DB);

// check DB connection
	if ($mysqlHandle->connect_errno)
		trigger_error("cannot connect to sql " . $mysqlHandle->error);

	@$mysqlQuery = "INSERT INTO " . $table . " (
	`remote_addr`, 
	`useragent`, 
	`client_country`, 
	`client_build`, 
	`client_version`,
	 `api_version`, 
	 `device_info`, 
	 `device_id`" .
	               ", `os_version`, 
	               `os_id`, 
	               `action`,
	                `from`, 
	                `where`, 
	                `weight`, 
	                `volume`, 
	                `insurancePrice`, 
	                `compId`, 
	                `client_language`, 
	                `apikey`) VALUES " .
	               "(\"" . $mysqlHandle->real_escape_string(inet_pton($_stat_client_ipaddr)). "\", \"" .
	               $mysqlHandle->real_escape_string($_stat_client_useragent) . "\", \"" .
	               $mysqlHandle->real_escape_string($oPOSTData->cargoToCountry) . "\", \"" .
	               $mysqlHandle->real_escape_string($_stat_client_build) . "\", \"" .
	               $mysqlHandle->real_escape_string($_stat_client_ver) . "\", \"" .
	               intval($_stat_api_ver) . "\", \"" .
	               $mysqlHandle->real_escape_string($_stat_device_info) . "\", \"" .
	               $mysqlHandle->real_escape_string($_stat_device_id) . "\", \"" .
	               $mysqlHandle->real_escape_string($_stat_os_version) . "\", \"" .
	               $mysqlHandle->real_escape_string($_stat_os_id) . "\", \"" .
	               $mysqlHandle->real_escape_string($_stat_action) . "\", \"" .
	               $mysqlHandle->real_escape_string($oPOSTData->cityFrom) . "\", \"" .
	               $mysqlHandle->real_escape_string($oPOSTData->cityTo) . "\" " .
	               ", \"" . floatval($oPOSTData->weight) . "\", \""
	               . floatval($oPOSTData->volume) . "\", \""
	               . $floatval($oPOSTData->insurancePrice) . "\" " .
	               ", " . $mysqlHandle->real_escape_string($cid) .
	               ", \"" . $mysqlHandle->real_escape_string($_stat_client_language) . "\"" .
	               ", \"" . $mysqlHandle->real_escape_string($sAPIKEY) . "\")";
	//echo ($mysqlQuery);
	$resultQuery = $mysqlHandle->query( $mysqlQuery );

	if ( ! $resultQuery ) {
		trigger_error( "cannot insert to sql " . $mysqlHandle->error );
		$mysqlHandle->close();
	}

	$mysqlHandle->close();
//}
}

?>