<?php

require_once "./service/config.php";
require_once "./service/service.php";

class OrderTemplate
	{
		public $iTemplateID			=	0;
		public $iTemplateUserID			=	0;
                public $iTemplateCompanyID              =       0;
		public $iTemplateTimestamp			=	0;
		public $sTemplateName			=	"";
		public $sTemplateOrderCargoName			=	"";
		public $sTemplateOrderCargoFrom			=	"";
		public $sTemplateOrderCargoTo			= 	"";
		public $fTemplateOrderCargoWeight		= 	"";
		public $fTemplateOrderCargoVol			= 	0;
		public $fTemplateOrderCargoLength		=	0;
		public $fTemplateOrderCargoWidth			=	0;
		public $fTemplateOrderCargoHeight		=	0;
		public $fTemplateOrderCargoValue			=	0;
		public $sTemplateOrderCargoMethod		=	"";
		public $sTemplateCargoSite				=	"";
                public $sTemplateComment                        =      "";


                public $objectOK				=	false;
		
		private $dirtyData				=	false;
		
		//////////////////////////////////////////////////////////////////////////////////////////////////	
			
		public function NewTemplateFromParameters($oDBHandler,
								$iTemplateUserID = 0,
                                                                $iTemplateCompanyID = 0,
								$sTemplateName = "",
								$sOrderCargoName = "",
								$sOrderCargoFrom = "",
								$sOrderCargoTo = "",
								$fOrderCargoWeight = 0, 
								$fOrderCargoVol = 0,
								$fOrderCargoLength = 0,
								$fOrderCargoWidth = 0,
								$fOrderCargoHeight = 0,
								$fOrderCargoValue = 0,
								$sOrderCargoMethod = "",
								$sOrderCargoSite = "",
								$sOrderComment = "")
			/*
			 * @param MYSQLI	mysqli connect handler
			 * 
			 */
			{				
				if (($iTemplateUserID == 0) or ($sTemplateName == ""))
					return PARCEL_NO_PARAMS;
				
				$iTemplateUserID = intval($iTemplateUserID);
                                $iTemplateCompanyID = intval($iTemplateCompanyID);
				$sTemplateName = $oDBHandler->real_escape_string($sTemplateName);
				$sOrderCargoName = $oDBHandler->real_escape_string($sOrderCargoName);
				$sOrderCargoFrom = $oDBHandler->real_escape_string($sOrderCargoFrom);
				$sOrderCargoTo = $oDBHandler->real_escape_string($sOrderCargoTo);
				$sOrderCargoMethod = $oDBHandler->real_escape_string($sOrderCargoMethod);
				$sOrderCargoSite = $oDBHandler->real_escape_string($sOrderCargoSite);
				$sOrderComment = $oDBHandler->real_escape_string($sOrderComment);
				
				$fOrderCargoWeight = floatval($fOrderCargoWeight);
				$fOrderCargoVol = floatval($fOrderCargoVol);
				$fOrderCargoLength = floatval($fOrderCargoLength);
				$fOrderCargoWidth = floatval($fOrderCargoWidth);
				$fOrderCargoHeight = floatval($fOrderCargoHeight);
				$fOrderCargoValue = floatval($fOrderCargoValue);
				$fOrderCargoPrice = floatval($fOrderCargoPrice);
				
				$sNewOrderQuery = "INSERT INTO `" . DB_TEMPLATES_TABLE . "` (`uid`, `name`, `cargo_name`, `cargo_from`" .
					", `cargo_to`, `cargo_weight`, `cargo_vol`, `cargo_length`, `cargo_width`, `cargo_height`" .
					", `cargo_value`, `cargo_method`, `cargo_site`, `comment`, `companyID`) " .
					"VALUES (" . $iTemplateUserID . ", \"" . $sTemplateName . "\", \"" . $sOrderCargoName . "\", \"" . $sOrderCargoFrom . "\", \"" . $sOrderCargoTo . "\", " .
					$fOrderCargoWeight . ", " . $fOrderCargoVol . ", " . $fOrderCargoLength . ", " . 
					$fOrderCargoWidth . ", " . $fOrderCargoHeight . ", " . $fOrderCargoValue . ", " .
					"\"" . $sOrderCargoMethod . "\", \"" . $sOrderCargoSite . "\", \"" . $sOrderComment . "\", " . $iTemplateCompanyID . ")";
					
				$oInsertResult = $oDBHandler->query($sNewOrderQuery);
				
				if ($oDBHandler->error)
					return PARCEL_EXISTS;
				
                                    if ($oDBHandler->affected_rows > 0) 
                                        {
                                            $this->objectOK = true;
                                            $this->iTemplateID = $oDBHandler->insert_id;

                                            $this->iTemplateTimestamp = time();
                                            $this->iTemplateUserID = $iTemplateUserID;
                                            $this->iTemplateCompanyID = $iTemplateCompanyID;
                                            $this->sTemplateName = $sTemplateName;
                                            $this->sTemplateOrderCargoName = $sOrderCargoName;
                                            $this->sTemplateOrderCargoFrom = $sOrderCargoFrom;
                                            $this->sTemplateOrderCargoTo = $sOrderCargoTo;
                                            $this->sTemplateOrderCargoMethod = $sOrderCargoMethod;
                                            $this->sTemplateCargoSite = $sOrderCargoSite;

                                            $this->fTemplateOrderCargoWeight = $fOrderCargoWeight;
                                            $this->fTemplateOrderCargoVol = $fOrderCargoVol;
                                            $this->fTemplateOrderCargoLength = $fOrderCargoLength;
                                            $this->fTemplateOrderCargoHeight = $fOrderCargoHeight;
                                            $this->fTemplateOrderCargoWidth = $fOrderCargoWidth;

                                            $this->fTemplateOrderCargoValue = $fOrderCargoValue;
                                            $this->sTemplateComment = $sOrderComment;
                                            return $this->iTemplateID;
        } else {
            return PARCEL_DB_ERROR;
        }
    }
				
		//////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function DeleteTemplate($oDBHandler)
		{
                    if ((!$this->objectOK) or ($this->iTemplateID < 1))
                        {
                            return PARCEL_NO_PARAMS;
                        }

                    $sDeleteQuery = "DELETE FROM `" . DB_TEMPLATES_TABLE . "` WHERE `id` = " . intval($this->iTemplateID);
			$oDBHandler->query($sDeleteQuery);
			
			if ($oDBHandler->affected_rows > 0)
				{
					$this->objectOK = false;
					$this->dirtyData = false;
				}
			
			if ($oDBHandler->affected_rows == 1)
				return PARCEL_OK;
			else
				return PARCEL_DB_ERROR;
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////
				
		public function TemplateFromID($oDBHandler, $iOrderID)
		{
			$sSearchQuery = "SELECT *, UNIX_TIMESTAMP(timestamp) AS u_time FROM `" . DB_TEMPLATES_TABLE . 
							"` WHERE `id` = " . intval($iOrderID);
			
			$oSearchResult = $oDBHandler->query($sSearchQuery);
			if ($oDBHandler->affected_rows > 0)
				{
					$oRow = $oSearchResult->fetch_assoc();				

					$this->objectOK = true;
					$this->iTemplateID = $oRow["id"];
						
					$this->iTemplateTimestamp = $oRow["u_time"];
					$this->iTemplateUserID = $oRow["uid"];
                                        $this->iTemplateCompanyID = $oRow["companyID"];
					$this->sTemplateOrderCargoName = $oRow["cargo_name"];
					$this->sTemplateOrderCargoFrom = $oRow["cargo_from"];
					$this->sTemplateOrderCargoTo = $oRow["cargo_to"];
					$this->sTemplateOrderCargoMethod = $oRow["cargo_method"];
					$this->sTemplateCargoSite = $oRow["cargo_site"];

					
					$this->fTemplateOrderCargoWeight = floatval($oRow["cargo_weight"]);
					$this->fTemplateOrderCargoVol = floatval($oRow["cargo_vol"]);
					$this->fTemplateOrderCargoLength = floatval($oRow["cargo_length"]);
					$this->fTemplateOrderCargoHeight = floatval($oRow["cargo_height"]);
					$this->fTemplateOrderCargoWidth = floatval($oRow["cargo_width"]);
					
					$this->fTemplateOrderCargoValue = $oRow["cargo_value"];
                                        $this->sTemplateComment = $oRow["comment"];
					return PARCEL_OK;
				}
			else
				{
					$this->objectOK = false;
					return PARCEL_NOT_FOUND;
				}
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function SaveTemplate($oDBHandler)
		{
			if ((!$this->objectOK) or ($this->iTemplateID < 1))
				return USER_NO_PARAMS;
			
			$sEditTemplateQuery = "UPDATE `" . DB_TEMPLATES_TABLE . "` SET " .
								"`uid` = " . intval($this->iTemplateID) . ", " .
                                                                "`companyID` = " . intval($this->iTemplateCompanyID) . ", " .
								"`name` = \"" . $oDBHandler->real_escape_string($this->sTemplateName) . "\", " .
								"`cargo_name` = \"" . $oDBHandler->real_escape_string($this->sTemplateOrderCargoName) . "\", " .
								"`cargo_from` = \"" . $oDBHandler->real_escape_string($this->sTemplateOrderCargoFrom) . "\", " .
								"`cargo_to` = \"" . $oDBHandler->real_escape_string($this->sTemplateOrderCargoTo) . "\", " .
								"`cargo_weight` = " . floatval($this->fTemplateOrderCargoWeight) . ", " .
								"`cargo_vol` = " . floatval($this->fTemplateOrderCargoVol) . ", " .
								"`cargo_value` = " . floatval($this->fTemplateOrderCargoValue) . ", " .
								"`cargo_length` = " . floatval($this->fTemplateOrderCargoLength) . ", " .
								"`cargo_width` = " . floatval($this->fTemplateOrderCargoWidth) . ", " .
								"`cargo_height` = " . floatval($this->fTemplateOrderCargoHeight) . ", " .
								"`cargo_method` = \"" . $oDBHandler->real_escape_string($this->sTemplateOrderCargoMethod) . "\", " .
								"`cargo_site` = \"" . $oDBHandler->real_escape_string($this->sTemplateCargoSite) . "\", " .
								"`comment` = \"" . $oDBHandler->real_escape_string($this->sTemplateComment) . "\" " .
								" WHERE `id` = " . intval($this->iTemplateID);
			
			$oDBHandler->query($sEditTemplateQuery);			
			//print($oDBHandler->error);
			if ($oDBHandler->error)
				return USER_DB_ERROR;
			
			if ($oDBHandler->affected_rows == 1)
				return USER_OK;
			else
				return USER_DB_ERROR;
		}
                
                ////////////////////////////////////////////////////////////////////////////////
                ////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function TemplatesFromSearch($oDBHandler, $aTemplateIDs = array(), $iTimestampFrom = 0, $iTimestampTo = 0, 
                                                $aUserIDs = array(),
						$sCargoName = "", $sCargoFrom = "", $sCargoTo = "",
						$limit = 0, $offset = 0)
			{
			// compile search clause
				$sSearchClause = "1 ";
				
				if ($iTimestampFrom > 0)
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "(UNIX_TIMESTAMP(orderTemplates.timestamp) >= " . intval($iTimestampFrom) . ")";
					}
				
				if ($iTimestampTo > 0)
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "(UNIX_TIMESTAMP(orderTemplates.timestamp) <= " . intval($iTimestampTo) . ")";
					}
				
				if (count($aUserIDs) > 0)
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "orderTemplates.uid IN (" . $oDBHandler->real_escape_string(implode(", ", $aUserIDs)) . ")";
					}

                                if (count($aTemplateIDs) > 0)
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "orderTemplates.id IN (" . $oDBHandler->real_escape_string(implode(", ", $aTemplateIDs)) . ")";
					}
				
				if ($sCargoName != "")
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "orderTemplates.cargo_name LIKE \"%" . $oDBHandler->real_escape_string($sCargoName) . "%\"";
					}
				
				if ($sCargoFrom != "")
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "orderTemplates.cargo_from LIKE \"%" . $oDBHandler->real_escape_string($sCargoFrom) . "%\"";
					}
					
				if ($sCargoTo != "")
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "orderTemplates.cargo_to LIKE \"%" . $oDBHandler->real_escape_string($sCargoTo) . "%\"";
					}
				
				// compiling limit 
				$sLimitClause = "";
				if (intval($offset) > 0)
					{
						$sLimitClause = "LIMIT " . $offset;
						if (intval($limit) > 0)
							$sLimitClause .= ", " . $limit;
					}
				
				$sSearchQuery = "SELECT *, UNIX_TIMESTAMP(timestamp) AS u_time FROM `" . DB_TEMPLATES_TABLE . "` " .
								"WHERE " . $sSearchClause . " " . $sLimitClause;
				
				//print($sSearchQuery);
				//$sSearchQuery = "SELECT parcels.* FROM `" . DB_PARCELS_TABLE . "` " .
					//			"WHERE " . $sSearchClause . " " . $sLimitClause . " GROUP BY parcels.id";
				
				$oSearchResult = $oDBHandler->query($sSearchQuery);
				
				if ($oDBHandler->error)
					return USER_DB_ERROR;
				
				// compile ret array
				$aParcels = array();
				
				while($oRow = $oSearchResult->fetch_assoc())
					{
						$oTemp = new OrderTemplate();
						$oTemp->iTemplateID = $oRow["id"];
						
                                                $oTemp->iTemplateTimestamp = $oRow["u_time"];
                                                $oTemp->iTemplateUserID = $oRow["uid"];
                                                $oTemp->iTemplateCompanyID = $oRow["companyID"];
                                                $oTemp->sTemplateOrderCargoName = $oRow["cargo_name"];
                                                $oTemp->sTemplateOrderCargoFrom = $oRow["cargo_from"];
                                                $oTemp->sTemplateOrderCargoTo = $oRow["cargo_to"];
                                                $oTemp->sTemplateOrderCargoMethod = $oRow["cargo_method"];
                                                $oTemp->sTemplateCargoSite = $oRow["cargo_site"];


                                                $oTemp->fTemplateOrderCargoWeight = floatval($oRow["cargo_weight"]);
                                                $oTemp->fTemplateOrderCargoVol = floatval($oRow["cargo_vol"]);
                                                $oTemp->fTemplateOrderCargoLength = floatval($oRow["cargo_length"]);
                                                $oTemp->fTemplateOrderCargoHeight = floatval($oRow["cargo_height"]);
                                                $oTemp->fTemplateOrderCargoWidth = floatval($oRow["cargo_width"]);

                                                $oTemp->fTemplateOrderCargoValue = $oRow["cargo_value"];
                                                $oTemp->sTemplateComment = $oRow["comment"];
						
						$aOrders[] = $oTemp;
					}
				return $aOrders;
			}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////

                public function TemplatesCountFromSearch($oDBHandler, $aTemplateIDs = array(), $iTimestampFrom = 0, $iTimestampTo = 0, 
                                                $aUserIDs = array(),
						$sCargoName = "", $sCargoFrom = "", $sCargoTo = "")
			{
			// compile search clause
				$sSearchClause = "1 ";
				
				if ($iTimestampFrom > 0)
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "(UNIX_TIMESTAMP(orderTemplates.timestamp) >= " . intval($iTimestampFrom) . ")";
					}
				
				if ($iTimestampTo > 0)
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "(UNIX_TIMESTAMP(orderTemplates.timestamp) <= " . intval($iTimestampTo) . ")";
					}
				
				if (count($aUserIDs) > 0)
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "orderTemplates.uid IN (" . $oDBHandler->real_escape_string(implode(", ", $aUserIDs)) . ")";
					}

                                if (count($aTemplateIDs) > 0)
					{
                                                //print(implode(',',$aTemplateIDs));
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "orderTemplates.id IN (" . $oDBHandler->real_escape_string(implode(", ", $aTemplateIDs)) . ")";
					}
				
				if ($sCargoName != "")
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "orderTemplates.cargo_name LIKE \"%" . $oDBHandler->real_escape_string($sCargoName) . "%\"";
					}
				
				if ($sCargoFrom != "")
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "orderTemplates.cargo_from LIKE \"%" . $oDBHandler->real_escape_string($sCargoFrom) . "%\"";
					}
					
				if ($sCargoTo != "")
					{
						$sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
						$sSearchClause .= "orderTemplates.cargo_to LIKE \"%" . $oDBHandler->real_escape_string($sCargoTo) . "%\"";
					}
				
				// compiling limit 
				$sLimitClause = "";
				if (intval($offset) > 0)
					{
						$sLimitClause = "LIMIT " . $offset;
						if (intval($limit) > 0)
							$sLimitClause .= ", " . $limit;
					}
				
				$sSearchQuery = "SELECT COUNT(*) AS cnt FROM `" . DB_TEMPLATES_TABLE . "` " .
								"WHERE " . $sSearchClause . " " . $sLimitClause;
				
				//print($sSearchQuery);
				//$sSearchQuery = "SELECT parcels.* FROM `" . DB_PARCELS_TABLE . "` " .
					//			"WHERE " . $sSearchClause . " " . $sLimitClause . " GROUP BY parcels.id";
				
				$oSearchResult = $oDBHandler->query($sSearchQuery);
				//print($oDBHandler->error);
				if ($oDBHandler->error)
					return USER_DB_ERROR;
				
				// compile ret array
				$aParcels = array();
				
                                $oRow = $oSearchResult->fetch_assoc();
				
				return $oRow["cnt"];
                                
                            }
	}

?>
