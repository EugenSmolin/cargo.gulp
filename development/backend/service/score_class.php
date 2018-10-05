<?php

require_once "./service/config.php";
require_once "./service/service.php";

class Score
	{
		public $scoreID					=	0;
		public $scoreUserID				=	0;
		public $scoreValue				=	0;
		public $scoreComment			=	"";
		public $scoreCompanyID			=	0;
		public $scoreModeratorID		=	0;
		public $scoreModerationTime		=	0;
		public $scoreModerationResult	=	0;
		public $scoreTime				=	0;
		
		private $dirtyData	=	false;
		public $objectOK				= false;
		
		
		public function ScoreFromID($oDBHandler, $iScoreID = 0)
			/*
			 * @param MYSQLI	mysqli connect handler
			 * 
			 */
			{
				$scoreID = intval($iScoreID);
				//$sEscapedPassword = $oDBHandler->real_escape_string($sPassword);
				
				$sSearchQuery = "SELECT *,UNIX_TIMESTAMP(timestamp) AS u_time FROM `" . DB_SCORES_TABLE . "` WHERE `id` = " . $iScoreID;
				
				$oSearchResult = $oDBHandler->query($sSearchQuery);
				//print($oDBHandler->error);
				if ($oDBHandler->error)
					return USER_DB_ERROR;
					
				if ($oDBHandler->affected_rows < 1)
					return USER_NOT_FOUND;
				
				$oRow = $oSearchResult->fetch_assoc();
				
				$this->objectOK = true;
				$this->scoreID = $oRow["id"];
				$this->scoreUserID = $oRow["userId"];
				$this->scoreValue = $oRow["score"];
				$this->scoreComment = $oRow["comment"];
				$this->scoreCompanyID = $oRow["companyId"];
				$this->scoreModeratorID = $oRow["moderatedBy"];
				$this->scoreModerationTime = $oRow["moderationDate"];
				$this->scoreModerationResult = $oRow["moderationResult"];
				$this->scoreTime = $oRow["u_time"];
								
				return USER_OK;
			}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////	
			
		public function NewScoreFromParameters($oDBHandler, 
							$scoreUserID = 0,
							$scoreValue = 0,
							$scoreComment = "",
							$scoreCompanyID = 0
						)
			/*
			 * @param MYSQLI	mysqli connect handler
			 * @param string	user name
			 * @param string	user phone
			 * @param string	user email
			 * @param string	user address
			 * @param string	user password
			 * @param bool		user admin flag
			 * @param string	user vk id
			 * @param int		user passport number
			 * 
			 */
			{				
				if (($scoreValue == 0) or ($scoreCompanyID == 0) or ($scoreUserID == 0)) // or ($sUserPassword == "") or ($iUserPassport == 0))
					return USER_NO_PARAMS;
				
				$scoreUserID = intval($scoreUserID);
				$scoreValue = intval($scoreValue);
				$scoreComment = $oDBHandler->real_escape_string($scoreComment);
				$scoreCompanyID = intval($scoreCompanyID);
				
				$sNewScoreQuery = "INSERT INTO `" . DB_SCORES_TABLE . "` (`userId`, `score`, `comment`, `companyId`) " .
					"VALUES (" . $scoreUserID . ", " . $scoreValue . ", \"" . $scoreComment . "\", " . $scoreCompanyID . ")";
					
				$oInsertResult = $oDBHandler->query($sNewScoreQuery);
				//print($oDBHandler->error);
				//trigger_error($oDBHandler->error);
				//print($sNewUserQuery);
				if ($oDBHandler->error)
					return USER_DB_ERROR;
				
				if ($oDBHandler->affected_rows > 0)
					{
						$this->objectOK = true;
						$this->scoreID = $oDBHandler->insert_id;

						$this->scoreUserID = $scoreUserID;
						$this->scoreValue = $scoreValue;
						$this->scoreComment = $scoreComment;
						$this->scoreCompanyID = $scoreCompanyID;
						$this->scoreModeratorID = 0;
						$this->scoreModerationTime = 0;
						$this->scoreModerationResult = 0;
						$this->scoreTime = time();
						return USER_OK;
					}
				else
					return USER_DB_ERROR;
			}
			
		//////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function DeleteScore($oDBHandler)
		{
			if ((!$this->objectOK) or ($this->scoreID < 1))
				return USER_NO_PARAMS;
			
			$sDeleteQuery = "DELETE FROM `" . DB_SCORES_TABLE . "` WHERE `id` = " . intval($this->scoreID);
			$oDBHandler->query($sDeleteQuery);
			
			if ($oDBHandler->affected_rows > 0)
				{
					$this->objectOK = false;
					$this->dirtyData = false;
				}
			
			if ($oDBHandler->affected_rows == 1)
				return USER_OK;
			else
				return USER_DB_ERROR;
		}
				
		//////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function SaveScore($oDBHandler)
		{
			if ((!$this->objectOK) or ($this->scoreID < 1))
				return USER_NO_PARAMS;
			
			$sEditScoreQuery = "UPDATE `" . DB_SCORES_TABLE . "` SET " .
								"`comment` = \"" . $oDBHandler->real_escape_string($this->scoreComment) . "\", " .
								"`userId` = " . intval($this->scoreUserID) . ", " .
								"`score` = " . intval($this->scoreValue) . ", " .
								"`companyId` = " . intval($this->scoreCompanyID) . ", " .
								"`moderatedBy` = " . intval($this->scoreModeratorID) . ", " .
								"`moderationResult` = " . intval($this->scoreModerationResult) . ", " .
								"`moderationDate` = FROM_UNIXTIME(" . intval($this->scoreModerationTime) . ") " .
								" WHERE `id` = " . intval($this->scoreID);
			
			$oDBHandler->query($sEditScoreQuery);
			//print($sEditScoreQuery);
			//print($oDBHandler->error);
			if ($oDBHandler->error)
				return USER_DB_ERROR;
			
			if ($oDBHandler->affected_rows == 1)
				return USER_OK;
			else
				return USER_DB_ERROR;
		}
		
	}

?>
