<?php
define("IS_DEBUG",TRUE);
#define("IS_DEBUG",FALSE);
#define("IS_PRODUCTION",FALSE);
define("IS_PRODUCTION",FALSE);
#define("DB_HOST","mariadb");
define("DB_HOST","localhost");
#define("DB_RO_LOGIN","qr_ro");
#define("DB_RO_PASSWORD","bcNhsCsAWI9rlK3H");
define("DB_RW_LOGIN","cargo_lc");
define("DB_RW_PASSWORD","l8Lvue6l6H4Py06I");
define("DB_NAME","cargo_cabinets");



define("DB_ORDER_PAYMENT","order_payment");
define("DB_PAYMENT_TYPE_DISCOUNT","payment_type_discount");
define("DB_DISCOUNT","discount");
define("KEYS_DBNAME","cargo_apikeys");
define("KEYS_TABLE","apikeys");
define("DB_ORDER_STATUS_TABLE", "order_status_type");

define("DB_LOG_DB","cargo_log");
define("DB_LOG_TABLE","log");
define("DB_LOG_LOGIN","cargo_logger");
define("DB_LOG_PASSWORD","hDoVnritJ8MN");

define("DB_USERS_TABLE","accounts");
define("DB_COMPANY_TABLE","companies");
define("DB_COMPANY_TARIFF_TYPE","company_tariff_type");
define("DB_COMPANY_DOCUMENT","company_document");
define("DB_COMPANY_DOCUMENT_TYPE","company_document_type");
define("DB_ORDERS_TABLE","orders");
define("DB_EVENT_TABLE","cargo_states_history");
define("DB_CITY_TABLE","city");
define("DB_PAYMENT_TYPE","payment_type");
define("DB_PAYER_TYPE","payer_type");
define("DB_PAYER_PAYMENT","payer_payment");
define("DB_DANGER_CLASS_TABLE","danger_class");
define("DB_GLOBAL_VALUES_TABLE","global_values");
define("DB_COMPANY_DOCUMENT_TABLE", "company_document");
define("DB_COMPANY_DOCUMENT_TYPE_TABLE", "company_document_type");
define("DB_COMPANY_TARIFF_TABLE", "company_tariff_type");
define("DB_COUNTRY_TABLE", "countries");
define("DB_COMPANY_ERRORS_TABLE", "company_errors");
define("DB_USER_DISCOUNTS_TABLE", "user_discounts");
define("DB_USER_DISCOUNTS_USED_TABLE", "user_discounts_used");
define("DB_USER_DISCOUNT_CATEGORIES", "discount_categories");
define("DB_USER_DISCOUNT_COUNTRIES", "discount_locations");
define("DB_USER_DISCOUNT_COMPANIES", "discount_companies");
define("DB_APPLICATIONS_TABLE", "applications");
define("DB_ORDER_DISCOUNT_TABLE", "order_discount");

define("DB_ADDITIONAL_TERMINAL","dellin_addition_terminal");
define("DB_COMPANY_TABLE", "companies");
define("DB_WEB_LOG","web_log");
define("DB_COMPANY_OPTIONS","company_options");
define("DB_DOCUMENT_INSERTS","document_inserts");
define("DB_COMPANY_ACTIVITY","company_activity");
define("DB_JUR_FORM_TABLE","jur_form");
define("DB_DOCUMENTS_TABLE","documents");
define("DB_PWRESTORE_TABLE","pw_restore");
define("DB_RECIPIENTS_TABLE","recipients");
define("DB_SENDERS_TABLE","senders");
define("DB_CONTACT_TABLE","contact");
define("DB_CONTACT_STATISTIC_TABLE","contact_statistic");
define("DB_REGISTRATIONS_TABLE","registration");
define("DB_TEMPLATES_TABLE","orderTemplates");
define("DB_FINOPERATIONS_TABLE","fin_history");
define("DB_STATES_TABLE","cargo_states_history");
define("DB_STATESLIST_TABLE","status_list");
define("DB_TEMPERATURE_MODE_TABLE","temperature_mode");
define("DB_SESSIONS_TABLE","sessions");
define("DB_SCORES_TABLE","scores_comments");

define("USER_OK",0);
define("USER_NO_PARAMS",-1);
define("USER_DB_ERROR",-2);
define("USER_EXISTS",-3);
define("USER_NO_AUTH",-4);
define("USER_NOT_FOUND",-5);

define("PARCEL_OK",0);
define("PARCEL_NO_PARAMS",-1);
define("PARCEL_DB_ERROR",-2);
define("PARCEL_EXISTS",-3);
define("PARCEL_NOT_FOUND",-5);

define("MODERATORIAL_REJECT",-1);
define("MODERATORIAL_NONE",0);
define("MODERATORIAL_ACCEPT",1);

define("ALFA_API_SERVICE_LINK","https://web.rbsuat.com/ab/rest/");
define("ALFA_API_CONFIRM_URL","https://api8.cargo.guru/3/alfa_payment_verified.php?verif=");
define("ALFA_API_CURRENCY","810");
define("ALFA_API_USER_NAME","cargo_guru-api");
define("ALFA_API_PASSWORD","cargo_guru");

define("API_SITE_URL","https://api8.cargo.guru/3/");
define("HOME_SITE_URL","http://taskmessage.com");
define("SITE_URL_FOR_LOGO_BRAND_IMAGES","/img/tk-logos/");

#define("OPERATION_NO_OPERATION",0);
#define("OPERATION_PARCEL_COURIER_ASSIGN",1);
#define("OPERATION_PARCEL_COURIER_TO_COURIER",2);
#define("OPERATION_PARCEL_FROM_USER",3);
#define("OPERATION_PARCEL_TO_USER",4);
#define("OPERATION_PARCEL_TO_COURIER",5);
#define("OPERATION_PARCEL_FROM_COURIER",6);
#define("OPERATION_PARCEL_INFO",7);

define("MAIL_REG_FROM","no-reply@cargo.guru");
//define("MAIL_TO_DISPATCHER","info@cargo.guru");
define("MAIL_TO_DISPATCHER","andrii.sokoliuk@gmail.com");
define("MAIL_ADMIN","andrii.sokoliuk@gmail.com");
define("PASS_REG_FROM","CgwHMBkSQS");
define("HOST_REG_FROM","cargo.guru");
define("MAILER","ssl://smtp.yandex.ru");
define("MAILER_PORT",465);
define("CALC_API_BASE_URL","https://api.cargo.guru/3/");

define("PHONE_MANAGER","(905) 976 45 44");

#define("TTN_PATH","/var/tmp/");
define("TTN_PATH","tmp/");
define("MAIL_HEADERS","MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: " . HOST_REG_FROM . " регистрация <" . MAIL_REG_FROM . ">\r\n");
define("MAIL_REST_HEADERS","MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: " . HOST_REG_FROM . " пароль <" . MAIL_REG_FROM . ">\r\n");

define("MAIL_SUBJECT_RU","Регистрация на сервисе " . HOST_REG_FROM);
define("MAIL_SUBJECT_EN","Registration on the service " . HOST_REG_FROM);
define("MAIL_SUBJECT_DE","Registrierung auf dem Dienst " . HOST_REG_FROM);
define("MAIL_SUBJECT_FR","Inscription sur le service " . HOST_REG_FROM);
define("MAIL_SUBJECT_ES","Registro en el servicio " . HOST_REG_FROM);
define("MAIL_SUBJECT_ZH","在服务上注册 " . HOST_REG_FROM);
define("MAIL_SUBJECT_KO","서비스 등록 " . HOST_REG_FROM);
define("MAIL_SUBJECT_MS","Pendaftaran perkhidmatan " . HOST_REG_FROM);

define("MAIL_REST_SUBJECT_RU","Восстановление пароля на сервисе " . HOST_REG_FROM);
define("MAIL_REST_SUBJECT_EN","Password recovery on the service " . HOST_REG_FROM);
define("MAIL_REST_SUBJECT_DE","Passwortwiederherstellung für den Dienst " . HOST_REG_FROM);
define("MAIL_REST_SUBJECT_FR","Récupération du mot de passe sur le service " . HOST_REG_FROM);
define("MAIL_REST_SUBJECT_ES","Recuperación de contraseña en el servicio " . HOST_REG_FROM);
define("MAIL_REST_SUBJECT_ZH","密碼恢復服務 " . HOST_REG_FROM);
define("MAIL_REST_SUBJECT_KO","서비스의 비밀번호 복구 " . HOST_REG_FROM);
define("MAIL_REST_SUBJECT_MS","Pemulihan kata laluan pada perkhidmatan " . HOST_REG_FROM);

define("MAIL_SUBJECT_FOR_ORDER_RU","Новый заказ №");
define("MAIL_SUBJECT_FOR_ORDER_EN","New order #");
define("MAIL_SUBJECT_FOR_ORDER_DE","Neue Bestell-Nr.");
define("MAIL_SUBJECT_FOR_ORDER_FR","Le nouvel ordre #");
define("MAIL_SUBJECT_FOR_ORDER_ES","Nueva orden #");
define("MAIL_SUBJECT_FOR_ORDER_ZH","新訂單號");
define("MAIL_SUBJECT_FOR_ORDER_KO","새로운 주문 번호");
define("MAIL_SUBJECT_FOR_ORDER_MS","Perintah baru tidak");


define("MAIL_TEXT_RU","Здравствуйте.\r\n\r\nПожалуйста, не отвечайте на это письмо.\r\n\r\n".
    "Кто-то (возможно, Вы) подали заявку на регистрацию в сервисе "
    . HOST_REG_FROM .
    ". Если это были не Вы, тогда просто проигнорируйте это письмо. Чтобы завершить регистрацию и пройдите по ссылке ");
define("MAIL_TEXT_EN","Hello.\r\n\r\nPlease do not reply to this email.\r\n\r\n".
    "Someone (perhaps you) applied for registration in the service "
    . HOST_REG_FROM .
    ". If it was not you, then just ignore this letter. To complete registration and follow the link ");
define("MAIL_TEXT_DE","Guten Tag.\r\n\r\nBitte antworten Sie nicht auf diese E-Mail.\r\n\r\n".
    "Jemand (vielleicht Sie) hat sich für die Registrierung im Dienst beworben "
    . HOST_REG_FROM .
    ". Wenn du es nicht warst, dann ignorier diesen Brief einfach. Um die Registrierung abzuschließen, folgen Sie dem Link ");
define("MAIL_TEXT_FR","Bonjour.\r\n\r\nMerci de ne pas répondre à cet e-mail.\r\n\r\n".
    "Quelqu'un (peut-être vous) a demandé l'enregistrement dans le service "
    . HOST_REG_FROM .
    ". Si ce n'était pas vous, ignorez simplement cette lettre. Pour compléter l'inscription et suivre le lien ");
define("MAIL_TEXT_ES","Hola.\r\n\r\nPor favor, no responda a este correo electrónico.\r\n\r\n".
    "Alguien (quizás usted) solicitó el registro en el servicio "
    . HOST_REG_FROM .
    ". Si no fueras tú, entonces simplemente ignora esta carta. Para completar el registro y seguir el enlace ");
define("MAIL_TEXT_ZH","你好.\r\n\r\n請不要回复此消息.\r\n\r\n".
    "有人（或許你）申請註冊服務 "
    . HOST_REG_FROM .
    ". 如果不是你，那麼就忽略這封信。 完成註冊並關注鏈接 ");
define("MAIL_TEXT_KO","안녕.\r\n\r\n이 이메일에 회신하지 마십시오.\r\n\r\n".
    "누군가 (아마도 당신)이 서비스에 등록 신청을했습니다 "
    . HOST_REG_FROM .
    ". 당신이 아니었다면이 편지를 무시하십시오. 등록을 완료하고 링크를 따라 가려면 ");
define("MAIL_TEXT_MS","Halo.\r\n\r\nTolong jangan balas e-mel ini.\r\n\r\n".
    "Seseorang (mungkin anda) memohon pendaftaran dalam perkhidmatan "
    . HOST_REG_FROM .
    ". Sekiranya anda bukan, anda hanya mengabaikan surat ini. Untuk melengkapkan pendaftaran dan ikut pautan ");


define("MAIL_REST_RU","Здравствуйте.
                    <br>Пожалуйста, не отвечайте на это письмо.
                    <br>Кто-то (возможно, Вы) подали заявку на восстановление пароля в сервисе "
                    . HOST_REG_FROM . ".
					<br>
					Если это были не Вы, тогда просто проигнорируйте это письмо.
					<br><br>
					Чтобы завершить восстановление необходимо в течении 2х часов пройти по ссылке:<br> ");
define("MAIL_REST_EN","Hello.
                    <br>Please do not reply to this email.
                    <br>Someone (perhaps you) applied for a password recovery in the service "
                    . HOST_REG_FROM . ".
					<br>
					If it was not you, then just ignore this letter.
					<br><br>
					To complete the restoration, you must follow the link within 2 hours:<br> ");
define("MAIL_REST_DE","Guten Tag.
                    <br>Bitte antworten Sie nicht auf diese E-Mail.
                    <br>Jemand (vielleicht Sie) hat im Service eine Passwortwiederherstellung beantragt "
    . HOST_REG_FROM . ".
					<br>
					Wenn du es nicht warst, dann ignorier diesen Brief einfach.
					<br><br>
					Um die Wiederherstellung abzuschließen, müssen Sie dem Link innerhalb von 2 Stunden folgen:<br> ");
define("MAIL_REST_FR","Bonjour.
                    <br>Merci de ne pas répondre à cet e-mail.
                    <br>Quelqu'un (peut-être vous) a demandé une récupération de mot de passe dans le service "
    . HOST_REG_FROM . ".
					<br>
					Si ce n'était pas vous, alors ignorez cette lettre.
					<br><br>
					Pour compléter la restauration, vous devez suivre le lien dans les 2 heures:<br> ");
define("MAIL_REST_ES","Hola.
                    <br>Por favor, no responda a este correo electrónico.
                    <br>Alguien (quizás usted) solicitó una recuperación de contraseña en el servicio "
    . HOST_REG_FROM . ".
					<br>
					Si no fueras tú, entonces simplemente ignora esta carta.
					<br><br>
					Para completar la restauración, debe seguir el enlace dentro de las 2 horas:<br> ");
define("MAIL_REST_ZH","你好.
                    <br>請不要回复此消息.
                    <br>有人（也許是你）在服務中申請了密碼恢復 "
    . HOST_REG_FROM . ".
					<br>
					如果不是你，那麼就忽略這封信.
					<br><br>
					要完成恢復，您必須在2小時內關注該鏈接:<br> ");
define("MAIL_REST_KO","안녕.
                    <br>이 메시지에 회신하지 마십시오.
                    <br>누군가 (아마도 당신)이 서비스에서 비밀번호 복구를 신청했습니다 "
    . HOST_REG_FROM . ".
					<br>
					당신이 아니었다면,이 편지를 무시하십시오.
					<br><br>
					복원을 완료하려면 2 시간 이내에 링크를 따라야합니다:<br> ");
define("MAIL_REST_MS","Halo.
                    <br>Tolong jangan balas e-mel ini.
                    <br>Seseorang (mungkin anda) memohon untuk pemulihan kata laluan dalam perkhidmatan "
    . HOST_REG_FROM . ".
					<br>
					Sekiranya anda bukan, anda hanya mengabaikan surat ini.
					<br><br>
					Untuk melengkapkan pemulihan, anda mesti mengikuti pautan dalam masa 2 jam:<br> ");

define("MAIL_RESTORE_SUBJECT_RU","Ваш новый пароль");
define("MAIL_RESTORE_SUBJECT_EN","Your new password");
define("MAIL_RESTORE_SUBJECT_DE","Dein neues Passwort");
define("MAIL_RESTORE_SUBJECT_FR","Votre nouveau mot de passe");
define("MAIL_RESTORE_SUBJECT_ES","Tu nueva contraseña");
define("MAIL_RESTORE_SUBJECT_ZH","你的新密碼");
define("MAIL_RESTORE_SUBJECT_KO","새 비밀번호");
define("MAIL_RESTORE_SUBJECT_MS","Kata laluan baru anda");

define("MAIL_TEXT_ORDER_CREATED_FOR_CLIENT_RU","Здравствуйте.\r\n\r\nВы создали заказ на сайте " . HOST_REG_FROM . ".\r\n\r\n");
define("MAIL_TEXT_ORDER_CREATED_FOR_CLIENT_EN","Hello.\r\n\r\nYou created an order for the site " . HOST_REG_FROM . ".\r\n\r\n");
define("MAIL_TEXT_ORDER_CREATED_FOR_CLIENT_DE","Guten Tag.\r\n\r\nSie haben auf der Website eine Bestellung erstellt " . HOST_REG_FROM . ".\r\n\r\n");
define("MAIL_TEXT_ORDER_CREATED_FOR_CLIENT_FR","Bonjour.\r\n\r\nVous avez créé une commande sur le site " . HOST_REG_FROM . ".\r\n\r\n");
define("MAIL_TEXT_ORDER_CREATED_FOR_CLIENT_ES","Hola.\r\n\r\nUsted creó un pedido en el sitio " . HOST_REG_FROM . ".\r\n\r\n");
define("MAIL_TEXT_ORDER_CREATED_FOR_CLIENT_ZH","你好.\r\n\r\n您在網站上創建了一個訂單 " . HOST_REG_FROM . ".\r\n\r\n");
define("MAIL_TEXT_ORDER_CREATED_FOR_CLIENT_KO","안녕.\r\n\r\n사이트에서 주문을 만들었습니다 " . HOST_REG_FROM . ".\r\n\r\n");
define("MAIL_TEXT_ORDER_CREATED_FOR_CLIENT_MS","Halo.\r\n\r\nAnda membuat pesanan di laman web ini " . HOST_REG_FROM . ".\r\n\r\n");


define("MAIL_TEXT_ORDER_CREATED_FOR_DISPATCHER_RU","Здравствуйте.\r\n\r\nСоздан заказ.\r\n\r\nДетали заказа:");
define("MAIL_TEXT_ORDER_CREATED_FOR_DISPATCHER_EN","Hello.\r\n\r\nCreated an order.\r\n\r\nOrder details:");
define("MAIL_TEXT_ORDER_CREATED_FOR_DISPATCHER_DE","Guten Tag.\r\n\r\nErstellt eine Bestellung.\r\n\r\nBestelldetails:");
define("MAIL_TEXT_ORDER_CREATED_FOR_DISPATCHER_FR","Bonjour.\r\n\r\nCréé une commande.\r\n\r\nDétails de la commande:");
define("MAIL_TEXT_ORDER_CREATED_FOR_DISPATCHER_ES","Hola.\r\n\r\nCreó una orden.\r\n\r\nDetalles del pedido:");
define("MAIL_TEXT_ORDER_CREATED_FOR_DISPATCHER_ZH","你好.\r\n\r\n創建了一個訂單.\r\n\r\n訂單詳情:");
define("MAIL_TEXT_ORDER_CREATED_FOR_DISPATCHER_KO","안녕.\r\n\r\n주문 생성.\r\n\r\n주문 세부 정보:");
define("MAIL_TEXT_ORDER_CREATED_FOR_DISPATCHER_MS","Halo.\r\n\r\nMembuat pesanan.\r\n\r\nButiran pesanan:");

define("DATE_MYSQL_FORMAT", "Y-m-d H:i:s");
define("MAX_TIMESTAMP", 2147483647);
?>
