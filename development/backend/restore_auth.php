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
require_once "./service/SendMailSmtpClass.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));

// check for parameters presence
if (!isset($oPOSTData->data->defaultEmail))
	{
		DropWithBadRequest("Not enough or wrong parameters");
	}

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithUnAuth();

$cNewUser = new User();

// search user
$iResult = $cNewUser->UserFromSearch($mysqli,"",$oPOSTData->data->defaultEmail);

if ($iResult != USER_OK) {
    DropWithNotFound("User not found");
}
else
{
    if($cNewUser->isApproved==0)
        DropWithBadMsg("User not Approved");
}

// ok, write it for restore
// generate random registration code

$sRandCookie = random_str(250);
// write it to DB

$sCookieWriteQuery = "INSERT INTO `" . DB_PWRESTORE_TABLE . "` (`uid`,`code`) VALUES (" . $cNewUser->userID . ", " .
						"\"" . $mysqli->real_escape_string($sRandCookie) . "\")";
$mysqli->query($sCookieWriteQuery);

// send email

$sMailText = MAIL_REST . " <a href=\"https://api." . HOST_REG_FROM . "/3/pwrestore.php?q=" . $sRandCookie . "\">" .
		"https://api." . HOST_REG_FROM . "/3/pwrestore.php?q=" . $sRandCookie . "</a>";

$sMailToHeader = "To: " . $oPOSTData->data->defaultEmail . "\r\n";

date_default_timezone_set("UTC");
$mailSMTP = new SendMailSmtpClass(MAIL_REG_FROM, PASS_REG_FROM, MAILER, HOST_REG_FROM, MAILER_PORT);
$mailResult = $mailSMTP->send($oPOSTData->data->defaultEmail, MAIL_REST_SUBJECT, $sMailText, MAIL_REST_HEADERS . $sMailToHeader);

if (!($mailResult === true))
	DropWithServerError($mailResult);

ReturnSuccess();

?>
