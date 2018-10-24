<?php

/*
 * @author Anton Dovgan <blackc.blackc@gmail.com>
 * 
 * @param string	JSON in POST body with parameters
 * 
 * @return string	JSON with results
 * 
 */

require_once('check_key.php');

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/user_class.php";
require_once "./service/auth.php";
require_once "./service/scores_class.php";

// check POST body
$oPOSTData = json_decode(file_get_contents("php://input"));
$iUserID = CheckAuth();
//print('fff ' . $iUserID);
if ($iUserID === false)
	DropWithUnAuth();

// check if data enough
if (
	(!isset($oPOSTData->data->companyIDs))
	and
	(!isset($oPOSTData->data->userIDs))
	and
	(!isset($oPOSTData->data->scoreIDs))
	and
	(!isset($oPOSTData->data->moderatorIDs))
	)
		{
			DropWithBadRequest("Not enough parameters");
		}

// connect to DB
$mysqli = new mysqlii(DB_HOST,DB_RW_LOGIN,DB_RW_PASSWORD,DB_NAME);

// check DB connection
if ($mysqli->connect_errno)
	DropWithServerError();

// create User object
$cUser = new User();

// trying to authenticate
$iAuth = $cUser->UserFromID($mysqli, $iUserID);

// req for scores list
$oScores = new Scores();

// if no moderation results specified -- set to 0
if (count($oPOSTData->data->moderationResults) <= 0)
	$oPOSTData->data->moderationResults = array(1);
	
array_intify($oPOSTData->data->scoreIDs);
array_intify($oPOSTData->data->userIDs);
array_intify($oPOSTData->data->companyIDs);
array_intify($oPOSTData->data->moderatorIDs);
array_intify($oPOSTData->data->moderationResults);
$oPOSTData->data->timeFrom = intval($oPOSTData->data->timeFrom);
$oPOSTData->data->timeTo = intval($oPOSTData->data->timeTo);

//print_r($oPOSTData->data->companyIDs);

$oScores->ScoresFromSearch($mysqli,
					$oPOSTData->data->scoreIDs, 
					$oPOSTData->data->userIDs,
					$oPOSTData->data->companyIDs,
					$oPOSTData->data->moderatorIDs,
					$oPOSTData->data->moderationResults,
					$oPOSTData->data->timeFrom,
					$oPOSTData->data->timeTo);

$aResultDataSet = array();

if ($oScores->objectOK)
	{
		foreach($oScores->scores as $oScore)
			{
				$aResultDataSet[] = array(
							"score" => $oScore->scoreValue,
							"companyIDs" => $oScore->scoreCompanyID,
							"comment" => $oScore->scoreComment,
							"scoreID" => $oScore->scoreID,
							"scoreTime" => $oScore->scoreTime,
							"moderatorIDs" => $oScore->scoreModeratorID,
							"moderationResults" => $oScore->scoreModerationResult,
							"moderationTime" => $oScore->scoreModerationTime
						);
			}
	}

$aResultOut = array(
			"success" => "success",
			"totalCount" => $oScores->scoresCount,
			"mediumScore" => $oScores->mediumScore,
			"data" => $aResultDataSet
		);

http_response_code(200);		
header('Content-Type: application/json');
print(json_encode($aResultOut));

?>
