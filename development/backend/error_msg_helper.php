<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 06.08.2018
 * Time: 15:36
 */

function GetErrorList($oDBHandler, $limit, $offset, $newonly = false, $singleCompanyId = null){

	$query = "SELECT a.id, a.company_id, a.message, a.msg_date, a.is_read, b.name FROM `" . DB_COMPANY_ERRORS_TABLE . "`a " .
	         "INNER JOIN `companies` b ON a.company_id = b.id WHERE 1";

	$cQuery = "SELECT COUNT(*) AS qty FROM " . DB_COMPANY_ERRORS_TABLE . " WHERE 1 ";
	if($newonly) {
		$clause = " AND `is_read` = 0 ";
		$query .= $clause;
		$cQuery .= $clause;
	}

	if(isset($singleCompanyId) and intval($singleCompanyId) > 0){
		$clause = " AND `company_id` = " . intval($singleCompanyId);
		$query .= $clause;
		$cQuery .= $clause;
	}

	$query .= " ORDER BY `msg_date` DESC LIMIT ". intval($offset) . ", " . intval($limit);

	$rescount = $oDBHandler->query($cQuery);
	$row = $rescount->fetch_assoc();
	$count = $row['qty'];

	$errList = array();

	$errList['count'] = intval($count);
	$res = $oDBHandler->query($query);
	if($res->num_rows > 0) {
		while($row = $res->fetch_assoc()){
			$temp['id'] = intval($row['id']);
			$temp['company_name'] = $row['name'];
			$temp['message'] = $row['message'];
			$temp['date'] = strtotime($row['msg_date']);
			$temp['is_read'] = boolval($row['is_read']);
			$errList['messages'][] = $temp;
		}
	}
	return $errList;
}

function SetRead($oDBHandler, $msgIds){
	if(count($msgIds) == 0 OR $msgIds == null) DropWithServerError("Not enough params specified");
	$query = "UPDATE `" . DB_COMPANY_ERRORS_TABLE . "` SET `is_read` = 1 WHERE 1";
	foreach($msgIds as $id){
		$query .= " AND `id` = " . intval($id);
	}
	$oDBHandler->query($query);
}

function GetMessage($oDBHandler, $msgId){
	$query = "SELECT a.id, a.company_id, a.message, a.msg_date, a.is_read, a.request_json, a.request_url, b.name FROM `" . DB_COMPANY_ERRORS_TABLE . "` a" .
				" INNER JOIN `companies` b ON a.company_id = b.id".
				" WHERE a.id = " . intval($msgId);
	$res = $oDBHandler->query($query);
	if($res->num_rows == 0) DropWithServerError("Incorrect message id specified");
	$err = array();
	$row = $res->fetch_assoc();
	$err['id'] = intval($row['id']);
	$err['companyName'] = $row['name'];
	$err['message'] = $row['message'];
	$err['date'] = strtotime($row['msg_date']);
	$err['request_json'] = $row['request_json'];
	$err['request_url'] = $row['request_url'];
	return $err;
}

?>