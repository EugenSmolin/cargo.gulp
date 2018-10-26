<?php
require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/user_class.php";
require_once "./service/auth.php";
require_once "./service/order_class.php";
require_once "./services.php";
require_once  "./service/alfabank_adapter.php";
require_once "./service/payment_class.php";
$orderId =(int)'888882';
$amount = '10000';
$clientEmail = '';
$from='Москва';
$to = 'Санкт-Петербург';
$lang = 'en';
$host = 'localhost';
$expirationDate = strtotime("now +2 day");
$now = strtotime("now -1 day");

    if(IS_PRODUCTION) {
        $host = DB_HOST;
    }
    // connect to DB
    $mysqli = new mysqlii($host,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

    if(IS_PRODUCTION)
    {
        // check POST body
        $oPOSTData = json_decode(file_get_contents("php://input"));

        if(!IS_DEBUG)
        {
            $iUserID = CheckAuth();
            if ($iUserID === false)
                DropWithUnAuth();
        }
        else
        {
            $iUserID = 15;
        }

        $oUser = new User();

        // trying to authenticate
        $iAuth = $oUser->UserFromID($mysqli, $iUserID);

        // check DB connection
        if ($mysqli->connect_errno)
            DropWithServerError("DB error");

        if(isset($oPOSTData->data->lang))
        {
            $lang = $oPOSTData->data->lang;
        }

        if(!isset($oPOSTData->data->orderId))
        {
            $msg = __GetAllTranslations("OrderId not set.","en");
            DropWithServerError($msg[$lang]);
        }

        $orderId = $oPOSTData->data->orderId;

        $oOrder = new Order();

        $aOrder = $oOrder->OrderFromID($mysqli, $orderId);
       // echo $oOrder->iOrderClientID , " ", $iUserID;
        if($oOrder->iOrderClientID != $iUserID)
        {
            $msg = __GetAllTranslations("У пользователя отсутствует доступ к заказу.","ru");
            DropWithBadMsg($msg[$lang]);
        }

        $expirationDate = $oOrder->sOrderDesiredDate;
        $amount = intval($oOrder->fOrderCargoPrice)*100;
        $from = $oOrder->sOrderCargoFrom;
        $to = $oOrder->sOrderCargoTo;
        $clientEmail = $oUser->userEMail;
        if ($now > $expirationDate)
        {
            // echo $now,'   ' ,strtotime($expirationDate);
            $msg = __GetAllTranslations("Order expired.","en");
           // DropWithBadRequest($msg[$lang]);
        }
    }

    $description = 'Отправка груза из '.$from.' в '.$to;

    $oPayment = new Payment();
    //var_dump($oPayment); die();
    $alfa = new Alfabank_adapter();
    //var_dump($alfa); die();
    $bResult = $oPayment->Info($mysqli, $orderId);
    //var_dump($oPayment); die();
    //var_dump($bResult); die();
    if ($oPayment->isStatusOK) {

        if ($now > $oPayment->dExpirationDate && $oPayment->isPayed == 'new') {
            // order opened but expired need to reject order
            $oPayment->Reject($mysqli, $orderId);
            $msg = __GetAllTranslations("Order expired.","en");
            DropWithBadRequest($msg[$lang]);
        }

        if ($now < $oPayment->dExpirationDate && $oPayment->isPayed == 'new') {
           GoodAnswer($oPayment->sFormUrl);
        }
    }

    if ($oPayment->isExist) {
        DropWithServerError($bResult);
    }

    $result = $alfa->
        CreateOrder($orderId,
            $amount,
            $clientEmail,
            $expirationDate,
            $comment,$lang);

    if ($result) {

        $iRes = $oPayment->Create($mysqli,
            $orderId,
            $alfa->iOrderID,
            $alfa->sFormUrl,
            $expirationDate);

        GoodAnswer($oPayment->sFormUrl);
        exit(0);
    }
    else
    {
        DropWithServerError($oPayment->sError);
    }

    function GoodAnswer($url)
    {
        http_response_code(200);

        $oAnswer = array(
            "status" => "ok",
            "url"=> $url,
            "failReason" => "");

        header('Content-Type: application/json');
        print(json_encode($oAnswer));
        exit(0);
    }

?>