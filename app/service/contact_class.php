<?php

require_once "./service/config.php";
require_once "./service/service.php";

class Contact
{
    public function SetContact($oDBHandler,
                               $iUserID,
                                $is_legal_entity=0,
                                $sOrderCargoPhone='',
                                $sOrderCargoEmail='',
                                $sOrderCargoFirstName='',
                                $sOrderCargoSecondName='',
                                $sOrderCargoLastName='',
                                $sOrderCargoDocumentTypeId=0,
                                $sOrderCargoDocumentNumber=0,
                                $sOrderCargoCompanyName='',
                                $sOrderCargoCompanyFormId=1,
                                $sOrderCargoCompanyPhone="",
                                $sOrderCargoCompanyEmail="",
                                $sOrderCargoCompanyInn = "",
                                $sOrderCargoCompanyAddress = "",
                                $sOrderCargoCompanyAddressCell = "",
                                $sOrderCargoCompanyContactPersonFirstName="",
                                $sOrderCargoCompanyContactPersonSecondName=""
    )
    {
        $sSearchQuery = '';
        if($is_legal_entity)
        {
            $sSearchQuery = "SELECT id	FROM `" . DB_CONTACT_TABLE. "` " .
                "WHERE account_id = ".$iUserID." AND company_inn='".$sOrderCargoCompanyInn."'
				 AND company_name ='".$sOrderCargoCompanyName."'";
        }
        else
        {
            $sSearchQuery = "SELECT id	FROM `" . DB_CONTACT_TABLE. "` " .
                "WHERE account_id = ".$iUserID." AND  phone_number='" . $sOrderCargoPhone . "'
							AND email ='$sOrderCargoEmail'
							AND person_first_name ='$sOrderCargoFirstName'
							AND person_second_name ='$sOrderCargoSecondName'";
        }


        $oSearchResult = $oDBHandler->query($sSearchQuery);
        //if(IS_DEBUG)echo $sSearchQuery,'<br>';
        //echo  $sSearchQuery,'<br>', $oDBHandler->error;
        $result = new stdClass();

        if ($oDBHandler->error) {
            $result->id = 0;
            $result->status = 'error';
            $result->error = USER_DB_ERROR;
            return $result;
        }
        // compile ret array
        $iRecipientID = 1;

        if ($oDBHandler->affected_rows > 0)
        {
            $oRow = $oSearchResult->fetch_assoc();
            $updateQuery = "";
            $clientId= intval($oRow["id"]);

            if(!$is_legal_entity)
            {
                $updateQuery = "
                UPDATE `".DB_CONTACT_TABLE."`
                SET person_last_name='$sOrderCargoLastName',
                    person_document_type_id=$sOrderCargoDocumentTypeId,
                    person_document_number='$sOrderCargoDocumentNumber'
                WHERE id=".$clientId;
            }
            else
            {
                $updateQuery = "UPDATE `".DB_CONTACT_TABLE."`
                SET company_form_id=".$sOrderCargoCompanyFormId.",
                    company_phone='".$sOrderCargoCompanyPhone."',
                    company_email='".$sOrderCargoCompanyEmail."',
                    company_address='$sOrderCargoCompanyAddress',
                    company_address_cell='".$sOrderCargoCompanyAddressCell."',
                    contact_person_first_name='".$sOrderCargoCompanyContactPersonFirstName."',
                    contact_person_second_name='".$sOrderCargoCompanyContactPersonSecondName."'
                  WHERE id= ".$clientId;
            }

            $oResult = $oDBHandler->query($updateQuery);

            $result->id = intval($oRow["id"]);
            $result->status = 'ok';
            $result->error = '';
            return $result;
        }
        else
        {
            $sNewOrderQuery = "INSERT INTO `" . DB_CONTACT_TABLE . "`
						 ( account_id,
						  phone_number,
						  email,
						  is_legal_entity,
						  person_first_name,
						  person_second_name,
						  person_last_name,
						  person_document_type_id,
						  person_document_number,
						  company_name,
						  company_form_id,
						  company_inn,
						  company_phone,
						  company_email,
						  company_address,
						  company_address_cell,
						  contact_person_first_name,
						  contact_person_second_name)
						VALUES( $iUserID,
						  '" . $sOrderCargoPhone . "',
						  '" . $sOrderCargoEmail . "',
						  " . $is_legal_entity . ",
						  '" . $sOrderCargoFirstName . "',
						  '" . $sOrderCargoSecondName . "',
						  '" . $sOrderCargoLastName . "',
						  '" . $sOrderCargoDocumentTypeId . "',
						  '" . $sOrderCargoDocumentNumber . "',
						  '" . $sOrderCargoCompanyName . "',
						  " . $sOrderCargoCompanyFormId . ",
						  '" . $sOrderCargoCompanyInn . "',
						  '" . $sOrderCargoCompanyPhone . "',
						  '" . $sOrderCargoCompanyEmail . "',
						  '" . $sOrderCargoCompanyAddress . "',
						  '" . $sOrderCargoCompanyAddressCell . "',
						  '" . $sOrderCargoCompanyContactPersonFirstName . "',
						  '" . $sOrderCargoCompanyContactPersonSecondName . "'
						)";
            $oInsertResult = $oDBHandler->query($sNewOrderQuery);
            //if(IS_DEBUG)echo $sNewOrderQuery,'<br>';

            if ($oDBHandler->error)
            {
                $result->id = PARCEL_DB_ERROR;
                $result->status = 'PARCEL_DB_ERROR';
                $result->error = $oDBHandler->error;
                return $result;
            }

            if ($oDBHandler->affected_rows > 0) {
                $result->id = $oDBHandler->insert_id;
                $result->status = 'ok';
                $result->error = '';
                return $result;
            }
        }
    }

    public function TopList($oDBHandler,
                            $iUserID,
                            $contactDirection,
                            $isLegal,
                            $iLimit)
    {
        $oResult = array();
        $person_first_name='';
        $person_second_name = '';
        $phone_number = '';
        $company_name = '';
        $company_address = '';
        $queryOrder=" ORDER BY cs.sender_count ";

        if(isset($contactDirection))
        if(mb_strtolower($contactDirection) == "to")
        {
            $queryOrder=" ORDER BY cs.recipient_count ";
        }

        $query = " WHERE c.account_id =$iUserID AND c.is_legal_entity = ".$isLegal
                  .$queryOrder.
                  " LIMIT $iLimit";

        return self::GetQueryResult($oDBHandler,$query);
    }

    public function FullList($oDBHandler,
                             $iUserID,
                             $contactDirection,
                             $searchWord,
                             $iLimit,
                             $iOffset)
    {
        $oResult = array();

        $queryWhere = "";
        $queryOrder=" ORDER BY cs.sender_count ";

        if(isset($contactDirection))
            if(mb_strtolower($contactDirection) == "to")
            {
                $queryOrder=" ORDER BY cs.recipient_count ";
            }

        $queryLimitClause = " LIMIT " . $iOffset;
        if (intval($iLimit) > 0)
            $queryLimitClause .= ", " . $iLimit;

        if(isset($searchWord))
        {
            $queryWhere =" OR c.person_first_name like '$searchWord'
                  OR c.phone_number  like '$searchWord'
                  OR c.company_name  like '$searchWord'
                  OR c.company_inn  like '$searchWord'
                  OR c.company_address  like '$searchWord' ";
        }

        $query = " WHERE c.account_id =".$iUserID
                  .$queryWhere
                  .$queryOrder
                  .$queryLimitClause;

        return self::GetQueryResult($oDBHandler,$query);
    }

    public function GetLegalList($oDBHandler,
                                 $iUserID,
                                 $searchWord,
                                 $iLimit,
                                 $iOffset)
    {
        $queryWhere = "";
        $queryOrder="ORDER BY cs.sender_count";

        if(isset($contactDirection))
            if(mb_strtolower($contactDirection) == "to")
            {
                $queryOrder=" ORDER BY cs.recipient_count ";
            }

        $queryLimitClause = " LIMIT " . $iOffset;
        if (intval($iLimit) > 0)
            $queryLimitClause .= ", " . $iLimit;

        if(isset($searchWord))
        {
            $queryWhere =" OR c.person_first_name like '$searchWord'
                  OR c.phone_number  like '$searchWord'
                  OR c.company_name  like '$searchWord'
                  OR c.company_inn  like '$searchWord'
                  OR c.company_address  like '$searchWord' ";
        }

        $query = " WHERE c.account_id =".$iUserID."  AND c.is_legal_entity = 1"
            .$queryWhere.$queryOrder.
            $queryLimitClause;
        return self::GetQueryResult($oDBHandler,$query);
    }

    public function GetPersonList($oDBHandler,
                                  $iUserID,
                                  $searchWord,
                                  $iLimit,
                                  $iOffset)
    {
        $queryWhere = "";
        $queryOrder="ORDER BY cs.sender_count";

        if(isset($contactDirection))
            if(mb_strtolower($contactDirection) == "to")
            {
                $queryOrder=" ORDER BY cs.recipient_count ";
            }

        $queryLimitClause = " LIMIT " . $iOffset;
        if (intval($iLimit) > 0)
            $queryLimitClause .= ", " . $iLimit;

        if(isset($searchWord))
        {
            $queryWhere =" OR c.person_first_name like '$searchWord'
                  OR c.phone_number  like '$searchWord'
                  OR c.company_name  like '$searchWord'
                  OR c.company_inn  like '$searchWord'
                  OR c.company_address  like '$searchWord' ";
        }

        $query = " WHERE c.account_id =".$iUserID."  AND c.is_legal_entity = 0"
            .$queryWhere.$queryOrder.
            $queryLimitClause;
        return self::GetQueryResult($oDBHandler,$query);
    }

    private function GetQueryResult($oDBHandler,$queryAdditions)
    {
        $oResult = array();
        $person_first_name='';
        $person_second_name = '';
        $phone_number = '';
        $company_name = '';
        $company_address = '';

        $is_legal_entity = 0;
        $person_last_name = '';
        $email = '';
        $person_document_type_id =0;
        $person_document_number = '';
        $company_full_name = '';
        $company_form_id = 0;
        $company_inn = '';
        $company_phone = '';
        $company_email = '';
        $company_address = '';
        $company_address_cell = '';
        $contact_person_first_name = '';
        $contact_person_second_name = '';
        $company_full_address = '';


        $query = "SELECT c.is_legal_entity,
                         c.person_first_name,
                         c.person_second_name,
                         c.person_last_name,
                         c.phone_number,
                         c.email,
                         c.person_document_type_id,
                         c.person_document_number,
                         
                         concat(c.company_name,' ',f.short_name) AS company_full_name,
                         c.company_name,
                         c.company_form_id,
                         c.company_inn,
                         c.company_phone,
                         c.company_email,
                         c.company_address,
                         c.company_address_cell,
                         c.contact_person_first_name,
                         c.contact_person_second_name,   
                         concat(c.company_address,', оф. ',c.company_address_cell) AS company_full_address        
                  FROM ".DB_CONTACT_TABLE." c
                  JOIN ".DB_CONTACT_STATISTIC_TABLE."  cs ON c.id=cs.contact_id
                  JOIN ".DB_JUR_FORM_TABLE."  f ON f.id=c.company_form_id "
                  .$queryAdditions;

        if ($stmt = $oDBHandler->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result(
                    $is_legal_entity,
                    $person_first_name,
                    $person_second_name,
                    $person_last_name,
                    $phone_number,
                    $email,
                    $person_document_type_id,
                    $person_document_number,
                    $company_full_name,
                    $company_name,
                    $company_form_id,
                    $company_inn,
                    $company_phone,
                    $company_email,
                    $company_address,
                    $company_address_cell,
                    $contact_person_first_name,
                    $contact_person_second_name,
                    $company_full_address

            );
            while ($stmt->fetch())
            {
                $oResult[] = array(
                    'is_legal_entity'=>$is_legal_entity,
                    'person_first_name'=>$person_first_name,
                    'person_second_name'=>$person_second_name,
                    'person_last_name'=>$person_last_name,
                    'phone_number'=>$phone_number,
                    'person_email'=>$email,
                    'person_document_type_id'=>$person_document_type_id,
                    'person_document_number'=>$person_document_number,
                    'company_full_name'=>$company_full_name,
                    'company_name'=>$company_name,
                    'company_form_id'=>$company_form_id,
                    'company_inn'=>$company_inn,
                    'company_phone'=>$company_phone,
                    'company_email'=>$company_email,
                    'company_address'=>$company_address,
                    'company_address_cell'=>$company_address_cell,
                    'contact_person_first_name'=>$contact_person_first_name,
                    'contact_person_second_name'=>$contact_person_second_name,
                    'company_full_address'=>$company_full_address
                );
            }

        }

        return $oResult;
    }

    public function SetStatisticForContact($oDBHandler,
                                 $iContactID,
                                 $sContactWayType)
    {
        $sQuery = "";

        $sSearchQuery = "SELECT 1	FROM `" . DB_CONTACT_STATISTIC_TABLE. "` " .
            "WHERE contact_id = ".$iContactID;

        $oDBHandler->query($sSearchQuery);
        //var_dump($sSearchQuery);
        if ($oDBHandler->affected_rows > 0) {
            if ($sContactWayType == 'from') {
                $set = " sender_count = sender_count + 1 ";
            }
            else
            {
                $set = " recipient_count = recipient_count + 1 ";
            }

            $sQuery = "UPDATE contact_statistic SET ".$set." WHERE contact_id=".$iContactID;
        }
        else
        {
            $val ='0,0';
            if($sContactWayType == 'from')
            {
                $val ='1,0';
            }
            else
            {
                $val ='0,1';
            }
            $sQuery = "INSERT INTO contact_statistic
	                   (contact_id, sender_count, recipient_count)
	                   VALUES (".$iContactID.", ".$val." )";
        }

        $oDBHandler->query($sQuery);


    }

    public function GetContactBy($oDBHandler,$clientID,
                                 $is_legal_entity=0,
                                 $sOrderCargoPhone='',
                                 $sOrderCargoEmail='',
                                 $sOrderCargoFirstName='',
                                 $sOrderCargoSecondName='',
                                 $sOrderCargoLastName='',
                                 $sOrderCargoDocumentTypeId=0,
                                 $sOrderCargoDocumentNumber=0,
                                 $sOrderCargoCompanyName='',
                                 $sOrderCargoCompanyFormId=1,
                                 $sOrderCargoCompanyPhone="",
                                 $sOrderCargoCompanyEmail="",
                                 $sOrderCargoCompanyInn = "",
                                 $sOrderCargoCompanyAddress = "",
                                 $sOrderCargoCompanyAddressCell = "",
                                 $sOrderCargoCompanyContactPersonFirstName="",
                                 $sOrderCargoCompanyContactPersonSecondName=""

    )
    {
        $sSearchQuery = '';
        if($is_legal_entity)
        {
            $sSearchQuery = "SELECT id	FROM `" . DB_CONTACT_TABLE. "` " .
                "WHERE company_inn='".$sOrderCargoCompanyInn."'
				 AND company_name ='".$sOrderCargoCompanyName."'";
        }
        else
        {
            $sSearchQuery = "SELECT id	FROM `" . DB_CONTACT_TABLE. "` " .
                "WHERE phone_number='" . $sOrderCargoPhone . "'
							AND email ='$sOrderCargoEmail'
							AND person_first_name ='$sOrderCargoFirstName'
							AND person_second_name ='$sOrderCargoSecondName'";
        }


        $oSearchResult = $oDBHandler->query($sSearchQuery);
        //if(IS_DEBUG)echo $sSearchQuery,'<br>';
        //echo  $sSearchQuery; die;//,'<br>', $oDBHandler->error;
        $result = new stdClass();

        if ($oDBHandler->error) {
            $result->id = 0;
            $result->status = 'error';
            $result->error = USER_DB_ERROR;
            return $result;
        }
        // compile ret array
        $iRecipientID = 1;

        if ($oDBHandler->affected_rows > 0)
        {
            $oRow = $oSearchResult->fetch_assoc();
            $updateQuery = "";
            $clientId= intval($oRow["id"]);

            if(!$is_legal_entity)
            {
                $updateQuery = "
                UPDATE `".DB_CONTACT_TABLE."`
                SET person_last_name='$sOrderCargoLastName',
                    person_document_type_id=$sOrderCargoDocumentTypeId,
                    person_document_number='$sOrderCargoDocumentNumber'
                WHERE id=".$clientId;
            }
            else
            {
                $updateQuery = "UPDATE `".DB_CONTACT_TABLE."`
                SET company_form_id=".$sOrderCargoCompanyFormId.",
                    company_phone='".$sOrderCargoCompanyPhone."',
                    company_email='".$sOrderCargoCompanyEmail."',
                    company_address='$sOrderCargoCompanyAddress',
                    company_address_cell='".$sOrderCargoCompanyAddressCell."',
                    contact_person_first_name='".$sOrderCargoCompanyContactPersonFirstName."',
                    contact_person_second_name='".$sOrderCargoCompanyContactPersonSecondName."'
                  WHERE id= ".$clientId;
            }

            $oResult = $oDBHandler->query($updateQuery);

            $result->id = intval($oRow["id"]);
            $result->status = 'ok';
            $result->error = '';
            return $result;
        }
        else
        {
            $sNewOrderQuery = "INSERT INTO `" . DB_CONTACT_TABLE . "`
						 (account_id,
						  phone_number,
						  email,
						  is_legal_entity,
						  person_first_name,
						  person_second_name,
						  person_last_name,
						  person_document_type_id,
						  person_document_number,
						  company_name,
						  company_form_id,
						  company_inn,
						  company_phone,
						  company_email,
						  company_address,
						  company_address_cell,
						  contact_person_first_name,
						  contact_person_second_name)
						VALUES(".$clientID.",
						  '" . $sOrderCargoPhone . "',
						  '" . $sOrderCargoEmail . "',
						  " . $is_legal_entity . ",
						  '" . $sOrderCargoFirstName . "',
						  '" . $sOrderCargoSecondName . "',
						  '" . $sOrderCargoLastName . "',
						  '" . $sOrderCargoDocumentTypeId . "',
						  '" . $sOrderCargoDocumentNumber . "',
						  '" . $sOrderCargoCompanyName . "',
						  '" . $sOrderCargoCompanyFormId . "',
						  '" . $sOrderCargoCompanyInn . "',
						  '" . $sOrderCargoCompanyPhone . "',
						  '" . $sOrderCargoCompanyEmail . "',
						  '" . $sOrderCargoCompanyAddress . "',
						  '" . $sOrderCargoCompanyAddressCell . "',
						  '" . $sOrderCargoCompanyContactPersonFirstName . "',
						  '" . $sOrderCargoCompanyContactPersonSecondName . "'
						)";
            $oInsertResult = $oDBHandler->query($sNewOrderQuery);

            if ($oDBHandler->error)
            {
                $result->id = PARCEL_DB_ERROR;
                $result->status = 'PARCEL_DB_ERROR';
                $result->error = $oDBHandler->error;
                return $result;
            }

            if ($oDBHandler->affected_rows > 0) {
                $result->id = $oDBHandler->insert_id;
                $result->status = 'ok';
                $result->error = '';
                return $result;
            }
        }

    }
}
?>