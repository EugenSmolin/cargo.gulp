<?php

require_once "./service/config.php";
require_once "./service/service.php";

class Finance
	{
		public $iOperationID	 	= 0;
		public $fOperationValue		= 0;
		public $iOperationUserID	= 0;
		public $iOperationOrderID	= 0;
		public $iOperationTimestamp = 0;
		public $sOperationDesc		= "";
		public $objectOK			= false;

    	public $fOrderSum			= 0;
    	public $fPayedSum			= 0;
    	public $dPayedDate			= 0;
    	public $sPayerName			= "";
    	public $sPaymentTypeName	= "";
		public $sPayerTypeName		= "";
		private $dirtyData	=	false;
		
		//////////////////////////////////////////////////////////////////////////////////////////////////	
			
		public function NewOperation($oDBHandler, $fOperationValue, $iOperationUserID, $iOperationOrderID = 0, $sOperationDesc)
			/*
			 * @param MYSQLI	mysqli connect handler
			 * 
			 */
			{
				//echo $sOperationDesc,' $iOperationUserID:', $iOperationUserID; die;
				if (($sOperationDesc == "") or ($iOperationUserID <= 0))
					return USER_NO_PARAMS;
				
				$sOperationDesc = $oDBHandler->real_escape_string($sOperationDesc);
				$fOperationValue = floatval($fOperationValue);
				$iOperationUserID = intval($iOperationUserID);
				$iOperationOrderID = intval($iOperationOrderID);
				
				$sNewOperationQuery = "INSERT INTO `" . DB_FINOPERATIONS_TABLE . "` (`value`, `user_id`, `order_id`, `description`) " .
					"VALUES (" . $fOperationValue . ", " . $iOperationUserID . ", " . $iOperationOrderID . ", " .
					"\"" .  $sOperationDesc . "\")";
					
				$oInsertResult = $oDBHandler->query($sNewOperationQuery);
								
				if ($oDBHandler->error)
					return USER_EXISTS;
				
				if ($oDBHandler->affected_rows > 0)
					{
						$this->objectOK = true;
						$this->iOperationID = $oDBHandler->insert_id;
						$this->fOperationValue = $fOperationValue;
						$this->iOperationUserID = $iOperationUserID;
						$this->iOperationOrderID = $iOperationOrderID;
						$this->sOperationDesc = $sOperationDesc;
						$this->iOperationTimestamp = time();
						return USER_OK;
					}
				else
					return USER_DB_ERROR;
			}


		public function NewPayment($oDBHandler, $iOrderId,$sBankOrderId,$sFormUrl)
		{

			if(!isset($iOrderId) || $iOrderId==0)
			{
				$objectOK = false;
				return 'Order not set';
			}

            if(!isset($sBankOrderId) || $sBankOrderId=='')
            {
                $objectOK = false;
                return 'BankOrderId not set';
            }

            if(!isset($sFormUrl) || $sFormUrl=='')
            {
                $objectOK = false;
                return 'FormUrl not set';
            }

			$sSearchQuery = "INSERT INTO `" . DB_ORDER_PAYMENT . "`
				(orderId, alfaOrderId, isPayed, formUrl)
				VALUES ($iOrderId, '".$sBankOrderId."', 0, '".$sFormUrl."')";

			$oSearchResult = $oDBHandler->query($sSearchQuery);

			$oRow = $oSearchResult->fetch_assoc();

			return '';
		}

		public function PaymentInfo($oDBHandler, $iOrderId)
		{

			if(!isset($iOrderId) || $iOrderId==0)
			{
				$objectOK = false;
				return 'Order not set';
			}

			$sSearchQuery = "SELECT orderId, alfaOrderId, isPayed, formUrl
							 FROM `" . DB_ORDER_PAYMENT . "`
							 WHERE orderId=".$iOrderId;

			$oSearchResult = $oDBHandler->query($sSearchQuery);

            if ($oDBHandler->affected_rows > 0)
            {
                $oRow = $oSearchResult->fetch_assoc();

                $this->objectOK = true;
                $this->iOperationID = $oRow["id"];
                $this->fOperationValue = $oRow["value"];
                $this->iOperationUserID = $oRow["user_id"];
                $this->iOperationOrderID = $oRow["order_id"];

                return USER_OK;
            }
            else
            {
                $this->objectOK = false;
                return USER_NOT_FOUND;
            }

			return '';
		}

		public function FinishPayment($oDBHandler, $iOrderId)
		{

			if(!isset($iOrderId) || $iOrderId==0)
			{
				$objectOK = false;
				return 'Order not set';
			}

			$sSearchQuery = "UPDATE order_payment
								SET isPayed=1
								WHERE orderId=".$iOrderId;

			$oSearchResult = $oDBHandler->query($sSearchQuery);

			$oRow = $oSearchResult->fetch_assoc();

			return '';
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function UserBalance($oDBHandler, $iUserID)
			{
				$sSearchQuery = "SELECT SUM(value) as balance FROM `" . DB_FINOPERATIONS_TABLE . "` WHERE `user_id` = " . intval($iUserID);
				
				$oSearchResult = $oDBHandler->query($sSearchQuery);
				
				$oRow = $oSearchResult->fetch_assoc();				
				
				return floatval($oRow["balance"]);
			}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function ParcelBalance($oDBHandler, $iOrderID)
			{
				$sSearchQuery = "SELECT SUM(value) as balance FROM `" . DB_FINOPERATIONS_TABLE . "` WHERE `order_id` = " . intval($iOrderID);
				
				$oSearchResult = $oDBHandler->query($sSearchQuery);
				
				$oRow = $oSearchResult->fetch_assoc();				
				
				return floatval($oRow["balance"]);
			}
	
		//////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function OperationFromID($oDBHandler, $iOperationID)
		{
			$sSearchQuery = "SELECT * FROM `" . DB_FINOPERATIONS_TABLE . "` WHERE `id` = " . intval($iOperationID);
			
			$oSearchResult = $oDBHandler->query($sSearchQuery);
			if ($oDBHandler->affected_rows > 0)
				{
					$oRow = $oSearchResult->fetch_assoc();				
					
					$this->objectOK = true;
					$this->iOperationID = $oRow["id"];
					$this->fOperationValue = $oRow["value"];
					$this->iOperationUserID = $oRow["user_id"];
					$this->iOperationOrderID = $oRow["order_id"];
					
					return USER_OK;
				}
			else
				{
					$this->objectOK = false;
					return USER_NOT_FOUND;
				}
		}

    public function RecordCountFromSearch($oDBHandler,
                                          $iUserID,
                                          $isAdmin,
                                          $start_date,
                                          $finish_date)
    {
        $userQuery = '';
        if($isAdmin!=1)
        {
            $userQuery = " ord.client_id = ".intval($iUserID)." AND ";
        }

        $query ='';
        if($start_date != '' and $finish_date != '')
        {
            $query = ' UNIX_TIMESTAMP(ord.timestamp) between '.$start_date.' AND ('.$finish_date.'  + 86400) ';
        }

		$sSearchQuery = "SELECT COUNT(*) as cnt
								FROM `".DB_ORDERS_TABLE."` ord
								JOIN `".DB_PAYMENT_TYPE."` p ON p.id = ord.payment_type_id
								JOIN `".DB_USERS_TABLE."` a ON ord.client_id = a.id
								JOIN `".DB_PAYMENT_TYPE."` pt ON ord.payer_type_id = pt.id
								WHERE  ord.company_internal_number is not null  AND ".$userQuery.$query ;

		//echo $sSearchQuery; die();

		$oSearchResult = $oDBHandler->query($sSearchQuery);

		if(!IS_PRODUCTION)
		{
			echo $sSearchQuery;
		}

        if ($oDBHandler->error)
            return USER_DB_ERROR;

        $oRow = $oSearchResult->fetch_assoc();

        return $oRow["cnt"];
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function OperationsList($oDBHandler,
									   $iUserID,
									   $isAdmin,
									   $iOffset = 0,
                                       $iLimit  = 0,
                                       $sSortColumn ='orderId',
                                       $sOrderType = 'DESC',
                                       $start_date = '',
                                       $finish_date = '' )
			{

                $sColumnValue = 'order_id';
                switch($sSortColumn)
                {
                    case 'orderId' :
                        $sColumnValue = 'order_id';
                        break;
                    case 'orderSum' :
                        $sColumnValue = 'order_sum';
                        break;
                    case 'payedDate' :
                        $sColumnValue = 'u_time';
                        break;
                        case 'payedSum' :
                        $sColumnValue = 'payed_sum';
                        break;
                    case 'payerName' :
                        $sColumnValue = 'payer_name';
                        break;
                    case 'payerTypeName' :
                        $sColumnValue = 'payer_type_name';
                        break;
                    case 'un_paid_sum' :
                        $sColumnValue = '(order_sum-if(payed_sum is null,0,payed_sum))';
                        break;

                }

                if($sOrderType =='')
                {
                    $sOrderType = 'DESC';
                }

                $sOrderValue = " ORDER BY ".$sColumnValue." ".$sOrderType;
                $sLimitClause = " LIMIT " . $iOffset;
                    if (intval($iLimit) > 0)
                        $sLimitClause .= ", " . $iLimit;

				$userQuery = '';
				if($isAdmin!=1)
				{
                    $userQuery = " ord.client_id = ".intval($iUserID)." AND ";
				}

                $query ='';
                if($start_date != '' and $finish_date != '')
                {
                    $query = ' UNIX_TIMESTAMP(ord.timestamp) between '.$start_date.' AND ('.$finish_date.'  + 86400) ';
                }

				$sSearchQuery = "SELECT t.*
									FROM (
										select if(ord.payer_type_id = 1,
														(SELECT (SELECT  if(a.isJur=1, a.jurName, concat (a.second_name,' ',a.first_name, ' ' ,a.last_name))
														FROM `orders` o
														JOIN accounts a ON a.id = o.client_id
														WHERE o.id = ord.id)),
														(if(ord.payer_type_id = 2, 
															(SELECT (SELECT  if(s.is_legal_entity=1, s.company_name, concat (s.person_second_name,' ',s.person_first_name, ' ' ,s.person_last_name))
															FROM `orders` o
															JOIN senders s ON s.id = o.sender_id
															WHERE o.id = ord.id)),
															
														   (SELECT  if(r.is_legal_entity=1, r.company_name, concat (r.person_second_name,' ',r.person_first_name, ' ' ,r.person_last_name))
															FROM `orders` o
															JOIN recipients r ON r.id = o.recipient_id
															WHERE o.id = ord.id)
															)
														)
											) payer_name,
											ord.id order_id,
										   UNIX_TIMESTAMP(ord.timestamp) AS u_time,
										   ord.cargo_price AS order_sum,
										   if(ord.payment_type_id <11,(select value from `fin_history` where order_id=ord.id and value >0 limit 1),'--')AS payed_sum,
										   (select UNIX_TIMESTAMP(timestamp) from `fin_history` where order_id=ord.id and value >0 limit 1) AS payed_date,
										   p.name   AS payment_type_name,
										   pt.name AS payer_type_name	
										FROM `".DB_ORDERS_TABLE."` ord
										JOIN `".DB_PAYMENT_TYPE."` p ON p.id = ord.payment_type_id
										JOIN `".DB_USERS_TABLE."` a ON ord.client_id = a.id
										JOIN `".DB_PAYER_TYPE."` pt ON ord.payer_type_id = pt.id
										WHERE  ord.company_internal_number is not null  AND ".$userQuery.$query."
									) t"
									.$sOrderValue
								.$sLimitClause ;

				//echo $sSearchQuery; die();

				$oSearchResult = $oDBHandler->query($sSearchQuery);

				if(!IS_PRODUCTION)
				{
					echo "<br>".$sSearchQuery."<br>";
				}

				if ($oDBHandler->error)
					return USER_DB_ERROR;
				
				// compile ret array
				$aTariffs = array();
				
				while($oRow = $oSearchResult->fetch_assoc())
					{
						$oTemp = new Finance();
						$oTemp->iOperationID = intval($oRow["order_id"]);
						$oTemp->fOrderSum = floatval($oRow["order_sum"]);
						$oTemp->iOperationOrderID = intval($oRow["order_id"]);
                        $oTemp->fPayedSum = $oRow["payed_sum"];
						$oTemp->iOperationTimestamp = intval($oRow["u_time"]);

						$oTemp->dPayedDate = $oRow["payed_date"];
                        $oTemp->sPayerName = $oRow["payer_name"];
                        $oTemp->sPaymentTypeName = $oRow["payment_type_name"];
                        $oTemp->sPayerTypeName = $oRow["payer_type_name"];
						$oTemp->objectOK = true;
						//print_r($oTemp);
						$aTariffs[] = $oTemp;
					}
				return $aTariffs;
			}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////

        public function TotalData($oDBHandler,
                                      $iUserID,
                                  	  $isAdmin,
                                      $start_date = '',
                                      $finish_date = '' )
        {
            $query ='';
            $userQuery = '';
            if($isAdmin!=1)
            {
                $userQuery = " ord.client_id = ".intval($iUserID)." AND ";
            }
            if($start_date != '' and $finish_date != '')
            {
                $query = ' AND UNIX_TIMESTAMP(o.timestamp) between '.$start_date.' AND '.$finish_date. ' + 86400 ';
            }
            $sSearchQuery = "SELECT (
								 SELECT SUM(o.cargo_price)
								 FROM  `orders`  o
								 WHERE o.client_id = ". intval($iUserID).$query."
								) as order_sum,
                                      if(t.payed_sum is null,0,t.payed_sum) as payed_sum,
		                            if(t.payed_sum =0,0,t.order_sum) AS un_payed_sum
                                FROM	(SELECT SUM( o.cargo_price) AS order_sum,
                                       SUM((SELECT  value
                                            FROM `fin_history`
                                            WHERE order_id=o.id AND VALUE >0 limit 1)) AS payed_sum
                                FROM  `".DB_ORDERS_TABLE."`  o
                                WHERE " .$userQuery ." payment_type_id < 10 ".$query.") t"
                                            ;

            $oSearchResult = $oDBHandler->query($sSearchQuery);

            if(!IS_PRODUCTION)
            {
                echo "<br>".$sSearchQuery."<br>";
            }

            if ($oDBHandler->error)
                return USER_DB_ERROR;

            // compile ret array
            $aTotalData = array();

            while($oRow = $oSearchResult->fetch_assoc())
            {

                $aTotalData = [
                    "totalCost"=>floatval($oRow["order_sum"]),
                    "totalPaymentArrears"=>floatval($oRow["un_payed_sum"]),
                    "totalPayed"=>floatval($oRow["payed_sum"]),
                ];
            }
            return $aTotalData;
        }
	}

?>
