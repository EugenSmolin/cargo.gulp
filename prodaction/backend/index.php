<?php
/**
 * Created by PhpStorm.
 * User: Andrii
 * Date: 03.11.2017
 * Time: 15:14
 */
/*
require_once "./service/config.php";
require_once "./service/service.php";
require_once "services.php";
*/
/*echo "<input id='inp' type='text' onchange='calculateVol(this)'><div id='result'></div>";
*/
/*
require_once "./service/jur_form.php";
*/

//rgroup(); die;


//create_pdf(); die;


//load_imgs(); die;
//echo date_default_timezone_get();

//die;
//echo 'Qr`.12&hP\gK@]h\U*ygCP|(RCd{>}7i','<br>';

//echo urlencode('Qr`.12&hP\gK@]h\U*ygCP|(RCd{>}7i');
//die;

//show_order();
//list_order();
//test();
//mail_for_customer();

//list_company_order_options();
/*
$dr = strtotime("2018-02-08 00:00:00");//,"<br>";
echo date('d',$dr);
echo strtotime("yesterday"),"<br>";
echo (new \DateTime("today +1 day"))->getTimestamp();
echo strtotime("tomorrow +1 day"); die;
*/
//header('Content-type: image/jpeg');
/*
echo '<a>';
//header('Content-type: image/jpeg');
$path_name = "https://www.tesgroup.ru/bitrix/templates/tes/img/logo.png";

$test=explode('/',$path_name);
$file_name = $test[count($test)-1];
$data = file_get_contents($path_name);
//$file_name= str_replace('.png','.jpg',$file_name);
$upload =file_put_contents($file_name, $data);
echo "<img src='$file_name'>";
echo '</a>';
unlink($file_name);

*/
/**/
//edit_user(); die;


//echo date("Y-m-d", '1516286823'),'<br>';
//echo date("d",( new \DateTime("today +1 day"))->getTimestamp());
//die;

/*
$test = "город 13";

$isd = 0;

preg_match('/\d+/', $test, $isd);


var_dump($isd);

die;
*/
//create_order(); die;

//PEK();

//RUEX();

//flush_redis();

// test2();
//pre_order();

//echo date("Y-m-d", '1513720800');

//test_contragent();

//test2();
/*
$s="NULL";
$text = intval($s);
var_dump($text);
*/
//get_complist();
//check_contact();
/*
for($i=0;$i<200;$i++)
{
    $calc = $i*0.1;
    _calculator($calc);
}

//_calculator(3.5);
die;
*/
//CalculatorDellin();


/*
$pass[ "passport" ]="Документ недействителен по данным ГУВМ МВД России. Выберите другой документ для оформления заказа.";
$array[ "document" ]=$pass;
 $res=Arr2Str1('Test',$array);


 echo $res;
*/
//SavePdf();
// GetPdf();
//sendMail();
//GetCalculateFromAddressToAddress(); die;

//finance_module();
//tekos();

TestTranslation();

//TestDellin();

function TestDellin()
{
    require_once "./modules/CALC_dellin.php";
    require_once "./services.php";
    $oCompanyObject = new calculator_DELLIN();//new $sCompClassName();
    $oPrice =

      $oCompanyObject->Calculate2(
          'Челябинск',
          'Москва',
          1,
          0.1,
          100,
          'ru',
          'RUB',
          'RU',
          'RU',
          '',
          '',
          false,
          'Варшавская улица ул., 3',
          '',
          false,
          'Варшавское ш., 1',
          '',
          [
              "loadingType" => "1",
              "unloadingType" => "1",
              "loadMachineType" => "1",
              "unloadMachineType" => "1",
              "openMachineType" => "0",
              "openLoadType" => [ "1","2","3" ],
              "openUnloadType" => [ "1","2","3" ],
              "packageType" => [ "1", "2" ],
              "openLoadLevel" => 4,
              "openLoadTransfer" =>10,
              "openUnLoadLevel" => 4,
              "openUnLoadTransfer" =>10,
          ]
      );




}

function TestTranslation()
{
    require_once 'services.php';
    $res =__GetAllTranslations('36 киллограмм','ru');
    //var_dump($res);
}


function flush_redis()
{
    try
    {
        require_once('service/hash_class.php');
        $hcache = new HCache();

        $hcache->FlushAll();

        echo "Redis flush DB already done";
    }
    catch (Exception $ex) {
       var_dump($ex);
    }

}

function load_imgs()
{
    require_once "./service/config.php";
    $rundir = 'modules';

    foreach(glob($rundir . "/CALC_*.php") as $modname)
    {
        include $modname;
    }

    //$transports['logo'];

    foreach($transports as $item )
    {
        $name = explode('_',$item['classname']);

        $logoPath =  $item['logo'];
        $logoName = explode('/',$logoPath);
        $file_sort_name = explode('.',$logoName[count($logoName)-1]);
        $file_name =  'tmp/logo_'.strtolower($name[1]).'.'.$file_sort_name[1];

        if( strpos($logoPath,'tk-logos'))
        {
            echo  'logo_'.strtolower($name[1]),'_',$logoName[count($logoName)-1],'   ',$logoPath,'<br>';
            continue;
        }
        else
        {
             try
             {

                 copy($logoPath, $file_name);
                // $data = file_get_contents($logoPath);
               //  file_put_contents($file_name, $data);
                 //echo $file_name,'<br>';
             }
             catch (Exception $e)
             {
                 echo $file_name,'<br>',$logoPath,'<br><br>';
             }
             //echo $logoPath,'<br>';
        }
    }


}

function _calculator($vol)
{
    $w = 0;
    $h = 0;
    $l = 0;

    $mul = $vol/3.456;
    $mul_i = intval($mul);
    $rest_i =$mul_i*3.456;
    $rest = $vol - $rest_i;

    //echo 'rest_i: ',$rest_i,' rest: ', $rest, ' mul_i: ',$mul_i,' <br>';

    if($rest<=0.432)
    {
        $h = 0.6;
        $w = 0.6;
        $l = round($rest/$h/$w,2);
    }

    if($rest>0.432 && $rest<=1.728 && $mul_i==0)
    {
        $h = 1.2;
        $w = 1.2;
        $l = round( $rest/$h/$w,2);
    }

    if( $rest>1.728 && $rest<=3.456 && $mul_i>=0)
    {
        $h = 2.3;
        $w = 2.3;
        $l = round($rest/$h/$w,2);
    }

    $l+=$mul_i*0.6;

    if( $mul_i>=1)
    {
        $h = 2.3;
        $w = 2.3;
        $l = round($vol/$h/$w,2);
    }



    echo
        'Vol:'.$vol,'   Total Vol: ',($w*$h*$l),' Width: ',$w,' Heigth: ',$h,' Length: ',$l,'<br>';
}

function rgroup()
{

    require_once "./modules/CALC_rgroup.php";
    require_once "./services.php";
    $obj = new calculator_RGRUP();
    $res =$obj->Calculate(
        "Москва",
        "Санкт-Петербург",1,0.1,100,"ru","RUB",
        "RU","RU","","",null);
    echo "<pre>";
    print_r($res);
}

function PEK()
{
    require_once "./modules/CALC_pec.php";
    require_once "./services.php";
    $obj = new calculator_PECOM();
    $res =$obj->Calculate(
        "Москва",
        "Нарьян-Мар",1,0.11,100,"ru","RUB",
        "RU","RU","","",null,0.6,0.6,0.6);
    echo "<pre>";
    print_r($res);
}

function RUEX()
{
    require_once "./modules/CALC_rosukrexpress.php";
    require_once "./services.php";
    $obj = new calculator_RUEX();
    $res =$obj->Calculate(
        "Киев",
        "Москва",1,0.11,100,"ru","RUB",
        "UA","RU","","",1,0.6,0.6,0.6);
    echo "<pre>";
    print_r($res);
}

function tekos()
{
    require_once "./modules/CALC_tekos.php";
    require_once "./services.php";
    $obj = new calculator_TEKOS();
    $res =$obj->Calculate(
        "Москва",
        "Санкт-Петербург",1,0.1,100,"ru","RUB",
        "RU","RU","","",null);
    echo "<pre>";
        print_r($res);
}

function finance_module()
{
    echo strtotime("2018-02-01 17:33:19"),"<br>";
     $ret=urlencode(base64_encode('{"o":1042,"v":1}'));
     echo $ret,'<br>';
     $ret=base64_decode(urldecode($ret));
     echo $ret,'<br>';
    $ret=urlencode(base64_encode('1042_1'));
    echo $ret,'<br>';
    $ret=base64_decode(urldecode($ret));
    echo $ret,'<br>';
     die;
    include_once "service/alfabank_adapter.php";

    $orderId = (int)'888883';
    $amount = '10000';
    $clientEmail = 'andrii.sokoliuk@gmail.com';
    $from = 'Москва';
    $to = 'Санкт-Петербург';
    $expirationDate = strtotime("now +1 day");
    $now = strtotime("now");
    $comment = 'Отправка груза из ' . $from . ' в ' . $to;

    if (IS_PRODUCTION) {
        $host = DB_HOST;
    } else {
        $host = 'localhost';
    }

    $mysqli = new mysqlii($host, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

    $oPayment = new Payment();

    $alfa = new Alfabank_adapter();

    $bResult = $oPayment->Info($mysqli, $orderId);

    if ($oPayment->isStatusOK) {

        if ($now > $oPayment->dExpirationDate && $oPayment->isPayed == 'new') {
            // order opened but experied need to reject order
            $oPayment->Reject($mysqli, $orderId);
        }

        if ($now < $oPayment->dExpirationDate && $oPayment->isPayed == 'new') {
            if(!isset($oPayment->sFormUrl) || $oPayment->sFormUrl=='')
            {
                echo 'Error: url not exist.';

            }
            header('Location: ' . $oPayment->sFormUrl);
            exit;
        }
    }

    if ($oPayment->isExist) {
        echo 'Error: ' . $bResult;
        exit;
    }

    $result = $alfa->
        CreateOrder($orderId,
            $amount,
            $clientEmail,
            $expirationDate,
            $comment);

    if ($result) {

        $iRes = $oPayment->Create($mysqli,
                                  $orderId,
                                  $alfa->iOrderID,
                                  $alfa->sFormUrl,
                                  $expirationDate);


        header('Location: ' . $alfa->sFormUrl);
        exit;
    } else {
        echo 'Error: ' . $alfa->sError;
    }


    //$handler->DeleteOrder("ef9282a3-0834-76d3-ef92-82a300006876","ru");

}


function GetCalculateFromAddressToAddress()
{
    require_once "./modules/CALC_sdek.php";
    //require_once "./modules/CALC_dellin.php";
    require_once "./services.php";
//    $oCompanyObject = new calculator_DELLIN();//new $sCompClassName();
    $oCompanyObject = new calculator_SDEK();
    $oPrice =
        $oCompanyObject->Calculate(
            'Москва',
            'Ростов',
            10,
            0.1,
            100,
            'ru',
            'RUB',
            'RU',
            'RU',
            '',
            '',
            true,
            0.28,
            0.6,
            0.6,
            null
        );

      /*  $oCompanyObject->Calculate2(
            'Челябинск',
            'Москва',
            1,
            0.1,
            true,
            0.278,
            0.6,
            0.6,
            100,
            'ru',
            'RUB',
            'RU',
            'RU',
            '',
            '',
            false,
            'Варшавская улица ул., 3',
            '',
            false,
            'Варшавское ш., 1',
            '',

            array()
        );
      */
        //var_dump($oPrice); die;

    //echo '<pre>'; print_r($oPrice);
    echo '<br>Order price:',$oPrice['methods'][0]['calcResultPrice'];
    die;
    $oPrice =
        $oCompanyObject->Calculate(
            'Москва',
            'Санкт-Петербург',
            10,
            1,
            100,
            'ru',
            'RUB',
            'RU',
            'RU',
            '',
            '',
            null
        );

    //echo '<pre>'; print_r($oPrice);
    echo '<br>Order price:',$oPrice['methods'][0]['calcResultPrice'];
//die;

//    $oPrice =
//        $oCompanyObject->Calculate2(
//            'г. Москва',
//            'г. Санкт-Петербург',
//            10,
//            1,
//            100,
//            'ru',
//            'RUB',
//            'RU',
//            'RU',
//            'г. Москва',
//            'г. Санкт-Петербург',
//            true,
//            'Варшавская улица ул., 3',
//            false,
//            'Варшавское ш., 1',
//            null
//        );

    //echo '<pre>'; print_r($oPrice);
    echo '<br>Order price:',$oPrice['methods'][0]['calcResultPrice'];
   // die;
//    $oPrice =
//        $oCompanyObject->Calculate2(
//            'г. Москва',
//            'г. Санкт-Петербург',
//            10,
//            1,
//            100,
//            'ru',
//            'RUB',
//            'RU',
//            'RU',
//            'г. Москва',
//            'г. Санкт-Петербург',
//            false,
//            'Варшавская улица ул., 3',
//            true,
//            'Варшавское ш., 1',
//            null
//        );

    echo '<br>Order price:',$oPrice['methods'][0]['calcResultPrice'];
    //echo '<br>'; print_r($oPrice);


//    $oPrice =
//        $oCompanyObject->Calculate2(
//            'г. Москва',
//            'г. Санкт-Петербург',
//            10,
//            1,
//            100,
//            'ru',
//            'RUB',
//            'RU',
//            'RU',
//            'г. Москва',
//            'г. Санкт-Петербург',
//            true,
//            'Варшавская улица ул., 3',
//            true,
//            'Варшавское ш., 1',
//            array()
//        );

    //echo '<br>'; print_r($oPrice);

    echo '<br>Order price:',$oPrice['methods'][0]['calcResultPrice'];
    die();

}

 function sendMail()
 {
     require_once "./service/config.php";
     require_once "./service/service.php";
     require_once "./service/mail_class.php";
     require_once "./modules/CALC_dellin.php";
     /*require_once "services.php";
     require_once "./service/user_class.php";
     require_once "./service/order_class.php";
*/
     $mysqli = new mysqlii('localhost',DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

  //   $oCompanyObject = new calculator_DELLIN();
     $requestId = 9897552 ;
     $orderId=187;
   SavePdf($requestId,$orderId);

      //  $mail=new Mail();
    // $mail->SendOrderMailToClient($mysqli,'187',"RU",'');
 }

 function SavePdf($requestId,$orderId)
{


    $loginResult = Login();

    if (is_array($loginResult))
        return $loginResult;

    $sSessionID = $loginResult;

    $sAddOptsURL = "https://api.dellin.ru/v1/customers/request/pdf.json";
    $aAddOpts = array("appKey" => '449A2F0C-9EA6-11E5-A3FB-00505683A6D3',
        "sessionID" => $sSessionID,
        "requestID" => "$requestId"
    );

    $mysqlHandle = new mysqlii('localhost', DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);
    var_dump($mysqlHandle);
    $oAddOptsReq = curl_init($sAddOptsURL);
    curl_setopt_array($oAddOptsReq, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => json_encode($aAddOpts)
    ));
    $sAddOptsJSON = curl_exec($oAddOptsReq);

    $file_name = 'ttn_#'.$orderId .'.pdf';
    $aPdf = json_decode($sAddOptsJSON);
    $data = base64_decode($aPdf->base64);
    $file = TTN_PATH .$file_name;
    echo '<br>',$file;

    $insertSql = "INSERT INTO ".DB_DOCUMENTS_TABLE."
					( companyId, timestamp, name, content)
					VALUES 
					( 32, NOW(), '".$file_name."', '".$aPdf->base64."')";

    if(IS_DEBUG)
        echo '<br>',$insertSql,'<br>';

    $res = $mysqlHandle->query($insertSql);

    $success = file_put_contents($file, $data);
    return $success;
}

 function Login()
{
    $sLogin         = 'cargoguru2015@gmail.com';
     $sPassword      = 'Ghfdj12345';
    $sLoginURL = "https://api.dellin.ru/v1/customers/login.json";
    $aLoginOpts = array(
        "appKey" => '449A2F0C-9EA6-11E5-A3FB-00505683A6D3',
        "login" => $sLogin,
        "password" => $sPassword
    );

    $oLoginAnswer = CallPOSTJSON($sLoginURL, $aLoginOpts);

    // if login fails
    if (isset($oLoginAnswer->errors))
        $errors[] = $oLoginAnswer->errors;

    if (count($errors) > 0) {
        return $errors;
    }

    return $oLoginAnswer->sessionID;
}


function GetPdf()
{
    $sAddOptsURL = "https://api.dellin.ru/v1/customers/request/pdf.json";
    $aAddOpts = array("appKey" => '449A2F0C-9EA6-11E5-A3FB-00505683A6D3',
        "sessionID"=> "31E75857-93F0-4EF6-AB3D-FF55CF5B61F0",
        "requestID"=> "9874515");

    $oAddOptsReq = curl_init($sAddOptsURL);
    curl_setopt_array($oAddOptsReq, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => json_encode($aAddOpts)
    ));
    $sAddOptsJSON = curl_exec($oAddOptsReq);

    var_dump($sAddOptsJSON);
    $aPdf = json_decode($sAddOptsJSON);


    define('UPLOAD_DIR', 'tmp/');
   $img = $aPdf->base64;
   /*  $img = str_replace('application/pdf;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
  */

    $data = base64_decode($img);
    $file = UPLOAD_DIR . uniqid() . '.pdf';
    $success = file_put_contents($file, $data);
    print $success ? $file : 'Unable to save the file.';
}


function Arr2Str1($strpre,$array) {
    $sTmp = '';

    if(count($array)>0)
        foreach($array as $key => $element) {
            // echo $key.': '.$element.'<br>';
            /* if(is_array($element) or is_object($element))
             { Arr2Str('',$element);}*/
            $sTmp .= $key . ': ';
            if (is_array($element) or is_object($element)) {
                $sTmp = Arr2Str1('',$element);
            }
            else {
                if ($element != '') {
                    $sTmp .= $element . ';<br> ';
                }
            }
        }

    if ($sTmp != '')
        $sTmp = $strpre . $sTmp;

    return $sTmp;
}

function CalculatorDellin()
{
    require_once('services.php');
    require_once "./modules/CALC_dellin.php";
    $from="Краснодар";
    $to="Екатеринбург";
    $weight=200;
    $vol = 0.5;
    $insPrice =100;
    $clientLang= "RU";
    $clientCurr="RUB";
    $cargoCountryFrom="RU";
    $cargoCountryTo="RU";
    $cargoStateFrom="";
    $cargoStateTo="";

    $oCompanyObject = new calculator_DELLIN();//new $sCompClassName();
 /*   $sCompanyOrderNum = $oCompanyObject->Calculate($from,$to,$weight,$vol,$insPrice,$clientLang,$clientCurr,
        $cargoCountryFrom,$cargoCountryTo,
        $cargoStateFrom,$cargoStateTo, $aOptions = []);
*/
   // var_dump($sCompanyOrderNum); die;
}




function get_complist()
{
    $_stat_action = "get_complist";

    require_once('services.php');
    require_once('modules/stat_mod.php');

    header('Content-Type: application/json');

////////////////////////////////////
//
// Transports array
    $transports = array();
//
////////////////////////////////////

//$cityFrom = $_POST['cargoFrom'];
//$cityTo = $_POST['cargoTo'];
//$weight = $_POST['cW'];
//$volume = $_POST['cV'];
//$insurancePrice = 5000;
//$kindOfCargo = 0;

// client language
    $client_lang = isset($_POST["lang"]) ? $_SERVER["lang"] : "";

//trigger_error($client_lang);
    /*

   // include all modules
       foreach(glob($rundir . "/CALC_*.php") as $modname)
       {

           include $modname;
       }

       $jsonret = "";

       $aCompRet = array();
       $aCanOrder = array();

   // ret
       foreach($transports as $transportNumber => $transport)
       {
           //printf(PHP_EOL . "calculating transport [%04d] %s" . PHP_EOL, $transportNumber, $transport['name']);
   //	$transport['calcfunc']($cityFrom,$cityTo,$weight,$volume,$insurancePrice,$kindOfCargo);
           $jsonret .= ($jsonret == "" ? "" : ",");
           $retval = array('transportNumber' => $transportNumber,
               'transportName' => $transport['name'],
               'transportNames' => __GetAllTranslations($transport['name'], $transport['language'], false, true),
               'transportSite' => $transport['site'],
               'transportLogo' => (isset($transport['logo']) ? $transport['logo'] : '' ),
               'canOrderNow' => (isset($transport['canorder']) ? true : false ),
               'transportLang' => strtolower($transport['language']));

           $aCanOrder[] = (isset($transport['canorder']) ? 0 : 1 );
           $aCompRet[] = $retval;
       }

       array_multisort($aCanOrder, $aCompRet);

       $aGlobalRet = array(
           'companies' => $aCompRet,
           'currenciesList' => $activeCurrencies
       );

       print(json_encode($aGlobalRet));
   */
}

function check_contact()
{
    $sSessionID = "CEBA11E9-C6FA-4F6F-BD93-4F13EB5D9E59";
    $iCounteragentID = 5865905;
    $sCityName = "Москва";
    $sAddress = "ул. Лобненская";
    $sAddressHouse = "18";
    $isTerminal=false;
    $iTerminalId=0;


    $res = CheckAddress($sSessionID,
        $iCounteragentID,
        $sCityName,
        $sAddress,
        $sAddressHouse,
        $isTerminal,
        $iTerminalId);

    var_dump($res);

}

function test2()
{

    $city = 'г. Москва';

    echo urlencode($city);
    die;

    $_name = trim(mb_ereg_replace('(\s+г|\s+г\.|г\.\s+|\.|\s+пгт|пгт\s+|\s+п|п\s+|\s+рп|рп\s+)','',$city));
    $_name = trim(mb_ereg_replace('(\s+с|\s+с\.|\s+д|\s+ст-ца)','',$_name));
    $_name = mb_ereg_replace('\(.+\)','',$_name);

    echo  $_name.'<br>';

die();

    $city = 'Москва';

    $street = 'лен';
    echo urlencode($street);

     urlencode($city);



    $geocodeURL = 'https://www.dellin.ru/api/cities/7700000000000000000000000/streets/search.json?q=' . urlencode($street);

    $sTmp = file_get_contents($geocodeURL);
    var_dump($sTmp);
    //$sTmp = json_decode(file_get_contents($geocodeURL));



    /*
    $sAddr='Новая Москва, 692844';
    GetCityKLADRCode3($sAddr, 'AIzaSyB79Ic3jTDxYacK477EAkTgk7xkO--hNu8');
*/
    /*
      $res =  GetCityKLADRCode2('Новая Москва', 'AIzaSyB79Ic3jTDxYacK477EAkTgk7xkO--hNu8');

      echo '<br><br>';
      var_dump($res);

     $res =  GetStreetKLADRCode2('Новая Москва, Белый переулок', 'AIzaSyB79Ic3jTDxYacK477EAkTgk7xkO--hNu8');

     var_dump($res);
  */
}

function GetCityKLADRCode3($sAddr, $sAPIKEY)
{
    mb_regex_encoding("UTF-8");
    mb_internal_encoding("UTF-8");
    date_default_timezone_set('UTC');

    if (trim($sAddr) == "")
        return "";

    /*
    $geocodeURL = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?'.
        'location=-33.8670522,151.1957362&radius=500'.
        '&type=restaurant&keyword=cruise&key='. $sAPIKEY;
    */
    $geocodeURL = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($sAddr) . '&language=ru&key=' . $sAPIKEY;
    echo  $geocodeURL; die;
    $sTmp = json_decode(file_get_contents($geocodeURL));
    var_dump($sTmp);
}

function GetCityKLADRCode2($sAddr, $sAPIKEY)
{
    mb_regex_encoding("UTF-8");
    mb_internal_encoding("UTF-8");
    date_default_timezone_set('UTC');

    if (trim($sAddr) == "")
        return "";

    $geocodeURL = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($sAddr) . '&language=ru&key=' . $sAPIKEY;

    $sTmp = json_decode(file_get_contents($geocodeURL));

    if ($sTmp->status != 'OK')
        return false;

    $oRetVal = array();

    foreach($sTmp->results[0]->address_components as $component)
    {
        if ((in_array('political',$component->types)) and (in_array('locality',$component->types)))
            $oRetVal['city'] = $component->long_name;

        if (in_array('route',$component->types))
            $oRetVal['street'] = $component->long_name;

        if (in_array('street_number',$component->types))
            $oRetVal['house'] = $component->long_name;
    }

    //connect to DB
    $host='localhost';

    //$mysqlHandle = new mysqlii(LANG_DB_HOST, LANG_DB_LOGIN, LANG_DB_PASSWORD, KLADR_DB_NAME);
    $mysqlHandle = new mysqlii($host,DB_RW_LOGIN, DB_RW_PASSWORD, KLADR_DB_NAME);

    $mysqlHandle->query("SET NAMES utf8 COLLATE utf8_unicode_ci");
    $mysqlHandle->set_charset("utf8");

//	print($mysqlHandle->conect_errno);
    if (!$mysqlHandle)
        return false;

    if (!empty($oRetVal['city']))
    {
        //echo 'test',$mysqlHandle->real_escape_string(mb_strtoupper($oRetVal['city'])),'test'; die;
        $requestText = "SELECT * FROM `dellin_kladr` WHERE UPPER(`search`) = \"" .
            $mysqlHandle->real_escape_string(mb_strtoupper($oRetVal['city'])) . "\" " .
            "ORDER BY (char_length(code) - char_length(replace(code,'0',''))) DESC";

//		$requestText = "SELECT * FROM `dellin_kladr` where `name` like '%москв%'";
        $resultAddr =
            $mysqlHandle->query($requestText);

        //echo print_r($requestText);
//			print($resultAddr->num_rows . '=' . LANG_DB_LOGIN . ' = ' . LANG_DB_PASSWORD . '=' . $requestText . PHP_EOL);
//		die();
        if ($resultAddr->num_rows > 0)
        {
            $col = $resultAddr->fetch_assoc();
            $oRetVal['city_kladr'] = $col['code'];
            $oRetVal['cityID'] = $col['cityID'];
        }
    }

    $mysqlHandle->close();

//	print_r($oRetVal);

    return $oRetVal;
}

function GetStreetKLADRCode21($streetName, $cityID, $sAPIKEY)
{
    mb_regex_encoding("UTF-8");
    mb_internal_encoding("UTF-8");
    date_default_timezone_set('UTC');

    if (trim($streetName) == "")
        return "";

    $geocodeURL = 'https://maps.googleapis.com/maps/api/geocode/json?address='
        . urlencode($streetName)
        . '&language=ru&key=' . $sAPIKEY;
    $sTmp = json_decode(file_get_contents($geocodeURL));

    if ($sTmp->status != 'OK')
        return false;

    $oRetVal = array();

    foreach($sTmp->results[0]->address_components as $component)
    {
        if ((in_array('political',$component->types)) and (in_array('locality',$component->types)))
            $oRetVal['city'] = $component->long_name;

        if (in_array('route',$component->types))
            $oRetVal['street'] = $component->long_name;

        if (in_array('street_number',$component->types))
            $oRetVal['house'] = $component->long_name;
    }
    var_dump($oRetVal); echo '<br>';
    //connect to DB
    $host='localhost';

    //$mysqlHandle = new mysqlii(LANG_DB_HOST, LANG_DB_LOGIN, LANG_DB_PASSWORD, KLADR_DB_NAME);
    $mysqlHandle = new mysqlii($host,DB_RW_LOGIN, DB_RW_PASSWORD, KLADR_DB_NAME);

    $mysqlHandle->query("SET NAMES utf8 COLLATE utf8_unicode_ci");
    $mysqlHandle->set_charset("utf8");

    if (!$mysqlHandle)
        return false;

    if (!empty($oRetVal['street']))
    {
        $streetNames= explode(' ',$oRetVal['street']);
        $streetNames[count($streetNames)-1]='';
        $street = implode($streetNames);

        $requestText = "SELECT * FROM `dellin_kladr_street` WHERE `cityID` = $cityID AND UPPER(`search`) LIKE \"" .
            $mysqlHandle->real_escape_string(mb_strtoupper($street)) . "%\" " .
            "ORDER BY (char_length(code) - char_length(replace(code,'0',''))) DESC";

        $resultAddr = $mysqlHandle->query($requestText);

        echo '<br><br>',$requestText,'<br><br>';

        if ($resultAddr->num_rows > 0)
        {
            $col = $resultAddr->fetch_assoc();
            $oRetVal['street_kladr'] = $col['code'];
        }
    }

    $mysqlHandle->close();

    return $oRetVal;
}

function test_contragent()
{

    $sSessionID =  "CEBA11E9-C6FA-4F6F-BD93-4F13EB5D9E59";
    /*
       $userFIO = 'Kalopso Karto5';
       $documentType = "foreignPassport";
       $senderDocumentNumber = '1234567890';

       $companyName = "Рога и Копыта(Тест6)";
       $companyINN = "12345";
       $formName = "ОБРУГ";
       $from = 'г. Москва';
       $senderAddress = "Зарайская";
       $senderAddressHouse = "1";
       $senderAddressCell = "1";
   */
    /*   $senderCompanyFormId,
       $senderCompanyINN,
       $senderCompanyPhone,
       $senderCompanyEmail,
   */



    // $reqResult = CreatePrivateCounteragent($sSessionID, $userFIO, $documentType,$senderDocumentNumber);
    //   $reqResult =  CreateJuridicalCounteragent($sSessionID, $userFIO, $documentType,$senderDocumentNumber);
    //   var_dump($reqResult);

    /*
        $reqResult= CreateJuridicalCounteragent($sSessionID,
                        $companyName,
                        $companyINN,
                        $formName,
                        "0x8f51001438c4d49511dbd774581edb7a",
                        $from,
                        $senderAddress,
                        $senderAddressHouse,
                        $senderAddressCell);

        var_dump($reqResult);


        $companyINN = "12345";
        $reqResult = CheckJurCounteragent($sSessionID,$companyINN);

        var_dump($reqResult);

        echo '<br><br>';
    */
    /*
        $reqResult = CheckJurCounteragent($sSessionID,"1234");

        var_dump($reqResult);
      */
    // $reqResult= CheckCounteragent($sSessionID,"asdasd","juridical");
    $host = 'localhost';
    $mysqli = new mysqlii($host,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);


    $senderCompanyFormId = 1;
    $cJurForm = new JurForm();
    $resForm= $cJurForm->GetJurForm($mysqli,$senderCompanyFormId);
    var_dump($resForm);
    echo '<br>';
    var_dump($cJurForm);

}

function CheckAddress($sSessionID,
                      $iCounteragentID,
                      $sCityName,
                      $sAddress,
                      $sAddressHouse,
                      $isTerminal,
                      $iTerminalId)
{
    $sAddressType = "delivery";
    $result = array();
    $iAddressID = "";
    $isExist = false;
    $error ='';

    $sAddressURL = "https://api.dellin.ru/v1/customers/book/addresses.json";
    $aAddressOpts = array(
        "appKey" => "449A2F0C-9EA6-11E5-A3FB-00505683A6D3",

        "sessionID" => $sSessionID,
        "counteragentID"=>$iCounteragentID
    );

    $oAddresses = CallPOSTJSON($sAddressURL, $aAddressOpts);

    if (isset($oAddresses->errors))
        $error = $oAddresses->errors;

    foreach($oAddresses as $oAddress)
    {
        $address = $oAddress->address;

        if($address->city_name==$sCityName
            && $address->address==$sAddress
            && $address->house==$sAddressHouse
            && $address->terminal_id==$iTerminalId)
        {
            $iAddressID = intval($address->id);
            $isExist = true;
            break;
        }
    }

    if ($isExist)
    {
        $reqResult["addressID"]=$iAddressID;
        $reqResult["status"]='ok';
        $reqResult["error"]='';
    }
    else
    {
        $reqResult["addressID"]= 0;
        $reqResult["status"]='bad';
        $reqResult["error"]='';
    }

    if ($error!='')
    {
        $reqResult["addressID"]= 0;
        $reqResult["status"]='error';
        $reqResult["error"]=$error;
    }

    return $reqResult;

}

function CreateAddress(
    $sSessionID,
    $iCounteragentID,
    $sCityName,
    $sStreetName,
    $sHouseNumber)
{
    $fullAddress = $sCityName.', '.$sStreetName.', '.$sHouseNumber;
    $aKLADR = GetCityKLADRCode($fullAddress,GAPI_KEY);

    /** TODO: Need to add street validation */

    $bld2=array();
    preg_match_all('/\d+\D+(\d+)/',$aKLADR["house"],$bld2);

    if ($aKLADR["city_kladr"] == '') {
        $errors[] = 'Не могу найти код КЛАДР для адреса отправителя.';
    }

    if ($aKLADR["street"] == '') {
        $errors[] = 'Не задана улица для адреса отправителя.';
    }

    // Add sender address
    $sAddAddrURL = "https://api.dellin.ru/v1/customers/book/addresses/update.json";
    $aAddAddrOpts = array(
        //"appKey" => $this::AppKey,
        "appKey" => "449A2F0C-9EA6-11E5-A3FB-00505683A6D3",
        "sessionID" => $sSessionID,
        "counteragentID" => $iCounteragentID,
        "customStreet" => array(
            "code" => $aKLADR["city_kladr"],
            "street" => $aKLADR["street"],
        ),
        "house" => intval($aKLADR["house"]) . '',
        "building" => $bld2[1][0]
    );

    $oAddAddrAnswer = $this->CallPOSTJSON($sAddAddrURL, $aAddAddrOpts);

    $errTmp = Arr2Str('Адрес отправителя - ',$oAddAddrAnswer->errors);

    if (isset($oAddAddrAnswer->success))
    {
        $reqResult["addressID"]=intval($oAddAddrAnswer->success->addressID);
        $reqResult["status"]='ok';
        $reqResult["error"]='';
    }
    else
    {
        $reqResult["counteragentId"]= 0;
        $reqResult["status"]='error';
        $reqResult["error"]=$errTmp;
    }
    return $reqResult;
}

function UpdateTerminalOfContragent(
    $sSessionID,
    $iCounteragentID,
    $iTerminalID)
{

    // Add sender address
    $sAddAddrURL = "https://api.dellin.ru/v1/customers/book/addresses/update.json";
    $aAddAddrOpts = array(
        //"appKey" => $this::AppKey,
        "appKey" => "449A2F0C-9EA6-11E5-A3FB-00505683A6D3",
        "sessionID" => $sSessionID,
        "counteragentID" => $iCounteragentID,
        "terminal_id"=>$iTerminalID
    );

    $oAddAddrAnswer = $this->CallPOSTJSON($sAddAddrURL, $aAddAddrOpts);

    $errTmp = Arr2Str('Адрес - ',$oAddAddrAnswer->errors);

    if (isset($oAddAddrAnswer->success))
    {
        $reqResult["addressID"]=intval($oAddAddrAnswer->success->addressID);
        $reqResult["status"]='ok';
        $reqResult["error"]='';
    }
    else
    {
        $reqResult["counteragentId"]= 0;
        $reqResult["status"]='error';
        $reqResult["error"]=$errTmp;
    }
    return $reqResult;
}

function UpdatePhoneOfContragent(
    $sSessionID,
    $sAddressID,
    $sPhoneNumber)
{

    // Add sender address
    $sAddAddrURL = "https://api.dellin.ru/v1/customers/book/addresses/update.json";
    $aAddAddrOpts = array(
        //"appKey" => $this::AppKey,
        "appKey" => "449A2F0C-9EA6-11E5-A3FB-00505683A6D3",
        "sessionID" => $sSessionID,
        "addressID" => strval($sAddressID),
        "phoneNumber"=>strval($sPhoneNumber)
    );

    $oAddAddrAnswer = $this->CallPOSTJSON($sAddAddrURL, $aAddAddrOpts);

    $errTmp = Arr2Str('Адрес - ',$oAddAddrAnswer->errors);

    if (isset($oAddAddrAnswer->success))
    {
        $reqResult["addressID"]=intval($oAddAddrAnswer->success->addressID);
        $reqResult["status"]='ok';
        $reqResult["error"]='';
    }
    else
    {
        $reqResult["counteragentId"]= 0;
        $reqResult["status"]='error';
        $reqResult["error"]=$errTmp;
    }
    return $reqResult;
}

function UpdateContactPerson(
    $sSessionID,
    $sAddressID,
    $sContactFIO)
{

    // Add sender address
    $sAddAddrURL = "https://api.dellin.ru/v1/customers/book/contacts/update.json";
    $aAddAddrOpts = array(
        //"appKey" => $this::AppKey,
        "appKey" => "449A2F0C-9EA6-11E5-A3FB-00505683A6D3",
        "sessionID" => $sSessionID,
        "addressID" => strval($sAddressID),
        "contact"=>$sContactFIO
    );

    $oAddAddrAnswer = $this->CallPOSTJSON($sAddAddrURL, $aAddAddrOpts);

    $errTmp = Arr2Str('Адрес - ',$oAddAddrAnswer->errors);

    if (isset($oAddAddrAnswer->success))
    {
        $reqResult["personID"]=intval($oAddAddrAnswer->success->personID);
        $reqResult["status"]='ok';
        $reqResult["error"]='';
    }
    else
    {
        $reqResult["personID"]= 0;
        $reqResult["status"]='error';
        $reqResult["error"]=$errTmp;
    }
    return $reqResult;
}

function CheckJurCounteragent($sSessionID,$companyINN)
{
    $type= "juridical";
    $result = array();
    $iCounteragentId = "";
    $isExist = false;
    $error ='';

    $sAddPhysicalAddrURL = "https://api.dellin.ru/v1/customers/book/counteragents.json";
    $aAddPhysicalAddrOpts = array(
        "appKey" => "449A2F0C-9EA6-11E5-A3FB-00505683A6D3",
        "sessionID" => $sSessionID,
        "WithAnonym"=> "true"
    );

    $oAgents = CallPOSTJSON($sAddPhysicalAddrURL, $aAddPhysicalAddrOpts);

    if (isset($oAgents->errors))
        $error = $oAgents->errors;

    foreach($oAgents as $oAgent)
    {
        $agent = $oAgent->counteragent;
        if($agent->type==$type)
        {
            if($agent->inn==$companyINN)
            {
                $iCounteragentId = $agent->id;
                var_dump($agent);
                $isExist = true;
                break;
            }
        }
    }

    if ($isExist)
    {
        $reqResult["counteragentId"]=intval($iCounteragentId);
        $reqResult["status"]='ok';
        $reqResult["error"]='';
    }
    else
    {
        $reqResult["counteragentId"]= 0;
        $reqResult["status"]='error';
        $reqResult["error"]=$error;
    }
    return $reqResult;
}

function CheckPrivateCounteragent($sSessionID,$userName)
{
    $type = "private";
    $result = array();
    $iCounteragentId = "";
    $isExist = false;
    $error ='';

    $sAddPhysicalAddrURL = "https://api.dellin.ru/v1/customers/book/counteragents.json";
    $aAddPhysicalAddrOpts = array(
        "appKey" => "449A2F0C-9EA6-11E5-A3FB-00505683A6D3",
        "sessionID" => $sSessionID,
        "WithAnonym"=> "true"
    );

    /*  $oAddOptsReq = curl_init($sAddPhysicalAddrURL);
      curl_setopt_array($oAddOptsReq, array(
          CURLOPT_POST => TRUE,
          CURLOPT_RETURNTRANSFER => TRUE,
          CURLOPT_HTTPHEADER => array(
              'Content-Type: application/json'
          ),
          CURLOPT_POSTFIELDS => json_encode($aAddPhysicalAddrOpts)
      ));
  */

    $oAgents = CallPOSTJSON($sAddPhysicalAddrURL, $aAddPhysicalAddrOpts);

    if (isset($oAgents->errors))
        $error = $oAgents->errors;

    foreach($oAgents as $oAgent)
    {
        $agent = $oAgent->counteragent;
        if($agent->type==$type)
        {
            if($agent->name==$userName)
            {
                $iCounteragentId = $agent->id;
                echo $iCounteragentId;
                $isExist = true;
                break;
            }
        }
    }

    if ($isExist)
    {
        $reqResult["counteragentId"]=$iCounteragentId;
        $reqResult["status"]='ok';
        $reqResult["error"]='';
    }
    else
    {
        $reqResult["counteragentId"]= 0;
        $reqResult["status"]='error';
        $reqResult["error"]=$error;
    }
    return $reqResult;
}

function CreatePrivateCounteragent($sSessionID, $userFIO, $documentType,$documentNumber)
{
    $reqResult = array();
    $sAddPhysicalAddrURL = "https://api.dellin.ru/v1/customers/book/counteragents/update.json";
    $aAddPhysicalAddrOpts = array(
        "appKey" => "449A2F0C-9EA6-11E5-A3FB-00505683A6D3",
        // "appKey" => $this::AppKey,
        "sessionID" => $sSessionID,
        "form" => "0xAB91FEEA04F6D4AD48DF42161B6C2E7A",
        "document" => array(
            "type" => $documentType,
            "number" => substr($documentNumber,4),
            "serial" => substr($documentNumber,0,4),
        ),
        "name" => $userFIO
    );

    $oAddPhysicalAddrAnswer = CallPOSTJSON($sAddPhysicalAddrURL, $aAddPhysicalAddrOpts);

    $errTmp = Arr2Str('Документ отправителя - ', $oAddPhysicalAddrAnswer->errors);
    if ($errTmp)
        $errors[] = $errTmp;

    //print_r($aAddPhysicalAddrOpts); die();

    if (!isset($oAddPhysicalAddrAnswer->success)) {
        $reqResult["counteragentId"]= 0;
        $reqResult["status"]='error';
        $reqResult["error"]=implode($errors);
    }
    else
    {
        $reqResult["counteragentId"] = $oAddPhysicalAddrAnswer->success->counteragentID;
        $reqResult["status"]='ok';
        $reqResult["error"]='';
    }

    return $reqResult;
}

function CreateJuridicalCounteragent($sSessionID,
                                     $companyName,
                                     $companyINN,
                                     $formName,
                                     $countryUID,
                                     $cityName,
                                     $address,
                                     $addressHouse,
                                     $addressCell
)
{

    $prevStreet = $cityName.', '.$address;
    echo $prevStreet,'<br>';
    $city = GetCityKLADRCode2($prevStreet,'AIzaSyB79Ic3jTDxYacK477EAkTgk7xkO--hNu8');
    $streetInfo = GetStreetKLADRCode2($prevStreet,$city["cityID"],'AIzaSyB79Ic3jTDxYacK477EAkTgk7xkO--hNu8');

    $juridicalAddress = array();

    if($streetInfo["street_kladr"]=='')
    {
        $juridicalAddress =  array(
            "customStreet"=>  array(
                "code" => "7800000000000000000000000",
                "street" => $address
            ),
            "house" => $addressHouse,
            "building" => "",
            "structure"=> "",
            "flat" => $addressCell);
    }
    else
    {
        $streetCode =  $streetInfo["street_kladr"];

        $juridicalAddress = array(
            "street"=> $streetCode,
            "house" => $addressHouse,
            "building" => "",
            "structure"=> "",
            "flat" => $addressCell);
    }

    $reqResult = array();
    $sAddPhysicalAddrURL = "https://api.dellin.ru/v1/customers/book/counteragents/update.json";
    $aAddPhysicalAddrOpts = array(
        "appKey" => "449A2F0C-9EA6-11E5-A3FB-00505683A6D3",
        // "appKey" => $this::AppKey,
        "sessionID" => $sSessionID,
        "name" => $companyName,
        "inn"=> $companyINN,

        "customForm"=> array(
            "formName" => $formName,
            "countryUID" => $countryUID,
            "juridical"=> "true"
        ),

        "juridicalAddress"=> $juridicalAddress,
    );

    $oAddPhysicalAddrAnswer = CallPOSTJSON($sAddPhysicalAddrURL, $aAddPhysicalAddrOpts);

    var_dump($oAddPhysicalAddrAnswer);

    $errTmp = Arr2Str('Документ отправителя - ', $oAddPhysicalAddrAnswer->errors);
    if ($errTmp)
        $errors[] = $errTmp;

    //print_r($aAddPhysicalAddrOpts); die();

    if (!isset($oAddPhysicalAddrAnswer->success)) {
        $reqResult["counteragentId"]= 0;
        $reqResult["status"]='error';
        $reqResult["error"]=implode($errors);
    }
    else
    {
        $reqResult["counteragentId"] = intval($oAddPhysicalAddrAnswer->success->counteragentID);
        $reqResult["status"]='ok';
        $reqResult["error"]='';
    }

    return $reqResult;
}


function list_company_order_options()
{

    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "services.php";

///////////////////////////////

    mb_internal_encoding("UTF-8");
    mb_regex_encoding("UTF-8");
    date_default_timezone_set('UTC');

    $json = '{"data":{"companyID":32,
            "cargoFrom":"Москва",
            "cargoTo":"Мурманск"},
            "modifies":{}}';

    // check POST body
    $oPOSTData = json_decode($json);

    if (!isset($oPOSTData->data->companyID))
        DropWithForbidden();

    if (intval($oPOSTData->data->companyID) <= 0)
        DropForbidden();
    ////////////////////////////////

    $transports = array();

    // include all modules
    foreach (glob($rundir . "/CALC_*.php") as $modname) {
        include $modname;
    }

    $oCompanyDesc = $transports[intval($oPOSTData->data->companyID)];
    $sCompClassName = $oCompanyDesc["classname"];

    $oCompanyObject = new $sCompClassName();

    $aResultOut = $oCompanyObject->GetOptions();

    /* http_response_code(200);
     header('Content-Type: application/json');
 */
    //echo "<pre>"; print_r($aResultOut); echo '</pre>';
    print(json_encode($aResultOut));
}


function login2()
{
    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "./service/user_class.php";
    require_once "./service/finance_class.php";

    /**
    // check POST body
    $oPOSTData = json_decode(file_get_contents("php://input"));

    //trigger_error('!!! ololo');
    // check if auth presence
    if ((!isset($oPOSTData->auth->login)) or (!isset($oPOSTData->auth->password)))
    DropWithUnAuth();
     */
// connect to DB
    $mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
    if ($mysqli->connect_errno)
        DropWithServerError();
// create User object
    $cUser = new User();

    $login = "test";
    $password = "test";

// trying to authenticate
    $iAuth = $cUser->UserFromAuth($mysqli, $login, $password);
//trigger_error("here");
    if (($iAuth != USER_OK) or (!$cUser->objectOK))
        DropWithUnAuth();

    $sSessionString = random_str(250);
    $iIP = ip2long($_SERVER['REMOTE_ADDR']);

// create session

    $sSessionReq = "INSERT INTO `" . DB_SESSIONS_TABLE . "` (`session_id`,`addr`, `uid`) VALUES (\"" . $sSessionString . "\", " . $iIP . ", " .
        intval($cUser->userID) . ")";
    $oRes = $mysqli->query($sSessionReq);
//trigger_error($mysqli->error);
    if ($oRes->affected_rows < 0)
        DropWithUnAuth();

// compile out data

    $aResultDataSet = array();

    $oFinance = new Finance();
    $fBalance = $oFinance->UserBalance($mysqli, $cUser->userID);

    $aResultDataSet = array(
        "id" => $cUser->userID,
        "formerlyName" => $cUser->userName,
        "defaultAddress" => $cUser->userAddress,
        "defaultEmail" => $cUser->userEMail,
        "defaultPhone" => $cUser->userPhone,
        "balance" => $fBalance,
        "isAdmin" => $cUser->isAdmin,
        "sessionString" => $sSessionString
    );

    $aResultOut = array(
        "success" => "success",
        "data" => $aResultDataSet
    );

    http_response_code(200);
    header('Content-Type: application/json');
    print(json_encode($aResultOut));

}

function create_user()
{
    //  require_once('check_key.php');

    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "./service/user_class.php";
    require_once "./service/auth.php";
    require_once "./service/SendMailSmtpClass.php";

// check POST body
    $str = '{
    
                "modifies":
                    {"formerlyName":""
                    ,"defaultAddress":""
                    ,"defaultEmail":"test2@test.zzz"
                    ,"password":"12345678",
                    "isJur":false,"passportNumber":""
                    ,"passportGivenName":""
                    ,"passportGivenDate":""
                    ,"INN":"","KPP":""
                    ,"jurAddress":""
                    ,"mailAddress":""
                    ,"accNumber":""
                    ,"BIK":""
                    ,"chiefName":""
                    ,"jurBase":""}}';

    $str = '{"modifies": {
             "defaultEmail": "hoda@ether123.net",
             "password": 123 
            }}';


    $oPOSTData = json_decode($str);

    //echo "<pre>"; print_r($oPOSTData); die;

    /**
    // check for parameters presence
    if ((!isset($oPOSTData->modifies->formerlyName)) or
    //(!isset($oPOSTData->modifies->passportNum)) or
    (!isset($oPOSTData->modifies->password))
    //or (isset($oPOSTData->modifies->passportNum) and (intval($oPOSTData->modifies->passportNum) < 1))
    or (!isset($oPOSTData->modifies->defaultEmail))
    ) {
    DropWithBadRequest("Not enough or wrong parameters");
    }

    ////////////////////////
     */
// connect to DB

    //  echo '<br>',DB_HOST,'<br>', DB_RW_LOGIN,'<br>', DB_RW_PASSWORD,'<br>', DB_NAME;

    $host= "localhost";

    $mysqli = new mysqlii($host, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

// check DB connection
    /*  if ($mysqli->connect_errno)
          DropWithUnAuth();
  */

    $iUserID = CheckAuth();

/////////////////////////
// check if auth presence
    if ($iUserID === false) {
//trigger_error('ggg');
        // NOT AUTH

        $cNewUser = new User();
        $iNewUserResult = $cNewUser->NewUserFromParameters($mysqli,
            $oPOSTData->modifies->formerlyName_i,
            $oPOSTData->modifies->formerlyName_o,
            $oPOSTData->modifies->formerlyName_f,

            $oPOSTData->modifies->defaultPhone,
            $oPOSTData->modifies->defaultEmail,
            isset($oPOSTData->modifies->defaultAddress) ? $oPOSTData->modifies->defaultAddress : "",
            $oPOSTData->modifies->defaultAddressCell,
            $oPOSTData->modifies->password,
            false,
            //$oPOSTData->modifies->vkID, $oPOSTData->modifies->passportNum,
            0,
            $oPOSTData->modifies->defaultLang,
            $oPOSTData->modifies->defaultCurrency,
            $oPOSTData->modifies->defaultWUnit,
            $oPOSTData->modifies->defaultVUnit,

            $oPOSTData->modifies->isJur,
            $oPOSTData->modifies->passportNumber,
            $oPOSTData->modifies->passportGivenName,
            $oPOSTData->modifies->passportGivenDate,
            $oPOSTData->modifies->INN,
            $oPOSTData->modifies->jurForm,
            $oPOSTData->modifies->OGRN,
            $oPOSTData->modifies->KPP,
            $oPOSTData->modifies->jurName,
            $oPOSTData->modifies->jurAddress,
            $oPOSTData->modifies->jurAddressCell,
            $oPOSTData->modifies->mailAddress,
            $oPOSTData->modifies->accNumber,
            $oPOSTData->modifies->BIK,
            $oPOSTData->modifies->chiefName,
            $oPOSTData->modifies->jurBase
        );

        switch ($iNewUserResult)
        {
            case USER_OK:
                // generate random registration code

                $sRandCookie = random_str(250);
                // write it to DB

                $sCookieWriteQuery = "INSERT INTO `" . DB_REGISTRATIONS_TABLE . "` 
									  (`id`,`code`) 
									  VALUES (" . $cNewUser->userID . ", " .
                    "\"" . $mysqli->real_escape_string($sRandCookie) . "\")";
                $mysqli->query($sCookieWriteQuery);

                // send email

                $sMailText = MAIL_TEXT . " <a href=\"https://api." . HOST_REG_FROM . "/3/register.php?q=" . $sRandCookie . "\">" .
                    "https://api." . HOST_REG_FROM . "/3/register.php?q=" . $sRandCookie . "</a>";

                $sMailToHeader = "To: " . $oPOSTData->modifies->defaultEmail . "\r\n";

                date_default_timezone_set("UTC");
                $mailSMTP = new SendMailSmtpClass(MAIL_REG_FROM, PASS_REG_FROM, MAILER, HOST_REG_FROM, MAILER_PORT);
                $mailResult = $mailSMTP->send($oPOSTData->modifies->defaultEmail, MAIL_SUBJECT, $sMailText, MAIL_HEADERS . $sMailToHeader);

                if (!($mailResult === true))
                    $iNewUserResult = USER_DB_ERROR;

                break;
            case USER_NO_PARAMS:
                DropWithServerError("Login or password is empty, error  " . $iNewUserResult);

                break;
            case USER_EXISTS:
                DropWithServerError("This email already exists, error " . $iNewUserResult);

                break;
            case USER_DB_ERROR:
                DropWithServerError("Notify to administrator, error " . $iNewUserResult);
                break;
        }


        $mysqli->close();

    } else {
        // AUTH
        // user
        // create User object
        $cUser = new User();

        // trying to authenticate
        $iAuth = $cUser->UserFromID($mysqli, $iUserID);

        if ($iAuth != USER_OK)
            DropWithUnAuth();

        // check for admin rights
        if (!$cUser->isAdmin)
            DropWithForbidden();
//trigger_error(intval($cUser->isAdmin));
        $cNewUser = new User();
        $iNewUserResult = $cNewUser->NewUserFromParameters($mysqli,
            $oPOSTData->modifies->formerlyName_i,
            $oPOSTData->modifies->formerlyName_o,
            $oPOSTData->modifies->formerlyName_f,
            $oPOSTData->modifies->defaultPhone,
            $oPOSTData->modifies->defaultEmail,
            isset($oPOSTData->modifies->defaultAddress) ? $oPOSTData->modifies->defaultAddress : "",
            $oPOSTData->modifies->defaultAddressCell,
            $oPOSTData->modifies->password,
            (isset($oPOSTData->modifies->isAdmin) ? $oPOSTData->modifies->isAdmin : false),
            //$oPOSTData->modifies->vkID, $oPOSTData->modifies->passportNum
            1,
            $oPOSTData->modifies->defaultLang,
            $oPOSTData->modifies->defaultCurrency,
            $oPOSTData->modifies->defaultWUnit,
            $oPOSTData->modifies->defaultVUnit,

            $oPOSTData->modifies->isJur,
            $oPOSTData->modifies->passportNumber,
            $oPOSTData->modifies->passportGivenName,
            $oPOSTData->modifies->passportGivenDate,
            $oPOSTData->modifies->INN,
            $oPOSTData->modifies->jurForm,
            $oPOSTData->modifies->OGRN,
            $oPOSTData->modifies->KPP,
            $oPOSTData->modifies->jurName,
            $oPOSTData->modifies->jurAddress,
            $oPOSTData->modifies->jurAddressCell,
            $oPOSTData->modifies->mailAddress,
            $oPOSTData->modifies->accNumber,
            $oPOSTData->modifies->BIK,
            $oPOSTData->modifies->chiefName,
            $oPOSTData->modifies->jurBase
        );

        $mysqli->close();
    }

    switch ($iNewUserResult) {
        case USER_OK:
            ReturnSuccess();
        case USER_NO_PARAMS:
            DropWithBadRequest("Not enough parameters");
        case USER_DB_ERROR:
            DropWithServerError("DB error");
        case USER_EXISTS:
            DropWithServerError("User already exists");
    }
}

function create_order()
{
    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "services.php";
    require_once "./service/user_class.php";
    require_once "./service/order_class.php";
    require_once "./service/auth.php";
    require_once "./service/finance_class.php";
    require_once "./modules/CALC_dellin.php";
// check POST body

    $strPerson = '
    {
    "data":{},
    "modifies":
{
    "clientID": 187,
    "derivalTerminalName": "",
    "arrivalTerminalName": "",
    "cargoVolUnitName": "м³",
    "cargoWeightUnitName": "кг",
    "cargoRecepientCompanyRegion": "Москва",
    "cargoRecepientCompanyCity": "Москва",
    "cargoRecepientCompanyZip": "129164",
    "cargoRecepientCompanyStreet": "проспект Мира",
    "cargoRecepientCompanyStreetNumber": "120с1",
    "cargoSenderCompanyRegion": "Москва",
    "cargoSenderCompanyCity": "Москва",
    "cargoSenderCompanyZip": "129223",
    "cargoSenderCompanyStreet": "проспект Мира",
    "cargoSenderCompanyStreetNumber": "владение 119",
    "cargoMethod": "Автотранспорт",
    "cargoName": "Деловые Линии",
    "cargoFrom": "Москва, Москва",
    "cargoFromZip": "109012",
    "cargoFromRegion": "Москва",
    "cargoTo": "Самара, Самарская обл.",
    "cargoToZip": "443084",
    "cargoToRegion": "Самарская обл.",
    "cargoWeight": "12",
    "cargoVol": "0.1",
    "cargoPrice": 1136,
    "cargoSite": "http://www.dellin.ru",
    "cargoDangerClass": "",
    "cargoTemperature": "",
    "cargoCompanyID": 32,
    "cargoDate": 1522713600,
    "cargoSenderAddressStructureNumber": "",
    "cargoSenderAddressCode": "7700000000000500000000000",
    "cargoRecepientAddressStructureNumber": "",
    "cargoRecepientAddressCode": "6300000100003400000000000",
    "derivalCourier": "Забор груза от адреса отправителя",
    "cargoDesireDate": 1522713600,
    "cargoSenderAddress": "ул Маевок",
    "cargoSenderAddressHouseNumber": "1",
    "cargoSenderAddressBuildingNumber": "2",
    "cargoSenderAddressCell": "3",
    "senderWorkTimeStart": "9",
    "senderWorkTimeEnd": "18",
    "arrivalCourier": "Доставить груз до адреса получателя",
    "cargoDeliveryDate": 1522713600,
    "cargoRecepientAddress": "ул Речная",
    "cargoRecepientAddressHouseNumber": "1",
    "cargoRecepientAddressBuildingNumber": "2",
    "cargoRecepientAddressCell": "3",
    "recepientWorkTimeStart": "9",
    "recepientWorkTimeEnd": "18",
    "senderPhysOrJur": "Юридическое лицо",
    "cargoSenderContactLastName": "Тест",
    "cargoSenderContactFirstName": "Тест",
    "cargoSenderCompanyName": "Тестовое1",
    "cargoSenderCompanyINN": "54656565656",
    "cargoSenderCompanyFormId": "5",
    "cargoSenderCompanyPhone": "+4 545 435 45 65",
    "cargoSenderCompanyEmail": "andrii.sokoliuk@gmail.com",
    "cargoSenderCompanyAddress": "Москва, проспект Мира, владение 119",
    "cargoSenderCompanyAddressCell": "5",
    "recepientPhysOrJur": "Юридическое лицо",
    "cargoRecepientContactLastName": "Тест",
    "cargoRecepientContactFirstName": "Тест",
    "cargoRecepientCompanyName": "Тестовое2",
    "cargoRecepientCompanyINN": "56776767565",
    "cargoRecepientCompanyFormId": "5",
    "cargoRecepientCompanyPhone": "+4 545 567 67 67",
    "cargoRecepientCompanyEmail": "ank@gmail.com",
    "cargoRecepientCompanyAddress": "Москва, проспект Мира, 120с1",
    "cargoRecepientCompanyAddressCell": "54",
    "cargoGoodsName": "Груз",
    "cargoTemperatureModeId": "1",
    "cargoDangerClassId": "1",
    "cargoWeightTypeID": "1",
    "cargoVolTypeID": "1",
    "cargoGoodsPrice": "100",
    "cargoLength": "0.278",
    "cargoWidth": "0.6",
    "cargoHeight": "0.6",
    "paymentType": "1",
    "payerType": "1"
  }
}';


    $oPOSTData = json_decode($strPerson);

   // echo "<pre>";var_dump($oPOSTData);echo "</pre>";


    $host = "localhost";

    $mysqli = new mysqlii($host, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

    $res = GetCompanyFormShortName($mysqli,1);

    // echo $res; die();

// check DB connection


// create User object
    $cUser = new User();

    $iAuth = $cUser->UserFromID($mysqli, $oPOSTData->modifies->clientID);
//print(strlen($oPOSTData->modifies->cargoRecipientPassport));

// check for parameters presence
    $isRecipientJur = false;
    $isSenderJur = false;

    // Check on correct price value
    //$oCalculator =  new $transports[$transportNum]['classname']();


    if (!isset($oPOSTData)) {
        DropWithBadRequest("Ошибка в полученой структуре JSON");
    }

    if (!isset($oPOSTData->modifies->recepientPhysOrJur)) {
        DropWithBadRequest("RecipientPhysOrJur didn't set");
    } else {
        if ($oPOSTData->modifies->recepientPhysOrJur == "Физическое лицо")
        {

            if (!isset($oPOSTData->modifies->cargoRecepientFirstName)) {
                DropWithBadRequest("Not set parameter cargoRecipientFirstName");
            }
            if (!isset($oPOSTData->modifies->cargoRecepientLastName)) {
                DropWithBadRequest("Not set parameter cargoRecipientLastName");
            }
            if (!isset($oPOSTData->modifies->cargoRecepientDocumentTypeId)) {
                DropWithBadRequest("Not set parameter cargoRecipientDocumentTypeId");
            }
            if (!isset($oPOSTData->modifies->cargoRecepientDocumentNumber)) {
                DropWithBadRequest("Not set parameter cargoRecipientDocumentNumber");
            }
            if (!isset($oPOSTData->modifies->cargoRecepientPhone)) {
                DropWithBadRequest("Not set parameter cargoRecipientPhone");
            }
            if (!isset($oPOSTData->modifies->cargoRecepientEmail)) {
                DropWithBadRequest("Not set parameter cargoRecipientEmail");
            }
        } elseif ($oPOSTData->modifies->recepientPhysOrJur == "Юридическое лицо") {
            $isRecipientJur   = true;
            if (!isset($oPOSTData->modifies->cargoRecepientContactFirstName)) {
                DropWithBadRequest("Not set parameter cargoRecipientContactFirstName");
            }

            if (!isset($oPOSTData->modifies->cargoRecepientContactLastName)) {
                DropWithBadRequest("Not set parameter cargoRecipientContactLastName");
            }
            if (!isset($oPOSTData->modifies->cargoRecepientCompanyName)) {
                DropWithBadRequest("Not set parameter cargoRecipientCompanyName");
            }
            if (!isset($oPOSTData->modifies->cargoRecepientCompanyFormId)) {
                DropWithBadRequest("Not set parameter cargoRecipientCompanyFormId");
            }
            if (!isset($oPOSTData->modifies->cargoRecepientCompanyINN)) {
                DropWithBadRequest("Not set parameter cargoRecipientCompanyINN");
            }
            if (!isset($oPOSTData->modifies->cargoRecepientCompanyPhone)) {
                DropWithBadRequest("Not set parameter cargoRecipientCompanyPhone");
            }
            if (!isset($oPOSTData->modifies->cargoRecepientCompanyAddress)) {
                DropWithBadRequest("Not set parameter cargoRecipientCompanyAddress");
            }

        } else {
            DropWithBadRequest("Not assigned RecipientPhysOrJur parameter");
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

        }
        else
        {
            DropWithBadRequest("Not assigned SenderPhysOrJur parameter");
        }
    }

    $isDerivalCourier = false;
    $isArrivalCourier = false;

    if (!isset($oPOSTData->modifies->derivalCourier))
    {
        if(intval($oPOSTData->modifies->cargoCompanyID)==32)
        {
            DropWithBadRequest("derivalCourier didn't set");
        }
    }
    else
    {
        if($oPOSTData->modifies->derivalCourier=="Забор груза от адреса отправителя")
        {
            if (!isset($oPOSTData->modifies->cargoSenderAddressCode)
                //   or (!isset($oPOSTData->modifies->cargoSenderHouseNumber))
            )
            {
                DropWithBadRequest("Not enough parameters for selected derivalCourier");
            }

            $isDerivalCourier=true;
        }
        else
        {
            if (!isset($oPOSTData->modifies->derivalTerminalId))
            {
                DropWithBadRequest("Not enough parameters for selected derivalTerminalId");
            }
        }
    }

    if (!isset($oPOSTData->modifies->arrivalCourier))
    {
        if(intval($oPOSTData->modifies->cargoCompanyID)==32)
        {
            DropWithBadRequest("arrivalCourier didn't set");
        }
    }
    else
    {
        if($oPOSTData->modifies->arrivalCourier=="Доставить груз до адреса получателя")
        {
            if (!isset($oPOSTData->modifies->cargoRecepientAddressCode)
                //   or (!isset($oPOSTData->modifies->cargoRecipientHouseNumber))
            )
            {
                DropWithBadRequest("Not enough parameters for selected derivalCourier");
            }


            $isArrivalCourier = true;
        }
        else
        {
            if (!isset($oPOSTData->modifies->arrivalTerminalId))
            {
                DropWithBadRequest("Not enough parameters for selected derivalTerminalId");
            }
        }
    }

    //  if(IS_DEBUG) echo '$sRecipientAddress='.$isArrivalCourier.'<br>';
    //  if(IS_DEBUG) echo '$sSenderAddress='.$isDerivalCourier.'<br>';



    $userLang = "ru";
    $userCurrency = "RUB";

    if($cUser->userDefLang!="")
    {
        $userLang = $cUser->userDefLang;
    }

    if($cUser->userDefCurr!="")
    {
        $userCurrency = strtoupper($cUser->userDefCurr);
    }

    if($oPOSTData->modifies->cargoWidth!="" &&
       $oPOSTData->modifies->cargoLength!="" &&
       $oPOSTData->modifies->cargoHeight!="")
       {
         $isActiveLineParams = 1;
       }

    $cargoFrom = explode(',',$oPOSTData->modifies->cargoFrom)[0];
    $cargoTo = explode(',',$oPOSTData->modifies->cargoTo)[0];

    $oCalculator = new calculator_DELLIN();
    require_once 'services.php';
    if(intval($oPOSTData->modifies->cargoCompanyID)==32)
    {

        $oRetVal = $oCalculator->Calculate2(
            $cargoFrom,
            $cargoTo,
            $oPOSTData->modifies->cargoWeight,
            $oPOSTData->modifies->cargoVol,
            $oPOSTData->modifies->cargoGoodsPrice,
            $userLang,
            $userCurrency,"RU","RU",
            $oPOSTData->modifies->cargoFromRegion,
            $oPOSTData->modifies->cargoToRegion,
            $isDerivalCourier,
            $oPOSTData->modifies->cargoSenderAddress,
            $oPOSTData->modifies->cargoSenderAddressCode,
            $isArrivalCourier,
            $oPOSTData->modifies->cargoRecepientAddress,
            $oPOSTData->modifies->cargoRecepientAddressCode,
            null);

    }
    else
    {
        $oRetVal = $oCalculator->Calculate(
            $oPOSTData->modifies->cargoFrom,
            $oPOSTData->modifies->cargoTo,
            $oPOSTData->modifies->cargoWeight,
            $oPOSTData->modifies->cargoVol,
            $oPOSTData->modifies->cargoGoodsPrice,
            $userLang,
            $userCurrency,
            "RU","RU",
            $oPOSTData->modifies->cargoFromRegion,
            $oPOSTData->modifies->cargoToRegion,
            $isActiveLineParams,
            $oPOSTData->modifies->cargoWidth,
            $oPOSTData->modifies->cargoLength,
            $oPOSTData->modifies->cargoHeight,
            null
        );
    }
    //var_dump($oRetVal['methods']['0']["calcResultPrice"]); die;

    if(floatval($oRetVal['methods']['0']["calcResultPrice"])!=floatval($oPOSTData->modifies->cargoPrice))
    {
        DropWithBadRequest("Цена не соответствует заявленой услуге. Измените параметры заявки или обратитесь к администрации сайта");
    }



    $iClientID = 15;

    $oNewOrder = new Order();


    $iNewOrderResult =
        $oNewOrder->NewOrder(
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
            $oPOSTData->modifies->cargoGoodsPrice,
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
            $oPOSTData->modifies->payerType,

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
            $oPOSTData->modifies->cargoSenderCompanyAddressCell,
            $oPOSTData->modifies->cargoSenderContactFirstName,
            $oPOSTData->modifies->cargoSenderContactLastName,


            $oPOSTData->modifies->derivalTerminalId,
            $oPOSTData->modifies->derivalTerminalName,
            $oPOSTData->modifies->cargoSenderAddress,
            $oPOSTData->modifies->cargoSenderAddressCode,
            $oPOSTData->modifies->cargoSenderAddressHouseNumber,
            $oPOSTData->modifies->cargoSenderAddressBuildingNumber,
            $oPOSTData->modifies->cargoSenderAddressStructureNumber,
            $oPOSTData->modifies->cargoSenderAddressCell,

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
            $oPOSTData->modifies->cargoRecepientCompanyAddressCell,
            $oPOSTData->modifies->cargoRecepientContactFirstName,
            $oPOSTData->modifies->cargoRecepientContactLastName,

            $oPOSTData->modifies->arrivalTerminalId,
            $oPOSTData->modifies->arrivalTerminalName,
            $oPOSTData->modifies->cargoRecepientAddress,
            $oPOSTData->modifies->cargoRecepientAddressCode,
            $oPOSTData->modifies->cargoRecepientAddressHouseNumber,
            $oPOSTData->modifies->cargoRecepientAddressBuildingNumber,
            $oPOSTData->modifies->cargoRecepientAddressStructureNumber,
            $oPOSTData->modifies->cargoRecepientAddressCell,

            $isDerivalCourier,
            $isArrivalCourier,
            $oPOSTData->modifies->cargoVolUnitName,
            $oPOSTData->modifies->cargoWeightUnitName
        );

    echo $iNewOrderResult.'<br>';

    //  echo "<pre>"; print_r($iNewOrderResult); die;
//var_dump($iNewOrderResult); die;
    if ($iNewOrderResult > 0) {

        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        // !! TEST CASE

        $transports = array();
        /*
                // include all modules
                foreach (glob($rundir . "/CALC_*.php") as $modname) {
                    include $modname;
                }
        */
        $oCompanyDesc = $transports[intval(
            $oPOSTData->modifies->cargoCompanyID)];

        $sCompanyOrderNum = 0;
        /*
                if ((isset($oCompanyDesc['canorder']))
                    and ($oCompanyDesc['canorder'] === true)
                ) {
          */          //print_r($cUser);
        // call company order


        $oPOSTData->modifies->internalNumber = $iNewOrderResult;


        $sRecipientFIO = trim($oPOSTData->modifies->cargoRecepientLastName)
            .' '.trim($oPOSTData->modifies->cargoRecepientFirstName).' '
            .trim($oPOSTData->modifies->cargoRecepientSecondName);

        if (isset($oPOSTData->modifies->cargoRecepientContactFirstName)
            &&
            isset($oPOSTData->modifies->cargoRecepientContactLastName))
        {
            $sRecipientContactFIO =
                trim($oPOSTData->modifies->cargoRecepientContactFirstName)
                . ' ' . trim($oPOSTData->modifies->cargoRecepientContactLastName);
        }
        else
        {
            $sRecipientContactFIO = trim($sRecipientFIO);
        }

        $sSenderFIO = trim($oPOSTData->modifies->cargoSenderLastName) . ' '
            . trim($oPOSTData->modifies->cargoSenderFirstName) . ' '
            . trim($oPOSTData->modifies->cargoSenderSecondName);

        if (isset($oPOSTData->modifies->cargoSenderContactFirstName)
            &&
            isset($oPOSTData->modifies->cargoSenderContactLastName))
        {
            $sSenderContactFIO = trim($oPOSTData->modifies->cargoSenderContactFirstName)
                . ' ' . trim($oPOSTData->modifies->cargoSenderContactLastName);
        }
        else
        {
            $sSenderContactFIO = trim($sSenderFIO);
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
        if($isRecipientJur)
        {
            $sRecipientCompanyFormShortName = GetCompanyFormShortName($mysqli,
                $oPOSTData->modifies->cargoRecepientCompanyFormId);

            if ($sRecipientCompanyFormShortName == '') {
                DropWithBadRequest("Bad RecipientCompanyFormID");
            }
        }

        //$iNewOrderResult = 1181;
        //$sCompanyOrderNum = 10207577;
        // $sCompClassName = $oCompanyDesc["classname"];

        $oCompanyObject = new calculator_DELLIN();//new $sCompClassName();
           $sCompanyOrderNum = $oCompanyObject->MakeOrderWithAddressDelivery(
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
            $oPOSTData->modifies->cargoRecepientAddressCode,
            $oPOSTData->modifies->cargoRecepientAddressHouseNumber,
            $oPOSTData->modifies->cargoRecepientAddressBuildingNumber,
            $oPOSTData->modifies->cargoRecepientAddressStructureNumber,
            $oPOSTData->modifies->cargoRecepientAddressCell,

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
            $oPOSTData->modifies->cargoSenderAddressCode,
            $oPOSTData->modifies->cargoSenderAddressHouseNumber,
            $oPOSTData->modifies->cargoSenderAddressBuildingNumber,
            $oPOSTData->modifies->cargoSenderAddressStructureNumber,
            $oPOSTData->modifies->cargoSenderAddressCell,

            $isDerivalCourier,
            $isArrivalCourier,
            $oPOSTData->modifies->cargoDesireDate,
            $oPOSTData->modifies->cargoDeliveryDate,
            $oPOSTData->modifies->cargoSenderAddressCode,
            $oPOSTData->modifies->cargoRecepientAddressCode
        );
        echo '<br>';
        var_dump($sCompanyOrderNum);
        echo '<br>';

        if (is_array($sCompanyOrderNum)) {
            DropWithServerError("Errors: " . implode(',', $sCompanyOrderNum));
        } else if (intval($sCompanyOrderNum) <= 0) {
            DropWithServerError("Cargo Company cannot create order with this parameters.");
        } else {

            // we have an order number from company
//		print($sCompanyOrderNum);
            $oNewOrder->sCompanyInternalNumber = $sCompanyOrderNum;
            $oNewOrder->SaveOrder($mysqli);

            //        }
        }



        $isTtnFileCrated = $oCompanyObject->SavePdf($sCompanyOrderNum,$iNewOrderResult);
        $oFinance = new Finance();
        $oFinance->NewOperation($mysqli, 0 - floatval($oPOSTData->modifies->cargoPrice), $iClientID, $iNewOrderResult, "Задолженность за логистические услуги");

        $sNewStateQuery = "INSERT INTO `" . DB_STATES_TABLE . "` (`order_id`, `operation_id`, `comment`) VALUES (" . $iNewOrderResult . ", " .
            "1, \"Создание заказа\")";
        $iNewStateRes = $mysqli->query($sNewStateQuery);



        $sCargoLogoPath='';

        switch (intval($oPOSTData->modifies->cargoCompanyID))
        {
            case 32:
                $sCargoLogoPath = "email_template/content/img/logo-dellin.jpg";
                break;
            case 136:
                $sCargoLogoPath =  "email_template/content/img/logo-intime.jpg";
                break;
            case 8:
                $sCargoLogoPath = "email_template/content/img/logo_airgreenland.jpg";
                break;
            case 130:
                $sCargoLogoPath =  "email_template/content/img/logo_matkahuolto.jpg";
                break;
            case 96:
                $sCargoLogoPath = "email_template/content/img/logo_xpologistics.jpg";
                break;

            default: $sCargoLogoPath = $oCompanyDesc[logo];
            break;
        }



        /** Send mail to dispatcher */
        require_once "./service/mail_class.php";
        $mail = new Mail();

        $mailToDispatcher =  $mail->SendOrderMailToClient($mysqli,$iNewOrderResult,"RU",'dispatcher',$sCargoLogoPath);

        /** Send mail to customer */

        $mailToClient =  $mail->SendOrderMailToClient($mysqli,$iNewOrderResult,"RU",'customer',$sCargoLogoPath);

        /** Send mail to sender */

        $mailToSender =  $mail->SendOrderMailToClient($mysqli,$iNewOrderResult,"RU",'sender',$sCargoLogoPath);

        if (!($mailToDispatcher === true))
            $message .='Mail to dispatcher not send. ';

        if (!($mailToClient === true))
            $message .='Mail to client not send. ';

        if (!($mailToSender === true))
            $message .='Mail to sender not send. ';


        $file_mail_name=TTN_PATH."cargo_order_$iNewOrderResult.pdf";
        $file_mail_name2 = TTN_PATH."ttn_#".$iNewOrderResult.".pdf";

        if(file_exists($file_mail_name))
        {
            unlink($file_mail_name);
        }

        if(file_exists($file_mail_name2))
        {
            unlink($file_mail_name2);
        }

        //}
        $mysqli->close();

        switch ($iNewOrderResult) {
            case PARCEL_NO_PARAMS:
                DropWithBadRequest("Not enough parameters");
            case PARCEL_DB_ERROR:
                DropWithServerError();
            case PARCEL_EXISTS:
                DropWithServerError("Order already exists");
            default: {
                //$oFinance = new Finance();
                //$oFinance->NewOperation($mysqli,0-$oPOSTData->modifies->cargoPrice, $iClientID, $iNewOrderResult, "Задолженность");
                ReturnSuccess(array("id" => $iNewOrderResult));
            }
        }

    }
}


function show_order()
{
    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "./service/user_class.php";
    require_once "./service/auth.php";
    require_once "./service/order_class.php";

// check POST body
    $json = '{"data":{"orderID":1174}}';

    $oPOSTData = json_decode($json);


    /*
      if (!isset($oPOSTData->data->orderID))
          DropWithBadRequest("Not enough parameters");
  */

// connect to DB
    $host = 'localhost';

    $mysqli = new mysqlii($host,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

    //   $mysqli->query('SET NAMES utf8; SET CHARACTER SET utf8;');

// check DB connection
    /*   if ($mysqli->connect_errno)
           DropWithServerError("DB error");
   */
// input parameters
    $iOrderID = intval($oPOSTData->data->orderID);



// check auth and rights
// create User object
    $oUser = new User();

// trying to authenticate
    //   $iAuth = $oUser->UserFromID($mysqli, $iUserID);

    /*   if (($iAuth != USER_OK) or (!$oUser->objectOK))
           DropWithUnAuth();
   */

    $oOrder = new Order();
    $aOrder = $oOrder->OrderFromID($mysqli, $iOrderID);

    //  echo '<pre>'; print_r($aOrder); die;

//    if ($aOrder != PARCEL_OK)
//        DropWithNotFound();

// if not admin
    /*    if ((!$oUser->isAdmin) && ($oOrder->iOrderClientID != $oUser->userID))
        {
            DropWithForbidden();
        }
    */

// compile out data
    $aResultDataSet = array();
    $aResults = array();
//print_r($
//foreach($aOrders as $oOrderRes)
//	{
    $oOrderRes = $oOrder;

    $aSerialized = $oOrderRes->oSerializedData;

    $aResults = array(
        "clientID" => intval($oOrderRes->iOrderClientID),
        "companyID" => intval($oOrderRes->iCompanyID),
        "orderTimestamp" => intval($oOrderRes->iOrderTimestamp),
        "cargoName" => $oOrderRes->sOrderCargoName,
        "cargoFrom" => $oOrderRes->sOrderCargoFrom,
        "cargoTo" => $oOrderRes->sOrderCargoTo,
        "cargoMethod" => $oOrderRes->sOrderCargoMethod,
        "cargoSite" => $oOrderRes->sCargoSite,
        "cargoWeight" => $oOrderRes->fOrderCargoWeight,
        "cargoVol" => $oOrderRes->fOrderCargoVol,
        "cargoWidth" => $oOrderRes->fOrderCargoWidth,
        "cargoHeight" => $oOrderRes->fOrderCargoHeight,
        "cargoLength" => $oOrderRes->fOrderCargoLength,
        "cargoPrice" => floatval($oOrderRes->fOrderCargoPrice),
        "cargoValue" => floatval($oOrderRes->fOrderCargoValue),
        "cargoDesiredDate" => $oOrderRes->sOrderDesiredDate,
        "cargoDeliveryDate" => $oOrderRes->sOrderDeliveryDate,

        "cargoVolUnitName" => $oOrderRes->sCargoVolUnitName,
        "cargoWeightUnitName" => $oOrderRes->sCargoWeightUnitName,

        "orderCompanyName" => $oOrderRes->sOrderCargoName,

        "orderComment" => $oOrderRes->sOrderComment,

        "orderRecepientLegalEntity" => $oOrderRes->iOrderRecipientLegalEntity,

        "orderRecepientEmail" => $oOrderRes->sOrderRecipientEmail,
        "orderRecepientPhone" => $oOrderRes->sOrderRecipientPhone,
        "orderRecepientAddress" =>  $oOrderRes->sOrderRecipientAddress,
        "isArrivalWithCourier" =>  $oOrderRes->isArrivalWithCourier,

        "orderRecepientDocumentNumber" => $oOrderRes->sOrderRecipientDocumentNumber,
        "orderRecepientDocumentType" => $oOrderRes->sOrderRecipientDocumentType,
        "orderRecepientFirstName" => $oOrderRes->sOrderRecipientFirstName,
        "orderRecepientSecondName" => $oOrderRes->sOrderRecipientSecondName,
        "orderRecepientLastName" => $oOrderRes->sOrderRecipientLastName,
        "orderRecepientFullName" => $oOrderRes->sOrderRecipientFullName,

        "orderRecepientCompanyName" => $oOrderRes->sOrderRecipientCompanyName,
        "orderRecepientCompanyForm" => $oOrderRes->sOrderRecipientCompanyFormName,
        "orderRecepientCompanyInn" => $oOrderRes->iOrderRecipientCompanyInn,
        "orderRecepientCompanyEmail" => $oOrderRes->sOrderRecipientCompanyEmail,
        "orderRecepientCompanyPhone" => $oOrderRes->sOrderRecipientCompanyPhone,
        "orderRecepientCompanyContactFirstName" => $oOrderRes->sOrderRecipientCompanyContactFirstName,
        "orderRecepientCompanyContactSecondName" => $oOrderRes->sOrderRecipientCompanyContactSecondName,

        "orderSenderLegalEntity" => $oOrderRes->iOrderSenderLegalEntity,
        "isDerivalWithCourier" =>  $oOrderRes->isDerivalWithCourier,

        "orderSenderEmail" => $oOrderRes->sOrderSenderEmail,
        "orderSenderPhone" => $oOrderRes->sOrderSenderPhone,
        "orderSenderAddress" =>  $oOrderRes->sOrderSenderAddress,
        "orderSenderDocumentNumber" => $oOrderRes->sOrderSenderDocumentNumber,
        "orderSenderDocumentType" => $oOrderRes->sOrderSenderDocumentType,
        "orderSenderFirstName" => $oOrderRes->sOrderSenderFirstName,
        "orderSenderSecondName" => $oOrderRes->sOrderSenderSecondName,
        "orderSenderLastName" => $oOrderRes->sOrderSenderLastName,
        "orderSenderFullName" => $oOrderRes->sOrderSenderFullName,

        "orderSenderCompanyName" => $oOrderRes->sOrderSenderCompanyName,
        "orderSenderCompanyForm" => $oOrderRes->sOrderSenderCompanyFormName,
        "orderSenderCompanyInn" => $oOrderRes->iOrderSenderCompanyInn,
        "orderSenderCompanyEmail" => $oOrderRes->sOrderSenderCompanyEmail,
        "orderSenderCompanyPhone" => $oOrderRes->sOrderSenderCompanyPhone,
        "orderSenderCompanyContactFirstName" => $oOrderRes->sOrderSenderCompanyContactFirstName,
        "orderSenderCompanyContactSecondName" => $oOrderRes->sOrderSenderCompanyContactSecondName,

        "orderTemperatureModeName" => $oOrderRes->sOrderTemperatureModeName,
        "orderDangerClassName" => $oOrderRes->sOrderDangerClassName,

        "orderTemperatureModeId" => $oOrderRes->sOrderTemperatureModeId,
        "orderDangerClassId" => $oOrderRes->sOrderDangerClassId,
        "orderGoodsName" => $oOrderRes->sOrderGoodsName,
        "paymentTypeName" =>  $oOrderRes->sPaymentTypeName,
        "isOrderPayed"=>  $oOrderRes->iPayed,
        "id" => intval($oOrderRes->iOrderID)

    );
    $aTotal = array_merge($aResults,(array) $aSerialized);

    http_response_code(200);
    header('Content-Type: application/json');

    echo '<pre>'; print_r($aTotal);
    echo 'test';
    die;

    //  print(json_encode($aTotal));



}

function edit_user()
{
    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "./service/auth.php";
    require_once "./service/user_class.php";

// check POST body
    $json = '{
  "data":{
    "id":"201",
    "defaultPhone":"73233333333",
    "defaultEmail":"yude@send22u.info",
    "defaultLang":"ru"
  },
  "modifies":{
    "id":"201",
    "defaultAddress":"",
    "defaultPhone":73233333333,
    "defaultEmail":"yude@send22u.info",
    "defaultLang":"ru",
    "defaultCurr":"RUB",
    "isJur":true,
    "passportNumber":"",
    "passportGivenName":"",
    "passportGivenDate":"1.1.1970",
    "INN":45454545454,
    "jurForm":"Общество с дополнительной ответственност",
    "OGRN":3434342342342,
    "KPP":"",
    "jurName":"test",
    "jurAddress":"",
    "mailAddress":true,
    "accNumber":"",
    "BIK":"",
    "chiefName":"  ",
    "jurBase":""
  }
}';

    $oPOSTData = json_decode($json);
    $iUserID = 15;

    /*   $iUserID = CheckAuth();
       if ($iUserID === false)
           DropWithUnAuth();
   */
// check if auth presence
//if ((!isset($oPOSTData->auth->login)) or (!isset($oPOSTData->auth->password)))
    //{
    //DropWithUnAuth();
    //}

// connect to DB
    $host = 'localhost';
    $mysqli = new mysqlii($host,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
    /*  if ($mysqli->connect_errno)
          DropWithUnAuth();
  */
// create User object
    $cUser = new User();

// trying to authenticate
    $iAuth = $cUser->UserFromID($mysqli, $iUserID);

    /*    if (($iAuth != USER_OK) or (!$cUser->objectOK))
            DropWithUnAuth();
    */



// check for admin rights
    if (!$cUser->isAdmin)
    {
        // no admin rights, we can only edit self own account
        if (isset($oPOSTData->data))
        {

            // here we check
            $bEnableEdit = false;
            //if (isset($oPOSTData->data->passportNum))
            //if ($oPOSTData->data->passportNum == $cUser->userPassport)
            //$bEnableEdit = true;

            if (isset($oPOSTData->data->defaultEmail))
                if ($oPOSTData->data->defaultEmail == $cUser->userEMail)
                    $bEnableEdit = true;

            if (isset($oPOSTData->data->defaultPhone))
                if ($oPOSTData->data->defaultPhone == $cUser->userPhone)
                    $bEnableEdit = true;

            //if (isset($oPOSTData->data->vkID))
            //if ($oPOSTData->data->vkID == $cUser->userVKID)
            //$bEnableEdit = true;

            if (isset($oPOSTData->data->id))
                if ($oPOSTData->data->id == $cUser->userID)
                    $bEnableEdit = true;

            if ($bEnableEdit)
            {
                // edit self
                // read parameters
                if (isset($oPOSTData->modifies->formerlyName_i))
                    $cUser->userName = $mysqli->real_escape_string($oPOSTData->modifies->formerlyName_i);

                if (isset($oPOSTData->modifies->formerlyName_o))
                    $cUser->userSecondName = $mysqli->real_escape_string($oPOSTData->modifies->formerlyName_o);

                if (isset($oPOSTData->modifies->formerlyName_f))
                    $cUser->userLastName = $mysqli->real_escape_string($oPOSTData->modifies->formerlyName_f);

                //if (isset($oPOSTData->modifies->passportNum))
                //$cUser->userPassport = intval($oPOSTData->modifies->passportNum);

                if (isset($oPOSTData->modifies->defaultAddress))
                    $cUser->userAddress = $mysqli->real_escape_string($oPOSTData->modifies->defaultAddress);

                if (isset($oPOSTData->modifies->defaultAddressCell))
                    $cUser->userAddressCell = $mysqli->real_escape_string($oPOSTData->modifies->defaultAddressCell);

                if (isset($oPOSTData->modifies->defaultPhone))
                    $cUser->userPhone = intval($oPOSTData->modifies->defaultPhone);

                if (isset($oPOSTData->modifies->defaultEmail))
                    $cUser->userEMail = $mysqli->real_escape_string($oPOSTData->modifies->defaultEmail);

                if (isset($oPOSTData->modifies->defaultLang))
                    $cUser->userDefLang = $mysqli->real_escape_string($oPOSTData->modifies->defaultLang);

                if (isset($oPOSTData->modifies->defaultCurrency))
                    $cUser->userDefCurr = $mysqli->real_escape_string($oPOSTData->modifies->defaultCurrency);

                if (isset($oPOSTData->modifies->defaultWUnit))
                    $cUser->userDefWUnit = intval($oPOSTData->modifies->defaultWUnit);

                if (isset($oPOSTData->modifies->defaultVUnit))
                    $cUser->userDefVUnit = intval($oPOSTData->modifies->defaultVUnit);

                if (isset($oPOSTData->modifies->isJur))
                    $cUser->userIsJur = ($oPOSTData->modifies->isJur ? true : false);

                if (isset($oPOSTData->modifies->passportNumber))
                    $cUser->userPassportNum = intval($oPOSTData->modifies->passportNumber);

                if (isset($oPOSTData->modifies->passportGivenName))
                    $cUser->userPassportGivenName = $mysqli->real_escape_string($oPOSTData->modifies->passportGivenName);

                if (isset($oPOSTData->modifies->passportGivenDate))
                    $cUser->userPassportGivenDate = $mysqli->real_escape_string($oPOSTData->modifies->passportGivenDate);

                if (isset($oPOSTData->modifies->INN))
                    $cUser->userINN = intval($oPOSTData->modifies->INN);

                if (isset($oPOSTData->modifies->jurForm))
                    $cUser->userJurForm = $mysqli->real_escape_string($oPOSTData->modifies->jurForm);

                if (isset($oPOSTData->modifies->OGRN))
                    $cUser->userOGRN = intval($oPOSTData->modifies->OGRN);

                if (isset($oPOSTData->modifies->KPP))
                    $cUser->userKPP = intval($oPOSTData->modifies->KPP);

                if (isset($oPOSTData->modifies->jurName))
                    $cUser->userJurName = $mysqli->real_escape_string($oPOSTData->modifies->jurName);

                if (isset($oPOSTData->modifies->jurAddress))
                    $cUser->userJurAddress = $mysqli->real_escape_string($oPOSTData->modifies->jurAddress);

                if (isset($oPOSTData->modifies->jurAddressCell))
                    $cUser->userJurAddressCell = $mysqli->real_escape_string($oPOSTData->modifies->jurAddressCell);

                if (isset($oPOSTData->modifies->mailAddress))
                    $cUser->userMailAddress = $mysqli->real_escape_string($oPOSTData->modifies->mailAddress);

                if (isset($oPOSTData->modifies->accNumber))
                    $cUser->userAccNumber = intval($oPOSTData->modifies->accNumber);

                if (isset($oPOSTData->modifies->BIK))
                    $cUser->userBIK = intval($oPOSTData->modifies->BIK);

                if (isset($oPOSTData->modifies->chiefName))
                    $cUser->userChiefName = $mysqli->real_escape_string($oPOSTData->modifies->chiefName);

                if (isset($oPOSTData->modifies->jurBase))
                    $cUser->userJurBase = $mysqli->real_escape_string($oPOSTData->modifies->jurBase);

                //if (isset($oPOSTData->modifies->vkID))
                //$cUser->userVKID = intval($oPOSTData->modifies->vkID);

                $iResult = $cUser->SaveUser($mysqli);

                $mysqli->close();
                if ($iResult == USER_OK)
                    ReturnSuccess();
                else
                    DropWithServerError("Wrong parameters");
            }
            else
            {
                $mysqli->close();
                DropWithForbidden();
            }
        }
        else
        {
            // parameter required
            $mysqli->close();
            DropWithBadRequest("Not enough or wrong parameters");
        }
    }
    else
    {


        // we have admin rights. edit what you wish.
        // check for parameters presence
        if (
            //!isset($oPOSTData->data->passportNum) and
            !isset($oPOSTData->data->defaultEmail)
            and !isset($oPOSTData->data->defaultPhone)
            //and !isset($oPOSTData->data->vkID)
            and !isset($oPOSTData->data->id))
        {
            $mysqli->close();
            DropWithBadRequest("Not enough or wrong parameters");
        }

        // here we delete
        $searchUser = new User();

        if (isset($oPOSTData->data->id))
            $iSearchResult = $searchUser->UserFromID($mysqli,$oPOSTData->data->id);
        else
            $iSearchResult = $searchUser->UserFromSearch($mysqli,$oPOSTData->data->defaultPhone, $oPOSTData->data->defaultEmail
            //$oPOSTData->data->vkID, $oPOSTData->data->passportNum
            );

        if ($iSearchResult == USER_OK)
        {
            // read parameters
            if (isset($oPOSTData->modifies->formerlyName_i))
                $searchUser->userName = $mysqli->real_escape_string($oPOSTData->modifies->formerlyName_i);

            if (isset($oPOSTData->modifies->formerlyName_o))
                $searchUser->userSecondName = $mysqli->real_escape_string($oPOSTData->modifies->formerlyName_o);

            if (isset($oPOSTData->modifies->formerlyName_f))
                $searchUser->userLastName = $mysqli->real_escape_string($oPOSTData->modifies->formerlyName_f);

            //if (isset($oPOSTData->modifies->passportNum))
            //$searchUser->userPassport = intval($oPOSTData->modifies->passportNum);

            if (isset($oPOSTData->modifies->defaultAddress))
                $searchUser->userAddress = $mysqli->real_escape_string($oPOSTData->modifies->defaultAddress);

            if (isset($oPOSTData->modifies->defaultPhone))
                $searchUser->userPhone = intval($oPOSTData->modifies->defaultPhone);

            if (isset($oPOSTData->modifies->defaultEmail))
                $searchUser->userEMail = $mysqli->real_escape_string($oPOSTData->modifies->defaultEmail);

            if (isset($oPOSTData->modifies->defaultLang))
                $searchUser->userDefLang = $mysqli->real_escape_string($oPOSTData->modifies->defaultLang);

            if (isset($oPOSTData->modifies->defaultCurrency))
                $searchUser->userDefCurr = $mysqli->real_escape_string($oPOSTData->modifies->defaultCurrency);

            if (isset($oPOSTData->modifies->defaultWUnit))
                $searchUser->userDefWUnit = intval($oPOSTData->modifies->defaultWUnit);

            if (isset($oPOSTData->modifies->defaultVUnit))
                $searchUser->userDefVUnit = intval($oPOSTData->modifies->defaultVUnit);

            if (isset($oPOSTData->modifies->isJur))
                $searchUser->userIsJur = ($oPOSTData->modifies->isJur ? true : false);

            if (isset($oPOSTData->modifies->passportNumber))
                $searchUser->userPassportNum = intval($oPOSTData->modifies->passportNumber);

            if (isset($oPOSTData->modifies->passportGivenName))
                $searchUser->userPassportGivenName = $mysqli->real_escape_string($oPOSTData->modifies->passportGivenName);

            if (isset($oPOSTData->modifies->passportGivenDate))
                $searchUser->userPassportGivenDate = $mysqli->real_escape_string($oPOSTData->modifies->passportGivenDate);

            if (isset($oPOSTData->modifies->INN))
                $searchUser->userINN = strval($oPOSTData->modifies->INN);

            if (isset($oPOSTData->modifies->jurForm))
                $searchUser->userJurForm = $mysqli->real_escape_string($oPOSTData->modifies->jurForm);

            if (isset($oPOSTData->modifies->OGRN))
                $searchUser->userOGRN = strval($oPOSTData->modifies->OGRN);

            if (isset($oPOSTData->modifies->KPP))
                $searchUser->userKPP = intval($oPOSTData->modifies->KPP);

            if (isset($oPOSTData->modifies->jurName))
                $searchUser->userJurName = $mysqli->real_escape_string($oPOSTData->modifies->jurName);

            if (isset($oPOSTData->modifies->jurAddress))
                $searchUser->userJurAddress = $mysqli->real_escape_string($oPOSTData->modifies->jurAddress);

            if (isset($oPOSTData->modifies->mailAddress))
                $searchUser->userMailAddress = $mysqli->real_escape_string($oPOSTData->modifies->mailAddress);

            if (isset($oPOSTData->modifies->accNumber))
                $searchUser->userAccNumber = intval($oPOSTData->modifies->accNumber);

            if (isset($oPOSTData->modifies->BIK))
                $searchUser->userBIK = intval($oPOSTData->modifies->BIK);

            if (isset($oPOSTData->modifies->chiefName))
                $searchUser->userChiefName = $mysqli->real_escape_string($oPOSTData->modifies->chiefName);

            if (isset($oPOSTData->modifies->jurBase))
                $searchUser->userJurBase = $mysqli->real_escape_string($oPOSTData->modifies->jurBase);

            //if (isset($oPOSTData->modifies->vkID))
            //$searchUser->userVKID = intval($oPOSTData->modifies->vkID);

            if (isset($oPOSTData->modifies->isAdmin))
                $searchUser->isAdmin = ($oPOSTData->modifies->isAdmin ? 1 : 0);

            $iResult = $searchUser->SaveUser($mysqli);
            //print($iResult);
            $mysqli->close();

            if ($iResult == USER_OK)
                ReturnSuccess();
            else
                DropWithServerError("param");
        }
        else
            DropWithNotFound();
    }

}

function list_order()
{
    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "./service/auth.php";
    require_once "./service/user_class.php";
    require_once "./service/order_class.php";

// check POST body

    $json = '{
  "data": { "clientIDs": [ "187" ] },
  "modifies": {
    "timestampFrom": 0,
    "timestampTo": 0,
    "clients": [],
    "orders": [],
    "cargoName": "",
    "cargoFrom": "",
    "cargoTo": "",
    "searchWord":"Челяб",
    "offset": 0,
    "limit": 5
  }
}';

    $oPOSTData = json_decode($json);


    /*
        $iUserID = CheckAuth();
        if ($iUserID === false)
            DropWithUnAuth();
    */
// check if auth presence
//if ((!isset($oPOSTData->auth->login)) or (!isset($oPOSTData->auth->password)))
    //{
    //DropWithUnAuth();
    //}

// connect to DB
    $host = 'localhost';

    $mysqli = new mysqlii($host, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

// check DB connection
    /*  if ($mysqli->connect_errno)
          DropWithServerError("DB error");
  */

// input parameters
    $bAllParcels = false;

    $iTimestampFrom = (isset($oPOSTData->modifies->timestampFrom) ? $oPOSTData->modifies->timestampFrom : 0);
    $iTimestampTo = (isset($oPOSTData->modifies->timestampTo) ? $oPOSTData->modifies->timestampTo : 0);
    $aClients = (isset($oPOSTData->modifies->clientIDs) ? $oPOSTData->modifies->clientIDs : array());
    $aOrders = (isset($oPOSTData->modifies->orderIDs) ? $oPOSTData->modifies->orderIDs : array());
    $sCargoName = (isset($oPOSTData->modifies->cargoName) ? $oPOSTData->modifies->cargoName : "");
    $sCargoFrom = (isset($oPOSTData->modifies->cargoFrom) ? $oPOSTData->modifies->cargoFrom : "");
    $sCargoTo = (isset($oPOSTData->modifies->cargoTo) ? $oPOSTData->modifies->cargoTo : "");

// offset and limit

    $sSearchWord = (isset($oPOSTData->modifies->searchWord) ? $oPOSTData->modifies->searchWord : "");
   // echo $sSearchWord,'<br>';
    $iOffset = (isset($oPOSTData->modifies->offset) ? $oPOSTData->modifies->offset : 0);
    $iLimit = (isset($oPOSTData->modifies->limit) ? $oPOSTData->modifies->limit : 0);


// check auth and rights
// create User object
    $oUser = new User();

    $iUserID = 15;
// trying to authenticate
    $iAuth = $oUser->UserFromID($mysqli, $iUserID);
    /*
        if (($iAuth != USER_OK) or (!$oUser->objectOK))
            DropWithUnAuth();
    */
// if not admin
    if (!$oUser->isAdmin) {
        $bAllParcels = false;

        if (isset($oPOSTData->data->clientIDs))
            $aClients = array($oUser->userID);
    }

    $aClients = array(15);
    $oOrder = new Order();
    $aOrders = $oOrder->OrdersFromSearch($mysqli,
        $iTimestampFrom,
        $iTimestampTo,
        $aClients,
        $sCargoName,
        $sCargoFrom,
        $sCargoTo,
        $sSearchWord,
        $iLimit,
        $iOffset  );

    // var_dump($aOrders); die();

    $iOrdersCount = $oOrder->OrdersCountFromSearch($mysqli,
        $iTimestampFrom,
        $iTimestampTo,
        $aClients,
        $sCargoName,
        $sCargoFrom,
        $sCargoTo,
        $sSearchWord
        );

    if ($iOrdersCount < 0)
        $iOrdersCount = 0;

// compile out data
    $aResultDataSet = array();
    $aResults = array();
//print_r($
    foreach ($aOrders as $oOrderRes) {
        $aResultTmp = array(
            "clientID" => intval($oOrderRes->iOrderClientID),
            "companyID" => intval($oOrderRes->iCompanyID),
            "orderTimestamp" => intval($oOrderRes->iOrderTimestamp),
            "cargoName" => $oOrderRes->sOrderCargoName,
            "cargoFrom" => $oOrderRes->sOrderCargoFrom,
            "cargoTo" => $oOrderRes->sOrderCargoTo,
            "cargoMethod" => $oOrderRes->sOrderCargoMethod,
            "cargoSite" => $oOrderRes->sOrderCargoSite,
            "cargoWeight" => $oOrderRes->fOrderCargoWeight,
            "cargoVol" => $oOrderRes->fOrderCargoVol,
            "cargoWidth" => $oOrderRes->fOrderCargoWidth,
            "cargoHeight" => $oOrderRes->fOrderCargoHeight,
            "cargoLength" => $oOrderRes->fOrderCargoLength,
            "cargoPrice" => floatval($oOrderRes->fOrderCargoPrice),
            "cargoValue" => floatval($oOrderRes->fOrderCargoValue),
            "cargoDesiredDate" => $oOrderRes->sOrderDesiredDate,
            "cargoDeliveryDate" => $oOrderRes->sOrderDeliveryDate,
            "orderComment" => $oOrderRes->sOrderComment,

            "orderRecepientLegalEntity" => $oOrderRes->iLegalEntity,
            "orderRecepientEmail" => $oOrderRes->sOrderRecipientEmail,
            "orderRecepientPhone" => $oOrderRes->sOrderRecipientPhone,
            "orderRecepientDocumentNumber" => $oOrderRes->sOrderRecipientDocumentNumber,
            "orderRecepientDocumentTypeId" => $oOrderRes->iOrderRecipientDocumentTypeId,
            "orderRecepientFirstName" => $oOrderRes->sOrderRecipientFirstName,
            "orderRecepientSecondName" => $oOrderRes->sOrderRecipientSecondName,
            "orderRecepientLastName" => $oOrderRes->sOrderRecipientLastName,
            "orderCompanyName" => $oOrderRes->sOrderCargoName,
            "orderRecepientCompanyFormId" => $oOrderRes->iOrderRecipientCompanyFormId,
            "orderRecepientInn" => $oOrderRes->iOrderRecipientRecipientInn,
            "orderTemperatureModeName" => $oOrderRes->sOrderTemperatureModeName,
            "orderDangerClassName" => $oOrderRes->sOrderDangerClassName,
            "orderTemperatureModeId" => $oOrderRes->sOrderTemperatureModeId,
            "orderDangerClassId" => $oOrderRes->sOrderDangerClassId,
            "orderGoodsName" => $oOrderRes->sOrderGoodsName,
            "paymentTypeName" => $oOrderRes->sPaymentTypeName,
            "id" => intval($oOrderRes->iOrderID)

        );
        $aResult = array_merge($aResultTmp, (array)$oOrderRes->oSerializedData);
        $aResults[] = $aResult;
    }

    $aResultDataSet = array(
        "success" => "success",
        "totalCount" => intval($iOrdersCount),
        "data" => $aResults
    );

    http_response_code(200);
    header('Content-Type: application/json');
    // echo '<pre>'; print_r(json_encode($aResultDataSet)); die;
    print(json_encode($aResultDataSet));

}


function show_user()
{
    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "./service/user_class.php";
    require_once "./service/auth.php";
    require_once "./service/finance_class.php";

// check POST body   "id":"122",
    $json = '{"data":
        {  
            "id":"0",
            "defaultPhone":"79322077777",
            "defaultLang":"ru" 
        }
    }';

    $oPOSTData = json_decode($json);
    /*
        $iUserID = CheckAuth();
        if ($iUserID === false)
            DropWithUnAuth();
    */

    $iUserID = $oPOSTData->data->id;




// check if data enough
    if (!isset($oPOSTData->data->id) and !isset($oPOSTData->data->defaultEmail)
        //and !isset($oPOSTData->data->vkID)
        and !isset($oPOSTData->data->defaultPhone)
        //and !isset($oPOSTData->data->passportNum)
    )
    {
        DropWithBadRequest("Not enough parameters");
    }

// check if auth presence
    $bAuth = true;
#if ((isset($oPOSTData->auth->login)) and (isset($oPOSTData->auth->password)))
#	$bAuth = true;
    $host = 'localhost';
// connect to DB
    $mysqli = new mysqlii($host,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
    /*    if ($mysqli->connect_errno)
            DropWithServerError();
    */
// create User object
    $cUser = new User();

// trying to authenticate
    if ($bAuth)
    {
        $iAuth = $cUser->UserFromID($mysqli, $iUserID);

        if (($iAuth != USER_OK) or (!$cUser->objectOK))
            $bAuth = false;
        else
            $bAuth = true;
    }

    $bShowAll = false;

// if we're authenticated
    if ($bAuth)
    {

        // check for admin rights
        if ($cUser->isAdmin)
            $bShowAll = true;
        else
        {
            // no admin rights, check if requested data equals to us
            if (isset($oPOSTData->data->id) and ($oPOSTData->data->id == $cUser->userID) and ($oPOSTData->data->id != 0))
                $bShowAll = true;

            //if (isset($oPOSTData->data->vkID) and ($oPOSTData->data->vkID == $cUser->userVKID) and ($oPOSTData->data->vkID != ""))
            //$bShowAll = true;

            if (isset($oPOSTData->data->defaultEmail) and ($oPOSTData->data->defaultEmail == $cUser->userEMail) and
                ($oPOSTData->data->defaultEmail != ""))
                $bShowAll = true;

            if (isset($oPOSTData->data->defaultPhone) and ($oPOSTData->data->defaultPhone == $cUser->userPhone) and
                ($oPOSTData->data->defaultPhone != ""))
                $bShowAll = true;


            //if (isset($oPOSTData->data->passportNum) and ($oPOSTData->data->passportNum == $cUser->userPassport) and
            //($oPOSTData->data->passportNum != 0))
            //$bShowAll = true;
        }
    }

    $cSearchUser = new User();
    if (isset($oPOSTData->data->id) and ($oPOSTData->data->id > 0))
        $iSearchResult = $cSearchUser->UserFromID($mysqli,$oPOSTData->data->id);
    else {
        $iSearchResult = $cSearchUser->UserFromSearch($mysqli, $oPOSTData->data->defaultPhone, $oPOSTData->data->defaultEmail
        //$oPOSTData->data->vkID,($bAuth ? $oPOSTData->data->passportNum : 0)
        );

    }



    if (($iSearchResult != USER_OK) or (!$cSearchUser->objectOK))
        DropWithNotFound();

    $bShowAll =true;
// compile out data
    if ($bShowAll)
    {
        $oFinance = new Finance();

        $aResultDataset = array(
            "id" => $cSearchUser->userID,
            "formerlyName_i" => $cSearchUser->userName,
            "formerlyName_o" => $cSearchUser->userSecondName,
            "formerlyName_f" => $cSearchUser->userLastName,
            "defaultAddress" => $cSearchUser->userAddress,
            "defaultPhone" => $cSearchUser->userPhone,
            "defaultEmail" => $cSearchUser->userEMail,
            "defaultLang" => $cSearchUser->userDefLang,
            "defaultCurr" => $cSearchUser->userDefCurr,
            "defaultWUnit" => $cSearchUser->userDefWUnit,
            "defaultVUnit" => $cSearchUser->userDefVUnit,
            "balance" => floatval($oFinance->UserBalance($mysqli,$cSearchUser->userID)),
            "isAdmin" => $cSearchUser->isAdmin,
            "isJur" => $cSearchUser->userIsJur,
            "passportNumber" => $cSearchUser->userPassportNum,
            "passportGivenName" => $cSearchUser->userPassportGivenName,
            "passportGivenDate" => $cSearchUser->userPassportGivenDate,
            "INN"  => $cSearchUser->userINN,
            "jurForm" => $cSearchUser->userJurForm,
            "OGRN" => $cSearchUser->userOGRN,
            "KPP" => $cSearchUser->userKPP,
            "jurName" => $cSearchUser->userJurName,
            "jurAddress" => $cSearchUser->userJurAddress,
            "mailAddress" => $cSearchUser->userIsJur,
            "accNumber" => $cSearchUser->userAccNumber,
            "BIK" => $cSearchUser->userBIK,
            "chiefName" => $cSearchUser->userChiefName,
            "jurBase" => $cSearchUser->userJurBase
        );


    }
    else
        DropWithNotFound();

    $aResultOut = array(
        "success" => "success",
        "data" => $aResultDataset
    );

    http_response_code(200);
    header('Content-Type: application/json');
    echo '<pre>'; print_r(json_encode($aResultOut)); die();
    print(json_encode($aResultOut));

}

function create_pdf()
{
    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "./service/user_class.php";
    require_once "./service/auth.php";
    require_once "./service/order_class.php";

// check POST body
    $json = '{"data":{"orderID":1347}}';

    $oPOSTData = json_decode($json);


    $host = 'localhost';

    $mysqli = new mysqlii($host,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);


    $iOrderID = intval($oPOSTData->data->orderID);


    require_once 'email_template/ttn_template.php';
    $className='DELLIN';


    $html = Template::GetTtn($mysqli,$iOrderID,"RU",
        "modules/logo_images/logo-dellin.png");

    echo $html; die;

    include("service/mpdf60/mpdf.php");

    $mpdf=new mPDF();

    $mpdf->SetDisplayMode('fullpage');

    $mpdf->WriteHTML($html);

//    $mpdf->Output();

    header('Content-Type: application/pdf');
    $mpdf->Output('output.pdf', 'I');

    /*
       $file_mail_name="cargo_order_$iOrderID.pdf";
        $content = $mpdf->Output($file_mail_name,"S");

        $fio =$recipient_first_name.' '.$recipient_second_name.' '.$recipient_last_name;

        $recipient_email = 'andrii.sokliuk@gmail.com';


        $filename = $file_mail_name;
        $message = "Заказ $order_number";

        $uid = md5(uniqid(time()));
        $header = "From: ".HOST_REG_FROM." <".MAIL_REG_FROM.">\r\n";
        $header .= "To: ".$recipient_email." <".$recipient_email.">\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
        $header .= "This is a multi-part message in MIME format.\r\n";
        $header .= "--".$uid."\r\n";
        $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
        $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $header .= $message."\r\n\r\n";
        $header .= "--".$uid."\r\n";
        $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
        $header .= "Content-Transfer-Encoding: base64\r\n";
        $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
        $header .= $content."\r\n\r\n";
        $header .= "--".$uid."--";

        date_default_timezone_set("UTC");
        $mailSMTP = new SendMailSmtpClass(MAIL_REG_FROM, PASS_REG_FROM, MAILER, HOST_REG_FROM, MAILER_PORT);

        $mailResult = $mailSMTP->sendWithAttach($recipient_email, MAIL_SUBJECT, $header);

        if (!($mailResult === true))
            $iNewUserResult = USER_DB_ERROR;
    */
}

function create_pdf2()
{
    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "./service/user_class.php";
    require_once "./service/auth.php";
    require_once "./service/order_class.php";

// check POST body
    $json = '{"data":{"orderID":1406}}';

    $oPOSTData = json_decode($json);


    $host = 'localhost';

    $mysqli = new mysqlii($host,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);


    $iOrderID = intval($oPOSTData->data->orderID);


    require_once 'documents/order_bill.php';
    $className='DELLIN';


    $html = BillTemplate::GetBill($mysqli,$iOrderID,"RU");

    echo $html; die;

    include("service/mpdf60/mpdf.php");

    $mpdf=new mPDF();

    $mpdf->SetDisplayMode('fullpage');

    $mpdf->WriteHTML($html);

//    $mpdf->Output();

    header('Content-Type: application/pdf');
    $mpdf->Output('output.pdf', 'I');

}


function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    if (mail($mailto, $subject, "", $header)) {
        echo "mail send ... OK"; // or use booleans here
    } else {
        echo "mail send ... ERROR!";
    }
}

function mail_for_customer()
{
    require_once "./service/SendMailSmtpClass.php";

    echo 'test';

    $sMailText = "Test";

    $sMailToHeader = "To: andrii.sokoliuk@gmail.com" . "\r\n";

    date_default_timezone_set("UTC");
    $mailSMTP = new SendMailSmtpClass(MAIL_REG_FROM, PASS_REG_FROM, MAILER, HOST_REG_FROM, MAILER_PORT);
    $mailResult = $mailSMTP->send("andrii.sokoliuk@gmail.com",
        MAIL_REST_SUBJECT,
        $sMailText,
        MAIL_REST_HEADERS . $sMailToHeader);




    $message = "Заказ тестовый";

    $recipient_email = 'andrii.sokoliuk@gmail.com';

    $uid = md5(uniqid(time()));
    $header = "From: ".HOST_REG_FROM." <".MAIL_REG_FROM.">\r\n";
    $header .= "To: ".$recipient_email." <".$recipient_email.">\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    //$header .= "--".$uid."\r\n";
    //$header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
    //$header .= "Content-Transfer-Encoding: base64\r\n";
    //$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    //$header .= $content."\r\n\r\n";
    //$header .= "--".$uid."--";

    date_default_timezone_set("UTC");
    $mailSMTP = new SendMailSmtpClass(MAIL_REG_FROM, PASS_REG_FROM, MAILER, HOST_REG_FROM, MAILER_PORT);

    $result = $mailSMTP->send($recipient_email,$message,$message,'');
    //$mailResult = $mailSMTP->sendWithAttach($recipient_email, MAIL_SUBJECT, $header);

    var_dump($result);

}

function test()
{
    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "./service/order_class.php";

    $host = 'localhost';

    $db_name = "cargo_old";

    // $mysqli = new mysqlii($host,DB_RW_LOGIN,DB_RW_PASSWORD,$db_name);


    $mysqli = new mysqlii($host,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

    $sQuery = " SELECT  id,serialized_fields
                FROM orders
                where serialized_fields is not null and serialized_fields !=''";

    $oSearchResult =  $mysqli->query($sQuery);
    //echo $sQuery;
    if ($mysqli->affected_rows > 0) {
        while ($oRow = $oSearchResult->fetch_assoc()) {
            if(!isset($oRow["serialized_fields"])) continue;
            $order = json_decode($oRow["serialized_fields"]);
            // var_dump($oRow["serialized_fields"]);
            // echo "<pre>"; print_r($oRow["serialized_fields"]); echo "</pre><br>";
            //die;
            $oOrder = new Order();

            $recipient=$oOrder->GetRecipientBy(
                $mysqli,0,
                $order->cargoRecipientPhone,
                $order->cargoRecipientEmail,
                $order->cargoRecipientUser,
                $order->cargoRecipientUser,
                $order->cargoRecipientUser,
                1,
                intval($order->cargoRecipientPassport),
                '',1,1
            );

            // echo "<br><pre>"; print_r($recipient); echo "</pre><br>";

            if($recipient->status == 'ok') {

                $updateStr = "UPDATE orders
                         SET recipient_id = " . $recipient->id . "
                         WHERE id = " . $oRow["id"];

                //  echo $updateStr, '<br>';

                $mysqli->query($updateStr);

                // echo $recipient->id,'<br>';

            }


            //  echo "<br><pre>"; print_r($recipient->id); echo "</pre><br>";
            // die();
            /*  echo $oRow["id"],
                  ' ', $oRow["rcptUser"],
                  ' ', $oRow["rcptPassport"],
                  ' ', $oRow["rcptPassportGivenDate"],
                  ' ', $oRow["rcptPhone"],
                  ' ', $oRow["rcptEmail"],
                  ' ', $oRow["rcptAddress"],
                  ' ', $oRow["companyInternalNumber"],

              '<br>';
            */
            //$oOrder = json_decode($oRow["serializedFields"]);
            //  echo "<pre>"; print_r($oOrder); echo "</pre><br>";
        }
    }
}

function CallPOSTJSON($sURL, $oJSON)
{
    $oReq = curl_init($sURL);
    curl_setopt_array($oReq, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => json_encode($oJSON)
    ));

    $sAnswer = curl_exec($oReq);
    return json_decode($sAnswer);

}

function Arr2Str2($strpre,$array) {
    $sTmp = '';

    if(count($array)>0)
        foreach($array as $key => $element) {
            $sTmp .= $key . ': ';
            if (is_array($element) or is_object($element)) {
                $sTmp .= Arr2Str('',$element);
            }
            else {
                if ($element != '') {
                    $sTmp .= $element . '; ';
                }
            }
        }

    if ($sTmp != '')
        $sTmp .= $strpre . $sTmp;

    return $sTmp;
}

?>
