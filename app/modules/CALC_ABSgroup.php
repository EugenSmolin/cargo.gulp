<?php

//require '../services.php';
//require_once '../abstract_calc.php';
require_once 'abstract_calc.php';

///////////////////////////////////////////////////

class calculator_ABSGroup extends AbstractCalculator
    {
		const name		= 'ABS group';
		const site		= 'http://www.absl.kz';
		const logo		= 'http://absl.kz/incom/template/template1/images/logo.jpg';
		const language		= 'RU';
		const currency		= 'KZT';
	
		public $oDerivals	= array('RU', 'KZ', 'BY');
		public $oArrivals	= array('KZ');

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
				$clientLang = mb_strtolower($clientLang);
		
				if ($this::currency != $clientCurr)
				    {
						$cvt_curr = GetConvertedPrices($insPrice,$clientCurr);
						//print_r($cvt_curr);
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
				//////////////////////////////////////////////////
						
				// first of all, we need to fetch cities
				// 'from' city first
			
				$citiesURL = "http://absl.kz/ru/calculator.php#result";
			
				// form calculation request
				$citiesHTML = file_get_contents($citiesURL);
                                //print($citiesHTML); die();
			
				// convert to html ent
				//$citiesToPageHE = mb_convert_encoding($citiesToHTMLAnswer, "UTF-8", 'cp1251');
                                $iDerivalIdx = 0;
                                $iArrivalIdx = 0;

				// fetch js with cities list
				$DOMResult = new DOMDocument();
				@$DOMResult->loadHTML($citiesHTML);
				@$xpath = new DOMXPath($DOMResult);
				$parseRes = $xpath->query('//select[@name="filialA"]/option');
                                
				if ($parseRes == false)
				    return DropCalculation();
			
                                // detect cities
                                                                
				foreach($parseRes as $element)
				    {
					$cityVal = $element->textContent;
					if (trim(mb_strtoupper($cityVal)) == $from)
					    $iDerivalIdx = $element->getAttribute('value');
				    }
			                                    
				// source cities collected
			
				$DOMResult = new DOMDocument();
				@$DOMResult->loadHTML($citiesHTML);
				@$xpath = new DOMXPath($DOMResult);
				$parseRes = $xpath->query('//select[@name="filialC"]/option');
						
				foreach($parseRes as $element)
				    {
					$cityVal = $element->textContent;
                                        
                                        $cityVal = trim(mb_strtoupper(preg_replace('/\(.*\)/', '', $cityVal)));

					if ($cityVal == $to)
					    $iArrivalIdx = $element->getAttribute('value');
				    }
			
				
				if (($iArrivalIdx == 0) or ($iDerivalIdx == 0))
				    return DropCalculation();
			
			
				$calcURL = "http://absl.kz/ru/calculator.php";
			
				// form calculation request
				$calcParams = array(
							'filialA' => $iDerivalIdx,
                                                        'filialB' => '',
							'filialC' => $iArrivalIdx,
							'ves' => $weight,
							'ob' => $vol,
							'Action' => 'РАССЧИТАТЬ',
						    );
				$calcReq = curl_init();
				curl_setopt_array($calcReq, array(
						    CURLOPT_URL => $calcURL,
						    CURLOPT_POST => TRUE,
						    CURLOPT_RETURNTRANSFER => TRUE,
						    CURLOPT_POSTFIELDS => $calcParams
						));
				
				$calcHTMLAnswer = curl_exec($calcReq);
			
				// fetch js with cities list
				$DOMResult = new DOMDocument();
				@$DOMResult->loadHTML($calcHTMLAnswer);
				@$xpath = new DOMXPath($DOMResult);
				$parseRes = $xpath->query('//div[@class="result_box"]');
			
				if ($parseRes == false)
				    return DropCalculation();
			
				$realTotal = $parseRes->item(0)->textContent;
				$rtSplit1 = explode(':',$realTotal);
				@$realPrice = floatval(trim($rtSplit1[1]));
                                			
				// if no one section exists
				if ($realPrice < 100)
				    return DropCalculation();


				$_names = __GetAllTranslations("Смешанный",$this::language);
				$_calcResultPrice = floatval($realPrice);
				$_calcResultPrices = GetConvertedPrices($realPrice,$this::currency);
				$_calcResultTimes = __GetAllTranslations("",$this::language);
			
					$outResultMethods[] = array(
					    'name' => $_names[$clientLang], //GetTranslation($method->cargoTypeName,$transport_lang,$client_lang),
					    'names' => $_names, //GetAllTranslations($method->cargoTypeName,$transport_lang),
					    'calcResultPrice' => $_calcResultPrices[$clientCurr], //floatval($method->destFreight),
					    'calcResultPrices' => $_calcResultPrices, //GetConvertedPrices(floatval($method->destFreight),$base_curr),
					    'calcResultTime' => $_calcResultTimes[$clientLang], //GetTranslation($method->deliverTime,$transport_lang,$client_lang),
					    'calcResultTimes' => $_calcResultTimes //GetAllTranslations($method->deliverTime,$transport_lang)		
					);
			
				$outResultArray['cities']['derival'] = __GetAllTranslations($from,$this::language);
				$outResultArray['cities']['arrival'] = __GetAllTranslations($to,$this::language);
				$outResultArray['cityFrom'] = $outResultArray['cities']['derival']['ru'];
				$outResultArray['cityTo'] = $outResultArray['cities']['arrival']['ru'];
				$outResultArray['methods'] = $outResultMethods;
			
				return $outResultArray;
			}
	}


$transports[2] = array(
		'name' => calculator_ABSGroup::name,
		'site' => calculator_ABSGroup::site,
		'logo' => calculator_ABSGroup::logo,
		'calcfunc' => 'absgroup_calc',
		'language' => calculator_ABSGroup::language,
		'currency' => calculator_ABSGroup::currency,
		'classname' => 'calculator_ABSGroup'
	);

////////////////////////////////////////////


//$from = "ЧЕЛЯБИНСК";
//$to = "АСТАНА";
//$weight = 1;
//$vol = 1;
//$insPrice = 0;
//
//absgroup_calc($from,$to,$weight,$vol,$insPrice,'ru','ru','KZT','KZT','ru','kz','','','','');


////////////////////////////////////////////

function absgroup_calc($from,$to,$weight,$vol,$insPrice,$client_lang, $transport_lang, $base_curr, $client_curr, $cargoCountryFrom,$cargoCountryTo, $cargoStateFrom, $cargoStateTo)
    {
		$calc = new calculator_ABSGroup;
				
		$calc->Calculate($from,$to,$weight,$vol,$insPrice,$client_lang, $base_curr, $cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo);
	
		exit(0);
	}
		

?>
