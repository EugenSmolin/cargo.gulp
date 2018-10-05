<?php

//require '../services.php';
//require_once '../abstract_calc.php';
//require_once '../ccodes.php';
require_once 'ccodes.php';
require_once 'abstract_calc.php';

///////////////////////////////////////////////////

class calculator_adelservice extends AbstractCalculator
    {
		const name		= 'Адель Сервис';
		const site		= 'http://express-dostawka.com.ua';
		const logo		= SITE_URL_FOR_LOGO_BRAND_IMAGES.'logo-adelservice.png';
		const language		= 'RU';
		const currency		= 'UAH';
	
		public $oDerivals	= array('UA');
		public $oArrivals	= array('UA', 'RU');

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
			
			$outResultMethods = array();
	
			//////////////////////////////////////////////////

                        $iCoeff = 0;
                        
                        // calculate coeff
                        if (($weight > 0) and ($weight <= 4))
                            $iCoeff = 500;
                        else if (($weight >= 5) and ($weight < 10))
                            $iCoeff = 130;
                        else if (($weight >= 10) and ($weight < 150))
                            $iCoeff = 100;
                        else if (($weight >= 150) and ($weight < 300))
                            $iCoeff = 80;
                        else if (($weight >= 300) and ($weight < 500))
                            $iCoeff = 66;
                        else if (($weight >= 500) and ($weight < 800))
                            $iCoeff = 58;
                        else
                            return DropCalculation ();

                        // calc data
                        $aCalcOptions = array(
                            "wpcc_structure_inputtext[3]" => $weight, 
                            "wpcc_structure[4]" => $iCoeff, 
                            "wpcc_structure_id" => "4", 
                            "wpcc_this_id" => "1", 
                            "wpcc_calculate" => "Расчитать"
                            );
                        
                        // calc
                        $sResURL = "http://express-dostawka.com.ua/company/calculator";
                        $oResCurl = curl_init($sResURL);
                        curl_setopt($oResCurl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($oResCurl, CURLOPT_POST, TRUE);
                        //curl_setopt($oInitCurl, CURLOPT_HEADER, TRUE);
                        //curl_setopt($oResCurl, CURLOPT_FOLLOWLOCATION, TRUE);
                        //curl_setopt($Calc_curl, CURLOPT_PROXY, PROXY);
                        //curl_setopt($Calc_curl, CURLOPT_BINARYTRANSFER, TRUE);
                        curl_setopt($oResCurl, CURLOPT_POSTFIELDS, $aCalcOptions);
                        //curl_setopt($Calc_curl, CURLOPT_HTTPHEADER, $calcHeadOptions);
                        //curl_setopt($oResCurl, CURLOPT_COOKIE, 'liveBetaModalshown=yes;' . $sJSESSION2);
                        $sResAnswer = curl_exec($oResCurl);

                        // parse result
                        $DOMResult = new DOMDocument();
                        @$DOMResult->loadHTML($sResAnswer);
                        @$xpath = new DOMXPath($DOMResult);
                        $oResultPrice = $xpath->query('//div[@class="wpcc_result_block wpcc_result_block_1"]/div[@class="wpcc_result wpcc_result_1"]');
                                                
                        $fPrice = floatval(preg_replace('/[^\d\.]+/','',$oResultPrice->item(0)->textContent));

                        if ($fPrice > 0)
                            {
                                $_names = __GetAllTranslations('Смешанный',$this::language);
                                $_calcResultPrice = $fPrice;
                                $_calcResultPrices = GetConvertedPrices($_calcResultPrice,$this::currency);
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

$transports[132] = array(
	'name' => calculator_adelservice::name,
	'site' => calculator_adelservice::site,
	'logo' => calculator_adelservice::logo,
	'calcfunc' => 'adelservice_calc',
	'language' => calculator_adelservice::language,
	'currency' => calculator_adelservice::currency,
	'classname' => 'calculator_adelservice'
	);
	
////////////////////////////////////////////

/*
$from = "kiyev";
$to = "moscow";
$weight = 19;
$vol = 0.25;
$insPrice = 0;

adelservice_calc($from,$to,$weight,$vol,$insPrice,"en","en","UAH","RUB", "UA", "RU", "", "");
*/

////////////////////////////////////////////

function adelservice_calc($from,$to,$weight,$vol,$insPrice,$client_lang,$transport_lang, $base_curr, $client_curr,
		 $cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo)
    {

		$calc = new calculator_adelservice();
		$calc->Calculate($from,$to,$weight,$vol,$insPrice,$client_lang,$client_curr,$cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo);
		
		exit(0);

    }

/////////////////////////////////////////////

?>
