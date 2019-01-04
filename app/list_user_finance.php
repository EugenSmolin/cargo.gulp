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
require_once "./service/user_class.php";
require_once "./service/auth.php";
require_once "./service/finance_class.php";
require_once './service/mpdf60/mpdf.php';

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

if(IS_PRODUCTION) {
    $iUserID = CheckAuth();
    if ($iUserID === false)
        DropWithUnAuth();
}
else
{
    $iUserID = 15;
}

// check if data enough
if (!isset($oPOSTData->data->id))
		{
			DropWithBadRequest("Not enough parameters");
		}
$start_date = '';
$finish_date = '';
if(isset($oPOSTData->data->start_date))
{
    $start_date = $oPOSTData->data->start_date;
}

if(isset($oPOSTData->data->finish_date))
{
    $finish_date = $oPOSTData->data->finish_date;
}

$iOffset = (isset($oPOSTData->data->offset) ? $oPOSTData->data->offset : 0);
$iLimit = (isset($oPOSTData->data->limit) ? $oPOSTData->data->limit : 0);

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();

// create User object
$cUser = new User();

// trying to authenticate
$iAuth = $cUser->UserFromID($mysqli, $iUserID);

// if no user
if (($iAuth != USER_OK) or (!$cUser->objectOK))
	DropWithUnAuth();

// check for id and admin
if(IS_PRODUCTION)
{
    if ((!$cUser->isAdmin) && ($cUser->userID != $oPOSTData->data->id))
	    DropWithForbidden();
}

// lowcase doc format
if (isset($oPOSTData->data->doctype))
    $oPOSTData->data->doctype = strtolower($oPOSTData->data->doctype);

if(!IS_PRODUCTION) echo '<br><br>',strtotime($start_date),'<br><br>';

$oFinance = new Finance();
$aOperations = $oFinance->OperationsList($mysqli,
    $oPOSTData->data->id,
    $cUser->isAdmin,
    $iOffset,
    $iLimit,
    $start_date,
    $finish_date);

// compile out data

$aResultDataSet = array();
$sHTMLResult = "";
if(!IS_PRODUCTION) echo 'Кол-во:', count($aOperations),'<br>';
foreach($aOperations as $oOperation)
	{
		$aResultDataSet[] = array(
				"id" => $oOperation->iOperationID,
				"userID" => $oOperation->iOperationUserID,
				"orderID" => $oOperation->iOperationOrderID,
				"orderSum" => $oOperation->fOrderSum,
				"timestamp" => $oOperation->iOperationTimestamp,
				"payedSum" => $oOperation->fPayedSum,
                "payedDate" => $oOperation->dPayedDate,
                "payerName" => $oOperation->sPayerName,
                "paymentTypeName" => $oOperation->sPaymentTypeName,
                "payerTypeName" => $oOperation->sPayerTypeName,

			);
                $sHTMLResult .= "<tr>";
                $sHTMLResult .= "<td>" . $oOperation->iOperationID . "</td>";
                $sHTMLResult .= "<td>" . $oOperation->iOperationUserID . "</td>";
                $sHTMLResult .= "<td>" . $oOperation->iOperationOrderID . "</td>";
                $sHTMLResult .= "<td>" . $oOperation->fOperationValue . "</td>";
                $sHTMLResult .= "<td>" . date('d.m.Y H:i:s P',$oOperation->iOperationTimestamp) . "</td>";
                $sHTMLResult .= "<td>" . $oOperation->sOperationDesc . "</td>";
                $sHTMLResult .= "</tr>";
	}

$aTotalData = $oFinance->TotalData(
                $mysqli,
                $iUserID,
                $cUser->isAdmin,
                $start_date,
                $finish_date);

$iRecordCount = $oFinance->RecordCountFromSearch($mysqli,
    $iUserID,
    $cUser->isAdmin,
    $start_date,
    $finish_date);

if(!IS_PRODUCTION) echo 'Кол-во2 :', $iRecordCount,'<br>';

$aResultOut = array(
			"success" => "success",
			"totalCount" => $iRecordCount,
            "totalData" => $aTotalData,
			"data" => $aResultDataSet
		);

http_response_code(200);

if (!isset($oPOSTData->data->doctype) or ($oPOSTData->data->doctype == 'json'))
    {
        header('Content-Type: application/json');
        print(json_encode($aResultOut));
    }
else if (($oPOSTData->data->doctype == 'html') or ($oPOSTData->data->doctype == 'pdf'))
    {
        $sHTMLHead = "<!DOCTYPE html><html><head>";
        $sHTMLHead .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
        $sHTMLHead .= "<style type=\"text/css\">";
        $sHTMLHead .= "body { font-family: Arial, Helvetica; }";
        $sHTMLHead .= "table { border-collapse: collapse; }";
        $sHTMLHead .= "table, tr, td { padding: 5px; border: 1px solid black; }";
        $sHTMLHead .= ".thead { font-weight: 800; }";
        $sHTMLHead .= "</style></head><body><br /><br /><h2>Финансовые операции пользователя " . $cUser->userName . "</h2><br /><br /><br />";
        $sHTMLHead .= "<table border=\"1\"><tr class=\"thead\"><td>№№ операции</td><td>№ пользователя</td>";
        $sHTMLHead .= "<td>№ посылки</td>";
        $sHTMLHead .= "<td>Сумма</td>";
        $sHTMLHead .= "<td>Время</td>";
        $sHTMLHead .= "<td>Комментарий</td>";
        $sHTMLHead .= "</tr>";
        
        $sHTMLFoot = "</table><br /><br /></body></html>";
        
        if ($oPOSTData->data->doctype == 'html')
            {
                header('Content-Type: text/html');
                print($sHTMLHead . $sHTMLResult . $sHTMLFoot);
            }
        else if ($oPOSTData->data->doctype == 'pdf')
            {   
                $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                $mpdf->charset_in = 'utf-8';
                $mpdf->WriteHTML($sHTMLHead . $sHTMLResult . $sHTMLFoot);
                header('Content-Type: application/pdf');
                $mpdf->Output('output.pdf', 'I');
            }
        
    }

?>
