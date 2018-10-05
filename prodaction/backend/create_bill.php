<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('check_key.php');

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/user_class.php";
require_once "./service/auth.php";
require_once "./service/order_class.php";
require_once("./service/mpdf60/mpdf.php");
require_once 'documents/order_bill.php';

$oPOSTData = json_decode(file_get_contents("php://input"));

if(IS_PRODUCTION) {
    $iUserID = CheckAuth();
    if ($iUserID === false)
        DropWithUnAuth();
}

$iOrderID = intval($oPOSTData->data->orderID);
// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

if ($mysqli->connect_errno)
    DropWithUnAuth();

foreach(glob($rundir . "/CALC_*.php") as $modname)
{
    include $modname;
}

$html = BillTemplate::GetBill($mysqli,$iOrderID,"RU");

//echo $html; die;

$mpdf=new mPDF();

$mpdf->SetDisplayMode('fullpage');

$mpdf->WriteHTML($html);
//echo $html; die;
header('Content-Type: application/pdf');

$filename = 'cargo.guru_order#_'.$iOrderID.'.pdf';

$mpdf->Output('.pdf', 'I');

exit(0);

?>