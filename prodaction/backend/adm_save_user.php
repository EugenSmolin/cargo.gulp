<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 06.06.2018
 * Time: 15:16
 */

// require_once('check_key.php');

$_stat_action = "save_user";

require_once( 'services.php' );
require_once( 'modules/stat_mod.php' );
include_once('service/user_class.php');


$oPOSTData = json_decode(file_get_contents("php://input"));
include_once ('auth.php');

//data about user
$userData = $oPOSTData->data->user;
$uid = $oPOSTData->data->user->id;
//var_dump($userData);
//user from parameters
$newUser = new User();
$newUser->UserFromID($mysqli, $uid);
//var_dump($uid,$newUser);

if(isset($userData->lastName))
$newUser->userLastName = $userData->lastName;
if(isset($userData->middleName))
$newUser->userSecondName = $userData->middleName;
if(isset($userData->firstName))
$newUser->userName = $userData->firstName;
if(isset($userData->jurName))
$newUser->userJurName = $userData->jurName;
if(isset($userData->email))
$newUser->userEMail = $userData->email;
if(isset($userData->documentNumber))
$newUser->userPassportNum = $userData->documentNumber;
if(isset($userData->legalForm))
$newUser->userJurForm = $userData->legalForm;
if(isset($userData->OGRN))
$newUser->userOGRN = $userData->OGRN;
if(isset($userData->address))
$newUser->userAddress = $userData->address;
if(isset($userData->jurAddress))
$newUser->userJurAddress = $userData->jurAddress;
if(isset($userData->companyAddressCell))
    $newUser->userJurAddressCell = $userData->companyAddressCell;
if(isset($userData->INN))
$newUser->userINN = $userData->INN;
if(isset($userData->phone))
$newUser->userPhone = $userData->phone;
if(isset($userData->jurFormId))
$newUser->legalFormId = intval($userData->jurFormId);
if(isset($userData->passportDivisionCode))
$newUser->passportDivisionCode = $userData->passportDivisionCode;
if(isset($userData->addressCell))
$newUser->userAddressCell = $userData->addressCell;
if(isset($userData->givenName))
$newUser->userPassportGivenName = $userData->givenName;
if(isset($userData->givenDate))
$newUser->userPassportGivenDate = $userData->givenDate;
if(isset($userData->docTypeId))
$newUser->docTypeId = intval($userData->docTypeId);
if(isset($userData->userKPP))
	$newUser->userKPP = $userData->userKPP;
if(isset($userData->userAccNumber))
	$newUser->userAccNumber = $userData->userAccNumber;
if(isset($userData->userBIK))
	$newUser->userBIK = intval($userData->userBIK);
if(isset($userData->userJurBase))
	$newUser->userJurBase = $userData->userJurBase;
if(isset($userData->userChiefName))
	$newUser->userChiefName = $userData->userChiefName;
if(isset($userData->companyAddressCell))
	$newUser->userJurAddressCell = $userData->companyAddressCell;

//$newUser->userID  = $uid;
//$newUser->objectOK = true;

//var_dump($newUser);
if($newUser->SaveUser($mysqli) == USER_OK) {
    header('Content-Type: application/json');
	http_response_code(200);
	$success['status'] = "good";
	print(json_encode($success));
}
else DropWithServerError();
?>