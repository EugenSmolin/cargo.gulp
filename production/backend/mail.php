<?php

require_once "./service/config.php";
require_once "./service/service.php";
require_once "services.php";
require_once "./service/user_class.php";
require_once "./service/order_class.php";
require_once "./service/auth.php";
require_once "./service/finance_class.php";
require_once "./service/mail_class.php";

// check POST body
$strPerson = '
{
  "data": {},
  "modifies": {
    "clientID": 187,
    "derivalTerminalName": "Москва Север (Лобненская ул., 18)",
    "arrivalTerminalName": "Санкт-Петербург Крупской (ул. Крупской, 29)",
    "cargoVolUnitName": "м³",
    "cargoWeightUnitName": "кг",
    "cargoRecepientCompanyRegion": "",
    "cargoRecepientCompanyCity": "",
    "cargoRecepientCompanyZip": "",
    "cargoRecepientCompanyStreet": "",
    "cargoRecepientCompanyStreetNumber": "",
    "cargoSenderCompanyRegion": "",
    "cargoSenderCompanyCity": "",
    "cargoSenderCompanyZip": "",
    "cargoSenderCompanyStreet": "",
    "cargoSenderCompanyStreetNumber": "",
    "cargoMethod": "Автотранспорт",
    "cargoName": "Деловые Линии",
    "cargoFrom": "Москва, Москва",
    "cargoFromZip": "109012",
    "cargoFromRegion": "Москва",
    "cargoTo": "Санкт-Петербург, Санкт-Петербург",
    "cargoToZip": "191186",
    "cargoToRegion": "Санкт-Петербург",
    "cargoRecepientAddress": "Санкт-Петербург Крупской (ул. Крупской, 29)",
    "cargoWeight": 10,
    "cargoVol": 0.1,
    "cargoPrice": 770,
    "cargoSite": "http://www.dellin.ru",
    "cargoDangerClass": "",
    "cargoTemperature": "",
    "cargoCompanyID": 32,
    "cargoDate": 1515595854,
    "derivalCourier": "Самостоятельно доставить груз до терминала",
    "derivalTerminalId": "36",
    "arrivalCourier": "Самостоятельно забрать груз на терминале",
    "arrivalTerminalId": "365",
    "senderPhysOrJur": "Физическое лицо",
    "cargoSenderLastName": "sdsd",
    "cargoSenderFirstName": "user",
    "cargoSenderSecondName": "sdsds",
    "cargoSenderPhone": "+7 333 333 33 33",
    "cargoSenderEmail": "a1@gmail.com",
    "cargoSenderDocumentTypeId": "1",
    "cargoSenderDocumentNumber": "6664444444",
    "recepientPhysOrJur": "Физическое лицо",
    "cargoRecepientLastName": "deded",
    "cargoRecepientFirstName": "dededed",
    "cargoRecepientPhone": "+7 222 222 22 22",
    "cargoRecepientEmail": "a2@gmail.com",
    "cargoRecepientDocumentTypeId": "1",
    "cargoRecepientDocumentNumber": "3333333333",
    "cargoGoodsName": "Dsdsdsd",
    "cargoTemperatureModeId": "1",
    "cargoDangerClassId": "1",
    "cargoWeightTypeID": "1",
    "cargoVolTypeID": "1",
    "cargoGoodsPrice": 100,
    "cargoWidth": 1,
    "cargoLength": 0.1,
    "cargoHeight": 1,
    "paymentType": "1"
  }
}';


$oPOSTData = json_decode($strPerson);

$iUserID = 187;

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
    DropWithServerError("DB error");

// create User object
$cUser = new User();

// trying to authenticate
$iAuth = $cUser->UserFromID($mysqli, $iUserID);

if ($iAuth != USER_OK)
    DropWithUnAuth();

//print(strlen($oPOSTData->modifies->cargoRecipientPassport));

// check for parameters presence
if (!isset($oPOSTData->modifies->clientID)
    //or !isset($oPOSTData->modifies->cargoName)
    or (!isset($oPOSTData->modifies->cargoCompanyID))
    or !isset($oPOSTData->modifies->cargoFrom)
    or !isset($oPOSTData->modifies->cargoTo)
    or !isset($oPOSTData->modifies->cargoWeight)
    or !isset($oPOSTData->modifies->cargoPrice)
    //or !isset($oPOSTData->modifies->cargoSite)
    or (!isset($oPOSTData->modifies->cargoVol)
        and !isset($oPOSTData->modifies->cargoLength)
        and !isset($oPOSTData->modifies->cargoWidth)
        and !isset($oPOSTData->modifies->cargoHeight))
)
{
    DropWithBadRequest("Not enough or wrong parameters");
}

// prepare data for parcel

if (!$cUser->isAdmin)
    $iClientID = $cUser->userID;
else
    $iClientID = $oPOSTData->modifies->clientID;

/* Check RecipientPhysOrJur parameters*/
$isRecipientJur = false;
$isSenderJur = false;

$isDerivalCourier = false;
$isArrivalCourier = false;

if(intval($oPOSTData->modifies->cargoCompanyID)==32) {
    if (!isset($oPOSTData->modifies->derivalCourier)) {
        DropWithBadRequest("derivalCourier didn't set");
    } else {
        if ($oPOSTData->modifies->derivalCourier == "Забор груза от адреса отправителя") {
            if (!isset($oPOSTData->modifies->cargoSenderAddress)
                //or (!isset($oPOSTData->modifies->cargoSenderHouseNumber))
            ) {
                DropWithBadRequest("Not enough parameters for selected derivalCourier");
            }

            $isDerivalCourier = true;
        } else {
            if (!isset($oPOSTData->modifies->derivalTerminalId)) {
                DropWithBadRequest("Not enough parameters for selected derivalTerminalId");
            }
        }
    }

    if (!isset($oPOSTData->modifies->arrivalCourier)) {
        DropWithBadRequest("arrivalCourier didn't set");
    } else {
        if ($oPOSTData->modifies->arrivalCourier == "Доставить груз до адреса получателя") {
            if (!isset($oPOSTData->modifies->cargoRecepientAddress)
                //  or (!isset($oPOSTData->modifies->cargoRecepientHouseNumber))
            ) {
                DropWithBadRequest("Not enough parameters for selected derivalCourier");
            }

            $isArrivalCourier = true;
        } else {
            if (!isset($oPOSTData->modifies->arrivalTerminalId)) {
                DropWithBadRequest("Not enough parameters for selected derivalTerminalId");
            }
        }
    }
}
if (!isset($oPOSTData->modifies->recepientPhysOrJur)) {
    DropWithBadRequest("RecepientPhysOrJur didn't set");
} else {
    if ($oPOSTData->modifies->recepientPhysOrJur == "Физическое лицо") {

        if (!isset($oPOSTData->modifies->cargoRecepientFirstName)) {
            DropWithBadRequest("Not set parameter cargoRecepientFirstName");
        }
        if (!isset($oPOSTData->modifies->cargoRecepientLastName)) {
            DropWithBadRequest("Not set parameter cargoRecepientLastName");
        }
        if (!isset($oPOSTData->modifies->cargoRecepientDocumentTypeId)) {
            DropWithBadRequest("Not set parameter cargoRecepientDocumentTypeId");
        }
        if (!isset($oPOSTData->modifies->cargoRecepientDocumentNumber)) {
            DropWithBadRequest("Not set parameter cargoRecepientDocumentNumber");
        }
        if (!isset($oPOSTData->modifies->cargoRecepientPhone)) {
            DropWithBadRequest("Not set parameter cargoRecepientPhone");
        }
        if (!isset($oPOSTData->modifies->cargoRecepientEmail)) {
            DropWithBadRequest("Not set parameter cargoRecepientEmail");
        }
    } elseif ($oPOSTData->modifies->recepientPhysOrJur == "Юридическое лицо") {
        $isRecipientJur = true;
        if (!isset($oPOSTData->modifies->cargoRecepientContactFirstName)) {
            DropWithBadRequest("Not set parameter cargoRecepientContactFirstName");
        }

        if (!isset($oPOSTData->modifies->cargoRecepientContactLastName)) {
            DropWithBadRequest("Not set parameter cargoRecepientContactLastName");
        }
        if (!isset($oPOSTData->modifies->cargoRecepientCompanyName)) {
            DropWithBadRequest("Not set parameter cargoRecepientCompanyName");
        }
        if (!isset($oPOSTData->modifies->cargoRecepientCompanyFormId)) {
            DropWithBadRequest("Not set parameter cargoRecepientCompanyFormId");
        }
        if (!isset($oPOSTData->modifies->cargoRecepientCompanyINN)) {
            DropWithBadRequest("Not set parameter cargoRecepientCompanyINN");
        }
        if (!isset($oPOSTData->modifies->cargoRecepientCompanyPhone)) {
            DropWithBadRequest("Not set parameter cargoRecepientCompanyPhone");
        }
        if (!isset($oPOSTData->modifies->cargoRecepientCompanyAddress)) {
            DropWithBadRequest("Not set parameter cargoRecepientCompanyAddress");
        }
        if (!isset($oPOSTData->modifies->cargoRecepientCompanyPhone)) {
            DropWithBadRequest("Not set parameter cargoRecepientCompanyAddressCell");
        }
    } else {
        DropWithBadRequest("Not assigned RecepientPhysOrJur parameter");
    }
}


if (!isset($oPOSTData->modifies->senderPhysOrJur))
{
    DropWithBadRequest("SenderPhysOrJur didn't set");
}
else
{
    if($oPOSTData->modifies->senderPhysOrJur=="Физическое лицо")
    {
        if (!isset($oPOSTData->modifies->cargoSenderFirstName))
        {
            DropWithBadRequest("Not set parameter cargoSenderFirstName");
        }
        if (!isset($oPOSTData->modifies->cargoSenderLastName))
        {
            DropWithBadRequest("Not set parameter cargoSenderLastName");
        }
        if (!isset($oPOSTData->modifies->cargoSenderDocumentTypeId))
        {
            DropWithBadRequest("Not set parameter cargoSenderDocumentTypeId");
        }
        if (!isset($oPOSTData->modifies->cargoSenderDocumentNumber))
        {
            DropWithBadRequest("Not set parameter cargoSenderDocumentNumber");
        }
        if (!isset($oPOSTData->modifies->cargoSenderPhone))
        {
            DropWithBadRequest("Not set parameter cargoSenderPhone");
        }
        if (!isset($oPOSTData->modifies->cargoSenderEmail))
        {
            DropWithBadRequest("Not set parameter cargoSenderEmail");
        }
    }
    elseif($oPOSTData->modifies->senderPhysOrJur=="Юридическое лицо") {
        $isSenderJur = true;
        if (!isset($oPOSTData->modifies->cargoSenderContactFirstName)) {
            DropWithBadRequest("Not set parameter cargoSenderContactFirstName");
        }
        if (!isset($oPOSTData->modifies->cargoSenderContactLastName)) {
            DropWithBadRequest("Not set parameter cargoSenderContactFirstName");
        }
        if (!isset($oPOSTData->modifies->cargoSenderCompanyName)) {
            DropWithBadRequest("Not set parameter cargoSenderContactFirstName");
        }
        if (!isset($oPOSTData->modifies->cargoSenderCompanyFormId)) {
            DropWithBadRequest("Not set parameter cargoSenderContactFirstName");
        }
        if (!isset($oPOSTData->modifies->cargoSenderCompanyINN)) {
            DropWithBadRequest("Not set parameter cargoSenderContactFirstName");
        }
        if (!isset($oPOSTData->modifies->cargoSenderCompanyPhone)) {
            DropWithBadRequest("Not set parameter cargoSenderContactFirstName");
        }
        if (!isset($oPOSTData->modifies->cargoSenderCompanyAddress)) {
            DropWithBadRequest("Not set parameter cargoSenderCompanyAddress ");
        }
        if (!isset($oPOSTData->modifies->cargoSenderCompanyPhone)) {
            DropWithBadRequest("Not set parameter cargoSenderCompanyAddressCell");
        }
    }
    else
    {
        DropWithBadRequest("Not assigned SenderPhysOrJur parameter");
    }
}


/////////////////// create order
// create order

$oNewOrder = new Order();

$iNewOrderResult = $oNewOrder->NewOrder
(
    $mysqli,
    $iClientID,
    $oPOSTData->modifies->cargoCompanyID,
    $oPOSTData->modifies->cargoName,
    $oPOSTData->modifies->cargoFromCoordinate,
    $oPOSTData->modifies->cargoToCoordinate,
    $oPOSTData->modifies->cargoFrom,
    $oPOSTData->modifies->cargoTo,
    $oPOSTData->modifies->cargoFromZip,
    $oPOSTData->modifies->cargoToZip,
    $oPOSTData->modifies->cargoFromRegion,
    $oPOSTData->modifies->cargoToRegion,

    $oPOSTData->modifies->cargoWeight,
    $oPOSTData->modifies->cargoVol,
    $oPOSTData->modifies->cargoLength,
    $oPOSTData->modifies->cargoWidth,
    $oPOSTData->modifies->cargoHeight,
    $oPOSTData->modifies->cargoValue,
    $oPOSTData->modifies->cargoPrice,
    $oPOSTData->modifies->cargoMethod,
    $oPOSTData->modifies->cargoSite,
    $oPOSTData->modifies->comment,
    $oPOSTData->modifies->cargoDangerClassId,
    $oPOSTData->modifies->cargoTemperatureModeId,
    $oPOSTData->modifies->cargoGoodsName,
    $oPOSTData->modifies->cargoDesireDate,
    $oPOSTData->modifies->cargoDeliveryDate,
    json_encode($oPOSTData->modifies),
    $oPOSTData->modifies->paymentType,

    $isSenderJur,
    $oPOSTData->modifies->cargoSenderFirstName,
    $oPOSTData->modifies->cargoSenderLastName,
    $oPOSTData->modifies->cargoSenderSecondName,
    $oPOSTData->modifies->cargoSenderPhone,
    $oPOSTData->modifies->cargoSenderEmail,
    $oPOSTData->modifies->cargoSenderDocumentTypeId,
    $oPOSTData->modifies->cargoSenderDocumentNumber,


    $oPOSTData->modifies->cargoSenderCompanyName,
    $oPOSTData->modifies->cargoSenderCompanyFormId,
    $oPOSTData->modifies->cargoSenderCompanyPhone,
    $oPOSTData->modifies->cargoSenderCompanyEmail,
    $oPOSTData->modifies->cargoSenderCompanyINN,
    $oPOSTData->modifies->cargoSenderCompanyAddress,
    $oPOSTData->modifies->cargoSenderCompanyAddressCell="",

    $oPOSTData->modifies->derivalTerminalId,
    $oPOSTData->modifies->derivalTerminalName,
    $oPOSTData->modifies->cargoSenderAddress,
    $oPOSTData->modifies->cargoSenderHouseNumber,
    $oPOSTData->modifies->cargoSenderCell,

    $isRecipientJur,

    $oPOSTData->modifies->cargoRecepientPhone,
    $oPOSTData->modifies->cargoRecepientEmail,

    $oPOSTData->modifies->cargoRecepientFirstName,
    $oPOSTData->modifies->cargoRecepientSecondName,
    $oPOSTData->modifies->cargoRecepientLastName,
    $oPOSTData->modifies->cargoRecepientDocumentTypeId,
    $oPOSTData->modifies->cargoRecepientDocumentNumber,

    $oPOSTData->modifies->cargoRecepientCompanyName,
    $oPOSTData->modifies->cargoRecepientCompanyFormId,
    $oPOSTData->modifies->cargoRecepientCompanyPhone,
    $oPOSTData->modifies->cargoRecepientCompanyEmail,
    $oPOSTData->modifies->cargoRecepientCompanyINN,
    $oPOSTData->modifies->cargoRecepientCompanyAddress,
    $oPOSTData->modifies->cargoRecepientCompanyAddressCell="",

    $oPOSTData->modifies->arrivalTerminalId,
    $oPOSTData->modifies->arrivalTerminalName,
    $oPOSTData->modifies->cargoRecepientAddress,
    $oPOSTData->modifies->cargoRecepientHouseNumber,
    $oPOSTData->modifies->cargoRecepientCell,

    $isDerivalCourier,
    $isArrivalCourier

);

if(IS_DEBUG) echo '<br>NewOrderResult<br>'.$iNewOrderResult.'<br>';

if ($iNewOrderResult > 0) {

    /** TODO: TEST CASE
     */
    $transports = array();

    // include all modules
    foreach(glob($rundir . "/CALC_*.php") as $modname)
    {
        include $modname;
    }

    $oCompanyDesc = $transports[intval($oPOSTData->modifies->cargoCompanyID)];

    $sCompanyOrderNum = 0;

    if ((isset($oCompanyDesc['canorder'])) and ($oCompanyDesc['canorder'] === true)) {
        //print_r($cUser);
        // call company order


        $sRecipientFIO = $oPOSTData->modifies->cargoRecepientLastName
            .' '.$oPOSTData->modifies->cargoRecepientFirstName.' '
            .$oPOSTData->modifies->cargoRecepientSecondName;

        if (isset($oPOSTData->modifies->cargoRecepientContactFirstName)
            &&
            isset($oPOSTData->modifies->cargoRecepientContactLastName))
        {
            $sRecipientContactFIO =
                $oPOSTData->modifies->cargoRecepientContactFirstName
                . ' ' . $oPOSTData->modifies->cargoRecepientContactLastName;
        }
        else
        {
            $sRecipientContactFIO = $sRecipientFIO;
        }

        $sSenderFIO = $oPOSTData->modifies->cargoSenderLastName . ' '
            . $oPOSTData->modifies->cargoSenderFirstName . ' '
            . $oPOSTData->modifies->cargoSenderSecondName;

        if (isset($oPOSTData->modifies->cargoSenderContactFirstName)
            &&
            isset($oPOSTData->modifies->cargoSenderContactLastName))
        {
            $sSenderContactFIO = $oPOSTData->modifies->cargoSenderContactFirstName
                . ' ' . $oPOSTData->modifies->cargoSenderContactLastName;
        }
        else
        {
            $sSenderContactFIO = $sSenderFIO;
        }

        $sSenderCompanyFormShortName = '';
        if($isSenderJur) {
            $sSenderCompanyFormShortName = GetCompanyFormShortName($mysqli,
                $oPOSTData->modifies->cargoSenderCompanyFormId);

            if ($sSenderCompanyFormShortName == '') {
                DropWithBadRequest("Bad SenderCompanyFormID");
            }
        }
        $sRecipientCompanyFormShortName = '';
        if($isRecipientJur) {
            $sRecipientCompanyFormShortName = GetCompanyFormShortName($mysqli,
                $oPOSTData->modifies->cargoRecepientCompanyFormId);

            if ($sRecipientCompanyFormShortName == '') {
                DropWithBadRequest("Bad RecipientCompanyFormID");
            }
        }

        $oPOSTData->modifies->internalNumber = $iNewOrderResult;

        $sCompClassName = $oCompanyDesc["classname"];
        $oCompanyObject =  new $sCompClassName();
        $sCompanyOrderNum = $oCompanyObject->MakeOrder(
            $oPOSTData->modifies->cargoFrom,
            $oPOSTData->modifies->cargoTo,
            $oPOSTData->modifies->cargoFromZip,
            $oPOSTData->modifies->cargoToZip,
            $oPOSTData->modifies->cargoFromRegion,
            $oPOSTData->modifies->cargoToRegion,
            $oPOSTData->modifies->cargoWeight,
            $oPOSTData->modifies->cargoVol,
            $oPOSTData->modifies->cargoGoodsPrice,
            $oPOSTData->modifies->cargoLength,
            $oPOSTData->modifies->cargoWidth,
            $oPOSTData->modifies->cargoHeight,
            $oPOSTData->modifies->cargoGoodsName,
            $oPOSTData->modifies->cargoDate,
            $oPOSTData->modifies,

            $isRecipientJur,
            $sRecipientFIO,
            $oPOSTData->modifies->cargoRecepientDocumentTypeId,
            $oPOSTData->modifies->cargoRecepientDocumentNumber,
            $oPOSTData->modifies->cargoRecepientPhone,
            $oPOSTData->modifies->cargoRecepientEmail,
            $oPOSTData->modifies->arrivalTerminalId,
            $oPOSTData->modifies->cargoRecepientCompanyName,
            $sRecipientCompanyFormShortName,
            $oPOSTData->modifies->cargoRecepientCompanyINN,
            $oPOSTData->modifies->cargoRecepientCompanyAddress,
            $oPOSTData->modifies->cargoRecepientCompanyAddressCell,
            $oPOSTData->modifies->cargoRecepientCompanyPhone,
            $oPOSTData->modifies->cargoRecepientCompanyEmail,
            $sRecipientContactFIO,
            $oPOSTData->modifies->cargoRecepientAddress,
            $oPOSTData->modifies->cargoRecepientCell,

            $isSenderJur,
            $sSenderFIO,
            $oPOSTData->modifies->cargoSenderDocumentTypeId,
            $oPOSTData->modifies->cargoSenderDocumentNumber,
            $oPOSTData->modifies->cargoSenderPhone,
            $oPOSTData->modifies->cargoSenderEmail,
            $oPOSTData->modifies->derivalTerminalId,
            $oPOSTData->modifies->cargoSenderCompanyName,
            $sSenderCompanyFormShortName,
            $oPOSTData->modifies->cargoSenderCompanyINN,
            $oPOSTData->modifies->cargoSenderCompanyAddress,
            $oPOSTData->modifies->cargoSenderCompanyAddressCell,
            $oPOSTData->modifies->cargoSenderCompanyPhone,
            $oPOSTData->modifies->cargoSenderCompanyEmail,
            $sSenderContactFIO,
            $oPOSTData->modifies->cargoSenderAddress,
            $oPOSTData->modifies->cargoSenderCell,

            $isDerivalCourier,
            $isArrivalCourier,
            $oPOSTData->modifies->cargoDesireDate,
            $oPOSTData->modifies->cargoDeliveryDate
        );


        if (is_array($sCompanyOrderNum)) {
            DropWithServerError("Errors: " . implode(',',$sCompanyOrderNum));
        }
        else if (intval($sCompanyOrderNum) <= 0) {
            DropWithServerError("Cargo Company cannot create order with this parameters.");
        } else {

            // we have an order number from company
//		print($sCompanyOrderNum);
            $oNewOrder->sCompanyInternalNumber = $sCompanyOrderNum;
            $oNewOrder->SaveOrder($mysqli);

        }
    }

    $oFinance = new Finance();
    $oFinance->NewOperation($mysqli, 0-floatval($oPOSTData->modifies->cargoPrice), $iClientID, $iNewOrderResult, "Задолженность за логистические услуги");

    $sNewStateQuery = "INSERT INTO `" . DB_STATES_TABLE . "` (`order_id`, `operation_id`, `comment`) 
					  VALUES (" . $iNewOrderResult . ", " .
        "1, \"Создание заказа\")";
    $iNewStateRes = $mysqli->query($sNewStateQuery);

    $message = '';

    /** Send mail to customer and dispatcher */

    $mailToDispatcher =  Mail::SendOrderMailToClient($mysqli,$iNewOrderResult,"RU",true);

    /** Send mail to dispatcher */

    $mailToClient =  Mail::SendOrderMailToClient($mysqli,$iNewOrderResult,"RU",false);

    if (!($mailToDispatcher === true))
        $message .='Mail to dispatcher not send. ';
    if (!($mailToClient === true))
        $message .='Mail to client not send. ';
}
$mysqli->close();

switch($iNewOrderResult)
{
    case PARCEL_NO_PARAMS:
        DropWithBadRequest("Not enough parameters");
    case PARCEL_DB_ERROR:
        DropWithServerError();
    case PARCEL_EXISTS:
        DropWithServerError("Order already exists");
    default:
    {
        //$oFinance = new Finance();
        //$oFinance->NewOperation($mysqli,0-$oPOSTData->modifies->cargoPrice, $iClientID, $iNewOrderResult, "Задолженность");
        ReturnSuccess(array("id" => $iNewOrderResult,"message"=>$message));
    }
}


function test_mail_sending()
{

    require_once "./service/SendMailSmtpClass.php";
    require_once "./service/config.php";
//require_once 'email_template/ttn_template.php';
    require_once "./service/service.php";
//require_once "./service/order_class.php";
    require_once "./service/mail_class.php";

    $iOrderID = 361;

    $host = 'localhost';

    $mysqli = new mysqlii($host, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);
    $Mail = new Mail();
    $res = $Mail->SendOrderMailToClient($mysqli, $iOrderID, "RU", true);
    echo $res;
    die;
}

test_mail_sending();
?>