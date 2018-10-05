<?php

//require '../services.php';
//require_once '../abstract_calc.php';
//require_once '../ccodes.php';
require_once 'abstract_calc.php';
//require_once 'ccodes.php';

///////////////////////////////////////////////////

class calculator_air21 extends AbstractCalculator
    {
	const name		= 'AIR21';
	const site		= 'http://www.air21.com.ph';
	const logo		= 'http://www.air21.com.ph/main/images/h_head_logo.jpg';
	const language		= 'EN';
	const currency		= 'PHP';

	public $oDerivals	= array('PH');
	public $oArrivals	= array('PH');

	public function Calculate($from,$to,$weight,$vol,$insPrice,$clientLang,
							  $clientCurr,$cargoCountryFrom,$cargoCountryTo,
							  $cargoStateFrom,$cargoStateTo,
							  $isActiveLineParams, $width, $length, $height,
							  $options = [])
	{
	    
		global $aCCodes, $aFCodes;

		mb_internal_encoding("UTF-8");
		mb_regex_encoding("UTF-8");
		date_default_timezone_set('UTC');
		
		if ($this::currency != $clientCurr)
		    {
			$cvt_curr = GetConvertedPrices($insPrice,$clientCurr);
			$insPrice = $cvt_curr[$this::currency];
		    }

		$cargoCountryFromU = mb_strtoupper($cargoCountryFrom);
		$cargoCountryToU = mb_strtoupper($cargoCountryTo);

		// if source country not in Derivals -- exit
		if ((!(in_array($cargoCountryFrom,$this->oDerivals))) and (!(in_array('*',$this->oDerivals))))
		    return DropCalculation();

		// if target country not in Arrivals -- exit
		if ((!(in_array($cargoCountryTo,$this->oArrivals))) and (!(in_array('*',$this->oArrivals))))
		    return DropCalculation();

		// prepare for request
		$from = mb_strtoupper($from);
		$to = mb_strtoupper($to);
		
		$fDim = round(pow($vol,1/3) * 100,2);

		if($isActiveLineParams==0)
		{
			$width = $fDim;
			$length =  $fDim;
			$height = $fDim;
		}
		//////////////////////////////////////////////////////////

		$sInitProvincesURL = "http://www.air21.com.ph/developers/rates.php";
		$sInitProvincesAnswer = file_get_contents_proxy($sInitProvincesURL, PROXY);
		
		$iDerivalRegionIdx = 0;
		$iDerivalCityIdx = 0;
		$iArrivalCityIdx = 0;
		
		// provinces list
		$DOMResult = new DOMDocument();
		@$DOMResult->loadHTML($sInitProvincesAnswer);
		@$xpath = new DOMXPath($DOMResult);
		$oDerivalsParseRes = $xpath->query('//select[@name="origin_id"]/option');
		$oArrivalsParseRes = $xpath->query('//select[@name="destination_id"]/option');

		if (($oDerivalsParseRes == false) or ($oArrivalsParseRes == false))
		    return DropCalculation();
		
		// derival provinces
		foreach($oDerivalsParseRes as $item)
			{
				$iProvince = intval($item->getAttribute("value"));
				$sReqCitiesURL = "http://www.air21.com.ph/developers/sublocations.php?country=" . $iProvince;
				
				$sAnswerCities = file_get_contents_proxy($sReqCitiesURL, PROXY);
				
				// derival cities list
				$DOMResult = new DOMDocument();
				@$DOMResult->loadHTML($sAnswerCities);
				@$xpath = new DOMXPath($DOMResult);
				$oDerivalsCitiesRes = $xpath->query('//select[@name="suborigin_id"]/option');
			
				if ($oDerivalsCitiesRes == false)
				    return DropCalculation();
				
				foreach($oDerivalsCitiesRes as $oItem)
					{
						$iCurrentCityIdx = $oItem->getAttribute("value");
						$sDerivalCityName = $oItem->textContent;
						
						$sDerivalCityName = trim(preg_replace("/Manila\s.*/","Manila",$sDerivalCityName));
						$sDerivalCityName = mb_strtoupper(trim(preg_replace("/\(.*\)/","",$sDerivalCityName)));
						
						if ($sDerivalCityName == $from)
							{
								$iDerivalCityIdx = $iCurrentCityIdx;
								$iDerivalRegionIdx = $iProvince;
							}
					}
			}
			
		// arrival provinces
		foreach($oArrivalsParseRes as $item)
			{
				$iProvince = intval($item->getAttribute("value"));
				$sReqCitiesURL = "http://www.air21.com.ph/developers/sublocations2.php?country=" . $iProvince;
				
				$sAnswerCities = file_get_contents_proxy($sReqCitiesURL, PROXY);
				
				// arrival cities list
				$DOMResult = new DOMDocument();
				@$DOMResult->loadHTML($sAnswerCities);
				@$xpath = new DOMXPath($DOMResult);
				$oArrivalsCitiesRes = $xpath->query('//select[@name="subdestination_id"]/option');
			
				if ($oArrivalsCitiesRes == false)
				    return DropCalculation();
				
				foreach($oArrivalsCitiesRes as $oItem)
					{
						$iCurrentCityIdx = $oItem->getAttribute("value");
						$sArrivalCityName = $oItem->textContent;
						
						$sArrivalCityName = trim(preg_replace("/Manila\s.*/","Manila",$sArrivalCityName));
						$sArrivalCityName = mb_strtoupper(trim(preg_replace("/\(.*\)/","",$sArrivalCityName)));
						
						if ($sArrivalCityName == $to)
							{
								$iArrivalCityIdx = $iCurrentCityIdx;
								$iArrivalRegionIdx = $iProvince;
							}
					}
			}
			
		if (($iArrivalCityIdx == 0) or ($iArrivalRegionIdx == 0) or ($iDerivalCityIdx == 0) or ($iDerivalRegionIdx == 0))
			return DropCalculation();
		
		// lets calc
		
		// weight correction
		$fVolWeight = 285.71 * $vol;
		$weight = max($weight, $fVolWeight);
		
		$oCalcPOSTOptions = array(
				"package_id" => 3,
				"length" => $length,
				"width" => $width,
				"height" => $height,
				"weight" => $weight,
				"origin_id" => $iDerivalRegionIdx,
				"suborigin_id" => $iDerivalCityIdx,
				"destination_id" => $iArrivalRegionIdx,
				"subdestination_id" => $iArrivalCityIdx,
				"declared_value" => $insPrice,
				"submit" => "save",
				"submit.x" => 61,
				"submit.y" => 24
			);
		
		$sCalcURL = 'http://www.air21.com.ph/developers/rates.php';
		$oCalcCurl = curl_init($sCalcURL);
		curl_setopt($oCalcCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCalcCurl, CURLOPT_POST, TRUE);
		//curl_setopt($Calc_curl, CURLOPT_HEADER, TRUE);
		//curl_setopt($Calc_curl, CURLOPT_PROXY, PROXY);
		//curl_setopt($Calc_curl, CURLOPT_BINARYTRANSFER, TRUE);
		curl_setopt($oCalcCurl, CURLOPT_POSTFIELDS, $oCalcPOSTOptions);
		//curl_setopt($Calc_curl, CURLOPT_HTTPHEADER, $calcHeadOptions);
		//curl_setopt($Calc_curl, CURLOPT_COOKIE, $aInpCookies[1]);
		$sCalcAnswer = curl_exec($oCalcCurl);
		
		// parse result
		$DOMResult = new DOMDocument();
		@$DOMResult->loadHTML($sCalcAnswer);
		@$xpath = new DOMXPath($DOMResult);
		$parseRes = $xpath->query('//div[@id="results"]/table/tr[2]/td[3]');

		if ($parseRes == false)
		    return DropCalculation();

		$fPrice = floatval(trim(str_replace(",","",$parseRes->item(0)->textContent)));

		$outResultMethods = array();

		if ($fPrice > 0)
			{
				$_names = __GetAllTranslations('Mixed transport',$this::language);
				$_calcResultPrice = $fPrice;
				$_calcResultPrices = GetConvertedPrices($fPrice,$this::currency);
				$_calcResultTimes = __GetAllTranslations('',$this::language);
				
				$outResultMethods[] = array(
				    'name' => $_names[$clientLang],
				    'names' => $_names,
				    'calcResultPrice' => $_calcResultPrices[$clientCurr],
				    'calcResultPrices' => $_calcResultPrices,
				    'calcResultTime' => $_calcResultTimes[$clientLang],
				    'calcResultTimes' => $_calcResultTimes
				    );
			}

		$outResultArray['cities']['derival'] = __GetAllTranslations($from,$this::language);
		$outResultArray['cities']['arrival'] = __GetAllTranslations($to,$this::language);
		$outResultArray['cityFrom'] = $outResultArray['cities']['derival'][$clientLang];
		$outResultArray['cityTo'] = $outResultArray['cities']['arrival'][$clientLang]; 
		$outResultArray['methods'] = $outResultMethods;

		return $outResultArray;
	}
    }

//////////////////////////////////////////////////////

$transports[7] = array(
	'name' => calculator_AIR21::name,
	'site' => calculator_AIR21::site,
	'logo' => calculator_AIR21::logo,
	'calcfunc' => 'AIR21_calc',
	'language' => calculator_AIR21::language,
	'currency' => calculator_AIR21::currency,
	'classname' => 'calculator_AIR21'
	);
	
////////////////////////////////////////////

/*
$from = "Manila";
$to = "hermosa";
$weight = 14;
$vol = 0.9;
$insPrice = 5200;

AIR21_calc($from,$to,$weight,$vol,$insPrice,"en","en","USD","USD",'PH','PH','','');
*/

////////////////////////////////////////////

function AIR21_calc($from,$to,$weight,$vol,$insPrice,$client_lang,$transport_lang, $base_curr, $client_curr,
		 $cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo)
    {

	$calc = new calculator_AIR21;
	$calc->Calculate($from,$to,$weight,$vol,$insPrice,$client_lang,$client_curr,$cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo);
	
	exit(0);

    }

?>
