<?php

//require '../services.php';
//require_once '../abstract_calc.php';
//require_once '../ccodes.php';
require_once 'ccodes.php';
require_once 'abstract_calc.php';

///////////////////////////////////////////////////

class calculator_2GO extends AbstractCalculator
    {
		const name		= '2GO Supply Chain';
		const site		= 'http://supplychain.2go.com.ph';
		const logo		= 'http://supplychain.2go.com.ph/images/layout/supplychain_logo.png';
		const language		= 'EN';
		const currency		= 'PHP';
	
		public $oDerivals	= array('PH');
		public $oArrivals	= array('*');
	
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
			    if (!(in_array($cargoCountryFromU,$this->oDerivals)))
				return DropCalculation();
	
			// if target country not in Arrivals -- exit
			if (!($this->oArrivals[0] == '*'))
			    if (!(in_array($cargoCountryToU,$this->oArrivals)))
				return DropCalculation();
	
			$fDim = round(pow($vol,1/3) * 100,2);

			if($isActiveLineParams==0)
			{
				$width = $fDim;
				$length =  $fDim;
				$height = $fDim;
			}

			$insPrice = (intval($insPrice) == 0 ? 1 : $insPrice);

			$outResultMethods = array();
	
			//////////////////////////////////////////////////
			
			// detect type of cargo
			if ($cargoCountryToU != 'PH')
				{
					// INTERNATIONAL cargo
				
					// request for countries for intl
					
					$sInitURL = 'http://supplychain.2go.com.ph/CustomerSupport/tools/quote_int.asp';
					$oInitCurl = curl_init($sInitURL);
					curl_setopt($oInitCurl, CURLOPT_RETURNTRANSFER, 1);
					//curl_setopt($Calc_curl, CURLOPT_POST, TRUE);
					//curl_setopt($oInitCurl, CURLOPT_HEADER, TRUE);
					//curl_setopt($Calc_curl, CURLOPT_PROXY, PROXY);
					//curl_setopt($Calc_curl, CURLOPT_BINARYTRANSFER, TRUE);
					//curl_setopt($Calc_curl, CURLOPT_POSTFIELDS, $calcPOSTOptions);
					//curl_setopt($Calc_curl, CURLOPT_HTTPHEADER, $calcHeadOptions);
					//curl_setopt($Calc_curl, CURLOPT_COOKIE, $aInpCookies[1]);
					$sInitAnswer = curl_exec($oInitCurl);
					
					// fetch cookie
					//preg_match('/^Set-Cookie:\s*([^;]*)/mi', $sInitAnswer, $aInitCookies);
					
					//print($sInitAnswer);
					//exit(0);
								
					// lets parse countries
			
					$oDOMResult = new DOMDocument();
					@$oDOMResult->loadHTML($sInitAnswer);
					@$oXPath = new DOMXPath($oDOMResult);
					$oCountries = $oXPath->query('//select[@name="destination"]/option');
			
					if ($oCountries->length < 1)
							return DropCalculation();
					 
					$sSelCountry = '';
					foreach($oCountries as $oCountry)
						{
							$sCountry = preg_replace('/\(.*\)/','',$oCountry->textContent);
							$sCountry = str_replace('*','',$sCountry);
							$oParsedCountries = explode(',',$sCountry);
							$sCountry = $oParsedCountries[0];
							
							if (strtoupper(trim($sCountry)) == strtoupper($aCCodes[$cargoCountryToU]))
								$sSelCountry = $oCountry->getAttribute('value');
						}
		
					if ($sSelCountry == '')
						return DropCalculation();
					
					// request to calc
					$aCalcOptions = array(
							"package_type" => "PX",
							"destination" => $sSelCountry,
							"actual_wt" => $weight,
							"aw_unit" => "kg",
							"declared_value" => $insPrice,
							"length" => $length,
							"l_unit" => "cm",
							"width" => $width,
							"w_unit" => "cm",
							"height" => $height,
							"h_unit" => "cm"
						);
						
					$sResURL = "http://supplychain.2go.com.ph/CustomerSupport/tools/quote_int_rates.asp";
					
					$sResData = http_build_query($aCalcOptions);
										
					$sCurlExec = "curl 'http://supplychain.2go.com.ph/CustomerSupport/tools/quote_int_rates.asp' " .
							"--data '" . $sResData . "' --compressed -s";

					exec($sCurlExec, $sTempOut);
				
					$sResAnswer = "";
				
					foreach($sTempOut as $str)
					    {
							$sResAnswer .= $str . PHP_EOL;
					    }

					// lets parse

					$oDOMResult = new DOMDocument();
					@$oDOMResult->loadHTML($sResAnswer);
					@$oXPath = new DOMXPath($oDOMResult);
					$oResultTime = $oXPath->query('//table[2]/tr[6]/td[2]');
					$oResultPrice = $oXPath->query('//table[3]/tr[4]/td[2]');
					
					$sTime = $oResultTime->item(0)->textContent;
					$fPrice = floatval(trim(str_replace(array('PHP',','),'',strtoupper($oResultPrice->item(0)->textContent))));
					
					/////////////////////////////////////////
					// form results
					
					$_names = __GetAllTranslations("Mixed",$this::language);
					$_calcResultPrice = floatval($fPrice);
					$_calcResultPrices = GetConvertedPrices($fPrice,$this::currency);
					$_calcResultTimes = __GetAllTranslations($sTime,$this::language);
					
					$outResultMethods[] = array(
						    'name' => $_names[$clientLang],
						    'names' => $_names,
						    'calcResultPrice' => $_calcResultPrices[$clientCurr],
						    'calcResultPrices' => $_calcResultPrices,
						    'calcResultTime' => $_calcResultTimes[$clientLang],
						    'calcResultTimes' => $_calcResultTimes
						);
			}
			
			if (count($outResultMethods) < 1)
			    return DropCalculation();
	
			$outResultArray['cities']['derival'] = __GetAllTranslations($from,$this::language);
			$outResultArray['cities']['arrival'] = __GetAllTranslations($to,$this::language);
			$outResultArray['cityFrom'] = $outResultArray['cities']['derival'][$clientLang]; //GetTranslation($aSrcCity[$sSrcIdx],$transport_lang,$client_lang);
			$outResultArray['cityTo'] = $outResultArray['cities']['arrival'][$clientLang]; //GetTranslation($aDstCity[$sDstIdx],$transport_lang,$client_lang);
			$outResultArray['methods'] = $outResultMethods;
		
			return $outResultArray;
		}
    }
    
//////////////////////////////////////////////////////

$transports[109] = array(
	'name' => calculator_2GO::name,
	'site' => calculator_2GO::site,
	'logo' => calculator_2GO::logo,
	'calcfunc' => 'TOGO_calc',
	'language' => calculator_2GO::language,
	'currency' => calculator_2GO::currency,
	'classname' => 'calculator_2GO'
	);
	
////////////////////////////////////////////

/*
$from = "Manila";
$to = "Moscow";
$weight = 12;
$vol = 1;
$insPrice = 0;

TOGO_calc($from,$to,$weight,$vol,$insPrice,"en","en","PHP","RUB", "PH", "RU", "", "");
*/

////////////////////////////////////////////

function TOGO_calc($from,$to,$weight,$vol,$insPrice,$client_lang,$transport_lang, $base_curr, $client_curr,
		 			$cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo,$isActiveLineParams,
				   $width, $length, $height,  $options = [])
    {

		$calc = new calculator_2GO;
		$calc->Calculate($from,$to,$weight,$vol,$insPrice,$client_lang,$client_curr,
				$cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo,
				$isActiveLineParams, $width, $length, $height,  $options = []);
		
		exit(0);

    }

/////////////////////////////////////////////

?>
