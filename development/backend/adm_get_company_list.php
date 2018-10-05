<?php

//require_once('check_key.php');

/////////////////////////////////

$_stat_action = "get_company_list";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once( 'service/user_class.php' );


$oPOSTData = json_decode(file_get_contents("php://input"));

//establishing DB connection
$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

////////////////////////////////////
/////////AUTHENTICATION/////////////
// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();


// create User object
$cUser = new User();

// client language
$client_lang = isset($_POST["lang"]) ? $_SERVER["lang"] : "";
//trigger_error($client_lang);

// include all modules
foreach(glob($rundir . "/CALC_*.php") as $modname)
{
	include $modname;
}

$limit = intval($oPOSTData->data->limit);
$offset = intval($oPOSTData->data->offset);
$activeOnly = boolval($oPOSTData->data->activeOnly);
$keyword = $oPOSTData->data->keyword;
$sortCol = $oPOSTData->data->sortCol;
//helper vars
$limitSelection = false;
$limitSelection = ($activeOnly) ? true : false;
$neededIds = array();
$ord = $oPOSTData->data->order;
$jsonret = "";


$sortMethod =" ORDER BY c.id";
switch ($sortCol)
{
	case "canOrderNow":
        $sortMethod =" ORDER BY ca.is_active ".$ord;
		break;
    case "transportName":
        $sortMethod =" ORDER BY c.name ".$ord;
        break;
}

/*
$aCompRet = array();
$aCanOrder = array();
*/

/*
$comp_activities = array();
$query = 'SELECT * FROM `'.DB_COMPANY_ACTIVITY.'`';
$res = $mysqli->query($query);

if($res->num_rows > 0){
	while($row = $res->fetch_assoc()){
		$comp_activities[$row['company_id']] = $row['is_active'];
	}
}
*/
$companies = array();

$query = "SELECT c.id as id, c.icon_url as icon_url, ca.is_active as is_active, c.country as country
			FROM `" . DB_COMPANY_TABLE . "` c
			JOIN `". DB_COMPANY_ACTIVITY . "` ca ON c.id=ca.company_id
			WHERE 1";

if(isset($keyword) && $keyword != '' && $keyword != null)
{
    $query.= " AND `name` LIKE '%". $mysqli->real_escape_string($keyword) ."%'";
}


if($activeOnly)
{
    $query.= " AND ca.is_active = 1";
}

$query.= $sortMethod;

$res = $mysqli->query($query);


//if(!IS_PRODUCTION)
//{
//    var_dump($query); //die;
//}
//var_dump($query ); die;

if($res->num_rows > 0){
    while($row = $res->fetch_assoc()){
        $companies[] = array( "id" => intval($row['id']),
				"icon_url"=> $row['icon_url'],
				"is_active"=> $row['is_active'],
				"country"=> $row['country']
				);
    }
}

//var_dump($companies); die;

$aCompRet = array();

foreach($companies as $company )
{

    $transport = $transports[$company["id"]];
    $query = "SELECT * FROM " . DB_COMPANY_TABLE . " WHERE `id` = " . intval($company["id"]);
	$res = $mysqli->query($query);
	$row = $res->fetch_assoc();
    $retval  = array(
        'transportNumber' => intval($company["id"]),
        'transportName'   => $row['name'],
        'transportNames'  => __GetAllTranslations( $transport['name'], $transport['language'], false, true ),
        'transportSite'   => $row['site'],
        'transportLogo'   => $company['icon_url'] ,
        'canOrderNow'     => $company['is_active'],
        'transportLang'   => strtolower( $transport['language'] )
    );
    $aCanOrder[] = ( isset( $transport['canorder'] ) ? 0 : 1 );
    $aCompRet[]  = $retval;

}

//array_multisort($aCanOrder, $aCompRet);

$count = count($aCompRet);

$slicedret = array_slice($aCompRet, $offset, $limit);

$aGlobalRet = array(
		'companies' => $slicedret,
		'currenciesList' => $activeCurrencies,
		'count' => $count
	    );
http_response_code(200);
header('Content-Type: application/json');
print(json_encode($aGlobalRet));
//print(json_encode($slicedret));

?>

