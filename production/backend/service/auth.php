<?php

/*
 * @author Anton Dovgan <blackc.blackc@gmail.com>
 * 
 */

require_once "./service/config.php";
require_once "./service/service.php";

function CheckAuth()
    {
		if (!isset($_SERVER['HTTP_SESSION_STRING'])) {
			return false;
		}
		$sSessionString = $_SERVER['HTTP_SESSION_STRING'];
		//var_dump($sSessionString);
		// connect to DB
		$mysqli = new mysqli(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);
		
		// check DB connection
		if ($mysqli->connect_errno)
			DropWithServerError("DB error.");
		
		
		$sSessionReq = 'SELECT * FROM ' . DB_SESSIONS_TABLE . ' WHERE `session_id` = "' . $mysqli->real_escape_string($sSessionString) . 
						'"';// AND `addr` = ' . ip2long($_SERVER['REMOTE_ADDR']);
		$oSessionAnswer = $mysqli->query($sSessionReq);
//print('_dd');		
//print_r($oSessionAnswer);
		
		if ($oSessionAnswer->num_rows < 1)
			return false;
		else
			{
				// update session
				$sSessionUpdate = 'UPDATE ' . DB_SESSIONS_TABLE . ' SET `timestamp` = now() WHERE `session_id` = "' . 
							$mysqli->real_escape_string($sSessionString) . '"';
				$iSessionUpdate = $mysqli->query($sSessionUpdate);
				
				$oAnswer = $oSessionAnswer->fetch_assoc();
				return $oAnswer['uid'];
			}
		
	}
?>
