<?php

//require '../services.php';
//require_once '../abstract_calc.php';
require_once 'ccodes.php';
require_once 'abstract_calc.php';

///////////////////////////////////////////////////

class calculator_AACARGO extends AbstractCalculator
    {
	const name		= 'American Airlines Cargo';
	const site		= 'https://www.aacargo.com';
	const logo		= 'https://www.aacargo.com/resources/images/aac-logo.png';
	const language		= 'EN';
	const currency		= 'USD';

	public $oDerivals	= array('US');
	public $oArrivals	= array('US');

	public function Calculate($from,$to,$weight,$vol,$insPrice,$clientLang,
							  $clientCurr,$cargoCountryFrom,$cargoCountryTo,
							  $cargoStateFrom,$cargoStateTo,
							  $isActiveLineParams, $width, $length, $height,
							  $options = [])
	    {

		global	$aFCodes, $aCCodes;

		mb_internal_encoding("UTF-8");
		mb_regex_encoding("UTF-8");
		date_default_timezone_set('UTC');

		$from = mb_strtoupper($from);
		$to = mb_strtoupper($to);

		if ($this::currency != $clientCurr)
		    {
			$cvt_curr = GetConvertedPrices($insPrice,$clientCurr);
			$insPrice = $cvt_curr[$this::currency];
		    }

		$cargoCountryFromU = mb_strtoupper($cargoCountryFrom);
		$cargoCountryToU = mb_strtoupper($cargoCountryTo);

		// if source country not in Derivals -- exit
		if (!($this->oDerivals[0] == '*'))
		    if (!(in_array($cargoCountryFrom,$this->oDerivals)))
			return DropCalculation();

		// if target country not in Arrivals -- exit
		if (!($this->oArrivals[0] == '*'))
		    if (!(in_array($cargoCountryTo,$this->oArrivals)))
			return DropCalculation();

		$fDim = round(pow($vol,1/3) * 100,2);

		//////////////////////////////////////////////////
		$sDerivalIdx = "";
		$sArrivalIdx = "";
		//////////////////////////////////////////////////
		
		// request for Airports
		
		$sPortsURL = "https://www.aacargo.com/AACargo/rateCalculator";
		$sPortsAnswer = file_get_contents($sPortsURL);
		
		// lets parse
		$oDOMResult = new DOMDocument();
		@$oDOMResult->loadHTML($sPortsAnswer);
		@$oXPath = new DOMXPath($oDOMResult);
		$oParseDerivalPorts = $oXPath->query('//select[@id="originAirports"]/option');
		$oParseArrivalPorts = $oXPath->query('//select[@id="destAirports"]/option');
		
		if ((!$oParseDerivalPorts) or (!$oParseArrivalPorts))
		  return DropCalculation();
		  
		// derival
		foreach($oParseDerivalPorts as $oPort)
			{
				$sPortName = $oPort->textContent;
				$sPortNameParse0 = explode('-',$sPortName);
				$sPortNameParse1 = explode(',',$sPortNameParse0[0]);
				$sPortNameParse2 = explode('/',$sPortNameParse1[0]);
				$sPortNameParse3 = trim(preg_replace("/\(.*\)/","",trim($sPortNameParse2[0])));
				
				@$sPortStateName = trim($sPortNameParse1[1]);
				
				if ($from == strtoupper($sPortNameParse3) and ($cargoStateFrom == $sPortStateName))
					{
						$sDerivalIdx = $oPort->getAttribute("value");
						break;
					}
			}
			
		// arrival
		foreach($oParseArrivalPorts as $oPort)
			{
				$sPortName = $oPort->textContent;
				$sPortNameParse0 = explode('-',$sPortName);
				$sPortNameParse1 = explode(',',$sPortNameParse0[0]);
				$sPortNameParse2 = explode('/',$sPortNameParse1[0]);
				$sPortNameParse3 = trim(preg_replace("/\(.*\)/","",trim($sPortNameParse2[0])));
				
				@$sPortStateName = trim($sPortNameParse1[1]);
				
				if ($to == strtoupper($sPortNameParse3) and ($cargoStateTo == $sPortStateName))
					{
						$sArrivalIdx = $oPort->getAttribute("value");
						break;
					}
			}
			
		if (($sDerivalIdx == "") or ($sArrivalIdx == ""))
			return DropCalculation();
		
		/////////////////////////////////////
		// get tariffs
		
		$sCalcURL = "https://www.aacargo.com/AACargo/newUserAccounts/ppsRateDetail";
		
		$sReq = "curl 'https://www.aacargo.com/AACargo/newUserAccounts/ppsRateDetail' " .
				"--data 'originAirportCode=" . $sDerivalIdx . "&destAirportCode=" . $sArrivalIdx. "&shipmentType=2&military=false' -s";		
		exec($sReq,$sOutRes);
		$sCurlResult = "";

		foreach($sOutRes as $sStr)
			$sCurlResult .= $sStr;	
		
		$oTariffs = json_decode($sCurlResult);
		
		if ((!isset($oTariffs->rate1)) or (!isset($oTariffs->rate4)) or (!isset($oTariffs->rate5)) or (!isset($oTariffs->tax)))
			return DropCalculation();
		
		// counting
		$limit1 = KgFromLbs(50);
		$limit2 = KgFromLbs(70);
		$limit3 = KgFromLbs(100);
		$fPrice = 0;
		
		if ($weight > $limit3)
			$fPrice = (ceil($weight / $limit3) * $oTariffs->rate5 * weight) + $oTariffs->tax;
		else if (($weight < $limit3) and ($weight > $limit2))
			$fPrice = $oTariffs->rate4 * $weight + $oTariffs->tax;
		else if ($weight < $limit2)
			$fPrice = $oTariffs->rate1 * $weight + $oTariffs->tax;
		else
			return DropCalculation();
		
		/////////////////////////////////////////
		// form results
		  
		$outResultMethods = array();
		$outResultArray = array();
		
		$_names = __GetAllTranslations("Air transport",$this::language);
		$_calcResultPrice = floatval($fPrice);
		$_calcResultPrices = GetConvertedPrices($fPrice,$this::currency);
		$_calcResultTimes = __GetAllTranslations("",$this::language);
		
		$outResultMethods[] = array(
			    'name' => $_names[$clientLang], //GetTranslation($method->cargoTypeName,$transport_lang,$client_lang),
			    'names' => $_names, //GetAllTranslations($method->cargoTypeName,$transport_lang),
			    'calcResultPrice' => $_calcResultPrices[$clientCurr], //floatval($method->destFreight),
			    'calcResultPrices' => $_calcResultPrices, //GetConvertedPrices(floatval($method->destFreight),$base_curr),
			    'calcResultTime' => $_calcResultTimes[$clientLang], //GetTranslation($method->deliverTime,$transport_lang,$client_lang),
			    'calcResultTimes' => $_calcResultTimes //GetAllTranslations($method->deliverTime,$transport_lang)
			);
			
		if (count($outResultMethods) < 1)
		    return DropCalculation();

		$outResultArray['cities']['derival'] = __GetAllTranslations($from,$this::language);
		$outResultArray['cities']['arrival'] = __GetAllTranslations($to,$this::language);
		$outResultArray['cityFrom'] = $outResultArray['cities']['derival'][$clientLang]; 
		$outResultArray['cityTo'] = $outResultArray['cities']['arrival'][$clientLang]; 
		$outResultArray['methods'] = $outResultMethods;

		return $outResultArray;
	}
    }
    
//////////////////////////////////////////////////////

$transports[1] = array(
	'name' => calculator_AACARGO::name,
	'site' => calculator_AACARGO::site,
	'logo' => calculator_AACARGO::logo,
	'calcfunc' => 'AACARGO_calc',
	'language' => calculator_AACARGO::language,
	'currency' => calculator_AACARGO::currency,
	'classname' => 'calculator_AACARGO'
	);
	
////////////////////////////////////////////

/*
$from = "New York";
$to = "Washington";
$weight = 100;
$vol = 1;
$insPrice = 0;

AACARGO_calc($from,$to,$weight,$vol,$insPrice,"en","en","USD","USD", "US", "US", "NY", "MI");
*/

////////////////////////////////////////////

function AACARGO_calc($from,$to,$weight,$vol,$insPrice,$client_lang,$transport_lang, $base_curr, $client_curr,
		 $cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo)
    {

	$calc = new calculator_AACARGO;
	$calc->Calculate($from,$to,$weight,$vol,$insPrice,$client_lang,$client_curr,$cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo);
	
	exit(0);

    }

/////////////////////////////////////////////

?>
