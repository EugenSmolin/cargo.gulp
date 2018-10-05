<?php

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/payment_class.php";
require_once "./service/finance_class.php";
require_once "./service/order_class.php";

    $alfaOrderId = $_GET['orderId'];
    $resVerify=urldecode(base64_decode($_GET['verif']));

    $result = explode ('_',$resVerify);
    if(sizeof($result)<>2)
    {
        echo 'Data for verify is incorrect';
    }
    $isVerified = (int)$result[1];
    $orderId = (int)$result[0];
    //var_dump($result); die;
    if (IS_PRODUCTION) {
        $host = DB_HOST;
    } else {
        $host = 'localhost';
    }

    $mysqli = new mysqlii($host, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

    // check DB connection
    if ($mysqli->connect_errno)
        DropWithServerError("DB error");

    $sReqURL = ALFA_API_SERVICE_LINK.'getOrderStatus.do?' .
            '&orderId=' . $alfaOrderId .
            '&password=' . ALFA_API_PASSWORD .
            '&userName=' . ALFA_API_USER_NAME;

    $oReqAnswer = json_decode(file_get_contents($sReqURL));

    $oOrder = new Order();
    $iOrderResult = $oOrder->GetClientIDBy($mysqli,$orderId);

    $clientID=0;

    $iAmount=0;

    if($iOrderResult->status == 'ok') {

        $clientID = $iOrderResult->id;

        $oPayment = new Payment();
        $oFinance = new Finance();
        //var_dump($oPayment);
        $sOrderNum = $oReqAnswer->OrderNumber;
        $sCardholderName = $oReqAnswer->cardholderName;
        $iAmount = $oReqAnswer->Amount;
        $sIPAddr = $oReqAnswer->Ip;
        $sCardNum = $oReqAnswer->Pan;
        $iAuthCode = $oReqAnswer->authCode;
        $iErrorCode = $oReqAnswer->ErrorCode;

        //$aParsedOrder = split(';',$sOrderNum);

        $orderStatus = '';
        $errorCode = '';

        switch ($iAuthCode) {
            case 0:
                $orderStatus = 'Заказ #' . $orderId . ' зарегистрирован, но не оплачен';
                break;
            case 1:
                $orderStatus = 'Предавторизованная сумма захолдирована';
                break;
            case 2:
                $orderStatus = 'Заказ №' . $orderId . ' был успешно оплачен.';
                break;
            case 3:
                $orderStatus = 'Авторизация отменена';
                break;
            case 4:
                $orderStatus = 'По транзакции была проведена операция возврата';
                $oPayment->Refund($mysqli, $orderId);
                break;
            case 5:
                $orderStatus = 'Инициирована авторизация через ACS банка-эмитента';
                break;
            case 6:
                $orderStatus = 'Авторизация отклонена';
                $oPayment->Reject($mysqli, $orderId);
                break;
        }

        switch ($iErrorCode) {
            case 0:
                $errorCode = '';
                break;
            case 2:
                $errorCode = 'Заказ  №' . $orderId . ' отклонен по причине ошибки в реквизитах платежа';
                break;
            case 5:
                $errorCode = 'Доступ запрещён. Пользователь должен сменить свой пароль или [orderId] не указан.';
                break;
            case 6:
                $errorCode = 'Заказ  №' . $orderId . '. Неверный номер заказа';
                break;
            case 7:
                $errorCode = 'Системная ошибка. Обратитесь в службу поддержки клиентов.';
                break;
        }
    }
    else
    {
        $errorCode = 'Ошибка базы данных';
        $iAuthCode == -1;
        $orderStatus = "Заказ не оплачен";
    }

    if($errorCode!='')
    {
        $orderStatus = $errorCode;
    }


    if (($iAuthCode == 2) && ($iErrorCode == 0))
    {

        $sDescription = "Зачислено через Альфа-Банк (карта " . $sCardNum . ", владелец " . $sCardholderName . ") с IP " . $sIPAddr;
        $oPayment->Finish($mysqli, $orderId);
        $oFinance->NewOperation($mysqli,round($iAmount / 100,2),$clientID,$orderId,$sDescription);
    }
    else
    {
        $oFinance->NewOperation($mysqli,0,$clientID,$orderId,$orderStatus);
    }
        echo
        "
<head>
<link rel=\"stylesheet\" href=\"content/main.css\">
</head>
<body>
<div id=\"issue_order_form\" class=\"fullheight flex\">
<div class=\"container flex_content\">
 <header class=\"header clearfix\">
  <div class=\"main_logo pull-left\">
    <a href=\"".HOME_SITE_URL."\" class=\"inline-block\">
      <img src=\"content/logo.png\" alt=\"\">
      <span class=\"text-uppercase hidden-xs\"> Cargo.guru </span>
    </a>
  </div>
  <div class=\"right_side_header\">
    <div class=\"right_side_header_overlay\"></div>
    <div class=\"right_side_header_inner\">
      <div class=\"bg_contain\">
        <div class=\"list_menu inline-block\">
        <ul class=\"list-inline main_ul\">
          <li> <a tabindex=\"0\" href=\"".HOME_SITE_URL."/order_calc.php\" class=\"underborder\"> Калькулятор </a> </li>
          <li> <a href=\"#!\" class=\"underborder\"> О компании  </a> </li>
          <li> <a href=\"#!\" class=\"underborder\"> Справка     </a> </li>
          <li>
            <a tabindex=\"0\" href=\"#!\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">
              <span class=\"drop_li\">
                <img src=\"content/ru.png\" alt=\"\">
                <span class=\"underborder\"> Русский </span>
                <i class=\"glyphicon glyphicon-menu-down\" aria-hidden=\"true\"></i>
              </span>
            </a>
            <ul class=\"dropdown-menu\">
              <li></li>
              <li></li>
              <li></li>
            </ul>
          </li>

          <li>
            <a href=\"#!\" class=\"dropdown-toggle drop_li\" data-toggle=\"dropdown\">
              <span class=\"currency\">
                <span class=\"underborder\">₽ RUB</span>
              </span>
              <i class=\"glyphicon glyphicon-menu-down\" aria-hidden=\"true\"></i>
            </a>
            <ul class=\"text-center currency_list dropdown-menu\">
                <li>
                  <a href=\"#!\" onclick=\"mainCurr(this)\">
                      <span class=\"underborder\"> $ CAD </span>
                  </a>
                </li>
                <li>
                  <a href=\"#!\" onclick=\"mainCurr(this)\">
                      <span class=\"underborder\"> ¥ CNY </span>
                  </a>
                </li>
                <li>
                  <a href=\"#!\" onclick=\"mainCurr(this)\">
                      <span class=\"underborder\"> € EUR </span>
                  </a>
                </li>
                <li>
                  <a href=\"#!\" onclick=\"mainCurr(this)\">
                      <span class=\"underborder\"> £ GBP </span>
                  </a>
                </li>
                <li>
                  <a href=\"#!\" onclick=\"mainCurr(this)\">
                      <span class=\"underborder\"> $ HKD </span>
                  </a>
                </li>
                <li>
                  <a href=\"#!\" onclick=\"mainCurr(this)\">
                      <span class=\"underborder\"> ₸ KZT </span>
                  </a>
                </li>
                <li>
                  <a href=\"#!\" onclick=\"mainCurr(this)\">
                      <span class=\"underborder\"> $ NZD </span>
                  </a>
                </li>
                <li>
                  <a href=\"#!\" onclick=\"mainCurr(this)\">
                      <span class=\"underborder\"> $ USD </span>
                  </a>
                </li>
              
                <li>
                  <a href=\"#!\" onclick=\"mainCurr(this)\">
                      <span class=\"underborder\"> R ZAR </span>
                  </a>
                </li>
                <li>
                  <a href=\"#!\" onclick=\"mainCurr(this)\">
                      <span class=\"underborder\"> ₽ RUB </span>
                  </a>
                </li>
            </ul>
          </li>
        </ul>
      </div>
      </div>
    </div>
  </div>
        <button type=\"button\" class=\"mobile_btn visible-sm visible-xs\"></button>
    </header>
      <div style=\"margin: 80px;color: #9c3b8b;font-size: 3rem;\"> <h1>".$orderStatus."</h1><div>
      <div style=\"margin-top: 60px;font-size: 1.5rem;\">
       <a href=\"".HOME_SITE_URL."\" class=\"inline-block\">
          <span class=\"underborder text-uppercase hidden-xs\"> Вернуться на сайт </span>
        </a>
      </div>
        </div>
</body>";



    $mysqli->close();
?>