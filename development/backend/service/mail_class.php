<?php

require_once 'email_template/ttn_template.php';
require_once "./service/order_class.php";
require_once "./service/SendMailSmtpClass.php";
include("service/mpdf60/mpdf.php");

class Mail {

    public function SendOrderMailToClient($oHendler,$iOrderId,$language,$userType,$sCargoLogoPath)
    {
        $oOrder = new Order();
        $aOrder = $oOrder->OrderFromID($oHendler, $iOrderId);

        $companyId = $oOrder->iCompanyID;

        $file_mail_name=TTN_PATH."cargo_order_$iOrderId.pdf";

        if(!file_exists($file_mail_name)) {

            $template = new Template();

            $html = $template->GetTtn($oHendler, $iOrderId, "RU", $sCargoLogoPath);

            $mpdf = new mPDF();

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML($html);

            $mpdf->Output($file_mail_name, "F");
        }
        $lang = mb_strtoupper($language);
        date_default_timezone_set("UTC");
        $mailSMTP = new SendMailSmtpClass(MAIL_REG_FROM, PASS_REG_FROM, MAILER, HOST_REG_FROM, MAILER_PORT);

        $headers= "MIME-Version: 1.0\r\n";
        $subject = constant("MAIL_SUBJECT_FOR_ORDER_".$lang).$iOrderId." (".HOST_REG_FROM.")";

        switch ($userType)
        {
            case "dispatcher":
                $message = constant("MAIL_TEXT_ORDER_CREATED_FOR_DISPATCHER_".$lang);
                $mailTo = MAIL_TO_DISPATCHER;
                break;
            case "customer":
                $message = constant("MAIL_TEXT_ORDER_CREATED_FOR_CLIENT_".$lang);
                $mailTo = $oOrder->sClientEmail;
                break;
            case "sender":
                $message = constant("MAIL_TEXT_ORDER_CREATED_FOR_CLIENT_".$lang);
                $mailTo = '';
                if($oOrder->iOrderSenderLegalEntity)
                {
                    $mailTo = $oOrder->sOrderSenderCompanyEmail;
                }
                else
                {
                    $mailTo = $oOrder->sOrderSenderEmail;
                }
                if($mailTo == '')
                {
                    return false;
                }
                break;
            default:
                $message = 'Problem with type of mail for order#'.$iOrderId;
                $mailTo = MAIL_ADMIN;
                break;
        }

        $originalMail = $mailTo;

        $filename2 = "ttn_#".$iOrderId.".pdf";

        $boundary = md5(uniqid(time())); // генерируем разделитель
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n"; // кодировка письма// разделитель указывается в заголовке в параметре boundary
        $headers .= "From: ".HOST_REG_FROM." <".MAIL_REG_FROM.">\r\n";
        $headers .="To: ".$mailTo." \n";
        $multipart = "--$boundary\r\n";

        $multipart .= "Content-type:text/html; charset=utf-8\r\n";
        //$multipart .= "Content-type:text/plain; charset=iso-8859-1\r\n";
        $multipart .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        if(IS_DEBUG)
        {
            $multipart .= "Данное сообщение должно быть оправлено клиенту c email адресом:".$originalMail."\r\n". $message."\r\n\r\n";
        }
        else
        {
            $multipart .= $message."\r\n\r\n";
        }

        $filename = "order_#".$iOrderId.".pdf"; // название файла
        $file = self::LoadFile($file_mail_name);

        $message_part = "\r\n";
        $message_part .="--$boundary\r\n";
        $message_part .= "Content-Type: application/octet-stream; name=\"$filename\"\r\n";
        $message_part .= "Content-Disposition: attachment\r\n";
        $message_part .= "Content-Transfer-Encoding: base64\r\n";
        $message_part .= "\r\n";
        $message_part .= chunk_split(base64_encode($file));

        if ($companyId==32)
        {

            $file_mail_name2 = TTN_PATH.$filename2;

            if(file_exists ($file_mail_name2)) {

                $file2 = self::LoadFile($file_mail_name2);

                if (!($file2 === false)) {
                    $message_part .= "\r\n";
                    $message_part .= "--$boundary\r\n";
                    $message_part .= "Content-Type: application/octet-stream; name=\"$filename2\"\r\n";
                    $message_part .= "Content-Disposition: attachment\r\n";
                    $message_part .= "Content-Transfer-Encoding: base64\r\n";
                    $message_part .= "\r\n";
                    $message_part .= chunk_split(base64_encode($file2));
                }
            }
        }

        // Create bill start
        if($oOrder->iPaymentTypeID == 10 ) {
            $filename3 = 'cargo.guru_bill#_1_' . $iOrderId . '.pdf';
            $file_mail_name3 = TTN_PATH . $filename3;

            if (!file_exists($file_mail_name3)) {

                $template = new BillTemplate();

                $html = $template->GetBill($oHendler, $iOrderId, "RU");

                $mpdf = new mPDF();

                $mpdf->SetDisplayMode('fullpage');

                $mpdf->WriteHTML($html);

                $mpdf->Output($file_mail_name3, "F");
            }

            if (file_exists($file_mail_name3)) {

                $file3 = self::LoadFile($file_mail_name3);

                if (!($file3 === false)) {
                    $message_part .= "\r\n";
                    $message_part .= "--$boundary\r\n";
                    $message_part .= "Content-Type: application/octet-stream; name=\"$filename3\"\r\n";
                    $message_part .= "Content-Disposition: attachment\r\n";
                    $message_part .= "Content-Transfer-Encoding: base64\r\n";
                    $message_part .= "\r\n";
                    $message_part .= chunk_split(base64_encode($file3));
                }
            }
        }
        // Create bill stop

        $message_part .= "\r\n\r\n--$boundary--\r \n"; // второй частью прикрепляем файл, можно прикрепить два и более файла
        $multipart .= $message_part;

        $result = $mailSMTP->send($mailTo, $subject, $multipart, $headers); // отправляем письмо

        if (!$result)
        {
            $mailToAdmin = MAIL_ADMIN;
            $result = $mailSMTP->send($mailToAdmin, 'Ошибка отправки для:'.$mailTo, $multipart, $headers); // отправляем письмо админу
        }

        return $result;
    }

    public function SendCompanyErrorToAdmin($companyId,$message,$request_url="",$request_json="")
    {

        $oHandler = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);
        $sqlQuery=" 
			INSERT INTO company_errors
	          (company_id, message, msg_date, is_read,request_json,request_url)
	          VALUES ( ".$companyId.", '".$message."', NOW(), 0,".$request_json.",".$request_url.");			
			";

        $oRes = $oHandler->query($sqlQuery);

        date_default_timezone_set("UTC");
        $mailSMTP = new SendMailSmtpClass(MAIL_REG_FROM,
                                          PASS_REG_FROM, MAILER,
                                          HOST_REG_FROM, MAILER_PORT);

        $mailTo = MAIL_ADMIN;

        $boundary = md5(uniqid(time())); // генерируем разделитель
        $headers= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n"; // кодировка письма// разделитель указывается в заголовке в параметре boundary
        $headers .= "From: ".HOST_REG_FROM." <".MAIL_REG_FROM.">\r\n";
        $headers .="To: ".$mailTo." \n";
        $multipart = "--$boundary\r\n";
        $multipart .= "Content-type:text/plain; charset=iso-8859-1\r\n";
        $multipart .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $multipart .= $message."\r\n Хост: ".$request_url."\r\n Запрос: ".$request_json;
        $multipart .= "\r\n\r\n--$boundary--\r\n";

        $mailSMTP->send($mailTo, 'Ошибка компании :'.$companyId, $multipart, $headers);

    }

    private function  LoadFile($filename)
    {
        $fp = fopen($filename,"r");
        if (!$fp)
        {
            return false;
            //print "Не удается открыть файл";
            //exit();
        }
        $file = fread($fp, filesize($filename)); // чтение файла
        fclose($fp);

        return $file;
    }
}