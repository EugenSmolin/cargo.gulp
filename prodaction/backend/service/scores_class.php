<?php

require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/score_class.php";

class Scores
	{
		public $scores					=	array();
		public $scoresCount				=	0;
		public $mediumScore				=	0;
		
		public $objectOK				= 	false;
		private $dirtyData				=	false;
		
		
		public function ScoresFromSearch($oDBHandler, 
							$aScoreIDs = array(),
							$aUserIDs = array(),
							$aCompanyIDs = array(),
							$aModeratorIDs = array(),
							$aModerationResults = array(),
							$iTimeFrom = 0,
							$iTimeTo = 0
						)
			/*
			 * @param MYSQLI	mysqli connect handler
			 * 
			 */
			{
				// parse parameters
				$sScoreIDs = implode(',',$aScoreIDs);
				$sUserIDs = implode(',',$aUserIDs);
				$sCompanyIDs = implode(',',$aCompanyIDs);
				$sModeratorIDs = implode(',',$aModeratorIDs);
				$sModerationResults = implode(',',$aModerationResults);
				
				//print_r($aCompanyIDs);
				
				// build query
				$sQuery = " 1 ";
				
				if ($sScoreIDs != "")
					$sQuery = "(`id` IN (" . $sScoreIDs . "))";

				if ($sUserIDs != "")
					{
						$sQuery .= (($sQuery == "") ? "" : " AND ");
						$sQuery .= "(`userId` IN (" . $sUserIDs . "))";
					}

				if ($sCompanyIDs != "")
					{
						$sQuery .= (($sQuery == "") ? "" : " AND ");
						$sQuery .= "(`companyId` IN (" . $sCompanyIDs . "))";
					}

				if ($sModeratorIDs != "")
					{
						$sQuery .= (($sQuery == "") ? "" : " AND ");
						$sQuery .= "(`moderatedBy` IN (" . $sModeratorIDs . "))";
					}

				if ($sModerationResults != "")
					{
						$sQuery .= (($sQuery == "") ? "" : " AND ");
						$sQuery .= "(`moderationResult` IN (" . $sModerationResults . "))";
					}
				
				if ($iTimeFrom != 0)
					{
						$sQuery .= (($sQuery == "") ? "" : " AND ");
						$sQuery .= "(`timestamp` >= FROM_UNIXTIME(" . $iTimeFrom . "))";
					}

				if ($iTimeTo != 0)
					{
						$sQuery .= (($sQuery == "") ? "" : " AND ");
						$sQuery .= "(`timestamp` <= FROM_UNIXTIME(" . $iTimeTo . "))";
					}

				// main query
				$sQuery = "SELECT *, UNIX_TIMESTAMP(timestamp) AS u_time, UNIX_TIMESTAMP(moderationDate) AS u_mtime FROM `" . 
							DB_SCORES_TABLE . "` WHERE " . $sQuery;
				//print($sQuery);
				$oSearchResult = $oDBHandler->query($sQuery);
				
				if ($oDBHandler->error)
					return USER_DB_ERROR;
					
				if ($oDBHandler->affected_rows < 1)
					return USER_NOT_FOUND;
					
				$iSum = 0;
				
				while($oRow = $oSearchResult->fetch_assoc())
					{
						$oTemp = new Score();
						$oTemp->scoreID = intval($oRow["id"]);
						$oTemp->scoreUserID = intval($oRow["userId"]);
						$oTemp->scoreValue = intval($oRow["score"]);
						$oTemp->scoreComment = $oRow["comment"];
						$oTemp->scoreCompanyID = intval($oRow["companyId"]);
						$oTemp->scoreTime = intval($oRow["u_time"]);
						$oTemp->scoreModeratorID = intval($oRow["moderatedBy"]);
						$oTemp->scoreModerationResult = intval($oRow["moderationResult"]);
						$oTemp->scoreModerationTime = intval($oRow["u_mtime"]);
						$oTemp->objectOK = true;
						
						$this->scores[] = $oTemp;
						
						$this->scoresCount++;
						
						$iSum += $oTemp->scoreValue;
						
						$this->mediumScore = round(floatval($iSum / $this->scoresCount),2);
					}
				
				$this->objectOK = true;
												
				return USER_OK;
			}
		
		/////////////////////////////////////////////////////////////////////////////
	}

?>
