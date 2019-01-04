<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 26.06.2018
 * Time: 14:12
 */

//require_once ('adm_log.php');

//$bLog = true;
//include_once ('adm_log.php');

require_once ('service/order_class.php');

$or = new Order();
$id = 5;
$mysqli = new mysqlii(DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME);
$or->OrderFromID($mysqli, $id);

print json_encode($or);
?>