<?php

//require '../services.php';
//require_once '../abstract_calc.php';
require_once 'ccodes.php';
require_once 'abstract_calc.php';

/**
 * Class calculator_DELLIN
 */

class calculator_DELLIN extends AbstractCalculator
    {
		const name		= 'Деловые Линии';
		const site		= 'http://www.dellin.ru';
		const logo		= 'https://www.dellin.ru/assets/layout/logo-c9d249def2b4e71903781c92cc7fb21d1b3f2a047dbf81d7411e3b69bb7bd4b7.svg';
		const language		= 'RU';
		const currency		= 'RUB';
        const AppKey            = '449A2F0C-9EA6-11E5-A3FB-00505683A6D3';


        private $sLogin         = 'cargoguru2015@gmail.com';
        private $sPassword      = 'Ghfdj12345';

        public $oDerivals	= array('RU', 'BY', 'KZ', 'KG');
		public $oArrivals	= array('RU', 'BY', 'KZ', 'KG');


    	public $aAddLoadVariants = [
            [
                "displayName" => "Загрузка",
                "fieldName" => "loadingType",
                "type" => "enum",
                "visibleOrder" => 11,
                "recalcTotalPrice" => true,
                "is_option"=> true,
                "variants" => [
                    [
                        "number" => 0,
                        "selected" => true,
                        "visible" => "Не задана",
                        "uid" => "",
                    ],
                    [
                        "number" => 1,
                        "visible" => "Боковая загрузка",
                        "uid" => "0xb83b7589658a3851440a853325d1bf69",
                    ],
                    [
                        "number" => 2,
                        "visible" => "Верхняя загрузка",
                        "uid" => "0xabb9c63c596b08f94c3664c930e77778",
                    ]
                ]
            ],
            [
                "displayName" => "Погрузчик на загрузке",
                "fieldName" => "loadMachineType",
                "type" => "enum",
                "visibleOrder" => 12,
                "recalcTotalPrice" => true,
                "is_option"=> true,
                "variants" => [
                    [
                        "number" => 0,
                        "selected" => true,
                        "visible" => "Не задана",
                        "uid" => "",
                    ],
                    [
                        "number" => 1,
                        "visible" => "Для погрузки необходим гидроборт",
                        "uid" => "0x92fce2284f000b0241dad7c2e88b1655",
                    ],
                    [
                        "number" => 2,
                        "visible" => "Для погрузки необходим манипулятор",
                        "uid" => "0x88f93a2c37f106d94ff9f7ada8efe886",
                    ]
                ]
            ],
            [
                "displayName" => "Погрузочные работы",
                "fieldName" => "openLoadType",
                "type" => "multienum",
                "visibleOrder" => 13,
                "is_option"=> true,
                "recalcTotalPrice" => true,
                "variants" => [
                    [
                        "number" => 1,
                        "visible" => "Наличие грузового лифта",
                        "makesInvisible" => ["openLoadLevelBlock","openLoadTransferBlock"],
                        "uid" => "0xa77fcf6a449164ed490133777a68bd51",

                    ],
              /*      [
                        "number" => 2,
                        "visible" => "Поднятие (этаж)",
                        "makesVisible" => ["openLoadLevelBlock"],
                        "makesInvisible" => ["openLoadTransferBlock"],
                        "uid" => "0xadf1fc002cb8a9954298677b22dbde12",
                    ],
                    [
                        "number" => 3,
                        "visible" => "Пронос, м",
                        "makesVisible" => ["openLoadTransferBlock"],
                        "makesInvisible" => ["openLoadLevelBlock"],
                        "uid" => "0x9a0d647ddb11ebbd4ddaaf3b1d9f7b74",
                    ]
              */
                ]
            ],
		];

    	public $aAddUnLoadVariants = [
            [
                "displayName" => "Выгрузка",
                "fieldName" => "unloadingType",
                "type" => "enum",
                "visibleOrder" => 11,
                "is_option"=> true,
                "recalcTotalPrice" => true,
                "variants" => [
                    [
                        "number" => 0,
                        "selected" => true,
                        "visible" => "Не задана",
                        "uid" => "",
                    ],
                    [
                        "number" => 1,
                        "visible" => "Боковая выгрузка",
                        "uid" => "0xb83b7589658a3851440a853325d1bf69",
                    ],
                    [
                        "number" => 2,
                        "visible" => "Верхняя выгрузка",
                        "uid" => "0xabb9c63c596b08f94c3664c930e77778",
                    ]
                ]
            ],
            [
                "displayName" => "Погрузчик на выгрузке",
                "fieldName" => "unloadMachineType",
                "type" => "enum",
                "visibleOrder" => 12,
                "is_option"=> true,
                "recalcTotalPrice" => true,
                "variants" => [
                    [
                        "number" => 0,
                        "selected" => true,
                        "visible" => "Не задана",
                        "uid" => "",
                    ],
                    [
                        "number" => 1,
                        "visible" => "Для выгрузки необходим гидроборт",
                        "uid" => "0x92fce2284f000b0241dad7c2e88b1655",
                    ],
                    [
                        "number" => 2,
                        "visible" => "Для выгрузки необходим манипулятор",
                        "uid" => "0x88f93a2c37f106d94ff9f7ada8efe886",
                    ]
                ]
            ],
            [
                "displayName" => "Открытая машина",
                "fieldName" => "openMachineType",
                "type" => "enum",
                "visibleOrder" => 13,
                "is_option"=> true,
                "recalcTotalPrice" => true,
                "variants" => [
                    [
                        "number" => 0,
                        "selected" => true,
                        "visible" => "Не задана",
                        "uid" => "",
                    ],
                    [
                        "number" => 1,
                        "visible" => "Для погрузки необходима открытая машина",
                        "uid" => "0x9951e0ff97188f6b4b1b153dfde3cfec",
                    ],
                    [
                        "number" => 2,
                        "visible" => "Растентовка",
                        "uid" => "0x818e8ff1eda1abc349318a478659af08",
                    ]
                ]
            ],
            [
                "displayName" => "Разгрузочные работы",
                "fieldName" => "openUnloadType",
                "type" => "multienum",
                "visibleOrder" => 14,
                "is_option"=> true,
                "recalcTotalPrice" => true,
                "variants" => [
                    [
                        "number" => 1,
                        "visible" => "Наличие грузового лифта",
                        "makesInvisible" => ["openUnLoadLevelBlock","openUnLoadTransferBlock"],
                        "uid" => "0xa77fcf6a449164ed490133777a68bd51",
                    ],
            /*        [
                        "number" => 2,
                        "visible" => "Поднятие (этаж)",
                        "makesVisible" => ["openUnLoadLevelBlock"],
                        "makesInvisible" => ["openUnLoadTransferBlock"],
                        "uid" => "0xadf1fc002cb8a9954298677b22dbde12",
                    ],
                    [
                        "number" => 3,
                        "visible" => "Пронос,м",
                        "makesVisible" => ["openUnLoadTransferBlock"],
                        "makesInvisible" => ["openUnLoadLevelBlock"],
                        "uid" => "0x9a0d647ddb11ebbd4ddaaf3b1d9f7b74",
                    ]
            */
                ]
            ],
		];

        public $aAdditionalVariants = [

        /*    [
                "id"=>"openLoadLevelBlock",
                "name"=>"openLoadLevelBlock",
                "displayName" => "Этаж",
                "fieldName" => "openLoadLevel",
                "hidden" => true,
                "visibleOrder" => 4,
                "type" => "int",
            ],
            [
                "id"=>"openLoadTransferBlock",
                "name"=>"openLoadTransferBlock",
                "displayName" => "Пронос в метрах",
                "fieldName" => "openLoadTransfer",
                "hidden" => true,
                "visibleOrder" => 5,
                "type" => "int",
            ],*/

         /*   [
                "id"=>"openUnLoadLevelBlock",
                "name"=>"openUnLoadLevelBlock",
                "displayName" => "Этаж",
                "fieldName" => "openUnLoadLevel",
                "visibleOrder" => 10,
                "hidden" => true,
                "type" => "int",
            ],
            [
                "id"=>"openUnLoadTransferBlock",
                "name"=>"openUnLoadTransferBlock",
                "displayName" => "Пронос в метрах",
                "fieldName" => "openUnLoadTransfer",
                "visibleOrder" => 11,
                "hidden" => true,
                "type" => "int",
            ],*/
            [
                "displayName" => "Упаковка",
                "fieldName" => "packageType",
                "type" => "multienum",
                "visibleOrder" => 12,
                "recalcTotalPrice" => true,
                "variants" => [
                    [
                        "number" => 1,
                        "visible" => "Жесткая упаковка",
                        "makesInvisible" => ["boxCountBlock","bagCountBlock"],
                        "uid" => "0x838fc70baeb49b564426b45b1d216c15",
                    ],
                    [
                        "number" => 2,
                        "visible" => "Жесткий короб",
                        "uid" => "0x8783b183e825d40d4eb5c21ef63fbbfb",
                    ],
                    [
                        "number" => 3,
                        "visible" => "Картонная коробка",
                        "makesVisible" => ["boxCountBlock"],
                        "makesInvisible" => ["bagCountBlock"],
                        "uid" => "0x951783203a254a05473c43733c20fe72",
                    ],
                    [
                        "number" => 4,
                        "visible" => "Дополнительная упаковка",
                        "makesInvisible" => ["boxCountBlock","bagCountBlock"],
                        "uid" => "0x9a7f11408f4957d7494570820fcf4549",
                    ],
                    [
                        "number" => 5,
                        "visible" => "Воздушно-пузырьковая плёнка",
                        "makesInvisible" => ["boxCountBlock","bagCountBlock"],
                        "uid" => "0xa8b42ac5ec921a4d43c0b702c3f1c109",
                    ],
                    [
                        "number" => 6,
                        "visible" => "Мешок",
                        "makesVisible" => ["bagCountBlock"],
                        "makesInvisible" => ["boxCountBlock"],
                        "uid" => "0xad22189d098fb9b84eec0043196370d6",
                    ],
                    [
                        "number" => 7,
                        "visible" => "Паллетный борт (только до терминала-получателя)",
                        "makesInvisible" => ["boxCountBlock","bagCountBlock"],
                        "uid" => "0xbaa65b894f477a964d70a4d97ec280be",
                    ],
                ]
            ],
        ];



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

			if($from == $to)
            {
                return DropCalculation();
            }

            if ($insPrice<2)
			{
                $insPrice=2;
			}

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

			//////////////////////////////////////////////////	

			$DELLINAppKey = $this::AppKey;
                        // '449A2F0C-9EA6-11E5-A3FB-00505683A6D3';
		
			mb_internal_encoding("UTF-8");
			mb_regex_encoding("UTF-8");
		
			
			$nSourceCityIdx = 0;
			$nTargetCityIdx = 0;
		
			// processing array index
			$arrProcIdx = 0;
		
			// fetch cities
			$citiesURL = "https://api.dellin.ru/v1/public/cities.json";
			$citiesReqBody = json_encode([ "appKey" =>  "$DELLINAppKey" ]);
			$cCitiesReq = curl_init($citiesURL);
			curl_setopt_array($cCitiesReq, array(
					    CURLOPT_POST => TRUE,
					    CURLOPT_RETURNTRANSFER => TRUE,
					    CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json'
					    ),
					    CURLOPT_POSTFIELDS => $citiesReqBody
					));
			$citiesJSON = curl_exec($cCitiesReq);
			$citiesURI = json_decode($citiesJSON)->url;

			if ($citiesURI == '') {
			    return DropCalculation();
			}

			//$citiesCSVByString = file($citiesURI);

            $data = file_get_contents($citiesURI);
            $rows = explode("\n",$data);
            $s = array();

            foreach($rows as $row) {
            	//echo  "$row";
                $s[] = str_getcsv($row);

            }

            $citiesCSVByString=$s;
                //die;
			$cityNum = 0;

			foreach($citiesCSVByString as $city) {
			    $cityNum++;

			    if ($cityNum == 1)
				continue;

			    //$parsedCity = str_getcsv($city);
                $parsedCity=$city;

			    $_id = $parsedCity[2];
			    $_name = trim(mb_ereg_replace('(\s+г|\s+г\.|г\.\s+|\.|\s+пгт|пгт\s+|\s+п|п\s+|\s+рп|рп\s+)','',$parsedCity[1]));
			    $_name = trim(mb_ereg_replace('(\s+с|\s+с\.|\s+д|\s+ст-ца)','',$_name));
			    $_name = mb_ereg_replace('\(.+\)','',$_name);
			    //print($_name.PHP_EOL);
			    $citiesRArr[$_id] = $_name;

			    if ((mb_strtoupper(trim($from),"utf-8") == mb_strtoupper(trim($_name),"utf-8")))
				$nSourceCityIdx = $_id;
			    if ((mb_strtoupper(trim($to),"utf-8") == mb_strtoupper(trim($_name),"utf-8")))
				$nTargetCityIdx = $_id;
			}
			//echo $from,' ',$to,'<br>';
			//echo $nSourceCityIdx,' ',$nTargetCityIdx; die;

			if (($nSourceCityIdx == 0) || ($nTargetCityIdx == 0))
			    return DropCalculation();
			    
			// ok, we have to process additional options
			$addOptions = [];
			if (count($options->addOptions) > 0) {
            		    //////////////////////////////////////
            		    // fetch additional services
            		    //////////////////////////////////////
                
            		    $sAddOptsURL = "https://api.dellin.ru/v1/public/request_services.json";
            		    $aAddOpts = array("appKey" => $this::AppKey);

            		    $oAddOptsReq = curl_init($sAddOptsURL);
            		    curl_setopt_array($oAddOptsReq, array(
                                    CURLOPT_POST => TRUE,
                                    CURLOPT_RETURNTRANSFER => TRUE,
                                    CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/json'
                                    ),
                                    CURLOPT_POSTFIELDS => json_encode($aAddOpts)
                                ));
            		    $sAddOptsJSON = curl_exec($oAddOptsReq);
            		    $sAddOptsListURI = json_decode($sAddOptsJSON)->url;
            		    $aAddOpts = file($sAddOptsListURI);
            		    
            		    // compile additional services from additional options
            		    $aScanOptArray = [];
            		    foreach($aAddOpts as $sOptElement) {
            			$aOptElement = str_getcsv($sOptElement);
            			if (($aOptElement[0] == 0) or ($aOptElement[1] == "")) {
            			    continue;
            			}
            			$aScanOptArray[intval($aOptElement[0])] = $aOptElement[1];
            		    }

            		    foreach($aScanOptArray as $aOptEKey => $aOptElement) {
            			if (in_array($aOptEKey,$options->addOptions)) {
            			    //$addOptions[] = $aOptElement;
            			    $addOptions[] = $aOptEKey;
            			}
            		    }
//            		    print_r($addOptions);
//            		    die();
			}
		
			// so, request calculation
			$reqBody = json_encode(array(
				    'appKey' => $DELLINAppKey,
				    'derivalPoint' => $nSourceCityIdx,
				    'arrivalPoint' => $nTargetCityIdx,
				    'additionalServices' => $addOptions,
				    'sizedVolume' => $vol."",
				    'sizedWeight' => $weight."",
				    'statedValue' => $insPrice.""
				));
		
			$calcURL = 'https://api.dellin.ru/v1/public/calculator.json';
			$calcReq = curl_init($calcURL);
			curl_setopt_array($calcReq, array(
					    CURLOPT_POST => TRUE,
					    CURLOPT_RETURNTRANSFER => TRUE,
					    CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json'
					    ),
					    CURLOPT_POSTFIELDS => $reqBody
					));
			
			$resultJSON = curl_exec($calcReq);

			//echo 'Test';print_r($reqBody); print_r($resultJSON); die();
			$resultObj = json_decode($resultJSON);


			// Optional terminals
            $terminalsTo = $resultObj->arrival->terminals;

            self::SetTerminals($to,$nTargetCityIdx,$terminalsTo);

            $terminalsFrom = $resultObj->derival->terminals;

            self::SetTerminals($from,$nSourceCityIdx,$terminalsFrom);

			$outResultArray = array();
			$outResultMethods = array();
		
			if (!isset($resultObj->errors))		// no service
			    {
					$_names = __GetAllTranslations("Автотранспорт",$this::language);
					$_calcResultPrice = floatval($resultObj->price);
					$_calcResultPrices = GetConvertedPrices($resultObj->price,$this::currency);
					$_calcResultTimes = __GetAllTranslations($resultObj->time->nominative,$this::language);

					$outResultMethods[] = array(
					    'name' => $_names[$clientLang], //GetTranslation($method->cargoTypeName,$transport_lang,$client_lang),
					    'names' => $_names, //GetAllTranslations($method->cargoTypeName,$transport_lang),
					    'calcResultPrice' => $_calcResultPrices[$clientCurr], //floatval($method->destFreight),
					    'calcResultPrices' => $_calcResultPrices, //GetConvertedPrices(floatval($method->destFreight),$base_curr),
					    'calcResultTime' => $_calcResultTimes[$clientLang], //GetTranslation($method->deliverTime,$transport_lang,$client_lang),
					    'calcResultTimes' => $_calcResultTimes //GetAllTranslations($method->deliverTime,$transport_lang)					
					    );

					if(isset($resultObj->air))
					{
						$price = floatval($resultObj->air->price) +
							floatval($resultObj->air->insurance) +
                            floatval($resultObj->air->notify->price);

                        $_calcResultPrice = $price;
                        $_calcResultPrices = GetConvertedPrices($price, $this::currency);

                        $_names = __GetAllTranslations("Авиадоставка",$this::language);
                        $outResultMethods[] = array(
                            'name' => $_names[$clientLang], //GetTranslation($method->cargoTypeName,$transport_lang,$client_lang),
                            'names' => $_names, //GetAllTranslations($method->cargoTypeName,$transport_lang),
                            'calcResultPrice' => $_calcResultPrices[$clientCurr], //floatval($method->destFreight),
                            'calcResultPrices' => $_calcResultPrices, //GetConvertedPrices(floatval($method->destFreight),$base_curr),
                            'calcResultTime' => $_calcResultTimes[$clientLang], //GetTranslation($method->deliverTime,$transport_lang,$client_lang),
                            'calcResultTimes' => $_calcResultTimes //GetAllTranslations($method->deliverTime,$transport_lang)
                        );
                    }
			    }
			else
			    return DropCalculation();
				
			$outResultArray['cities']['derival'] = __GetAllTranslations($from,$this::language);
			$outResultArray['cities']['arrival'] = __GetAllTranslations($to,$this::language);
			$outResultArray['cityFrom'] = $outResultArray['cities']['derival'][$clientLang];
			$outResultArray['cityTo'] = $outResultArray['cities']['arrival'][$clientLang];
			$outResultArray['methods'] = $outResultMethods;

			return $outResultArray;
		}

    public function Calculate2(
        $from,
        $to,
        $weight,
        $vol,
        $insPrice,
        $clientLang,
        $clientCurr,
        $cargoCountryFrom,
        $cargoCountryTo,
        $cargoStateFrom,
        $cargoStateTo,
		$isDerivalByCourier,
        $cargoDerivalStreet,
        $cargoDerivalStreetCode,
        $isArrivalByCourier,
        $cargoArrivalStreet,
        $cargoArrivalStreetCode,
        $aOptions = [])
    {
        global	$aFCodes, $aCCodes;

        if(IS_DEBUG)
		{
            $timeFirst  = strtotime('now');

		}

   /*     require_once "./service/config.php";
        require_once "./service/service.php";
*/
        mb_internal_encoding("UTF-8");
        mb_regex_encoding("UTF-8");
        date_default_timezone_set('UTC');

    /*    $from = mb_strtoupper($from);
        $to = mb_strtoupper($to);
*/
    /*    if(IS_PRODUCTION)
		{
            $host = DB_HOST;
		}
		else
		{
            $host = 'localhost';
		}

		$mysqli = new mysqlii($host,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);
*/

        if ($insPrice<2)
        {
            $insPrice=2;
        }

        if ($this::currency != $clientCurr)
        {

            $cvt_curr = GetConvertedPrices($insPrice,$clientCurr);
            $insPrice = $cvt_curr[$this::currency];
        }

        $cityFrom = $from;
        $cityTo = $to;

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

        $DELLINAppKey = $this::AppKey;

        mb_internal_encoding("UTF-8");
        mb_regex_encoding("UTF-8");

        $nSourceCityIdx = 0;
        $nTargetCityIdx = 0;

        if (IS_PRODUCTION) {
            $host = DB_HOST;
        } else {
            $host = 'localhost';
        }

        $oHandler = new mysqlii($host, LANG_DB_LOGIN, LANG_DB_PASSWORD, KLADR_DB_NAME);

        // processing array index
        $arrProcIdx = 0;

        /*
        if (IS_DEBUG ) {
            // fetch cities
            $citiesURL = "https://api.dellin.ru/v1/public/cities.json";
            $citiesReqBody = array("appKey" => $this::AppKey);
           // $citiesReqBody = json_encode(["appKey" => "$DELLINAppKey"]);

            $json = self::CallPOSTJSON($citiesURL, $citiesReqBody);

            $citiesURI = $json->url;

            if (IS_DEBUG) {
                $timeSecond = strtotime('now');
                $differenceInSeconds = $timeSecond - $timeFirst;
                echo 'Part 0:', $differenceInSeconds, 'sec';
            }

            if ($citiesURI == '') {
                return DropCalculation();
            }

            //$citiesCSVByString = file($citiesURI);

            $sqlQuery = "
			SELECT cities_file_size
			FROM " . DB_GLOBAL_VALUES_TABLE . " 
			LIMIT 1;";

            $oRes = $mysqli->query($sqlQuery);

            //var_dump($mysqli); die;

            $oRow = $oRes->fetch_assoc();

            $cities_file_size = (int)$oRow['cities_file_size'];

            $data = file_get_contents($citiesURI);

            $new_cities_file_size = strlen($data);
            $cityFrom = mb_strtoupper(
                trim(
                    mb_ereg_replace('(\s+г|\s+г\.|г\.\s+|\.|\s+пгт|пгт\s+|\s+п|п\s+|\s+рп|рп\s+)', '', $from)
                )
                , "utf-8");
            $cityTo = mb_strtoupper(
                trim(
                    mb_ereg_replace('(\s+г|\s+г\.|г\.\s+|\.|\s+пгт|пгт\s+|\s+п|п\s+|\s+рп|рп\s+)', '', $to)
                ), "utf-8");

            if (IS_DEBUG) {
                $timeSecond = strtotime('now');
                $differenceInSeconds = $timeSecond - $timeFirst;
                echo 'Part 1:', $differenceInSeconds, 'sec';
            }
        }
        else
		{
			$cities_file_size=0;
			$new_cities_file_size=0;
		}

        $cities_file_size=0;
        $new_cities_file_size=0;

        if($cities_file_size!=$new_cities_file_size) {

            $rows = explode("\n", $data);
            $s = array();

            foreach ($rows as $row) {
                $s[] = str_getcsv($row);
            }

            $citiesCSVByString = $s;

            $cityNum = 0;

            foreach ($citiesCSVByString as $city) {
                $cityNum++;

                if ($cityNum == 1)
                    continue;

                $parsedCity = $city;

                $_id = $parsedCity[2];
                $_name = trim(mb_ereg_replace('(\s+г|\s+г\.|г\.\s+|\.|\s+пгт|пгт\s+|\s+п|п\s+|\s+рп|рп\s+)', '', $parsedCity[1]));

                $_name = trim(mb_ereg_replace('(\s+с|\s+с\.|\s+д|\s+ст-ца)', '', $_name));

                $regname = trim(mb_ereg_replace('(\s+с|\s+с\.|\s+д|\s+ст-ца)', '', $_name));

                $regnames = explode("(", $regname);

                $regname = $regnames[1];

                $regname =  str_replace(')','',$regname);

                $_name = mb_ereg_replace('\(.+\)', '', $_name);

                $citiesRArr[$_id] = $_name;

                if ($cityFrom == mb_strtoupper(trim($_name), "utf-8"))
                    $nSourceCityIdx = $_id;
                if ($cityTo == mb_strtoupper(trim($_name), "utf-8"))
                    $nTargetCityIdx = $_id;

                if (IS_DEBUG) {
                    $timeSecond = strtotime('now');
                    $differenceInSeconds = $timeSecond - $timeFirst;
                    echo 'Part 2:', $differenceInSeconds,'sec','<br>';
                }

                self::AddOrUpdateCityInfo($parsedCity[0],
					$parsedCity[1],
					$parsedCity[2],
					$_name,$regname);

                if (IS_DEBUG) {
                    $timeSecond = strtotime('now');
                    $differenceInSeconds = $timeSecond - $timeFirst;
                    echo 'Part 3:', $differenceInSeconds,'sec','<br>';
                }
            }

            $sqlInsert = "UPDATE ".DB_GLOBAL_VALUES_TABLE."
				SET	cities_file_size = ".$new_cities_file_size;

            $oRes = $mysqli->query($sqlInsert);

        }
        else
*/

		{
            $stateFrom =  mb_strtoupper(trim($cargoStateFrom), "utf-8");
            $stateTo =  mb_strtoupper(trim($cargoStateTo), "utf-8");

            $nSourceCityIdx = self::GetCityCode($oHandler,$cityFrom,$stateFrom);

            $nTargetCityIdx = self::GetCityCode($oHandler,$cityTo,$stateTo);

            if (IS_DEBUG) {
                $timeSecond = strtotime('now');
                $differenceInSeconds = $timeSecond - $timeFirst;
                echo 'Part 2:', $differenceInSeconds,'sec','<br>';
            }
        }

        //echo $cityFrom,':',$nSourceCityIdx,'<br> ',$cityTo,':',$nTargetCityIdx; die;

        if (($nSourceCityIdx == 0) || ($nTargetCityIdx == 0))
            return DropCalculation();

        // ok, we have to process additional options
        $addOptions = [];

        if (count($aOptions) > 0) {

        //    var_dump($this->aAddLoadVariants);
        //    var_dump($aOptions->packageType); die;

            $loadingType = "";
            $unloadingType = "";
            $loadMachineType = "";//array();
            $unloadMachineType = "";
            $openMachineType = "";
            $openLoadUnloadType = array();

            $derivalLoading = array();
            $arrivalUnLoading = array();

            $packageTypes = array();
            $derivalServices = array();
            $arrivalServices = array();

            $boxCount = 0;
            $bagCount = 0;
                         /* "openLoadUnloadType" => [ "1", "2" ],
              "packageType" => [ "1", "2" ],
            */

			$variants = $this->aAddLoadVariants;
            $variants = array_merge($variants, $this->aAddUnLoadVariants);
            $variants = array_merge($variants, $this->aAdditionalVariants);

            foreach ($variants as $item)
            {
                $optionName = $item["fieldName"];
               // if(!IS_PRODUCTION) var_dump($aOptions->$optionName);

                $indx = $aOptions->$optionName;

                // echo $optionName,' ',$indx ,'<br>';

                switch($optionName)
                {
                    case "loadingType":
                        if(intval($indx)!=0)
                        {
                            $loadingType = $item["variants"][$indx]["uid"];
                            $derivalServices[] =$loadingType;
                        }
                        break;
                    case "unloadingType":
                        if(intval($indx)!=0)
                        {
                            $unloadingType = $item["variants"][$indx]["uid"];
                            $arrivalServices[] =$unloadingType;
                        }
                        break;
                    case "loadMachineType":
                        if(intval($indx)!=0)
                        {
                            $loadMachineType = $item["variants"][$indx]["uid"];

                            $derivalServices[] = $loadMachineType;
                        }
                        break;
                    case "unloadMachineType":
                        if(intval($indx)!=0)
                        {
                            $unloadMachineType = $item["variants"][$indx]["uid"];
                            $arrivalServices[] = $unloadMachineType;
                        }
                        break;
                    case "openMachineType":
                        if(intval($indx)!=0)
                        {
                            $openMachineType = $item["variants"][$indx]["uid"];

                        }
                        break;
                    case "openLoadType":
                        if(count($aOptions->$optionName)>0) {
                            foreach ($aOptions->$optionName as $opt)
                            {


                                switch ($opt) {
                                    case "1":
                                        $derivalLoading[] = array("uid" => $item["variants"]["0"]["uid"]);
                                        break;
                                  /*  case "2":

                                        $derivalLoading[] =
                                            array(
                                                "uid" => $item["variants"]["1"]["uid"],
                                                "value"=> strval($aOptions->openLoadLevel)
                                                  );
                                        break;
                                    case "3":
                                        $derivalLoading[] =
                                            array(
                                                "uid" => $item["variants"]["2"]["uid"],
                                                "value"=> strval($aOptions->openLoadTransfer)
                                            );
                                        break;
                                  */
                                }
                            }
                        }
                        break;
                    case "openLoadLevel":
                        if(intval($aOptions->openLoadLevel)>0)
                        {
                            $derivalLoading[] =
                                array(
                                    "uid" => $item["variants"]["1"]["uid"],
                                    "value"=> strval($aOptions->openLoadLevel)
                                );
                        }
                        break;
                    case "openLoadTransfer":
                        if(intval($aOptions->openLoadTransfer)>0)
                        {
                            $derivalLoading[] =
                                array(
                                    "uid" => $item["variants"]["1"]["uid"],
                                    "value"=> strval($aOptions->openLoadTransfer)
                                );
                        }
                        break;
                    case "openUnloadType":
                        if(count($aOptions->$optionName)>0) {
                            foreach ($aOptions->$optionName as $opt)
                            {
                                switch ($opt) {
                                    case "1":
                                        $arrivalUnLoading[] = array("uid" => $item["variants"]["0"]["uid"]);
                                        break;
                                }
                            }
                        }
                        break;
                    case "openUnloadLevel":
                        if(intval($aOptions->openUnloadLevel)>0)
                        {
                            $derivalLoading[] =
                                array(
                                    "uid" => $item["variants"]["1"]["uid"],
                                    "value"=> strval($aOptions->openUnloadLevel)
                                );
                        }
                        break;
                    case "openUnloadTransfer":
                        if(intval($aOptions->openUnloadTransfer)>0)
                        {
                            $derivalLoading[] =
                                array(
                                    "uid" => $item["variants"]["1"]["uid"],
                                    "value"=> strval($aOptions->openUnloadTransfer)
                                );
                        }
                        break;
                    case "packageType":
                        if(count($aOptions->$optionName)>0)
                        {
                            foreach($aOptions->$optionName as $opt)
                            {
                                foreach($item["variants"] as $variant)
                                {
                                    if ($opt == $variant["number"])
                                    {
                                        $packageTypes[] = $variant["uid"];
                                    }
                                }
                            }
                        }
                        break;

                    case "bagCount":
                        $bagCount = intval($aOptions->$optionName);
                        break;
                    case "boxCount":
                        $boxCount = intval($aOptions->$optionName);
                        break;

                }
            }

//var_dump($packageTypes);die();

            /*
            $sAddOptsURL = "https://api.dellin.ru/v1/public/request_services.json";
            $aAddOpts = array("appKey" => $this::AppKey);

            $oAddOptsReq = curl_init($sAddOptsURL);
            curl_setopt_array($oAddOptsReq, array(
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
                CURLOPT_POSTFIELDS => json_encode($aAddOpts)
            ));
            $sAddOptsJSON = curl_exec($oAddOptsReq);
            $sAddOptsListURI = json_decode($sAddOptsJSON)->url;
            $aAddOpts = file($sAddOptsListURI);

            // compile additional services from additional options
            $aScanOptArray = [];
            foreach($aAddOpts as $sOptElement) {
                $aOptElement = str_getcsv($sOptElement);
                if (($aOptElement[0] == 0) or ($aOptElement[1] == "")) {
                    continue;
                }
                $aScanOptArray[intval($aOptElement[0])] = $aOptElement[1];
            }

            foreach($aScanOptArray as $aOptEKey => $aOptElement) {
                if (in_array($aOptEKey,$aOptions->addOptions)) {
                    //$addOptions[] = $aOptElement;
                    $addOptions[] = $aOptEKey;
                }
            }
            */
//            		    print_r($addOptions);
//            		    die();

        }

        $reqArray = array(
            'appKey' => $DELLINAppKey,
			'sessionID' =>$this->Login(),
            'additionalServices' => $addOptions,
            'sizedVolume' => $vol."",
            'sizedWeight' => $weight."",
            'statedValue' => $insPrice.""
        );

    if(isset($packageTypes)) $reqArray["packages"] = $packageTypes;
    if(isset($derivalServices)) $reqArray["derivalServices"] = $derivalServices;
    if(count($arrivalServices)>0) $reqArray["arrivalServices"] = $arrivalServices;
    if(isset($derivalLoading)) $reqArray["derivalLoading"] = $derivalLoading;
    if(isset($arrivalUnLoading)) $reqArray["arrivalUnLoading"] =$arrivalUnLoading;

       // echo '<br>',json_encode($reqArray); die();

        if($isDerivalByCourier)
		{
            $reqArray["derivalDoor"] = "true";
            if(!isset($cargoDerivalStreetCode)) {
                $nSourceCityId = self::GetCityId($oHandler, $cityFrom, $stateFrom);
                //echo  'nSourceCityId:',$nSourceCityId,'<br>';
                $nSourceStreetCode = self::GetStreetKLADRCode2($cityFrom . ', ' . $cargoDerivalStreet,
                    $nSourceCityId, GAPI_KEY);
                // echo '<pre>'; var_dump($nSourceStreetCode); die();
                $reqArray["derivalPoint"] = $nSourceStreetCode['street_kladr'];
            }
            else
            {
                $reqArray["derivalPoint"] = $cargoDerivalStreetCode;
            }

		}
		else
		{
			$reqArray["derivalDoor"]="false";
			$reqArray["derivalPoint"]=$nSourceCityIdx;
		}

		if($isArrivalByCourier)
        {
            $reqArray["arrivalDoor"] = "true";
            if(!isset($cargoArrivalStreetCode))
            {
                $nTargetCityId = self::GetCityId($oHandler, $cityTo, $stateTo);
                $nTargetStreetCode =
                    self::GetStreetKLADRCode2($cityTo . ', ' . $cargoArrivalStreet,
                        $nTargetCityId, GAPI_KEY);
                $reqArray["arrivalPoint"] = $nTargetStreetCode['street_kladr'];
            }
            else
            {
                $reqArray["arrivalPoint"] = $cargoArrivalStreetCode;
            }
            /* echo
             $cargoArrivalStreet,':',$nTargetStreetCode['street_kladr'];
            */
        }
		else
		{
			$reqArray["arrivalDoor"]="false";
			$reqArray["arrivalPoint"]=$nTargetCityIdx;
		}

        if (IS_DEBUG) {
            echo '$isArrivalByCourier',$isArrivalByCourier,'<br>';
            echo 'TargetCityIdx',$nTargetCityIdx,'<br>';
            echo "arrivalPoint",$reqArray["arrivalPoint"],'<br>';
            $timeSecond = strtotime('now');
            $differenceInSeconds = $timeSecond - $timeFirst;
            echo 'Part 4:', $differenceInSeconds,'sec','<br>';
        }

		$reqBody= $reqArray;
        $calcURL = 'https://api.dellin.ru/v1/public/calculator.json';

		$resultObj =  $this->CallPOSTJSON($calcURL,$reqBody);

        if (!IS_PRODUCTION) {
      /*       echo '<pre>';

            var_dump(json_encode($resultObj) ); die;
            echo '</pre>';
        */}

        if (IS_DEBUG) {
            $timeSecond = strtotime('now');
            $differenceInSeconds = $timeSecond - $timeFirst;
            echo 'Part 5:', $differenceInSeconds,'sec','<br>';
        }

        $outResultArray = array();
        $outResultMethods = array();

        if (!isset($resultObj->errors))		// no service
        {
            $_names = __GetAllTranslations("Автотранспорт",$this::language);
			//$_calcResultPrice = floatval($resultObj->price);


            $arrivalPrices =  array();
            $arrivalPrice = 0;
            $arrivalCourierPrices =  array();
            $arrivalCourierPrice = 0;

            $derivalCourierPrices = array();
            $derivalPrices = array();
            $derivalCourierPrice=0;
            $derivalPrice = 0;


            $insurancePrices = array();
            $insurancePrice = 0;
            $airPrices = array();
            $airPrice = 0;
            $notifyPrices = array();
            $notifyPrice=0;

            $intercityPrices = array();
            $intercityPrice = 0;

            $airInsurancePrices = array();
            $airInsurancePrice = 0;

            $packagePrice = 0;
            $packagePrices = array();

            $cratePrice = 0;
            $cratePrices = array();

            $bagPrice = 0;
            $bagPrices = array();

            $boxPrice = 0;
            $boxPrices = array();

            $crate_plusPrice = 0;
            $crate_plusPrices = array();

            $typePrice = 0;
            $typePrices = array();

            $addBagCount = 0;
            $addBoxCount = 0;

            $bublePrice = 0;
            $bublePrices = array();

            if(isset($resultObj->air->price))
            {
                $airPrices =
                    GetConvertedPrices($resultObj->air->price,$this::currency);
                $airPrice = $airPrices[$clientCurr];
            }

            if(isset($resultObj->insurance))
            {
                $insurancePrices =
                    GetConvertedPrices($resultObj->insurance,$this::currency);
                $insurancePrice = $insurancePrices[$clientCurr];
            }

            if(isset($resultObj->insurance))
            {
                $insuranceAirPrices =
                    GetConvertedPrices($resultObj->air->insurance,$this::currency);
                $insuranceAirPrice = $insuranceAirPrices[$clientCurr];
            }

            if(isset($resultObj->air->insurance))
            {
                $airInsurancePrices =
                    GetConvertedPrices($resultObj->air->insurance,$this::currency);
                $airInsurancePrice = $airInsurancePrices[$clientCurr];
            }

            if(isset($resultObj->notify->price))
            {
                $notifyPrices =
                    GetConvertedPrices($resultObj->notify->price,$this::currency);
                $notifyPrice = $notifyPrices[$clientCurr];
            }

            if(isset($resultObj->packages->crate))
            {
                $cratePrices = GetConvertedPrices($resultObj->packages->crate,$this::currency);
                $cratePrice=$cratePrices[$clientCurr];
            }

            if(isset($resultObj->packages->type))
            {
                $typePrices = GetConvertedPrices($resultObj->packages->type,$this::currency);
                $typePrice=$typePrices[$clientCurr];
            }

            if(isset($resultObj->packages->buble))
            {
                $bublePrices = GetConvertedPrices($resultObj->packages->buble,$this::currency);
                $bublePrice=$bublePrices[$clientCurr];
            }

            if(isset($resultObj->packages->crate_plus))
            {
                $crate_plusPrices = GetConvertedPrices($resultObj->packages->crate_plus,$this::currency);
                $crate_plusPrice=$crate_plusPrices[$clientCurr];
            }

            if(isset($resultObj->packages->pallet))
            {
                $packagePrices = GetConvertedPrices($resultObj->packages->pallet,$this::currency);
                $packagePrice=$packagePrices[$clientCurr];
            }

            if(isset($resultObj->packages->bag))
            {
				if($bagCount>1)
				{
                    $addBagCount = floatval($resultObj->packages->bag)*($bagCount - 1);
				}
				else
				{
                    $bagCount = 1;
				}

                $bagPrices = GetConvertedPrices(floatval($resultObj->packages->bag) * $bagCount,$this::currency);
                $bagPrice=$bagPrices[$clientCurr];
            }

            if(isset($resultObj->packages->box))
            {

                if($boxCount>1)
                {
                    $addBoxCount = floatval($resultObj->packages->box)*($boxCount - 1);
                }
                else
                {
                    $boxCount =1;
                }

                $boxPrices = GetConvertedPrices(floatval($resultObj->packages->box) * $boxCount,$this::currency);
                $boxPrice=$boxPrices[$clientCurr];
            }

            if(isset($resultObj->intercity->price))
            {
                if(!IS_PRODUCTION) echo "<br>intercity-price", $resultObj->intercity->price,"<br>";
                $intercityPrices =
                    GetConvertedPrices($resultObj->intercity->price,$this::currency);
                $intercityPrice = $intercityPrices[$clientCurr];
            }

            if(isset($resultObj->arrival->price))
            {
				if($isArrivalByCourier)
				{
                    $arrivalCourierPrices =
                        GetConvertedPrices($resultObj->arrival->price,$this::currency);
                    $arrivalCourierPrice = $arrivalCourierPrices[$clientCurr];
				}
            	else
				{
                    $arrivalPrices =
                        GetConvertedPrices($resultObj->arrival->price,$this::currency);
                    $arrivalPrice = $arrivalPrices[$clientCurr];
				}

            }

            if(isset($resultObj->derival->price))
			{
				if($isDerivalByCourier)
				{
                    $derivalCourierPrices =
                        GetConvertedPrices($resultObj->derival->price,$this::currency);
                    $derivalCourierPrice = $derivalCourierPrices[$clientCurr];
				}
				else
				{
                    $derivalPrices =
                        GetConvertedPrices($resultObj->derival->price,$this::currency);
                    $derivalPrice = $derivalPrices[$clientCurr];

                    if(!IS_PRODUCTION)
					{
                        var_dump($resultObj->derival);//,'<br>';
					}

				}

			}
            //echo  $resultObj->price,' ',$addBagCount,' ',$addBoxCount; die;
            $_calcResultPrices = GetConvertedPrices($resultObj->price+$addBagCount+$addBoxCount,$this::currency);
            $_calcResultTimes = __GetAllTranslations($resultObj->time->nominative,$this::language);

            $intercity[1]=array(
                'description'=>$cityFrom.'-'.$cityTo,
                'price'=>$intercityPrice,
                'prices'=>$intercityPrices
            );

            $additional[1]=array(
                'description'=>'Забор от адреса '.$cityFrom,
                'price'=>$derivalCourierPrice,
                'prices'=>$derivalCourierPrices
            );

            $additional[2]=array(
                'description'=>'Забор груза от терминала',
                'price'=>$derivalPrice,
                'prices'=>$derivalPrices
            );

            $additional[3]=array(
                'description'=>'Доставка до адреса '.$cityTo,
                'price'=>$arrivalCourierPrice,
                'prices'=>$arrivalCourierPrices
            );

            $additional[4]=array(
                'description'=>'Услуги на терминале получателя',
                'price'=>$arrivalPrice,
                'prices'=>$arrivalPrices
            );

            $additional[5] = array(
                'description'=>'Стоимость страховки',
                'price'=>$insurancePrice,
                'prices'=>$insurancePrices
            );

            $additional[6] = array(
                'description'=>'Информирование о статусе груза',
                'price'=>$notifyPrice,
                'prices'=>$notifyPrices
            );

            $additional[] = array(
                    'description'=>'Упаковать в жёсткую упаковку',
                	"opt_name" => "packageType",
                	"opt_num" => "1",
                    'price'=>$cratePrice,
                    'prices'=>$cratePrices
            );

            $additional[] = array(
                'description'=>'Упаковать в жёсткий короб',
                "opt_name" => "packageType",
                "opt_num" => "2",
				'price'=>$crate_plusPrice,
                'prices'=>$crate_plusPrices
            );

            $additional[] = array(
                'description'=>'Упаковать в коробку',
                "opt_name" => "packageType",
                "opt_num" => "3",
                'price'=>$boxPrice,
                'prices'=>$boxPrices
            );

            $additional[] = array(
                'description'=>'Дополнительная упаковка',
                "opt_name" => "packageType",
                "opt_num" => "4",
                'price'=>$typePrice,
                'prices'=>$typePrices
            );

            $additional[] = array(
                'description'=>'Упаковать в воздушно-пузырьковую плёнку',
                "opt_name" => "packageType",
                "opt_num" => "5",
				'price'=>$bublePrice,
                'prices'=>$bublePrices
            );

            $additional[] = array(
                'description'=>'Упаковать в мешок',
                "opt_name" => "packageType",
                "opt_num" => "6",
                'price'=>$bagPrice,
                'prices'=>$bagPrices
            );

            $additional[] = array(
                    'description'=>'Упаковать в паллетный борт',
					"opt_name" => "packageType",
					"opt_num" => "7",
                    'price'=>$packagePrice,
                    'prices'=>$packagePrices
            );

            $airTransportation = array(
                    'calcResultAirDescription' => 'Забор груза:',
                    'calcResultAirInsuranceDescription'=>'Услуги на терминале отправителе: Страхование груза и срока при авиапееревозке',
                    'calcResultAirPrice' => $airPrice,
                    'calcResultAirInsurancePrice'=>$airInsurancePrice,
                    'calcResultAirInsurancePrices'=>$airInsurancePrices,
                    'calcResultAirPrices' => $airPrices,
                );

            $outResultMethods[] = array(
                'name' => $_names[$clientLang], //GetTranslation($method->cargoTypeName,$transport_lang,$client_lang),
                'names' => $_names, //GetAllTranslations($method->cargoTypeName,$transport_lang),

                'intercity' => $intercity,
                'additional' => $additional,
                //'airTransportation' => $airTransportation,
                'calcResultPrice' => $_calcResultPrices[$clientCurr],
                'calcResultPrices' => $_calcResultPrices, //GetConvertedPrices(floatval($method->destFreight),$base_curr),
                'calcResultTime' => $_calcResultTimes[$clientLang], //GetTranslation($method->deliverTime,$transport_lang,$client_lang),
                'calcResultTimes' => $_calcResultTimes //GetAllTranslations($method->deliverTime,$transport_lang)
            );

            if(isset($resultObj->air))
            {
                $price = floatval($resultObj->air->price) +
                    floatval($resultObj->air->insurance) +
                    floatval($resultObj->derival->price) +
                    floatval($resultObj->arrival->price) +
                    floatval($resultObj->air->notify->price);

                $additional_air[1]=array(
                    'description'=>'Забор от адреса '.$cityFrom,
                    'price'=>$derivalCourierPrice,
                    'prices'=>$derivalCourierPrices
                );

                $additional_air[2]=array(
                    'description'=>'Забор груза от терминала',
                    'price'=>$derivalPrice,
                    'prices'=>$derivalPrices
                );

                $additional_air[3]=array(
                    'description'=>'Доставка до адреса '.$cityTo,
                    'price'=>$arrivalCourierPrice,
                    'prices'=>$arrivalCourierPrices
                );

                $additional_air[4]=array(
                    'description'=>'Услуги на терминале получателя',
                    'price'=>$arrivalPrice,
                    'prices'=>$arrivalPrices
                );

                $additional_air[5] = array(
                    'description'=>'Стоимость страховки',
                    'price'=>$insuranceAirPrice,
                    'prices'=>$insuranceAirPrices
                );

                $additional_air[6] = array(
                    'description'=>'Информирование о статусе груза',
                    'price'=>$notifyPrice,
                    'prices'=>$notifyPrices
                );

                $additional_air[] = array(
                    'description'=>'Упаковать в жёсткую упаковку',
                    "opt_name" => "packageType",
                    "opt_num" => "1",
                    'price'=>$cratePrice,
                    'prices'=>$cratePrices
                );

                $additional_air[] = array(
                    'description'=>'Упаковать в жёсткий короб',
                    "opt_name" => "packageType",
                    "opt_num" => "2",
                    'price'=>$crate_plusPrice,
                    'prices'=>$crate_plusPrices
                );

                $additional_air[] = array(
                    'description'=>'Упаковать в коробку',
                    "opt_name" => "packageType",
                    "opt_num" => "3",
                    'price'=>$boxPrice,
                    'prices'=>$boxPrices
                );

                $additional_air[] = array(
                    'description'=>'Дополнительная упаковка',
                    "opt_name" => "packageType",
                    "opt_num" => "4",
                    'price'=>$typePrice,
                    'prices'=>$typePrices
                );

                $additional_air[] = array(
                    'description'=>'Упаковать в воздушно-пузырьковую плёнку',
                    "opt_name" => "packageType",
                    "opt_num" => "5",
                    'price'=>$bublePrice,
                    'prices'=>$bublePrices
                );

                $additional_air[] = array(
                    'description'=>'Упаковать в мешок',
                    "opt_name" => "packageType",
                    "opt_num" => "6",
                    'price'=>$bagPrice,
                    'prices'=>$bagPrices
                );

                $additional_air[] = array(
                    'description'=>'Упаковать в паллетный борт',
                    "opt_name" => "packageType",
                    "opt_num" => "7",
                    'price'=>$packagePrice,
                    'prices'=>$packagePrices
                );

               // $_calcResultPrice = $price;
                $_calcResultPrices = GetConvertedPrices($price, $this::currency);

                $_names = __GetAllTranslations("Авиадоставка",$this::language);
                $outResultMethods[] = array(
                    'name' => $_names[$clientLang], //GetTranslation($method->cargoTypeName,$transport_lang,$client_lang),
                    'names' => $_names, //GetAllTranslations($method->cargoTypeName,$transport_lang),
                    'additional' => $additional_air,
                    'calcResultPrice' => $_calcResultPrices[$clientCurr], //floatval($method->destFreight),
                    'calcResultPrices' => $_calcResultPrices, //GetConvertedPrices(floatval($method->destFreight),$base_curr),
                    'calcResultTime' => $_calcResultTimes[$clientLang], //GetTranslation($method->deliverTime,$transport_lang,$client_lang),
                    'calcResultTimes' => $_calcResultTimes //GetAllTranslations($method->deliverTime,$transport_lang)
                );
            }
        }
        else
            return DropCalculation();

        $outResultArray['cities']['derival'] = __GetAllTranslations($from,$this::language);
        $outResultArray['cities']['arrival'] = __GetAllTranslations($to,$this::language);
        $outResultArray['cityFrom'] = $outResultArray['cities']['derival'][$clientLang];
        $outResultArray['cityTo'] = $outResultArray['cities']['arrival'][$clientLang];
        $outResultArray['methods'] = $outResultMethods;

        return $outResultArray;
    }

    public function GetDiscount($paymentType)
    {
        $oHandler = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);
        $sqlQuery="
			SELECT d.percent,d.description
            FROM ".DB_DISCOUNT." d
            JOIN ".DB_PAYMENT_TYPE_DISCOUNT." ptd ON d.id = ptd.discount_id
            WHERE ptd.payment_type_id = ".$paymentType."
			LIMIT 1;";

        $oRes = $oHandler->query($sqlQuery);

        if(!IS_PRODUCTION) echo $sqlQuery,'<br>';


        if($oHandler->affected_rows > 0)
        {
            $oRow = $oRes->fetch_assoc();
            $result = [
                "percent"=>floatval($oRow['percent']),
                "description"=>strval($oRow['description']),
            ];
            return $result;
        }
        else
        {
            return null;
        }
    }

    public function GetCityId($oHandler,$cityName,$regName)
    {
        $sqlQuery="
			SELECT cityID
			FROM dellin_kladr
			WHERE UPPER(search)='".$cityName."'
			AND UPPER(regname) like '%$regName%'
			LIMIT 1;";

        $oRes = $oHandler->query($sqlQuery);

        //echo $sqlQuery,'<br>';

        $oRow = $oRes->fetch_assoc();

        if($oHandler->affected_rows > 0)
        {
            return strval($oRow['cityID']);
        }
        else
        {
            return 0;
        }
    }

    public function SetAdditionalTerminalAddress($city_name,$city_id,$terminal_id,$name,$address)
    {
        if(!IS_PRODUCTION)
        {
            $host = 'localhost';
        }
        else
        {
            $host = DB_HOST;
        }

        $oHandler = new mysqlii($host, LANG_DB_LOGIN, LANG_DB_PASSWORD, KLADR_DB_NAME);

        $sSearchQuery = "SELECT terminal_id
							FROM ".DB_ADDITIONAL_TERMINAL."
							WHERE terminal_id=".$terminal_id;

        $oSearchResult = $oHandler->query($sSearchQuery);

        if ($oHandler->affected_rows == 0) {

            $sQuery = "INSERT INTO ".DB_ADDITIONAL_TERMINAL."
                            (city_name, city_id ,terminal_id, name, address)
                            VALUES ('".$city_name."','".$city_id."',".$terminal_id.", '".$name."', '".$address."')";

            $oResult = $oHandler->query($sQuery);
        }
    }

    public function SetTerminals($sCityName,$nCityIdx,$terminals)
	{
        foreach ($terminals as $terminal)
        {
            $terminal_id = $terminal->id;
            $terminal_name = $terminal->name;
            $terminal_address = $terminal->address;

            $oRes = self::SetAdditionalTerminalAddress($sCityName,$nCityIdx,$terminal_id,$terminal_name,$terminal_address);
        }
	}

    public function GetAdditionalTerminals($city_name,$city_id)
    {
        if(!IS_PRODUCTION)
        {
            $host = 'localhost';
        }
        else
        {
            $host = DB_HOST;
        }

        $oHandler = new mysqlii($host, LANG_DB_LOGIN, LANG_DB_PASSWORD, KLADR_DB_NAME);

        $where = "";

        if($city_id>0)
		{
            $where = "city_id='".$city_id."'";
		}
		else
		{
			if(isset($city_name))
			{
                $where = "city_name='".mb_strtoupper($city_name)."'";
			}
			else
			{
                $Result["terminals"] = array();
			}
		}

        $sSearchQuery = "SELECT city_id ,terminal_id, name, address
							FROM ".DB_ADDITIONAL_TERMINAL."
							WHERE ".$where;

		if(!IS_PRODUCTION)
		{
			echo  $sSearchQuery,'<br>';
		}

        $oSearchResult = $oHandler->query($sSearchQuery);

        $oResult["terminal"] = array();

        if ($oHandler->affected_rows > 0) {

            if(!IS_PRODUCTION)
            {
                echo  $oHandler->affected_rows,'<br>';
            }

            $oRow = $oSearchResult->fetch_assoc();

            $terminal = array(
                "id" => $oRow["terminal_id"],
                "name" => $oRow["name"],
                "address" => $oRow["address"]
            );
            $oResult["terminal"][] = $terminal;
        }

        $Result["terminals"] = $oResult["terminal"];

        return $Result["terminals"];

    }


    public function GetCityCode($oHandler,$cityName,$regName)
	{
        $sqlQuery="
			SELECT code
			FROM dellin_kladr
			WHERE UPPER(search)='".$oHandler->real_escape_string(mb_strtoupper($cityName))."'
			OR (UPPER(search)='".$oHandler->real_escape_string(mb_strtoupper($cityName))."' AND UPPER(regname) like '%$regName%')
			ORDER BY (char_length(code) - char_length(replace(code,'0',''))) DESC LIMIT 1;";

        if(!IS_PRODUCTION) {var_dump($sqlQuery); echo '<br>';}

        $oRes = $oHandler->query($sqlQuery);



        $oRow = $oRes->fetch_assoc();

        if($oHandler->affected_rows > 0)
		{
           return strval($oRow['code']);
		}
		else
		{
			return 0;
		}
	}

    public function AddOrUpdateCityInfo($oHandler,$uid,$city_name,$city_code,$search_word,$regname)
	{

       		//var_dump($oHandler); die;
        $sqlQuery="
			SELECT 1 is_exists
			FROM dellin_kladr
			WHERE code='".trim($city_code)."'
			LIMIT 1;";

        $oRes = $oHandler->query($sqlQuery);

        $oRow = $oRes->fetch_assoc();

        if((int)$oRow['is_exists']==1)
		{
            $sqlQuery2="
			SELECT 1 is_exists
			FROM dellin_kladr
			WHERE code='".$city_code."'
			AND search = '".$city_name."'
			LIMIT 1;";

            $oRes2 = $oHandler->query($sqlQuery2);

            $oRow2 = $oRes->fetch_assoc();

            if((int)$oRow2['is_exists']!=1)
			{
                $sqlQuery3="INSERT INTO dellin_kladr
            	( name, code, search, regname)
				VALUES (
				'".$city_name."', 
				'".$city_code."', 
				'".$search_word."', 
				'".$regname."')";

                $oRes3 = $oHandler->query($sqlQuery3);
            }
		}
		else
		{
            $sqlQuery4="INSERT INTO dellin_kladr
            	( name, code, search, regname)
				VALUES (
				'".$city_name."', 
				'".$city_code."', 
				'".$search_word."', 
				'".$regname."')";

            $oRes4 = $oHandler->query($sqlQuery4);
        }
	}

		/**
		 * @return array
		 */
	public function GetOptions() {
                $baseOptions = parent::GetOptions();

                // here we have to compile all options from dellin

                //////////////////////////////////////
                // fetch terminals
                //////////////////////////////////////
                $sAddOptsURL = "https://api.dellin.ru/v2/public/terminals.json";
                $aAddOpts = array("appKey" => $this::AppKey);

                $oAddOptsReq = curl_init($sAddOptsURL);
                curl_setopt_array($oAddOptsReq, array(
                                    CURLOPT_POST => TRUE,
                                    CURLOPT_RETURNTRANSFER => TRUE,
                                    CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/json'
                                    ),
                                    CURLOPT_POSTFIELDS => json_encode($aAddOpts)
                                ));
                $sAddOptsJSON = curl_exec($oAddOptsReq);
                $aTerminals = json_decode($sAddOptsJSON);

		// if cities is specifies
		$oPOSTData = json_decode(file_get_contents("php://input"));
		$sCargoFrom = mb_strtoupper(trim($oPOSTData->data->cargoFrom));
		$sCargoTo = mb_strtoupper(trim($oPOSTData->data->cargoTo));

		/** fetch derival terminals */

		$aPrepFromTerms = array();
		foreach($aTerminals->city as $terminal) {
		    if ((mb_strtoupper(trim($terminal->name)) == $sCargoFrom) or (empty($sCargoFrom))) {
			foreach($terminal->terminals->terminal as $realTerm) {
			    if ((!$realTerm->isOffice)
					and (!$realTerm->onlyReceive)) {
				$aPrepFromTerms[] = array(
				    'visible' => $realTerm->name . ' (' . htmlspecialchars($realTerm->address, ENT_QUOTES, 'UTF-8') . ')',
				    'number' => $realTerm->id
				);
			    }
			}
		    }
		}

		if(count($aPrepFromTerms)==0)
		{
			$terminals = self::GetAdditionalTerminals($sCargoFrom,0);
			foreach ($terminals as $terminal)
			{
                //var_dump($terminal); die;
                $aPrepFromTerms[] = array(
                    'visible' => $terminal["name"] . ' (' . htmlspecialchars($terminal["address"], ENT_QUOTES, 'UTF-8') . ')',
                    'number' => $terminal["id"]
                );
			}
			/*if(!IS_PRODUCTION)
			{
				var_dump($aPrepFromTerms); die;
			}*/
		}

		/** fetch arrival terminals */

		$aPrepToTerms = array();
		foreach($aTerminals->city as $terminal) {
		    if ((mb_strtoupper(trim($terminal->name)) == $sCargoTo) or (empty($sCargoTo))) {
		    $rr = 0;
			foreach($terminal->terminals->terminal as $realTerm) {
			    if ((!$realTerm->isOffice)
					 and
					(!$realTerm->onlyGiveout))
			    {
				$rr++;
				$aPrepToTerms[] = array(
				    'visible' => $realTerm->name . ' (' . htmlspecialchars($realTerm->address, ENT_QUOTES, 'UTF-8') . ')',
				    'number' => $realTerm->id,
				    'selected' => ($rr > 1) ? true : false
				);
			    }
			}
		    }
		}

        if(count($aPrepToTerms)==0)
        {
            $terminals = self::GetAdditionalTerminals($sCargoTo,0);
            foreach ($terminals as $terminal)
            {
                //var_dump($terminal); die;
                $aPrepToTerms[] = array(
                    'visible' => $terminal["name"] . ' (' . htmlspecialchars($terminal["address"], ENT_QUOTES, 'UTF-8') . ')',
                    'number' => $terminal["id"]
                );
            }
           /* if(!IS_PRODUCTION)
            {
                var_dump($aPrepFromTerms); die;
            }*/
        }
		//////////////////////////////////////
		// compile terminal from expressions
		$aFromOptions = array();
		
        $aFromOptions['derivalCourier'] = array(
            "displayName" => "Способ доставки",
            "fieldName" => "derivalCourier",
            "type" => "enum",
            "required" => TRUE,
            "recalcTotalPrice" => true,
            "visibleOrder"=>3,
            "variants" => [
        		[
        		    "number" => 1,
        		    "visible" => "Самостоятельно доставить груз до терминала",
        		    "makesVisible" => ["derivalTerminalsBlock"],
        		    "makesInvisible" => ["derivalAddressBlock"],
        		    "selected" => true
        		],
        		[
        		    "number" => 2,
        		    "visible" => "Забор груза от адреса отправителя",
        		    "makesVisible" => ["derivalAddressBlock"],
        		    "makesInvisible" => ["derivalTerminalsBlock"]
        		]
        	    ],

        );
		$aFromOptions['derivalDateBlock'] = [
					"id" => "derivalDateBlock",
					"name" => "derivalDateBlock",
					"hidden" => false,
            		"visibleOrder"=>4,
					"aoptions" => [
							[
									"displayName" => "Забрать груз",
									"fieldName" => "cargoDesireDate",
									"type" => "date",
									"value" => strtotime("now +1 day"),
									"presentation" => [
											"size" => 20
									],
									"required" => FALSE,
									"inputSize" => 9,
									"pattern" => "/\d{1,2}\.\d{1,2}\.\d{1,4}/"
							],

					]
			];
		// id block for derivalterminals
		$aFromOptions['derivalTerminalsBlock'] = [
			"id" => "derivalTerminalsBlock",
			"name" => "derivalTerminalsBlock",
			"hidden" => false,
            "visibleOrder"=>5,
			"aoptions" => [
				[
					"displayName" => "Доступные терминалы",
					"fieldName" => "derivalTerminalId",
					"type" => "enum",
                    "presentation" => [
                        "size" => 100
                    ],
					"required" => FALSE,
					"variants" => $aPrepFromTerms
				],


			]
		];

		// id block for derivaladdress
		$aFromOptions['derivalAddressBlock'] = [
			"id" => "derivalAddressBlock",
			"name" => "derivalAddressBlock",
			"hidden" => true,
            "visibleOrder"=>5,
			"aoptions" => [
				[
					"displayName" => "Адрес улицы",
					"fieldName" => "cargoSenderAddress",
                    "recalcTotalPrice" => true,
					"required"=>true,
                    "visibleOrder" => 1,
					"type" => "string",
                    "presentation" => [
                        "size" => 100
                    ]
				],
                [
                    "displayName" => "Код улицы",
                    "fieldName" => "cargoSenderAddressCode",
                    "type" => "string",
                    "hidden" => true,
                    "visibleOrder" => 2,
                    "required" => false,
                    "presentation" => [
                        "size" => 67
                    ]
                ],
                [
                    "displayName" => "Номер дома",
                    "fieldName" => "cargoSenderAddressHouseNumber",
                    "type" => "string",
                    "required"=>true,
                    "inputSize" => 5,
                    "visibleOrder" => 3,
                    "required" => true,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
                [
                    "displayName" => "Номер корпуса",
                    "fieldName" => "cargoSenderAddressBuildingNumber",
                    "type" => "string",
                    "inputSize" => 5,
                    "visibleOrder" => 4,
                    "required" => false,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
                [
                    "displayName" => "Номер структуры",
                    "fieldName" => "cargoSenderAddressStructureNumber",
                    "type" => "string",
                    "inputSize" => 5,
                    "visibleOrder" => 5,
                    "required" => false,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
				[
					"displayName" => "Квартира (офис)",
					"fieldName" => "cargoSenderAddressCell",
					"type" => "int32",
                    "inputSize" => 15,
                    "visibleOrder" => 6,
					"required" => false,
                    "presentation" => [
                        "size" => 33
                    ]
				],
                [
                    "fieldName" => "senderWorkTimeStart",
                    "type" => "enum",
                    "inputSize" => 5,
                    "visibleOrder" => 3,
                    "visibleOrder" => 7,
                    "presentation" => [
                        "size" => 50
                    ],
                    "displayname" => "Забрать груз с ",
                    "variants" => $this->aStartTimeVariants
                ],
                [
                    "fieldName" => "senderWorkTimeEnd",
                    "type" => "enum",
                    "inputSize" => 5,
                    "visibleOrder" => 8,
                    "presentation" => [
                        "size" => 50
                    ],
                    "displayname" => " до ",
                    "variants" => $this->aEndTimeVariants
                ]

			]
		];

		$baseOptions['groups']['from']['aoptions'] = array_merge($baseOptions['groups']['from']['aoptions'],$aFromOptions);

		//////////////////////////////////////
		// compile terminal from expressions

        $aWhereOptions['arrivalCourier'] = array(
            "displayName" => "Способ доставки",
            "fieldName" => "arrivalCourier",
            "type" => "enum",
            "required" => TRUE,
            "recalcTotalPrice" => true,
            "visibleOrder"=>3,
            "variants" => [
        		[
        		    "number" => 1,
        		    "visible" => "Самостоятельно забрать груз на терминале",
        		    "makesVisible" => ["arrivalTerminalsBlock"],
        		    "makesInvisible" => ["arrivalAddressBlock"],
        		    "selected" => true
        		],
        		[
        		    "number" => 2,
        		    "visible" => "Доставить груз до адреса получателя",
        		    "makesVisible" => ["arrivalAddressBlock"],
        		    "makesInvisible" => ["arrivalTerminalsBlock"]
        		]
        	    ]
        );

		$aWhereOptions['arrivalDateBlock'] = [
					"id" => "arrivalDateBlock",
					"name" => "arrivalDateBlock",
					"hidden" => false,
            		"visibleOrder"=>4,
					"aoptions" => [
							[
									"displayName" => "Доставить груз",
									"fieldName" => "cargoDeliveryDate",
									"type" => "date",
									"value" => strtotime("now +1 day"),
									"presentation" => [
											"size" => 20
									],
									"inputSize" => 9,
									"required" => FALSE,
									"pattern" => "/\d{1,2}\.\d{1,2}\.\d{1,4}/"
							],

					]
		];

		$aWhereOptions['arrivalTerminalsBlock'] = [
			"id" => "arrivalTerminalsBlock",
			"name" => "arrivalTerminalsBlock",
			"hidden" => false,
            "visibleOrder"=>5,
			"aoptions" => [
				[
					"displayName" => "Доступные терминалы",
					"fieldName" => "arrivalTerminalId",
					"type" => "enum",
                    "presentation" => [
                        "size" => 100
                    ],
					"required" => FALSE,
					"variants" => $aPrepToTerms
				],

			]
		];

		// id block for derivaladdress
		$aWhereOptions['arrivalAddressBlock'] = [
			"id" => "arrivalAddressBlock",
			"name" => "arrivalAddressBlock",
			"hidden" => true,
            "visibleOrder"=>5,
			"aoptions" => [
				[
					"displayName" => "Адрес улицы",
					"fieldName" => "cargoRecepientAddress",
                    "recalcTotalPrice" => true,
					"type" => "string",
                    "visibleOrder" => 1,
					"required" => true,
					"presentation" => [
                		"size" => 100
            		]
				],
                [
                    "displayName" => "Код улицы",
                    "fieldName" => "cargoRecepientAddressCode",
                    "type" => "string",
                    "visibleOrder" => 2,
                    "hidden" => true,
                    "required" => false,
                    "presentation" => [
                        "size" => 67
                    ]
                ],
                [
                    "displayName" => "Номер дома",
                    "fieldName" => "cargoRecepientAddressHouseNumber",
                    "type" => "string",
                    "inputSize" => 5,
                    "visibleOrder" => 3,
                    "required" => true,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
                [
                    "displayName" => "Номер корпуса",
                    "fieldName" => "cargoRecepientAddressBuildingNumber",
                    "type" => "string",
                    "inputSize" => 5,
                    "visibleOrder" => 4,
                    "required" => false,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
                [
                    "displayName" => "Номер структуры",
                    "fieldName" => "cargoRecepientAddressStructureNumber",
                    "type" => "string",
                    "required" => false,
                    "visibleOrder" => 5,
                    "inputSize" => 5,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
				[
					"displayName" => "Квартира (офис)",
					"fieldName" => "cargoRecepientAddressCell",
					"type" => "int32",
					"required" => false,
                    "visibleOrder" => 6,
                    "inputSize" => 15,
                    "presentation" => [
                        "size" => 33
                    ]
				],
                [
                    "fieldName" => "recepientWorkTimeStart",
                    "type" => "enum",
                    "inputSize" => 5,
                    "visibleOrder" => 7,
                    "presentation" => [
                        "size" => 50
                    ],
                    "displayname" => "Время доставки груза с ",
                    "variants" => $this->aStartTimeVariants
                ],
                [
                    "fieldName" => "recepientWorkTimeEnd",
                    "type" => "enum",
                    "inputSize" => 5,
                    "visibleOrder" => 8,
                    "presentation" => [
                        "size" => 50
                    ],
                    "displayname" => " по ",
                    "variants" => $this->aEndTimeVariants
                ]
			]
		];

		$baseOptions['groups']['where']['aoptions'] = array_merge($baseOptions['groups']['where']['aoptions'],$aWhereOptions);


		$aRetResults = array();
                
                //////////////////////////////////////
                // fetch additional services
                //////////////////////////////////////
                
                $sAddOptsURL = "https://api.dellin.ru/v1/public/request_services.json";
                $aAddOpts = array("appKey" => $this::AppKey);

                $oAddOptsReq = curl_init($sAddOptsURL);
                curl_setopt_array($oAddOptsReq, array(
                                    CURLOPT_POST => TRUE,
                                    CURLOPT_RETURNTRANSFER => TRUE,
                                    CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/json'
                                    ),
                                    CURLOPT_POSTFIELDS => json_encode($aAddOpts)
                                ));
                $sAddOptsJSON = curl_exec($oAddOptsReq);
                $sAddOptsListURI = json_decode($sAddOptsJSON)->url;
                $aAddOpts = file($sAddOptsListURI);

        /*
                $aAddOptVariants = array();
//                print_r($aAddOpts); die();
                foreach ($aAddOpts as $option)
                {
                    $aParsedOpt = explode(',',$option);
                    
                    $iOptNumber = intval(str_replace('"','',$aParsedOpt[0]));
                    $sOptText = trim(str_replace('"', '', $aParsedOpt[2]));
                    
                    if (($iOptNumber <= 0) or ($iOptNumber == 1) or ($iOptNumber == 33)) {
                        continue;
                    }
                    
                    $aAddOptVariants[] = array(
                        "number" => $iOptNumber,
                        "visible" => $sOptText
                    );
                }

                $aRetResults['addOldOptions'] = array(
                    "displayName" => "Дополнительные услуги",
                    "fieldName" => "addOptions",
                    "recalcTotalPrice" => true,
                    "type" => "multienum",
                    "variants" => $aAddOptVariants
                );
*/

        $aAddOptVariants = array();

        $aAddLoadOptions = array();

        $aAddUnLoadOptions = array();

        $aAddOptions = array();


        foreach ($this->aAddLoadVariants as $item)
        {
            $newOpts=array();
            $newItem=$item;
            if(count($item['variants'])>0)
            {
                foreach ($item['variants'] as $opt)
                {
                    $newOpts[]=$opt;
                }
            }

            if(count($newOpts)>0)
			{
                $newItem['variants']=$newOpts;
			}
            $aAddLoadOptions[] = $newItem;
        }

        $aAddLoadOptions["openLoadLevelBlock"]= [
            "id"=>"openLoadLevelBlock",
            "name"=>"openLoadLevelBlock",
            "displayName" => "Этаж",
            "fieldName" => "openLoadLevel",
            "recalcTotalPrice" => true,
            "is_option"=> true,
            "hidden" => true,
            "visibleOrder" => 14,
            "type" => "int",
        ];

        $aAddLoadOptions["openLoadTransferBlock"]= [
                "id"=>"openLoadTransferBlock",
                "name"=>"openLoadTransferBlock",
                "displayName" => "Пронос в метрах",
                "fieldName" => "openLoadTransfer",
            	"recalcTotalPrice" => true,
            	"is_option"=> true,
                "hidden" => true,
                "visibleOrder" => 15,
                "type" => "int",
         ];

        /*
        $aAddLoadOptVariants[] = array(
            "displayName" => "Дополнительные услуги погрузки",
            "fieldName" => "addLoadOptions",
            "recalcTotalPrice" => true,
            "type" => "multienum",
            "variants" => $aAddLoadOptions
        );
*/

        $baseOptions['groups']['from']['aoptions']['derivalAddressBlock']['aoptions'] =
			array_merge($baseOptions['groups']['from']['aoptions']['derivalAddressBlock']['aoptions'],$aAddLoadOptions);

        foreach ($this->aAddUnLoadVariants as $item)
        {
            $newOpts=array();
            $newItem=$item;
            if(count($item['variants'])>0)
            {
                foreach ($item['variants'] as $opt)
                {
                    $newOpts[]=$opt;

                }
            }

            if(count($newOpts)>0)
            {
                $newItem['variants']=$newOpts;
            }
            $aAddUnLoadOptions[] = $newItem;
        }

        $aAddUnLoadOptions["openUnLoadLevelBlock"]=
            [
            "id"=>"openUnLoadLevelBlock",
            "name"=>"openUnLoadLevelBlock",
            "displayName" => "Этаж",
            "fieldName" => "openUnloadLevel",
            "recalcTotalPrice" => true,
            "is_option"=> true,
            "visibleOrder" => 14,
            "hidden" => true,
            "type" => "int",
        ];

        $aAddUnLoadOptions["openUnLoadTransferBlock"]= [
                "id"=>"openUnLoadTransferBlock",
                "name"=>"openUnLoadTransferBlock",
                "displayName" => "Пронос в метрах",
                "fieldName" => "openUnloadTransfer",
            	"recalcTotalPrice" => true,
                "is_option"=> true,
                "visibleOrder" => 15,
                "hidden" => true,
                "type" => "int",
        ];

        $baseOptions['groups']['where']['aoptions']['arrivalAddressBlock']['aoptions'] =
            array_merge($baseOptions['groups']['where']['aoptions']['arrivalAddressBlock']['aoptions'],
                $aAddUnLoadOptions);


        foreach ($this->aAdditionalVariants as $item)
        {
            $newOpts=array();
            $newItem=$item;
            if(count($item['variants'])>0)
            {
                foreach ($item['variants'] as $opt)
                {
                    $newOpts[]=$opt;
                }
            }

            if(count($newOpts)>0)
            {
                $newItem['variants']=$newOpts;
            }
            $aAddOptions[] = $newItem;
        }

		$aAddOptions["bagCountBlock"]=[
            "id"=>"bagCountBlock",
            "name"=>"bagCountBlock",
            "displayName" => "Количество мешков",
            "fieldName" => "bagCount",
            "type" => "int",
            "visibleOrder" => 13,
            "hidden" => true,
            "recalcTotalPrice" => true,
            "value" => 0,
        ];

        $aAddOptions["boxCountBlock"]=   [
                "id"=>"boxCountBlock",
                "name"=>"boxCountBlock",
                "displayName" => "Количество коробок",
                "fieldName" => "boxCount",
                "type" => "int",
                "visibleOrder" => 14,
                "hidden" => true,
                "recalcTotalPrice" => true,
                "value" => 0,
            ];
/*
        $aAddOptVariants[] = array(
            "displayName" => "Дополнительные услуги погрузки",
            "fieldName" => "addLoadOptions",
            "recalcTotalPrice" => true,
            "type" => "multienum",
            "variants" => $aAddOptions
        );
*/
        $aRetResults['addOptions'] = $aAddOptions;


                $aGroupAdd = array();
				$aGroupAdd['name'] = 'Дополнительные опции';
				$aGroupAdd['visibleOrder'] = 12;
				$aGroupAdd['aoptions'] = $aRetResults;

				$baseOptions['groups']['addOptions'] = $aGroupAdd;

            	unset($baseOptions['groups']["when"]);

                return $baseOptions;
        }

        /** LOGIN Get sessionID*/
        private function Login()
		{
            $sLoginURL = "https://api.dellin.ru/v1/customers/login.json";
            $aLoginOpts = array(
                "appKey" => $this::AppKey,
                "login" => $this->sLogin,
                "password" => $this->sPassword
            );

            $oLoginAnswer = $this->CallPOSTJSON($sLoginURL, $aLoginOpts);

            if(!IS_PRODUCTION)
            {
                echo 'Login result<br>';
                var_dump($oLoginAnswer);
                echo '<br>';
            }
            // if login fails
            if (isset($oLoginAnswer->errors))
                $errors[] = $oLoginAnswer->errors;

            if (count($errors) > 0) {
                return $errors;
            }

            return $oLoginAnswer->sessionID;
        }
            ////////////////////////////////////////////////////////////////////
            
        public function GetRequisites() {
                //parent::GetRequisites();
                
                $aRetVal = array(
                    "bankSpecialId" => '112112'
                );
        }
            
            ////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////
            
            private function CallPOSTJSON($sURL, $oJSON)
            {
                $oReq = curl_init($sURL);
                curl_setopt_array($oReq, array(
                                    CURLOPT_POST => TRUE,
                                    CURLOPT_RETURNTRANSFER => TRUE,
                                    CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/json'
                                    ),
                                    CURLOPT_POSTFIELDS => json_encode($oJSON)
                                ));

                $sAnswer = curl_exec($oReq);

                if(IS_DEBUG) { echo '<br>'.$sURL.'<br>'; echo json_encode($oJSON),'<br><br>';}

                return json_decode($sAnswer);

            }

			/**
			 * @param $requestId
			 * @param $orderId
			 * @return boolean value or error array
			 */
            public function SavePdf($requestId,$orderId)
            {

                $loginResult = $this::Login();

                if (is_array($loginResult))
                    return $loginResult;

                $sSessionID = $loginResult;

                $mysqlHandle = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

                $sAddOptsURL = "https://api.dellin.ru/v1/customers/request/pdf.json";
                $aAddOpts = array("appKey" => $this::AppKey,
                    "sessionID" => $sSessionID,
                    "requestID" => "$requestId"
                );

                $oAddOptsReq = curl_init($sAddOptsURL);
                curl_setopt_array($oAddOptsReq, array(
                    CURLOPT_POST => TRUE,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                    CURLOPT_POSTFIELDS => json_encode($aAddOpts)
                ));
                $sAddOptsJSON = curl_exec($oAddOptsReq);


				$file_name = 'ttn_#'.$orderId .'.pdf';
                $aPdf = json_decode($sAddOptsJSON);

                $data = base64_decode($aPdf->base64);
                $file = TTN_PATH .$file_name;

                $insertSql = "
					INSERT INTO ".DB_DOCUMENTS_TABLE."
					( companyId, timestamp, name, content)
					VALUES 
					( 32, NOW(), '".$file_name."', '".$aPdf->base64."')";

                if(IS_DEBUG)
                // echo '<br>',$insertSql,'<br>';
                    echo $file,'<br>';

                $mysqlHandle->query($insertSql);

                $success = file_put_contents($file, $data);

                if(IS_DEBUG)
                    echo $success,'<br>';

                return $success;
            }
            /**  CheckCounteragent */

	private function CheckPrivateCounteragent($sSessionID,$userName,$documentType,$documentNumber)
            {
                $type = "private";
                $result = array();
                $iCounteragentId = "";
                $isExist = false;
                $error = '';

                $sAddPhysicalAddrURL = "https://api.dellin.ru/v1/customers/book/counteragents.json";
                $aAddPhysicalAddrOpts = array(
                    "appKey" => $this::AppKey,
                    "sessionID" => $sSessionID,
                    "WithAnonym" => "true"
                );

                $oAgents = $this->CallPOSTJSON($sAddPhysicalAddrURL, $aAddPhysicalAddrOpts);

                if (isset($oAgents->errors))
				{
                    $reqResult["counteragentId"] = 0;
                    $reqResult["status"] = 'error';
                    $reqResult["error"] = $oAgents->errors;
                }

                foreach ($oAgents as $oAgent)
                {
                    $agent = $oAgent->counteragent;
                    if ($agent->type == $type) {
                        if ($agent->name == $userName && $agent->document->type ==$documentType)
                        {
                            switch($documentType)
                            {
                                case "passport":
                                    if($agent->document->serial== substr($documentNumber,0,4)
                                    && $agent->document->number==substr($documentNumber,4))
                                    {
                                        $iCounteragentId = $agent->id;
                                        $isExist = true;
                                    }
                                    break;
                                case "drivingLicence":
                                case "foreignPassport":
                                    if($agent->document->number== $documentNumber)
                                    {
                                        $iCounteragentId = $agent->id;
                                        $isExist = true;
                                    }
                                    break;
                            }
                        }
                    }
                    if($isExist)
                    {
                        break;
                    }
                }

                if ($isExist) {
                    $reqResult["counteragentId"] = $iCounteragentId;
                    $reqResult["status"] = 'ok';
                    $reqResult["error"] = '';
                } else {

                    $reqResult["counteragentId"] = 0;
                    $reqResult["status"] = 'not_exist';
                    $reqResult["error"] = '';
                }
                return $reqResult;
            }

    private	function CheckJurCounteragent($sSessionID,$companyINN)
			{
				$type= "juridical";
				$result = array();
				$iCounteragentId = "";
				$isExist = false;
				$error ='';

				$sAddPhysicalAddrURL = "https://api.dellin.ru/v1/customers/book/counteragents.json";
				$aAddPhysicalAddrOpts = array(
                    "appKey" => $this::AppKey,
                    "sessionID" => $sSessionID,
					"WithAnonym"=> "true"
				);

				$oAgents = $this->CallPOSTJSON($sAddPhysicalAddrURL, $aAddPhysicalAddrOpts);

				if (isset($oAgents->errors))
					$error = $oAgents->errors;

				foreach($oAgents as $oAgent)
				{
					$agent = $oAgent->counteragent;
					if($agent->type==$type)
					{
						if($agent->inn==$companyINN)
						{
							$iCounteragentId = $agent->id;
							$isExist = true;
							break;
						}
					}
				}

				if ($isExist)
				{
					$reqResult["counteragentId"]=$iCounteragentId;
					$reqResult["status"]='ok';
					$reqResult["error"]='';
				}
				else
				{
					$reqResult["counteragentId"]= 0;
					$reqResult["status"]='error';
					$reqResult["error"]=$error;
				}
				return $reqResult;
			}

    private	function CreateJuridicalCounteragent($sSessionID,
												 $companyName,
												 $companyINN,
												 $formName,
												 $countryUID,
												 $cityName,
												 $address,
												 $addressCell
			)
			{
				$house = "";
                $building = "";
                $structure = "";


				$addressArr = explode(',',$address);
				if (count($addressArr)>1)
				{
                    $house = trim($addressArr[count($addressArr)-1]);
				}

				if(IS_DEBUG)
				{
					echo '<br>CreateJuridicalCounteragent function input data:';
					echo 'companyName: ',$companyName,
					'<br>companyINN: ',$companyINN,
					'<br>formName: ',$formName,
					'<br>countryUID: ',$countryUID,
					'<br>cityName: ',$cityName,
					'<br>address: ',$address,
					'<br>addressCell: ',$addressCell;
					echo '<br>';
				}


  				 $prevStreet = $cityName.', '.$address;

                 $city = GetCityKLADRCode($prevStreet,'AIzaSyB79Ic3jTDxYacK477EAkTgk7xkO--hNu8');
                 if(IS_DEBUG) var_dump($city);
                 $addressHouse = $city["house"];
                 $streetInfo = $this->GetStreetKLADRCode2($prevStreet,$city["cityID"],'AIzaSyB79Ic3jTDxYacK477EAkTgk7xkO--hNu8');

                if(IS_DEBUG)
                {
                    echo '<br>';
                    var_dump($city);
                    echo '<br>';
                }

                 if (isset($addressHouse))
				 {
                     $house =$addressHouse;
				 }

                 $ch_arr=explode(' ',$addressHouse);

                if (count($ch_arr)>0)
				{
                    //$house =$ch_arr [0];
                    $structure_pos = array_search("корпус", $ch_arr);
                    if($structure_pos>0)
					{
                        $structure =  $ch_arr[$structure_pos];
					}
                    $building_pos=array_search("строение", $ch_arr);
                    if($building_pos>0)
					{
                        $building = $ch_arr[$building_pos];
					}
				}
				else
				{
                    $reqResult["counteragentId"] = 0;
                    $reqResult["status"]='error';
                    $reqResult["error"]='Номер дома для Юр. лица не указан или не определён';
				}

                $juridicalAddress = array();

                if(IS_DEBUG)
                {
                    echo '<br>Data before counteragents update:';
                    var_dump($streetInfo);
                    echo 'house: ',$house,
                    '<br>building: ',$building,
                    '<br>structure: ',$structure,
                    '<br>addressCell: ',$addressCell;
                    echo '<br>';
                }

                preg_match('/\d+/', $house, $house);
                preg_match('/\d+/', $addressHouse, $addressHouse);

                if($streetInfo["street_kladr"]=='')
                {
                    $juridicalAddress =  array(
                        "customStreet"=>  array(
                            "code" => "7800000000000000000000000",
                            "street" => $address
                        ),
                        "house" =>  strval($house),
                        "building" =>  strval($building),
                        "structure"=>  strval($structure),
                        "flat" => strval($addressCell));
                }
                else
                {
                    $streetCode =  $streetInfo["street_kladr"];

                    $juridicalAddress = array(
                        "street"=> $streetCode,
                        "house" =>  strval($addressHouse),
                        "building" =>  strval($building),
                        "structure"=>  strval($structure),
                        "flat" =>  strval($addressCell));
                }

                $reqResult = array();
                $sAddPhysicalAddrURL = "https://api.dellin.ru/v1/customers/book/counteragents/update.json";
                $aAddPhysicalAddrOpts = array(
					"appKey" => $this::AppKey,
                    "sessionID" => $sSessionID,
                    "name" => $companyName,
                    "inn"=> strval($companyINN),

                    "customForm"=> array(
                        "formName" => $formName,
                        "countryUID" => $countryUID,
                        "juridical"=> "true"
                    ),

                    "juridicalAddress"=> $juridicalAddress,
                );

                $oAddPhysicalAddrAnswer =$this->CallPOSTJSON($sAddPhysicalAddrURL, $aAddPhysicalAddrOpts);

                if(IS_DEBUG) {
                    echo "<br>CreateJuridicalCounteragent <br>";
                    var_dump($oAddPhysicalAddrAnswer);
                    echo "<br>";
                    var_dump(!isset($oAddPhysicalAddrAnswer->success));
                    echo "<br>";
                }

                $errTmp = Arr2Str('Документ отправителя - ', $oAddPhysicalAddrAnswer->errors);
                if ($errTmp)
                    $errors[] = $errTmp;

                //print_r($aAddPhysicalAddrOpts); die();

                if (!isset($oAddPhysicalAddrAnswer->success)) {
                    $reqResult["counteragentId"]= 0;
                    $reqResult["status"]='error';
                    $reqResult["error"]=implode($errors);
                }
                else
                {
                    if(IS_DEBUG) {
                        echo "<br>";
                        var_dump($oAddPhysicalAddrAnswer->success->counteragentID);
                        echo "<br>";
                    }
                    $reqResult["counteragentId"] = $oAddPhysicalAddrAnswer->success->counteragentID;
                    $reqResult["status"]='ok';
                    $reqResult["error"]='';
                }
				//var_dump($reqResult); die;
                return $reqResult;
			}

    private	function GetStreetKLADRCode2($streetName, $cityID, $sAPIKEY)
			{
				mb_regex_encoding("UTF-8");
				mb_internal_encoding("UTF-8");
				date_default_timezone_set('UTC');

				if (trim($streetName) == "")
					return "";

				$geocodeURL = 'https://maps.googleapis.com/maps/api/geocode/json?address='
					. urlencode($streetName)
					. '&language=ru&key=' . $sAPIKEY;
				$sTmp = json_decode(file_get_contents($geocodeURL));
				//echo '<pre>';
                //var_dump($sTmp); echo '<br>';
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
				//var_dump($oRetVal); echo '<br>';

				$mysqlHandle = new mysqlii(LANG_DB_HOST, LANG_DB_LOGIN, LANG_DB_PASSWORD, KLADR_DB_NAME);

				$mysqlHandle->query("SET NAMES utf8 COLLATE utf8_unicode_ci");
				$mysqlHandle->set_charset("utf8");

				if (!$mysqlHandle)
					return false;

				if (!empty($oRetVal['street']))
				{
					$streetNames= explode(' ',$oRetVal['street']);

                    $street='';
                    $count = count($streetNames)-1;
					for($i=0; $i < $count;$i++)
                    {
                        $street.=$streetNames[$i].'%';
                    }

					$requestText = "SELECT * FROM `dellin_kladr_street` WHERE `cityID` = $cityID AND UPPER(`searchString`) LIKE \"" .
						$mysqlHandle->real_escape_string(mb_strtoupper($street)) . "%\" " .
						"ORDER BY (char_length(code) - char_length(replace(code,'0',''))) DESC limit 1";

					$resultAddr = $mysqlHandle->query($requestText);

					//echo '<br><br>',$requestText,'<br><br>';

					if ($resultAddr->num_rows > 0)
					{
						$col = $resultAddr->fetch_assoc();
						$oRetVal['street_kladr'] = $col['code'];
					}
				}

				$mysqlHandle->close();

				return $oRetVal;
			}

    private		function CreatePrivateCounteragent($sSessionID, $userFIO, $documentType,$documentNumber)
			{
				$reqResult = array();
				$sAddPhysicalAddrURL = "https://api.dellin.ru/v1/customers/book/counteragents/update.json";

                if($documentType=='passport')
                {
                    $aAddPhysicalAddrOpts = array(
                        "appKey" => $this::AppKey,
                        "sessionID" => $sSessionID,
                        "form" => "0xAB91FEEA04F6D4AD48DF42161B6C2E7A",
                        "document" => array(
                            "type" => $documentType,
                            "number" => substr($documentNumber,4),
                            "serial" => substr($documentNumber,0,4),
                        ),
                        "name" => $userFIO
                    );
                }
                else
                {
                    $aAddPhysicalAddrOpts = array(
                        "appKey" => $this::AppKey,
                        "sessionID" => $sSessionID,
                        "form" => "0xAB91FEEA04F6D4AD48DF42161B6C2E7A",
                        "document" => array(
                            "type" => $documentType,
                            "number" => $documentNumber
                        ),
                        "name" => $userFIO
                    );
                }

				$oAddPhysicalAddrAnswer = $this->CallPOSTJSON($sAddPhysicalAddrURL, $aAddPhysicalAddrOpts);

				$errTmp = Arr2Str('Документ отправителя - ', $oAddPhysicalAddrAnswer->errors);
				if ($errTmp)
					$errors[] = $errTmp;
				if(IS_DEBUG) {
                    echo "<br>CreatePrivateCounteragent<br>";
                    var_dump($oAddPhysicalAddrAnswer->success);
                    echo "<br>";
                }

				if (!isset($oAddPhysicalAddrAnswer->success)) {

					$reqResult["counteragentId"]= 0;
					$reqResult["status"]='error';
					$reqResult["error"]=implode($errors);
				}
				else
				{
					$reqResult["counteragentId"] = $oAddPhysicalAddrAnswer->success->counteragentID;
					$reqResult["status"]=$oAddPhysicalAddrAnswer->success->state;
					$reqResult["error"]='';
				}

				return $reqResult;
			}

    private		function GetTerminals()
			{
                $sAddOptsURL = "https://api.dellin.ru/v2/public/terminals.json";
                $aAddOpts = array("appKey" => $this::AppKey);

                $oAddOptsReq = curl_init($sAddOptsURL);
                curl_setopt_array($oAddOptsReq, array(
                    CURLOPT_POST => TRUE,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                    CURLOPT_POSTFIELDS => json_encode($aAddOpts)
                ));
                $sAddOptsJSON = curl_exec($oAddOptsReq);
                $aTerminals = json_decode($sAddOptsJSON);

                return $aTerminals;
			}

    private function CheckAddress($sSessionID,
                          $iCounteragentID,
                          $sCityName,
                          $sAddress,
                          $iTerminalId,
                          $streetCode='',
                          $house='')
    {
        $sAddressType = "delivery";
        $result = array();
        $iAddressID = "";
        $isExist = false;
        $error ='';

		if(IS_DEBUG) {
			echo '<br>CheckAddress<br>';
			echo 'sCityName: '.$sCityName.'<br>';
			echo 'sAddress: '.$sAddress.'<br>';
            echo 'sHouse: '.$house.'<br>';
            echo 'sStreetCode'.$streetCode.'<br>';
		}

        $sAddressURL = "https://api.dellin.ru/v1/customers/book/addresses.json";
        $aAddressOpts = array(
            "appKey" => $this::AppKey,
            "sessionID" => $sSessionID,
            "counteragentID"=>$iCounteragentID
        );

        $oAddresses = $this->CallPOSTJSON($sAddressURL, $aAddressOpts);

        if (isset($oAddresses->errors))
            $error = $oAddresses->errors;

        foreach($oAddresses as $oAddress)
        {
            $address = $oAddress->address;
            if (IS_DEBUG) echo 'address->code',$address->code,' $address->id',$address->id,'<br>';

            if($address!='')
            {
                if($address->code==$streetCode && $address->house==$house)
                {
                    if (IS_DEBUG) echo '<br>Exist<br>';
                    $iAddressID = intval($address->id);
                    $isExist = true;
                    break;
                }
            }
            else
            {

                $s = GetCityKLADRCode($sCityName . ', ' . $sAddress, GAPI_KEY);

                if (IS_DEBUG) var_dump($s);

                $f = GetCityKLADRCode($address->address, GAPI_KEY);

                if (IS_DEBUG) echo '<br>' . $f["city"] . ' = ' . $s["city"] . '<br>' .
                    $f["street"] . ' = ' . $s["street"] . '<br>' .
                    strval($address->house) . ' = ' . $s["house"] . '<br>';

                if ($f["city"] == $s["city"]
                    && $f["street"] == $s["street"]
                    && intval($address->house) == intval($s["house"])
                ) {
                    if (IS_DEBUG) echo '<br>Exist<br>';
                    $iAddressID = intval($address->id);
                    $isExist = true;
                    break;
                }
            }
        }

        if ($isExist)
        {
            $reqResult["addressID"]=$iAddressID;
            $reqResult["status"]='ok';
            $reqResult["error"]='';
        }
        else
        {
            $reqResult["addressID"]= 0;
            $reqResult["status"]='bad';
            $reqResult["error"]='';
        }

        if ($error!='')
        {
            $reqResult["addressID"]= 0;
            $reqResult["status"]='error';
            $reqResult["error"]=$error;
        }

        return $reqResult;

    }

	private function CreateAddress(
        $sSessionID,
        $iCounteragentID,
        $sCityName,
        $sStreetName,
        $sAddressStreetCode='',
        $sAddressHouse='',
        $sAddressBuilding='',
        $sAddressStructure='',
        $sAddressFlat=''
        )
    {
        $fullAddress = $sCityName.', '.$sStreetName;

        preg_match('/\d+/', $sAddressHouse, $sAddressHouse);

        if($sAddressStreetCode!='')
        {
            $sAddAddrURL = "https://api.dellin.ru/v1/customers/book/addresses/update.json";
            $aAddAddrOpts = array(
                "appKey" => $this::AppKey,
                "sessionID" => $sSessionID,
                "counteragentID" => $iCounteragentID,
                "street"=>$sAddressStreetCode,
                "house"=>strval($sAddressHouse),
                "building"=>$sAddressBuilding,
                "structure"=>$sAddressStructure,
                "flat"=>strval($sAddressFlat)
            );

            $oAddAddrAnswer = $this->CallPOSTJSON($sAddAddrURL, $aAddAddrOpts);

        }
        else {
            if (IS_DEBUG) {
                echo "<br> Request KLADR:<br>";
                var_dump($fullAddress);
                echo "<br>";
            }

            $aKLADR = GetCityKLADRCode($fullAddress, GAPI_KEY);

            if (IS_DEBUG) {
                echo "<br> Result KLADR:<br>";
                echo "<br> $fullAddress<br>";
                var_dump($aKLADR);

                echo "<br>";
            }

            $bld2 = array();
            preg_match_all('/\d+\D+(\d+)/', $aKLADR["house"], $bld2);

            if ($aKLADR["city_kladr"] == '') {

                $reqResult["counteragentId"] = 0;
                $reqResult["status"] = 'error';
                $reqResult["error"] = 'Не могу найти код КЛАДР для адреса отправителя.';
                return $reqResult;
            }

            if ($aKLADR["street"] == '') {
                $reqResult["counteragentId"] = 0;
                $reqResult["status"] = 'error';
                $reqResult["error"] = 'Не задана улица или отсутствует в реестре улиц.';
                return $reqResult;
            }

            // Add sender address
            $sAddAddrURL = "https://api.dellin.ru/v1/customers/book/addresses/update.json";
            $aAddAddrOpts = array(
                "appKey" => $this::AppKey,
                "sessionID" => $sSessionID,
                "counteragentID" => $iCounteragentID,
                "customStreet" => array(
                    "code" => $aKLADR["city_kladr"],
                    "street" => $aKLADR["street"],
                ),
                "house" => intval($aKLADR["house"]) . '',
                "building" => $bld2[1][0]
            );


            $oAddAddrAnswer = $this->CallPOSTJSON($sAddAddrURL, $aAddAddrOpts);

        }

        $errTmp = Arr2Str('Адрес отправителя - ',$oAddAddrAnswer->errors);

        if (isset($oAddAddrAnswer->success))
        {
            $reqResult["addressID"]=intval($oAddAddrAnswer->success->addressID);
            $reqResult["status"]='ok';
            $reqResult["error"]='';
        }
        else
        {
            $reqResult["counteragentId"]= 0;
            $reqResult["status"]='error';
            $reqResult["error"]=$errTmp;
        }
        return $reqResult;
    }


    private function UpdateTerminalOfContragent(
        $sSessionID,
        $iCounteragentID,
        $iTerminalID)
    {

        // Add sender address
        $sAddAddrURL = "https://api.dellin.ru/v1/customers/book/addresses/update.json";
        $aAddAddrOpts = array(
            "appKey" => $this::AppKey,
            "sessionID" => $sSessionID,
            "counteragentID" => $iCounteragentID,
            "terminal_id"=>$iTerminalID
        );
       // var_dump($aAddAddrOpts); echo '<br>';
        $oAddAddrAnswer = $this->CallPOSTJSON($sAddAddrURL, $aAddAddrOpts);
        //var_dump($oAddAddrAnswer); echo '<br>';
        $errTmp = Arr2Str('Адрес - ',$oAddAddrAnswer->errors);

        if (isset($oAddAddrAnswer->success))
        {
            $reqResult["addressID"]=intval($oAddAddrAnswer->success->addressID);
            $reqResult["status"]='ok';
            $reqResult["error"]='';
        }
        else
        {
            $reqResult["addressID"]= 0;
            $reqResult["status"]='error';
            $reqResult["error"]=$errTmp;
        }
        return $reqResult;
    }

    private function UpdatePhoneOfContragent(
        $sSessionID,
        $sAddressID,
        $sPhoneNumber)
    {

        // Add sender address
        $sAddAddrURL = "https://api.dellin.ru/v1/customers/book/phones/update.json";
        $aAddAddrOpts = array(
            "appKey" => $this::AppKey,
            "sessionID" => $sSessionID,
            "addressID" => strval($sAddressID),
            "phoneNumber"=>strval($sPhoneNumber)
        );

        $oAddAddrAnswer = $this->CallPOSTJSON($sAddAddrURL, $aAddAddrOpts);

        $errTmp = Arr2Str('Адрес - ',$oAddAddrAnswer->errors);

        if (isset($oAddAddrAnswer->success))
        {
            $reqResult["phoneID"]=intval($oAddAddrAnswer->success->phoneID);
            $reqResult["status"]='ok';
            $reqResult["error"]='';
        }
        else
        {
            $reqResult["phoneID"]= 0;
            $reqResult["status"]='error';
            $reqResult["error"]=$errTmp;
        }
        return $reqResult;
    }

    private function UpdateContactPerson(
        $sSessionID,
        $sAddressID,
        $sContactFIO)
    {

        // Add sender address
        $sAddAddrURL = "https://api.dellin.ru/v1/customers/book/contacts/update.json";
        $aAddAddrOpts = array(
            "appKey" => $this::AppKey,
            "sessionID" => $sSessionID,
            "addressID" => strval($sAddressID),
            "contact"=>$sContactFIO
        );

        $oAddAddrAnswer = $this->CallPOSTJSON($sAddAddrURL, $aAddAddrOpts);

        $errTmp = Arr2Str('Адрес - ',$oAddAddrAnswer->errors);

        if (isset($oAddAddrAnswer->success))
        {
            $reqResult["personID"]=intval($oAddAddrAnswer->success->personID);
            $reqResult["status"]='ok';
            $reqResult["error"]='';
        }
        else
        {
            $reqResult["personID"]= 0;
            $reqResult["status"]='error';
            $reqResult["error"]=$errTmp;
        }
        return $reqResult;
    }
    		/////////////////////////////////////////////////////////////////////////
public function MakeOrder($sCityFrom,
                          $sCityTo,
                          $sCargoFromZip,
                          $sCargoToZip,
                          $sCargoFromRegion,
                          $sCargoToRegion,
                          $weight,
                          $vol,
                          $insPrice,
                          $length,
                          $width,
                          $height,
                          $cargoName,
                          $cargoDate,
                          $oOptions,

                          $isRecipientJur,
                          $sRecipientUserFIO,
                          $iRecipientDocumentTypeId,
                          $sRecipientDocumentNumber,
                          $sRecipientPhone,
                          $sRecipientEmail,
                          $iRecipientTerminalID,
                          $sRecipientCompanyName,
                          $sRecipientCompanyFormShortName,
                          $sRecipientCompanyINN,
                          $sRecipientCompanyAddress,
                          $sRecipientCompanyAddressCell,
                          $sRecipientCompanyPhone,
                          $sRecipientCompanyEmail,
                          $sRecipientContactFIO,

                          $sRecipientAddress,
                          $sRecipientAddressCell,

                          $isSenderJur,
                          $sSenderUserFIO,
                          $iSenderDocumentTypeId,
                          $sSenderDocumentNumber,
                          $sSenderPhone,
                          $sSenderEmail,
                          $iSenderTerminalID,
                          $sSenderCompanyName,
                          $sSenderCompanyFormShortName,
                          $sSenderCompanyINN,
                          $sSenderCompanyAddress,
                          $sSenderCompanyAddressCell,
                          $sSenderCompanyPhone,
                          $sSenderCompanyEmail,
                          $sSenderContactFIO,

                          $sSenderAddress,
                          $sSenderAddressCell,

                          $isDerivalCourier,
                          $isArrivalCourier,
                          $dCargoDesireDate,
                          $dCargoDeliveryDate)
{

}

            ////////////////////////////////////////////////////////////////////////
            
            public function MakeOrderWithAddressDelivery($sCityFrom,
                                      $sCityTo,
                                      $sCargoFromZip,
									  $sCargoToZip,
									  $sCargoFromRegion,
									  $sCargoToRegion,
                                      $weight,
                                      $vol,
                                      $insPrice,
                                      $length,
                                      $width,
                                      $height,
                                      $cargoName,
                                      $cargoDate,
                                      $oOptions,

                                      $isRecipientJur,
                                      $sRecipientUserFIO,
                                      $iRecipientDocumentTypeId,
                                      $sRecipientDocumentNumber,
                                      $sRecipientPhone,
                                      $sRecipientEmail,
			                          $iRecipientTerminalID,
                                      $sRecipientCompanyName,
                                      $sRecipientCompanyFormShortName,
                                      $sRecipientCompanyINN,
                                      $sRecipientCompanyAddress,
                                      $sRecipientCompanyAddressCell,
                                      $sRecipientCompanyPhone,
                                      $sRecipientCompanyEmail,
                                      $sRecipientContactFIO,
                                      $sRecipientAddress,
                                      $sRecipientAddressCode ,
                                      $sRecipientAddressHouseNumber ,
                                      $sRecipientAddressBuildingNumber ,
                                      $sRecipientAddressStructureNumber ,
                                      $sRecipientAddressCell,

                                      $isSenderJur,
                                      $sSenderUserFIO,
                                      $iSenderDocumentTypeId,
                                      $sSenderDocumentNumber,
                                      $sSenderPhone,
                                      $sSenderEmail,
                                      $iSenderTerminalID,
                                      $sSenderCompanyName,
                                      $sSenderCompanyFormShortName,
                                      $sSenderCompanyINN,
                                      $sSenderCompanyAddress,
                                      $sSenderCompanyAddressCell,
                                      $sSenderCompanyPhone,
                                      $sSenderCompanyEmail,
                                      $sSenderContactFIO,

                                      $sSenderAddress,
                                      $sSenderAddressCode ,
                                      $sSenderAddressHouseNumber ,
                                      $sSenderAddressBuildingNumber ,
                                      $sSenderAddressStructureNumber ,
                                      $sSenderAddressCell,

									  $isDerivalCourier,
									  $isArrivalCourier,
                                      $dCargoDesireDate,
                                      $dCargoDeliveryDate

            )
			{
                //parent::MakeOrder($from, $to, $weight, $vol, $insPrice, $method, $length, $width, $height, $recepientUser, $recepientPassport, $recepientAddress, $recepientPhone, $recepientEmail, $senderUser, $senderPassport, $senderAddress, $senderPhone, $senderEmail, $oOptions);

                if(!isset($dCargoDesireDate))
				{
                    $dCargoDesireDate = $cargoDate;
				}

                $dCargoDesireDate+=10800;
                $cday = date("d", $dCargoDesireDate);
                $cmonth = date("m", $dCargoDesireDate);
                $cyear = date("Y", $dCargoDesireDate);
                $cdate=date("Y-m-d", $dCargoDesireDate);

                if(IS_DEBUG) {
                    echo '<pre>';
                    echo "$sSenderDocumentNumber<br>", date("Y-m-d", $dCargoDesireDate), "<br>";
                    echo date("d", $dCargoDesireDate), "<br>";
                    echo date("m", $dCargoDesireDate), "<br>";
                    echo date("Y", $dCargoDesireDate), "<br>";
                    echo
                    '$sSenderAddressCode='.$sSenderAddressCode,'<br>',
                    '$sSenderAddressHouseNumber='.$sSenderAddressHouseNumber,'<br>',
                    '$sSenderAddressBuildingNumber='.$sSenderAddressBuildingNumber,'<br>',
                    '$sSenderAddressStructureNumber='.$sSenderAddressStructureNumber,'<br>'

                    .'$sRecipientAddressCode='.$sRecipientAddressCode,'<br>'
                    .'$sRecipientAddressHouseNumber='.$sRecipientAddressHouseNumber,'<br>'
                    .'$sRecipientAddressBuildingNumber='.$sRecipientAddressBuildingNumber,'<br>'
                    .'$sRecipientAddressStructureNumber='.$sRecipientAddressStructureNumber,'<br>'

                    ;
                }

                /**
                // FETCH OPTIONS
                */
                $errors = array();

                $sRecipientUserFIO= trim($sRecipientUserFIO);

                $sSenderContactFIO= trim($sSenderContactFIO);

                // load/unload params
                $aSenderOptions = array();
                $aRecipientOptions = array();

                /*
                foreach ($oOptions as $sOName => $option)
                {
                    if (strpos($sOName,'senderLU') > -1)
                    {
                        $aParseUID = explode('_',$sOName);
                        $sUID = $aParseUID[1];
                        $aSenderOptions[] = array(
                            "uid" => $sUID . '',
                            "value" => $option . ''
                        );
                    }

                    if (strpos($sOName,'recepientLU') > -1)
                    {
                        $aParseUID = explode('_',$sOName);
                        $sUID = $aParseUID[1];
                        $aRecepientOptions[] = array(
                            "uid" => $sUID . '',
                            "value" => $option . ''
                        );
                    }
                }

                // additional services
                $addServices = array();
                foreach ($oOptions->addOptions as $addOption)
                {
                    $addServices[] = array(
                        "service" => $addOption . '',
                        "payer" => "1"
                    );
                }
                */

				/** LOGIN Get sessionID*/

                $loginResult = $this::Login();

                if (is_array($loginResult))
                	return $loginResult;

                if(IS_DEBUG) {
                    echo 'login result:';
                    var_dump($loginResult);
                    echo '<br>';
                }

                if (!isset($loginResult)) {
                    sleep(1);
                    $loginResult = $this::Login();

                    if (!isset($loginResult)) {
                        sleep(1);
                        $loginResult = $this::Login();
                        if (is_array($loginResult))
                            return $loginResult;
                        if (!isset($loginResult)) {
                            $errors[] = "Сессия не была создана для данного пользователя";
                            return $errors;
                        }
                    }
                }

                $sSessionID = $loginResult;

				/** End block LOGIN */
                // check adress (if we need to specify a terminal)
                $arrivalTerminal = intval($oOptions->arrivalTerminalId);

                /** COUNTERAGENTS  PARTS */

                /** Get/Add Sender to addressbook */

                $sCityFrom = $sCargoFromZip.', '.$sCityFrom.', '.$sCargoFromRegion;
                $sCityTo =  $sCargoToZip.', '.$sCityTo.', '.$sCargoToRegion;

                if(IS_DEBUG)
                	echo '$sCityFrom = '.$sCargoFromZip.', '.$sCityFrom.', '.$sCargoFromRegion.'<br>',
                        'sSenderAddress = '.$sSenderAddress.'<br>',

                        'sSenderAddressCell = '.$sSenderAddressCell.'<br>';

               // $city = GetCityKLADRCode($sCargoFromZip.', '.$sCityFrom.', '.$sCargoFromRegion,'AIzaSyB79Ic3jTDxYacK477EAkTgk7xkO--hNu8');
				//var_dump($city);
               // die;
                $isExist = false;
                $iCounteragentFromID ="";

                $recipientPhone='';
                $senderPhone = '';

                if($isSenderJur==1)
				{
                    if(IS_DEBUG) echo 'Sender Conteragent Jur. Check it in db and get ID.<br>';

                    $reqJurCounteragent =
                        $this->CheckJurCounteragent($sSessionID,$sSenderCompanyINN);

                    $senderPhone = $sSenderCompanyPhone;
                    if ($reqJurCounteragent->status=="ok")
					{
                        $iCounteragentFromID = $reqJurCounteragent["counteragentId"];
                        if(IS_DEBUG) echo 'Sender Conteragent Jur exist.<br>'.
                            'counteragentId='.$iCounteragentFromID.'<br>';
					}
					else
					{
                        if(IS_DEBUG) echo 'Sender Conteragent Jur not exist.<br>'.
                            'Create new jur <br>';
                        $reqJuridicalCounteragent=
                            $this->CreateJuridicalCounteragent($sSessionID,
                            $sSenderCompanyName,
                            $sSenderCompanyINN,
                            $sSenderCompanyFormShortName,
                            "0x8f51001438c4d49511dbd774581edb7a", // Россия
                            $sCityFrom,
                            $sSenderCompanyAddress,
                                intval($sSenderCompanyAddressCell));

                        if ($reqJuridicalCounteragent["status"]=="ok")
                        {
                            $iCounteragentFromID = $reqJuridicalCounteragent["counteragentId"];
                            if(IS_DEBUG) echo 'Sender Conteragent Jur created.<br>'.
                                'counteragentId='.$iCounteragentFromID.'<br>';
                        }
                        else
						{
                            $errors[] = $reqJuridicalCounteragent["error"];
                            return $errors;
						}
					}
				}
				else
				{
                    if(IS_DEBUG) echo 'Sender Conteragent Private. Check it in db and get ID.<br>';
                    /** Check CounterAgent on exist in system */
                    $senderPhone = $sSenderPhone;
                    $senderDocumentType = "passport";
                    switch ($iSenderDocumentTypeId) {
                        case 1:
                            $senderDocumentType = "passport";
                            break;
                        case 2:
                            $senderDocumentType = "drivingLicence";
                            break;
                        case 3:
                            $senderDocumentType = "foreignPassport";
                            break;
                    }

                    $checkResult =
						$this->CheckPrivateCounteragent
                    		($sSessionID, $sSenderUserFIO,$senderDocumentType,$sSenderDocumentNumber);

                    if($checkResult["status"] == 'error')
                    {
                        $errors[] = $checkResult["error"];
                        return $errors;
                    }

                    if ($checkResult["status"] == 'ok') {
                        $isExist = true;
                        $iCounteragentFromID = $checkResult["counteragentId"];
                        if(IS_DEBUG) echo 'Sender Conteragent Private is exist.<br>'.
                        'CounteragentFromID='.$iCounteragentFromID.'<br>';
                    }

                    if($checkResult["status"] == 'not_exist')
                    {
                        $isExist = false;
                        if(IS_DEBUG) echo 'Sender Conteragent Private not exist.<br>';
                    }

                    if (!$isExist) {



                        if(IS_DEBUG) echo 'Sender Conteragent Private start create.<br>';
                        $counteragentFrom =
                            $this->CreatePrivateCounteragent(
                                $sSessionID,
                                $sSenderUserFIO,
                                $senderDocumentType,
                                $sSenderDocumentNumber);

                        if(IS_DEBUG)
                        {
                            echo 'result CreatePrivateCounteragent for Sender:<br>';
                            var_dump($counteragentFrom);

                            echo '<br>';
                        }

                        if ($counteragentFrom["status"] == "new") {
                            $iCounteragentFromID = $counteragentFrom["counteragentId"];
                            if(IS_DEBUG) echo 'Sender Conteragent Private created.<br>',
                            'CounteragentFromID='.$iCounteragentFromID.'<br>';
                        }

                        if ($counteragentFrom["status"] == 'error') {
                            if(IS_DEBUG)
                            {
                                echo "Error Sender Sender <br>";
                                var_dump($errors);
                                echo "<br>";
                            }
                            $errors[] = $counteragentFrom["error"];
                        }
                    }
                }

                if(IS_DEBUG) {
                    echo "<br>CounteragentSenderID: $iCounteragentFromID<br>";
                	echo "Check Sender CounterAgent<br>"; var_dump($errors);}
                if (count($errors) > 0) {
                    return $errors;
                }

                /** Add Recipient to address book */

                $isExist = false;
                $iCounteragentToID = "";

				if($isRecipientJur==1)
				{
                    $reqJurCounteragent =
						$this->CheckJurCounteragent(
							$sSessionID,$sRecipientCompanyINN);
					$recipientPhone = $sRecipientCompanyPhone;
                    if ($reqJurCounteragent->status=="ok")
                    {

                        $iCounteragentToID = $reqJurCounteragent["counteragentId"];
                    }
                    else
                    {
                        $reqJuridicalCounteragent= $this->CreateJuridicalCounteragent($sSessionID,
                            $sRecipientCompanyName,
                            $sRecipientCompanyINN,
                            $sRecipientCompanyFormShortName,
                            "0x8f51001438c4d49511dbd774581edb7a", // Россия
                            $sCityTo,
                            $sRecipientCompanyAddress,
                            intval($sRecipientCompanyAddressCell));

                        if ($reqJuridicalCounteragent["status"]=='ok')
                        {
                            $iCounteragentToID = $reqJuridicalCounteragent["counteragentId"];
                        }
                        else
                        {
                            $errors[] = $reqJuridicalCounteragent["error"];
                        }
                    }
				}
				else
				{

					/** Check CounterAgent on exist in system */
                    $recipientDocumentType = "passport";
                    $recipientDocument = "Паспорт";
                    switch ($iRecipientDocumentTypeId) {
                        default:
                            $recipientDocumentType = "passport";
                            break;
                        case 2:
                            $recipientDocumentType = "drivingLicence";
                            $recipientDocument = "Водительские права";
                            break;
                        case 3:
                            $recipientDocumentType = "foreignPassport";
                            $recipientDocument = "Заграничный паспорт";
                            break;
                    }
                    $recipientPhone = $sRecipientPhone;
                    $checkResult = $this->CheckPrivateCounteragent
                    ($sSessionID, $sRecipientUserFIO,$recipientDocumentType,$sRecipientDocumentNumber);

                    if($checkResult["status"] == 'error')
					{
                        $errors[] = $checkResult["error"];
                        return $errors;
					}
                    if ($checkResult["status"] == 'ok') {
                        $isExist = true;
                        $iCounteragentToID = $checkResult["counteragentId"];
                    }
					if($checkResult["status"] == 'not_exist')
					{
						$isExist = false;
					}

					if (!$isExist)
					{

                        $counteragentTo =
                            $this->CreatePrivateCounteragent(
                                $sSessionID,
                                $sRecipientUserFIO,
                                $recipientDocumentType,
                                $sRecipientDocumentNumber);

						if(IS_DEBUG)
						{
							echo 'result CreatePrivateCounteragent for Recipient:<br>';
							var_dump($counteragentTo);
                            echo '<br>';
						}

                        if ($counteragentTo["status"] == 'new') {
                            $iCounteragentToID = $counteragentTo["counteragentId"];
                        }

                        if ($counteragentTo["status"] == 'error') {
                            if(IS_DEBUG)
                            {
                                echo "<br><br>Error Private Recipient <br>";
                                var_dump($errors);
                                echo "<br>";
                            }
                            $errors[] = $counteragentTo["error"];
                        }
					}
				}
                if(IS_DEBUG)
                {
                	echo "<br>CounteragentRecipientID: $iCounteragentToID<br>";
                	echo "Check Recipient CounterAgent<br>";
                	var_dump($errors);
                	echo "<br>";
				}
                if (count($errors) > 0) {
                    return $errors;
                }
                /**
                // CREATING COUNTERAGENTS ADDRESSES
                */
                $givenDerivalTerminalId = 0;
                $senderAddress = $sSenderAddress.', кв.'.$sSenderAddressCell;
                $recipientAddress = $sRecipientAddress.', кв.'.$sRecipientAddressCell;

                $iSenderAddressID = 0;
                $iRecipientAddressID = 0;

                if (!$isDerivalCourier)
				{

                    $aTerminals = $this->GetTerminals();
                    $givenDerivalTerminalId = intval($iSenderTerminalID);

                    foreach($aTerminals->city as $cities) {
                        foreach($cities->terminals as $terms) {
                            foreach($terms as $term) {

                                // derival
                                if ($givenDerivalTerminalId == $term->id) {

                                    /** Set terminal to counteragent */

                                    $oTermResult= $this->UpdateTerminalOfContragent(
                                        $sSessionID,
                                        $iCounteragentFromID,
                                        $givenDerivalTerminalId);

                                    if(IS_DEBUG){
                                    	echo '<br>Reply from Sender terminal update';
                                    	var_dump($iSenderAddressID);
										echo "<br>";
                                    }


                                    if ($oTermResult["status"]=='error')
                                    {
                                        $errors[] = $oTermResult["error"];
                                    }

                                    if ($oTermResult["status"] == 'ok') {
                                        $iSenderAddressID = $oTermResult["addressID"];
                                        if(IS_DEBUG) echo '<br>Good Set terminal for sender<br>AddressID:',$iSenderAddressID,"<br>";
                                    }
                                    //$sSenderContactFIO=$sSenderUserFIO;
                                }

                            }
                        }
                    }
				}
				else
				{
					/** Custom senderAddress */
                    if(IS_DEBUG) echo '<br>Start test delivery address for sender<br>'
                    ,$sSessionID,
                    $iCounteragentFromID,
                    $sCityFrom,
                    $senderAddress,'$givenDerivalTerminalId:',
                    $givenDerivalTerminalId,'<br>';

                    $isSenderAddress = $this->CheckAddress(
                        $sSessionID,
                        $iCounteragentFromID,
                        $sCityFrom,
                        $sSenderAddress,
                        $givenDerivalTerminalId,
                        $sSenderAddressCode,$sSenderAddressHouseNumber);

                    if ($isSenderAddress["status"] == 'ok') {
                        $iSenderAddressID = $isSenderAddress["addressID"];
                        if(IS_DEBUG) echo '<br>Good Exist address for sender<br>AddressID:',$iSenderAddressID,"<br>";
                    }
                    else
                    {

                        /** Create new Address for sender */
                        if(IS_DEBUG) echo '<br>Start create custom address for sender<br>',
                        $sSessionID,
                        $iCounteragentFromID,
                        $sCityFrom,
                        $sSenderAddress,"<br>";

                        $resAddress = $this->CreateAddress(
                            $sSessionID,
                            $iCounteragentFromID,
                            $sCityFrom,
                            $sSenderAddress,
                            $sSenderAddressCode,
                            $sSenderAddressHouseNumber,
                            $sSenderAddressBuildingNumber,
                            $sSenderAddressStructureNumber,
                            $sSenderAddressCell
                            );

                        if ($resAddress["status"] == 'ok') {
                            $iSenderAddressID = $resAddress["addressID"];
                            if(IS_DEBUG) echo '<br>Good Create new Address 1 for sender<br>AddressID:',$iSenderAddressID,"<br>";
                            if(IS_DEBUG) echo '<br>Start create custom address for sender second time<br>';
                            $isSenderAddress = $this->CheckAddress(
                                $sSessionID,
                                $iCounteragentFromID,
                                $sCityFrom,
                                $sSenderAddress,
                                $givenDerivalTerminalId,
                                $sSenderAddressCode,$sSenderAddressHouseNumber);

                            if ($isSenderAddress["status"] == 'ok') {
                                $iSenderAddressID = $isSenderAddress["addressID"];
                                if(IS_DEBUG) echo '<br>Good Create new Address 2 for sender<br>AddressID:',$iSenderAddressID,"<br>";
                            }
                        }
                        if ($resAddress["status"]=='error')
                        {
                            $errors[] = $resAddress["error"];
                        }
                    }

				}
                if (count($errors) > 0) {
                    if(IS_DEBUG) var_dump($errors);
                    return $errors;
                }
                $givenArrivalTerminalId = 0;
                if (!$isArrivalCourier)
                {
                    if(IS_DEBUG) echo '<br>Start test terminal for Recipient<br>';
                    $aTerminals = $this->GetTerminals();
                    $givenArrivalTerminalId = intval($iRecipientTerminalID);

                    foreach($aTerminals->city as $cities) {
                        foreach($cities->terminals as $terms) {
                            foreach($terms as $term) {
                                // arrival
                                if ($givenArrivalTerminalId == $term->id) {

                                	/** Set terminal to counteragent */

                                    $oTermResult= $this->UpdateTerminalOfContragent(
                                        $sSessionID,
                                        $iCounteragentToID,
                                        $givenArrivalTerminalId);

                                    if ($oTermResult["status"] == 'ok') {
                                        $iRecipientAddressID = $oTermResult["addressID"];
                                        if(IS_DEBUG) echo '<br>Good Set terminal for recipient<br>AddressID:',$iRecipientAddressID,"<br>";
                                    }
                                    elseif(IS_DEBUG) var_dump($givenArrivalTerminalId);

                                    if ($oTermResult["status"]=='error')
                                    {
                                        $errors[] = $oTermResult["error"];
                                    }

                                    //$sRecipientContactFIO = $sRecipientUserFIO;
                                }
                            }
                        }
                    }

                }
                else
                {
                    /** Custom senderAddress */
                    if(IS_DEBUG) echo '<br>Start test custom address for Recipient<br>';
                    $isRecipientAddress = $this ->CheckAddress(
                        $sSessionID,
                        $iCounteragentToID,
                        $sCityTo,
                        $sRecipientAddress,
                        $givenArrivalTerminalId,
                        $sRecipientAddressCode,$sRecipientAddressHouseNumber);

                    if ($isRecipientAddress["status"]=='ok')
                    {
                        $iRecipientAddressID = $isRecipientAddress["addressID"];
                        if(IS_DEBUG) echo '<br>Good Exist Address for recipient<br>AddressID:',$iRecipientAddressID,"<br>";
                    }
                    else
                    {
                        /** Create new Address for recipient */
                        if(IS_DEBUG) echo '<br>Start create custom address for Recipient<br>';
                        $resAddress = $this ->CreateAddress(
                            $sSessionID,
                            $iCounteragentToID,
                            $sCityTo,
                            $sRecipientAddress,
                            $sRecipientAddressCode,
                            $sRecipientAddressHouseNumber,
                            $sRecipientAddressBuildingNumber,
                            $sRecipientAddressStructureNumber,
                            $sRecipientAddressCell
						);

                        if ($resAddress["status"]=='ok')
                        {
                            $iRecipientAddressID = $resAddress["addressID"];
                            if(IS_DEBUG) echo '<br>Good Create new Address 1 for recipient<br>AddressID:',$iRecipientAddressID,"<br>";
                            if(IS_DEBUG) echo '<br>Start create custom address for Recipient second time<br>';
                            $isRecipientAddress = $this ->CheckAddress(
                                $sSessionID,
                                $iCounteragentToID,
                                $sCityTo,
                                $sRecipientAddress,
                                $givenArrivalTerminalId,
                                $sRecipientAddressCode,
                                $sRecipientAddressHouseNumber);
                            if ($isRecipientAddress["status"]=='ok')
                            {
                                $iRecipientAddressID = $isRecipientAddress["addressID"];
                                if(IS_DEBUG) echo '<br>Good Create new Address 2 for recipient<br>AddressID:',$iRecipientAddressID,"<br>";
                            }
                            else
							{
                                if(IS_DEBUG) {
                                    echo '<br>Bad check Address after creation for recipient<br>
										AddressID:', $iRecipientAddressID, "<br>";
                                    var_dump($isRecipientAddress);
                                }
                            }
                        }

                        if ($resAddress["status"]=='error')
                        {
                            $errors[] = $resAddress["error"];
                        }
                    }
                }
                if (count($errors) > 0) {
                    if(IS_DEBUG) var_dump($errors);
                    return $errors;
                }

                /** Editing phone numbers of sender */
                if(IS_DEBUG) echo '<br>Start update Phone for Sender<br>';
                $iSenderPhoneID = 0;
				$oPhoneResult = $this->UpdatePhoneOfContragent(
					$sSessionID,
					$iSenderAddressID,
					$senderPhone
				);

                if ($oPhoneResult["status"]=='ok')
                {
                    $iSenderPhoneID = $oPhoneResult["phoneID"];
                }

                if ($oPhoneResult["status"]=='error')
                {
                    $errors[] = $oPhoneResult["error"];
                }

                if(IS_DEBUG) {echo '<br>Good Editing phone numbers of sender<br>';
                    echo "<br>SenderContactFIO: $sSenderContactFIO<br>";
                }

                /** ContactPerson sender*/
                if(IS_DEBUG) echo '<br>Start update Contact info for Sender<br>';
                $iSenderPersonID = 0;
                $oContactPersonResult
						= $this->UpdateContactPerson
					($sSessionID,
                    $iSenderAddressID,
                    $sSenderContactFIO);

                if ($oContactPersonResult["status"]=='ok')
                {
                    $iSenderPersonID = $oContactPersonResult["personID"];
                }

                if ($oContactPersonResult["status"]=='error')
                {
                    $errors[] = $oContactPersonResult["error"];
                }

                if (count($errors) > 0) {
                    return $errors;
                }

                if(IS_DEBUG) echo '<br>Good SenderPerson sender';

                /** Editing phone numbers of sender */

                //if(IS_DEBUG) echo'<br>'.$sRecipientPhone;
                if(IS_DEBUG) echo '<br>Start update Phone for Recipient<br>';
                $oPhoneResult = $this->UpdatePhoneOfContragent(
                    $sSessionID,
                    $iRecipientAddressID,
                    $recipientPhone
                );
                $iRecipientPhoneID = 0;
                if ($oPhoneResult["status"]=='ok')
                {
                    $iRecipientPhoneID = $oPhoneResult["phoneID"];
                }

                if ($oPhoneResult["status"]=='error')
                {
                    $errors[] = $oPhoneResult["error"];
                }

                if (count($errors) > 0) {
                	return $errors;
            	}

                if(IS_DEBUG) echo'<br>Good Editing RecipientPhone numbers ';

                /** ContactPerson */
                if(IS_DEBUG) echo '<br>Start update Contact info for Recipient<br>';
                $oContactPersonResult
                    = $this->UpdateContactPerson
                ($sSessionID,
                    $iRecipientAddressID,
                    $sRecipientContactFIO);
                $iRecipientPersonID = 0;
                if ($oContactPersonResult["status"]=='ok')
                {
                    $iRecipientPersonID = $oContactPersonResult["personID"];
                }

                if ($oContactPersonResult["status"]=='error')
                {
                    $errors[] = $oContactPersonResult["error"];
                }
                if (count($errors) > 0) {
                    return $errors;
                }

				if(IS_DEBUG) echo'<br>Good ContactPerson of sender';

                /** CREATING PRE PARCEL */

                $senderWorkTimeStart = "00:00";
                if(isset($oOptions->senderWorkTimeStart)&& $oOptions->senderWorkTimeStart!=0)
                {
                    if($oOptions->senderWorkTimeStart<10)
                    {
                        $senderWorkTimeStart = '0'.$oOptions->senderWorkTimeStart.':00';
                    }
                    else
                    {
                        $senderWorkTimeStart = $oOptions->senderWorkTimeStart.':00';
                    }
                }

                $senderWorkTimeEnd ="23:30";
                if(isset($oOptions->senderWorkTimeEnd)&& $oOptions->senderWorkTimeEnd!=0)
                {
                	if($oOptions->senderWorkTimeEnd<10)
					{
                        $senderWorkTimeEnd = '0'.$oOptions->senderWorkTimeEnd.':00';
					}
					else
					{
                        $senderWorkTimeEnd = $oOptions->senderWorkTimeEnd.':00';
					}

                }

                $recipientWorkTimeStart = "00:00";
                if(isset($oOptions->recipientWorkTimeStart)&& $oOptions->recipientWorkTimeStart!=0)
                {
                    if($oOptions->recipientWorkTimeStart<10)
                    {
                        $recipientWorkTimeStart = '0'.$oOptions->recipientWorkTimeStart.':00';
                    }
                    else
                    {
                        $recipientWorkTimeStart = $oOptions->recipientWorkTimeStart.':00';
                    }
                }

                $recipientWorkTimeEnd = "23:30";
                if(isset($oOptions->recipientWorkTimeEnd)&& $oOptions->recipientWorkTimeEnd!=0)
                {
                    if($oOptions->recipientWorkTimeEnd<10)
                    {
                        $recipientWorkTimeEnd = '0'.$oOptions->recipientWorkTimeEnd.':00';
                    }
                    else
                    {
                        $recipientWorkTimeEnd = $oOptions->recipientWorkTimeEnd.':00';
                    }
                }


                if(IS_DEBUG) echo '<br>',$senderWorkTimeStart,' ',$senderWorkTimeEnd, ' ',
                $recipientWorkTimeStart,' ',$recipientWorkTimeEnd;

                $goodName='Груз';

                if (isset($cargoName) && $cargoName!='')
				{
                    $goodName = $cargoName;
				}

				$additionalServices=array();

				if($isDerivalCourier)
				{
					$additionalServices[]=
							[
									"service"=>33,
									"payer"=>1
							];
				}

				if($isArrivalCourier)
				{
					$additionalServices[]=
							[
									"service"=>1,
									"payer"=>1
							];
				}
                if(IS_DEBUG) {

                    echo "<br>",$cday, ' ',$cmonth,' ',$cyear,' ',$cdate,"<br>" ;
                }
                $sAddPreParcelURL =
					"https://api.dellin.ru/v1/customers/request.json";
                $aAddPreParcelOpts = array(
                       "appKey" => $this::AppKey,
                       "sessionID" => $sSessionID,
                       "sender" => array(
                       	"counteragentID" => $iCounteragentFromID,
						   "addressID" => $iSenderAddressID,
						   "contacts" => $iSenderPersonID,
						   "phones" => $iSenderPhoneID,
						   "worktimeStart" => $senderWorkTimeStart,
						   "worktimeEnd" => $senderWorkTimeEnd,
                           "loadUnload" => $aSenderOptions
					   		),
                        "receiver" => array(
                        	"counteragentID" => $iCounteragentToID,
                            "addressID" => $iRecipientAddressID,
							"contacts" => $iRecipientPersonID,
							"phones" => $iRecipientPhoneID,
							"worktimeStart" => $recipientWorkTimeStart,
							"worktimeEnd" => $recipientWorkTimeEnd,
							"loadUnload" => $aRecipientOptions
							),

                            "additionalServices" => $additionalServices,

                            "day" => $cday,
                            "month" => $cmonth,
							"year" => $cyear,
                    		"produce_date" => $cdate,
							"totalWeight" => $weight,
							"totalVolume" => $vol,
							"quantity" => 1,
							"maxLength" => $length,
							"maxHeight" => $height,
							"maxWidth" => $width,
							"maxweight" => $weight,
							"statedValue" => $insPrice,
							"paymentType" => 1,
							"LoadingType" => 1,
							"whoIsPayer" => [1],
							"primaryPayer" => 1,
							"name" => $goodName,
							"document" => "паспорт",
                            "moreinfo"=>"Тест BIA не оформлять",
							"deliveryType" => 1,
                        );

                $oAddPreParcelAnswer = $this->CallPOSTJSON($sAddPreParcelURL, $aAddPreParcelOpts);
				// var_dump($oAddPreParcelAnswer); die;
				//                print_r($aAddPreParcelOpts);

                if(IS_DEBUG) {echo '<br><pre>'; print_r($aAddPreParcelOpts);echo '<br></pre>';}

				$errTmp = Arr2Str('Посылка - ',$oAddPreParcelAnswer->answer->errors);
				if ($errTmp)
					$errors[] = $errTmp;
                //var_dump($oAddPreParcelAnswer); die();

                if ($oAddPreParcelAnswer->answer->state == 'error')
                    return $errors;

                $sPreParcelRequestID = $oAddPreParcelAnswer->answer->requestID;



                return $sPreParcelRequestID;

            }

	/** Check agent on exist. Get or add agent to addressbook */
    private function GetPhysicalCounteragentId(
        $sSessionID,
		$userFIO,
        $documentTypeId,
        $senderDocumentNumber
	)
    {
        $iCounteragentId = "";

        /** Check CounterAgent on exist in system */
        $isExist = false;

        $sAddPhysicalAddrURL = "https://api.dellin.ru/v1/customers/book/counteragents.json";
        $aAddPhysicalAddrOpts = array(
            "appKey" => $this::AppKey,
            "sessionID" => $sSessionID,
            "WithAnonym"=> "true"
        );

        $oAddPhysicalAddrAnswer = $this->CallPOSTJSON($sAddPhysicalAddrURL, $aAddPhysicalAddrOpts);

        $oAgents = json_decode($oAddPhysicalAddrAnswer);
        // var_dump($oAgents);
        foreach($oAgents as $oAgent)
        {
            $agent = $oAgent->counteragent;
            //echo '<br>';
            if($agent->type=="private")
            {
                //echo '<br>',$agent->name,'<br>';
            }
        }

        if ($isExist) {

            /** TODO: Need to finish */

            $iCounteragentId = "";
        }
        else
		{
            $senderDocumentType = "passport";

            switch ($documentTypeId) {
                case 1:
                    $senderDocumentType = "passport";
                    break;
                case 2:
                    $senderDocumentType = "drivingLicence";
                    break;
                case 3:
                    $senderDocumentType = "foreignPassport";
                    break;
            }

            // physical
            $sAddPhysicalAddrURL = "https://api.dellin.ru/v1/customers/book/counteragents/update.json";
            $aAddPhysicalAddrOpts = array(
                "appKey" => $this::AppKey,
                "sessionID" => $sSessionID,
                "form" => "0xAB91FEEA04F6D4AD48DF42161B6C2E7A",
                "document" => array(
                    "type" => $senderDocumentType,
                    "number" => $senderDocumentNumber
                    //substr($senderDocumentNumber,4)
                    // "serial" => substr($senderDocumentNumber,0,4),
                    //"date" => date('Y-m-d',$senderPassportDate)
                ),
                "name" => $userFIO
            );

            $oAddPhysicalAddrAnswer = $this->CallPOSTJSON($sAddPhysicalAddrURL, $aAddPhysicalAddrOpts);

            $errTmp = Arr2Str('Документ отправителя - ', $oAddPhysicalAddrAnswer->errors);
            if ($errTmp)
                $errors[] = $errTmp;

            //print_r($aAddPhysicalAddrOpts); die();

            if (!isset($oAddPhysicalAddrAnswer->success))
                return $errors;

            $iCounteragentId = $oAddPhysicalAddrAnswer->success->counteragentID;
        }

        return $iCounteragentId;
    }

}

///////////////////////////////////////////

$transports[32] = array(
	'name' => calculator_DELLIN::name,
	'site' => calculator_DELLIN::site,
	'logo' => calculator_DELLIN::logo,
	'calcfunc' => 'DELLIN_calc',
	'language' => calculator_DELLIN::language,
	'currency' => calculator_DELLIN::currency,
	'classname' => 'calculator_DELLIN',
	'canorder' => true
	);
	
////////////////////////////////////////////


//$from = "челябинск";
//$to = "астана";
//$weight = 11;
//$vol = 1;
//$insPrice = 0;
//
//DELLIN_calc($from,$to,$weight,$vol,$insPrice,"ru","ru","RUB","RUB", 'ru', 'ru');


////////////////////////////////////////////

function DELLIN_calc($from,$to,$weight,$vol,$insPrice,$client_lang,$transport_lang, $base_curr, $client_curr, $cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo,
		$options
	    )
    {

		$calc = new calculator_DELLIN;
		$calc->Calculate2($from,$to,$weight,$vol,$insPrice,$client_lang,$client_curr,$cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo,
			    $options
			);
		
		exit(0);

	}


?>
