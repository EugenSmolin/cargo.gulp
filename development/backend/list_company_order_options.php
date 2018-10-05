<?php

/*
 * @author Anton Dovgan <blackc.blackc@gmail.com>
 * 
 * @param string	JSON in POST body with parameters
 * 
 * @return string	JSON with results
 * 
 */
require_once('check_key.php');

require_once "./service/config.php";
require_once "./service/service.php";
require_once "services.php";

///////////////////////////////

mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");
date_default_timezone_set('UTC');

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

if (!isset($oPOSTData->data->companyID))
    DropWithForbidden ();

if (intval($oPOSTData->data->companyID) <= 0)
    DropForbidden ();
////////////////////////////////

$transports = array();

// include all modules
foreach(glob($rundir . "/CALC_*.php") as $modname)
    {
/*    if($modname=="modules/CALC_dpd.php" ||
        $modname=="modules/CALC_pec.php")
    {
        //echo $modname;
    }
    else
*/	  include $modname;
    }
$company_id =intval($oPOSTData->data->companyID);
$oCompanyDesc = $transports[$company_id];
$sCompClassName = $oCompanyDesc["classname"];

$oCompanyObject = new $sCompClassName();

$aResultOut = $oCompanyObject->GetOptions();

$aResultOut['groups']['payment']=
    array(
        "name" => "Способ оплаты",
        "visibleOrder" => 13,
        "aoptions" =>
            [
               "paymentType" =>
                   GetCustomPaymentMethods($company_id),
              /* [
                    'displayName' => 'Способ оплаты',
                    'fieldName' => 'paymentType',
                    'type' => 'enum',
                    'required' => TRUE,
                    "recalcTotalPrice" => true,
                    'variants' =>

                    [
                        [
                            'number' => 1,
                            'visible' => 'Банковская карта VISA, MasterCard, МИР',
                            'description' => 'Моментальное подтверждение оплаты.'
                        ],
                        [
                            'number' => 2,
                            'visible' => 'ЯндексДеньги',
                            'description' => 'Моментальное подтверждение оплаты.'
                        ],
                        [
                            'number' => 3,
                            'visible' => 'Qiwi',
                            'description' => 'Моментальное подтверждение оплаты.'
                        ],

                        [
                            'number' => 10,
                            'visible' => 'Счет на оплату',
                            'description' => ''
                        ],
                        [
                            'number' => 11,
                            'visible' => 'Оплата при сдаче отправителя',
                            'description' => 'Скидка не предусмотрена.<br>Услуга оплачивается при отправке груза.'
                        ],
                        [
                            'number' => 12,
                            'visible' => 'Оплата при получении отправителя',
                            'description' => 'Скидка не предусмотрена.<br>Услуга оплачивается при получении груза.'
                        ],
                    ],
                ],*/
               "payerType" =>
                    array(
                        "displayName" => "Плательщик",
                        "fieldName" => "payerType",
                        "type" => "enum",
                        "required" => true,
                        "variants" =>GetPayers($company_id))
            ]);
/*
$aResultOut['groups']['payment1']= array(
    "displayName" => "Плательщик",
    "fieldName" => "payerType",
    "type" => "enum",
    "required" => true,
    "variants" =>GetPayers($company_id));
*/
http_response_code(200);		
header('Content-Type: application/json');
print(json_encode($aResultOut));

function GetCustomPaymentMethods($company_id)
{
    $payments = array();
    $oHandler = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);
    $payments = GetPaymentMethods($oHandler,1,$company_id);
    return $payments;
}

function GetPayers($company_id)
{
    $payers=array();
    $oHandler = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

    $sqlQuery="SELECT id, name FROM ".DB_PAYER_TYPE.";";

    $oRes = $oHandler->query($sqlQuery);

    if(!IS_PRODUCTION) echo $sqlQuery,'<br>';

    if($oHandler->affected_rows > 0)
    {

        while($oRow = $oRes->fetch_assoc())
        {
            $payer_id = intval($oRow['id']);
            $payments = GetPaymentMethods($oHandler,$payer_id,$company_id);
            $payer =[
                'number' => $payer_id,
                'visible' => strval($oRow['name']),
                "standard"=> true,
                "paymentType"=>$payments
            ];
            $payers[]=$payer;
        }
    }

    return $payers;
}

function GetPaymentMethods($oHandler,$payer_id,$company_id)
{

    $payments=array();
    $sqlQuery="	SELECT pp.payment_id as payment_id,pt.name as payment_type_name ,COALESCE(pd.description,'Скидка не предусмотрена.')  as description
            FROM ".DB_PAYER_PAYMENT." pp
             JOIN ".DB_PAYMENT_TYPE." pt ON pp.payment_id = pt.id
             LEFT JOIN ( SELECT ptd.payment_type_id,d.description
 					FROM  ".DB_PAYMENT_TYPE_DISCOUNT." ptd 
            LEFT JOIN ".DB_DISCOUNT." d on d.id=ptd.discount_id
				 WHERE  ptd.company_id = ".$company_id.") pd ON pd.payment_type_id=pp.payment_id
           WHERE pp.payer_id=".$payer_id."  
			LIMIT 10;
            ";

    $oRes = $oHandler->query($sqlQuery);

    if(!IS_PRODUCTION) echo $sqlQuery,'<br>';

    if($oHandler->affected_rows > 0)
    {

        while($oRow = $oRes->fetch_assoc())
        {
            $payment =[
                'number' => intval($oRow['payment_id']),
                'visible' => strval($oRow['payment_type_name']),
                'description' => strval($oRow['description'])
            ];
            $payments[]=$payment;
        }
    }

    $paymentType = [
        'displayName' => 'Способ оплаты',
        'fieldName' => 'paymentType',
        'type' => 'enum',
        'required' => TRUE,
        'recalcTotalPrice' => TRUE,
        'variants' => $payments
    ];
    return $paymentType;
}



?>
