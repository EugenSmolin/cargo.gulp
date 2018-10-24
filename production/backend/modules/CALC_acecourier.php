<?php

//require '../services.php';
//require_once '../abstract_calc.php';
//require_once '../ccodes.php';
require_once 'ccodes.php';
require_once 'abstract_calc.php';

///////////////////////////////////////////////////

class calculator_ACECOURIER extends AbstractCalculator
    {
		const name		= 'ACE Courier Services';
		const site		= 'http://www.acecourier.bc.ca';
		const logo		= 'http://www.acecourier.bc.ca/wp-content/uploads/2015/01/cropped-cropped-top.jpg';
		const language		= 'EN';
		const currency		= 'CAD';
	
		public $oDerivals	= array('CA');
		public $oArrivals	= array('CA');

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
	
			$fDim = round(pow(CubicInchesFromCubicMeters($vol),1/3) * 100,2);
                        $weight = round(LbsFromKg($weight),2);
			
			$outResultMethods = array();
	
			//////////////////////////////////////////////////
			
                        $sReqOrigin = "";
                        $sReqDestination = "";

                        // fetch origin
                        $sOriginListURL = 'http://www.acecourier.bc.ca/rates_calculator.php?action=rate_list&service_level=regular&rate_list=british_columbia';
                        $oOriginList = json_decode(file_get_contents($sOriginListURL));
                        
                        foreach ($oOriginList->location as $sOrigin)
                            {
                                $sMOrigin = preg_replace('/\(.*\)/','',strtoupper((trim($sOrigin))));
                                if (trim($sMOrigin) == strtoupper(trim($from)))
                                    $sReqOrigin = $sOrigin;
                            }

                        // fetch destination
                        $sDestinationListURL = 'http://www.acecourier.bc.ca/rates_calculator.php?' .
                                    'action=rate_list&service_level=regular&rate_list=british_columbia&' .
                                    'source=' . urlencode($sReqOrigin);
                        $oDestinationList = json_decode(file_get_contents($sDestinationListURL));
                        
                        foreach ($oDestinationList->location as $sDestination)
                            {
                                $sMDestination = preg_replace('/\(.*\)/','',strtoupper((trim($sDestination))));
                                if (trim($sMDestination) == strtoupper(trim($to)))
                                    $sReqDestination = $sDestination;
                            }
                            
                        if (($sReqOrigin == "") or ($sReqDestination == ""))
                            return DropCalculation ();
                            
                        /////////////////////////////////////	
                        // fetch calculation

                        $sReqCalcURL = 'http://www.acecourier.bc.ca/rates_calculator.php?action=rate&' .
                                'rate_list=british_columbia&fee_dangerous_goods=0&fee_power_tail_gate=0&' .
                                'fee_residential=0&fee_cod=0&fee_appointment=0&' .
                                'location_from=' . urlencode($sReqOrigin) . 
                                '&location_to=' . urlencode($sReqDestination) .
                                '&service_level=regular&envelopes_only=0&weight_total=' . $weight . 
                                '&custom_modifier=false';
                        $sReqCalcAnswer = file_get_contents($sReqCalcURL);
                        
                        $oCalcAnswer = json_decode($sReqCalcAnswer);
                        
                        $fPrice = floatval($oCalcAnswer->total);

                        if ($fPrice > 0)
                            {
                                $_names = __GetAllTranslations('Mixed',$this::language);
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

$transports[121] = array(
	'name' => calculator_ACECOURIER::name,
	'site' => calculator_ACECOURIER::site,
	'logo' => calculator_ACECOURIER::logo,
	'calcfunc' => 'ACECOURIER_calc',
	'language' => calculator_ACECOURIER::language,
	'currency' => calculator_ACECOURIER::currency,
	'classname' => 'calculator_ACECOURIER'
	);
	
////////////////////////////////////////////

/*
$from = "Clearwater";
$to = "Abbotsford";
$weight = 2;
$vol = 0.25;
$insPrice = 560;

ACECOURIER_calc($from,$to,$weight,$vol,$insPrice,"en","en","CAD","CAD", "CA", "CA", "", "");
*/

////////////////////////////////////////////

function ACECOURIER_calc($from,$to,$weight,$vol,$insPrice,$client_lang,$transport_lang, $base_curr, $client_curr,
		 $cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo)
    {

		$calc = new calculator_ACECOURIER();
		$calc->Calculate($from,$to,$weight,$vol,$insPrice,$client_lang,$client_curr,$cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo);
		
		exit(0);

    }

/////////////////////////////////////////////

?>
