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

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

if(IS_PRODUCTION)
{
    $iUserID = CheckAuth();
    if ($iUserID === false)
        DropWithUnAuth();
}

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

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();

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
{
    $iSearchResult = $cSearchUser->UserFromID($mysqli, $oPOSTData->data->id);
}
else
	$iSearchResult = $cSearchUser->UserFromSearch($mysqli,$oPOSTData->data->defaultPhone,$oPOSTData->data->defaultEmail
							//$oPOSTData->data->vkID,($bAuth ? $oPOSTData->data->passportNum : 0)
							);

if (($iSearchResult == USER_OK) or ($cSearchUser->objectOK))
{
	if($cSearchUser->isApproved==0)
	DropWithBadMsg("User not Approved");
}
else
{
    DropWithNotFound();
}
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
                "defaultAddressCell"=>$cSearchUser->userAddressCell,
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
            	"jurAddressCell"=>$cSearchUser->userJurAddressCell,
				"mailAddress" => $cSearchUser->userIsJur,
				"accNumber" => $cSearchUser->userAccNumber,
				"BIK" => $cSearchUser->userBIK,
				"chiefName" => $cSearchUser->userChiefName,
				"jurBase" => $cSearchUser->userJurBase,
				"passportDivisionCode" => $cSearchUser->passportDivisionCode
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
print(json_encode($aResultOut));

?>
