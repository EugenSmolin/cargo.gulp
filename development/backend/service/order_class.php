<?php


require_once "./service/config.php";
require_once "./service/service.php";
require_once "./service/contact_class.php";

class Order
{
    public $fOrderStatusID          =   0;
    public $iOrderID				=	0;
    public $iCompanyID              =   0;
    public $iOrderRecipientId		=	0;
    public $iOrderClientID			=	0;
    public $iOrderTimestamp			=	0;
    public $sOrderCargoName			=	"";
    public $sCargoIconUrl			=	"";
    public $sCargoEmail	    		=	"";
    public $sCargoPhone 			=	"";
    public $sCargoCountry 			=	"";

    public $iOrderCityFromId		=	0;
    public $iOrderCityToId			= 	0;

    public $sOrderCargoFrom			=	"";
    public $sOrderCargoTo			= 	"";
    public $fOrderCargoWeight		= 	"";
    public $fOrderCargoVol			= 	0;
    public $fOrderCargoLength		=	0;
    public $fOrderCargoWidth		=	0;
    public $fOrderCargoHeight		=	0;
    public $fOrderCargoValue		=	0;

    public $fOrderCargoPaid         =   0;
	public $fOrderCargoOrigPrice    =   0;

    public $fOrderCargoPrice		=	0;
    public $sOrderCargoMethod		=	"";
    public $sCargoSite				=	"";
    public $sOrderComment			=	"";
    public $sOrderDesiredDate       =   "";
    public $sOrderDeliveryDate       =   "";

    public $iClientId               = 0;
    public $sClientFirstName        =   "";
    public $sClientSecondName       =   "";
    public $sClientLastName       	=   "";
    public $sClientEmail       		=   "";
    public $sClientPhone       		=   "";
    public $sClientAddress      	=   "";
    public $sClientAddressCell     	=   "";
    public $sClientDocumentType     =   "";
    public $sClientCompanyFormName  =   "";

    public $iClientIsLegal      	=   "";
    public $sClientCompanyName     	=   "";
    public $sClientCompanyInn      	=   "";
    public $sClientOGRN      	    =   "";
    public $sClientAccNumber      	=   "";
    public $sClientCompanyAddress  	=   "";
    public $sClientCompanyAddressCell      	=   "";

    public $sOrderRecipientFirstName       	=   "";
    public $sOrderRecipientSecondName      	=   "";
    public $sOrderRecipientLastName        	=   "";
    public $iOrderRecipientDocumentTypeId   =   0;
    public $sOrderRecipientDocumentNumber   =   "";

    public $iOrderRecipientLegalEntity		=   0;

    public $sOrderRecipientPhone            =   "";
    public $sOrderRecipientEmail            =   "";

    public $sOrderRecipientCompanyName  	=	"";
    public $sOrderRecipientCompanyFormName		=   "";
    public $iOrderRecipientCompanyFormId  	=	0;
    public $sOrderRecipientDocumentType		=   "";
    public $iOrderRecipientCompanyInn  		=	0;
    public $sOrderRecipientCompanyPhone		=   "";
    public $sOrderRecipientCompanyEmail		=   "";
    public $sOrderRecipientCompanyContactFirstName    =   "";
    public $sOrderRecipientCompanyContactSecondName    =   "";
    public $sOrderRecipientCompanyAddress    =   "";
    public $sOrderRecipientCompanyAddressCell    =   "";

    public $sOrderSenderFirstName          	=   "";
    public $sOrderSenderSecondName        	=   "";
    public $sOrderSenderLastName           	=   "";

    public $sOrderSenderDocumentType        =   "";

    public $sOrderSenderCompanyName        =   "";
    public $sOrderSenderCompanyFormName    =   "";
    public $iOrderSenderCompanyInn         =   0;
    public $sOrderSenderCompanyContactFirstName    =   "";
    public $sOrderSenderCompanyContactSecondName    =   "";
    public $sOrderSenderCompanyAddress    =   "";
    public $sOrderSenderCompanyAddressCell    =   "";

    public $sOrderSenderEmail      			=   "";
    public $sOrderSenderPhone   			=   "";
    public $iOrderSenderLegalEntity      	=   0;
    public $sOrderSenderDocumentNumber		=   "";

    public $sOrderSenderCompanyPhone		=   "";
    public $sOrderSenderCompanyEmail		=   "";

    public $sOrderRecipientFullName         =   "";
    public $sOrderSenderFullName           	=   "";

    public $sOrderRecipientAddress         	=   "";
    public $sOrderSenderAddress           	=   "";

    public $sOrderSenderContactPerson		=	"";
    public $sOrderRecipientContactPerson	=	"";

    public $sCargoVolUnitName	            =	"";
    public $sCargoWeightUnitName	        =	"";

    public $iPaymentTypeID                  =   0;
    public $sPaymentTypeName				=	"";
    public $iPayerTypeId				    =	"";
    public $sPayerTypeName				    =	"";

    public $iOrderPlaces                    =   0;
    public $sOrderDangerClassId             =	0;
    public $sOrderDangerClassName			=	"";
    public $sOrderTemperatureModeId         =   0;
    public $sOrderTemperatureModeName       =   "";
    public $sOrderGoodsName                 =   "";

    public $sAddOptions                     =       "";
    public $oSerializedData;
    public $sCompanyInternalNumber          =       "";

    public $isDerivalWithCourier          =  0;
    public $isArrivalWithCourier          =  0;

    public $iPayed                          =	0;
    public $sPayedName                      =	"";

    public $objectOK				=	false;

    private $dirtyData				=	false;

    public $sSerializedFields      = "";

    public $sFinDesc = 'Оплата через админ панель';

    //////////////////////////////////////////////////////////////////////////////////////////////////

	function SetAppNumber($oDBHandler, $orderId = 0){
    if($orderId == 0) {
        $orderId = $this->iOrderID;
    }
	//company id
	$query = "SELECT `companyID`, `company_internal_number` FROM " . DB_ORDERS_TABLE . " WHERE `id` = ". $orderId;
	$res = $oDBHandler->query($query);
	$row = $res->fetch_assoc();
	$companyId = $row['companyID'];
	//company internal number
	$transportNum = $row['company_internal_number'];

	//application prefix
	$query = "SELECT `application_prefix` FROM ". DB_COMPANY_TABLE . " WHERE `id` = ". $companyId;
	$prefix = $oDBHandler->query($query)->fetch_assoc()['application_prefix'];

	//application number

    //if the first order of the company
	$appNum = 1;
	$query = "SELECT `application_number` AS num FROM ". DB_APPLICATIONS_TABLE . " WHERE `application_number` LIKE '%" . $prefix . "%' ".
	         "ORDER BY `application_number` DESC LIMIT 0, 1";
	$res = $oDBHandler->query($query);
	if($res->num_rows > 0){
	    //if company already has existing orders
	    $row = $res->fetch_assoc();
	    $appNum = $row['num'];
		$appNum = intval(str_replace($prefix . "-", '', $appNum));
		$appNum++;
    }

	//echo $appNum; //die;
	$appNum = sprintf("%07d", $appNum);
	$appNum = $prefix . "-" . $appNum;

	$query = "INSERT INTO ". DB_APPLICATIONS_TABLE . " (`order_id`, `transport_internal_number`, `application_number`) VALUES (".
	         $orderId . ", ".
	         "'" . $transportNum . "', ".
	         "'" . $appNum . "');";
	$oDBHandler->query($query);
}

    public  function SetOrderCompanyInternalNumber($oDBHandler,$iOrderID,
                                                   $sCompanyInternalNumber)
    {

        $query = "UPDATE `". DB_ORDERS_TABLE . "` ".
            "SET ".
            "`company_internal_number` = '" .
            $oDBHandler->real_escape_string($sCompanyInternalNumber) . "' ".
            "WHERE `id` = ". $oDBHandler->real_escape_string($iOrderID);
        return $oDBHandler->query($query);
    }

    public function GetClientBy($oDBHandler,$table_name,
                                $is_legal_entity=0,
                                $sOrderCargoPhone='',
                                $sOrderCargoEmail='',
                                $sOrderCargoFirstName='',
                                $sOrderCargoSecondName='',
                                $sOrderCargoLastName='',
                                $sOrderCargoDocumentTypeId=0,
                                $sOrderCargoDocumentNumber=0,
                                $sOrderCargoCompanyName='',
                                $sOrderCargoCompanyFormId=1,
                                $sOrderCargoCompanyPhone="",
                                $sOrderCargoCompanyEmail="",
                                $sOrderCargoCompanyInn = "",
                                $sOrderCargoCompanyAddress = "",
                                $sOrderCargoCompanyAddressCell = "",
                                $sOrderCargoCompanyContactPersonFirstName="",
                                $sOrderCargoCompanyContactPersonSecondName=""

    )
    {
        $sSearchQuery = '';
        if($is_legal_entity)
        {
            $sSearchQuery = "SELECT id	FROM `" . $table_name. "` " .
                "WHERE company_inn='".$sOrderCargoCompanyInn."'
				 AND company_name ='".$sOrderCargoCompanyName."'";
        }
        else
        {
            $sSearchQuery = "SELECT id	FROM `" . $table_name. "` " .
                "WHERE phone_number='" . $sOrderCargoPhone . "'
							AND email ='$sOrderCargoEmail'
							AND person_first_name ='$sOrderCargoFirstName'
							AND person_second_name ='$sOrderCargoSecondName'";
        }


        $oSearchResult = $oDBHandler->query($sSearchQuery);
        //if(IS_DEBUG)echo $sSearchQuery,'<br>';
        //echo  $sSearchQuery; die;//,'<br>', $oDBHandler->error;
        $result = new stdClass();

        if ($oDBHandler->error) {
            $result->id = 0;
            $result->status = 'error';
            $result->error = USER_DB_ERROR;
            return $result;
        }
        // compile ret array
        $iRecipientID = 1;

        if ($oDBHandler->affected_rows > 0)
        {
            $oRow = $oSearchResult->fetch_assoc();
            $updateQuery = "";
            $clientId= intval($oRow["id"]);

            if(!$is_legal_entity)
            {
                $updateQuery = "
                UPDATE `".$table_name."`
                SET person_last_name='$sOrderCargoLastName',
                    person_document_type_id=$sOrderCargoDocumentTypeId,
                    person_document_number='$sOrderCargoDocumentNumber'
                WHERE id=".$clientId;
            }
            else
            {
                $updateQuery = "UPDATE `".$table_name."`
                SET company_form_id=".$sOrderCargoCompanyFormId.",
                    company_phone='".$sOrderCargoCompanyPhone."',
                    company_email='".$sOrderCargoCompanyEmail."',
                    company_address='$sOrderCargoCompanyAddress',
                    company_address_cell='".$sOrderCargoCompanyAddressCell."',
                    contact_person_first_name='".$sOrderCargoCompanyContactPersonFirstName."',
                    contact_person_second_name='".$sOrderCargoCompanyContactPersonSecondName."'
                  WHERE id= ".$clientId;
            }

            $oResult = $oDBHandler->query($updateQuery);

            $result->id = intval($oRow["id"]);
            $result->status = 'ok';
            $result->error = '';
            return $result;
        }
        else
        {
            $sNewOrderQuery = "INSERT INTO `" . $table_name . "`
						 (phone_number,
						  email,
						  is_legal_entity,
						  person_first_name,
						  person_second_name,
						  person_last_name,
						  person_document_type_id,
						  person_document_number,
						  company_name,
						  company_form_id,
						  company_inn,
						  company_phone,
						  company_email,
						  company_address,
						  company_address_cell,
						  contact_person_first_name,
						  contact_person_second_name)
						VALUES(
						  '" . $sOrderCargoPhone . "',
						  '" . $sOrderCargoEmail . "',
						  " . $is_legal_entity . ",
						  '" . $sOrderCargoFirstName . "',
						  '" . $sOrderCargoSecondName . "',
						  '" . $sOrderCargoLastName . "',
						  '" . $sOrderCargoDocumentTypeId . "',
						  '" . $sOrderCargoDocumentNumber . "',
						  '" . $sOrderCargoCompanyName . "',
						  " . $sOrderCargoCompanyFormId . ",
						  '" . $sOrderCargoCompanyInn . "',
						  '" . $sOrderCargoCompanyPhone . "',
						  '" . $sOrderCargoCompanyEmail . "',
						  '" . $sOrderCargoCompanyAddress . "',
						  '" . $sOrderCargoCompanyAddressCell . "',
						  '" . $sOrderCargoCompanyContactPersonFirstName . "',
						  '" . $sOrderCargoCompanyContactPersonSecondName . "'
						)";
            $oInsertResult = $oDBHandler->query($sNewOrderQuery);
            //if(IS_DEBUG)echo $sNewOrderQuery,'<br>';
            /*
            echo $sOrderCargoRecipientPhone,' ',
                                $sOrderCargoRecipientEmail,' ',
                                $sOrderCargoRecipientFirstName,' ',
                                $sOrderCargoRecipientSecondName,' ',
                                $sOrderCargoRecipientLastName,'<br> ';


            echo  $sNewOrderQuery,'<br>', $oInsertResult->error;
            */

            if ($oDBHandler->error)
            {
                $result->id = PARCEL_DB_ERROR;
                $result->status = 'PARCEL_DB_ERROR';
                $result->error = $oDBHandler->error;
                return $result;
            }

            if ($oDBHandler->affected_rows > 0) {
                $result->id = $oDBHandler->insert_id;
                $result->status = 'ok';
                $result->error = '';
                return $result;
            }
        }

    }

    public function GetClientIDBy($oDBHandler,$orderId)
    {
        $result = new stdClass();

        if (!isset($orderId)||$orderId==0) {
            $result->id = -1  ;
            $result->status = 'ORDER_NOT_SET';
            $result->error = "OrderID is not set";
            return $result;
        }

        $sSearchQuery = "SELECT client_id	FROM `" . DB_ORDERS_TABLE. "` " .
                        "WHERE id =".$orderId;

        $oSearchResult = $oDBHandler->query($sSearchQuery);
        $result = new stdClass();

        if ($oDBHandler->error) {
            $result->id = USER_DB_ERROR;
            $result->status = 'USER_DB_ERROR';
            $result->error = $oDBHandler->error;
            return $result;
        }
        if ($oDBHandler->affected_rows > 0) {
            $oRow = $oSearchResult->fetch_assoc();
            $result->id = intval($oRow["client_id"]);
            $result->status = 'ok';
            $result->error = '';
            return $result;
        }
        else
        {
            $result->id = -1  ;
            $result->status = 'ORDER_NOT_EXIST';
            $result->error = "OrderID is not EXIST";
            return $result;
        }
    }

    public function  GetCityBy($oDBHandler,
                               $oCoordinate,
                               $sCityName="",
                               $sCityZip = "",
                               $sCityRegion ="")
    {
        $res = $oCoordinate;
        $south=0.0;
        $west=0.0;
        $north=0.0;
        $east=0.0;
        if (isset($res->south))
        {
            $south = floatval($res->south);
        }
        if (isset($res->south))
        {
            $west = floatval($res->west);
        }
        if (isset($res->south))
        {
            $north = floatval($res->north);
        }
        if (isset($res->south))
        {
            $east = floatval($res->east);
        }
        $where = "WHERE `name`='$sCityName'";
        if (isset($sCityZip) and $sCityZip!='')
        {
            $where .=" and zip_code='$sCityZip'";
        }

        $sSearchQuery = "SELECT id	FROM `" . DB_CITY_TABLE. "` " .$where;

        $oSearchResult = $oDBHandler->query($sSearchQuery);

        $result = new stdClass();

        if ($oDBHandler->error) {
            $result->id = USER_DB_ERROR;
            $result->status = 'USER_DB_ERROR';
            $result->error = $oDBHandler->error;
            return $result;
        }

        if ($oDBHandler->affected_rows > 0)
        {
            $oRow = $oSearchResult->fetch_assoc();

            $result->id =  intval($oRow["id"]);
            $result->status = 'ok';
            $result->error = '';
            return $result;
        }
        else
        {
            $sNewOrderQuery = "INSERT INTO `" . DB_CITY_TABLE . "`
						 (`name`, south_coordinate,
						  west_coordinate, north_coordinate, 
						  east_coordinate, zip_code, region
						  )
						VALUES(
						  '".$sCityName."',
							".$south.",
							".$west.",
							".$north.",
							".$east.",
							'".$sCityZip."',
							'".$sCityRegion."'
						)";

            $oInsertResult = $oDBHandler->query($sNewOrderQuery);

            if ($oDBHandler->error)
            {
                $result->id =  PARCEL_DB_ERROR;
                $result->status = 'PARCEL_DB_ERROR';
                $result->error = $oDBHandler->error;
                return $result;
            }


            if ($oDBHandler->affected_rows > 0)
            {
                $result->id =  $oDBHandler->insert_id;
                $result->status = 'ok';
                $result->error = '';
                return $result;
            }
        }
    }


    public function NewOrder(
        $oDBHandler,
        $iOrderClientID = 0,
        $iOrderCargoCompanyID = 0,
        $sOrderCargoName = "",
        $sOrderCargoFromCoordinate = null,
        $sOrderCargoToCoordinate = null,
        $sOrderCargoFrom = "",
        $sOrderCargoTo = "",
        $sOrderCargoFromZip = "",
        $sOrderCargoToZip = "",
        $sOrderCargoFromRegion = "",
        $sOrderCargoToRegion = "",
        $fOrderCargoWeight = 0,
        $fOrderCargoVol = 0,
        $fOrderCargoLength = 0,
        $fOrderCargoWidth = 0,
        $fOrderCargoHeight = 0,
        $fOrderCargoValue = 0,
        $fOrderCargoPrice = 0,
        $sOrderCargoMethod = "",
        $sOrderCargoSite = "",
        $sOrderComment = "",
        $iOrderDangerClassId = 1 ,
        $iOrderTemperatureModeId = 1,
        $sOrderGoodsName = "",
        $sDesiredDate = 0,
        $sDeliveryDate = 0,
        $sSerializedData = '{}',
        $iPaymentType = 0,
        $iPayerType = 0,

        $isSenderPhysOrJur = false,

        $sOrderCargoSenderFirstName ="",
        $sOrderCargoSenderLastName = "",
        $sOrderCargoSenderSecondName="",
        $sOrderCargoSenderPhone = "",
        $sOrderCargoSenderEmail = "",
        $iOrderCargoSenderDocumentTypeId = 0,
        $sOrderCargoSenderDocumentNumber ="",

        $sOrderCargoSenderCompanyName = "",
        $iOrderCargoSenderCompanyFormId = 0,
        $sOrderCargoSenderCompanyPhone = "",
        $sOrderCargoSenderCompanyEmail = "",
        $sOrderCargoSenderCompanyINN = "",
        $sOrderCargoSenderCompanyAddress = "",
        $sOrderCargoSenderCompanyAddressCell = "",
        $sOrderCargoSenderCompanyContactFirstName = "",
        $sOrderCargoSenderCompanyContactLastName = "",

        $iSenderTerminalId = 0,
        $sSenderTerminalName = "",
        $sCargoSenderAddress = "",
        $sCargoSenderAddressCode = "",
        $sCargoSenderAddressHouseNumber = "",
        $sCargoSenderAddressBuildingNumber = "",
        $sCargoSenderAddressStructureNumber = "",
        $sCargoSenderAddressCell = "",

        $isRecipientPhysOrJur = false,

        $sOrderCargoRecipientPhone = "",
        $sOrderCargoRecipientEmail = "",
        $sOrderCargoRecipientFirstName = "",
        $sOrderCargoRecipientSecondName = "",
        $sOrderCargoRecipientLastName = "",
        $iOrderCargoRecipientDocumentTypeId = 1,
        $sOrderCargoRecipientDocumentNumber = "",

        $sOrderCargoRecipientCompanyName = "",
        $iOrderCargoRecipientCompanyFormId = 0,
        $sOrderCargoRecipientCompanyPhone = "",
        $sOrderCargoRecipientCompanyEmail = "",
        $sOrderCargoRecipientCompanyINN = "",
        $sOrderCargoRecipientCompanyAddress = "",
        $sOrderCargoRecipientCompanyAddressCell = "",
        $sOrderCargoRecipientCompanyContactFirstName = "",
        $sOrderCargoRecipientCompanyContactLastName = "",

        $iRecipientTerminalId = 0,
        $sRecipientTerminalName = "",
        $sCargoRecipientAddress = "",
        $sCargoRecipientAddressCode = "",
        $sCargoRecipientAddressHouseNumber = "",
        $sCargoRecipientAddressBuildingNumber = "",
        $sCargoRecipientAddressStructureNumber = "",
        $sCargoRecipientAddressCell = "",
        $isDerivalCourier = false,
        $isArrivalCourier = false,
        $sCargoVolUnitName="",
        $sCargoWeightUnitName=""
    )
    {

        if(!IS_PRODUCTION)
        {
            echo 'Create db order<br>';
           /* echo $sDesiredDate ,' ' ,$sDeliveryDate,'<br>' ;
            echo date('d-m-Y',$sDesiredDate) ,' ' ,date('d-m-Y',$sDeliveryDate),'<br>' ;
            echo  $sCargoVolUnitName,   $sCargoWeightUnitName,'<br>';
            *///die();
        }


        if (!isset($iOrderClientID))
        {
            DropWithBadRequest("Not set parameter OrderClientID");
        }

        if (!isset($sOrderCargoFromZip) or ($sOrderCargoFromZip==''))
        {
            DropWithBadRequest("Not set parameter OrderCargoFromZip");
        }
        if (!isset($sOrderCargoToZip) or ($sOrderCargoToZip==''))
        {
            DropWithBadRequest("Not set parameter OrderCargoToZip");
        }
        if (!isset($sOrderCargoFrom) or ($sOrderCargoFrom==''))
        {
            DropWithBadRequest("Not set parameter OrderCargoFrom");
        }
        if (!isset($sOrderCargoTo) or ($sOrderCargoTo==''))
        {
            DropWithBadRequest("Not set parameter OrderCargoTo");
        }
        if (!isset($fOrderCargoWeight) or ($fOrderCargoWeight==0))
        {
            DropWithBadRequest("Not set parameter OrderCargoWeight");
        }
        if (!isset($fOrderCargoPrice) or ($fOrderCargoPrice==0))
        {
            DropWithBadRequest("Not set parameter OrderCargoPrice");
        }
        if (!isset($fOrderCargoVol) or ($fOrderCargoVol==0))
        {
            DropWithBadRequest("Not set parameter OrderCargoVol");
        }
        if (($fOrderCargoLength == 0)
            or ($fOrderCargoWidth == 0)
            or ($fOrderCargoHeight == 0))
        {
            DropWithBadRequest("Not set parameter OrderCargoLength or OrderCargoWidth or OrderCargoHeight");
        }

    /*    if (($iOrderClientID == 0)

            or ($sOrderCargoFromZip == "")
            or ($sOrderCargoToZip == "")
            or ($sOrderCargoFromRegion == "")
            or ($sOrderCargoToRegion == "")

            or ($sOrderCargoFrom == "")
            or ($sOrderCargoTo == "")
            or ($fOrderCargoWeight <= 0)
            or ($fOrderCargoPrice <= 0)

            or (($fOrderCargoVol == 0)
                and
                (($fOrderCargoLength == 0)
                    or ($fOrderCargoWidth == 0)
                    or ($fOrderCargoHeight == 0)))
        )
        {
            if(IS_DEBUG) echo "Bad parameters";
            return PARCEL_NO_PARAMS;
        }
    */

        if (!$isRecipientPhysOrJur)
        {
            if (!isset($sOrderCargoRecipientFirstName))
            {
                DropWithBadRequest("Not set parameter CargoRecipientFirstName");
            }
            if (!isset($sOrderCargoRecipientLastName))
            {
                DropWithBadRequest("Not set parameter CargoRecipientLastName");
            }
            if(!isset($sOrderCargoRecipientDocumentNumber))
            {
                DropWithBadRequest("Not set parameter CargoRecipientDocumentNumber");
            }
            if (!isset($sOrderCargoRecipientPhone))
            {
                DropWithBadRequest("Not enough parameters for selected CargoRecipientPhone");
            }
        }
        elseif ($isRecipientPhysOrJur)
        {
            if (!isset($sOrderCargoRecipientCompanyContactFirstName)) {
                DropWithBadRequest("Not enough parameters for selected CargoRecipientCompanyContactFirstName");
            }
            if (!isset($sOrderCargoRecipientCompanyContactLastName)) {
                DropWithBadRequest("Not enough parameters for selected CargoRecipientCompanyContactLastName");
            }
            if (!isset($sOrderCargoRecipientCompanyName)) {
                DropWithBadRequest("Not enough parameters for selected CargoRecipientPhone");
            }
            if (!isset($iOrderCargoRecipientCompanyFormId)) {
                DropWithBadRequest("Not enough parameters for selected CargoRecipientCompanyFormId");
            }
            if (!isset($sOrderCargoRecipientCompanyPhone)) {
                DropWithBadRequest("Not enough parameters for selected RecipientCompanyPhone");
            }
            if (!isset($sOrderCargoRecipientCompanyINN)) {
                DropWithBadRequest("Not enough parameters for selected RecipientCompanyINN");
            }
            if (!isset($sOrderCargoRecipientCompanyAddress)) {
                DropWithBadRequest("Not enough parameters for selected RecipientCompanyAddress");
            }
        }
        else
        {
            DropWithBadRequest("Not assigned RecipientPhysOrJur parameter");
        }

        if(!$isSenderPhysOrJur)
        {

            if (!isset($sOrderCargoSenderFirstName))
            {
                DropWithBadRequest("Not set parameter CargoSenderCompanyName");
            }
            if (!isset($sOrderCargoSenderLastName))
            {
                DropWithBadRequest("Not set parameter CargoSenderLastName");
            }
            if (!isset($sOrderCargoSenderDocumentNumber))
            {
                DropWithBadRequest("Not set parameter CargoSenderDocumentNumber");
            }
            if (!isset($sOrderCargoSenderPhone))
            {
                DropWithBadRequest("Not enough parameters for selected CargoSenderPhone");
            }
        }
        elseif($isSenderPhysOrJur)
        {
            if (!isset($sOrderCargoSenderCompanyContactFirstName)) {
                DropWithBadRequest("Not set parameter CargoSenderCompanyContactFirstName");
            }
            if (!isset($sOrderCargoSenderCompanyContactLastName)) {
                DropWithBadRequest("Not set parameter CargoSenderCompanyContactLastName");
            }
            if (!isset($sOrderCargoSenderCompanyName)) {
                DropWithBadRequest("Not set parameter CargoSenderCompanyName");
            }
            if (!isset($iOrderCargoSenderCompanyFormId)) {
                DropWithBadRequest("Not set parameter CargoSenderCompanyFormId");
            }
            if (!isset($sOrderCargoSenderCompanyPhone)) {
                DropWithBadRequest("Not set parameter CargoSenderCompanyPhone");
            }
            if (!isset($sOrderCargoSenderCompanyINN)) {
                DropWithBadRequest("Not set parameter CargoSenderCompanyINN");
            }
            if (!isset($sOrderCargoSenderCompanyAddress))
            {
                DropWithBadRequest("Not set parameter CargoSenderCompanyAddress");
            }

        }
        else
        {
            DropWithBadRequest("Not assigned SenderPhysOrJur parameter");
        }


        if(IS_DEBUG) echo "Good parameters";

        $iOrderClientID = intval($iOrderClientID);

        $sOrderCargoName = $oDBHandler->real_escape_string($sOrderCargoName);
        $sOrderCargoFrom = $oDBHandler->real_escape_string($sOrderCargoFrom);
        $sOrderCargoTo = $oDBHandler->real_escape_string($sOrderCargoTo);
        $sOrderCargoMethod = $oDBHandler->real_escape_string($sOrderCargoMethod);
        $sOrderCargoSite = $oDBHandler->real_escape_string($sOrderCargoSite);
        $sOrderComment = $oDBHandler->real_escape_string($sOrderComment);

        $sOrderCargoFromZip = $oDBHandler->real_escape_string($sOrderCargoFromZip);
        $sOrderCargoToZip = $oDBHandler->real_escape_string($sOrderCargoToZip);
        $sOrderCargoFromRegion = $oDBHandler->real_escape_string($sOrderCargoFromRegion);
        $sOrderCargoToRegion = $oDBHandler->real_escape_string($sOrderCargoToRegion);
        $sOrderCargoRecipientCompanyPhone = $oDBHandler->real_escape_string($sOrderCargoRecipientCompanyPhone);
        $sOrderCargoRecipientCompanyEmail = $oDBHandler->real_escape_string($sOrderCargoRecipientCompanyEmail);
        $iPaymentType = intval($iPaymentType);
        $iPayerType = intval($iPayerType);

        $fOrderCargoWeight = floatval($fOrderCargoWeight);
        $fOrderCargoVol = floatval($fOrderCargoVol);
        $fOrderCargoLength = floatval($fOrderCargoLength);
        $fOrderCargoWidth = floatval($fOrderCargoWidth);
        $fOrderCargoHeight = floatval($fOrderCargoHeight);
        $fOrderCargoValue = floatval($fOrderCargoValue);
        $fOrderCargoPrice = floatval($fOrderCargoPrice);

        $sOrderCargoRecipientFirstName = $oDBHandler->real_escape_string($sOrderCargoRecipientFirstName);
        $sOrderCargoRecipientLastName = $oDBHandler->real_escape_string($sOrderCargoRecipientLastName);
        $sOrderCargoRecipientPhone = $oDBHandler->real_escape_string($sOrderCargoRecipientPhone);
        $sOrderCargoRecipientEmail = $oDBHandler->real_escape_string($sOrderCargoRecipientEmail);
        $sOrderCargoRecipientDocumentNumber = $oDBHandler->real_escape_string($sOrderCargoRecipientDocumentNumber);

        $iOrderCargoCompanyID = intval($iOrderCargoCompanyID);
        $iOrderDangerClassId = intval($iOrderDangerClassId);
        $iOrderTemperatureModeId = intval($iOrderTemperatureModeId);
        $sOrderGoodsName = $oDBHandler->real_escape_string($sOrderGoodsName);
        $sSerializedData = $oDBHandler->real_escape_string($sSerializedData);

        //$sOrderCargoRecipientPhone = $oDBHandler->real_escape_string($sOrderCargoRecipientPhone);
        $sOrderCargoRecipientEmail = $oDBHandler->real_escape_string($sOrderCargoRecipientEmail);
        $sOrderCargoRecipientFirstName = $oDBHandler->real_escape_string($sOrderCargoRecipientFirstName);
        $sOrderCargoRecipientSecondName = $oDBHandler->real_escape_string($sOrderCargoRecipientSecondName);
        $sOrderCargoRecipientLastName = $oDBHandler->real_escape_string($sOrderCargoRecipientLastName);
        $iOrderCargoRecipientDocumentTypeId = intval($iOrderCargoRecipientDocumentTypeId);
        $sOrderCargoRecipientDocumentNumber = $oDBHandler->real_escape_string($sOrderCargoRecipientDocumentNumber);

        $sOrderCargoSenderSecondName=$oDBHandler->real_escape_string($sOrderCargoSenderSecondName);
        $sOrderCargoSenderPhone = $oDBHandler->real_escape_string($sOrderCargoSenderPhone);
        $sOrderCargoSenderEmail = $oDBHandler->real_escape_string($sOrderCargoSenderEmail);
        $iOrderCargoSenderDocumentTypeId =intval($iOrderCargoSenderDocumentTypeId);

        $sOrderCargoSenderCompanyName = $oDBHandler->real_escape_string($sOrderCargoSenderCompanyName);
        $iOrderCargoSenderCompanyFormId = intval($iOrderCargoSenderCompanyFormId);
        $sOrderCargoSenderCompanyPhone = $oDBHandler->real_escape_string($sOrderCargoSenderCompanyPhone);
        $sOrderCargoSenderCompanyEmail = $oDBHandler->real_escape_string($sOrderCargoSenderCompanyEmail);
        $sOrderCargoSenderCompanyINN = $oDBHandler->real_escape_string($sOrderCargoSenderCompanyINN);
        $sOrderCargoSenderCompanyAddress = $oDBHandler->real_escape_string($sOrderCargoSenderCompanyAddress);
        $sOrderCargoSenderCompanyAddressCell = $oDBHandler->real_escape_string($sOrderCargoSenderCompanyAddressCell);
        $sOrderCargoSenderCompanyContactFirstName = $oDBHandler->real_escape_string($sOrderCargoSenderCompanyContactFirstName);
        $sOrderCargoSenderCompanyContactLastName = $oDBHandler->real_escape_string($sOrderCargoSenderCompanyContactLastName);
        $sOrderCargoRecipientCompanyContactFirstName = $oDBHandler->real_escape_string($sOrderCargoRecipientCompanyContactFirstName);
        $sOrderCargoRecipientCompanyContactLastName = $oDBHandler->real_escape_string($sOrderCargoRecipientCompanyContactLastName);



        $iSenderTerminalId = intval($iSenderTerminalId);
        $sSenderTerminalName =$oDBHandler->real_escape_string($sSenderTerminalName);
        $sCargoSenderAddress = $oDBHandler->real_escape_string($sCargoSenderAddress);
        $sCargoSenderAddressHouseNumber = $oDBHandler->real_escape_string($sCargoSenderAddressHouseNumber);
        $sCargoSenderAddressBuildingNumber = $oDBHandler->real_escape_string($sCargoSenderAddressBuildingNumber);
        $sCargoSenderAddressStructureNumber = $oDBHandler->real_escape_string($sCargoSenderAddressStructureNumber);
        $sCargoSenderAddressCell = $oDBHandler->real_escape_string($sCargoSenderAddressCell);
        //$sCargoSenderCell = $oDBHandler->real_escape_string($sCargoSenderAddressCell);



        $sOrderCargoRecipientCompanyName = $oDBHandler->real_escape_string($sOrderCargoRecipientCompanyName);
        $iOrderCargoRecipientCompanyFormId = intval($iOrderCargoRecipientCompanyFormId);
        $sOrderCargoRecipientCompanyINN = $oDBHandler->real_escape_string($sOrderCargoRecipientCompanyINN);
        $sOrderCargoRecipientCompanyAddress = $oDBHandler->real_escape_string($sOrderCargoRecipientCompanyAddress);
        $sOrderCargoRecipientCompanyAddressCell = $oDBHandler->real_escape_string($sOrderCargoRecipientCompanyAddressCell);


        $iRecipientTerminalId = intval($iRecipientTerminalId);
        $sRecipientTerminalName = $oDBHandler->real_escape_string($sRecipientTerminalName);
        $sCargoRecipientAddress =$oDBHandler->real_escape_string($sCargoRecipientAddress);
        $sCargoRecipientAddressHouseNumber = $oDBHandler->real_escape_string($sCargoRecipientAddressHouseNumber);
        $sCargoRecipientAddressBuildingNumber = $oDBHandler->real_escape_string($sCargoRecipientAddressBuildingNumber);
        $sCargoRecipientAddressStructureNumber = $oDBHandler->real_escape_string($sCargoRecipientAddressStructureNumber);
        //$sCargoRecipientAddressCode = $oDBHandler->real_escape_string($sCargoRecipientAddressCode);

        $sCargoRecipientAddressCell = $oDBHandler->real_escape_string($sCargoRecipientAddressCell);


        $sRecipientAddress='';
        $sSenderAddress='';
        //   if(IS_DEBUG) echo '$isDerivalCourier = '.intval($isDerivalCourier).'<br>';
        //   if(IS_DEBUG) echo '$isArrivalCourier = '.intval($isArrivalCourier).'<br>';

        if ($isArrivalCourier)
        {
            $sRecipientAddress =
                $sCargoRecipientAddress;
            if($sCargoRecipientAddressHouseNumber!="")
            {
                $sRecipientAddress .=', д. '.$sCargoRecipientAddressHouseNumber;
            }
            if($sCargoRecipientAddressBuildingNumber!="")
            {
                $sRecipientAddress .=', корп. '.$sCargoRecipientAddressBuildingNumber;
            }
            if($sCargoRecipientAddressStructureNumber!="")
            {
                $sRecipientAddress .=', строение. '.$sCargoRecipientAddressStructureNumber;
            }
            if($sCargoRecipientAddressCell!="")
            {
                $sRecipientAddress .=', кв.(оф.) '.$sCargoRecipientAddressCell;
            }
        }
        else
        {
            $sRecipientAddress = $sRecipientTerminalName;
        }

        if ($isDerivalCourier)
        {
            $sSenderAddress = $sCargoSenderAddress;
            if($sCargoSenderAddressHouseNumber!="")
            {
                $sSenderAddress .=', д. '.$sCargoSenderAddressHouseNumber;
            }
            if($sCargoSenderAddressBuildingNumber!="")
            {
                $sSenderAddress .=', корп. '.$sCargoSenderAddressBuildingNumber;
            }
            if($sCargoSenderAddressStructureNumber!="")
            {
                $sSenderAddress .=', строение. '.$sCargoSenderAddressStructureNumber;
            }
            if($sCargoSenderAddressCell!="")
            {
                $sSenderAddress .=', кв.(оф.) '.$sCargoSenderAddressCell;
            }
        }
        else
        {
            $sSenderAddress = $sSenderTerminalName;
        }

        //  if(IS_DEBUG) echo '$sRecipientAddress='.$sRecipientAddress.'<br>';
        //   if(IS_DEBUG) echo '$sSenderAddress='.$sSenderAddress.'<br>';

        //  $iRecipientID from DB

        $iRecipientID = 1;

        if(IS_DEBUG) {
            echo '<br>';
            var_dump($isRecipientPhysOrJur);
            echo '<br>';
        }

        $oContact = new Contact();

        $oRecipientRes =$oContact->GetContactBy(
            $oDBHandler,
            $iOrderClientID,
            intval($isRecipientPhysOrJur),
            $sOrderCargoRecipientPhone,
            $sOrderCargoRecipientEmail,
            $sOrderCargoRecipientFirstName,
            $sOrderCargoRecipientSecondName,
            $sOrderCargoRecipientLastName,
            $iOrderCargoRecipientDocumentTypeId,
            $sOrderCargoRecipientDocumentNumber,
            $sOrderCargoRecipientCompanyName,
            $iOrderCargoRecipientCompanyFormId,
            $sOrderCargoRecipientCompanyPhone,
            $sOrderCargoRecipientCompanyEmail,
            $sOrderCargoRecipientCompanyINN,
            $sOrderCargoRecipientCompanyAddress,
            $sOrderCargoRecipientCompanyAddressCell,
            $sOrderCargoRecipientCompanyContactFirstName,
            $sOrderCargoRecipientCompanyContactLastName
        );

        /*
        $oRecipientRes = $this->GetClientBy($oDBHandler,
            DB_RECIPIENTS_TABLE,
            intval($isRecipientPhysOrJur),
            $sOrderCargoRecipientPhone,
            $sOrderCargoRecipientEmail,
            $sOrderCargoRecipientFirstName,
            $sOrderCargoRecipientSecondName,
            $sOrderCargoRecipientLastName,
            $iOrderCargoRecipientDocumentTypeId,
            $sOrderCargoRecipientDocumentNumber,
            $sOrderCargoRecipientCompanyName,
            $iOrderCargoRecipientCompanyFormId,
            $sOrderCargoRecipientCompanyPhone,
            $sOrderCargoRecipientCompanyEmail,
            $sOrderCargoRecipientCompanyINN,
            $sOrderCargoRecipientCompanyAddress,
            $sOrderCargoRecipientCompanyAddressCell,
            $sOrderCargoRecipientCompanyContactFirstName,
            $sOrderCargoRecipientCompanyContactLastName

        );
*/

        if(IS_DEBUG) {
            echo '<br>';
            var_dump($oRecipientRes);
            echo '<br>';
        }

        if ($oRecipientRes->status == 'error') {
            return $oRecipientRes->error;
        }

        if ($oRecipientRes->status == 'ok') {
            $iRecipientID = $oRecipientRes->id;
        }

        $oContact->SetStatisticForContact($oDBHandler,$iRecipientID,'to');

        $oSenderRes = $oContact->GetContactBy(
            $oDBHandler,
            $iOrderClientID,
            intval($isSenderPhysOrJur),
            $sOrderCargoSenderPhone,
            $sOrderCargoSenderEmail,
            $sOrderCargoSenderFirstName,
            $sOrderCargoSenderSecondName,
            $sOrderCargoSenderLastName,
            $iOrderCargoSenderDocumentTypeId,
            $sOrderCargoSenderDocumentNumber,
            $sOrderCargoSenderCompanyName,
            $iOrderCargoSenderCompanyFormId,
            $sOrderCargoSenderCompanyPhone,
            $sOrderCargoSenderCompanyEmail,
            $sOrderCargoSenderCompanyINN,
            $sOrderCargoSenderCompanyAddress,
            $sOrderCargoSenderCompanyAddressCell,
            $sOrderCargoSenderCompanyContactFirstName ,
            $sOrderCargoSenderCompanyContactLastName
        );

        if ($oSenderRes->status == 'error') {
            return $oSenderRes->error;
        }
        //echo '<br>';var_dump($oSenderRes);
        //die();
        if ($oSenderRes->status == 'ok') {
            $iSenderID = $oSenderRes->id;
        }

        $oContact->SetStatisticForContact($oDBHandler,$iSenderID,'from');
        /** @var get $iCityFromId form DB */
        $iCityFromId = 1;

        $oRes = $this->GetCityBy($oDBHandler,
            $sOrderCargoFromCoordinate,
            $sOrderCargoFrom,
            $sOrderCargoFromZip,
            $sOrderCargoFromRegion);

        if ($oRes->status == 'error') {
            return $oRes->error;
        }

        if ($oRes->status == 'ok') {
            $iCityFromId = $oRes->id;
        }

        /** @var get $iCityToId form DB */

        $oRes = $this->GetCityBy($oDBHandler,
            $sOrderCargoToCoordinate,
            $sOrderCargoTo,
            $sOrderCargoToZip,
            $sOrderCargoToRegion);

        if ($oRes->status == 'error') {
            return $oRes->error;
        }

        if ($oRes->status == 'ok') {
            $iCityToId = $oRes->id;
        }

        $sFullRecipientAddress = $sRecipientAddress;
        $sFullSenderAddress = $sSenderAddress;

        // Save new order in DB

        $sNewOrderQuery = "INSERT INTO `" . DB_ORDERS_TABLE .
            "` (
                	sender_id,
					recipient_id,
					client_id,
					recipient_address,
					sender_address,
					is_derival_with_courier,
					is_arrival_with_courier,
					companyID,
					cargo_name,
					city_from_id,
					city_to_id,
					cargo_from,
					cargo_to,
					cargo_weight,
					cargo_vol,
					cargo_length,
					cargo_width,
					cargo_height,
					cargo_value,
					cargo_price,
					cargo_method,
					cargo_site,
					cargo_desired_date,
					cargo_delivery_date,
					`comment`,
			
					cargo_danger_class_id,
					cargo_temperature_id,
					cargo_good_name,
			
					serialized_fields,
					payment_type_id,
					payer_type_id,
					cargo_vol_unit_name,
					cargo_weight_unit_name
			) VALUES ("
            . $iSenderID . ","
            . $iRecipientID . ","
            . $iOrderClientID . ",
			'$sFullRecipientAddress',
			'$sFullSenderAddress',"
            . intval($isDerivalCourier). ","
            . intval($isArrivalCourier).","
            . $iOrderCargoCompanyID . ",
			'$sOrderCargoName'," .
            $iCityFromId . "," .
            $iCityToId . ",
			'$sOrderCargoFrom',
			'$sOrderCargoTo'," .
            $fOrderCargoWeight . "," .
            $fOrderCargoVol . "," .
            $fOrderCargoLength . "," .
            $fOrderCargoWidth . ", " .
            $fOrderCargoHeight . ", " .
            $fOrderCargoValue . ", " .
            $fOrderCargoPrice . ",
		    '$sOrderCargoMethod',
		    '$sOrderCargoSite',
		    '".date("Y-m-d",$sDesiredDate)."',
			'".date("Y-m-d",$sDeliveryDate)."',

    	    '$sOrderComment'," .
            $iOrderDangerClassId . "," .
            $iOrderTemperatureModeId . ",
			'$sOrderGoodsName',
			'$sSerializedData',"
			. $iPaymentType ." ,
			". $iPayerType ." ,
			'".$sCargoVolUnitName."',
			'".$sCargoWeightUnitName."'
		    )";

        $oInsertResult = $oDBHandler->query($sNewOrderQuery);

        if(IS_DEBUG) echo '<br>iSenderID: '.$iSenderID.'<br>';
        if(IS_DEBUG) echo '<br>'.$sNewOrderQuery.'<br>';



        if ($oDBHandler->error)
            DropWithBadValidation("DB error: ".$oDBHandler->error);

        if ($oDBHandler->affected_rows > 0) {
            $this->objectOK = true;
            $this->iOrderID = $oDBHandler->insert_id;
            $this->iCompanyID = $iOrderCargoCompanyID;

            $this->iOrderTimestamp = time();
            $this->iOrderClientID = $iOrderClientID;
            $this->iOrderCityFromId = $iCityFromId;
            $this->iOrderCityToId = $iCityToId;
            $this->sOrderCargoName = $sOrderCargoName;
            $this->sOrderCargoFrom = $sOrderCargoFrom;
            $this->sOrderCargoTo = $sOrderCargoTo;
            $this->sOrderCargoMethod = $sOrderCargoMethod;
            $this->sCargoSite = $sOrderCargoSite;
            $this->sOrderComment = $sOrderComment;
            $this->sOrderDesiredDate = $sDesiredDate;
            $this->sOrderDeliveryDate = $sDeliveryDate;

            $this->fOrderCargoWeight = $fOrderCargoWeight;
            $this->fOrderCargoVol = $fOrderCargoVol;
            $this->fOrderCargoLength = $fOrderCargoLength;
            $this->fOrderCargoHeight = $fOrderCargoHeight;
            $this->fOrderCargoWidth = $fOrderCargoWidth;

            $this->fOrderCargoValue = $fOrderCargoValue;
            $this->fOrderCargoPrice = $fOrderCargoPrice;

            $this->sOrderRecipientPhone = $sOrderCargoRecipientPhone;
            $this->sOrderRecipientEmail = $sOrderCargoRecipientEmail;
            $this->iOrderRecipientDocumentTypeId = $iOrderCargoRecipientDocumentTypeId;
            $this->sOrderRecipientDocumentNumber = $sOrderCargoRecipientDocumentNumber;

            $this->sOrderRecipientFirstName = $sOrderCargoRecipientFirstName;
            $this->sOrderRecipientSecondName = $sOrderCargoRecipientSecondName;
            $this->sOrderRecipientLastName = $sOrderCargoRecipientLastName;

            $this->sOrderDangerClassId = $iOrderDangerClassId;
            $this->sOrderTemperatureModeId = $iOrderTemperatureModeId;
            $this->sOrderGoodsName = $sOrderGoodsName;

            $this->oSerializedData = json_encode($sSerializedData);

            return $this->iOrderID;
        } else
            return PARCEL_DB_ERROR;

    }
    //////////////////////////////////////////////////////////////////////////////////////////////////

    public function DeleteOrder($oDBHandler)
    {
        if ((!$this->objectOK) or ($this->iOrderID < 1))
            return PARCEL_NO_PARAMS;

        $sDeleteQuery = "DELETE FROM `" . DB_ORDERS_TABLE . "` WHERE `id` = " . intval($this->iOrderID);
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

    public function AddOrderEvent($oDBHandler, $iOwnerID = 0, $iOperationID = 0, $iOperationParam1 = 0,
                                  $iOperationParam2 = 0, $fCoordLat = 0, $fCoordLon = 0, $sPlaceName = "",$iCourierID=0)
    {
        if (($this->parcelID == 0) or ($iOperationID == 0))
            return PARCEL_NO_PARAMS;

        // parse
        $iOwnerID = intval($iOwnerID);
        $iOperationID = intval($iOperationID);
        $iOperationParam1 = intval($iOperationParam1);
        $iOperationParam2 = intval($iOperationParam2);
        $iCourierID = intval($iCourierID);
        $fCoordLat = floatval($fCoordLat);
        $fCoordLon = floatval($fCoordLon);
        $sPlaceName = $oDBHandler->real_escape_string($sPlaceName);

        $sAddEventQuery = "INSERT INTO `" . DB_EVENT_TABLE . "` (`coords`, `place_name`, `parcel_id`, " .
            "`courier_id`, `operation_id`, `operation_param1`, `operation_param2`) VALUES (" .
            "POINT(" . $fCoordLat. ", " . $fCoordLon . "), \"" . $sPlaceName . "\", "
            . intval($this->parcelID) . ", " .
            $iCourierID . ", " . $iOperationID . ", " . $iOperationParam1 . ", "
            . $iOperationParam2 . ")";

        $oDBHandler->query($sAddEventQuery);


        //print($sAddEventQuery);
        //print($oDBHandler->error);

        if (($oDBHandler->affected_rows > 0) and !$oDBHandler->error)
            return PARCEL_OK;
        else
            return PARCEL_DB_ERROR;
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////

    public function OrderFromID($oDBHandler, $iOrderID)
    {
        //var_dump($iOrderID); die;

        $sSearchQuery = "SELECT f.value as order_paid,
                                a.isJur as client_is_jur,
                                a.jurName as client_company_name,
                                a.INN as client_company_inn,
                                a.OGRN as client_ogrn,
                                a.accNumber as client_accNumber,
                                a.jurAddress as client_company_address,
                                a.jur_address_cell as client_company_address_cell,
                                a.jurForm as client_jur_form,
                                
                                com.icon_url as tk_icon_url,
                                com.email as tk_email,
                                com.country as tk_country,
                                com.phones as tk_phones,

                                a.id as client_id,
								a.first_name as client_first_name,
								a.second_name as client_second_name,
								a.last_name as client_last_name,
								a.email as client_email,
								a.phone as client_phone,
								a.address as client_address,
								a.address_cell as client_address_cell,
								
								o.*,
								r.phone_number as r_phone_number, 
								r.email as r_email, 
								r.is_legal_entity as r_is_legal_entity, 
								r.person_first_name as r_person_first_name, 
								r.person_second_name as r_person_second_name, 
								r.person_last_name as r_person_last_name, 
								r.person_document_type_id as r_person_document_type_id, 
								r.person_document_number as r_person_document_number, 
								r.company_name as r_company_name, 
								r.company_form_id as r_company_form_id, 
								r.company_inn as r_company_inn, 
								r.company_phone as r_company_phone, 
								r.company_email as r_company_email,
								r.company_address as r_company_address,
								r.company_address_cell as r_company_address_cell,
								r.contact_person_first_name as r_contact_person_first_name,
 								r.contact_person_second_name as r_contact_person_second_name,
								
								s.phone_number as s_phone_number, 
								s.email as s_email, 
								s.is_legal_entity as s_is_legal_entity, 
								s.person_first_name as s_person_first_name, 
								s.person_second_name as s_person_second_name, 
								s.person_last_name as s_person_last_name, 
								s.person_document_type_id as s_person_document_type_id, 
								s.person_document_number as s_person_document_number, 
								s.company_name as s_company_name, 
								s.company_form_id as s_company_form_id, 
								s.company_inn as s_company_inn, 
								s.company_phone as s_company_phone, 
								s.company_email as s_company_email,
								s.company_address as s_company_address,
								s.company_address_cell as s_company_address_cell,
								s.contact_person_second_name as s_contact_person_second_name,
								s.contact_person_first_name as s_contact_person_first_name,
								 
								o.id AS order_id,
								o.original_price,
								t.name
								as temperature_mode_name, 
								d.name as danger_class_name ,
								cf.name as city_from_name, 
								ct.name as city_to_name,
								pt.id as payment_type_id,
								pt.name as payment_type_name,
								pyt.id as payer_type_id,
								pyt.name as payer_type_name,
								UNIX_TIMESTAMP(o.timestamp) AS u_time, 
								if(o.payment_type_id in (11,12), 2,
								(SELECT count(p.orderId)
                                 FROM order_payment p
                                 WHERE p.orderId = o.id 
                                 AND p.`status` = 'payed')) is_payed
							 FROM `" . DB_ORDERS_TABLE . "` o
							 JOIN `" . DB_CONTACT_TABLE . "` r ON o.recipient_id=r.id
							 JOIN `" . DB_CONTACT_TABLE . "` s ON o.sender_id=s.id
							 JOIN `" . DB_TEMPERATURE_MODE_TABLE . "`  t ON o.cargo_temperature_id = t.id
							 JOIN `" . DB_DANGER_CLASS_TABLE . "` d ON d.id = o.cargo_danger_class_id
							 JOIN `" . DB_CITY_TABLE . "` cf ON o.city_from_id = cf.id
							 JOIN `" . DB_CITY_TABLE . "` ct ON o.city_to_id = ct.id
							 JOIN `" . DB_PAYMENT_TYPE . "` pt ON o.payment_type_id = pt.id
							 JOIN `" . DB_PAYER_TYPE . "` pyt ON o.payer_type_id = pyt.id
							 JOIN `" . DB_USERS_TABLE . "` a ON o.client_id=a.id
							 LEFT JOIN `" . DB_FINOPERATIONS_TABLE . "` f ON o.id=f.order_id AND f.value > 0
							 LEFT JOIN `" . DB_COMPANY_TABLE . "` com ON com.id=o.companyID
							 WHERE o.id = " . intval($iOrderID);

        $oSearchResult = $oDBHandler->query($sSearchQuery);
//        var_dump($sSearchQuery); die;
        //var_dump($oDBHandler->affected_rows); die;
        if(IS_DEBUG)
            //echo  $sSearchQuery,'<br><br>';
        if(IS_DEBUG)
        {
            //var_dump($oDBHandler);
            //echo '<br>'.$oDBHandler->affected_rows.'<br>';
        }

        if ($oDBHandler->affected_rows > 0 || !IS_PRODUCTION)
        {

            $oRow = $oSearchResult->fetch_assoc();

            $cargoFrom = self::CityCorrection($oRow["cargo_from"]);

            $cargoTo = self::CityCorrection($oRow["cargo_to"]);

            $this->objectOK = true;
            $this->iOrderID = intval($oRow["order_id"]);
            $this->iCompanyID = intval($oRow["companyID"]);
            $this->iOrderRecipientId = $oRow["recipient_id"];
            $this->iOrderTimestamp = intval($oRow["u_time"]);
            $this->iOrderClientID = intval($oRow["client_id"]);
            $this->sOrderCargoName = $oRow["cargo_name"];
            $this->sCargoIconUrl = $oRow["tk_icon_url"];
            $this->sCargoEmail = $oRow["tk_email"];
            $this->sCargoPhone = $oRow["tk_phones"];
            $this->sCargoCountry = $oRow["tk_country"];

            $this->iOrderCityFromId = $oRow["city_from_id"];
            $this->iOrderCityToId = $oRow["city_to_id"];
            $this->sOrderCargoFrom = $cargoFrom;
            $this->sOrderCargoTo = $cargoTo;
            $this->fOrderStatusID = $oRow["order_status_id"];
            $this->sCompanyInternalNumber = $oRow["company_internal_number"];

            $this->sOrderCargoMethod = $oRow["cargo_method"];
            $this->sCargoSite = $oRow["cargo_site"];
            $this->sOrderComment = $oRow["comment"];
            $this->sOrderDesiredDate = $oRow["cargo_desired_date"];
            $this->sOrderDeliveryDate = $oRow["cargo_delivery_date"];

            $this->fOrderCargoWeight = floatval($oRow["cargo_weight"]);
            $this->fOrderCargoVol = floatval($oRow["cargo_vol"]);
            $this->fOrderCargoLength = floatval($oRow["cargo_length"]);
            $this->fOrderCargoHeight = floatval($oRow["cargo_height"]);
            $this->fOrderCargoWidth = floatval($oRow["cargo_width"]);

            $this->fOrderCargoValue = floatval($oRow["cargo_value"]);
            $this->fOrderCargoPrice = floatval($oRow["cargo_price"]);

	        $this->fOrderCargoPaid = floatval($oRow["order_paid"]);
	        $this->fOrderCargoOrigPrice = floatval($oRow["original_price"]);

            $this->sOrderTemperatureModeId = $oRow["cargo_temperature_id"];
            $this->sOrderTemperatureModeName = $oRow["temperature_mode_name"];
            $this->sOrderDangerClassId = $oRow["cargo_danger_class_id"];
            $this->sOrderDangerClassName = $oRow["danger_class_name"];
            $this->sOrderGoodsName = $oRow["cargo_good_name"];
            $this->oSerializedData = json_decode($oRow["serializedFields"]);

            /** Client data */
            $this->iClientId = $oRow["client_id"];
            $this->sClientFirstName = $oRow["client_first_name"];
            $this->sClientSecondName  = $oRow["client_second_name"];
            $this->sClientLastName = $oRow["client_last_name"];
            $this->sClientEmail  = $oRow["client_email"];
            $this->sClientPhone  = $oRow["client_phone"];
            $this->sClientAddress  = $oRow["client_address"];
            $this->sClientAddressCell  = $oRow["client_address_cell"];

            $clientDocumentType = 'Паспорт';
            switch (intval($oRow["r_person_document_type_id"]))
            {
                case 2:
                    $clientDocumentType = "Водительские права";
                    break;
                case 3:
                    $clientDocumentType = "Заграничный паспорт";
                    break;
                default:
                    $clientDocumentType = 'Паспорт';
                    break;
            }

            $this->sClientDocumentType = $clientDocumentType;

            $this->iClientIsLegal  = $oRow["client_is_jur"];
            $this->sClientCompanyName  = $oRow["client_company_name"];
            $this->sClientCompanyInn  = $oRow["client_company_inn"];
            $this->sClientOGRN = $oRow["client_ogrn"];
            $this->sClientAccNumber  = $oRow["client_accNumber"];
            $this->sClientCompanyAddress  = $oRow["client_company_address"];
            $this->sClientCompanyAddressCell  = $oRow["client_company_address_cell"];
            $this->sClientCompanyFormName = $oRow["client_jur_form"];
                //GetCompanyFormShortName($oDBHandler,intval($oRow["client_jur_form"]));

            /** Recipient data */
            $this->sOrderRecipientEmail = $oRow["r_email"];
            $this->sOrderRecipientPhone = $oRow["r_phone_number"];
            $this->iOrderRecipientLegalEntity = $oRow["r_is_legal_entity"];
            $this->sOrderRecipientAddress = trim(str_replace('кв.(офис)','',$oRow["recipient_address"]));
            $this->sOrderRecipientContactPerson =
                $oRow["r_contact_person_first_name"].' '
                .$oRow["r_contact_person_second_name"];

            $this->sOrderRecipientCompanyContactFirstName = $oRow["r_contact_person_first_name"];
            $this->sOrderRecipientCompanyContactSecondName = $oRow["r_contact_person_second_name"];

            /** Person data */
            $this->sOrderRecipientDocumentNumber = $oRow["r_person_document_number"];

            $recipientDocumentType = 'Паспорт';
            switch (intval($oRow["r_person_document_type_id"]))
            {
                case 2:
                    $recipientDocumentType = "Водительские права";
                    break;
                case 3:
                    $recipientDocumentType = "Заграничный паспорт";
                    break;
                default:
                    $recipientDocumentType = 'Паспорт';
                    break;
            }

            $this->sOrderRecipientDocumentType = $recipientDocumentType;

            $this->sOrderRecipientFirstName = $oRow["r_person_first_name"];
            $this->sOrderRecipientSecondName = $oRow["r_person_second_name"];
            $this->sOrderRecipientLastName = $oRow["r_person_last_name"];
            $this->sOrderRecipientFullName =  strval($oRow["r_person_last_name"]). ' '
                .strval($oRow["r_person_first_name"]).' '
                .strval($oRow["r_person_second_name"]);

            /** Company data*/
            $this->sOrderRecipientCompanyName = $oRow["r_company_name"];
            $this->sOrderRecipientCompanyFormName =
                GetCompanyFormShortName($oDBHandler,intval($oRow["r_company_form_id"]));
            $this->iOrderRecipientCompanyInn = intval($oRow["r_company_inn"]);
            $this->sOrderRecipientCompanyPhone = $oRow["r_company_phone"];
            $this->sOrderRecipientCompanyEmail =$oRow["r_company_email"];
            $this->sOrderRecipientCompanyAddress =$oRow["r_company_address"];
            $this->sOrderRecipientCompanyAddressCell =$oRow["r_company_address_cell"];

            /** Sender data */
            $this->sOrderSenderEmail = $oRow["s_email"];
            $this->sOrderSenderPhone = $oRow["s_phone_number"];
            $this->iOrderSenderLegalEntity = $oRow["s_is_legal_entity"];
            $this->sOrderSenderAddress = trim(str_replace('кв.(офис)','',$oRow["sender_address"]));
            $this->isArrivalWithCourier = $oRow["is_arrival_with_courier"];
            $this->isDerivalWithCourier = $oRow["is_derival_with_courier"];

            /** Person data */
            $this->sOrderSenderDocumentNumber = $oRow["s_person_document_number"];

            $senderDocumentType = 'Паспорт';
            switch (intval($oRow["r_person_document_type_id"]))
            {
                case 2:
                    $senderDocumentType = "Водительские права";
                    break;
                case 3:
                    $senderDocumentType = "Заграничный паспорт";
                    break;
                default:
                    $senderDocumentType = 'Паспорт';
                    break;
            }

            $this->sOrderSenderDocumentType = $senderDocumentType;

            $this->sOrderSenderFirstName = $oRow["s_person_first_name"];
            $this->sOrderSenderSecondName = $oRow["s_person_second_name"];
            $this->sOrderSenderLastName = $oRow["s_person_last_name"];

            $this->sOrderSenderFullName =  strval($oRow["s_person_last_name"]). ' '
                .strval($oRow["s_person_first_name"]).' '
                .strval($oRow["s_person_second_name"]);

            /** Company data*/
            $this->sOrderSenderCompanyName = $oRow["s_company_name"];
            $this->sOrderSenderCompanyFormName =
                GetCompanyFormShortName($oDBHandler, intval($oRow["s_company_form_id"]));
            $this->iOrderSenderCompanyInn = intval($oRow["s_company_inn"]);
            $this->sOrderSenderCompanyPhone = $oRow["s_company_phone"];
            $this->sOrderSenderCompanyEmail = $oRow["s_company_email"];
            $this->sOrderSenderCompanyAddress = $oRow["s_company_address"];
            $this->sOrderSenderCompanyAddressCell = $oRow["s_company_address_cell"];

            $this->sOrderSenderContactPerson =
                $oRow["s_contact_person_first_name"].' '
                .$oRow["s_contact_person_second_name"];

            $this->sOrderSenderCompanyContactFirstName=$oRow["s_contact_person_first_name"];
            $this->sOrderSenderCompanyContactSecondName=$oRow["s_contact_person_second_name"];

            $this->iPayed = $oRow["is_payed"];
            switch ($this->iPayed)
            {
                case 0:
                    $this->sPayedName = "Не оплачен";
                    break;
                case 1:
                    $this->sPayedName = "Оплачен";
                    break;
            }
            $this->iPaymentTypeID = intval($oRow["payment_type_id"]);
            $this->sPaymentTypeName = $oRow["payment_type_name"];
            $this->iPayerTypeId = intval($oRow["payer_type_id"]);
            $this->sPayerTypeName = $oRow["payer_type_name"];

            $this->sCargoVolUnitName = $oRow["cargo_vol_unit_name"];
            $this->sCargoWeightUnitName = $oRow["cargo_weight_unit_name"];

            $this->sSerializedFields = $oRow["serialized_fields"];

            return PARCEL_OK;
        }
        else
        {

            $this->objectOK = false;
            return PARCEL_NOT_FOUND;
        }
    }

    function CityCorrection($city)
    {

        $cargoCities = explode(',',$city);

        if(count($cargoCities)<2)
        {
            return $city;
        }

        $pos = strpos($cargoCities[1], trim($cargoCities[0]));
        if($pos)
        {
            return trim($cargoCities[0]);
        }

        return $city;
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////

    public function SaveOrder($oDBHandler)
    {
        //print('lol');
        if ((!$this->objectOK) or ($this->iOrderID < 1))
            return USER_NO_PARAMS;
			//print('svsdvsdv ');
        $sEditParcelQuery = "UPDATE `" . DB_ORDERS_TABLE . "` SET " .
								"`creator_id` = " . intval($this->parcelCreatorID) . ", " .
								"`is_creator_courier` = " . ($this->parcelIsCreatorCourier ? 1 : 0) . ", " .
								"`sender_id` = " . intval($this->parcelSenderID) . ", " .
								"`recipient_id` = " . intval($this->parcelRecipientID) . ", " .
								"`sender_address` = \"" . $oDBHandler->real_escape_string($this->parcelSenderAddress) . "\", " .
								"`recipient_address` = \"" . $oDBHandler->real_escape_string($this->parcelRecipientAddress) . "\", " .
								"`sender_coords` = POINT(" . floatval($this->parcelSenderCoordLat) . ", " . floatval($this->parcelSenderCoordLon) . "), " .
								"`recipient_coords` = POINT(" . floatval($this->parcelRecipientCoordLat) . ", " . floatval($this->parcelRecipientCoordLon) . "), " .
								"`weight` = " . floatval($this->parcelWeight) . ", " .
								"`value` = " . floatval($this->parcelValue) . ", " .
								"`length` = " . floatval($this->parcelLength) . ", " .
								"`width` = " . floatval($this->parcelWidth) . ", " .
								"`height` = " . floatval($this->parcelHeight) . ", " .
								"`price` = " . floatval($this->parcelPrice) . ", " .
								"`comment` = \"" . $oDBHandler->real_escape_string($this->parcelComment) . "\" " .

                                                                ", `cargoDesiredDate` = FROM_UNIXTIME(" . intval($this->iOrderDesiredDate) . ")" .
                                                                ", `rcptUser` = \"" . $oDBHandler->real_escape_string($this->sOrderRecipientUser) . "\"" .
                                                                ", `rcptPassport` = \"" . $oDBHandler->real_escape_string($this->sOrderRecipientPassport) . "\"" .
                                                                ", `rcptPassportGivenDate` = " . intval($this->iOrderRecipientPassportGivenDate) .
                                                                ", `rcptPhone` = \"" . $oDBHandler->real_escape_string($this->sOrderRecipientPhone) . "\"" .
                                                                ", `rcptEmail` = \"" . $oDBHandler->real_escape_string($this->sOrderRecipientEmail) . "\"" .
                                                                ", `rcptAddress` = \"" . $oDBHandler->real_escape_string($this->sOrderRecipientAddress) . "\"" .

                                                                ", `cargoPlaces` = " . intval($this->iOrderPlaces) .
                                                                ", `cargoDangerClass` = \"" . $oDBHandler->real_escape_string($this->sOrderDangerClass) . "\"" .
                                                                ", `cargoTemperature` = \"" . $oDBHandler->real_escape_string($this->sOrderTemperature) . "\"" .
                                                                ", `cargoGoodsName` = \"" . $oDBHandler->real_escape_string($this->sOrderGoodsName) . "\"" .
                                                                ", `addOptions` = \"" . $oDBHandler->real_escape_string($this->sAddOptions) . "\"" .
                                                                ", `serializedFields` = \"" . $oDBHandler->real_escape_string(json_decode($this->oSerializedData)) . "\"" .
            " `company_internal_number` = \"" . $oDBHandler->real_escape_string($this->sCompanyInternalNumber) . "\"" .

            " WHERE `id` = " . intval($this->iOrderID);


 //       print($sEditParcelQuery);
       $oDBHandler->query($sEditParcelQuery);
//			print($oDBHandler->error);
        if ($oDBHandler->error)
//            return USER_DB_ERROR;
        if ($oDBHandler->affected_rows == 1)
            return USER_OK;
        else
            return USER_DB_ERROR;
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function OrdersFromSearch($oDBHandler,
                                     $iTimestampFrom = 0,
                                     $iTimestampTo = 0,
                                     $aClientIDs = array(),
                                     $sCargoName = "",
                                     $sCargoFrom = "",
                                     $sCargoTo = "",
                                     $sSearchWord="",
                                     $limit = 0,
                                     $offset = 0,
                                     $sSortColumn = "",
                                     $sOrderType = "DESC")
    {
        // compile search clause
        $sSearchClause = "1 ";

        $sColumnValue = 'id';
        switch($sSortColumn)
        {
            case 'order_id' :
                $sColumnValue = 'id';
                break;
            case 'date_from' :
            case 'orderTimestamp' :
                $sColumnValue = 'timestamp';
                break;
            case 'place_from' :
            case 'cargoFrom' :
                $sColumnValue = 'cargo_from';
                break;
            case 'place_to' :
            case 'cargoTo' :
                $sColumnValue = 'cargo_to';
                break;
            case 'destination_surname' :
            case 'orderRecepientFirstName' :
                $sColumnValue = ' r_person ';
                break;
            case 'order_price' :
            case 'cargoPrice' :
                $sColumnValue = 'cargo_price';
                break;
        }

        IF($sOrderType == "")
        {
            $sOrderType = "DESC";
        }

        $sOrderValue = " ORDER BY ".$sColumnValue." ".$sOrderType;

        if ($iTimestampFrom > 0)
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "(UNIX_TIMESTAMP(o.timestamp) >= " . intval($iTimestampFrom) . ")";
        }

        if ($iTimestampTo > 0)
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "(UNIX_TIMESTAMP(o.timestamp) <= " . intval($iTimestampTo) . ")";
        }

        if (count($aClientIDs) > 0)
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "o.client_id IN (" . $oDBHandler->real_escape_string(implode(", ", $aClientIDs)) . ")";
        }


        if ($sCargoName != "")
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "o.cargo_name LIKE \"%" . $oDBHandler->real_escape_string($sCargoName) . "%\"";
        }

        if ($sCargoFrom != "")
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "o.cargo_from LIKE \"%" . $oDBHandler->real_escape_string($sCargoFrom) . "%\"";
        }

        if ($sCargoTo != "")
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "o.cargo_to LIKE \"%" . $oDBHandler->real_escape_string($sCargoTo) . "%\"";
        }

        if ($sSearchWord != "")
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "
            (o.id LIKE '%" . $oDBHandler->real_escape_string($sSearchWord) . "%' 
             OR cf.name like '%" . $oDBHandler->real_escape_string($sSearchWord) . "%' 
             OR cf.name like '%" . $oDBHandler->real_escape_string($sSearchWord) . "%' 
             OR ct.name like '%" . $oDBHandler->real_escape_string($sSearchWord) . "%' 
             OR r.company_name LIKE '%" . $oDBHandler->real_escape_string($sSearchWord) . "%' 
             OR r.person_first_name LIKE '%" . $oDBHandler->real_escape_string($sSearchWord) . "%'
             OR r.person_second_name LIKE '%" . $oDBHandler->real_escape_string($sSearchWord) . "%'
             OR r.person_last_name LIKE '%" . $oDBHandler->real_escape_string($sSearchWord) . "%') ";
        }

        // compiling limit
        $sLimitClause = "";
    //    if (intval($offset) > 0)
     //   {
            $sLimitClause = " LIMIT " . $offset;
            if (intval($limit) > 0)
                $sLimitClause .= ", " . $limit;
      //  }

        $sSearchQuery = "SELECT t.*
                         FROM (
                          SELECT 	
                                if(r.is_legal_entity=0,r.person_second_name ,r.company_name ) as r_person, 
                                o.*,
								r.phone_number as r_phone_number, 
								r.email as r_email, 
								r.is_legal_entity as r_is_legal_entity, 
								r.person_first_name as r_person_first_name, 
								r.person_second_name as r_person_second_name, 
								r.person_last_name as r_person_last_name, 
								r.person_document_type_id as r_person_document_type_id, 
								r.person_document_number as r_person_document_number, 
								r.company_name as r_company_name, 
								r.company_form_id as r_company_form_id, 
								r.company_inn as r_company_inn, 
								r.company_phone as r_company_phone, 
								r.company_email as company_email,
								
								s.phone_number as s_phone_number, 
								s.email as s_email, 
								s.is_legal_entity as s_is_legal_entity, 
								s.person_first_name as s_person_first_name, 
								s.person_second_name as s_person_second_name, 
								s.person_last_name as s_person_last_name, 
								s.person_document_type_id as s_person_document_type_id, 
								s.person_document_number as s_person_document_number, 
								s.company_name as s_company_name, 
								s.company_form_id as s_company_form_id, 
								s.company_inn as s_company_inn, 
								s.company_phone as s_company_phone, 
								s.company_email as s_company_email,
								
								o.id AS order_id, 
								t.name 
								as temperature_mode_name, 
								d.name as danger_class_name ,
								cf.name as city_from_name, 
								ct.name as city_to_name,
								pt.name as payment_type_name,
								pyt.name as payer_type_name,
								UNIX_TIMESTAMP(timestamp) AS u_time 
							 FROM `" . DB_ORDERS_TABLE . "` o
							 JOIN `" . DB_CONTACT_TABLE . "` r ON o.recipient_id=r.id
							 JOIN `" . DB_CONTACT_TABLE . "` s ON o.sender_id=s.id
							 JOIN `" . DB_TEMPERATURE_MODE_TABLE . "`  t ON o.cargo_temperature_id = t.id
							 JOIN `" . DB_DANGER_CLASS_TABLE . "` d ON d.id = o.cargo_danger_class_id
							 JOIN `" . DB_CITY_TABLE . "` cf ON o.city_from_id = cf.id
							 JOIN `" . DB_CITY_TABLE . "` ct ON o.city_to_id = ct.id
							 JOIN `" . DB_PAYMENT_TYPE . "` pt ON o.payment_type_id = pt.id
							 JOIN `" . DB_PAYER_TYPE . "` pyt ON o.payer_type_id = pyt.id
							 WHERE " . $sSearchClause . " AND o.company_internal_number is not null
							 ) t
							 ".$sOrderValue . $sLimitClause;

        //if(IS_DEBUG){  print($sSearchQuery); die();  }
        //$sSearchQuery = "SELECT parcels.* FROM `" . DB_PARCELS_TABLE . "` " .
        //			"WHERE " . $sSearchClause . " " . $sLimitClause . " GROUP BY parcels.id";

        $oSearchResult = $oDBHandler->query($sSearchQuery);

        if ($oDBHandler->error)
            return USER_DB_ERROR;

        // compile ret array
        $aParcels = array();

        while($oRow = $oSearchResult->fetch_assoc())
        {
            $oTemp = new Order();

            $cargoFrom = self::CityCorrection($oRow["cargo_from"]);

            $cargoTo = self::CityCorrection($oRow["cargo_to"]);

            $oTemp->iOrderID = intval($oRow["order_id"]);
            $oTemp->iCompanyID = intval($oRow["companyID"]);
            $oTemp->iOrderRecipientId = $oRow["recipient_id"];
            $oTemp->iOrderTimestamp = intval($oRow["u_time"]);
            $oTemp->iOrderClientID = intval($oRow["client_id"]);
            $oTemp->sOrderCargoName = $oRow["cargo_trans_company_name"];
            $oTemp->iOrderCityFromId = $oRow["city_from_id"];
            $oTemp->iOrderCityToId = $oRow["city_to_id"];
            $oTemp->sOrderCargoFrom = $cargoFrom;
            $oTemp->sOrderCargoTo = $cargoTo;

            $oTemp->sOrderCargoMethod = $oRow["cargo_method"];
            $oTemp->sCargoSite = $oRow["cargo_site"];
            $oTemp->sOrderComment = $oRow["comment"];
            $oTemp->sOrderDesiredDate = $oRow["cargo_desired_date"];
            $oTemp->sOrderDeliveryDate = $oRow["cargo_delivery_date"];

            $oTemp->fOrderCargoWeight = floatval($oRow["cargo_weight"]);
            $oTemp->fOrderCargoVol = floatval($oRow["cargo_vol"]);
            $oTemp->fOrderCargoLength = floatval($oRow["cargo_length"]);
            $oTemp->fOrderCargoHeight = floatval($oRow["cargo_height"]);
            $oTemp->fOrderCargoWidth = floatval($oRow["cargo_width"]);

            $oTemp->fOrderCargoValue = floatval($oRow["cargo_value"]);
            $oTemp->fOrderCargoPrice = floatval($oRow["cargo_price"]);

            $oTemp->sOrderTemperatureModeId = $oRow["cargo_temperature_id"];
            $oTemp->sOrderTemperatureModeName = $oRow["temperature_mode_name"];
            $oTemp->sOrderDangerClassId = $oRow["cargo_danger_class_id"];
            $oTemp->sOrderDangerClassName = $oRow["danger_class_name"];
            $oTemp->sOrderGoodsName = $oRow["cargo_good_name"];
            $oTemp->oSerializedData = json_decode($oRow["serializedFields"]);

            /** Recipient data */
            $oTemp->sOrderRecipientEmail = $oRow["r_email"];
            $oTemp->sOrderRecipientPhone = $oRow["r_phone_number"];
            $oTemp->iOrderRecipientLegalEntity = $oRow["r_is_legal_entity"];

            /** Person data */
            $this->sOrderRecipientDocumentNumber = $oRow["r_person_document_number"];

            $recipientDocumentType = 'Паспорт';
            switch (intval($oRow["r_person_document_type_id"]))
            {
                case 2:
                    $recipientDocumentType = "Водительские права";
                    break;
                case 3:
                    $recipientDocumentType = "Заграничный паспорт";
                    break;
                default:
                    $recipientDocumentType = 'Паспорт';
                    break;
            }

            $oTemp->sOrderRecipientDocumentType = $recipientDocumentType;

            $oTemp->sOrderRecipientFirstName = $oRow["r_person_first_name"];
            $oTemp->sOrderRecipientSecondName = $oRow["r_person_second_name"];
            $oTemp->sOrderRecipientLastName = $oRow["r_person_last_name"];
            $oTemp->sOrderRecipientFullName =  strval($oRow["r_person_last_name"]). ' '
                .strval($oRow["r_person_first_name"]).' '
                .strval($oRow["r_person_second_name"]);

            /** Company data*/
            $oTemp->sOrderRecipientCompanyName = $oRow["r_company_name"];
            $oTemp->sOrderRecipientCompanyFormName =
                GetCompanyFormShortName($oDBHandler,intval($oRow["r_company_form_id"]));
            $oTemp->iOrderRecipientCompanyInn = intval($oRow["r_company_inn"]);
            $oTemp->sOrderRecipientCompanyPhone = $oRow["r_company_phone"];
            $oTemp->sOrderRecipientCompanyEmail =$oRow["r_company_email"];


            /** Sender data */
            $oTemp->sOrderSenderEmail = $oRow["s_email"];
            $oTemp->sOrderSenderPhone = $oRow["s_phone_number"];
            $oTemp->iOrderSenderLegalEntity = $oRow["s_is_legal_entity"];

            /** Person data */
            $oTemp->sOrderSenderDocumentNumber = $oRow["s_person_document_number"];

            $senderDocumentType = 'Паспорт';
            switch (intval($oRow["r_person_document_type_id"]))
            {
                case 2:
                    $senderDocumentType = "Водительские права";
                    break;
                case 3:
                    $senderDocumentType = "Заграничный паспорт";
                    break;
                default:
                    $senderDocumentType = 'Паспорт';
                    break;
            }

            $oTemp->sOrderSenderDocumentType = $senderDocumentType;

            $oTemp->sOrderSenderFirstName = $oRow["s_person_first_name"];
            $oTemp->sOrderSenderSecondName = $oRow["s_person_second_name"];
            $oTemp->sOrderSenderLastName = $oRow["s_person_last_name"];

            $oTemp->sOrderSenderFullName =  strval($oRow["s_person_last_name"]). ' '
                .strval($oRow["s_person_first_name"]).' '
                .strval($oRow["s_person_second_name"]);

            /** Company data*/
            $oTemp->sOrderSenderCompanyName = $oRow["s_company_name"];
            $oTemp->sOrderSenderCompanyFormName =
                GetCompanyFormShortName($oDBHandler, intval($oRow["s_company_form_id"]));
            $oTemp->iOrderSenderCompanyInn = intval($oRow["s_company_inn"]);
            $oTemp->sOrderSenderCompanyPhone = $oRow["s_company_phone"];
            $oTemp->sOrderSenderCompanyEmail = $oRow["s_company_email"];

            $oTemp->sPaymentTypeName = $oRow["payment_type_name"];
            $oTemp->sPayerTypeName = $oRow["payer_type_name"];

            $aOrders[] = $oTemp;
        }
        return $aOrders;
    }

	/**Saves changes of current order. Affects only orders table
	 * @param $oDBHandler
     * @return bool
	 */
	public function UpdateOrder($oDBHandler) {

		$payedNameStatus = 0;

		switch ($this->iPayed)
		{
			case 0:
				$payedNameStatus = "new";
				break;
			case 1:
				$payedNameStatus = "payed";
				break;
			case 2:
				$payedNameStatus = "reject";
				break;
		}

		// Статус "оплачено"
		$pay = "SELECT `orderId` ".
               "FROM `". DB_ORDER_PAYMENT . "` ".
		       "WHERE `orderId` = ". $oDBHandler->real_escape_string($this->iOrderID);
		$res = $oDBHandler->query($pay);

		if($res->num_rows > 0 ){
			$payment = "UPDATE `". DB_ORDER_PAYMENT . "` ".
			           "SET ".
			           "`status` = '".$payedNameStatus."', ".
			           "`isPayed` = $this->iPayed ".
			           "WHERE `orderId` = ". $oDBHandler->real_escape_string($this->iOrderID);
			$oDBHandler->query($payment);
		} else {
			$pay_insert = "INSERT INTO `" . DB_ORDER_PAYMENT . "` " .
			              "(`orderId`, `alfaOrderId`, `isPayed`, `expirationDate`, `status`, `formUrl`) " .
			              "VALUES (" . $this->iOrderID . ", '', $this->iPayed, CURRENT_TIMESTAMP,'$payedNameStatus','')";

			$oDBHandler->query( $pay_insert );
		}

		// Запись суммы оплаты
		$fin = "SELECT `id` ".
		       "FROM `". DB_FINOPERATIONS_TABLE . "` ".
		       "WHERE `order_id` = ". $oDBHandler->real_escape_string($this->iOrderID). " and `value` > 0 ";
		$res = $oDBHandler->query($fin);

		if($res->num_rows > 0 ){
			$historyUpdate = "UPDATE `". DB_FINOPERATIONS_TABLE . "` ".
			           "SET ".
			           "`timestamp` = CURRENT_TIMESTAMP, ".
			           "`value` = '". floatval($this->fOrderCargoPaid) . "', ".
                       "`user_id` = '". intval($this->iOrderClientID) . "', ".
			           "`order_id` = '". $this->iOrderID . "', ".
                       "`description` = '". $this->sFinDesc . "' ".
			           "WHERE `order_id` = ". $this->iOrderID;
			$oDBHandler->query($historyUpdate);
		} else {
			$historyInsert = "INSERT INTO `" . DB_FINOPERATIONS_TABLE . "` " .
			              "( `timestamp`, `value`, `user_id`, `order_id`, `description`) " .
			              "VALUES (  CURRENT_TIMESTAMP, ". floatval($this->fOrderCargoPaid).", ".intval($this->iOrderClientID).",$this->iOrderID, '$this->sFinDesc')";

			$oDBHandler->query($historyInsert );
		}



        //TODO: currency table and order relation
        $query = "UPDATE `". DB_ORDERS_TABLE . "` ".
                 "SET ".
                 DB_ORDERS_TABLE .".`cargo_value` = " . $oDBHandler->real_escape_string($this->fOrderCargoValue) . ", ".
                 DB_ORDERS_TABLE .".`cargo_price` = " . $oDBHandler->real_escape_string($this->fOrderCargoPrice) . ", ".
                 DB_ORDERS_TABLE .".`company_internal_number` = '" . $oDBHandler->real_escape_string($this->sCompanyInternalNumber) . "', ".
                 DB_ORDERS_TABLE .".`cargo_desired_date` = '" . $oDBHandler->real_escape_string($this->sOrderDesiredDate) . "', ".
                 DB_ORDERS_TABLE .".`cargo_delivery_date` = '" . $oDBHandler->real_escape_string($this->sOrderDeliveryDate) . "', ".
                 DB_ORDERS_TABLE .".`comment` = '" . $oDBHandler->real_escape_string($this->sOrderComment) . "', ".
                 DB_ORDERS_TABLE .".`cargo_danger_class_id` = '" . $oDBHandler->real_escape_string($this->sOrderDangerClassId) . "', ".
                 DB_ORDERS_TABLE .".`cargo_temperature_id` = '" . $oDBHandler->real_escape_string($this->sOrderTemperatureModeId) . "', ".
                 DB_ORDERS_TABLE .".`cargo_weight` = '" . $oDBHandler->real_escape_string($this->fOrderCargoWeight) . "', ".
                 DB_ORDERS_TABLE .".`cargo_vol` = '" . $oDBHandler->real_escape_string($this->fOrderCargoVol) . "', ".
                 DB_ORDERS_TABLE .".`cargo_width` = '" . $oDBHandler->real_escape_string($this->fOrderCargoWidth) . "', ".
                 DB_ORDERS_TABLE .".`cargo_height` = '" . $oDBHandler->real_escape_string($this->fOrderCargoHeight) . "', ".
                 DB_ORDERS_TABLE .".`cargo_length` = '" . $oDBHandler->real_escape_string($this->fOrderCargoLength) . "', ".
                 DB_ORDERS_TABLE .".`cargo_name` = '". $oDBHandler->real_escape_string($this->sOrderCargoName) . "', ".
                 DB_ORDERS_TABLE .".`payment_type_id` = '". intval($this->iPaymentTypeID) . "', ".
                 DB_ORDERS_TABLE .".`order_status_id` = " . $oDBHandler->real_escape_string($this->fOrderStatusID) . " ".
                 "WHERE `". DB_ORDERS_TABLE ."`.`id` = ". $oDBHandler->real_escape_string($this->iOrderID);

        return $oDBHandler->query($query);


	}

	public function SearchOrders($oDBHandler, $limit, $offset, $order, $sort_col, $keyword = '', $dateStart, $dateEnd,$singleUserId='') {

	    // var_dump($limit, $offset, $order, $sort_col, $keyword);
		if ( $sort_col == '' )
        {
			$sort_col = 'o.id';
		}
       /* else
        {
            switch($sort_col)
            {
                case 'city_from':
                     'o.city_from_id';
                    break;
                case 'city_to':
                    'o.city_to_id';
                    break;
                case 'city_to':
                    'o.city_to_id';
                    break;
                case 'rec_last_name':
                    'o.city_to_id';
                    break;
                case 'city_to':
                    'o.city_to_id';
                    break;
                case 'city_to':
                    'o.city_to_id';
                    break;
            }
        }*/

        if ( $order == '' )
        {
            $sort_col = 'DESC';
        }

		$query = "SELECT " .
		         "o.id,
                    o.city_from_id,
                    o.city_to_id,
                    o.company_internal_number,
                    o.timestamp,
                    o.order_status_id,
                    o.companyID,
                    c.name AS 'city_from', c2.name AS 'city_to',
                    a.person_last_name AS 'sen_last_name', a.person_first_name AS 'sen_first_name',
                    a2.person_last_name AS 'rec_last_name', a2.person_first_name AS 'rec_first_name',
                    s.name AS 'status' ,
                    com.name AS 'company_name'" .

		         "FROM `" . DB_ORDERS_TABLE . "` o " ;


		$joinclause = "INNER JOIN `" . DB_CITY_TABLE . "` c ON c.id = o.city_from_id
                    INNER JOIN `" . DB_CITY_TABLE . "` c2 ON c2.id = o.city_to_id
                    INNER JOIN `" . DB_CONTACT_TABLE . "` a ON a.id = o.sender_id
                    INNER JOIN `" . DB_CONTACT_TABLE . "` a2 ON a2.id = o.recipient_id
                    INNER JOIN `order_status_type` s ON s.id = o.order_status_id
                    LEFT JOIN `companies` com ON com.id = o.companyID
                    WHERE 1 ";

		$query .= $joinclause;

		$sQuery = "SELECT count(*) AS count FROM ". DB_ORDERS_TABLE . " o ";

		$sQuery .= $joinclause;

		//TODO: global variable for `order_status_type` table
		if (isset($dateStart) && isset($dateEnd) && $dateStart != '' && $dateEnd != ''){
			$q = " AND o.timestamp BETWEEN '".$dateStart."' AND '".$dateEnd."' " ;
			//$sQuery .= " WHERE o.timestamp BETWEEN '".$dateStart."' AND '".$dateEnd."' " ;
            $query .= $q;
            $sQuery .= $q;
		}

        if($singleUserId!='')
        {
            $clause = " AND o.client_id= ".$singleUserId;
            $query .= $clause;
            $sQuery .=$clause;
        }


        if ( $keyword != '' ) {
		   // $sQuery .= $joinclause;
			$clause = //" AND o.timestamp BETWEEN '".$dateStart."' AND '".$dateEnd."' " .
                      "AND (o.company_internal_number LIKE '%" . $oDBHandler->real_escape_string($keyword) . "%' " .
			          "OR c.name LIKE '%" . $oDBHandler->real_escape_string($keyword) . "%' " .
			          "OR c2.name LIKE '%" . $oDBHandler->real_escape_string($keyword) . "%' " .
			          "OR a.person_last_name LIKE '%" . $oDBHandler->real_escape_string($keyword) . "%' " .
			          "OR a.person_first_name LIKE '%" . $oDBHandler->real_escape_string($keyword) . "%' " .
			          "OR a2.person_last_name LIKE '%" . $oDBHandler->real_escape_string($keyword) . "%' " .
			          "OR a2.person_first_name LIKE '%" . $oDBHandler->real_escape_string($keyword) . "%' )";

			$query .= $clause;
            $sQuery .= $clause;
        }


        //if (isset($dateStart) && isset($dateEnd)){
		//    $query .= " AND o.timestamp BETWEEN '".$dateStart."' AND '".$dateEnd."' " ;
		//    $sQuery .= " AND o.timestamp BETWEEN '".$dateStart."' AND '".$dateEnd."' " ;
        //}
		$query .= " ORDER BY " . $sort_col . " " . $order .
        " LIMIT " . $offset . ", " . $limit;

		//var_dump($query); die;

		$searchRes = $oDBHandler->query($query);

		$count = 0;

		$countRes = $oDBHandler->query($sQuery);
		if(mysqli_num_rows($countRes)!=0) {
			while ( $oRow = $countRes->fetch_assoc() ) {
				$count = $oRow['count'];
			}
		}

        if(mysqli_num_rows($searchRes)!=0) {
	        while ( $oRow = $searchRes->fetch_assoc() ) {
		        $tmp['id']                      = intval($oRow['id']);
		        $tmp['city_from_id']            = intval($oRow['city_from_id']);
		        $tmp['city_from']               = $oRow['city_from'];
		        $tmp['city_to_id']              = intval($oRow['city_to_id']);
		        $tmp['city_to']                 = $oRow['city_to'];
		        $tmp['company_internal_number'] = $oRow['company_internal_number'];
		        $tmp['timestamp']               = strtotime($oRow['timestamp']);
		        $tmp['order_status_id']         = intval($oRow['order_status_id']);
                $tmp['order_status_name']       = $oRow['status'];
		        $tmp['sen_last_name']           = $oRow['sen_last_name'];
		        $tmp['sen_first_name']          = $oRow['sen_first_name'];
		        $tmp['rec_last_name']           = $oRow['rec_last_name'];
		        $tmp['rec_first_name']          = $oRow['rec_first_name'];
		        $tmp['companyID'] 		        = intval($oRow['companyID']);
                $tmp['company_name'] 		    = $oRow['company_name'];
		        $aOrders[]                      = $tmp;
	        }
        }
		$toret['orders'] = array();
		if(count($aOrders) > 0){
			$toret['orders'] = $aOrders;
		}
		$toret['count'] = $count;
        return $toret;

	}

    /////////////////////////////////////////////////////////////////////////////////////////////////

    public function OrdersCountFromSearch($oDBHandler, $iTimestampFrom = 0, $iTimestampTo = 0, $aClientIDs = array(),
                                          $sCargoName = "", $sCargoFrom = "", $sCargoTo = "",$sSearchWord = "")
    {
        // compile search clause
        $sSearchClause = "1 ";

        if ($iTimestampFrom > 0)
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "(UNIX_TIMESTAMP(o.timestamp) >= " . intval($iTimestampFrom) . ")";
        }

        if ($iTimestampTo > 0)
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "(UNIX_TIMESTAMP(o.timestamp) <= " . intval($iTimestampTo) . ")";
        }

        if (count($aClientIDs) > 0)
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "o.client_id IN (" . $oDBHandler->real_escape_string(implode(", ", $aClientIDs)) . ")";
        }


        if ($sCargoName != "")
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "o.cargo_name LIKE \"%" . $oDBHandler->real_escape_string($sCargoName) . "%\"";
        }

        if ($sCargoFrom != "")
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "o.cargo_from LIKE \"%" . $oDBHandler->real_escape_string($sCargoFrom) . "%\"";
        }

        if ($sCargoTo != "")
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "o.cargo_to LIKE \"%" . $oDBHandler->real_escape_string($sCargoTo) . "%\"";
        }

        if ($sSearchWord != "")
        {
            $sSearchClause .= ($sSearchClause == "" ? "" : " AND ");
            $sSearchClause .= "
            (o.id LIKE '%" . $oDBHandler->real_escape_string($sSearchWord) . "%' 
             OR cf.name like '%" . $oDBHandler->real_escape_string($sSearchWord) . "%' 
             OR cf.name like '%" . $oDBHandler->real_escape_string($sSearchWord) . "%' 
             OR ct.name like '%" . $oDBHandler->real_escape_string($sSearchWord) . "%' 
             OR r.company_name LIKE '%" . $oDBHandler->real_escape_string($sSearchWord) . "%' 
             OR r.person_first_name LIKE '%" . $oDBHandler->real_escape_string($sSearchWord) . "%'
             OR r.person_second_name LIKE '%" . $oDBHandler->real_escape_string($sSearchWord) . "%'
             OR r.person_last_name LIKE '%" . $oDBHandler->real_escape_string($sSearchWord) . "%') ";
        }

        // compiling limit



        $sSearchQuery = "SELECT COUNT(*) as cnt 
                             FROM `" . DB_ORDERS_TABLE . "` o
							 JOIN `" . DB_CONTACT_TABLE . "` r ON o.recipient_id=r.id
							 JOIN `" . DB_CONTACT_TABLE . "` s ON o.sender_id=s.id
							 JOIN `" . DB_TEMPERATURE_MODE_TABLE . "`  t ON o.cargo_temperature_id = t.id
							 JOIN `" . DB_DANGER_CLASS_TABLE . "` d ON d.id = o.cargo_danger_class_id
							 JOIN `" . DB_CITY_TABLE . "` cf ON o.city_from_id = cf.id
							 JOIN `" . DB_CITY_TABLE . "` ct ON o.city_to_id = ct.id
							 JOIN `" . DB_PAYMENT_TYPE . "` pt ON o.payment_type_id = pt.id
							 " .
            "WHERE " . $sSearchClause . "  AND o.company_internal_number is not null" ;

       // if(IS_DEBUG){  print($sSearchQuery); die();  }

        $oSearchResult = $oDBHandler->query($sSearchQuery);

        if ($oDBHandler->error)
            return USER_DB_ERROR;

        $oRow = $oSearchResult->fetch_assoc();

        return $oRow["cnt"];
    }

    public function SetAdditionalOptions($oDBHandler,$order_id,$options,$originalPrice)
    {
        $opts = array();

        $ind = 0;

        mb_internal_encoding("UTF-8");
        mb_regex_encoding("UTF-8");

        foreach($options["methods"]["0"]["additional"] as $key=>$opt)
        {
            if($key<7)
            {
                continue;
            }
            if($opt['price']==0 )
            {
                continue;
            }

            $opts[$opt['opt_name']]["item".$opt['opt_num']] =
                array("name"=>$opt["description"],
                      "cost"=>$opt['price']);

            //var_dump($opts);
        }

        $options_str = $oDBHandler->real_escape_string(json_encode($opts));

        $sSearchQuery = "INSERT INTO order_options
                         (order_id, serialized_options,orginal_price)
                         VALUES ($order_id, '$options_str',$originalPrice)";

       // echo $sSearchQuery; die;

        $oSearchResult = $oDBHandler->query($sSearchQuery);

       // var_dump($oDBHandler);
       // die();
    }

    public function GetAdditionalOptions($oDBHandler,$order_id)
    {
        $sSearchQuery = "	SELECT serialized_options
                            FROM order_options
                            WHERE order_id = $order_id";

        // echo $sSearchQuery; die;

        $oSearchResult = $oDBHandler->query($sSearchQuery);

        $nullResult["packageType"] = array();

        if ($oDBHandler->affected_rows > 0) {
            $oRow = $oSearchResult->fetch_assoc();
            return json_decode($oRow["serialized_options"]);
        }
        else
        {
            return $nullResult;
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////
    public function SetAdditionalTerminalAddress($oDBHandler,$city_id,$terminal_id,$name,$address)
    {
        $sSearchQuery = "SELECT terminal_id
							FROM ".DB_ADDITIONAL_TERMINAL."
							WHERE terminal_id=".$terminal_id;

        $oSearchResult = $oDBHandler->query($sSearchQuery);
        if ($oDBHandler->affected_rows = 0) {

            $sQuery = "INSERT INTO ".DB_ADDITIONAL_TERMINAL."
                            (city_id ,terminal_id, name, address)
                            VALUES (".$city_id.",".$terminal_id.", '".$name."', '".$address."')";

            $oResult = $oDBHandler->query($sQuery);
        }
    }

    public function GetAdditionalTerminals($oDBHandler,$city_id)
    {
        if(!IS_PRODUCTION)
        {
            $host = 'localhost';
        }
        else
        {
            $host = DB_HOST;
        }

        $oHandler = new mysqlii($host, LANG_DB_LOGIN, LANG_DB_PASSWORD, KLADR_DB_NAME);

        $sSearchQuery = "SELECT city_id ,terminal_id, name, address
							FROM ".DB_ADDITIONAL_TERMINAL."
							WHERE city_id=".$city_id;

        $oSearchResult = $oHandler->query($sSearchQuery);

        $oResult["terminal"] = array();

        if ($oDBHandler->affected_rows > 0) {
            $oRow = $oSearchResult->fetch_assoc();

            $terminal = array(
                 "id" => $oRow["terminal_id"],
                 "name" => $oRow["name"],
                 "address" => $oRow["address"]
            );
            $oResult["terminal"][] = $terminal;
        }

        $Result["terminals"] = $oResult["terminal"];

        return $Result["terminals"];

    }

}

function GetCompanyFormShortName($oDBHandler,$FormID)
{
    $sSearchQuery = "SELECT short_name
							FROM ".DB_JUR_FORM_TABLE."
							WHERE id= $FormID";

    $oSearchResult = $oDBHandler->query($sSearchQuery);
   // if (IS_DEBUG) echo $sSearchQuery, '<br><br>';
    if ($oDBHandler->affected_rows > 0) {
        $oRow = $oSearchResult->fetch_assoc();
        return $oRow["short_name"];
    }
    else
    {
        return '';
    }
}

?>
