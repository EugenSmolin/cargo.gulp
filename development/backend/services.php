<?php

require_once('service/config.php');
require_once('service/service.php');

define('LANG_DB_LOGIN','cargo_dict');
define('LANG_DB_PASSWORD','q2wjlCFDoI');
define('LANG_DB_HOST',DB_HOST);
define('LANG_DB_NAME','cargo_dictionary');

define('CURR_DB_LOGIN','cargo_cur_update');
define('CURR_DB_PASSWORD','MN6jLUu6fk');
define('CURR_DB_HOST',DB_HOST);
define('CURR_DB_NAME','cargo_currencies');

define('ICAO_DB_LOGIN','cargo_iata');
define('ICAO_DB_PASSWORD','YjSeKZmR3cyV7L4d');
define('ICAO_DB_HOST',DB_HOST);
define('ICAO_DB_NAME','cargo_iatacodes');

define('ZIP_DB_NAME','cargo_zipcodes');
define('KLADR_DB_NAME','cargo_kladr');

define('LANG_API_KEY','trnsl.1.1.20160324T054319Z.0c2dc6fa673d9436.60e9ca68cc43eb12bb258226d5233af0ac0fb95d');
define('LANG_API_URL','https://translate.yandex.net/api/v1.5/tr.json/translate');

//define('GAPI_KEY','AIzaSyBF_ELhc1wkf1ntzHaQlP21cP66SFwh_qc');
//define('GAPI_KEY','AIzaSyAYRFT9UNy9usKQV4U80fi_ELXBUdm85TI');
//define('GAPI_KEY','AIzaSyBuXdTQ94kwQjbLUnnxGsPh43-JXNSKIj8'); // Changed 03.05.2018 by Adriis
define('GAPI_KEY','AIzaSyBuXdTQ94kwQjbLUnnxGsPh43-JXNSKIj8');

define('GAPI_URL','https://www.googleapis.com/language/translate/v2');

define('PROXY','squid:3128');

define('TRAN_LOG','/var/www/html/api.cargo.guru/2/log/tran.log');
define('ZIP_LOG','/var/www/html/api.cargo.guru/2/log/zip.log');

define('REDIS_HOST','redis');
define('REDIS_PORT', 6379);
define('REDIS_EXPIRE', 86400);

//////////////////////////////////////////////

$activeCurrencies = array('RUB','USD','CNY','KZT','EUR', 'UAH', 'HKD', 'CAD', 'ZAR', 'NZD', 'BGN', 'HRK', 'CZK', 'HUF', 'PLN', 'RON', 'SEK', 'GBP', 'PKR', 'BGN',
			'KES', 'INR', 'PHP', 'AED', 'AUD', 'BHD', 'KWD','JPY', 'DKK', 'TTD', 'SGD', 'SAR', 'NGN', 'QAR', 'MYR');
$activeLangs = array('ru','en','de','zh', 'fr', 'uk','es','ja','ko','ms');//,'cn');

//////////////////////////////////////////////

// rundir
$rundir = 'modules';

//////////////////////////////////////////////

function getDOMNodeAsObject($node) {
    $html = '';
    $children = $node->childNodes;

    $tmp_doc = new DOMDocument();
    
    foreach ($children as $child) {
	    $tmp_doc->appendChild($tmp_doc->importNode($child, true));
	    //$html .= $tmp_doc->saveHTML();
	}
    return $tmp_doc;
}

////////////////////////
function DropCalculation()
    {
	global $activeLangs;
	
	$outResultArray['failReason'] = 'У этой компании нет для вас предложений';
	$outResultArray['failReasons'] = array();

	foreach($activeLangs as $activeLang) {
	    $outResultArray['failReasons'][$activeLang] = 'no offers';
	}
	
	return $outResultArray;
    }

///////////////////////////////////////////
function OOGetTranslationYA($srcText,$from,$to)
    {
    
	//connect to DB
	$mysqlHandle = mysql_connect(LANG_DB_HOST, LANG_DB_LOGIN, LANG_DB_PASSWORD);
	if (!$mysqlHandle)
	    return false;

	$selectResult = mysql_select_db(LANG_DB_NAME);
	if (!$selectResult)
	    return false;

	if (mb_strtoupper($from,"utf-8") == mb_strtoupper($to,"utf-8"))
	    return $srcText;

	// request DB for text
	$requestText = "SELECT * FROM `dictionary` WHERE `from_lang` = '" . mysql_real_escape_string(mb_strtoupper($from,"utf-8")) . "' AND `to_lang` = '" . 
	    mysql_real_escape_string(mb_strtoupper($to,"utf-8")) . "' AND `original_text` = '" . mysql_real_escape_string(mb_strtoupper($srcText,"utf-8")) . "'";

	$queryText = mysql_query($requestText);

	if (mysql_num_rows($queryText) < 1)
	    {
		// no @ DB
		// request from yandex
		$trTextReq = LANG_API_URL . "?lang=" . mysql_real_escape_string(mb_strtoupper($from,"utf-8")) . "-" . 
		    mysql_real_escape_string(mb_strtoupper($to,"utf-8")) . "&text=" . $srcText . "&key=" . LANG_API_KEY;
		$trTextAnsw = file_get_contents($trTextReq);
		//trigger_error($trTextAnsw);
		$trTextJSON = json_decode($trTextAnsw);

		if ($trTextJSON->code != 200)
		    return false;
		else
		    {
			// translation ok, write it to DB
			$reqAddText = "INSERT INTO `dictionary` (from_lang, to_lang, original_text, translation_text) VALUES ('" . mysql_real_escape_string(mb_strtoupper($from,"utf-8")) . "', '" . 
			    mysql_real_escape_string(mb_strtoupper($to,"utf-8")) . "', '" . mysql_real_escape_string(mb_strtoupper($srcText,"utf-8")) . "', '" . 
			    mysql_real_escape_string(mb_strtoupper($trTextJSON->text[0], "utf-8")) . "')";
			//$queryAddText = mysql_query($reqAddText);
			// return translation
			return mb_strtoupper($trTextJSON->text[0], "utf-8");
		    }
	    }
	else
	    {
		// we have translation @ DB
		$queryResult = mysql_fetch_assoc($queryText);
		// return translated
		return $queryResult['translation_text'];
	    }
    }

//////////////////////////////////////////////
///////////////////////////////////////////
function _GetTranslation($srcText,$from,$to, $convert = true, $mysqlHandle)
    {

        if(!IS_PRODUCTION) return;

	date_default_timezone_set('UTC');
	mb_internal_encoding("utf-8");

	if (mb_strtoupper($from,"utf-8") == mb_strtoupper($to,"utf-8"))
	    return $srcText;

	if (trim($srcText) == "")
	    return "";

//	trigger_error('ALLTRAN convert = ' . $convert);

	if ($convert)
	    {
//		$reqSrcText = mb_strtolower($srcText,"utf-8");
//		trigger_error('CONVERT ALLTRAN' . PHP_EOL);
/*		$_FSsrc = mb_strtoupper(mb_substr($reqSrcText,0,1,"utf-8"));
		$_LSsrc = mb_substr($reqSrcText,1,NULL,"utf-8");
		$reqSrcText = $_FSsrc . $_LSsrc;*/
		//trigger_error($reqSrcText . PHP_EOL);
		$reqSrcText = CapitalizeFirstLetters($srcText);
//		trigger_error($reqSrcText . PHP_EOL);
	    }
	else
		$reqSrcText = $srcText;

	//return $srcText;
	//exit(0);
	
	// request DB for text
	//mysql_query('LOCK TABLES dictionary WRITE');
	//trigger_error(mysql_error());
	//print(microtime() . PHP_EOL);
	$requestText = "SELECT * FROM `dictionary` WHERE `from_lang` = '" . $mysqlHandle->real_escape_string(mb_strtoupper($from,"utf-8")) . "' AND `to_lang` = '" . 
	    $mysqlHandle->real_escape_string(mb_strtoupper($to,"utf-8")) . "' AND `original_text` = '" . $mysqlHandle->real_escape_string($reqSrcText) . "'";
//echo $requestText; die;
	$queryText = $mysqlHandle->query($requestText);
	//print(mysql_error());
	//print(microtime() . PHP_EOL . $requestText . PHP_EOL);

	if ($queryText->affected_rows < 1)
	    {
		// no @ DB
		// request from yandex
		//print(PHP_EOL.PHP_EOL.'we have no result with ' . $requestText . PHP_EOL . PHP_EOL);
//		file_put_contents(TRAN_LOG,"RQ " . date('c') .' ' . $from . " -> " . $to . " | " . $reqSrcText . PHP_EOL, FILE_APPEND);
		//print($res.PHP_EOL);
		//echo "TYT"; die;
		$trTextReq = GAPI_URL . "?source=" . $mysqlHandle->real_escape_string(mb_strtolower($from,"utf-8")) . "&target=" . 
		    $mysqlHandle->real_escape_string(mb_strtolower($to,"utf-8")) . "&q=" . urlencode($reqSrcText) . "&key=" . GAPI_KEY;
		    
		$trTextAnsw = file_get_contents($trTextReq);
/*		trigger_error($trTextReq);
		trigger_error($trTextAnsw);*/
		$trTextJSON = json_decode($trTextAnsw);
		
/*		if ($trTextJSON->data->translations[0]->translatedText == "")
		    return false;
		else
		    {*/
			// translation ok, write it to DB
			$retText = trim($trTextJSON->data->translations[0]->translatedText);
			if ($retText != "")
			    {
				if ($convert)
				    {
/*					$reqWrText = mb_strtolower($retText,"utf-8");
					$_FSsrc = mb_strtoupper(mb_substr($reqWrText,0,1,"utf-8"));
					$_LSsrc = mb_substr($reqWrText,1,NULL,"utf-8");
					$reqWrText = $_FSsrc . $_LSsrc;*/
					$reqWrText = CapitalizeFirstLetters($retText);
//					trigger_error('transl' . $reqWrText . PHP_EOL);
				    }
				else
				    $reqWrText = $retText;
				
				//trigger_error('for write ' . $reqWrText . PHP_EOL);
				$reqAddText = "INSERT INTO `dictionary` (from_lang, to_lang, original_text, translation_text) VALUES ('" . $mysqlHandle->real_escape_string(mb_strtoupper($from,"utf-8")) . "', '" . 
				    $mysqlHandle->real_escape_string(mb_strtoupper($to,"utf-8")) . "', '" . $mysqlHandle->real_escape_string($reqSrcText) . "', '" . 
				    $mysqlHandle->real_escape_string($reqWrText) . "')";
				$queryAddText = $mysqlHandle->query($reqAddText);
				//$retText = $reqWrText;
			    }
			// return translation
			return $retText;
//		    }
	    }
	else
	    {
		// we have translation @ DB
		//print(PHP_EOL.PHP_EOL.'we have found result with ' . $requestText . PHP_EOL . PHP_EOL);
//		file_put_contents(TRAN_LOG,"DB " . date('c') . ' ' . $from . " -> " . $to . " | " . $reqSrcText . PHP_EOL, FILE_APPEND);
//		print($rr);
		$queryResult = $queryText->fetch_assoc();
		//print(microtime() . PHP_EOL . PHP_EOL);
		// return translated
		return $queryResult['translation_text'];
	    }
	//mysql_query('UNLOCK TABLES;');
	//trigger_error(mysql_error());
    }

//////////////////////////////////////////////
//////////////////////////////////////////////
///////////////////////////////////////////
function _GetTranslationYA($srcText,$from,$to, $convert = true, $mysqlHandle)
    {

	date_default_timezone_set('UTC');
	mb_internal_encoding("utf-8");

//print($from . ' fff ' . $to . PHP_EOL);

	if (mb_strtoupper($from,"utf-8") == mb_strtoupper($to,"utf-8"))
	    return $srcText;

	if (trim($srcText) == "")
	    return "";

//	trigger_error('ALLTRAN convert = ' . $convert);

	if ($convert)
	    {
//		$reqSrcText = mb_strtolower($srcText,"utf-8");
//		trigger_error('CONVERT ALLTRAN' . PHP_EOL);
/*		$_FSsrc = mb_strtoupper(mb_substr($reqSrcText,0,1,"utf-8"));
		$_LSsrc = mb_substr($reqSrcText,1,NULL,"utf-8");
		$reqSrcText = $_FSsrc . $_LSsrc;*/
		//trigger_error($reqSrcText . PHP_EOL);
		$reqSrcText = CapitalizeFirstLetters($srcText);
//		trigger_error($reqSrcText . PHP_EOL);
	    }
	else
		$reqSrcText = $srcText;

	//return $srcText;
	//exit(0);
	
	// request DB for text
	//mysql_query('LOCK TABLES dictionary WRITE');
	//trigger_error(mysql_error());
	//print(microtime() . PHP_EOL);
	$requestText = "SELECT * FROM `dictionary` WHERE `from_lang` = '" . mysql_real_escape_string(mb_strtoupper($from,"utf-8")) . "' AND `to_lang` = '" . 
	    mysql_real_escape_string(mb_strtoupper($to,"utf-8")) . "' AND `original_text` = '" . mysql_real_escape_string($reqSrcText) . "'";

	$queryText = mysql_query($requestText);
	//print(mysql_error());
	//print(microtime() . PHP_EOL . $requestText . PHP_EOL);

	if (mysql_num_rows($queryText) < 1)
	    {
		// no @ DB
		// request from yandex
		//print(PHP_EOL.PHP_EOL.'we have no result with ' . $requestText . PHP_EOL . PHP_EOL);
		file_put_contents(TRAN_LOG,"RQ " . date('c') .' ' . $from . " -> " . $to . " | " . $reqSrcText . PHP_EOL, FILE_APPEND);
		
		//print($ff.PHP_EOL);
		
//		$trTextReq = GAPI_URL . "?source=" . mysql_real_escape_string(mb_strtolower($from,"utf-8")) . "&target=" . 
//		    mysql_real_escape_string(mb_strtolower($to,"utf-8")) . "&q=" . urlencode($reqSrcText) . "&key=" . GAPI_KEY;

		$trTextReq = LANG_API_URL . "?lang=" . mysql_real_escape_string(mb_strtoupper($from,"utf-8")) . "-" . 
		    mysql_real_escape_string(mb_strtoupper($to,"utf-8")) . "&text=" . urlencode($srcText) . "&key=" . LANG_API_KEY;
		$trTextAnsw = file_get_contents($trTextReq);
		    
//		$trTextAnsw = file_get_contents($trTextReq);
/*		trigger_error($trTextReq);
		trigger_error($trTextAnsw);*/
		$trTextJSON = json_decode($trTextAnsw);
		
/*		if ($trTextJSON->data->translations[0]->translatedText == "")
		    return false;
		else
		    {*/
			// translation ok, write it to DB
//			$retText = trim($trTextJSON->data->translations[0]->translatedText);
			$retText = trim($trTextJSON->text[0]);
			if (($retText != ""))
			    {
				if ($convert)
				    {
/*					$reqWrText = mb_strtolower($retText,"utf-8");
					$_FSsrc = mb_strtoupper(mb_substr($reqWrText,0,1,"utf-8"));
					$_LSsrc = mb_substr($reqWrText,1,NULL,"utf-8");
					$reqWrText = $_FSsrc . $_LSsrc;*/
					$reqWrText = CapitalizeFirstLetters($retText);
//					trigger_error('transl' . $reqWrText . PHP_EOL);
				    }
				else
				    $reqWrText = $retText;
				
				//trigger_error('for write ' . $reqWrText . PHP_EOL);
				$reqAddText = "INSERT INTO `dictionary` (from_lang, to_lang, original_text, translation_text) VALUES ('" . mysql_real_escape_string(mb_strtoupper($from,"utf-8")) . "', '" . 
				    mysql_real_escape_string(mb_strtoupper($to,"utf-8")) . "', '" . mysql_real_escape_string($reqSrcText) . "', '" . 
				    mysql_real_escape_string($reqWrText) . "')";
				$queryAddText = mysql_query($reqAddText);
				//$err = mysql_error();
				//file_put_contents(TRAN_LOG,"RQ " . date('c') .' ' . $err . PHP_EOL, FILE_APPEND);
				//$retText = $reqWrText;
			    }
			// return translation
			return $retText;
//		    }
	    }
	else
	    {
		// we have translation @ DB
		//print(PHP_EOL.PHP_EOL.'we have found result with ' . $requestText . PHP_EOL . PHP_EOL);
		file_put_contents(TRAN_LOG,"DB " . date('c') . ' ' . $from . " -> " . $to . " | " . $reqSrcText . PHP_EOL, FILE_APPEND);
//		print($rr);
		$queryResult = mysql_fetch_assoc($queryText);
		//print(microtime() . PHP_EOL . PHP_EOL);
		// return translated
		return $queryResult['translation_text'];
	    }
	//mysql_query('UNLOCK TABLES;');
	//trigger_error(mysql_error());
    }

//////////////////////////////////////////////

function GetConvertedPrices($base_price, $base_curr)
    {
        $activeCurrencies = array('RUB','USD','CNY','KZT','EUR', 'UAH', 'HKD', 'CAD', 'ZAR', 'NZD', 'BGN', 'HRK', 'CZK', 'HUF', 'PLN', 'RON', 'SEK', 'GBP', 'PKR', 'BGN',
            'KES', 'INR', 'PHP', 'AED', 'AUD', 'BHD', 'KWD','JPY', 'DKK', 'TTD', 'SGD', 'SAR', 'NGN', 'QAR', 'MYR');

    $base_curr = strtoupper($base_curr);

	$currStr = "'" . implode('\',\'',$activeCurrencies) . "'";

	//connect to DB
	$mysqlHandle = new mysqlii(CURR_DB_HOST, CURR_DB_LOGIN, CURR_DB_PASSWORD, CURR_DB_NAME);
	if (!$mysqlHandle)
	    return false;

	$retVal = array();

	$currSQL = "SELECT * FROM `currencies` WHERE `currency_quote` IN (" . $currStr .
			 ") AND `currency_base` = '" . $mysqlHandle->real_escape_string($base_curr) . "'";
//	$currSQL = "SELECT * FROM `currencies` WHERE `currency_quote` IN (" . mysql_real_escape_string($currStr) .
//			 ") AND `currency_base` = '" . mysql_real_escape_string($base_curr) . "'";
	$currQuery = $mysqlHandle->query($currSQL);

//	trigger_error(mysql_error());

	while($currRow = $currQuery->fetch_assoc())
	    {
		$retVal[$currRow['currency_quote']] = floatval($base_price / $currRow['value']);
	    }

	$retVal[$base_curr] = floatval($base_price);

	$mysqlHandle->close();

	return $retVal;
    }

////////////////////////////////////////////////////

function __GetAllTranslations($srcText,$from, $convert = true, $upcase = true)
    {
	global	$activeLangs;
	$retval = [];
//die();
	$from = mb_strtoupper($from);
	//connect to DB
	$mysqlHandle = new mysqlii(LANG_DB_HOST, LANG_DB_LOGIN, LANG_DB_PASSWORD, LANG_DB_NAME);

	if ($mysqlHandle->connect_error) {
	    trigger_error($mysqlHandle->connect_error . PHP_EOL . LANG_DB_HOST);
	    return false;
	}

	$mysqlHandle->query('SET NAMES utf8');
	$mysqlHandle->query('SET CHARACTER SET `utf8`');

// die();
	// compile langs
	$dbLangs = '';
	$reqLangs = array();

	if(!isset($activeLangs)) {
	    $activeLangs = array('ru','en','de','zh', 'fr', 'uk','es','ja','ko','ms');//,'cn');
    }
    foreach ($activeLangs as $lang) {
        $upLang = mb_strtoupper($lang);
        $dbLangs .= $dbLangs == '' ? '' : ', ';
        $dbLangs .= "'" . $mysqlHandle->real_escape_string($upLang) . "'";
        $reqLangs[mb_strtolower($upLang)] = '';
    }

	$dbLangs = "(" . $dbLangs . ")";
/*	foreach($activeLangs as $lang)
	    {
		if ($upcase)
		    $retval[$lang] = mb_strtoupper(_GetTranslation($srcText,$from,$lang, $convert,$mysqlHandle),"utf-8");
		else
		    $retval[$lang] = _GetTranslation($srcText,$from,$lang, $convert,$mysqlHandle);
	    }
*/
	$trQuery = 'SELECT * FROM `dictionary` WHERE `from_lang` = "' . mb_strtoupper($from) . '" AND ' .
		    '`to_lang` IN ' . $dbLangs . ' AND `original_text` = "' . CapitalizeFirstLetters($srcText). '"';
    //var_dump($trQuery); die();
	$trResult = $mysqlHandle->query($trQuery);

	if ($mysqlHandle->error)
	    {
		$mysqlHandle->close();
		return false;
	    }

	// fill results
	while($trRow = $trResult->fetch_assoc())
	    {
			//$retval[$trRow['to_lang']] = $trRow['translation_text'];
			$reqLangs[mb_strtolower($trRow['to_lang'])] = mb_strtoupper($trRow['translation_text']);
	    }
	//var_dump($reqLangs); die;
	// check for empty results
	foreach($reqLangs as $key => $lang)
	    {
		if (($lang == '') and ($key != $from))
		    {
	//			trigger_error('fail ' . $key);
				//print($key . ' fail' . PHP_EOL);
				$reqLangs[mb_strtolower($key)] = mb_strtoupper(_GetTranslation($srcText,$from,$key, true,$mysqlHandle));
	//			$reqLangs[mb_strtolower($key)] = mb_strtoupper(_GetTranslationYA($srcText,$from,$key, true,$mysqlHandle));
		    }
		else
		    {
			// we have an translation
			//file_put_contents(TRAN_LOG,"DB " . date('c') . ' ' . $from . " -> " . $lang . " | " . $srcText . PHP_EOL, FILE_APPEND);
		    }
	    }

	$mysqlHandle->close();

	$reqLangs[mb_strtolower($from)] = $srcText;

	return $reqLangs;
    }

///////////////////////////////////////////////////

function CapitalizeFirstLetters($str)
    {
	mb_regex_encoding("UTF-8");
	mb_internal_encoding("UTF-8");
	$pStr = mb_split(" ", $str);
	$newStr = "";
	foreach($pStr as $cWord)
	    {
//		trigger_error($cWord);
		$tmp1 = mb_strtolower($cWord);
		$_FSsrc = mb_strtoupper(mb_substr($tmp1,0,1));
		$_LSsrc = mb_substr($tmp1,1,NULL);
		$newStr .= ($newStr == "" ? "" : " ");
		$newStr .= $_FSsrc . $_LSsrc;
	    }
//	trigger_error('FFFFFFFFFFFFFFf ' . $newStr . PHP_EOL);
	return $newStr;
    }

/////////////////////////////////////////////////
/////////////////////////////////////////////////

function GetFreightClass($LbsPerKg)
    {
	if ($LbsPerKg > 50)
	    return 50;
	else if (($LbsPerKg > 35) && ($LbsPerKg <= 50))
	    return 55;
	else if (($LbsPerKg > 30) && ($LbsPerKg <= 35))
	    return 60;
	else if (($LbsPerKg > 22.5) && ($LbsPerKg <= 30))
	    return 65;
	else if (($LbsPerKg > 15) && ($LbsPerKg <= 22.5))
	    return 70;
	else if (($LbsPerKg > 13.5) && ($LbsPerKg <= 15))
	    return 77.5;
	else if (($LbsPerKg > 12) && ($LbsPerKg <= 13.5))
	    return 85;
	else if (($LbsPerKg > 10.5) && ($LbsPerKg <= 12))
	    return 92.5;
	else if (($LbsPerKg > 9) && ($LbsPerKg <= 10.5))
	    return 100;
	else if (($LbsPerKg > 8) && ($LbsPerKg <= 9))
	    return 110;
	else if (($LbsPerKg > 7) && ($LbsPerKg <= 8))
	    return 125;
	else if (($LbsPerKg > 6) && ($LbsPerKg <= 7))
	    return 150;
	else if (($LbsPerKg > 5) && ($LbsPerKg <= 6))
	    return 175;
	else if (($LbsPerKg > 4) && ($LbsPerKg <= 5))
	    return 200;
	else if (($LbsPerKg > 3) && ($LbsPerKg <= 4))
	    return 250;
	else if (($LbsPerKg > 2) && ($LbsPerKg <= 3))
	    return 300;
	else if (($LbsPerKg > 1) && ($LbsPerKg <= 2))
	    return 400;
	else if ($LbsPerKg <= 1)
	    return 500;
	else
	    return 0;
    }

/////////////////////////////

function CubicInchesFromCubicMeters($cmeters)
    {
	return $cmeters * 61023.76;
    }

/////////////////////////////

function CubicFeetsFromCubicMeters($cmeters)
    {
	return $cmeters / 0.0283168;
    }

////////////////////////////

function CubicFeetsFromCubicInches($cinches)
    {
	return $cinches / 1728;
    }

////////////////////////////

function FeetsFromMeters($meters)
    {
	return $meters / 0.3048;
    }

///////////////////////////

function InchesFromMeters($meters)
    {
	return $meters * 39.3701;
    }

///////////////////////////

function LbsFromKg($kg)
    {
	return $kg * 2.2046226218487757;
    }

///////////////////////////

function KgFromLbs($lbs)
    {
	return $lbs * 2.20462;
    }

//////////////////////////

function ParseCamelString($camelString)
    {
	return trim(preg_replace('/[A-Z]+/',' $0',$camelString));
    }

////////////////////////

function GetZIPCode($sAddr, $sAPIKEY)
    {
	date_default_timezone_set('UTC');

	if (trim($sAddr) == "")
	    return "";

	//connect to DB
	$mysqlHandle = new mysqlii(LANG_DB_HOST, LANG_DB_LOGIN, LANG_DB_PASSWORD, ZIP_DB_NAME);
	if (!$mysqlHandle)
	    return false;

	// check if we have this addr
	
	$requestText = "SELECT * FROM `zipcodes` WHERE `address` = \"" . $mysqlHandle->real_escape_string($sAddr) . "\"";
	$resultAddr = $mysqlHandle->query($requestText);
	
	if ($resultAddr->affected_rows > 0)
	    {
//		file_put_contents(ZIP_LOG,"DB " . date('c') . ' ' . $sAddr . PHP_EOL, FILE_APPEND);
		$resultFields = $resultAddr->fetch_assoc();
		$mysqlHandle->close();
		return $resultFields["zipcode"];
	    }
	else
	    {
	    
		// REQUEST NEEDED
		
//		file_put_contents(ZIP_LOG,"RQ " . date('c') . ' ' . $sAddr . PHP_EOL, FILE_APPEND);

		$geocodeURL = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($sAddr) . '&key=' . $sAPIKEY;
		$sTmp = file_get_contents_proxy($geocodeURL, PROXY);
//	trigger_error($stmp);
		$sGCRes = json_decode($sTmp);

		if ($sGCRes->status != 'OK')
		    {
			$mysqlHandle->close();
			return '';
		    }

		$lat = $sGCRes->results[0]->geometry->location->lat;
		$lng = $sGCRes->results[0]->geometry->location->lng;

		$rGeocodeURL = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&key=' . $sAPIKEY;
		$sRGCRes = json_decode(file_get_contents_proxy($rGeocodeURL, PROXY));

		if ($sRGCRes->status != 'OK')
		    {
			$mysqlHandle->close();
			return '';
		    }

		$aAddrComponents = $sRGCRes->results[0]->address_components;

		foreach($aAddrComponents as $component)
		    {
			//print_r($aAddrComponents);
			foreach($component->types as $type)
			    {
				//print_r($component);
				//print(PHP_EOL . PHP_EOL);
				if (($type == 'postal_code') or ($type == 'postal_code_prefix'))
				    {
					$insertReq = "INSERT INTO zipcodes (`address`,`zipcode`) VALUES (\"" . $mysqlHandle->real_escape_string($sAddr) . "\", \"" . 
							$mysqlHandle->real_escape_string($component->long_name) . "\")";
					$mysqlHandle->query($insertReq);
					return $component->long_name;
					//print($component->long_name . PHP_EOL);
				    }
			    }
		    }
	    }
    }

////////////////////////

function GetCityKLADRCode($sAddr, $sAPIKEY)
    {
	mb_regex_encoding("UTF-8");
	mb_internal_encoding("UTF-8");
	date_default_timezone_set('UTC');

	if (trim($sAddr) == "")
	    return "";

	$geocodeURL = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($sAddr) . '&language=ru&key=' . $sAPIKEY;
	$sTmp = json_decode(file_get_contents($geocodeURL));
	
	if ($sTmp->status != 'OK')
	    return false;
	
	$oRetVal = array();
	
	foreach($sTmp->results[0]->address_components as $component)
	    {
		if ((in_array('political',$component->types)) and (in_array('locality',$component->types)))
		    $oRetVal['city'] = $component->long_name;

		if (in_array('route',$component->types))
		    $oRetVal['street'] = $component->long_name;

		if (in_array('street_number',$component->types))
		    $oRetVal['house'] = $component->long_name;
	    }


         //   echo ' <br>',$sAddr, ' <br>';
          //  var_dump($oRetVal);


	    $host = LANG_DB_HOST;
	   // if (IS_DEBUG) $host='localhost';

	//connect to DB
	$mysqlHandle = new mysqlii($host, LANG_DB_LOGIN, LANG_DB_PASSWORD, KLADR_DB_NAME);
	$mysqlHandle->query("SET NAMES utf8 COLLATE utf8_unicode_ci");
	$mysqlHandle->set_charset("utf8");

//	print($mysqlHandle->conect_errno);
	if (!$mysqlHandle)
	    return false;

	if (!empty($oRetVal['city']))
	    {
		$requestText = "SELECT * FROM `dellin_kladr` WHERE UPPER(`search`) = \"" .
                    mb_strtoupper($oRetVal['city']) . "\" " .
				"ORDER BY (char_length(code) - char_length(replace(code,'0',''))) DESC";

//		$requestText = "SELECT * FROM `dellin_kladr` where `name` like '%москв%'";
		$resultAddr = $mysqlHandle->query($requestText);
        if(IS_DEBUG) echo '<br>'.$requestText.'<br>';
//			print($resultAddr->num_rows . '=' . LANG_DB_LOGIN . ' = ' . LANG_DB_PASSWORD . '=' . $requestText . PHP_EOL);
//		die();
//var_dump( $resultAddr);
		if ($resultAddr->num_rows > 0)
		  {
			$col = $resultAddr->fetch_assoc();
                if(IS_DEBUG){
                    echo '<br>';
                    var_dump($col);
                    echo '<br>';}
			    $oRetVal['city_kladr'] = $col['code'];
                $oRetVal['cityID'] = $col['cityID'];
		  }
	    }

	$mysqlHandle->close();

//	print_r($oRetVal);

	return $oRetVal;
    }

////////////////////////

function GetCountryCode($sAddr, $sAPIKEY)
    {

	if (trim($sAddr) == "")
	    return "";

	//connect to DB
//	$mysqlHandle = new mysqlii(LANG_DB_HOST, LANG_DB_LOGIN, LANG_DB_PASSWORD);
//	if (!$mysqlHandle)
//	    return false;

//	file_put_contents(ZIP_LOG,"RQ CNTRY " . $sAddr . PHP_EOL, FILE_APPEND);

	$geocodeURL = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($sAddr) . '&key=' . $sAPIKEY;
	$sGCRes = json_decode(file_get_contents_proxy($geocodeURL, PROXY));

	if ($sGCRes->status != 'OK')
	    return '';

	$lat = $sGCRes->results[0]->geometry->location->lat;
	$lng = $sGCRes->results[0]->geometry->location->lng;

	$rGeocodeURL = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&key=' . $sAPIKEY;
	$sRGCRes = json_decode(file_get_contents_proxy($rGeocodeURL, PROXY));

	if ($sRGCRes->status != 'OK')
	    return '';

	$aAddrComponents = $sRGCRes->results[0]->address_components;

	foreach($aAddrComponents as $component)
	    {
		//print_r($aAddrComponents);
		foreach($component->types as $type)
		    {
			//print_r($component);
			//print(PHP_EOL . PHP_EOL);
			if ($type == 'country')
			    {
				return $component->short_name;
				//print($component->long_name . PHP_EOL);
			    }
		    }
	    }
    }

///////////////////////////////////

function file_get_contents_proxy($url,$proxy)
    {
	// check for http/https
	$protoParse0 = explode('://',$url);
	$proto = $protoParse0[0];

	// Create context stream
	if ($proto == 'http')
	    $context_array = array('http' => array('proxy'=>$proxy,'request_fulluri'=>true));
	else if ($proto == 'https')
	    $context_array = array('https' => array('proxy'=>$proxy,'request_fulluri'=>true));
	else
	    return false;

	$context = stream_context_create($context_array);

        // Use context stream with file_get_contents
        $data = file_get_contents($url,false,$context);

	// Return data via proxy
	return $data;
    }

/////////////////////////////////////////

function GetIATAByCity($sCity = '')
    {
	if ($sCity == '')
	    return array();
	
	$oMysqli = new mysqlii('', ICAO_DB_LOGIN, ICAO_DB_PASSWORD, ICAO_DB_NAME);
	if ($oMysqli->connect_error)
	    return array();
	
	$oSelect = $oMysqli->query('SELECT * FROM `iatacodes` WHERE UPPER(`city`) = UPPER("' . $oMysqli->real_escape_string($sCity) . '") AND `iata` <> "" ' .
			'AND `icao` <> "NONE" AND `icao` <> "\\N"');
	
	if ($oSelect->num_rows < 1)
	    return array();

	$aRetVal = array();
	
	while($oCurRow = $oSelect->fetch_assoc())
	    {
		$aRetVal[] = $oCurRow['iata'];
	    }
	
	$oMysqli->close();
	
	return $aRetVal;
    }

/*
 * compile string array recursive
*/
function Arr2Str($strpre,$array) {
    $sTmp = '';

    if(count($array)>0)
    foreach($array as $key => $element) {
	$sTmp .= $key . ': ';
	if (is_array($element) or is_object($element)) {
	    $sTmp = Arr2Str('',$element);
	}
	else {
	    if ($element != '') {
		$sTmp .= $element . '; ';
	    }
	}
    }
    
    if ($sTmp != '')
	$sTmp = $strpre . $sTmp;
    
    return $sTmp;
}

/*
function GetCompanyFormShortName($oDBHandler,$FormID)
{
    $sSearchQuery = "SELECT short_name
                        FROM ".DB_JUR_FORM_TABLE."
                        WHERE id= $FormID";

    $oSearchResult = $oDBHandler->query($sSearchQuery);
    if (IS_DEBUG) echo $sSearchQuery, '<br><br>';
    if ($oDBHandler->affected_rows > 0) {
        $oRow = $oSearchResult->fetch_assoc();
        return $oRow["short_name"];
    }
    else
    {
        return '';
    }
}*/
?>