<?php

//require_once '../service/config.php';
//require_once '../services.php';
//require_once '../abstract_calc.php';
require_once 'abstract_calc.php';

///////////////////////////////////////////////////

class calculator_DPD extends AbstractCalculator
{
    const name = 'DPD';
    const site = 'http://www.dpd.ru';
    const logo = 'http://www.dpd.ru/template2/newimages/logo.png';
    const language = 'RU';
    const currency = 'RUB';

    private $DPD_NUMBER = '1021004582';
    private $DPD_KEY = '479B53D03312EC208168A1786336B05BAC86E905';

    private $server = array(
        'http://ws.dpd.ru/services/',
        'http://wstest.dpd.ru/services/'
    );

    public $oDerivals = array('RU');
    public $oArrivals = array('RU');

    public function Calculate($from, $to, $weight, $vol, $insPrice, $clientLang,
                              $clientCurr, $cargoCountryFrom, $cargoCountryTo,
                              $cargoStateFrom, $cargoStateTo,
                              $isActiveLineParams, $width, $length, $height,
                              $options = []) {

        mb_internal_encoding("UTF-8");
        mb_regex_encoding("UTF-8");

        if ($this::currency != $clientCurr) {
            $cvt_curr = GetConvertedPrices($insPrice, $clientCurr);
            $insPrice = $cvt_curr[$this::currency];
        }

        $cargoCountryFromU = mb_strtoupper($cargoCountryFrom);
        $cargoCountryToU = mb_strtoupper($cargoCountryTo);

        // if source country not in Derivals -- exit
//        if (!(in_array($cargoCountryFrom, $this->oDerivals)))
//            return DropCalculation();
//
//        // if target country not in Arrivals -- exit
//        if (!(in_array($cargoCountryTo, $this->oArrivals)))
//            return DropCalculation();

	    $sCargoFromArray  = $this->GetCityIndex($from);
	    $sCargoToArray  = $this->GetCityIndex($to);

	    $sCargoFromID = $sCargoFromArray['cityId'];
	    $sCargoToID = $sCargoToArray['cityId'];

        ///////////////////////////////////////////////////
        ///////////////////////////////////////////////////


        $calculatorURL = 'calculator2?wsdl';

        $client = new SoapClient($this->server[1] . $calculatorURL);

        $reqData['auth'] = array(
            'clientNumber' => $this->DPD_NUMBER,
            'clientKey' => $this->DPD_KEY
        );

        $reqData['pickup'] = [
            	'cityName' => $from,
            'cityId' => $sCargoFromID,
            'index' => false,
            'regionCode' => $sCargoFromArray['regionCode'],
            'countryCode' => 'RU'
        ];

        $reqData['delivery'] = [
            'cityName' => $to,
            'cityId' => $sCargoToID,
            'index' => false,
            'regionCode' => $sCargoToArray['regionCode'],
            'countryCode' => 'RU'
        ];

        $reqData['selfPickup'] = true;
        $reqData['selfDelivery'] = true;
        $reqData['weight'] = $weight;
        $reqData['volume'] = $vol;
        $reqData['declaredValue'] = round($insPrice, 2);

        $prepRequest['request'] = $reqData;

//	    $result = $client->getServiceCost2($prepRequest);


        if (!IS_PRODUCTION) var_dump($prepRequest);
        try {
            $result = $client->getServiceCost2($prepRequest);
        } catch (Exception $e) {
            $result = [];
        }
        if (!IS_PRODUCTION) var_dump($result);
        $outResultMethods = array();

        foreach ($result->return as $method) {
            $fPrice = $method->cost;

            if ($fPrice > 0) {

                $_names = __GetAllTranslations($method->serviceName, $this::language);
                $_calcResultPrice = floatval($fPrice);
                $_calcResultPrices = GetConvertedPrices($_calcResultPrice, $this::currency);
                $_calcResultTimes = __GetAllTranslations($method->days . ' дней', $this::language);

                $outResultMethods[] = array(
                    'cargoService' => $method->serviceCode,
                    'name' => $_names[$clientLang],
                    'names' => $_names,
                    'calcResultPrice' => $_calcResultPrices[$clientCurr],
                    'calcResultPrices' => $_calcResultPrices,
                    'calcResultTime' => $_calcResultTimes[$clientLang],
                    'calcResultTimes' => $_calcResultTimes
                );
            }
        }

        if (count($outResultMethods) < 1)
            return DropCalculation();

        $outResultArray['cities']['derival'] = __GetAllTranslations($from, $this::language);
        $outResultArray['cities']['arrival'] = __GetAllTranslations($to, $this::language);
        $outResultArray['cityFrom'] = $outResultArray['cities']['derival'][$clientLang];
        $outResultArray['cityTo'] = $outResultArray['cities']['arrival'][$clientLang];
        $outResultArray['methods'] = $outResultMethods;

        var_dump($outResultArray);
        die;
        return $outResultArray;
    }

    private function GetCity() {

	    $mysqli = new mysqlii( DB_HOST, LANG_DB_LOGIN, LANG_DB_PASSWORD,  KLADR_DB_NAME );

	    if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
	    }

	    $client = new SoapClient ($this->server[0] . "geography2?wsdl");

	    $arData['auth'] = array(
		    'clientNumber' => $this->DPD_NUMBER,
		    'clientKey' => $this->DPD_KEY);

	    $arRequest['request'] = $arData; //помещаем наш массив авторизации в массив запроса request.
	    $ret = $client->getCitiesCashPay($arRequest);
	    //обращаемся к функции getCitiesCashPay  и получаем список городов.

	    $rc = (array)$ret;

	    $rc = $rc['return'];
	    $size = sizeof($rc);

	    for($i=0; $i<$size; $i++) {
		    $cityId = $rc[$i]->cityId;
		    $countryCode= $rc[$i]->countryCode;
		    $countryName = $rc[$i]->countryName;
		    $regionCode = $rc[$i]->regionCode;
		    $regionName = $rc[$i]->regionName;
		    $cityCode = $rc[$i]->cityCode;
		    $cityName = $rc[$i]->cityName;
		    $abbreviation = $rc[$i]->abbreviation;

		    $mysqli->query( "INSERT INTO dpd_kladr (id, cityId, countryCode, countryName, regionCode, regionName, cityCode, cityName, abbreviation )
 											VALUES (NULL, '$cityId', '$countryCode', '$countryName', '$regionCode', 
 											'$regionName', '$cityCode', '$cityName', '$abbreviation' )" );
	    }
	    return true;
    }

    public function GetCityIndex($cityName) {

	    $mysqli = new mysqlii( DB_HOST, LANG_DB_LOGIN, LANG_DB_PASSWORD,  KLADR_DB_NAME );

	    if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
	    }

	    $query = $mysqli->query("SELECT DISTINCT * FROM dpd_kladr kr WHERE kr.cityName = '$cityName'");

	    if ($mysqli->affected_rows > 0)
	    {
		    $result = $query->fetch_assoc();
		    return $result;
	    }
    }

	////////////////////////////////////////////////////////////////////
/*
	public function  Calc()
{
    $city = 'Калуга';
    $findcity = findCity($city);
    $sposob ="Способ";
//Массив tovars $a[] = array(0 =>’тут id’, 1=>’тут количество этого товара’); и так можно дублировать до скольких вам нужно. Или же использовать отправление с помощью AJAX
        $tovars = $_POST[tovars]; //принимаем масив товаров
        $spec = $_POST[tovars];
        for ($g=0; $g <= count($tovars)-1; $g++){ //перебираем масив(можно через foreach)
            $all[] = $tovars[$g][0];//id товара
            $cout[] = $tovars[$g][1];//количество товаров
        }


        sort($cout);// сортируем количество
        $tovar = array_unique($all);//удаляем тот товар который повторяеться
        $tovar = implode(",", $tovar); // записываем товары через ‘,’

        $mysql_query = mysql_query("SELECT * FROM items WHERE id IN ($tovar)"); //таблица items имеет структуру id(тот который мы искали),name(название товара),mesto(количество мест),width,height,weight,length,price
        $mysql_array = mysql_fetch_assoc($mysql_query);


        $client = new SoapClient ("$server[0]calculator2?wsdl"); //создаем подключение soap
        $arData = array(
            'delivery' => array(			// город доставки
                'cityId' => $findcity, //id города
                'cityName' => $city, //сам город
            ),
        );		$arData['auth'] = array(
        'clientNumber' => $uchet,
        'clientKey' => $keys); //данные авторизации
        if ($sposob == 'home'){ //если отправляем до дома то ставим значение false
            $arData['selfDelivery'] = false;// Доставка ДО дома
        }
        else { // если же мы хотим отправить до терминала то true
            $arData['selfDelivery'] = true;// Доставка ДО терминала
        }
        $arData['pickup'] = array(
            'cityId' => 195733465,
            'cityName' => 'Калуга',
        ); // где забирают товар

        // что делать с терминалом
        $arData['selfPickup'] = true;// Доставка ОТ терминала // если вы сами довозите до терминала то true если вы отдаёте от двери то false
        $i=0;
        do { //перебираем массив запроса в БД
            if ($mysql_array['mesto'] > 1 ){ //если мест больше чем 1
                $ves = explode(",", $mysql_array["weight"]); //в бд всё храниться в одном столбике но через ‘,’ для этого используем команду explode(где указываем что у нас стоит ‘,');
                $length = explode(",", $mysql_array["length"]);
                $width = explode(",", $mysql_array["width"]);
                $height = explode(",", $mysql_array["height"]);
            }
            else {
                $ves[] = $mysql_array["weight"];
                $length[] = $mysql_array["length"];
                $width[] = $mysql_array["width"];
                $height[] = $mysql_array["height"]; //если у нас место 1 то мы просто заносим в массив
            }
            for ($s = 0; $s <= $mysql_array['mesto']-1; $s++){ //создаем цикл помещаем в масив parcel информацию о товарах
                $arData['parcel'][] = array('weight' => $ves[$s], 'length' => $length[$s], 'width' => $width[$s], 'height' => $height[$s] , 'quantity' => $cout[$i]);
            }
            $i++;
            $cena[] = $mysql_array['price']; // указываем цену за товар из БД

        }while($mysql_array = mysql_fetch_assoc($mysql_query)); //повторяем тело цикла

        for ($c=0; $c <= count($cena); $c++){
            $a = $a + ($cena[$c] * $cout[$c]);
        }
        //сумируем цену и умножаем на количество
		$arData['declaredValue'] = $a; //Объявленная ценность (итоговая)
		$arRequest['request'] = $arData; // помещаем в массив запроса
		$ret = $client->getServiceCostByParcels2($arRequest); //делаем сам запрос

		$echo = stdToArray($ret); // функция из объекта в массив (в 1 пункте она есть).
		$all = array();
		for ($j = 0; $j <= count($echo['return'])-1; $j++){
			$all[] = array('serviceName' => $echo['return'][$j]['serviceName'],'cost' => $echo['return'][$j]['cost'],'tarif' => $echo['return'][$j]['serviceCode']);
		} //помещаем в массив all – указывает название тарифа, код тарифа, стоимость.

		echo json_encode($all); выводим для JS в json формате.


}
*/
	////////////////////////////////////////////////////////////////////
        
	public function GetOptions() {
    	    
	    mb_internal_encoding("UTF-8");
	    mb_regex_encoding("UTF-8");

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

		$sCargoFrom = 'Саратов';
		$sCargoTo = 'Челябинск';
		//////////////////////////////////////
		// fetch terminals
		//////////////////////////////////////

		$sCargoFromArray  = $this->GetCityIndex($sCargoFrom);
		$sCargoToArray  = $this->GetCityIndex($sCargoTo);

		$sCargoFromID = $sCargoFromArray['cityId'];
		$sCargoToID = $sCargoToArray['cityId'];


		$aDerivalTerms = array();
		$aArrivalTerms = array();

//		var_dump($sCargoFromID, $sCargoToID);

//		$aDerivalTerms = $this->GetPvzCode($sCargoFromID);
//		$aArrivalTerms = $this->GetPvzCode($sCargoToID);


//	    if ((!isset($oPOSTData->data->cargoFrom)) || (!isset($oPOSTData->data->cargoTo))) {
//		DropWithBadRequest('No cities specified');
//	    }

    	$terminalsURL = 'geography2?wsdl';
    	    
	    $client = new SoapClient($this->server[1] . $terminalsURL);

    	    // here we fetch terminals from dpd

		$reqData = array (
			'auth' => array(
			'clientNumber' => $this->DPD_NUMBER,
			'clientKey' => $this->DPD_KEY
		),
			'countryCode' => 'RU',
			'cityCode' => $sCargoToID,
			'cityName' => $sCargoTo,
			'regionCode' => false
		);

	    $prepRequest = array (
	    	'request' => $reqData,
	    );

		$terminalsTo = $client->getParcelShops($prepRequest);

		$reqDataFrom = array (
			'auth' => array(
				'clientNumber' => $this->DPD_NUMBER,
				'clientKey' => $this->DPD_KEY
			),
			'countryCode' => 'RU',
			'cityCode' => $sCargoFromID,
			'cityName' => $sCargoFrom,
			'regionCode' => false
		);

		$prepRequestFrom = array (
			'request' => $reqDataFrom,
		);

		$terminalsFrom = $client->getParcelShops($prepRequestFrom);

		////////////////////////////////////////
    	    // where section
    	    
    	    // we change cargoRecepientAddress to hidden cause DPD requires component address. we will glue address in the creation
    	    $baseOptions['groups']['where']['aoptions']['cargoRecepientAddress']['hidden'] = TRUE;
    	    
    	    // add recepient address block for DPD
    	    $baseOptions['groups']['where']['aoptions']['cargoRecepientStreet'] = [
        					    "displayName" => "Улица",
        					    "fieldName" => "cargoRecepientStreet",
						    "type" => "string",
						    "required" => TRUE
    						];

    	    $baseOptions['groups']['where']['aoptions']['cargoRecepientStreetType'] = [
        					    "displayName" => "Тип улицы",
        					    "fieldName" => "cargoRecepientStreetType",
						    "type" => "string",
						    "required" => TRUE
    						];

    	    $baseOptions['groups']['where']['aoptions']['cargoRecepientStreetHouse'] = [
        					    "displayName" => "Номер дома",
        					    "fieldName" => "cargoRecepientStreetHouse",
						    "type" => "string",
						    "required" => TRUE
    						];
    	
    	    // create terminals
    	    $termToOptions = [];


    	    foreach($terminalsTo->return->parcelShop as $terminal) {

    		$termToOptions[] = [
    			'visible' => $terminal->address->streetAbbr . ' ' . $terminal->address->street . ', ' . $terminal->address->houseNo . ' (' . $terminal->brand . ')',
    			'number' => $terminal->code
    		    ];
    	    }

    	    $baseOptions['groups']['where']['aoptions']['arrivalTerminalId'] = [
                	    "displayName" => 'Адрес терминала',
                	    "fieldName" => "arrivalTerminalId",
                	    "type" => "enum",
                	    "required" => TRUE,
                	    "variants" => $termToOptions
    			];
    			
    	    //////////////////////////////
    	    // группа "откуда"
    	    
    	    // add sender address block for DPD
    	    $baseOptions['groups']['from']['aoptions']['cargoSenderStreet'] = [
        					    "displayName" => "Улица",
        					    "fieldName" => "cargoSenderStreet",
						    "type" => "string",
						    "required" => TRUE
    						];

    	    $baseOptions['groups']['from']['aoptions']['cargoSenderStreetType'] = [
        					    "displayName" => "Тип улицы",
        					    "fieldName" => "cargoSenderStreetType",
						    "type" => "string",
						    "required" => TRUE
    						];

    	    $baseOptions['groups']['from']['aoptions']['cargoSenderStreetHouse'] = [
        					    "displayName" => "Номер дома",
        					    "fieldName" => "cargoSenderStreetHouse",
						    "type" => "string",
						    "required" => TRUE
    						];

    	    // create terminals
    	    $termFromOptions = [];
    	    foreach($terminalsFrom as $terminal) {
    		$termFromOptions[] = [
    			'visible' => $terminal->address->streetAbbr . ' ' . $terminal->address->street . ', ' . $terminal->address->houseNo . ' (' . $terminal->brand . ')',
    			'number' => $terminal->code
    		    ];
    	    }

    	    $baseOptions['groups']['from']['aoptions']['derivalTerminalId'] = [
                	    "displayName" => 'Адрес терминала',
                	    "fieldName" => "derivalTerminalId",
                	    "type" => "enum",
                	    "required" => TRUE,
                	    "variants" => $termFromOptions
    			];
    			
    	    //////////////////////////////
    	    // группа "когда"
    	    $baseOptions['groups']['when']['aoptions']['cargoPickupPeriod'] = [
                	    "displayName" => 'Забрать с .. по ..',
                	    "fieldName" => "cargoPickupPeriod",
                	    "type" => "string",
                	    "required" => TRUE
    			];

    	    $baseOptions['groups']['when']['aoptions']['cargoDeliveryPeriod'] = [
                	    "displayName" => 'Доставить с .. по ..',
                	    "fieldName" => "cargoDeliveryPeriod",
                	    "type" => "string",
                	    "required" => FALSE
    			];
    	    
    			
    	    //////////////////////////////
    	    // группа опций груза
    	    
    	    $baseOptions['groups']['addOptions'] = [
    			    'name' => 'Дополнительные опции',
    			    'aoptions' => [
    				    'cargoService' => [
    					    'displayName' => 'Метод доставки (тариф)',
    					    'fieldName' => 'cargoService',
    					    'type' => 'string',
    					    'required' => TRUE
    					],
    				    'cargoTransferType' => [
    					    'displayName' => 'Тип доставки',
    					    'fieldName' => 'cargoTransferType',
    					    'type' => 'enum',
    					    'required' => TRUE,
    					    'variants' => [
    						    [
    							'id' => 'ТТ',
    							'value' => 'Терминал - терминал'
    						    ],
    						    [
    							'id' => 'ДД',
    							'value' => 'Дверь - дверь'
    						    ],
    						    [
    							'id' => 'ТД',
    							'value' => 'Терминал - Дверь'
    						    ],
    						    [
    							'id' => 'ДТ',
    							'value' => 'Дверь - терминал'
    						    ],
    						]
    					],
    				    'cargoDeliveryOption' => [
    					    'displayName' => 'Ценный груз',
    					    'fieldName' => 'cargoRegistered',
    					    'type' => 'boolean',
    					    'required' => TRUE
    					],
    				    'cargoRegistered' => [
    					    'displayName' => 'Ценный груз',
    					    'fieldName' => 'cargoRegistered',
    					    'type' => 'boolean',
    					    'required' => TRUE
    					],
    				    'cargoValue' => [
    					    'displayName' => 'Объявленная ценность груза',
    					    'fieldName' => 'cargoValue',
    					    'type' => 'float',
    					    'required' => FALSE
    					],
    				    'cargoType' => [
    					    'displayName' => 'Описание груза',
    					    'fieldName' => 'cargoType',
    					    'type' => 'string',
    					    'required' => TRUE
    					],
    				    'cargoPack' => [
    					    'displayName' => 'Обрешетка груза',
    					    'fieldName' => 'cargoPack',
    					    'type' => 'boolean',
    					    'required' => FALSE
    					],
    				    'cargoUnloadWait' => [
    					    'displayName' => 'Ожидание на адресе',
    					    'fieldName' => 'cargoUnloadWait',
    					    'type' => 'boolean',
    					    'required' => FALSE
    					],
    				    'cargoTerm' => [
    					    'displayName' => 'Термостатирование',
    					    'fieldName' => 'cargoTerm',
    					    'type' => 'boolean',
    					    'required' => FALSE
    					],
    				    'cargoLoadUnload' => [
    					    'displayName' => 'Погрузочно-разгрузочные работы',
    					    'fieldName' => 'cargoLoadUnload',
    					    'type' => 'boolean',
    					    'required' => FALSE
    					],
    				    'cargoWeekEnd' => [
    					    'displayName' => 'Доставка в выходные дни',
    					    'fieldName' => 'cargoWeekEnd',
    					    'type' => 'boolean',
    					    'required' => FALSE
    					],
    				    'cargoDocsReturn' => [
    					    'displayName' => 'Возврат документов',
    					    'fieldName' => 'cargoDocsReturn',
    					    'type' => 'boolean',
    					    'required' => FALSE
    					],
    				    'cargoSMSRecepient' => [
    					    'displayName' => 'SMS уведомление получателя',
    					    'fieldName' => 'cargoSMSRecepient',
    					    'type' => 'boolean',
    					    'required' => FALSE
    					],
    				    'cargoEmailRecepient' => [
    					    'displayName' => 'Email уведомление получателя',
    					    'fieldName' => 'cargoEmailRecepient',
    					    'type' => 'boolean',
    					    'required' => FALSE
    					],
    				    'cargoEmailDelivery' => [
    					    'displayName' => 'Email уведомление о доставке груза',
    					    'fieldName' => 'cargoEmailDelivery',
    					    'type' => 'boolean',
    					    'required' => FALSE
    					],
    				    'cargoEmailOrder' => [
    					    'displayName' => 'Email уведомление о приеме заказа',
    					    'fieldName' => 'cargoEmailOrder',
    					    'type' => 'boolean',
    					    'required' => FALSE
    					],
    				    'cargoDeliveryApprove' => [
    					    'displayName' => 'Подтверждение доставки',
    					    'fieldName' => 'cargoDeliveryApprove',
    					    'type' => 'boolean',
    					    'required' => FALSE
    					]
    				]
    			];


//    	    print_r($termFromOptions);
//    	    print_r($terminalsFrom);

	    return $baseOptions;
        }

            
        /////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////
            
      /*  public function MakeOrder($from, $to, $weight, $vol, $insPrice,
                    $method, $length, $width, $height, $cargoName, $cargoDate,
                    $recepientUser, $recepientPassport, $recepientPassportGivenDate, $recepientAddress, $recepientPhone, $recepientEmail,
                    $senderUser, $senderPassport, $senderPassportDate, $senderAddress, $senderPhone, $senderEmail,
                    $isSenderJur, $sSenderJurForm, $sSenderINN,
                    $oOptions) {
      */
            public function MakeOrder(
            				$sCityFrom,
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

        $errors = array();


	    $orderData['auth'] = [
			'clientNumber' => $this->DPD_NUMBER,
			'clientKey' => $this->DPD_KEY
			];

	    if (intval($cargoDate) <= 0) {
		DropWithBadRequest('Cargo date not specified');
	    }
	    
	    if (empty($senderUser)) {
		DropWithBadRequest('Sender username not specified');
	    }
	    
	    if (empty($oOptions->derivalTerminalId)) {
		DropWithBadRequest('Derival terminal not specified');
	    }
	    
	    if (empty($from)) {
		DropWithBadRequest('Derival city not specified');
	    }

	    if (empty($to)) {
		DropWithBadRequest('Arrrival city not specified');
	    }
	    
	    if (empty($oOptions->cargoSenderStreet)) {
		DropWithBadRequest('Derival street address not specified');
	    }

	    if (empty($oOptions->cargoRecepientStreet)) {
		DropWithBadRequest('Arrival street address not specified');
	    }

	    if (empty($oOptions->cargoSenderStreetType)) {
		DropWithBadRequest('Derival street type not specified');
	    }

	    if (empty($oOptions->cargoRecepientStreetType)) {
		DropWithBadRequest('Arrival street type not specified');
	    }

	    if (empty($oOptions->cargoSenderStreetHouse)) {
		DropWithBadRequest('Derival house not specified');
	    }

	    if (empty($oOptions->cargoRecepientStreetHouse)) {
		DropWithBadRequest('Arrival house not specified');
	    }

	    if (empty($senderPhone)) {
		DropWithBadRequest('Sender phone not specified');
	    }

	    if (empty($recepientPhone)) {
		DropWithBadRequest('Recepient phone not specified');
	    }

	    if (empty($oOptions->cargoPickupPeriod)) {
		DropWithBadRequest('Pickup period not specified');
	    }

//	    if (empty($oOptions->cargoDeliveryPeriod)) {
//		DropWithBadRequest('Delivery period not specified');
//	    }

	    if (empty($oOptions->arrivalTerminalId)) {
		DropWithBadRequest('Arrival terminal not specified');
	    }

	    if (floatval($vol) <= 0) {
		DropWithBadRequest('Volume not specified');
	    }

	    if (floatval($weight) <= 0) {
		DropWithBadRequest('Weight not specified');
	    }

	    if (empty($cargoName)) {
		DropWithBadRequest('Cargo name (content) not specified');
	    }

	    if (empty($oOptions->cargoService)) {
		DropWithBadRequest('Cargo service not specified');
	    }

	    if (floatval($oOptions->internalNumber) <= 0) {
		DropWithBadRequest('Internal number not specified');
	    }

	    
	    $orderData['header'] = [
			'datePickup' => date('Y-m-d',intval($cargoDate)),
			'senderAddress' => [
				'name' => $sSenderUserFIO,
				'terminalCode' => $oOptions->derivalTerminalId,
				'countryName' => 'Россия',
				'city' => $sCityFrom,
				'street' => $oOptions->cargoSenderStreet,
				'streetAbbr' => $oOptions->cargoSenderStreetType,
				'house' => $oOptions->cargoSenderStreetHouse,
				'contactPhone' => $sSenderPhone,
				'contactFio' => $sSenderUserFIO,
			    ],
			'pickupTimePeriod' => $oOptions->cargoPickupPeriod,
		    ];
		    
	    $orderData['order'] = [
			'orderNumberInternal' => $oOptions->internalNumber,
			'serviceCode' => $oOptions->cargoService,
			'serviceVariant' => $oOptions->cargoTransferType,
			'cargoNumPack' => 1,
			'cargoWeight' => round($weight,2),
			'cargoVolume' => round($vol,2),
			'cargoRegistered' => $oOptions->cargoRegistered ? TRUE : FALSE,
			'cargoCategory' => $cargoName,
			'deliveryTimePeriod' => $oOptions->cargoDeliveryPeriod,
			'cargoValue' => round(floatval($insPrice),2),
			
			'receiverAddress' => [
				    'name' => $sRecipientUserFIO,
				    'terminalCode' => $oOptions->arrivalTerminalId,
				    'countryName' => 'Россия',
				    'city' => $sCityTo,
				    'street' => $oOptions->cargoRecepientStreet,
				    'streetAbbr' => $oOptions->cargoRecepientStreetType,
				    'house' => $oOptions->cargoRecepientStreetHouse,
				    'contactPhone' => $sRecipientPhone,
				    'contactFio' => $sRecipientUserFIO,
			    ]
		    ];
	    
	    $orderParams = [];
	    $orderParams['orders'] = $orderData;

//	    print_r($orderParams);

    	    $orderURL = 'order2?wsdl';
    	    
	    $client = new SoapClient($this->server[1] . $orderURL);
	    
	    $ret = $client->createOrder($orderParams);
	    
//	    print_r($ret);
	    
	    if ((isset($ret->return->status)) && ($ret->return->status == 'OK')) {
		return $ret->return->orderNum;
	    } else {
		return false;
	    }
	    //print_r($ret); die();
        }
    }

//////////////////////////////////////////////////////

$transports[149] = array(
	'name' => calculator_DPD::name,
	'site' => calculator_DPD::site,
	'logo' => calculator_DPD::logo,
	'calcfunc' => 'DPD_calc',
	'language' => calculator_DPD::language,
	'currency' => calculator_DPD::currency,
	'classname' => 'calculator_DPD',
	'canorder' => true
	);
	
////////////////////////////////////////////

/*
$from = "Москва";
$to = "Челябинск";
$weight = 1;
$vol = 1;
$insPrice = 0;

DPD_calc($from,$to,$weight,$vol,$insPrice,"ru","ru","RUB","RUB",'RU','RU','','');
*/

////////////////////////////////////////////

function DPD_calc($from,$to,$weight,$vol,$insPrice,$client_lang,$transport_lang, $base_curr, $client_curr,
		 $cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo)
    {

	$calc = new calculator_DPD;
	$calc->Calculate($from,$to,$weight,$vol,$insPrice,$client_lang,$client_curr,$cargoCountryFrom, $cargoCountryTo, $cargoStateFrom, $cargoStateTo);
	
	exit(0);

    }

?>
