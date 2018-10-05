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
require_once "./service/SendMailSmtpClass.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

// check for parameters presence
if ((!isset($oPOSTData->modifies->password))

	or (!isset($oPOSTData->modifies->defaultEmail)))
	{
		DropWithBadRequest("Not enough or wrong parameters");
	}

$password = $oPOSTData->modifies->password;

$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);

if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
    DropWithBadRequest("Password does not match. Must be a minimum of 8 characters.
                        Must contain at least 1 number.
                        Must contain at least one uppercase character.
                        Must contain at least one lowercase character.");
}

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithUnAuth();

$iUserID = CheckAuth();

// check if auth presence
if ($iUserID === false)
	{

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
							$oPOSTData->modifies->jurBase,
            				$oPOSTData->modifies->passportDivisionCode
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
		
	}
else
	{
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
							$oPOSTData->modifies->jurBase,
            				$oPOSTData->modifies->passportDivisionCode
							);
							
		$mysqli->close();						
	}

switch($iNewUserResult)
	{
		case USER_OK:
			ReturnSuccess();
		case USER_NO_PARAMS:
			DropWithBadRequest("Not enough parameters");
		case USER_DB_ERROR:
			DropWithServerError("DB error");
		case USER_EXISTS:
			DropWithServerError("User already exists");
	}

?>
