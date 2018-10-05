<?php

require_once('check_key.php');

/////////////////////////////////

//require('simple_html_dom.php');
//require('levenshtein_utf.php');

//trigger_error($_SERVER['REMOTE_ADDR']);

$_stat_action = "get_calculation";

/*define('LANG_DB_LOGIN','cargo_dict');
define('LANG_DB_PASSWORD','q2wjlCFDoI');
define('LANG_DB_HOST',':/var/lib/mysql/mysql.sock');
define('LANG_DB_NAME','cargo_dictionary');

define('LANG_API_KEY','trnsl.1.1.20160324T054319Z.0c2dc6fa673d9436.60e9ca68cc43eb12bb258226d5233af0ac0fb95d');
*/
require_once('services.php');
require_once('modules/stat_mod.php');
require_once('service/hash_class.php');
//require('/var/www/html/api.cargo.guru/2/abstract_calc.php');

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

// check parameters
if (
    (!isset($oPOSTData->transportNumber))
    or (!isset($oPOSTData->cityFrom))
    or (!isset($oPOSTData->cityTo))
    or (!isset($oPOSTData->weight))
    or (!isset($oPOSTData->volume))
    or (!isset($oPOSTData->language))
    or (!isset($oPOSTData->currency))
    ) {
	DropWithBadRequest("No mandatory parameters");
    }

////////////////////////////////////
//
// rundir
$rundir = 'modules';
//
// Transports array
$transports = array();
//
////////////////////////////////////

$transportNum = intval($oPOSTData->transportNumber);
$cityFrom = $oPOSTData->cityFrom;
$cityTo = $oPOSTData->cityTo;
@$cargoFromCountry = $oPOSTData->cargoFromCountry;
@$cargoToCountry = $oPOSTData->cargoToCountry;
@$cargoFromState = $oPOSTData->cargoFromState;
@$cargoToState = $oPOSTData->cargoToState;
$weight = floatval($oPOSTData->weight);
$volume = floatval($oPOSTData->volume);
$insurancePrice = floatval($oPOSTData->insurancePrice);
@$paymentType = intval($oPOSTData->paymentType);

// isDerivalByCourier
// isArrivalByCourier
// cargoFromStreet
// cargoToStreet

$isActiveLineParams = 0;
$width = 0;
$length = 0;
$height = 0;

if(isset($oPOSTData->width)
	&&	isset($oPOSTData->length)
	&&	isset($oPOSTData->height))
{
	$isActiveLineParams = 1;
	$width = floatval($oPOSTData->width);
	$length = floatval($oPOSTData->length);
	$height = floatval($oPOSTData->height);
}


// client language
$client_lang = $oPOSTData->language;

// client currency
$client_curr = $oPOSTData->currency;
//isset($_POST["currency"]) ? $_POST["currency"] : "";

// create HCache instance
$hcache = new HCache();

// generate hash
$searchHash = $hcache->GenerateHash(
		$transportNum,
		$cityFrom,
		$cityTo,
		$cargoFromCountry,
		$cargoToCountry,
        $cargoFromState,
		$cargoToState,'','','','',
		$weight,
		$volume,
		$insurancePrice,
		$isActiveLineParams,
		$width,
		$length,
		$height,
    	$paymentType,
		$oPOSTData->options);

//// try to connect to redis
if(IS_PRODUCTION)
if ($hcache->isCacheAvailable()) {
    // check if we have this key
    if ($hcache->dataExists($searchHash)) {
	// data exists
	$data = $hcache->getData($searchHash);
	header('Content-Type: application/json');
	print(json_encode($data));
	exit(0);
    }
} 
//else
//    trigger_error('off');

if (trim($client_lang) == "")
    {
//	trigger_error("NO LANG");
	failResult();
    }

// include all modules
foreach(glob($rundir . "/CALC_*.php") as $modname)
    {
	include $modname;
    }

$_cityFrom = __GetAllTranslations($cityFrom,$client_lang);
$_cityTo = __GetAllTranslations($cityTo,$client_lang);

if (($_cityFrom === false) || ($_cityTo === false))
    failTranslateResult();

$cityFrom = $_cityFrom[strtolower($transports[$transportNum]['language'])];
$cityTo = $_cityTo[strtolower($transports[$transportNum]['language'])];

//trigger_error(PHP_EOL . "!!! call transport num " . $transportNum . ", cityfrom " . $cityFrom . ", cityTo " . $cityTo . ", weight " . $weight . ", vol " . $volume . ", ins " . $insurancePrice . " in " . $client_curr);
//trigger_error(PHP_EOL . "!!! states " . $cargoFromState . " = " . $cargoToState . PHP_EOL);
//trigger_error(PHP_EOL . "!!! countries " . $cargoFromCountry . " = " . $cargoToCountry . ' ; ' . $transports[$transportNum]['calcfunc'] . PHP_EOL);

//$oCalculator = new $transports[$transportNum]['classname']();
require_once ('modules/CALC_sdek.php');
$oCalculator = new calculator_SDEK();
$oRetVal = $oCalculator->Calculate(
	$oPOSTData->cityFrom,
	$oPOSTData->cityTo,
	$oPOSTData->weight,
	$oPOSTData->volume,
	100,
	$oPOSTData->language,
	$oPOSTData->currency,
	'RU',
	'RU',
	'',
	'',
	true,
	$oPOSTData->width,
	$oPOSTData->length,
	$oPOSTData->height,
	null
);;

$oRetVal = AddBonus($oRetVal,$transportNum,$paymentType,$client_lang,$client_curr);

if($transportNum!=32) {
    $oRetVal = AddIntercity($oRetVal, $cityFrom, $cityTo, $client_curr);
}

//// trying Redis again
if(IS_PRODUCTION)
if ($hcache->isCacheAvailable()) {
    // we have to save this key
    $hcache->setData($searchHash, $oRetVal);
}

header('Content-Type: application/json');
if(IS_PRODUCTION)
{
    print(json_encode($oRetVal));
}
else
{
    echo '<pre>';
    print_r($oRetVal);
}

//////////////////////////////////////////////////////////////////////////////////

function failResult() {
	$errArr = array(
		'failReason' => 'No mandatory parameters.'
	    );
	http_response_code(500);
	header('Content-Type: application/json');
	print(json_encode($errArr));
	exit(0);
}

function failTranslateResult() {
	$errArr = array(
		'failReason' => 'Cannot translate'
	    );
	http_response_code(500);
	header('Content-Type: application/json');
	print(json_encode($errArr));
	exit(0);
}

?>

