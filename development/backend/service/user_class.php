<?php

require_once "./service/config.php";
require_once "./service/service.php";

class User
	{
		public $registered = 0;
		public $lastLoginDateTime = 0;
		public $userID		=	0;
		public $isAdmin		 =	false;
		public $userName	 =	"";
	    public $userSecondName	 =	"";
		public $userLastName	 =	"";
		public $userEMail	 =	"";
		public $userPhone	 =	"";
		public $userAddress  =	"";
    	public $userAddressCell  =	"";
		public $userDefLang  =	"";
		public $userDefCurr  =	"";
		public $userDefWUnit =	0;
		public $userDefVUnit =	0;
		//public $userPassport =	0;
		//public $userVKID	 =	"";
		public $objectOK		=	false;

		public $userIsJur		= false;
		public $userPassportNum         = "";
		public $userPassportGivenName		= "";
		public $userPassportGivenDate		= 0;
		public $userINN			= 0;
		public $userJurForm			= "";
		public $userOGRN		= 0;
		public $userKPP			= '';
		public $userJurName		= "";
		public $userJurAddress	= "";
	    public $userJurAddressCell	= "";

    	public $userMailAddress	= "";
		public $userAccNumber	= "";
		public $userBIK			= 0;
		public $userChiefName	= "";
		public $userJurBase		= "";
		public $passportDivisionCode	= "";
		private $dirtyData	=	false;

		public $isApproved = false;
		public $legalFormId = 0;
		public $docTypeId = 1;

		/** Get user info */
		public function UserFromAuth($oDBHandler, $sLogin = "", $sPassword = "")
			/*
			 * @param MYSQLI	mysqli connect handler
			 * 
			 */
			{
				if (($sLogin == "") or ($sPassword == ""))
					return USER_NO_AUTH;
				
				$sEscapedLogin = $oDBHandler->real_escape_string($sLogin);
				$sEscapedPassword = $oDBHandler->real_escape_string($sPassword);
				
				$sSearchQuery = "SELECT *, unix_timestamp(givenDate) AS u_givenDate FROM `" . DB_USERS_TABLE . "` WHERE " .
				    "( " .
					"(" .
					    "(`email` = \"" . $sEscapedLogin . "\") " .
						" OR " .
						    "(" .
							"(`phone` = \"" . $sEscapedLogin . "\") AND (CAST(\"" . $sEscapedLogin . "\" AS INT) > 0)" .
						    ") " .
					") " .
					"AND `password` = PASSWORD(\"" . $sEscapedPassword . "\") " .
					"AND `approved` = 1 AND `is_deleted` = 0" .
				    ")";
				
				//print($sSearchQuery);
				
				$oSearchResult = $oDBHandler->query($sSearchQuery);
				
				if ($oDBHandler->error)
					return USER_NO_AUTH;
					
				if ($oDBHandler->affected_rows < 1)
					return USER_NO_AUTH;
				
				$oRow = $oSearchResult->fetch_assoc();
				
				$this->objectOK = true;
				$this->userID = $oRow["id"];
				$this->userName = $oRow["first_name"];
				$this->userSecondName = $oRow["second_name"];
				$this->userLastName = $oRow["last_name"];
				$this->userEMail = $oRow["email"];
				$this->userPhone = $oRow["phone"];
				$this->userAddress = $oRow["address"];
                $this->userAddress = $oRow["address_cell"];
				$this->userDefLang = $oRow["defLang"];
				$this->userDefCurr = $oRow["defCurrency"];
				$this->userDefWUnit = $oRow["defWUnit"];
				$this->userDefVUnit = $oRow["defVUnit"];
				$this->isAdmin = ($oRow["admin"] > 0 ? true : false);
				$this->userIsJur = ($oRow["isJur"] > 0 ? true : false);
				$this->userPassportNum = $oRow["defVUnit"];
				$this->userPassportGivenDate = intval($oRow["u_givenDate"]);
				$this->userPassportGivenName = $oRow["givenName"];
				$this->userINN = intval($oRow["INN"]);
				$this->userJurForm = $oRow["jurForm"];
				$this->userOGRN = $oRow["OGRN"];
				$this->userKPP = $oRow["KPP"];
				$this->userJurName = $oRow["jurName"];
				$this->userJurAddress = $oRow["jurAddress"];
                $this->userAddressCell = $oRow["jur_address_cell"];
				$this->userMailAddress = $oRow["mailAddress"];
				$this->userAccNumber = $oRow["accNumber"];
				$this->userBIK = intval($oRow["BIK"]);
				$this->userChiefName = $oRow["chiefFIO"];
				$this->userJurBase = $oRow["jurBase"];
                $this->passportDivisionCode = $oRow["passportDivisionCode"];
				return USER_OK;
			}


    /** Create user
     * @param $oDBHandler
     * @param string $sUserName
     * @param string $sUserSecondName
     * @param string $sUserLastName
     * @param string $sUserPhone
     * @param $sUserEmail - Required parameter
     * @param string $sUserAddress
	 * @param string $sUserAddressCell
     * @param $sUserPassword - Required parameter
     * @param bool $bAdminFlag
     * @param int $iApproved
     * @param string $sUserDefLang
     * @param string $sUserDefCurr
     * @param int $iUserDefWUnit
     * @param int $iUserDevVUnit
     * @param bool $bUserJur
     * @param int $iPassportNum
     * @param string $sPassportGivenName
     * @param int $sPassportGivenDate
     * @param int $iINN
     * @param string $sJurForm
     * @param int $iOGRN
     * @param int $iKPP
     * @param string $sJurName
     * @param string $sJurAddress
	 * @param string $sJurAddressCell
     * @param string $sMailAddress
     * @param string $iAccNumber
     * @param int $iBIK
     * @param string $sChiefFIO
     * @param string $sJurBase
     * @return int - Error message
     */
		public function NewUserFromParameters(
						$oDBHandler,
						$sUserName = "",
						$sUserSecondName = "",
						$sUserLastName = "",
						$sUserPhone = "",
						$sUserEmail,
						$sUserAddress = "",
                        $sUserAddressCell = "",
						$sUserPassword,
						$bAdminFlag = false,

						$iApproved = 1,
						$sUserDefLang = "",
						$sUserDefCurr = "",
						$iUserDefWUnit = 0,
						$iUserDevVUnit = 0,
						
						$bUserJur = false,
						$iPassportNum = "",
						$sPassportGivenName = "",
						$sPassportGivenDate = 0,
						$iINN = 0,
						$sJurForm = "",
						$iOGRN = '',
						$iKPP = '',
						$sJurName = "",
						$sJurAddress = "",
                        $sJurAddressCell = "",
						$sMailAddress = "",
						$iAccNumber = "",
						$iBIK = 0,
						$sChiefFIO = "",
						$sJurBase = "",
                        $sPassportDivisionCode = ""
						)
			{
				if (($sUserEmail == "") or ($sUserPassword == ""))
				{
                    return USER_NO_PARAMS;
				}

                $isUserExist = $this->UserExist($oDBHandler,$sUserEmail);
				if($isUserExist==USER_OK)
				{
					return USER_EXISTS;
				}

				$sUserLastName = $oDBHandler->real_escape_string($sUserLastName);
				$sUserSecondName = $oDBHandler->real_escape_string($sUserSecondName);
				$sUserName = $oDBHandler->real_escape_string($sUserName);
				$sUserPhone = floatval($sUserPhone);
				$sUserEmail = $oDBHandler->real_escape_string($sUserEmail);
				$sUserAddress = $oDBHandler->real_escape_string($sUserAddress);
                $sUserAddressCell = $oDBHandler->real_escape_string($sUserAddressCell);
				$sUserPassword = $oDBHandler->real_escape_string($sUserPassword);
				$sUserDefLang = $oDBHandler->real_escape_string($sUserDefLang);
				$sUserDefCurr = $oDBHandler->real_escape_string($sUserDefCurr);
				$iUserDefWUnit = intval($iUserDefWUnit);
				$iUserDefVUnit = intval($iUserDevVUnit);
				$iApproved = intval($iApproved);
				$bUserJur = ($bUserJur ? 1 : 0);
				$iPassportNum = $iPassportNum;
				$sPassportGivenName = $oDBHandler->real_escape_string($sPassportGivenName);
				$iPassportGivenDate = intval($sPassportGivenDate);
				$iINN = intval($iINN);
				$sJurForm = $oDBHandler->real_escape_string($sJurForm);
				$iOGRN = strval($iOGRN);
				$iKPP = $iKPP;
				$sJurName = $oDBHandler->real_escape_string($sJurName);
				$sJurAddress = $oDBHandler->real_escape_string($sJurAddress);
                $sJurAddressCell = $oDBHandler->real_escape_string($sJurAddressCell);
                $sMailAddress = $oDBHandler->real_escape_string($sMailAddress);
//				$iAccNumber = strval($iAccNumber);
				$iBIK = intval($iBIK);
				$sChiefFIO = $oDBHandler->real_escape_string($sChiefFIO);
				$sJurBase = $oDBHandler->real_escape_string($sJurBase);
                $sPassportDivisionCode = $oDBHandler->real_escape_string($sPassportDivisionCode);

				$sNewUserQuery = "INSERT INTO `" . DB_USERS_TABLE . "` 
				(
				
				`first_name`
				,`second_name`
				,`last_name`
				, `address`,`address_cell` ,`phone`, `email`, `admin`, " .

					"`password`, " .
					"`approved`, " .
					"`defCurrency`, " . 
					"`defLang`, " . 
					"`defWUnit`, " . 
					"`defVUnit`, " . 
					
					"`isJur`, " . 
					"`passportnum`, " . 
					"`givenName`, " . 
					"`givenDate`, " . 
					"`inn`, " . 
					"`jurForm`, " . 
					"`OGRN`, " . 
					"`KPP`, " . 
					"`jurName`, " . 
					"`jurAddress`, " .
                    "`jur_address_cell`, " .
                    "`mailAddress`, " .
					"`accNumber`, " . 
					"`BIK`, " . 
					"`chiefFIO`, " . 
					"`jurBase`, " .
                    "`passportDivisionCode`, " .
                    "`is_deleted`) " .
					"VALUES (\"" . $sUserName
					."\", \"".$sUserSecondName."\", \"".$sUserLastName
					."\", \"" . $sUserAddress . "\", \"" . $sUserAddressCell . "\", " . $sUserPhone . ", \"" . $sUserEmail . "\", " .
					($bAdminFlag ? 1 : 0) . ", " .
					//"\"" . $sUserVKID . "\", " .
					"PASSWORD(\"" . $sUserPassword . "\"), " . 
					//$iUserPassport . ", " .
					$iApproved . ", " . 
					"\"" . $sUserDefCurr . "\", " . 
					"\"" . $sUserDefLang . "\", " . 
					$iUserDefWUnit . ", " . 
					$iUserDefVUnit . ", " . 
					
					($bUserJur ? 1 : 0) . ", " . 
					$iPassportNum . ", " . 
					"\"" . $sPassportGivenName . "\", " . 
					"FROM_UNIXTIME(" . $iPassportGivenDate . "), " . 
					$iINN . ", " . 
					"\"" . $sJurForm . "\", " .
                    "\"" .$iOGRN . "\", " .
					$iKPP . ", " . 
					"\"" . $sJurName . "\", " . 
					"\"" . $sJurAddress . "\", " .
                    "\"" . $sJurAddressCell . "\", " .
                    "\"" . $sMailAddress . "\", " .
                    "\"" .$iAccNumber ."\", " .
					$iBIK . ", " . 
					"\"" . $sChiefFIO . "\", " . 
					"\"" . $sJurBase . "\", " .
                    "\"" . $sPassportDivisionCode . "\", " .
                    "0 )";
					
				$oDBHandler->query($sNewUserQuery);
               // echo $sNewUserQuery; die;
				if ($oDBHandler->error)
					return USER_DB_ERROR;

				if ($oDBHandler->affected_rows > 0)
					{
						$this->objectOK = true;
						$this->userID = $oDBHandler->insert_id;
						$this->userName = $sUserName;
						$this->userSecondName = $sUserSecondName;
						$this->userLastName = $sUserLastName;
						$this->userEMail = $sUserEmail;
						$this->userPhone = $sUserPhone;
						$this->userAddress = $sUserAddress;
                        $this->userAddressCell = $sUserAddressCell;
						$this->userDefLang = $sUserDefLang;
						$this->userDefCurr = $sUserDefCurr;
						$this->userDefWUnit = $iUserDefWUnit;
						$this->userDefVUnit = $iUserDefVUnit;
						$this->isAdmin = $bAdminFlag;

						$this->userIsJur = ($bUserJur ? true : false);
						$this->userPassportNum = $iPassportNum;
						$this->userPassportGivenDate = intval($sPassportGivenDate);
						$this->userPassportGivenName = $sPassportGivenName;
						$this->userINN = $iINN;
						$this->userJurForm = $sJurForm;
						$this->userOGRN = $iOGRN;
						$this->userKPP = $iKPP;
						$this->userJurName = $sJurName;
						$this->userJurAddress = $sJurAddress;
                        $this->userJurAddressCell = $sJurAddressCell;
						$this->userMailAddress = $sMailAddress;
						$this->userAccNumber = $iAccNumber;
						$this->userBIK = $iBIK;
						$this->userChiefName = $sChiefFIO;
						$this->userJurBase = $sJurBase;
                        $this->passportDivisionCode = $sPassportDivisionCode;
						return USER_OK;
					}
				else
					return USER_DB_ERROR;
			}


    /** Delete User
     * @param $oDBHandler
     * @return int - Error message
     */
		public function DeleteUser($oDBHandler)
		{
			if ((!$this->objectOK) or ($this->userID < 1))
				return USER_NO_PARAMS;
			
			//$sDeleteQuery = "DELETE FROM `" . DB_USERS_TABLE . "` WHERE `id` = " . intval($this->userID) . " AND `approved` = 1";
			$sDeleteQuery = "UPDATE `" . DB_USERS_TABLE . "` SET `email` = CONCAT(`email`, ':DEL'), `is_deleted` = 1, `approved` = 0 WHERE  `id` = ". intval($this->userID);
			$oDBHandler->query($sDeleteQuery);
            //var_dump($oDBHandler); die;
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


		public function UserExist($oDBHandler, $sUserEmail = "")
		{
            $sSearchQuery = "SELECT *, unix_timestamp(givenDate) AS u_givenDate 
							 FROM `" . DB_USERS_TABLE . "` 
							 WHERE (`email` = '" . $oDBHandler->real_escape_string($sUserEmail) . "' AND `is_deleted` = 0)";

            $oSearchResult = $oDBHandler->query($sSearchQuery);

            if ($oDBHandler->affected_rows > 0)
            {
                $oRow = $oSearchResult->fetch_assoc();

                $this->objectOK = true;

                return USER_OK;
            }
            else
            {
                $this->objectOK = false;
                return USER_NOT_FOUND;
            }
		}

    /** Search user by Phone or Email
     * @param $oDBHandler - DB connection
     * @param string $sUserPhone
     * @param string $sUserEmail
     * @return int - Error message
     */
		public function UserFromSearch($oDBHandler, $sUserPhone = "", $sUserEmail = "") //, $sUserVKID = "", $iUserPassport = 0)
		{
			$sSearchQuery = "SELECT *, unix_timestamp(givenDate) AS u_givenDate FROM `" . DB_USERS_TABLE . "` WHERE ";
			$sSearchClause = " (`approved` = 1)";// AND `is_deleted` = 1";//"(`approved` = 1)";
			
			if ($sUserPhone != "")
				{
					$sSearchClause .= $sSearchClause == "" ? "" : " AND ";
					$sSearchClause .= "(`phone` = \"" . $oDBHandler->real_escape_string($sUserPhone) . "\")";
				}
			
			if ($sUserEmail != "")
				{
					$sSearchClause .= $sSearchClause == "" ? "" : " AND ";
					$sSearchClause .= "(`email` = \"" . $oDBHandler->real_escape_string($sUserEmail) . "\")";
				}

			$sSearchQuery .= $sSearchClause;

            $oSearchResult = $oDBHandler->query($sSearchQuery);

			if ($oDBHandler->affected_rows > 0)
				{
					$oRow = $oSearchResult->fetch_assoc();				
					
					$this->objectOK = true;
					$this->isApproved = $oRow["approved"];
					$this->userID = $oRow["id"];
					$this->userName = $oRow["first_name"];
					$this->userSecondName = $oRow["second_name"];
					$this->userLastName = $oRow["last_name"];
					$this->userEMail = $oRow["email"];
					$this->userPhone = $oRow["phone"];
					$this->userAddress = $oRow["address"];
                    $this->userAddressCell = $oRow["address_cell"];
					$this->userDefLang = $oRow["defLang"];
					$this->userDefCurr = $oRow["defCurrency"];
					$this->userDefWUnit = $oRow["defWUnit"];
					$this->userDefVUnit = $oRow["defVUnit"];
					$this->isAdmin = $oRow["admin"] > 0 ? true : false;

					$this->userIsJur = ($oRow["isJur"] > 0 ? true : false);
					$this->userPassportNum = $oRow["defVUnit"];
					$this->userPassportGivenDate = intval($oRow["u_givenDate"]);
					$this->userPassportGivenName = $oRow["givenName"];
					$this->userINN = intval($oRow["INN"]);
					$this->userJurForm = $oRow["jurForm"];
					$this->userOGRN = $oRow["OGRN"];
					$this->userKPP = $oRow["KPP"];
					$this->userJurName = $oRow["jurName"];
					$this->userJurAddress = $oRow["jurAddress"];
                    $this->userJurAddressCell = $oRow["jur_address_cell"];
					$this->userMailAddress = $oRow["mailAddress"];
					$this->userAccNumber = $oRow["accNumber"];
					$this->userBIK = intval($oRow["BIK"]);
					$this->userChiefName = $oRow["chiefFIO"];
					$this->userJurBase = $oRow["jurBase"];
                    $this->passportDivisionCode = $oRow["passportDivisionCode"];

					return USER_OK;
				}
			else
				{
					$this->objectOK = false;
					return USER_NOT_FOUND;
				}
		}

		/** Get user by ID
		 * @param $oDBHandler - DB connection
		 * @param $iUserID - User ID
		 * @return int - Error message
		 */
		public function UserFromID($oDBHandler, $iUserID)
		{
			$sSearchQuery = "SELECT *, unix_timestamp(givenDate) AS u_givenDate FROM `" . DB_USERS_TABLE . "` WHERE `id` = " . intval($iUserID);// . " AND `approved` = 1";
			
			$oSearchResult = $oDBHandler->query($sSearchQuery);
			//if(!IS_PRODUCTION) echo $sSearchQuery;
			if ($oDBHandler->affected_rows > 0)
				{
					$oRow = $oSearchResult->fetch_assoc();				
					
					$this->objectOK = true;
					$this->userID = $oRow["id"];
                    $this->isApproved = $oRow["approved"];
					$this->userName = $oRow["first_name"];
					$this->userSecondName = $oRow["second_name"];
					$this->userLastName = $oRow["last_name"];
					$this->userEMail = $oRow["email"];
					$this->userPhone = $oRow["phone"];
					$this->userAddress = $oRow["address"];
                    $this->userAddressCell = $oRow["address_cell"];
					$this->userDefLang = $oRow["defLang"];
					$this->userDefCurr = $oRow["defCurrency"];
					$this->userDefWUnit = $oRow["defWUnit"];
					$this->userDefVUnit = $oRow["defVUnit"];

					$this->isAdmin = ($oRow["admin"] > 0 ? true : false);

					$this->userIsJur = ($oRow["isJur"] > 0 ? true : false);
					$this->userPassportNum = $oRow["passportnum"];
					$this->userPassportGivenDate = intval($oRow["u_givenDate"]);
					$this->userPassportGivenName = $oRow["givenName"];
					$this->userINN = intval($oRow["INN"]);
					$this->userJurForm = $oRow["jurForm"];
					$this->userOGRN = $oRow["OGRN"];
					$this->userKPP = $oRow["KPP"];
					$this->userJurName = $oRow["jurName"];
					$this->userJurAddress = $oRow["jurAddress"];
                    $this->userJurAddressCell = $oRow["jur_address_cell"];
					$this->userMailAddress = $oRow["mailAddress"];
					$this->userAccNumber = $oRow["accNumber"];
					$this->userBIK = intval($oRow["BIK"]);
					$this->userChiefName = $oRow["chiefFIO"];
					$this->userJurBase = $oRow["jurBase"];
                    $this->passportDivisionCode = $oRow["passportDivisionCode"];
//					$this->documentTypeId = $oRow["docTypeId"];


					return USER_OK;
				}
			else
				{
					$this->objectOK = false;
					return USER_NOT_FOUND;
				}
		}

    /** Change user password
     * @param $oDBHandler - DB connection
     * @param $sNewPass - new user password
     * @return int - Error message
     */
		public function ChangePass($oDBHandler, $sNewPass)
		{
			if ((!$this->objectOK) or ($this->userID < 1))
				return USER_NO_PARAMS;
			
			$sChPassQuery = "UPDATE `" . DB_USERS_TABLE . "` SET `password` = PASSWORD(\"" . $oDBHandler->real_escape_string($sNewPass) .
								"\") WHERE `id` = " . intval($this->userID) . " AND `approved` = 1";
			$oDBHandler->query($sChPassQuery);
			
			if ($oDBHandler->affected_rows == 1)
				return USER_OK;
			else
				return USER_DB_ERROR;
		}

        /** Check user password
         * @param $oDBHandler - DB connection
         * @param $sOldPass - old user password
         * @return int - Error message
         */
        public function CheckPass($oDBHandler, $sOldPass)
        {
            if ((!$this->objectOK) or ($this->userID < 1))
                return USER_NO_PARAMS;

            $sChPassQuery = "SELECT 1 FROM `" . DB_USERS_TABLE . "`
                WHERE  `password` = PASSWORD(\"" . $oDBHandler->real_escape_string($sOldPass) ."\") AND  `id` = " . intval($this->userID) . " AND `approved` = 1";
            $oDBHandler->query($sChPassQuery);

            if ($oDBHandler->affected_rows == 1)
                return USER_OK;
            else
                return USER_DB_ERROR;
        }

    /** Save changes for user
     * @param $oDBHandler - DB connection
     * @return int - Error message
     */
		public function SaveUser($oDBHandler)
		{
			//var_dump($this->userID );
			if (($this->userID < 1)) {
				return USER_NO_PARAMS;
			}
			//print_r($this); die();
//			var_dump($oDBHandler->real_escape_string($this->userAccNumber) ); die;
			$sEditUserQuery = "UPDATE `" . DB_USERS_TABLE . "` SET " .
			                    "`accNumber` = '" . $oDBHandler->real_escape_string($this->userAccNumber) . "', " .
								"`first_name` = '" . $oDBHandler->real_escape_string($this->userName) . "', " .
								"`second_name` = '" . $oDBHandler->real_escape_string($this->userSecondName) . "', " .
								"`last_name` = '" . $oDBHandler->real_escape_string($this->userLastName) . "', " .
								"`address` = '" . $oDBHandler->real_escape_string($this->userAddress) . "', " .
                				"`address_cell` = '" . $oDBHandler->real_escape_string($this->userAddressCell) . "', " .
								"`phone` = '" . $oDBHandler->real_escape_string($this->userPhone). "', " .
								"`email` = '" . $oDBHandler->real_escape_string($this->userEMail) . "', " .

								"`admin` = " . ($this->isAdmin ? 1 : 0) . ", " .
								"`defLang` = '" . $oDBHandler->real_escape_string($this->userDefLang) . "', " .
								"`defCurrency` = '" . $oDBHandler->real_escape_string($this->userDefCurr) . "', " .
								"`defWUnit` = '" . $oDBHandler->real_escape_string($this->userDefWUnit) . "', " .
								"`defVUnit` = '" . $oDBHandler->real_escape_string($this->userDefVUnit) . "', " .
								
								"`isJur` = " . ($this->userIsJur ? 1 : 0) . ", " .
								"`jurFormId` = " . intval($this->legalFormId) . ", " .
			                    "`docTypeId` = " . intval($this->docTypeId) . ", " .
								"`passportnum` = '" . $this->userPassportNum . "', " .
								"`givenName` = '" . $oDBHandler->real_escape_string($this->userPassportGivenName) . "', " .
								"`givenDate` = FROM_UNIXTIME(" . intval($this->userPassportGivenDate) . "), " .
								"`inn` = " . strval($this->userINN) . ", " .
								"`jurForm` = '" . $oDBHandler->real_escape_string($this->userJurForm) . "', " .
								"`OGRN` = '" . $oDBHandler->real_escape_string($this->userOGRN) . "', " .
								"`KPP` = '" . $oDBHandler->real_escape_string($this->userKPP) . "', " .
								"`jurName` = '" . $oDBHandler->real_escape_string($this->userJurName) . "', " .
								"`jurAddress` = '" . $oDBHandler->real_escape_string($this->userJurAddress) . "', " .
                				"`jur_address_cell` = '" . $oDBHandler->real_escape_string($this->userJurAddressCell) . "', " .
								"`mailAddress` = '" . $oDBHandler->real_escape_string($this->userMailAddress) . "', " .
								"`BIK` = " . intval($this->userBIK) . ", " .
								"`chiefFIO` = '" . $oDBHandler->real_escape_string($this->userChiefName) . "', " .
								"`jurBase` = '" . $oDBHandler->real_escape_string($this->userJurBase) . "', " .
                				"`passportDivisionCode` = " . intval($this->passportDivisionCode) . " " .

                " WHERE `id` = " . intval($this->userID);
			//echo '2';
       /*    if(!IS_PRODUCTION) {
                  print($sEditUserQuery);
                  die();
            }

*/
       $oDBHandler->query($sEditUserQuery);
		//	echo '<br>';
		//	print($oDBHandler->affected_rows);die();

		/*	if ($oDBHandler->error)
				return USER_DB_ERROR;

            return USER_OK;
*/
		
			if ($oDBHandler->affected_rows == 1)
				return USER_OK;
			else
				return USER_DB_ERROR;

		}

    /** Search users by phone or email
     * @param $oDBHandler
     * @param string $sUserPhone
     * @param string $sUserEmail
     * @param int $limit
     * @param int $offset
     * @return array|int
     */
		public function UsersFromSearch($oDBHandler, $sUserPhone = "", $sUserEmail = "", 
							//$sUserVKID = "", 
							//$iUserPassport = 0,
							$limit = 0, $offset = 0)
			{
			// compile search clause
				$sSearchClause = "(`approved` = 1)";
				
				if ($sUserPhone != "")
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "`phone` LIKE \"%" . $oDBHandler->real_escape_string($sUserPhone) . "%\"";
					}
				
				if ($sUserEmail != "")
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "`email` LIKE '%" . $oDBHandler->real_escape_string($sUserEmail) . "%'";
					}

				// compiling limit /
				/* TODO: need to check */
				$sLimitClause = "";
				if (intval($offset) > 0)
					{
						$sLimitClause = "LIMIT " . $offset;
						if (intval($limit) > 0)
							$sLimitClause .= ", " . $limit;
					}
				
				$sSearchQuery = "SELECT *,unix_timestamp(givenDate) AS u_givenDate FROM `" . DB_USERS_TABLE . "` WHERE " . $sSearchClause . " " . $sLimitClause;
				$oSearchResult = $oDBHandler->query($sSearchQuery);
				
				if ($oDBHandler->error)
					return USER_DB_ERROR;
				
				// compile ret array
				$aUsers = array();
				
				while($oRow = $oSearchResult->fetch_assoc())
					{
						$oTemp = new User();
						$oTemp->userID = $oRow["id"];
						$oTemp->userName = $oRow["first_name"];
						$oTemp->userSecondName = $oRow["second_name"];
						$oTemp->userLastName = $oRow["last_name"];
						$oTemp->userEMail = $oRow["email"];
						$oTemp->userPhone = $oRow["phone"];
						$oTemp->userAddress = $oRow["address"];
                        $oTemp->userAddressCell = $oRow["address_cell"];
						$oTemp->userDefLang = $oRow["defLang"];
						$oTemp->userDefCurr = $oRow["defCurrency"];
						$oTemp->userDefWUnit = $oRow["defWUnit"];
						$oTemp->userDefVUnit = $oRow["defVUnit"];
						$oTemp->isAdmin = ($oRow["admin"] > 0 ? true : false);
						$oTemp->objectOK = true;

						$oTemp->userIsJur = ($oRow["isJur"] > 0 ? true : false);
						$oTemp->userPassportNum = $oRow["defVUnit"];
						$oTemp->userPassportGivenDate = intval($oRow["u_givenDate"]);
						$oTemp->userPassportGivenName = $oRow["givenName"];
						$oTemp->userINN = intval($oRow["INN"]);
						$oTemp->userJurForm = $oRow["jurForm"];
						$oTemp->userOGRN = $oRow["OGRN"];
						$oTemp->userKPP = $oRow["KPP"];
						$oTemp->userJurName = $oRow["jurName"];
						$oTemp->userJurAddress = $oRow["jurAddress"];
                        $oTemp->userJurAddressCell = $oRow["jur_address_cell"];
						$oTemp->userMailAddress = $oRow["mailAddress"];
						$oTemp->userAccNumber = $oRow["accNumber"];
						$oTemp->userBIK = intval($oRow["BIK"]);
						$oTemp->userChiefName = $oRow["chiefFIO"];
						$oTemp->userJurBase = $oRow["jurBase"];
                        $oTemp->passportDivisionCode = $oRow["passportDivisionCode"];
						//print_r($oTemp);
						$aUsers[] = $oTemp;
					}
				return $aUsers;
			}
		
    /** Search user by phone or email
     * @param $oDBHandler
     * @param string $sUserPhone
     * @param string $sUserEmail
     * @return int
     */
		public function UsersCountFromSearch($oDBHandler, $sUserPhone = "", $sUserEmail = "")
							//, $sUserVKID = "", 
							//$iUserPassport = 0)
		{
				// compile search clause
				$sSearchClause = "(`approved` = 1)";
				
				if ($sUserPhone != "")
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "`phone` LIKE \"%" . $oDBHandler->real_escape_string($sUserPhone) . "%\"";
					}
				
				if ($sUserEmail != "")
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "`email` LIKE '%" . $oDBHandler->real_escape_string($sUserEmail) . "%'";
					}

				$sSearchQuery = "SELECT COUNT(*) AS cnt FROM `" . DB_USERS_TABLE . "` WHERE " . $sSearchClause;
				$oSearchResult = $oDBHandler->query($sSearchQuery);
				
				if ($oDBHandler->error)
					return USER_DB_ERROR;
				
				$oRow = $oSearchResult->fetch_assoc();
				$iCount = $oRow["cnt"];
				return $iCount;
		}

		public function GetUsers($oDBHandler, $keyword = '', $limit = 0, $offset = 0, $sort_col, $order) {
			if ($keyword == ''){
				$query = "SELECT a.*, (select MAX(s.timestamp) from ".DB_SESSIONS_TABLE." s where s.uid = a.id) as last_login ".
				"FROM ".DB_USERS_TABLE ." a  WHERE `is_deleted` = 0 ";

				$cquery = "SELECT COUNT(*) AS scount FROM ". DB_USERS_TABLE . " WHERE `is_deleted` = 0";
			}

			else{
                $query = "SELECT a.*,(select MAX(s.timestamp) from ". DB_SESSIONS_TABLE ." s where s.uid = a.id) as last_login ".
                "FROM " . DB_USERS_TABLE . " a ".
                "WHERE `first_name` LIKE '%" .$oDBHandler->real_escape_string($keyword) ."%' ".
				         "OR `last_name` LIKE '%" .$oDBHandler->real_escape_string($keyword). "%' ".
				         "OR `email` LIKE '%" .$oDBHandler->real_escape_string($keyword). "%' ".
				         "OR `phone` LIKE '%" .$oDBHandler->real_escape_string($keyword). "%' ".
				         "OR `jurName` LIKE '%" .$oDBHandler->real_escape_string($keyword). "%' ".
                         "OR `OGRN` LIKE '%" .$oDBHandler->real_escape_string($keyword). "%' ";

				$cquery = "SELECT COUNT(*) AS scount FROM ". DB_USERS_TABLE . " ".
				          "WHERE `first_name` LIKE '%" .$oDBHandler->real_escape_string($keyword) ."%' ".
				          "OR `last_name` LIKE '%" .$oDBHandler->real_escape_string($keyword). "%' ".
				          "OR `email` LIKE '%" .$oDBHandler->real_escape_string($keyword). "%' ".
				          "OR `phone` LIKE '%" .$oDBHandler->real_escape_string($keyword). "%' ".
				          "OR `jurName` LIKE '%" .$oDBHandler->real_escape_string($keyword). "%' ".
				          "OR `OGRN` LIKE '%" .$oDBHandler->real_escape_string($keyword). "%' ";
			}

			$countres = $oDBHandler->query($cquery);
			while($oRow = $countres->fetch_assoc()){
				$count = $oRow['scount'];
			};

			$query .= "ORDER BY ". $sort_col . " " . $order . " ".
			          "LIMIT ". intval($offset) . ", " . intval($limit);
			$res = $oDBHandler->query($query);
			if($res->num_rows > 0){
				while($oRow = $res->fetch_assoc()){
					$tempUsr = new User();
					$tempUsr->userID = $oRow["id"];
					$tempUsr->userName = $oRow["first_name"];
					$tempUsr->userSecondName = $oRow["second_name"];
					$tempUsr->userLastName = $oRow["last_name"];
					$tempUsr->userEMail = $oRow["email"];
					$tempUsr->userPhone = $oRow["phone"];
					$tempUsr->userAddress = $oRow["address"];
					$tempUsr->userAddressCell = $oRow["address_cell"];
					$tempUsr->userDefLang = $oRow["defLang"];
					$tempUsr->userDefCurr = $oRow["defCurrency"];
					$tempUsr->userDefWUnit = $oRow["defWUnit"];
					$tempUsr->userDefVUnit = $oRow["defVUnit"];
					$tempUsr->isAdmin = ($oRow["admin"] > 0 ? true : false);
					$tempUsr->isApproved = ($oRow["approved"] > 0 ? true : false);
					$tempUsr->objectOK = true;
					$tempUsr->userIsJur = ($oRow["isJur"] > 0 ? true : false);
					$tempUsr->userPassportNum = $oRow["defVUnit"];
					$tempUsr->userPassportGivenDate = intval($oRow["u_givenDate"]);
					$tempUsr->userPassportGivenName = $oRow["givenName"];
					$tempUsr->userINN = intval($oRow["INN"]);
					$tempUsr->userJurForm = $oRow["jurForm"];
					$tempUsr->userOGRN = $oRow["OGRN"];
					$tempUsr->userKPP = $oRow["KPP"];
					$tempUsr->userJurName = $oRow["jurName"];
					$tempUsr->userJurAddress = $oRow["jurAddress"];
					$tempUsr->userJurAddressCell = $oRow["jur_address_cell"];
					$tempUsr->userMailAddress = $oRow["mailAddress"];
					$tempUsr->userAccNumber = $oRow["accNumber"];
					$tempUsr->userBIK = intval($oRow["userBIK"]);
					$tempUsr->userChiefName = $oRow["chiefFIO"];
					$tempUsr->userJurBase = $oRow["jurBase"];
					$tempUsr->passportDivisionCode = $oRow["passportDivisionCode"];
					$tempUsr->lastLoginDateTime = $oRow["last_login"];
					$aUsers[] = $tempUsr;
				}
			}
			$result['users'] = $aUsers;
			$result['count'] = $count;
			return $result;
		}

		public function UserForPage($oDBHandler, $userID) {
			$tempUsr = new User();
			$query = "SELECT a.*, (select MAX(s.timestamp) from `" . DB_SESSIONS_TABLE . "` s where s.uid = a.id) as last_login ".
			         "FROM `". DB_USERS_TABLE."` a ".
			         "WHERE a.id = ". intval($userID) . " AND `is_deleted` = 0";
			$res = $oDBHandler->query($query);
			$usr = $res->fetch_assoc();
			$tempUsr->userID = $usr["id"];
			$tempUsr->userName = $usr["first_name"];
			$tempUsr->userSecondName = $usr["second_name"];
			$tempUsr->userLastName = $usr["last_name"];
			$tempUsr->userEMail = $usr["email"];
			$tempUsr->userPhone = $usr["phone"];
			$tempUsr->userAddress = $usr["address"];
			$tempUsr->userAddressCell = $usr["address_cell"];
			$tempUsr->userDefLang = $usr["defLang"];
			$tempUsr->userDefCurr = $usr["defCurrency"];
			$tempUsr->userDefWUnit = $usr["defWUnit"];
			$tempUsr->userDefVUnit = $usr["defVUnit"];
			$tempUsr->isAdmin = ($usr["admin"] > 0 ? true : false);
			$tempUsr->objectOK = true;
			$tempUsr->userIsJur = ($usr["isJur"] > 0 ? true : false);
//			$tempUsr->userPassportNum = $usr["defVUnit"];
			$tempUsr->userPassportGivenDate = strtotime($usr["givenDate"]);
			$tempUsr->userPassportGivenName = $usr["givenName"];
			$tempUsr->userINN = intval($usr["INN"]);
			$tempUsr->userJurForm = $usr["jurForm"];
			$tempUsr->userOGRN = $usr["OGRN"];
			$tempUsr->userKPP = $usr["KPP"];
			$tempUsr->userJurName = $usr["jurName"];
			$tempUsr->userJurAddress = $usr["jurAddress"];
			$tempUsr->userJurAddressCell = $usr["jur_address_cell"];
			$tempUsr->userMailAddress = $usr["mailAddress"];
			$tempUsr->userAccNumber = $usr["accNumber"];
			$tempUsr->userBIK = intval($usr["BIK"]);
			$tempUsr->userChiefName = $usr["chiefFIO"];
			$tempUsr->userJurBase = $usr["jurBase"];
			$tempUsr->passportDivisionCode = $usr["passportDivisionCode"];
			$tempUsr->lastLoginDateTime = $usr["last_login"];
			$tempUsr->isApproved = boolval($usr["approved"]);
			$tempUsr->userPassportNum = $usr["passportnum"];
			$tempUsr->legalFormId = $usr["jurFormId"];
			$tempUsr->docTypeId = $usr["docTypeId"];


			//date query
			$query = "SELECT timestamp FROM " . DB_REGISTRATIONS_TABLE . " WHERE `id` = " . $userID;
			$res = $oDBHandler->query($query);
			if($res->num_rows > 0){
				$reg = $res->fetch_assoc();
				$tempUsr->registered = strtotime($reg['timestamp']);
			}

			return $tempUsr;
		}

		public function SetJurOrApproved($oDBHandler, $userId, $value, $mode) {
			switch($mode) {
				case 'STATUS':
					$col = 'approved';
					break;
				case 'LEGAL':
					$col = 'isJur';
					break;
				default:
					break;
			}
			$query = "UPDATE `".DB_USERS_TABLE ."`".
				" SET `". $col . "` = ". $value .
			    " WHERE `id` = ". $userId . " AND `is_deleted` = 0";
			return $oDBHandler->query($query);
		}

		public function GetJurFormsLists($oDBHandler){
			$query = "SELECT `id`, `name`, `short_name` FROM " . DB_JUR_FORM_TABLE . " WHERE `is_visible` = 1";
			$jurForms = Array();
			$res = $oDBHandler->query($query);
			while($row = $res->fetch_assoc()){
				$temp['id'] = intval($row['id']);
				$temp['name'] = $row['name'];
				$temp['short_name'] = $row['short_name'];
				$jurForms[] = $temp;
			}
			return $jurForms;
		}
	}

?>
