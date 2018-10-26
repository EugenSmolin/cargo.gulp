<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//require_once('check_key.php');

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/user_class.php";
require_once "./service/auth.php";
require_once "./service/order_class.php";
require_once("./service/mpdf60/mpdf.php");
require_once 'email_template/ttn_template.php';
require_once "services.php";
require_once "./service/company_class.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

$orderID = 0;
$companyID = 0;

if(isset($oPOSTData))
{
    require_once('check_key.php');

    $mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

    if(IS_PRODUCTION)
    {
        $iUserID = CheckAuth();
        if ($iUserID === false)
            DropWithUnAuth();
    }
    else
    {
        $iUserID =15;
    }

    // check auth and rights
    // create User object
    $oUser = new User();

    // trying to authenticate
    $iAuth = $oUser->UserFromID($mysqli, $iUserID);



    if (isset($oPOSTData->data->orderID))
    {
        $orderID = $oPOSTData->data->orderID;
    }
    else
    {
        DropWithBadRequest("OrderID not set");
    }

    if (isset($oPOSTData->data->companyID))
    {
        $companyID = $oPOSTData->data->companyID;
    }
    else
    {
        DropWithBadRequest("CompanyID not set");
    }

    $oOrder = new Order();

    $rOrder = $oOrder->OrderFromID($mysqli,$orderID);

    if(!IS_PRODUCTION)
    {
        var_dump($mysqli);
    }

    if((!$oUser->isAdmin) && ($oOrder->iOrderClientID != $iUserID))
    {
        DropWithBadRequest("User didn't have access to order");
    }

    $keys=$orderID.'|'.$companyID;

    $documents = array();

    // Счет на оплату
    $keys=$orderID.'|'.$companyID.'|1';
    $url = API_SITE_URL.'get_documents.php?u='
        .urlencode( base64_encode($keys));

    $documents[] = array(
        'document_name'=>'Счет на оплату #'.$orderID.' от '.date("Y-m-d", $oOrder->iOrderTimestamp),
        'file_url' => $url
    );

    // Поручение экспедитору
    $keys=$orderID.'|'.$companyID.'|2';
    $url = API_SITE_URL.'get_documents.php?u='
        .urlencode( base64_encode($keys));

    $documents[] = array(
        'document_name'=>'Поручение экспедитору #'.$orderID.' от '.date("Y-m-d", $oOrder->iOrderTimestamp),
        'file_url' => $url
    );

    // Договор экспедирования
    if($companyID==32)
    {
        $keys=$orderID.'|'.$companyID.'|3';
        $url = API_SITE_URL.'get_documents.php?u='
            .urlencode( base64_encode($keys));

        $documents[] = array(
            'document_name'=>'Договор экспедирования #'.$orderID.' от '.date("Y-m-d", $oOrder->iOrderTimestamp),
            'file_url' => $url
        );
    }

    $oAnswer = array(
         'status'=>'ok',
         'documents'=>$documents
     );

    header('Content-Type: application/json');
    print(json_encode($oAnswer));
    exit(0);
}
else
{
   $encode = $_GET["u"];
   if(!isset($encode))
   {
       DropWithBadRequest("Parameters not set.");
   }
   $resultStr = base64_decode(urldecode($encode));
   $data = explode('|',$resultStr);
    if(!IS_PRODUCTION)
    {
         var_dump($data);
    }
   $orderID = $data[0];
   $companyID = $data[1];
   $documentTypeID=$data[2];

}

if(!isset($orderID) || $orderID=='')
{
    DropWithBadRequest("OrderID not set");
}
else
{
    $orderID = intval($orderID);
}

if(!isset($companyID) || $companyID == '')
{
    DropWithBadRequest("CompanyID  not set");
}
else
{
    $companyID = intval($companyID);
}

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

if ($mysqli->connect_errno)
    DropWithUnAuth();
/*
foreach(glob($rundir . "/CALC_*.php") as $modname)
{
    include $modname;
}

$oCompanyDesc = $transports[$companyID];

$sCargoLogoPath='';

$imagesPath = 'email_template/content/img/';

switch ($companyID)
{
    case 32:
        $sCargoLogoPath = $imagesPath."logo-dellin.jpg";
        break;
    case 136:
        $sCargoLogoPath =   $imagesPath."logo-intime.jpg";
        break;
    case 8:
        $sCargoLogoPath =  $imagesPath."logo_airgreenland.jpg";
        break;
    case 130:
        $sCargoLogoPath =   $imagesPath."logo_matkahuolto.jpg";
        break;
    case 96:
        $sCargoLogoPath =  $imagesPath."logo_xpologistics.jpg";
        break;
    default:
        $sCargoLogoPath = GetImagePath($oCompanyDesc['classname'], $oCompanyDesc['logo']);
        break;
}
*/

$filename ="";

switch($documentTypeID)
{
    case 1:
        // Счет на оплату
        $filename = 'cargo.guru_bill#_1_' . $orderID . '.pdf';
        $file = LoadFile($filename);
        break;
    case 2:
        // Поручение экспедитору
        $filename = "order_#".$orderID.".pdf";
        $file = LoadFile($filename);
        /*    $sCargoLogoPath ='';

            $html = Template::GetTtn($mysqli,$orderID,"RU",  $sCargoLogoPath);

            //echo $html; die;

            $mpdf=new mPDF();

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML($html);

            header('Content-Type: application/pdf');

            $filename = 'cargo.guru_order#_'.$orderID.'.pdf';

            $mpdf->Output('.pdf', 'I');

            exit(0);
            */
        break;
    case 3:
        // Договор экспедирования
        $filename = "ttn_#".$iOrderId.".pdf";
        $file = LoadFile($filename);

        break;
    default:
        DropWithBadRequest("Type of file is not exist");

}

header("Content-type:application/pdf");

// It will be called downloaded.pdf
header("Content-Disposition:attachment;filename='".$filename."'");

// The PDF source is in original.pdf
readfile(TTN_PATH.$filename);


function GetImagePath($classname, $logoPath)
{
    $imagesPath = 'email_template/content/img/';
    $name = explode('_',$classname);
    $logoName = explode('/',$logoPath);
    $file_sort_name = explode('.',$logoName[count($logoName)-1]);
    $file_name =  $imagesPath.'logo_'.strtolower($name[1]).'.'.$file_sort_name[1];
    return $file_name;
}

function  LoadFile($filename)
{
    $fp = fopen($filename,"r");
    if (!$fp)
    {
        return false;
        //print "Не удается открыть файл";
        //exit();
    }
    $file = fread($fp, filesize($filename)); // чтение файла
    fclose($fp);

    return $file;
}
?>