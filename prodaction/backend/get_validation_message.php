<?php
require_once('services.php');
require_once "service/config.php";
require_once "service/service.php";
//require_once "./service/user_class.php";

$oReqAnswer = json_decode(file_get_contents("php://input"));

$iClientId = $oReqAnswer->data->clientId;
$iMessageTypeId = intval($oReqAnswer->data->messageTypeId);
$sMessage = $oReqAnswer->data->message;

$resultMessage = array();

switch ($iMessageTypeId)
{
	case 1:
        $resultMessage = array(
            'status' => 'good',
            'message' => 'Регистрация пройдена успешно!'
        );
		break;

    case 2:
        $resultMessage = array(
            'status' => 'good',
            'message' => 'Временная проблема на сервере.  <br>Регистрация не пройдена. <br><br>Повторите подтверждение регистрации позже.'
        );
        break;

    case 3:
        $resultMessage = array(
            'status' => 'good',
            'message' => 'Регистрация не пройдена. <br><br>Текущая ссылка не активна.'
        );
        break;
    case 10:
        $resultMessage = array(
            'status' => 'good',
            'message' => 'Заказ № '.$sMessage.' был успешно оплачен в ситеме Paymaster. <br><br>Детали заказа вы можете прочитать здесь: <a href="/order_page.php?o='.$sMessage.'">ссылка</a>.'
        );
        break;
    case 11:
        $resultMessage = array(
            'status' => 'good',
            'message' => 'Заказ № '.$sMessage.' не оплачен в ситеме Paymaster. <br><br>Детали заказа вы можете прочитать здесь: <a href="/order_page.php?o='.$sMessage.'">ссылка</a>.<br><br>Детали можно уточнить у менеджера по телефону :'.PHONE_MANAGER
        );
        break;
    case 12:
        $resultMessage = array(
            'status' => 'good',
            'message' => 'Заказ № '.$sMessage.' был успешно оплачен в ситеме Paymaster, но не полностью.<br><br>Детали уточняйте у менеджера по телефону :'.PHONE_MANAGER
        );
    case 13:
        $resultMessage = array(
            'status' => 'good',
            'message' => 'Проблема верификации оплаты на серврере <br><br>Детали заказа вы можете прочитать здесь: <a href="/order_page.php?o='.$sMessage.'">ссылка</a>.'
        );
        break;

}

/*
$resultMessage = array(
		'status' => 'good',
		'message' => 'Регистрация пройдена успешно!'
	);
*/
header('Content-Type: application/json');
print(json_encode($resultMessage));

?>
