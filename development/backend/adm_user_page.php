<?php

//require_once('check_key.php');

$_stat_action = "get_user_page";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once('service/user_class.php');
require_once ('service/discount_class.php'); //for getting user specific discounts

$oPOSTData = json_decode(file_get_contents("php://input"));
include_once ('auth.php');

$pageUserId = $oPOSTData->data->userId;

//holding found user
$fUser = new User();

$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

$searchUser = $fUser->UserForPage($mysqli, intval($oPOSTData->data->userId));

$endUsr['id'] = intval($searchUser->userID);
$endUsr['lastLogin'] = strtotime($searchUser->lastLoginDateTime);
$endUsr['lastName'] = $searchUser->userLastName;
$endUsr['middleName'] = $searchUser->userSecondName;
$endUsr['firstName'] = $searchUser->userName;
$endUsr['isJur'] = $searchUser->userIsJur;
$endUsr['jurName'] = $searchUser->userJurName;
$endUsr['email'] = $searchUser->userEMail;
$endUsr['docTypeId'] = $searchUser->docTypeId;
$endUsr['passportDivisionCode'] = $searchUser->passportDivisionCode;
$endUsr['documentNumber'] = $searchUser->userPassportNum;
$endUsr['legalForm'] = $searchUser->userJurForm;
$endUsr['OGRN'] = $searchUser->userOGRN;
$endUsr['address'] = $searchUser->userAddress;
$endUsr['addressCell'] = $searchUser->userAddressCell;
$endUsr['jurAddress'] = $searchUser->userJurAddress;
$endUsr['companyAddressCell'] = $searchUser->userJurAddressCell;
$endUsr['INN'] = $searchUser->userINN;
$endUsr['isApproved'] = $searchUser->isApproved;
$endUsr['phone'] = $searchUser->userPhone;
$endUsr['jurFormId'] = intval($searchUser->legalFormId);
$endUsr['givenName'] = $searchUser->userPassportGivenName;
$endUsr['givenDate'] = $searchUser->userPassportGivenDate;
$endUsr['registeredDate'] = $searchUser->registered;

$endUsr['userKPP'] = intval($searchUser->userKPP);
$endUsr['userAccNumber'] = $searchUser->userAccNumber;
$endUsr['userBIK'] = intval($searchUser->userBIK);
$endUsr['userJurBase'] = $searchUser->userJurBase;
$endUsr['userChiefName'] = $searchUser->userChiefName;

//discounts specific for a given user
$discounts = array();
$dis = new Discount();
$discounts = $dis->GetDiscounts($mysqli, 10, 0, "ACTIVE", '', "DESC", $pageUserId);
//assigning discounts to a concrete user
$endUsr['discounts'] = $discounts;

//legalform values


$endUsr['legalFormList'] = array();

$forms = Array();
$forms = $fUser->GetJurFormsLists($mysqli);

foreach ($forms as $form)
{
    $endUsr['legalFormList'][] = array("id" => $form['id'], "name" => $form['name']);
}

$endUsr['documentTypes'] = array();

$endUsr['documentTypes'] = [
	[
		"id" => 1,
		"name" => "Паспорт"
	],
	[
		"id" => 2,
		"name" => "Заграничный паспорт"
	],
	[
		"id" => 3,
		"name" => "Водительское удостоверение"
	]
];

http_response_code(200);
header('Content-Type: application/json');
print(json_encode($endUsr));

//print(json_encode($searchUser));
?>