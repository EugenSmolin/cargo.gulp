<?php

//require_once('check_key.php');

/////////////////////////////////

$_stat_action = "get_complist";

require_once('services.php');
require_once('modules/stat_mod.php');

header('Content-Type: application/json');

////////////////////////////////////
//
// Transports array
$transports = array();
//
////////////////////////////////////

//$cityFrom = $_POST['cargoFrom'];
//$cityTo = $_POST['cargoTo'];
//$weight = $_POST['cW'];
//$volume = $_POST['cV'];
//$insurancePrice = 5000;
//$kindOfCargo = 0;

// client language
$client_lang = isset($_POST["lang"]) ? $_SERVER["lang"] : "";

//trigger_error($client_lang);

// include all modules
foreach(glob($rundir . "/CALC_*.php") as $modname)
{
	include $modname;
}

$jsonret = "";

$aCompRet = array();
$aCanOrder = array();

// ret
foreach($transports as $transportNumber => $transport)
    {
	//printf(PHP_EOL . "calculating transport [%04d] %s" . PHP_EOL, $transportNumber, $transport['name']);
//	$transport['calcfunc']($cityFrom,$cityTo,$weight,$volume,$insurancePrice,$kindOfCargo);
	$jsonret .= ($jsonret == "" ? "" : ",");
	$retval = array('transportNumber' => $transportNumber,
			'transportName' => $transport['name'],
			'transportNames' => __GetAllTranslations($transport['name'], $transport['language'], false, true),
			'transportSite' => $transport['site'],
			'transportLogo' => (isset($transport['logo']) ? $transport['logo'] : '' ),
			'canOrderNow' => (isset($transport['canorder']) ? true : false ),
			'transportLang' => strtolower($transport['language']));

	$aCanOrder[] = (isset($transport['canorder']) ? 0 : 1 );
	$aCompRet[] = $retval;
    }

array_multisort($aCanOrder, $aCompRet);

$aGlobalRet = array(
		'companies' => $aCompRet,
		'currenciesList' => $activeCurrencies
	    );

print(json_encode($aGlobalRet));

?>

