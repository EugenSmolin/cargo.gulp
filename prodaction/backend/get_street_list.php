<?php

$city = $_GET["city"];
$street = $_GET["street"];

if(isset($street) && isset($city))
{
   $cityName = urldecode($city);

    $cityID = GetCityIDFromDB($cityName);

    if ($cityID=='')
    {
        header('HTTP/1.0 400 Bad Request');
        exit(1);
    }


    $resArr = GetStreet($cityID,$street);
  //  $sURL = 'https://www.dellin.ru/api/cities/'.$cityID.'/streets/search.json?q=' . urlencode($street);

//    $cityID = GetCityIDFromUrl($city);

    echo $cityID;

    http_response_code(200);
    header('Content-Type: application/json');
    print(json_encode($resArr));
}
else {
    header('HTTP/1.0 404 Not Found');
    exit(1);
}

function GetCityIDFromUrl($cityName)
{
    $sUrl = "https://api.dellin.ru/v2/public/kladr.json";
    $json = array("appkey"=>"449A2F0C-9EA6-11E5-A3FB-00505683A6D3",
        "q"=>$cityName,
        "limit"=>"1");
    $res = CallPOSTJSON($sUrl,$json);

    var_dump($res->cities[0]->code);

    if(isset($res->cities[0]->code))
    {
        return $res->cities[0]->code;
    }
    return '';
}

function GetCityIDFromDB($cityName)
{
    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "./services.php";
    $_name = trim(mb_ereg_replace('(\s+г|\s+г\.|г\.\s+|\.|\s+пгт|пгт\s+|\s+п|п\s+|\s+рп|рп\s+)','',$cityName));

    $_name = trim(mb_ereg_replace('(\s+с|\s+с\.|\s+д|\s+ст-ца)','',$_name));
    $_name = mb_ereg_replace('\(.+\)','',$_name);

    $mysqlHandle = new mysqlii(DB_HOST, LANG_DB_LOGIN, LANG_DB_PASSWORD, KLADR_DB_NAME);
    //var_dump($mysqlHandle); die;
    $q="SELECT dk.code FROM dellin_kladr dk WHERE  
        UPPER(search) = '".$mysqlHandle->real_escape_string(mb_strtoupper($_name))."'
        ORDER BY (char_length(code) - char_length(replace(code,'0',''))) DESC
        LIMIT 1";

    $resultAddr = $mysqlHandle->query($q);
    //var_dump($resultAddr);
    if ($resultAddr->num_rows > 0)
    {
        $col = $resultAddr->fetch_assoc();
        //echo $col['code'];
        return $col['code'];
    }

    $mysqlHandle->close();

    return '';
}

function GetStreet($cityID,$street)
{
    require_once "./service/config.php";
    require_once "./service/service.php";
    require_once "./services.php";

    $oRetVal = array();

    $mysqlHandle = new mysqlii(DB_HOST, LANG_DB_LOGIN, LANG_DB_PASSWORD, KLADR_DB_NAME);
    //var_dump($mysqlHandle); die;
    $q=	 "SELECT  dks.code,
            CONCAT(SPLIT_STRING(dks.name,' ',LENGTH(dks.name) -
            LENGTH(REPLACE(dks.name, ' ', ''))+1),' ',dks.searchString)  'street' ,
               dk.name cityName,
             dks.searchString 'label' FROM dellin_kladr_street dks
            JOIN dellin_kladr dk ON dks.cityID = dk.cityID
             WHERE dk.code = '".$cityID."'
            AND UPPER(`searchString`) LIKE '".$mysqlHandle->real_escape_string(mb_strtoupper($street))."%'
             ORDER BY (char_length(dks.code) - char_length(replace(dks.code,'0','')))
             DESC limit 10";

    $resultAddr = $mysqlHandle->query($q);
    //var_dump($resultAddr);
    $result = array();
    if ($resultAddr->num_rows > 0)
    {
        while ($col = $resultAddr->fetch_row())
        {
            $oRetVal['street'] = $col[1];
            $oRetVal['fullName'] = $col[1].' '. $col[2];
            $oRetVal['code'] = $col[0];
            $oRetVal['label'] = $col[3];
            $oRetVal['cityName'] = $col[2];
            $result[]=$oRetVal;
        }
        return $result;
    }

    $mysqlHandle->close();

    return '';
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

    if(IS_DEBUG) { echo '<br>'.$sURL.'<br>'; echo json_encode($oJSON),'<br><br>';}

    return json_decode($sAnswer);

}

?>