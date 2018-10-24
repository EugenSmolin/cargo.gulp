<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 16.07.2018
 * Time: 12:55
 */

require_once('service/service.php');

if(isset($_COOKIE['admin_session'])){

	unset($_COOKIE['admin_session']);
	unset($_COOKIE['user-name']);
	unset($_COOKIE['user-id']);

	setcookie('admin_session', '', time() - 3600, '/');
	setcookie('user-name', '', time() - 3600, "/");
	setcookie('user-id', '', time() - 3600, "/");
	$toret = array();
	$toret['status'] = 'good';
	http_response_code(200);
	header('Content-Type: application/json');
	print(json_encode($toret));
}
else {
	DropWithUnAuth();
}

?>