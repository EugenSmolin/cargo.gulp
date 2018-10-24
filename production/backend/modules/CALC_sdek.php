<?php

set_include_path( dirname( __FILE__ ) . "/../" );

require_once 'abstract_calc.php';


class calculator_SDEK extends AbstractCalculator {
	const name = 'СДЭК';
	const site = 'https://www.cdek.ru';
	const logo = 'https://www.cdek.ru/website/edostavka/template/images/logo.png';
	const language = 'RU';
	const currency = 'RUB';

	// Temporary on this momemt
	// TODO: Need to get actual login, pass, AppKey
	const AppKey = '449A2F0C-9EA6-11E5-A3FB-00505683A6D3';
	const   sLoginTest = 'b7b19cfea042b5c3eb5568bb34cf4800';
	const    sPasswordTest = '848e287e26dfaacd128b2b25301678f9';
	private $sPasswordTest = '848e287e26dfaacd128b2b25301678f9';

	const    sLogin = 'b07eac755879074ebdd490d5ecb2a71a';
	private $sLogin = 'b07eac755879074ebdd490d5ecb2a71a';
	private $sPassword = 'efbaaa7fa594aed7da5a0d0c5f9aad9a';


	public $oDerivals = array( 'RU', 'KZ', 'BY', 'KG', 'AM', 'BL', 'CN' );
	public $oArrivals = array( 'RU', 'KZ', 'BY', 'KG', 'AM', 'BL', 'CN' );

    public $tariffs = [
        [
            "name"=> "Экспресс лайт дверь-дверь",
            "index"=>1,
            "minWeight"=>0,
            "maxWeight"=>30,
        ],
        [
			"name"=> "Супер-экспресс до 18",
			"index"=>3,
			"minWeight"=>0,
			"maxWeight"=>1000,
		],
		[
			"name"=> "Экспресс лайт склад-склад",
			"index"=>10,
			"minWeight"=>0,
			"maxWeight"=>30,
		],
        [
			"name"=> "Экспресс лайт склад-дверь",
			"index"=>11,
			"minWeight"=>0,
			"maxWeight"=>30,
		],
        [
			"name"=> "Экспресс лайт дверь-склад",
			"index"=>12,
			"minWeight"=>0,
			"maxWeight"=>30,
		],
        [
			"name"=> "Экспресс тяжеловесы склад-склад",
			"index"=>15,
			"minWeight"=>30,
			"maxWeight"=>1000,
        ],
        [
			"name"=> "Экспресс тяжеловесы склад-дверь",
			"index"=>16,
			"minWeight"=>30,
			"maxWeight"=>1000,
		],
        [
			"name"=> "Экспресс тяжеловесы дверь-склад",
			"index"=>17,
			"minWeight"=>30,
			"maxWeight"=>1000,
		],
        [
			"name"=> "Экспресс тяжеловесы дверь-дверь",
			"index"=>18,
			"minWeight"=>30,
			"maxWeight"=>1000,
		],
        [
			"name"=> "Супер-экспресс до 9",
			"index"=>57,
			"minWeight"=>0,
			"maxWeight"=>5,
		],
        [
			"name"=> "Супер-экспресс до 10",
			"index"=>58,
			"minWeight"=>0,
			"maxWeight"=>5,
		],
        [
			"name"=> "Супер-экспресс до 12",
			"index"=>59,
			"minWeight"=>0,
			"maxWeight"=>5,
		],
        [
			"name"=> "Супер-экспресс до 14",
			"index"=>60,
			"minWeight"=>0,
			"maxWeight"=>5,
		],
        [
			"name"=> "Супер-экспресс до 16",
			"index"=>61,
			"minWeight"=>0,
			"maxWeight"=>1000,
		],
        [
			"name"=> "Магистральный экспресс склад-склад",
			"index"=>62,
			"minWeight"=>0,
			"maxWeight"=>1000,
		],
        [
			"name"=> "Магистральный супер-экспресс склад-склад",
			"index"=>63,
			"minWeight"=>0,
			"maxWeight"=>1000,
		]
		];

	public function Calculate(
		$from, $to, $weight, $vol, $insPrice, $clientLang,
		$clientCurr, $cargoCountryFrom, $cargoCountryTo,
		$cargoStateFrom, $cargoStateTo,
		$isActiveLineParams, $width, $length, $height,
		$options = []
	) {

		global $aFCodes, $aCCodes;
		mb_internal_encoding( "UTF-8" );
		mb_regex_encoding( "UTF-8" );
		date_default_timezone_set( 'UTC' );

		$fromUp = mb_strtoupper( $from );
		$toUp   = mb_strtoupper( $to );

		if ( $from == $to ) {
			return DropCalculation();
		}

		if ( $this::currency != $clientCurr ) {
			$cvt_curr = GetConvertedPrices( $insPrice, $clientCurr );
			$insPrice = $cvt_curr[ $this::currency ];
		}

		$cargoCountryFromU = mb_strtoupper( $cargoCountryFrom );
		$cargoCountryToU   = mb_strtoupper( $cargoCountryTo );
		// if source country not in Derivals -- exit
		if ( ! ( $this->oDerivals[0] == '*' ) ) {
			if ( ! ( in_array( $cargoCountryFromU, $this->oDerivals ) ) ) {
				return DropCalculation();
			}
		}

		// if target country not in Arrivals -- exit
		if ( ! ( $this->oArrivals[0] == '*' ) ) {
			if ( ! ( in_array( $cargoCountryToU, $this->oArrivals ) ) ) {
				return DropCalculation();
			}
		}

		$fDim = round( pow( $vol, 1 / 3 ), 2 );
		if ( $isActiveLineParams == 0 ) {
			$width  = $fDim;
			$length = $fDim;
			$height = $fDim;
		}

		// fetch cities list // TODO: Исправить
		$iDerivalIdx = $this->GetCityIndex( $fromUp );
		$iArrivalIdx = $this->GetCityIndex( $toUp );


        // Проверка тарифов
        // Если задан тариф на форме тогда применяем его
        // Если не задан ищем по минимальному

        $isOneResult = true;
        $oneResultTarif = 10;
        $oneResultTarifName = $this->tariffs[10]["name"];

        if(!isset($options->tariff))
        {
            $isOneResult = true;
            if($weight>=30)
            {
                $oneResultTarif = 15;
                $oneResultTarifName = $this->tariffs[15]["name"];
            }
        }
        else
        {
            if($options->tariff==0)
            {
                $isOneResult = false;
            }
            else
            {
                $oneResultTarif = $options->tariff;
                $oneResultTarifName = $this->tariffs[$oneResultTarif]["name"];
            }
        }

        //var_dump($isOneResult, $oneResultTarif, $oneResultTarifName); die;

        $outResultMethods = array();

        $results = array();

        if($isOneResult){

            $oCalcResult = $this->GetCalculation(
                $oneResultTarif,
                intval($iDerivalIdx),
                intval($iArrivalIdx),
                $weight, $length, $width,
                $height, $vol);

            $oCalcResult = $oCalcResult["result"];

            if (!isset($oCalcResult["error"]) && isset($oCalcResult)) {
                $results[] = array(
                    "name" => $oneResultTarifName,
                    "price" => $oCalcResult["price"],
                    "deliveryPeriodMin" => $oCalcResult["deliveryPeriodMin"],
                    "deliveryPeriodMax" => $oCalcResult["deliveryPeriodMax"],
                );
            }
            else
            {
                $opt=array('tariffId'=>$oneResultTarif,
                    "senderCityId"   =>intval($iDerivalIdx),
                    "receiverCityId" =>intval($iArrivalIdx),
                    "goods"          => [
                        [
                            "weight" => strval( $weight ),
                            "length" => strval( $length * 10 ),
                            "width"  => strval( $width * 10 ),
                            "height" => strval( $height * 10 )
                        ],
                        "volume" => strval( $vol )
                    ]);

                DropWithCompanyErrorResponse(150,"Для выбранных параметров данная услуга не оказывается"
                    ,'','http://api.cdek.ru/calculator/calculate_price_by_json.php',json_encode($opt),"ru"
                );
            }

        }
        else
        {
/*
             $res = $this->GetAllTariffCalculation(
                intval($iDerivalIdx),
                intval($iArrivalIdx),
                $weight, $length, $width,
                $height, $vol);

             var_dump($res); die;
*/

            foreach ($this->tariffs as $tarif) {

                //var_dump($weight,$tarif["minWeight"],$tarif["minWeight"] <$weight); die;

                if ($tarif["minWeight"] < $weight &&
                    $tarif["maxWeight"] > $weight
                ) {

                    $oCalcResult = $this->GetCalculation(
                        $tarif["index"],
                        intval($iDerivalIdx),
                        intval($iArrivalIdx),
                        $weight, $length, $width,
                        $height, $vol);

                    $oCalcResult = $oCalcResult["result"];

                    if (!isset($oCalcResult["error"]) && isset($oCalcResult)) {
                        $results[] = array(
                            "name" => $tarif["name"],
                            "price" => $oCalcResult["price"],
                            "deliveryPeriodMin" => $oCalcResult["deliveryPeriodMin"],
                            "deliveryPeriodMax" => $oCalcResult["deliveryPeriodMax"],
                        );
                    }
                }
            }
        }

        foreach ($results as $result) {
            $days = "";
            if ($result["deliveryPeriodMin"] == $result["deliveryPeriodMax"]) {
                $days = $result["deliveryPeriodMin"];
            } else {
                $days = $result["deliveryPeriodMin"] . " - " . $result["deliveryPeriodMax"];
            }

            $_names = __GetAllTranslations($result["name"], $this::language);
            $_calcResultPrice = floatval($result["price"]);
            $_calcResultPrices = GetConvertedPrices($_calcResultPrice, $this::currency);
            $_calcResultTimes = __GetAllTranslations($days . ' дней',
                $this::language);

            $outResultMethods[] = array(
                'name' => $_names[$clientLang],
                'names' => $_names,
                'calcResultPrice' => $_calcResultPrices[$clientCurr],
                'calcResultPrices' => $_calcResultPrices,
                'calcResultTime' => $_calcResultTimes[$clientLang],
                'calcResultTimes' => $_calcResultTimes
            );
        }
         //   var_dump($outResultMethods);die;

		$outResultArray['methods']           = $outResultMethods;
		$outResultArray['cities']['derival'] = __GetAllTranslations( $from, $this::language );
		$outResultArray['cities']['arrival'] = __GetAllTranslations( $to, $this::language );
		$outResultArray['cityFrom']          = $outResultArray['cities']['derival'][ $clientLang ];
		$outResultArray['cityTo']            = $outResultArray['cities']['arrival'][ $clientLang ];

		return $outResultArray;
	}


	private function GetCalculation2(
		$fromIndx, $toIndx,
		$weight, $length,
		$width, $height, $volume, $modeId = ''
	) {
		$jsonUrl = 'http://api.cdek.ru/calculator/calculate_price_by_json.php';

		$jsonUrl = "https://www.cdek.ru/ajax.php?JsHttpRequest=0-xml";

		$data = "Action=GetTarifList&orderType=1&FromCity=137&ToCity=44&Package[0][weight]=0.5&Package[0][length]=&Package[0][width]=&Package[0][height]=&Package[0][description]=&idInterface=3";

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $jsonUrl );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/octet-stream'
			)
		);

		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );

		$result = curl_exec( $ch );
		//var_dump($result); die;

		curl_close( $ch );

		return json_decode( $result );
	}

	public function GetCalculation( $tariffId,$fromIndx, $toIndx, $weight, $length, $width, $height, $volume = '1000', $modeId = '2' ) {
		$data =
			json_encode(
				array(
					"version"        => "1.0",
					"dateExecute"    => ( date( 'Y-m-d' ) ),
					"senderCityId"   => strval( $fromIndx ),
					"receiverCityId" => strval( $toIndx ),
					"tariffId"       => "$tariffId",
					"modeId"         => $modeId,
					"goods"          => [
						[
							"weight" => strval( $weight ),
							"length" => strval( $length * 10 ),
							"width"  => strval( $width * 10 ),
							"height" => strval( $height * 10 )
						],
						"volume" => strval( $volume )
					]
				)
			);


		$jsonUrl = 'http://api.cdek.ru/calculator/calculate_price_by_json.php';

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $jsonUrl );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json'
			)
		);
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );

		$result = curl_exec( $ch );
		curl_close( $ch );

//        var_dump(json_decode($result, true));
		return json_decode( $result, true );
	}

	public function GetAllTariffCalculation($fromIndx, $toIndx, $weight, $length, $width, $height, $volume = '1000', $modeId = '2' )
	{
        $results = array();
        foreach ($this->tariffs as $tarif) {

            //var_dump($weight,$tarif["minWeight"],$tarif["minWeight"] <$weight); die;

            if ($tarif["minWeight"] < $weight &&
                $tarif["maxWeight"] > $weight
            ) {

                $oCalcResult = $this->GetCalculation(
                    $tarif["index"],
                    intval($fromIndx),
                    intval($toIndx),
                    $weight, $length, $width,
                    $height, $volume);

                $oCalcResult = $oCalcResult["result"];

                if (!isset($oCalcResult["error"]) && isset($oCalcResult)) {
                    $results[] = array(
                    	"index" => $tarif["index"],
                        "name" => $tarif["name"],
                        "price" => $oCalcResult["price"],
                        "deliveryPeriodMin" => $oCalcResult["deliveryPeriodMin"],
                        "deliveryPeriodMax" => $oCalcResult["deliveryPeriodMax"],
                    );
                }
            }
        }

        return $results;
        /*
        $data ="Action=GetTarifList&orderType=1&FromCity=44&ToCity=259&Package[0][weight]=0.5&Package[0][length]=&Package[0][width]=&Package[0][height]=&Package[0][description]=&idInterface=3";

        $jsonUrl = 'https://www.cdek.ru/ajax.php?JsHttpRequest=0-xml';
        var_dump($data);
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $jsonUrl );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_COOKIE, 'sms=0shr7247u9o74l3j0ihha477e4; ipp_uid2=STR0wE5EwfsxoFf0/ZU4Z69wg4aljGWaULCdJ0g==; ipp_uid1=1526380852450; _ym_uid=1526380857232295343; _ga=GA1.2.109510570.1526380858; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; lt_uid=af4c5d3d-f281-4801-9208-0b676d01769b; _ym_d=1532011534; jv_visits_count_aGl1Ahg0tr=4; ILangCode=ru; rerf=AAAAAFug3AESbF9zAwjvAg==; b=2018-09-18; _gid=GA1.2.708373874.1537367297; _ym_isad=1; _ym_visorc_47420224=w; ipp_key=v1537443445409/780/H1Jlg8Aq0JIc4Y/ahj/rNw==; _ym_visorc_72427=w; _ym_visorc_47173557=w; _gat_UA-4806124-1=1; tmr_detect=1%7C1537443455067');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/octet-stream'
            )
        );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );

        $result = curl_exec( $ch );
        curl_close( $ch );

        var_dump($result); die();
        return json_decode( $result, true );
        */
	}

	public function GetCityIndex( $city_name, $postcode = 1 ) {

        $index =0;

		$mysqli = new mysqlii( DB_HOST, LANG_DB_LOGIN, LANG_DB_PASSWORD,  KLADR_DB_NAME );

		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		$query = $mysqli->query("SELECT DISTINCT * FROM cdek_kladr kr WHERE kr.cityName = '$city_name'");


        if ($mysqli->affected_rows > 0)
        {

            $result = $query->fetch_assoc();
            return $result["cityID"];
            //var_dump($city_name,$result["cityID"], $result); die;
        }
      /*  else
    	 {
			$query = $mysqli->query( "SELECT DISTINCT postCode FROM cdek_postcode pc WHERE pc.cityID = '$result[cityID]'" );

			if ( $postcode == 1 ) {
				$rows = $query->fetch_assoc();
			} else {
				while ( $row = $query->fetch_assoc() ) {
					$rows['postCode'][] = $row['postCode'];
				}
			}
			$index = $result + $rows;
		} */
       else
       {

			$citiesReqBody = "http://api.cdek.ru/city/getListByTerm/json.php?q=" . urlencode( $city_name ) . "&name_startsWith=" . urlencode( $city_name );

			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $citiesReqBody );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			$output = json_decode( curl_exec( $ch ) );

			curl_close( $ch );
			foreach ( $output->geonames as $city ) {
				if ($index == 0 ) {
					$index = $city->id;
				}
				$cityID        = $city->id;
				$cityName      = $city->cityName;
				$regionId      = $city->regionId;
				$countryId     = $city->countryId;
				$countryName   = $city->countryName;
				$countryIso    = $city->countryIso;
				$fullName      = $city->name;
				$postCodeArray = $city->postCodeArray;

				$mysqli->query( "INSERT INTO cdek_kladr (id, cityID, cityName, regionId, countryId, countryName, countryIso, `name` )
 											VALUES (NULL, '$cityID', '$cityName', '$regionId', '$countryId', '$countryName', '$countryIso', '$fullName' )" );
/*				foreach ( $postCodeArray as $postCode ) {
					$mysqli->query( "INSERT INTO cdek_postcode (id, postCode, cityID) VALUES (NULL, $postCode, $cityID )" );
				}
*/
			}
		}
//		var_dump($index);

		return $index;
	}

    public function GetPdf($oHandler,$orderId)
    {
        //$loginResult = $this::Login($isPickCargo);
/*
        if (is_array($loginResult))
            return $loginResult;

        $sSessionID = $loginResult;
*/
        $mysqlHandle = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

        $query = "SELECT company_internal_number FROM ".DB_ORDERS_TABLE."
                  WHERE id = ".$orderId;

        $oSearchResult = $oHandler->query($query);
        // var_dump($query); die();
        if ($oHandler->affected_rows > 0)
        {
            $oRow = $oSearchResult->fetch_assoc();
            $requestId = $oRow["company_internal_number"];
        }
        else
        {
            return false;
        }

        $date = date( 'Y-m-d' );
        $sPassword = self::sPasswordTest;
        $sLogin    = self::sLoginTest;
        $secure = md5( $date . '&' . $sPassword );

        $request = <<<XML
		<OrdersPrint Date="$date" 
					Account="$sLogin" 
					Secure="$secure" 
					OrderCount="1" 
					CopyCount="1">
			<Order DispatchNumber="$requestId" />
		</OrdersPrint>
XML;

        $file_name = 'cargo.guru_ttn_#'.$requestId .'.pdf';
        //var_dump($request); //die;
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, "https://integration.cdek.ru/orders_print.php" );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );

        curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/x-www-form-urlencoded' ) );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, 'xml_request=' . $request );

        $data = curl_exec( $curl );
        curl_close( $curl );

       // var_dump($response);
       // $xml = simplexml_load_string($response);
        header("Content-type:application/pdf");

        header("Content-Disposition:inline;filename='".$file_name."'");

        print_r($data);
    }

	public function GetPvzCode ($cityID, $weight=50) {
		$pvzCodes = array();
/*
		$mysqli = new mysqlii( DB_HOST,LANG_DB_LOGIN, LANG_DB_PASSWORD, KLADR_DB_NAME );

		if ( mysqli_connect_errno() ) {
			printf( "Connect failed: %s\n", mysqli_connect_error() );
			exit();
		}
		$query  = $mysqli->query( "SELECT DISTINCT * FROM cdek_pvzcode pvz WHERE CityCode = '$cityID'" );

        if ($mysqli->affected_rows > 0)
		{
            $result = $query->fetch_assoc();
        }
		else
	 	{
*/
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, "http://integration.cdek.ru/pvzlist/v1/xml?weightmax=$weight&cityid=$cityID&allowedcod=1" );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			$output = ( curl_exec( $ch ) );
			curl_close( $ch );

			$xml = simplexml_load_string( $output );

			foreach ($xml->Pvz as $name => $val) {

                //var_dump($name , $val->attributes()); die;

                $atts = $val->attributes();

                $pvzCodes[]=
                array(
                    'visible' => strval($atts["City"]).' ('.strval($atts["Address"]).')',
                    'number' => strval($atts["Code"])
                );
			}
//		}
		return $pvzCodes;
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
		$aOptions = []
	) {
		global $aFCodes, $aCCodes;

		mb_internal_encoding( "UTF-8" );
		mb_regex_encoding( "UTF-8" );
		date_default_timezone_set( 'UTC' );

		$fromUp = mb_strtoupper( $from );
		$toUp   = mb_strtoupper( $to );

		if ( $from == $to ) {
			return DropCalculation();
		}

		if ( $this::currency != $clientCurr ) {
			$cvt_curr = GetConvertedPrices( $insPrice, $clientCurr );
			$insPrice = $cvt_curr[ $this::currency ];
		}

		$cargoCountryFromU = mb_strtoupper( $cargoCountryFrom );
		$cargoCountryToU   = mb_strtoupper( $cargoCountryTo );

		// if source country not in Derivals -- exit
		if ( ! ( $this->oDerivals[0] == '*' ) ) {
			if ( ! ( in_array( $cargoCountryFromU, $this->oDerivals ) ) ) {
				return DropCalculation();
			}
		}

		// if target country not in Arrivals -- exit
		if ( ! ( $this->oArrivals[0] == '*' ) ) {
			if ( ! ( in_array( $cargoCountryToU, $this->oArrivals ) ) ) {
				return DropCalculation();
			}
		}

		$fDim = round( pow( $vol, 1 / 3 ), 2 );

		/*
		if($isActiveLineParams==0)
		{
			$width = $fDim;
			$length =  $fDim;
			$height = $fDim;
		}
		*/

		// fetch cities list
		$iDerivalIdx = $this->GetCityIndex( $fromUp );
		$iArrivalIdx = $this->GetCityIndex( $toUp );

		$modeId = '';
		/*
		  (дверь-дверь=1, дверь-склад=2, склад-дверь=3, склад-склад=4)
		 */


		// склад-склад=4
		if ( ! $isDerivalByCourier && ! $isArrivalByCourier ) {
			$modeId = '4';
		}

		// дверь-склад=2
		if ( $isDerivalByCourier && ! $isArrivalByCourier ) {
			$modeId = '2';
		}

		// склад-дверь=3
		if ( ! $isDerivalByCourier && $isArrivalByCourier ) {
			$modeId = '3';
		}

		// дверь-дверь=1
		if ( $isDerivalByCourier && $isArrivalByCourier ) {
			$modeId = '1';
		}

		die();
//        $oCalcResult = $this->GetCalculation($iDerivalIdx, $iArrivalIdx, $weight, $length, $width, $height, $vol,$modeId);

		if ( $oCalcResult == null ) {
			return DropCalculation();
		}

		$fPrice = 0;

		$outResultMethods = array();

		if ( property_exists( $oCalcResult, 'avia' ) ) {
			$_names            = __GetAllTranslations( 'Авиатранспорт', $this::language );
			$_calcResultPrice  = floatval( $oCalcResult->avia[2] + $fPrice );
			$_calcResultPrices = GetConvertedPrices( $_calcResultPrice, $this::currency );
			$_calcResultTimes  = __GetAllTranslations( $oCalcResult->periods_days . ' дней',
				$this::language );

			$outResultMethods[] = array(
				'name'             => $_names[ $clientLang ],
				'names'            => $_names,
				'calcResultPrice'  => $_calcResultPrices[ $clientCurr ],
				'calcResultPrices' => $_calcResultPrices,
				'calcResultTime'   => $_calcResultTimes[ $clientLang ],
				'calcResultTimes'  => $_calcResultTimes
			);
		}

		$outResultArray['methods'] = $outResultMethods;

		return $outResultArray;
	}

	public function GetOptions() {

		$baseOptions = parent::GetOptions();
		///////////////////////////////////////

		$oPOSTData  = json_decode( file_get_contents( "php://input" ) );
		$sCargoFrom = mb_strtoupper( trim( $oPOSTData->data->cargoFrom ) );
		$sCargoTo   = mb_strtoupper( trim( $oPOSTData->data->cargoTo ) );

        $fWeight   = floatval(mb_strtoupper( trim( $oPOSTData->modifies->weight )));
        $fLength   = floatval(mb_strtoupper( trim( $oPOSTData->modifies->length )));
        $fWidth   = floatval(mb_strtoupper( trim( $oPOSTData->modifies->width )));
        $fHeight  = floatval(mb_strtoupper( trim( $oPOSTData->modifies->height )));
        $fVolume  = floatval(mb_strtoupper( trim( $oPOSTData->modifies->volume )));

        $fWeight=1;
		$fLength=1;
		$fWidth=1;
		$fHeight=1;
		$fVolume=0.1;
		//////////////////////////////////////
		// fetch terminals
		//////////////////////////////////////

        $sCargoFromID  = $this->GetCityIndex($sCargoFrom);
        $sCargoToID  = $this->GetCityIndex($sCargoTo);

        $aDerivalTerms = array();
        $aArrivalTerms = array();

        $aDerivalTerms = $this->GetPvzCode($sCargoFromID);
        $aArrivalTerms = $this->GetPvzCode($sCargoToID);

        //var_dump($aDerivalTerms); die;
        //var_dump($aArrivalTerms); die;

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
                    "makesVisible" => ["derivalTerminalBlock"],
                    "makesInvisible" => ["derivalAddressBlock"],
                    "selected" => true
                ],
                [
                    "number" => 2,
                    "visible" => "Забор груза от адреса",
                    "makesVisible" => ["derivalAddressBlock"],
                    "makesInvisible" => ["derivalTerminalBlock"]
                ]
            ],

        );

        $aFromOptions['derivalTerminalBlock'] = [
            "id" => "derivalTerminalBlock",
            "name" => "derivalTerminalBlock",
            "hidden" => false,
            "visibleOrder"=>5,
            "aoptions" => [
                [
                    "displayName" => "Адрес терминала",
                    "fieldName" => "derivalTerminalId",
                    "type" => "enum",
                    "is_option" => true,
                    "presentation" => [
                        "size" => 100
                    ],
                    "required" => FALSE,
                    "variants" => $aDerivalTerms
                ],
            ]
        ];

        $aFromOptions['derivalAddressBlock'] = [
            "id" => "derivalAddressBlock",
            "name" => "derivalAddressBlock",
            "hidden" => true,
            "visibleOrder"=>7,
            "aoptions" => [
                [
                    "displayName" => "Улица",
                    "fieldName" => "cargoSenderAddress",
                    "recalcTotalPrice" => true,
                    "required"=>true,
                    "inputSize" => 4,
                    "visibleOrder" => 4,
                    "type" => "string",
                    "presentation" => [
                        "size" => 50
                    ]
                ],
                [
                    "displayName" => "Код улицы",
                    "fieldName" => "cargoSenderAddressCode",
                    "type" => "string",
                    "hidden" => true,
                    "visibleOrder" => 12,
                    "inputSize" => 8,
                    "required" => false,
                    "presentation" => [
                        "size" => 50
                    ]
                ],
                [
                    "displayName" => "Дом",
                    "fieldName" => "cargoSenderAddressHouseNumber",
                    "type" => "string",
                    "required"=>true,
                    "inputSize" => 5,
                    "visibleOrder" => 5,
                    "required" => true,
                    "presentation" => [
                        "size" => 25
                    ]
                ],
                [
                    "displayName" => "Квартира (офис)",
                    "fieldName" => "cargoSenderAddressCell",
                    "type" => "int32",
                    "inputSize" => 15,
                    "visibleOrder" => 8,
                    "required" => false,
                    "presentation" => [
                        "size" => 25
                    ]
                ],
                [
                    "fieldName" => "senderWorkTimeStart",
                    "type" => "enum",
                    "inputSize" => 5,
                    "visibleOrder" => 1,
                    "is_option"=> true,
                    "recalcTotalPrice" => true,
                    "presentation" => [
                        "size" => 25
                    ],
                    "displayname" => "Время забора груза с ",
                    "variants" => $this->aStartTimeVariants
                ],
                [
                    "fieldName" => "senderWorkTimeEnd",
                    "type" => "enum",
                    "inputSize" => 5,
                    "visibleOrder" => 2,
                    "is_option"=> true,
                    "presentation" => [
                        "size" => 25
                    ],
                    "displayname" => " до ",
                    "variants" => $this->aEndTimeVariants
                ],
                [
                    "displayName" => "Фиксированное время забора",
                    "fieldName" => "derivalFixedTimeVisit",
                    "type" => "bool",
                    "visibleOrder" => 3,
                    "presentation" => [
                        "size" => 40
                    ],
                    "is_option"=> true,
                    "recalcTotalPrice" => true,
                    "variants" => [
                        [
                            "number" => 1,
                            "visible" => "Фиксированное время забора",
                        ]
                    ]
                ]
            ]
        ];
/*
        $aFromOptions['derivalAddressTimeBlock'] = [
            "id" => "derivalAddressTimeBlock",
            "name" => "derivalAddressTimeBlock",
            "hidden" => true,
            "visibleOrder"=>7,
            "aoptions" => [
                [
                    "fieldName" => "senderWorkTimeStart",
                    "type" => "enum",
                    "inputSize" => 5,
                    "visibleOrder" => 1,
                    "is_option"=> true,
                    "recalcTotalPrice" => true,
                    "presentation" => [
                        "size" => 25
                    ],
                    "displayname" => "Время забора груза с ",
                    "variants" => $this->aStartTimeVariants
                ],
                [
                    "fieldName" => "senderWorkTimeEnd",
                    "type" => "enum",
                    "inputSize" => 5,
                    "visibleOrder" => 2,
                    "is_option"=> true,
                    "presentation" => [
                        "size" => 25
                    ],
                    "displayname" => " до ",
                    "variants" => $this->aEndTimeVariants
                ],
                [
                    "displayName" => "Фиксированное время забора",
                    "fieldName" => "derivalFixedTimeVisit",
                    "type" => "bool",
                    "visibleOrder" => 3,
                    "presentation" => [
                        "size" => 40
                    ],
                    "is_option"=> true,
                    "recalcTotalPrice" => true,
                    "variants" => [
                        [
                            "number" => 1,
                            "visible" => "Фиксированное время забора",
                        ]
                    ]
                ]
            ]
        ];
*/
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

        $baseOptions['groups']['from']['aoptions'] = array_merge(
            $baseOptions['groups']['from']['aoptions'],$aFromOptions);

        $aWhereOptions = array();

        $aWhereOptions['deliveryPick'] = array(
            "displayName" => "Способ доставки",
            "fieldName" => "arrivalCourier",
            "type" => "enum",
            "required" => TRUE,
            "variants" => [
                [
                    "number" => 1,
                    "visible" => "Получить груз в отделении",
                    "makesVisible" => ["deliveryTerminalBlock"],
                    "makesInvisible" => ["deliveryAddressBlock"],
                    "selected" => true
                ],
                [
                    "number" => 2,
                    "visible" => "Доставка до адреса",
                    "makesVisible" => ["deliveryAddressBlock"],
                    "makesInvisible" => ["deliveryTerminalBlock"]
                ]
            ]
        );

        $aWhereOptions['deliveryTerminalBlock'] = [
            "id" => "deliveryTerminalBlock",
            "name" => "deliveryTerminalBlock",
            "hidden" => false,
            "visibleOrder"=>5,
            "aoptions" => [
                [
                    "displayName" => "Доступные терминалы",
                    "fieldName" => "arrivalTerminalId",
                    "type" => "enum",
                    "is_option"=> true,
                    "presentation" => [
                        "size" => 100
                    ],
                    "required" => FALSE,
                    "variants" => $aArrivalTerms
                ],


            ]
        ];

      /*  $aWhereOptions['arrivalAddressTimeBlock'] = [
            "id" => "arrivalAddressTimeBlock",
            "name" => "arrivalAddressTimeBlock",
            "hidden" => true,
            "visibleOrder"=>6,
            "aoptions" => [
                [
                    "fieldName" => "recepientWorkTimeStart",
                    "type" => "enum",
                    "inputSize" => 5,
                    "visibleOrder" => 1,
                    "is_option"=> true,
                    "recalcTotalPrice" => true,
                    "presentation" => [
                        "size" => 25
                    ],
                    "displayname" => "Время доставки груза с ",
                    "variants" => $this->aStartTimeVariants
                ],
                [
                    "fieldName" => "recepientWorkTimeEnd",
                    "type" => "enum",
                    "inputSize" => 5,
                    "visibleOrder" => 2,
                    "is_option"=> true,
                    "presentation" => [
                        "size" => 25
                    ],
                    "displayname" => " по ",
                    "variants" => $this->aEndTimeVariants
                ],
                [
                    "displayName" => "Фиксированное время доставки",
                    "fieldName" => "arrivalFixedTimeVisit",
                    "type" => "bool",
                    "visibleOrder" => 4,
                    "presentation" => [
                        "size" => 40
                    ],
                    "is_option"=> true,
                    "recalcTotalPrice" => true,
                    "variants" => [
                        [
                            "number" => 1,
                            "visible" => "Фиксированное время доставки",
                        ]
                    ]
                ]
            ]
        ];
*/
        $aWhereOptions['deliveryAddressBlock'] = [
            "id" => "deliveryAddressBlock",
            "name" => "deliveryAddressBlock",
            "hidden" => true,
            "visibleOrder"=>6,
            "aoptions" => [
                [
                    "fieldName" => "recepientWorkTimeStart",
                    "type" => "enum",
                    "inputSize" => 5,
                    "visibleOrder" => 1,
                    "is_option"=> true,
                    "recalcTotalPrice" => true,
                    "presentation" => [
                        "size" => 25
                    ],
                    "displayname" => "Время доставки груза с ",
                    "variants" => $this->aStartTimeVariants
                ],
                [
                    "fieldName" => "recepientWorkTimeEnd",
                    "type" => "enum",
                    "inputSize" => 5,
                    "visibleOrder" => 2,
                    "is_option"=> true,
                    "presentation" => [
                        "size" => 25
                    ],
                    "displayname" => " по ",
                    "variants" => $this->aEndTimeVariants
                ],
                [
                    "displayName" => "Фиксированное время доставки",
                    "fieldName" => "arrivalFixedTimeVisit",
                    "type" => "bool",
                    "visibleOrder" => 4,
                    "presentation" => [
                        "size" => 40
                    ],
                    "is_option"=> true,
                    "recalcTotalPrice" => true,
                    "variants" => [
                        [
                            "number" => 1,
                            "visible" => "Фиксированное время доставки",
                        ]
                    ]
                ],
                [
                    "displayName" => "Адрес улицы",
                    "fieldName" => "cargoRecepientAddress",
                    "recalcTotalPrice" => true,
                    "type" => "string",
                    "visibleOrder" => 1,
                    "required" => true,
                    "presentation" => [
                        "size" => 50
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
                        "size" => 50
                    ]
                ],
                [
                    "displayName" => "Дом",
                    "fieldName" => "cargoRecepientAddressHouseNumber",
                    "type" => "string",
                    "inputSize" => 5,
                    "visibleOrder" => 3,
                    "required" => true,
                    "presentation" => [
                        "size" => 25
                    ]
                ],
                [
                    "displayName" => "Квартира (офис)",
                    "fieldName" => "cargoRecepientAddressCell",
                    "type" => "int32",
                    "required" => false,
                    "visibleOrder" => 9,
                    "inputSize" => 15,
                    "presentation" => [
                        "size" => 25
                    ]
                ],
            ]
        ];

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

        $baseOptions['groups']['where']['aoptions'] = array_merge(
            $baseOptions['groups']['where']['aoptions'],$aWhereOptions);


        $tariffs = $this->GetAllTariffCalculation(
            $sCargoFromID,
            $sCargoToID,
            $fWeight,$fLength,$fWidth,$fHeight,$fVolume
		);

        $tariffList=array();

        foreach ($tariffs as $tariff)
		{

            $days = "";
            if ($tariff["deliveryPeriodMin"] == $tariff["deliveryPeriodMax"]) {
                $days = $tariff["deliveryPeriodMin"];
            } else {
                $days = $tariff["deliveryPeriodMin"] . "-" . $tariff["deliveryPeriodMax"];
            }

            switch ($tariff["index"])
			{
				case 1:
                case 3:
                case 18:
                case 57:
                case 58:
                case 59:
                case 60:
                case 61:
                    	$makesVisible = "derivalAddressBlock, deliveryAddressBlock";
                    	$makesInvisible = "derivalTerminalBlock, deliveryTerminalBlock";
					break;
                case 10:
                case 15:
                case 62:
                case 63:
                    	$makesVisible = "derivalTerminalBlock, deliveryTerminalBlock";
                    	$makesInvisible = "derivalAddressBlock, deliveryAddressBlock";
                    break;
                case 11:
                case 16:
						$makesVisible = "derivalTerminalBlock, deliveryAddressBlock";
						$makesInvisible = "derivalAddressBlock, deliveryTerminalBlock";
                    break;
                case 12:
                case 17:
						$makesVisible = "derivalAddressBlock, deliveryTerminalBlock";
						$makesInvisible= "derivalTerminalBlock, deliveryAddressBlock";
                    break;


			}
           /*
            $makesVisible = "derivalAddressBlock";
            $makesInvisible = "derivalTerminalBlock";
*/
            $tariffList[]=
				array(
                    "number" => $tariff["index"],
                    "visible" => $tariff["name"].', '.$days.' дня',
                    "makesVisible" => [$makesVisible],
                    "makesInvisible" => [$makesInvisible]
				);
		}

        $aPaymentOptions['tariffType'] = array(
            'visibleOrder' => 0,
            "displayName" => "Тарифы",
            "fieldName" => "tariffType",
            "type" => "enum",
            "required" => false,
            "variants" => $tariffList
		);

        $aTariffsGroups['tariffs'] = array(
            'name' => 'Тарифы',
            'visibleOrder' => 1,
            'aoptions' => $aPaymentOptions
        );

        $baseOptions['groups'] = $aTariffsGroups;

        unset($baseOptions['groups']["when"]);

        //TODO: Need to finish
		return $baseOptions;
	}

	////////////////////////////////////////////////////////////////////

	public function GetRequisites() {
		//parent::GetRequisites();

		$aRetVal = array(
			"bankSpecialId" => '122122'
		);
	}

	/*
	 * Регистрация заказа: Заказ на доставку
	 */
	public function MakeOrder(
		$sCityFrom = '',
		$sCityTo = '',
		$sCargoFromZip = '',
		$sCargoToZip = '',
		$sCargoFromRegion = '',
		$sCargoToRegion = '',
		$weight = '',
		$vol = '',
		$insPrice = '',
		$length = '',
		$width = '',
		$height = '',
		$cargoName = '',
		$cargoDate = '',
		$oOptions,

		$isRecipientJur = '',
		$sRecipientUserFIO = '',
		$iRecipientDocumentTypeId = '',
		$sRecipientDocumentNumber = '',
		$sRecipientPhone = '',
		$sRecipientEmail = '',
		$iRecipientTerminalID = '',
		$sRecipientCompanyName = '',
		$sRecipientCompanyFormShortName = '',
		$sRecipientCompanyINN = '',
		$sRecipientCompanyAddress = '',
		$sRecipientCompanyAddressCell = '',
		$sRecipientCompanyPhone = '',
		$sRecipientCompanyEmail = '',
		$sRecipientContactFIO = '',

		$sRecipientAddress = '',
		$sRecipientAddressCell = '',

		$isSenderJur = '',
		$sSenderUserFIO = '',
		$iSenderDocumentTypeId = '',
		$sSenderDocumentNumber = '',
		$sSenderPhone = '',
		$sSenderEmail = '',
		$iSenderTerminalID = '',
		$sSenderCompanyName = '',
		$sSenderCompanyFormShortName = '',
		$sSenderCompanyINN = '',
		$sSenderCompanyAddress = '',
		$sSenderCompanyAddressCell = '',
		$sSenderCompanyPhone = '',
		$sSenderCompanyEmail = '',
		$sSenderContactFIO = '',

		$sSenderAddress = '',
		$sSenderAddressCell = '',

		$isDerivalCourier = '',
		$isArrivalCourier = '',
		$dCargoDesireDate = '',
		$dCargoDeliveryDate = ''
	) {


		//TODO: Need to finish

		$curl = curl_init();
		curl_setopt( $curl, CURLOPT_URL, "https://integration.cdek.ru/new_orders.php" );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_POST, true );

		//Создаём уникальный хэш
		date_default_timezone_set( 'UTC' );

		$date = date( 'Y-m-d' );
//		$dateReq = date('Y-m-d H:i:s');

		$sPassword = self::sPasswordTest;
		$sLogin    = self::sLoginTest;

		$secure = md5( $date . '&' . $sPassword );

		$oOptions->modifies->internalNumber;

		$cityFromID = $this->GetCityIndex($sCityFrom);
		$cityToID = $this->GetCityIndex($sCityTo);

		$sCityFromCode = $cityFromID;
		$sCityToCode = $cityToID;

		if($sCityFromCode != $sCityToCode) {
			$foreignDelivery = 'false';
		} else {
			$foreignDelivery = 'true';
		}

		$tariffTypeCode = '';
		/*
		  (дверь-дверь=139, дверь-склад=138, склад-дверь=137, склад-склад=136)
		 */

		// склад-склад=136
		if ( ! $isDerivalCourier && ! $isArrivalCourier ) {
			$tariffTypeCode = 136;
		}

		// дверь-склад=138
		if ( $isDerivalCourier && ! $isArrivalCourier ) {
			$tariffTypeCode = 138;
		}

		// склад-дверь=137
		if ( ! $isDerivalCourier && $isArrivalCourier ) {
			$tariffTypeCode = 137;
		}

		// дверь-дверь=139
		if ( $isDerivalCourier && $isArrivalCourier ) {
			$tariffTypeCode = 138;
		}

        $derivalAddress = '';

        if($isDerivalCourier)
        {
            $sAddress = explode(',',$sSenderAddress );
            $street = trim($sAddress[count($sAddress)-2]);
            $house = trim($sAddress[count($sAddress)-1]);
            $derivalAddress ='<address flat="'.$sSenderAddressCell.'" house="'.$house.'" street="'.$street.'"/>';
        }
        else
        {
            $derivalAddress ='<address PvzCode="'.$iSenderTerminalID.'" />';
        }

        $arrivalAddress = '';

        if($isDerivalCourier)
        {
            $sAddress = explode(',',$sRecipientAddress );
            $street = trim($sAddress[count($sAddress)-2]);
            $house = trim($sAddress[count($sAddress)-1]);
            $arrivalAddress ='<address flat="'.$sRecipientAddressCell.'" house="'.$house.'" street="'.$street.'"/>';
        }
        else
        {
            $arrivalAddress ='<address PvzCode="'.$iRecipientTerminalID.'" />';
        }

        $sender = '';
        if($isSenderJur)
        {
            $sender = '<sender company="'.$sSenderCompanyName.' '.$sSenderCompanyFormShortName.'" name="'.$sSenderContactFIO.'">';
            $sender .=$derivalAddress;
            $sender .='<emails>'.$sSenderCompanyEmail.'</emails>
                        <phone>'.$sSenderCompanyPhone.'</phone>';
            $sender .= '</sender>';
        }
        else
        {
            $sender = '<sender  name="'.$sSenderUserFIO.'">';
            $sender .=$derivalAddress;
            $sender .='<emails>'.$sSenderEmail.'</emails>
                        <phone>'.$sSenderPhone.'</phone>';
            $sender .= '</sender>';
        }
$recipient ='';
            if($isRecipientJur)
            {
                $recipient ='Phone="'.$sSenderCompanyPhone.'"
                    RecipientCompany="'.$sRecipientCompanyName.'"
                    RecipientEmail="'.$sRecipientCompanyEmail.'"
                    RecipientName="'.$sRecipientContactFIO.'"';
            }
            else
            {
                $recipient ='Phone="'.$sSenderPhone.'"
                    RecipientCompany=""
                    RecipientEmail="'.$sRecipientEmail.'"
                    RecipientName="'.$sRecipientUserFIO.'"';
            }

		/* PVZ
		 * Если нет адреса и тип доставки дверь-склад или склад-склад, то запрашиваем pvzCode
		 *
		*/
        /*
		if ($tariffTypeCode == 136 OR $tariffTypeCode == 138) {

			$pvz = $this->GetPvzCode($sCityToCode, $weight);
			$pvzCode = strval ($pvz[0]['Pvz']['Code']);
		}
        */

		/* $ClientSideType. Тип Клиент: отправитель, получатель, третье лицо.
		 * Принимаемые значения: “sender”-отправитель, “receiver”-получатель, “other”-третье лицо
		 */
		$clientSideType = 'sender';

		$orderNumber = rand(100,1000).rand(0,99);

		// Вес в граммах
		$weight = $weight * 1000;

		// Адрес получателя
		$companyAddress = explode(',',$sRecipientCompanyAddress );

		$request = <<<XML
<DeliveryRequest Account="$sLogin"
                 Currency="RUB" Date="$date" ForeignDelivery="$foreignDelivery"
                 Number="test_request" OrderCount="1" Secure="$secure">
    <Order ClientSide="$clientSideType" Number="$orderNumber"
            $recipient
     		ReccityCode="$sCityToCode"
     		RecipientCurrency="RUB"
     		SendCityCode="$sCityFromCode"
     		TariffTypeCode="$tariffTypeCode">
     		$arrivalAddress
            $sender
        <Package Number="1" BarCode="101" Weight="$weight">        
			<Item WareKey="25000050368" Cost="$insPrice" Payment="$insPrice" Weight="$weight" Amount="1"/>       
		</Package>
    </Order>
</DeliveryRequest>
XML;

        //var_dump($request); //die;

		curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/x-www-form-urlencoded' ) );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, 'xml_request=' . $request );

		$response = curl_exec( $curl );
		curl_close( $curl );

		$xml = simplexml_load_string($response);

		//var_dump($xml); die;

		$dispatchNumber = '';
		foreach ($xml->Order[0]->attributes() as $name => $val) {
			if($name == 'DispatchNumber') {
				$dispatchNumber = $val;
			}
		}

		return $dispatchNumber;
	}
}

$transports[150] = array(
	'name'      => calculator_SDEK::name,
	'site'      => calculator_SDEK::site,
	'logo'      => calculator_SDEK::logo,
	'calcfunc'  => 'SDEK_calc',
	'language'  => calculator_SDEK::language,
	'currency'  => calculator_SDEK::currency,
	'classname' => 'calculator_SDEK',
	'canorder'  => true
);


?>
