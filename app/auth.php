<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 11.07.2018
 * Time: 14:43
 */

require_once ('adm_auth.php');
//Checks for user liability on production only
	if(IS_PRODUCTION) {
		$iUserID = CheckAuth();
		if ($iUserID === false)
			DropWithUnAuth();
	}
	else
	{
		$iUserID = 15;
	}

//Establishing DB connection
	$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);

// check DB connection
	if ($mysqli->connect_errno)
		DropWithServerError();

// create User object
	$cUser = new User();

// trying to authenticate
	if(IS_PRODUCTION) {
		$iAuth = $cUser->UserFromID( $mysqli, $iUserID );

// if no user
		if ( ( $iAuth != USER_OK ) or ( ! $cUser->objectOK ) ) {
			DropWithUnAuth();
		}
	}
// check for id and admin
	if(IS_PRODUCTION)
	{
		if ((!$cUser->isAdmin) && ($cUser->userID != $oPOSTData->data->id))
			DropWithForbidden();
	}
?>