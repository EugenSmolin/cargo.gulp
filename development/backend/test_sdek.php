<?php

require_once( 'index.php' );

include_once( 'modules/CALC_dpd.php' );
//$calc = new calculator_SDEK();

$response = array();

//$res = $calc->
//$from;
//$to;
//$weight;
//$vol;
//$insPrice;
//$clientLang;
//$clientCurr;
//$cargoCountryFrom;
//$cargoCountryTo;
//$cargoStateFrom;
//$cargoStateTo;
//$isActiveLineParams;
//$width;
//$length;
//$height;
//$options = [];

$order = new calculator_DPD();

//$res = $calc->Calculate(
//	69076,
//	03134,
//	10,
//	10,
//	10,
//	10,
//	1000,
//	'RU',
//	'RU',
//	'',
//	'',
//	true,
//	10,
//	10,
//	10 );
//

//$sCityFrom = 'Москва';
//$sCityTo = 'Ростов-на-Дону';
//$sCargoFromZip = '109012';
//$sCargoToZip = '443084';
//$sCargoFromRegion = 'Москва';
//$sCargoToRegion = 'Ростов-на-Дону';
//$weight = '1';
//$vol = '0.11';
//$insPrice = '1300';
//$length = '0.30556';
//$width = '0.6';
//$height = '0.6';
//$cargoName = 'СДЕК';
//$cargoDate = time();
//$oOptions = '';
//$isRecipientJur = 'Юридическое лицо';
//$sRecipientUserFIO = '';
//$iRecipientDocumentTypeId = '1';
//$sRecipientDocumentNumber = '2';
//$sRecipientPhone = '+2 222 222 22 22';
//$sRecipientEmail = 'li.anton13@gmail.com';
//$iRecipientTerminalID = '20';
//$sRecipientCompanyName = 'Название компании получателя';
//$sRecipientCompanyFormShortName = '';
//$sRecipientCompanyINN = '22222222222';
//$sRecipientCompanyAddress = '119049, Москва, Ленинский проспект, 2';
//$sRecipientCompanyAddressCell = '';
//$sRecipientCompanyPhone = '';
//$sRecipientCompanyEmail = 'li.anton13@gmail.com';
//$sRecepientContactFirstName = 'Имя получателя';
//$sRecepientContactSecondName = 'Отчество получателя';
//$sRecepientContactLastName = 'Фамилия получателя';
//$sRecipientAddress = '';
//$sRecipientAddressCell = '';
//$isSenderJur = 'Юридическое лицо';
//$sSenderUserFIO = '';
//$iSenderDocumentTypeId = '1';
//$sSenderDocumentNumber = '2';
//$sSenderPhone = '+2 222 222 22 22';
//$sSenderEmail = 'andrii.sokoliuk@gmail.com';
//$iSenderTerminalID = '36';
//$sSenderCompanyName = 'Название компаниии отправителя';
//$sSenderCompanyFormShortName = '';
//$sSenderCompanyINN = '22222222222';
//$sSenderCompanyAddress = '119049, Москва, Ленинский проспект, 2';
//$sSenderCompanyAddressCell = '';
//$sSenderCompanyPhone = '+2 222 222 22 22';
//$sSenderCompanyEmail = 'andrii.sokoliuk@gmail.com';
//$sSenderFirstName = 'Имя отправителя';
//$sSenderSecondName = 'Отчество отправителя';
//$sSenderLastName = 'Фамилия отправителя';
//$sSenderAddress = '';
//$sSenderAddressCell = '';
//$isDerivalCourier = 'Самостоятельно доставить груз до терминала';
//$isArrivalCourier = 'Самостоятельно доставить груз до терминала';
//$dCargoDesireDate = '';
//$dCargoDeliveryDate = '';


//$res = $order->MakeOrder($sCityFrom, $sCityTo, $sCargoFromZip, $sCargoToZip, $sCargoFromRegion, $sCargoToRegion,
//	$weight, $vol, $insPrice, $length, $width, $height, $cargoName, $cargoDate, $oOptions, $isRecipientJur,
//	$sRecipientUserFIO, $iRecipientDocumentTypeId, $sRecipientDocumentNumber, $sRecipientPhone, $sRecipientEmail,
//	$iRecipientTerminalID, $sRecipientCompanyName, $sRecipientCompanyFormShortName, $sRecipientCompanyINN, $sRecipientCompanyAddress,
//	$sRecipientCompanyAddressCell, $sRecipientCompanyPhone, $sRecipientCompanyEmail, $sRecepientContactFirstName, $sRecepientContactSecondName,
//	$sRecepientContactLastName, $sRecipientAddress, $sRecipientAddressCell, $isSenderJur, $sSenderUserFIO, $iSenderDocumentTypeId,
//	$sSenderDocumentNumber, $sSenderPhone, $sSenderEmail, $iSenderTerminalID, $sSenderCompanyName, $sSenderCompanyFormShortName,
//	$sSenderCompanyINN, $sSenderCompanyAddress, $sSenderCompanyAddressCell, $sSenderCompanyPhone, $sSenderCompanyEmail, $sSenderFirstName,
//	$sSenderSecondName, $sSenderLastName, $sSenderAddress, $sSenderAddressCell, $isDerivalCourier, $isArrivalCourier, $dCargoDesireDate, $dCargoDeliveryDate);


//var_dump( $res );
$from="Саратов";
$to="Челябинск";
$weight="1";
$vol="1";
$insPrice="100";
$clientLang="ru";

$clientCurr="";
$cargoCountryFrom="Саратов";
$cargoCountryTo="Челябинск";

$cargoStateFrom="Саратов";
$cargoStateTo="Челябинск";

$isActiveLineParams="1";
$width="2";
$length="2";
$height="2";
$options = $order->GetOptions();


$calc = $order->Calculate($from, $to, $weight, $vol, $insPrice, $clientLang,
	$clientCurr, $cargoCountryFrom, $cargoCountryTo,
	$cargoStateFrom, $cargoStateTo,
	$isActiveLineParams, $width, $length, $height,
	$options);


//$res = $calc->GetCityIndex('Москва');

//var_dump($calc);
//GetCalculateFromAddressToAddress();

//$getCityIndex = $order ->GetCityIndex('Челябинск');
//$get = $order->GetOptions();

//var_dump($get);

?>