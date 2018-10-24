<?php

require_once "./service/config.php";

class Alfabank_adapter
{
    public $iOrderID    = 0;
    public $sFormUrl    = '';
    public $sError      = '';

    /**
     * @param string $orderNumber
     * @param int $amount
     * @param string $clientEmail
     * @param string $description
     * @param string $lang
     * @return string
     */
    public function CreateOrder($orderNumber='',
                                $amount=0,
                                $clientEmail='',
                                $expirationDate=0,
                                $description='',
                                $lang="ru")
    {
        if(!isset($orderNumber)||$orderNumber=='')
        {
            $this->sError='OrderNumber is not set';
            return false;
        }

        if(!isset($amount)||$amount==0)
        {
            $this->sError='Amount is not set';
            return false;
        }

        if(!isset($expirationDate)||$expirationDate==0)
        {
            $this->sError='Expiration Date is not set';
            return false;
        }

        if($expirationDate < strtotime("now"))
        {
            $expirationDateFormated = date('Y-m-d\TH:i:s', strtotime("now +1 day"));
        }
        else
        {
            $expirationDateFormated = date('Y-m-d\TH:i:s', $expirationDate);
        }

        $verified = $orderNumber.'_1';//"{\"o\":".$orderNumber.",\"v\":1}";
        $notVerified =$orderNumber.'_0'; //"{\"o\":".$orderNumber.",\"v\":0}";

        $returnUrl = ALFA_API_CONFIRM_URL.urlencode(base64_encode($verified));
        $failUrl = ALFA_API_CONFIRM_URL.urlencode(base64_encode($notVerified));

        $params="amount=".$amount
            ."&currency=".ALFA_API_CURRENCY
            ."&language=".$lang
            ."&orderNumber=".$orderNumber
            ."&password=".ALFA_API_PASSWORD
            ."&returnUrl=".$returnUrl
            ."&failUrl=".$failUrl
            ."&userName=".ALFA_API_USER_NAME
            ."&jsonParams={\"orderNumber\":".$orderNumber.",\"email\":\"".$clientEmail."\"}"
            ."&pageView=DESKTOP"
            ."&expirationDate=".$expirationDateFormated;

        if(isset($clientEmail))
        {
            $params.="&jsonParams={\"orderNumber\":".$orderNumber.",\"email\":\"".$clientEmail."\"}";
        }
        else
        {
            $params.="&jsonParams={\"orderNumber\":".$orderNumber."}";
        }

        if(isset($description))
        {
            $params.="&description=".$description;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ALFA_API_SERVICE_LINK."register.do");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        $result = json_decode(curl_exec($ch));



        $orderId = $result->orderId;
        $paymentFormUrl =  $result->formUrl;

        curl_close($ch);

        if(isset($orderId) && isset($paymentFormUrl))
        {
            $this->iOrderID = $orderId;
            $this->sFormUrl = $paymentFormUrl;
            return true;
        }
        else
        {
            $this->sError = $result->errorCode;
            return false;
        }
    }

    /**
     * @param $alfaOrderId
     * @param $amount
     * @param string $lang
     */
    public function RefundOrder($alfaOrderId,$amount,$lang="ru")
    {

        // TODO: Finish validation

        $string="language=".$lang
            ."&amount=".$amount
            ."&currency=".ALFA_API_CURRENCY
            ."&orderId=".$alfaOrderId
            ."&password=".ALFA_API_PASSWORD
            ."&userName=".ALFA_API_USER_NAME;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ALFA_API_SERVICE_LINK."refund.do");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        $token_data = curl_exec($ch);
        curl_close($ch);
        // TODO: Finish return of result
    }

    /**
     * Reverse order only for Admin
     * @param $id
     * @param $lang
     */
    public function ReverseOrder($alfaOrderId,$lang="ru")
    {
        // TODO: Finish validation

        $string="language=".$lang
                ."&orderId=".$alfaOrderId
                ."&password=".ALFA_API_PASSWORD
                ."&userName=".ALFA_API_USER_NAME;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ALFA_API_SERVICE_LINK."reverse.do");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        $token_data = curl_exec($ch);
        curl_close($ch);
        // TODO: Finish return of result

    }
}

?>