<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.06.2018
 * Time: 16:09
 */


require_once('check_key.php');

//TODO: cookie check in main auth file
$oPOSTData = json_decode(file_get_contents("php://input"));
require_once ('adm_auth.php');

$_stat_action = "get_users_list";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once ('service/user_class.php');
include_once ('service/order_class.php');

$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);
if ($mysqli->connect_errno)
	DropWithServerError();
include_once ('auth.php');

include_once( 'error_msg_helper.php' );

$dateStart = date(DATE_MYSQL_FORMAT, intval($oPOSTData->data->start));
$dateEnd = date(DATE_MYSQL_FORMAT, intval($oPOSTData->data->end));
//var_dump($dateStart);

$queryAll = "select count(*) as 'count' from " . DB_SESSIONS_TABLE .
			" WHERE TIMESTAMP BETWEEN '".$dateStart."' AND '" . $dateEnd . "'";

$queryUnique = "select count(DISTINCT uid) as 'count' from " . DB_SESSIONS_TABLE .
            " WHERE TIMESTAMP BETWEEN '".$dateStart."' AND '" . $dateEnd . "'";

$uniqueRes = $mysqli->query($queryUnique);

$allRes = $mysqli->query($queryAll);

while($oRow = $allRes->fetch_assoc()){
	$all = $oRow['count'];
}

while($oRow = $uniqueRes->fetch_assoc()){
	$unique = $oRow['count'];
}

$res['all'] = $all;
$res['unique'] = $unique;

$queryGroupedAll = "select DATE(timestamp) as 'date', count(*) as 'count' from ".DB_SESSIONS_TABLE."
WHERE TIMESTAMP BETWEEN '". $mysqli->real_escape_string($dateStart) ."' AND '". $mysqli->real_escape_string($dateEnd) ."'
 GROUP BY DAY(timestamp)  ORDER BY timestamp";

$queryGroupedUnique = "select DATE(timestamp) as 'date', count(DISTINCT uid) as 'count' from ".DB_SESSIONS_TABLE."
WHERE TIMESTAMP BETWEEN '". $mysqli->real_escape_string($dateStart) ."' AND '". $mysqli->real_escape_string($dateEnd) ."'
 GROUP BY DAY(timestamp)  ORDER BY timestamp";

$resGroupedAll =  $mysqli->query($queryGroupedAll);
$resGroupedUnique = $mysqli->query($queryGroupedUnique);

$allGrouped=array();
$uniqueGrouped = array();
while($oRow = $resGroupedAll->fetch_assoc()){
	$temp['date'] = strtotime($oRow['date']);
	$temp['count'] = $oRow['count'];
	$allGrouped[] = $temp;
}

while($oRow = $resGroupedUnique->fetch_assoc()){
	$temp['date'] = strtotime($oRow['date']);
	$temp['count'] = $oRow['count'];
	$uniqueGrouped[] = $temp;
}

$res['grouped']['all'] = $allGrouped;
$res['grouped']['unique'] = $uniqueGrouped;

$res['errors'] = GetErrorList($mysqli, 20, 0, true);

http_response_code(200);
header('Content-Type: application/json');
print(json_encode($res));
?>