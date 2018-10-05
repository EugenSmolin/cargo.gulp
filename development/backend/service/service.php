<?php

function DropWithUnAuth()
    {
		http_response_code(401);
		
		$oAnswer = array(
            	"status" => "bad",
				"failReason" => "Authorization required"
			);
			
		header('Content-Type: application/json');
		print(json_encode($oAnswer));
		exit(0);
    }

function DropWithForbidden()
    {
		http_response_code(403);
		
		$oAnswer = array(
            	"status" => "bad",
				"failReason" => "Forbidden"
			);
			
		header('Content-Type: application/json');
		print(json_encode($oAnswer));
		exit(0);
    }

function DropWithServerError($sText = "",$lang="ru")
    {
		http_response_code(500);

		$lang = mb_strtolower($lang);

		$answer1 = __GetAllTranslations("Server error","en")[$lang];
        $answer2 = __GetAllTranslations($sText,"en")[$lang];

        $answer1 = $answer1 != ""? $answer1:"Server error";
        $answer2 = $answer2 != ""? $answer2:$sText;

		$oAnswer = array(
				"status" => "bad",
				"failReason" => $answer1.", ".$answer2
			);
		
		header('Content-Type: application/json');
		print(json_encode($oAnswer));
		exit(0);
    }

function DropWithBadRequest($sText = "",$lang="ru")
    {
		http_response_code(400);

        $lang = mb_strtolower($lang);

        $answer1 = __GetAllTranslations("Bad request","en")[$lang];
        $answer2 = __GetAllTranslations($sText,"en")[$lang];

        $answer1 = $answer1 != ""? $answer1:"Bad request";
        $answer2 = $answer2 != ""? $answer2:$sText;
		
		$oAnswer = array(
            	"status" => "bad",
           		"failReason" => $answer1.", ".$answer2
			);
		
		header('Content-Type: application/json');
		print(json_encode($oAnswer));
		exit(0);
    }

function DropWithBadMsg($sText = "",$lang="ru")
{
    http_response_code(400);

    $lang = mb_strtolower($lang);

    $answer1 = __GetAllTranslations("Bad request","en")[$lang];
    $answer2 = __GetAllTranslations($sText,"en")[$lang];

    $answer1 = $answer1 != ""? $answer1:"Bad request";
    $answer2 = $answer2 != ""? $answer2:$sText;

    $oAnswer = array(
        "status" => "bad",
        "failReason" => $answer1.", ".$answer2
    );

    header('Content-Type: application/json');
    print(json_encode($oAnswer));
    exit(0);
}

function DropWithBadValidation($sText = "",$lang="ru")
{
    http_response_code(400);

    $lang = mb_strtolower($lang);

    $answer2 = __GetAllTranslations($sText,"en")[$lang];

    $answer2 = $answer2 != ""? $answer2:$sText;

    $oAnswer = array(
        "status" => "bad",
        "failReason" => $answer2
    );

    header('Content-Type: application/json');
    print(json_encode($oAnswer));
    exit(0);
}

function DropWithCompanyErrorResponse($companyId, $sText = "",$admin_message,
									  $request_url="",$request_json="", $lang="ru")
{
    require_once("mail_class.php");

    $mail = new Mail();

    $mail->SendCompanyErrorToAdmin($companyId,$admin_message,$request_url,$request_json);

    http_response_code(400);

    $lang = mb_strtolower($lang);

    $answers = array();

    $answers = __GetAllTranslations($sText,$lang);

    $answer = $answers[$lang] != ""? $answers[$lang]:$sText;

    $oAnswer = array(
        "failReason" => $answer,
        "failReasons" => $answers,
        "status" => "bad"
    );

    header('Content-Type: application/json');
    print(json_encode($oAnswer));
    exit(0);

}

function DropWithBadCompanyResponse($sText = "",$lang="ru")
{
    http_response_code(400);

    $lang = mb_strtolower($lang);

    $answers = array();

    $answers = __GetAllTranslations($sText,$lang);

    $answer = $answers[$lang] != ""? $answers[$lang]:$sText;

    $oAnswer = array(
        "failReason" => $answer,
        "failReasons" => $answers,
        "status" => "bad"
    );

    header('Content-Type: application/json');
    print(json_encode($oAnswer));
    exit(0);
}

function DropWithNotFound($sText = "",$lang="ru")
    {
		http_response_code(404);

        $lang = mb_strtolower($lang);

        $answer1 = __GetAllTranslations("Not Found","en")[$lang];
        $answer2 = __GetAllTranslations($sText,"en")[$lang];

        $answer1 = $answer1 != ""? $answer1:"Server error";
        $answer2 = $answer2 != ""? $answer2:$sText;

        $oAnswer = array(
            "status" => "bad",
            "failReason" => $answer1.", ".$answer2
        );

		header('Content-Type: application/json');
		print(json_encode($oAnswer));
		exit(0);
    }

function ReturnSuccess($sJson = "")
    {
		http_response_code(200);
		
		$oAnswer = array(
				"success" => "success"
			);
			
		if (isset($sJson) and ($sJson != ""))
			$oAnswer = array_merge($oAnswer, $sJson);
		
		header('Content-Type: application/json');
		print(json_encode($oAnswer));
		exit(0);
    }

function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i)
		{
			$str .= $keyspace[rand(0, $max)];
		}
    return $str;
}

function array_intify(&$inputArray)
	{
		foreach($inputArray as $arrKey => $arrayElement)
			{
				if (is_array($arrayElement))
					$inputArray[$arrKey] = array_intify($arrayElement);
				else
					$inputArray[$arrKey] = intval($arrayElement);
			}
	}

function num2str($num) {
	$nul='ноль';
	$ten=array(
		array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
		array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
	);
	$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
	$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
	$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
	$unit=array( // Units
		array('копейка' ,'копейки' ,'копеек',	 1),
		array('рубль'   ,'рубля'   ,'рублей'    ,0),
		array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
		array('миллион' ,'миллиона','миллионов' ,0),
		array('миллиард','милиарда','миллиардов',0),
	);
	//
	list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
	$out = array();
	if (intval($rub)>0) {
		foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
			if (!intval($v)) continue;
			$uk = sizeof($unit)-$uk-1; // unit key
			$gender = $unit[$uk][3];
			list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
			// mega-logic
			$out[] = $hundred[$i1]; # 1xx-9xx
			if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
			else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
			// units without rub & kop
			if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
		} //foreach
	}
	else $out[] = $nul;
	$out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
	$out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
	return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

/**
 * Склоняем словоформу
 * @ author runcore
 */
function morph($n, $f1, $f2, $f5) {
	$n = abs(intval($n)) % 100;
	if ($n>10 && $n<20) return $f5;
	$n = $n % 10;
	if ($n>1 && $n<5) return $f2;
	if ($n==1) return $f1;
	return $f5;
}

$monthes = array(
    1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
    5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
    9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
);


function AddPromoBonus($calcResult,$promo,$lang,$currency)
{
    $percent = 0;
    $discountDescription = '';
    $oHandler = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

    // Check payment discount
    require_once "./service/user_discount_class.php";

    $obj = new  UserDiscount();

    $res = $obj->CheckPromo($oHandler,$promo);

    if($res->is_active)
    {
        $percent = $res->value;
        $discountDescription = "Скидка по промокоду ".round($percent,2)." % от стоимости доставки";
    }
    else
	{
        $percent = 0;
        $discountDescription ="Скидка отсутствует" ;
	}

	// End payment discount

    return AddBonus($calcResult,$percent,$discountDescription,$lang,$currency);
}

function AddCountryBonus($calcResult,$cargoFromCountry,$cargoToCountry,$lang,$currency)
{
    $percent = 0;
    $discountDescription = '';
    $oHandler = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

    // Check payment discount
    require_once "./service/user_discount_class.php";

    $obj = new  UserDiscount();

    $res = $obj->CheckDiscountByCountry($oHandler,$cargoFromCountry,$cargoToCountry);

    if($res->is_active)
    {
        $percent = $res->value;
        $discountDescription = "Скидка по '".$res->name."' ".round($percent,2)." % от стоимости доставки";
    }
    else
    {
        $percent = 0;
        $discountDescription ="Скидка отсутствует" ;
    }

    // End payment discount

    return AddBonus($calcResult,$percent,$discountDescription,$lang,$currency);

}

function AddUserBonus($calcResult,$userId,$companyId,$lang,$currency)
{
    $percent = 0;
    $discountDescription = '';
    $oHandler = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

    // Check payment discount
    require_once "./service/user_discount_class.php";

    $obj = new  UserDiscount();

    $res = $obj->CheckDiscountByUser($oHandler,$userId,$companyId);

    if($res->is_active)
    {
        $percent = $res->value;
        $discountDescription = "Скидка для пользователя ".round($percent,2)." % от стоимости доставки";
    }
    else
    {
        $percent = 0;
        $discountDescription ="Скидка отсутствует" ;
    }

    // End payment discount

    return AddBonus($calcResult,$percent,$discountDescription,$lang,$currency);

}


function AddPaymentBonus($calcResult,$companyId,$paymentType,$lang,$currency)
{
    $percent = 0;
    $discountDescription = '';
    $oHandler = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

    // Check payment discount

    $sqlQuery="
				SELECT ptd.discount_value as percent,
			if(ptd.discount_value=0,'Скидка не предусмотрена',concat('Скидка ',cast(ptd.discount_value as char),' % от стоимости доставки')) as description
            FROM ".DB_PAYMENT_TYPE_DISCOUNT." ptd 
      		WHERE ptd.payment_type_id = ".$paymentType." and ptd.company_id = ".$companyId."
			LIMIT 1;";

    $oRes = $oHandler->query($sqlQuery);

    if(!IS_PRODUCTION) echo $sqlQuery,'<br>';

    if($oHandler->affected_rows > 0)
    {
        $oRow = $oRes->fetch_assoc();
        $percent = floatval($oRow['percent']);
        $discountDescription = strval($oRow['description']);
    }
    else
    {
        $percent = 0;
        $discountDescription ="Скидка отсутствует" ;
    }


    // End payment discount

    $desc = array();
    if(!IS_PRODUCTION)
    {
        $desc[$lang]=$discountDescription;
    }
    else {
        $desc = __GetAllTranslations($discountDescription, $lang);
    }

    $methods =array();
    if($calcResult["failReason"]==""  )
    {
        foreach ($calcResult["methods"] as $item)
        {
            //echo "<br> Original Price:",$item ["calcResultPrice"],'<br> percent:',$percent,'<br>';

            if($percent>0)
            {
                $discountPercent=$percent/100;
                $discountPrice = $item ["calcResultPrice"]*$discountPercent;
                $discountPrice =-round($discountPrice,2);
            }
            else
            {
                $discountPrice = 0;
            }
            $discount = [
                'description'=>$desc[$lang],
                'descriptions'=>$desc,
                'price'=>$discountPrice,
                'prices'=>GetConvertedPrices(round($discountPrice,2),$currency)
            ];
            $totalPrice = $item["calcResultPrice"] + $discountPrice;
            $item["calcResultPrice"]=round($totalPrice,2);

            $item["calcResultPrices"]=GetConvertedPrices(round($totalPrice,2),$currency);
            $item['discount']=$discount;
            //var_dump($item); die;
            $methods[]=$item;
            //echo "<br> Last Price:",$item ["calcResultPrice"],'<br>';
            //echo "<br> Discount Price:",round($discountPrice,2),'<br>';
        }
        //var_dump($methods); die;
        $calcResult["methods"]=$methods;
    }
    return $calcResult;
}

function   AddBonus($calcResult,$percent,$discountDescription,$lang,$currency)
{
    $desc = array();
    if(!IS_PRODUCTION)
    {
        var_dump($discountDescription);
        $desc[$lang]=$discountDescription;
    }
    else {
        $desc = __GetAllTranslations($discountDescription, $lang);
    }
    $methods =array();
    if($calcResult["failReason"]==""  )
    {
        foreach ($calcResult["methods"] as $item)
        {
            if($percent>0)
            {
                $discountPercent=$percent/100;
                $discountPrice = $item ["calcResultPrice"]*$discountPercent;
                $discountPrice =-round($discountPrice,2);
            }
            else
            {
                $discountPrice = 0;
            }
            $discount = [
                'description'=>$desc[$lang],
                'descriptions'=>$desc,
                'price'=>$discountPrice,
                'prices'=>GetConvertedPrices(round($discountPrice,2),$currency)
            ];
            $totalPrice = $item["calcResultPrice"] + $discountPrice;
            $item["calcResultPrice"]=round($totalPrice,2);

            $item["calcResultPrices"]=GetConvertedPrices(round($totalPrice,2),$currency);
            $item['discount']=$discount;
            $methods[]=$item;
        }
        $calcResult["methods"]=$methods;
    }
    return $calcResult;
}

function AddIntercity($oRetVal,$cityFrom,$cityTo,$currency)
{
    $price = $oRetVal["methods"]["calcResultPrice"];
    $methods =array();
	if($oRetVal["failReason"]==""  ) {
		foreach ($oRetVal["methods"] as $item)
		{
            $price = $item["calcResultPrice"];
			$item["intercity"] =
				array(
					"1"=>[
						"description" => $cityFrom . "-" . $cityTo,
						"price" => $price,
						"prices" => GetConvertedPrices($price, $currency)
					]
				);
            $methods[]=$item;
		}
        $oRetVal["methods"]=$methods;
	}
    return $oRetVal;
}

class mysqlii extends mysqli {

    public function __construct($param1,$param2,$param3,$param4) {
	parent::__construct($param1,$param2,$param3,$param4);
	$this->query("SET NAMES utf8 COLLATE utf8_unicode_ci");
	$this->set_charset("utf8");
	return $this;
    }
    
    public function fff() {
	die('fvfvfvf');
    }

}

?>
