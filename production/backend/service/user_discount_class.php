<?php

require_once "./service/config.php";
require_once "./service/service.php";

class UserDiscount
{
    public $value = 0.0;
    public $is_active = false;
    public $status = "";
    public $error_message ="";
    public $name = "";

    public function CheckPromo($oDBHandler,$userID,$promo,$countryFrom,$countryTo, $companyID)
    {
         self::CheckDiscountsEndDate($oDBHandler);

         $sSearchQuery = "SELECT if(
                            (SELECT 1 FROM ".DB_USER_DISCOUNTS_USED_TABLE." WHERE user_id = $userID and 
                            user_discounts_id =  ud.id) IS NULL AND
                            
                            (SELECT if((SELECT count(company_id)
                                FROM ".DB_USER_DISCOUNT_COMPANIES."
                                WHERE discount_id = ud.id)>0, 
                                (SELECT count(discount_id)
                                FROM ".DB_USER_DISCOUNT_COMPANIES."
                                WHERE discount_id = ud.id AND company_id = $companyID 
                                LIMIT 1)
                                ,1 )) AND
                            
                            (SELECT if((SELECT count(country_code)
                                FROM ".DB_USER_DISCOUNT_COUNTRIES."
                                WHERE discount_id = ud.id)>0, 
                                (SELECT count(discount_id)
                                FROM ".DB_USER_DISCOUNT_COUNTRIES."
                                WHERE discount_id = ud.id AND country_code IN ('$countryFrom','$countryTo') 
                                LIMIT 1)
                                ,1 )) AND
                            
                              ud.is_active=1
                            
                            , 1,0) is_active,  
                            ud.value
                             FROM  ".DB_USER_DISCOUNTS_TABLE." ud 
                             WHERE  ud.cat_id = 2 AND ud.is_active =1 AND ud.promo = '$promo' ";

        $oSearchResult = $oDBHandler->query($sSearchQuery);
        if(IS_DEBUG)echo $sSearchQuery,'<br>';
       // echo  $sSearchQuery,'<br>', $oDBHandler->error;


        if ($oDBHandler->error)
        {
            $this->status = 'error';
            $this->error_message = USER_DB_ERROR;
            return $this;
        }

        if ($oDBHandler->affected_rows > 0)
        {
            $oRow = $oSearchResult->fetch_assoc();
            $this->is_active = intval($oRow["is_active"])==1? true : false;
            $this->value = floatval($oRow["value"]);
            $this->status = 'ok';
            $this->error_message = '';
        }

        return $this;

    }

    public function FinishDiscount($oDBHandler,$discountId)
    {
        $sSearchQuery = "UPDATE ".DB_USER_DISCOUNTS_TABLE."
                            SET
                                is_active=0
                        WHERE
                           id = ".$discountId;

        $oDBHandler->query($sSearchQuery);
    }

    public function FinishPromoDiscount($oDBHandler,$userID,$promo)
    {
        $sSearchQuery = "INSERT INTO user_discounts_used
                            (user_id, user_discounts_id)
                            VALUES ( $userID , (SELECT   ud.id
                        FROM  user_discounts ud 
                        WHERE  ud.cat_id = 2 and 
                        ud.is_active =1 and ud.promo = '$promo' LIMIT 1))";

        $oDBHandler->query($sSearchQuery);
    }

    public function CheckDiscountsEndDate($oDBHandler)
    {
        $sSearchQuery = "UPDATE ".DB_USER_DISCOUNTS_TABLE."
                            SET
                                is_active=0
                        WHERE 
                           is_active = 1 
                            and 
                           date_end < now()
                            and 
                           is_forever = 0";

        $oDBHandler->query($sSearchQuery);
    }

    public function CheckDiscountByUser($oDBHandler,$userId,$companyId)
    {
        self::CheckDiscountsEndDate($oDBHandler);

        $sSearchQuery = "SELECT ud.name, ud.value
                        FROM user_discounts ud
                        INNER JOIN discount_companies dc
                        ON dc.discount_id = ud.id
                        WHERE ud.cat_id = 3
								and
								ud.user_id = $userId
								and
								dc.company_id = $companyId
                        order by ud.value desc
                        LIMIT 0, 1";

        $oSearchResult = $oDBHandler->query($sSearchQuery);
        //if(IS_DEBUG)echo $sSearchQuery,'<br>';
        //echo  $sSearchQuery,'<br>', $oDBHandler->error;

        if ($oDBHandler->error)
        {
            $this->status = 'error';
            $this->error_message = USER_DB_ERROR;
            return $this;
        }

        //var_dump($oDBHandler);

        if ($oDBHandler->affected_rows > 0)
        {
            $oRow = $oSearchResult->fetch_assoc();

            $this->is_active = true;
            $this->value = floatval($oRow["value"]);
            $this->status = 'ok';
            $this->error_message = '';
        }
        else
        {
            $this->is_active = false;
            $this->status = 'ok';
            $this->error_message = '';
        }

        return $this;
    }

    public function CheckDiscountByCountry($oDBHandler,$country_from,$country_to)
    {
        self::CheckDiscountsEndDate($oDBHandler);

        $sSearchQuery = "SELECT ud.name, ud.value
                        FROM user_discounts ud
                        INNER JOIN discount_locations dl
                        ON dl.discount_id = ud.id
                        WHERE ud.is_active = 1 and  ud.cat_id = 1
								and
								dl.country_code in ('$country_from','$country_to')
								order by ud.value desc
                        LIMIT 0, 1";

        $oSearchResult = $oDBHandler->query($sSearchQuery);
        //if(IS_DEBUG)echo $sSearchQuery,'<br>';
        //echo  $sSearchQuery,'<br>', $oDBHandler->error;

        if ($oDBHandler->error)
        {
            $this->status = 'error';
            $this->error_message = USER_DB_ERROR;
            return $this;
        }

        //var_dump($oDBHandler);

        if ($oDBHandler->affected_rows > 0)
        {
            $oRow = $oSearchResult->fetch_assoc();
            $this->name = $oRow["name"];
            $this->is_active = true;
            $this->value = floatval($oRow["value"]);
            $this->status = 'ok';
            $this->error_message = '';
        }
        else
        {
            $this->is_active = false;
            $this->status = 'ok';
            $this->error_message = '';
        }

        return $this;
    }

    public function GetDiscount($oHandler,$paymentType,$companyId)
{
    //$oHandler = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);
    $sqlQuery="
			SELECT ptd.discount_value as percent,
			if(ptd.discount_value=0,'Скидка не предусмотрена',concat('Скидка ',cast(ptd.discount_value as char),' % от стоимости доставки')) as description
            FROM ".DB_PAYMENT_TYPE_DISCOUNT." ptd
             WHERE ptd.payment_type_id = ".$paymentType." and ptd.company_id = ".$companyId."
			LIMIT 1;
			";

    $oRes = $oHandler->query($sqlQuery);

    if(!IS_PRODUCTION) echo $sqlQuery,'<br>';



    if($oHandler->affected_rows > 0)
    {

        $oRow = $oRes->fetch_assoc();
        $this->name = $oRow["description"];
        $this->is_active = true;
        $this->value = floatval($oRow["percent"]);
        $this->status = 'ok';
        $this->error_message = '';

    }
    else
    {
        $this->is_active = false;
        $this->status = 'ok';
        $this->error_message = '';
    }

    return $this;

}
}