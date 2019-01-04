<?php

/*
 * @author Anton Dovgan <blackc.blackc@gmail.com>
 * 
 * @param string	JSON in POST body with parameters
 * 
 * @return string	JSON with results
 * 
 */

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/user_class.php";
require_once "./service/SendMailSmtpClass.php";
require_once "./service/site_template.php";

// fetch GET data
$sCookie = $_GET["q"];

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();

$sCookieCheckQuery = "SELECT *, 
if(UNIX_TIMESTAMP(CURRENT_TIMESTAMP)-
UNIX_TIMESTAMP(timestamp)<7200,1,0) IS_ACTUAL FROM `" . DB_PWRESTORE_TABLE . "` WHERE `code` = \"" . $mysqli->real_escape_string($sCookie) . "\"";
$oCookieCheck = $mysqli->query($sCookieCheckQuery);

if ($mysqli->affected_rows < 1)
	DropWithNotFound();
else
	{
		$oRow = $oCookieCheck->fetch_assoc();
        $isActual = $oRow["IS_ACTUAL"];

       	if(!$isActual)
		{
            $registrationResult = "Ссылка для восстановления пароля уже не актуальна.";
		}
		else
		{

            $iUserID = $oRow["uid"];
            $iID = $oRow["id"];

            $oUser = new User();

            $oUser->UserFromID($mysqli,$iUserID);

            $sRandPassword = random_str(12);

            $sApproveRegistrationQuery = "UPDATE `" . DB_USERS_TABLE . "` SET `password` = PASSWORD(\"" .
                $mysqli->real_escape_string($sRandPassword) . "\") WHERE `id` = " . $iUserID . " AND `is_deleted` = 0";
            $oApproveRes = $mysqli->query($sApproveRegistrationQuery);

            $iAffected1 = $mysqli->affected_rows;

            $sDeleteApprove = "DELETE FROM `" . DB_PWRESTORE_TABLE . "` WHERE `id` = " . $iID;
            $oApproveRes = $mysqli->query($sDeleteApprove);

            $iAffected2 = $mysqli->affected_rows;

            $mysqli->close();

            $registrationResult = "";

            $sMailToHeader = "To: " . $oPOSTData->data->defaultEmail . "\r\n";

            $sMailText = "Здравствуйте.<br>Ваш новый пароль: ".$sRandPassword.
			"<br><br>Для смены пароля перейдите в личный кабинет по ссылке: 
			<a href='".HOME_SITE_URL."/profile.php'>Личный кабинет</a>
			<br>Введите отправленный пароль в поле \"Текущий пароль\" и введите новый пароль в поле \"Новый пароль\"";

            date_default_timezone_set("UTC");
            $mailSMTP = new SendMailSmtpClass(MAIL_REG_FROM, PASS_REG_FROM, MAILER, HOST_REG_FROM, MAILER_PORT);
            $mailResult =
                $mailSMTP->send($oUser->userEMail,
                    MAIL_REST_SUBJECT,
                    $sMailText,
                    MAIL_REST_HEADERS .
                    $sMailToHeader);

            if (($iAffected1 > 0) and ($iAffected2 > 0))
                $registrationResult = "Ваш новый пароль был отправлен на ваш e-mail.";//ReturnSuccess(array("newPassword" => $sRandPassword));
            else
                $registrationResult = "Восстановнение пароля завершен с ошибкой.<br>Повторите запрос или обратитесь к администратору.";//DropWithServerError();

        }

        $site = new Site();

       	$site->ShowMessage($registrationResult);

	}

?>
