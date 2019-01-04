<?php

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/user_class.php";
require_once "./service/finance_class.php";

$sLogin = 'cargo_guru-api';
$sPassword = 'cargo_guru';

$sOrderId = $_GET['orderId'];

$sReqURL = 'https://test.paymentgate.ru/testpayment/rest/getOrderStatus.do?' .
	    '&orderId=' . $sOrderId .
	    '&password=' . $sPassword . 
	    '&userName=' . $sLogin;

$oReqAnswer = json_decode(file_get_contents($sReqURL));

$sOrderNum = $oReqAnswer->OrderNumber;
$sCardholderName = $oReqAnswer->cardholderName;
$iAmount = $oReqAnswer->Amount;
$sIPAddr = $oReqAnswer->Ip;
$sCardNum = $oReqAnswer->Pan;
$iAuthCode = $oReqAnswer->authCode;
$iErrorCode = $oReqAnswer->ErrorCode;

$aParsedOrder = explode(';',$sOrderNum);

if (($iAuthCode == 2) && ($iErrorCode == 0))
    {

	// connect to DB
	$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

	// check DB connection
	if ($mysqli->connect_errno)
	    DropWithServerError("DB error");

	$sDescription = "Зачислено через Альфа-Банк (карта " . $sCardNum . ", владелец " . $sCardholderName . ") с IP " . $sIPAddr;

	$oFinance = new Finance();
	$iRes = $oFinance->NewOperation($mysqli,round($iAmount / 100,2),$aParsedOrder[0],$aParsedOrder[1],$sDescription);
	
	$mysqli->close();
	print("<h1>Ай, хорошо, молодец!</h1>");
    }
else if ($iAuthCode == 1)
    {
	print("<h1>Деньги заблокированы, но нам еще не пришли. Попробуйте помолиться.</h1>");
    }
else
    {
	print("<h1>Платеж не прошёл. Зарабатывайте больше.</h1>");
    }

?>
