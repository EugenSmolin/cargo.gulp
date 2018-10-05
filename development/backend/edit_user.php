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
require_once "./service/auth.php";
require_once "./service/user_class.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

$iUserID = CheckAuth();
if(!IS_PRODUCTION)
{
    $iUserID = $oPOSTData->data->id;
}
else
{
    if ($iUserID === false)
        DropWithUnAuth();
}

// check if auth presence
//if ((!isset($oPOSTData->auth->login)) or (!isset($oPOSTData->auth->password)))
	//{
		//DropWithUnAuth();
	//}

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithUnAuth();

// create User object
$cUser = new User();

// trying to authenticate
$iAuth = $cUser->UserFromID($mysqli, $iUserID);

if(!IS_PRODUCTION)
	var_dump($iAuth);

if(IS_PRODUCTION) {
    if (($iAuth != USER_OK) or (!$cUser->objectOK))
        DropWithUnAuth();
}
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
						
						if (isset($oPOSTData->modifies->defaultAddressCell))
							$cUser->userAddressCell = $mysqli->real_escape_string($oPOSTData->modifies->defaultAddressCell);

						if (isset($oPOSTData->modifies->defaultAddress))
                            $cUser->userAddress = $mysqli->real_escape_string($oPOSTData->modifies->defaultAddress);

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
							$cUser->userOGRN = $mysqli->real_escape_string($oPOSTData->modifies->OGRN);

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
							$cUser->userAccNumber = $mysqli->real_escape_string($oPOSTData->modifies->accNumber);

						if (isset($oPOSTData->modifies->BIK))
							$cUser->userBIK = intval($oPOSTData->modifies->BIK);

						if (isset($oPOSTData->modifies->chiefName))
							$cUser->userChiefName = $mysqli->real_escape_string($oPOSTData->modifies->chiefName);

						if (isset($oPOSTData->modifies->jurBase))
							$cUser->userJurBase = $mysqli->real_escape_string($oPOSTData->modifies->jurBase);

                        if (isset($oPOSTData->modifies->passportDivisionCode))
                            $cUser->passportDivisionCode = $mysqli->real_escape_string($oPOSTData->modifies->passportDivisionCode);

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

                if (isset($oPOSTData->modifies->defaultAddressCell))
                    $searchUser->userAddressCell = $mysqli->real_escape_string($oPOSTData->modifies->defaultAddressCell);

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

                if (isset($oPOSTData->modifies->jurAddressCell))
                    $searchUser->userJurAddressCell = $mysqli->real_escape_string($oPOSTData->modifies->jurAddressCell);

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

                if (isset($oPOSTData->modifies->passportDivisionCode))
                    $searchUser->passportDivisionCode = $mysqli->real_escape_string($oPOSTData->modifies->passportDivisionCode);

                //if (isset($oPOSTData->modifies->vkID))
					//$searchUser->userVKID = intval($oPOSTData->modifies->vkID);
				
				if (isset($oPOSTData->modifies->isAdmin))
					$searchUser->isAdmin = ($oPOSTData->modifies->isAdmin ? 1 : 0);
					
				$iResult = $searchUser->SaveUser($mysqli);
				
				$mysqli->close();
		
				if ($iResult == USER_OK)
					ReturnSuccess();
				else
					DropWithServerError("param");
			}
		else
			DropWithNotFound();
	}

?>
