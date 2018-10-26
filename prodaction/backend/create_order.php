<?php

/*
 * @author Anton Dovgan <blackc.blackc@gmail.com>
 * 
 * @param string	JSON in POST body with parameters
 * 
 * @return string	JSON with results
 * 
 */
require_once "./service/config.php";
require_once "./service/service.php";
require_once "services.php";
require_once "./service/user_class.php";
require_once "./service/order_class.php";
require_once "./service/auth.php";
require_once "./service/finance_class.php";
//require_once "./service/mail_class.php";
//require_once 'documents/order_bill.php';

$imagesPath = 'email_template/content/img/';

// check POST body
$oPOSTData = json_decode( file_get_contents( "php://input" ) );

if ( IS_PRODUCTION ) {
	$iUserID = CheckAuth();
	if ( $iUserID === false ) {
		DropWithUnAuth();
	}
} else {
	$iUserID = 15;
}
/*
// check if auth and new pass presence
if ((!isset($oPOSTData->auth->login)) or (!isset($oPOSTData->auth->password)))
	{
		DropWithUnAuth();
	}
*/
// connect to DB
$mysqli = new mysqlii( DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME );

// check DB connection
if ( $mysqli->connect_errno ) {
	DropWithServerError( "DB error" );
}

// create User object
$cUser = new User();

// trying to authenticate
$iAuth = $cUser->UserFromID( $mysqli, $iUserID );

if ( $iAuth != USER_OK ) {
	DropWithUnAuth();
}

//print(strlen($oPOSTData->modifies->cargoRecipientPassport));

// check for parameters presence
if ( ! isset( $oPOSTData->modifies->clientID )
     //or !isset($oPOSTData->modifies->cargoName)
     or ( ! isset( $oPOSTData->modifies->cargoCompanyID ) )
     or ! isset( $oPOSTData->modifies->cargoFrom )
     or ! isset( $oPOSTData->modifies->cargoTo )
     or ! isset( $oPOSTData->modifies->cargoWeight )
     or ! isset( $oPOSTData->modifies->cargoPrice )
     //or !isset($oPOSTData->modifies->cargoSite)
     or ( ! isset( $oPOSTData->modifies->cargoVol )
          and ! isset( $oPOSTData->modifies->cargoLength )
              and ! isset( $oPOSTData->modifies->cargoWidth )
                  and ! isset( $oPOSTData->modifies->cargoHeight ) )
) {
	DropWithBadRequest( "Not enough or wrong parameters" );
}


if ( isset( $oPOSTData->modifies->carriageTypeName ) ) {
	$carriageTypeName         = $oPOSTData->modifies->carriageTypeName;
	$isCarriageTypeNameEnable = true;
}

// prepare data for parcel

if ( ! $cUser->isAdmin ) {
	$iClientID = $cUser->userID;
} else {
	$iClientID = $oPOSTData->modifies->clientID;
}

/* Check RecipientPhysOrJur parameters*/
$isRecipientJur = false;
$isSenderJur    = false;

$isDerivalCourier = false;
$isArrivalCourier = false;

if ( ! isset( $oPOSTData ) ) {
	DropWithBadRequest( "Ошибка в полученой структуре JSON" );
}

if ( intval( $oPOSTData->modifies->cargoCompanyID ) == 32 ) {
	if ( ! isset( $oPOSTData->modifies->derivalCourier ) ) {
		DropWithBadRequest( "derivalCourier didn't set" );
	} else {
		if ( $oPOSTData->modifies->derivalCourier == "Забор груза от адреса отправителя" ) {
			if ( ! isset( $oPOSTData->modifies->cargoSenderAddressCode )
				//or (!isset($oPOSTData->modifies->cargoSenderHouseNumber))
			) {
				DropWithBadRequest( "Не задан код улицы отправителя" );
			}

			$isDerivalCourier = true;
		} else {
			if ( ! isset( $oPOSTData->modifies->derivalTerminalId ) ) {
				DropWithBadRequest( "Not enough parameters for selected derivalTerminalId" );
			}
		}
	}

	if ( ! isset( $oPOSTData->modifies->arrivalCourier ) ) {
		DropWithBadRequest( "arrivalCourier didn't set" );
	} else {
		if ( $oPOSTData->modifies->arrivalCourier == "Доставить груз до адреса получателя" ) {
			if ( ! isset( $oPOSTData->modifies->cargoRecepientAddressCode )
				//  or (!isset($oPOSTData->modifies->cargoRecepientHouseNumber))
			) {
				DropWithBadRequest( "Не задан код улицы получателя" );
			}

			$isArrivalCourier = true;
		} else {
			if ( ! isset( $oPOSTData->modifies->arrivalTerminalId ) ) {
				DropWithBadRequest( "Not enough parameters for selected derivalTerminalId" );
			}
		}
	}
}
if ( ! isset( $oPOSTData->modifies->recepientPhysOrJur ) ) {
	DropWithBadRequest( "RecepientPhysOrJur didn't set" );
} else {
	if ( $oPOSTData->modifies->recepientPhysOrJur == "Физическое лицо" ) {

		if ( ! isset( $oPOSTData->modifies->cargoRecepientFirstName ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientFirstName" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoRecepientLastName ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientLastName" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoRecepientDocumentTypeId ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientDocumentTypeId" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoRecepientDocumentNumber ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientDocumentNumber" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoRecepientPhone ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientPhone" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoRecepientEmail ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientEmail" );
		}
	} elseif ( $oPOSTData->modifies->recepientPhysOrJur == "Юридическое лицо" ) {
		$isRecipientJur = true;
		if ( ! isset( $oPOSTData->modifies->cargoRecepientContactFirstName ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientContactFirstName" );
		}

		if ( ! isset( $oPOSTData->modifies->cargoRecepientContactLastName ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientContactLastName" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoRecepientCompanyName ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientCompanyName" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoRecepientCompanyFormId ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientCompanyFormId" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoRecepientCompanyINN ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientCompanyINN" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoRecepientCompanyPhone ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientCompanyPhone" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoRecepientCompanyAddress ) ) {
			DropWithBadRequest( "Not set parameter cargoRecepientCompanyAddress" );
		}

	} else {
		DropWithBadRequest( "Not assigned RecepientPhysOrJur parameter" );
	}
}


if ( ! isset( $oPOSTData->modifies->senderPhysOrJur ) ) {
	DropWithBadRequest( "SenderPhysOrJur didn't set" );
} else {
	if ( $oPOSTData->modifies->senderPhysOrJur == "Физическое лицо" ) {
		if ( ! isset( $oPOSTData->modifies->cargoSenderFirstName ) ) {
			DropWithBadRequest( "Not set parameter cargoSenderFirstName" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoSenderLastName ) ) {
			DropWithBadRequest( "Not set parameter cargoSenderLastName" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoSenderDocumentTypeId ) ) {
			DropWithBadRequest( "Not set parameter cargoSenderDocumentTypeId" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoSenderDocumentNumber ) && $oPOSTData->modifies->cargoSenderDocumentNumber == '' ) {
			DropWithBadRequest( "Not set parameter cargoSenderDocumentNumber" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoSenderPhone ) ) {
			DropWithBadRequest( "Not set parameter cargoSenderPhone" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoSenderEmail ) ) {
			DropWithBadRequest( "Not set parameter cargoSenderEmail" );
		}
	} elseif ( $oPOSTData->modifies->senderPhysOrJur == "Юридическое лицо" ) {
		$isSenderJur = true;
		if ( ! isset( $oPOSTData->modifies->cargoSenderContactFirstName ) ) {
			DropWithBadRequest( "Not set parameter cargoSenderContactFirstName" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoSenderContactLastName ) ) {
			DropWithBadRequest( "Not set parameter cargoSenderContactFirstName" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoSenderCompanyName ) ) {
			DropWithBadRequest( "Not set parameter cargoSenderContactFirstName" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoSenderCompanyFormId ) ) {
			DropWithBadRequest( "Not set parameter cargoSenderContactFirstName" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoSenderCompanyINN ) ) {
			DropWithBadRequest( "Not set parameter cargoSenderContactFirstName" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoSenderCompanyPhone ) ) {
			DropWithBadRequest( "Not set parameter cargoSenderContactFirstName" );
		}
		if ( ! isset( $oPOSTData->modifies->cargoSenderCompanyAddress ) ) {
			DropWithBadRequest( "Not set parameter cargoSenderCompanyAddress" );
		}

	} else {
		DropWithBadRequest( "Not assigned SenderPhysOrJur parameter" );
	}
}

$userLang     = "ru";
$userCurrency = "RUB";

if ( $cUser->userDefLang != "" ) {
	$userLang = $cUser->userDefLang;
}

if ( $cUser->userDefCurr != "" ) {
	$userCurrency = strtoupper( $cUser->userDefCurr );
}

if ( $oPOSTData->modifies->cargoWidth != "" &&
     $oPOSTData->modifies->cargoLength != "" &&
     $oPOSTData->modifies->cargoHeight != "" ) {
	$isActiveLineParams = 1;
}

$cargoFrom = CityCorrection( $oPOSTData->modifies->cargoFrom );

if ( ! IS_PRODUCTION ) {
	echo '<br>До:', $oPOSTData->modifies->cargoFrom, ' <br>После:', $cargoFrom, '<br>';
}

$cargoTo = CityCorrection( $oPOSTData->modifies->cargoTo );

if ( ! IS_PRODUCTION ) {
	echo '<br>До:', $oPOSTData->modifies->cargoTo, ' <br>После:', $cargoTo, '<br>';
}

$transports = array();
$rundir     = 'modules';
// include all modules
foreach ( glob( $rundir . "/CALC_*.php" ) as $modname ) {

	include $modname;
}

$oCompanyDesc = $transports[ intval( $oPOSTData->modifies->cargoCompanyID ) ];

$sCompanyOrderNum = 0;

$sCompClassName = $oCompanyDesc["classname"];
$oCalculator    = new $sCompClassName();

if ( intval( $oPOSTData->modifies->cargoCompanyID ) == 32 ) {

	$oRetVal = $oCalculator->Calculate2(
		$cargoFrom,
		$cargoTo,
		$oPOSTData->modifies->cargoWeight,
		$oPOSTData->modifies->cargoVol,
		$oPOSTData->modifies->cargoGoodsPrice,
		$userLang,
		$userCurrency, "RU", "RU",
		$oPOSTData->modifies->cargoFromRegion,
		$oPOSTData->modifies->cargoToRegion,
		$isDerivalCourier,
		$oPOSTData->modifies->cargoSenderAddress,
		$oPOSTData->modifies->cargoSenderAddressCode,
		$isArrivalCourier,
		$oPOSTData->modifies->cargoRecepientAddress,
		$oPOSTData->modifies->cargoRecepientAddressCode,
		null );

} else {
	$oRetVal = $oCalculator->Calculate(
		$cargoFrom,
		$cargoTo,
		$oPOSTData->modifies->cargoWeight,
		$oPOSTData->modifies->cargoVol,
		$oPOSTData->modifies->cargoGoodsPrice,
		$userLang,
		$userCurrency,
		"RU", "RU",
		$oPOSTData->modifies->cargoFromRegion,
		$oPOSTData->modifies->cargoToRegion,
		$isActiveLineParams,
		$oPOSTData->modifies->cargoWidth,
		$oPOSTData->modifies->cargoLength,
		$oPOSTData->modifies->cargoHeight,
		null
	);

}
$calcResultPrice = $oRetVal['methods']['0']["calcResultPrice"];
if ( $isCarriageTypeNameEnable ) {
	foreach ( $oRetVal['methods'] as $method ) {
		if ( ! IS_PRODUCTION ) {
			echo 'Метод: ', $method["name"], ', Стоимость:', $method["calcResultPrice"], '<br>';
		}
		if ( $method["name"] == $carriageTypeName ) {
			$calcResultPrice = floatval( $method["calcResultPrice"] );
		}
	}
}

if ( ! IS_PRODUCTION ) {
	echo 'Параметры калькуляции:', '<br>',
	'cargoFrom ', $cargoFrom, '<br>',
	'cargoTo ', $cargoTo, '<br>',
	'cargoWeight ', $oPOSTData->modifies->cargoWeight, '<br>',
	'cargoVol ', $oPOSTData->modifies->cargoVol, '<br>',
	'cargoGoodsPrice ', $oPOSTData->modifies->cargoGoodsPrice, '<br>',
	'$userLang ', $userLang, '<br>',
	'$userCurrency ', $userCurrency, '<br>',
	"RU", "RU",
	'cargoFromRegion ', $oPOSTData->modifies->cargoFromRegion, '<br>',
	'cargoToRegion ', $oPOSTData->modifies->cargoToRegion, '<br>',
	'$isActiveLineParams ', $isActiveLineParams, '<br>',
	'cargoWidth ', $oPOSTData->modifies->cargoWidth, '<br>',
	'cargoLength ', $oPOSTData->modifies->cargoLength, '<br>',
	'cargoHeight ', $oPOSTData->modifies->cargoHeight, '<br>';


	echo 'Расчетная цена: ', $calcResultPrice;

	echo 'Передаю цену: ', round( floatval( $oPOSTData->modifies->cargoPrice ), 2 ), '<br>';
}

$oDiscount = GetDiscount( $oPOSTData->modifies->paymentType, intval( $oPOSTData->modifies->cargoCompanyID ) );
//var_dump($oDiscount) ; die;


if ( isset( $oDiscount['percent'] ) ) {
	//var_dump(round($oRetVal['methods']['0']["calcResultPrice"]),2); die;
	$discountPercent   = $oDiscount['percent'] / 100;
	$discountPrice     = round( $calcResultPrice * $discountPercent, 2 );
	$priceWithDiscount = $calcResultPrice - $discountPrice;
	if ( ! IS_PRODUCTION ) {
		echo '<br>Price with discount:', round( $priceWithDiscount, 2 ), '<br>';
	}
} else {
	$priceWithDiscount = $calcResultPrice;
}

if ( round( $priceWithDiscount, 2 ) != round( floatval( $oPOSTData->modifies->cargoPrice ), 2 ) ) {
	DropWithBadRequest( "Цена не соответствует заявленой услуге. Измените параметры заявки или обратитесь к администрации сайта" );
}

/////////////////// create order
// create order

$oNewOrder = new Order();

$iNewOrderResult = $oNewOrder->NewOrder
(
	$mysqli,
	$iClientID,
	$oPOSTData->modifies->cargoCompanyID,
	$oPOSTData->modifies->cargoName,
	$oPOSTData->modifies->cargoFromCoordinate,
	$oPOSTData->modifies->cargoToCoordinate,
	$oPOSTData->modifies->cargoFrom,
	$oPOSTData->modifies->cargoTo,
	$oPOSTData->modifies->cargoFromZip,
	$oPOSTData->modifies->cargoToZip,
	$oPOSTData->modifies->cargoFromRegion,
	$oPOSTData->modifies->cargoToRegion,

	$oPOSTData->modifies->cargoWeight,
	$oPOSTData->modifies->cargoVol,
	$oPOSTData->modifies->cargoLength,
	$oPOSTData->modifies->cargoWidth,
	$oPOSTData->modifies->cargoHeight,
	$oPOSTData->modifies->cargoGoodsPrice,
	$oPOSTData->modifies->cargoPrice,
	$oPOSTData->modifies->cargoMethod,
	$oPOSTData->modifies->cargoSite,
	$oPOSTData->modifies->comment,
	$oPOSTData->modifies->cargoDangerClassId,
	$oPOSTData->modifies->cargoTemperatureModeId,
	$oPOSTData->modifies->cargoGoodsName,
	$oPOSTData->modifies->cargoDesireDate,
	$oPOSTData->modifies->cargoDeliveryDate,
	json_encode( $oPOSTData->modifies ),
	$oPOSTData->modifies->paymentType,
	$oPOSTData->modifies->payerType,

	$isSenderJur,
	$oPOSTData->modifies->cargoSenderFirstName,
	$oPOSTData->modifies->cargoSenderLastName,
	$oPOSTData->modifies->cargoSenderSecondName,
	$oPOSTData->modifies->cargoSenderPhone,
	$oPOSTData->modifies->cargoSenderEmail,
	$oPOSTData->modifies->cargoSenderDocumentTypeId,
	$oPOSTData->modifies->cargoSenderDocumentNumber,


	$oPOSTData->modifies->cargoSenderCompanyName,
	$oPOSTData->modifies->cargoSenderCompanyFormId,
	$oPOSTData->modifies->cargoSenderCompanyPhone,
	$oPOSTData->modifies->cargoSenderCompanyEmail,
	$oPOSTData->modifies->cargoSenderCompanyINN,
	$oPOSTData->modifies->cargoSenderCompanyAddress,
	$oPOSTData->modifies->cargoSenderCompanyAddressCell,
	$oPOSTData->modifies->cargoSenderContactFirstName,
	$oPOSTData->modifies->cargoSenderContactLastName,


	$oPOSTData->modifies->derivalTerminalId,
	$oPOSTData->modifies->derivalTerminalName,
	$oPOSTData->modifies->cargoSenderAddress,
	$oPOSTData->modifies->cargoSenderAddressCode,
	$oPOSTData->modifies->cargoSenderAddressHouseNumber,
	$oPOSTData->modifies->cargoSenderAddressBuildingNumber,
	$oPOSTData->modifies->cargoSenderAddressStructureNumber,
	$oPOSTData->modifies->cargoSenderAddressCell,

	$isRecipientJur,

	$oPOSTData->modifies->cargoRecepientPhone,
	$oPOSTData->modifies->cargoRecepientEmail,

	$oPOSTData->modifies->cargoRecepientFirstName,
	$oPOSTData->modifies->cargoRecepientSecondName,
	$oPOSTData->modifies->cargoRecepientLastName,
	$oPOSTData->modifies->cargoRecepientDocumentTypeId,
	$oPOSTData->modifies->cargoRecepientDocumentNumber,

	$oPOSTData->modifies->cargoRecepientCompanyName,
	$oPOSTData->modifies->cargoRecepientCompanyFormId,
	$oPOSTData->modifies->cargoRecepientCompanyPhone,
	$oPOSTData->modifies->cargoRecepientCompanyEmail,
	$oPOSTData->modifies->cargoRecepientCompanyINN,
	$oPOSTData->modifies->cargoRecepientCompanyAddress,
	$oPOSTData->modifies->cargoRecepientCompanyAddressCell,
	$oPOSTData->modifies->cargoRecepientContactFirstName,
	$oPOSTData->modifies->cargoRecepientContactLastName,

	$oPOSTData->modifies->arrivalTerminalId,
	$oPOSTData->modifies->arrivalTerminalName,
	$oPOSTData->modifies->cargoRecepientAddress,
	$oPOSTData->modifies->cargoRecepientAddressCode,
	$oPOSTData->modifies->cargoRecepientAddressHouseNumber,
	$oPOSTData->modifies->cargoRecepientAddressBuildingNumber,
	$oPOSTData->modifies->cargoRecepientAddressStructureNumber,
	$oPOSTData->modifies->cargoRecepientAddressCell,

	$isDerivalCourier,
	$isArrivalCourier,
	$oPOSTData->modifies->cargoVolUnitName,
	$oPOSTData->modifies->cargoWeightUnitName
);

if ( IS_DEBUG ) {
	echo '<br>NewOrderResult<br>' . $iNewOrderResult . '<br>';
}

if ( $iNewOrderResult > 0 ) {

	if ( ( isset( $oCompanyDesc['canorder'] ) ) and ( $oCompanyDesc['canorder'] === true ) ) {
		//print_r($cUser);
		// call company order


		$sRecipientFIO = trim( $oPOSTData->modifies->cargoRecepientLastName )
		                 . ' ' . trim( $oPOSTData->modifies->cargoRecepientFirstName ) . ' '
		                 . trim( $oPOSTData->modifies->cargoRecepientSecondName );

		if ( isset( $oPOSTData->modifies->cargoRecepientContactFirstName )
		     &&
		     isset( $oPOSTData->modifies->cargoRecepientContactLastName ) ) {
			$sRecipientContactFIO =
				trim( $oPOSTData->modifies->cargoRecepientContactFirstName )
				. ' ' . trim( $oPOSTData->modifies->cargoRecepientContactLastName );
		} else {
			$sRecipientContactFIO = trim( $sRecipientFIO );
		}

		$sSenderFIO = trim( $oPOSTData->modifies->cargoSenderLastName ) . ' '
		              . trim( $oPOSTData->modifies->cargoSenderFirstName ) . ' '
		              . trim( $oPOSTData->modifies->cargoSenderSecondName );

		if ( isset( $oPOSTData->modifies->cargoSenderContactFirstName )
		     &&
		     isset( $oPOSTData->modifies->cargoSenderContactLastName ) ) {
			$sSenderContactFIO = trim( $oPOSTData->modifies->cargoSenderContactFirstName )
			                     . ' ' . trim( $oPOSTData->modifies->cargoSenderContactLastName );
		} else {
			$sSenderContactFIO = trim( $sSenderFIO );
		}

		$sSenderCompanyFormShortName = '';
		if ( $isSenderJur ) {
			$sSenderCompanyFormShortName = GetCompanyFormShortName( $mysqli,
				$oPOSTData->modifies->cargoSenderCompanyFormId );

			if ( $sSenderCompanyFormShortName == '' ) {
				DropWithBadRequest( "Bad SenderCompanyFormID" );
			}
		}
		$sRecipientCompanyFormShortName = '';
		if ( $isRecipientJur ) {
			$sRecipientCompanyFormShortName = GetCompanyFormShortName( $mysqli,
				$oPOSTData->modifies->cargoRecepientCompanyFormId );

			if ( $sRecipientCompanyFormShortName == '' ) {
				DropWithBadRequest( "Bad RecipientCompanyFormID" );
			}
		}


		$oPOSTData->modifies->internalNumber = $iNewOrderResult;
		$sCompanyOrderNum                    = 0;
		if ( intval( $oPOSTData->modifies->cargoCompanyID ) == 32 ) {
			$oCompanyObject   = new calculator_DELLIN();
			$sCompanyOrderNum = $oCompanyObject->MakeOrderWithAddressDelivery(
				$oPOSTData->modifies->cargoFrom,
				$oPOSTData->modifies->cargoTo,
				$oPOSTData->modifies->cargoFromZip,
				$oPOSTData->modifies->cargoToZip,
				$oPOSTData->modifies->cargoFromRegion,
				$oPOSTData->modifies->cargoToRegion,
				$oPOSTData->modifies->cargoWeight,
				$oPOSTData->modifies->cargoVol,
				$oPOSTData->modifies->cargoGoodsPrice,
				$oPOSTData->modifies->cargoLength,
				$oPOSTData->modifies->cargoWidth,
				$oPOSTData->modifies->cargoHeight,
				$oPOSTData->modifies->cargoGoodsName,
				$oPOSTData->modifies->cargoDate,
				$oPOSTData->modifies,

				$isRecipientJur,
				$sRecipientFIO,
				$oPOSTData->modifies->cargoRecepientDocumentTypeId,
				$oPOSTData->modifies->cargoRecepientDocumentNumber,
				$oPOSTData->modifies->cargoRecepientPhone,
				$oPOSTData->modifies->cargoRecepientEmail,
				$oPOSTData->modifies->arrivalTerminalId,
				$oPOSTData->modifies->cargoRecepientCompanyName,
				$sRecipientCompanyFormShortName,
				$oPOSTData->modifies->cargoRecepientCompanyINN,
				$oPOSTData->modifies->cargoRecepientCompanyAddress,
				$oPOSTData->modifies->cargoRecepientCompanyAddressCell,
				$oPOSTData->modifies->cargoRecepientCompanyPhone,
				$oPOSTData->modifies->cargoRecepientCompanyEmail,
				$sRecipientContactFIO,
				$oPOSTData->modifies->cargoRecepientAddress,
				$oPOSTData->modifies->cargoRecepientAddressCode,
				$oPOSTData->modifies->cargoRecepientAddressHouseNumber,
				$oPOSTData->modifies->cargoRecepientAddressBuildingNumber,
				$oPOSTData->modifies->cargoRecepientAddressStructureNumber,
				$oPOSTData->modifies->cargoRecepientAddressCell,

				$isSenderJur,
				$sSenderFIO,
				$oPOSTData->modifies->cargoSenderDocumentTypeId,
				$oPOSTData->modifies->cargoSenderDocumentNumber,
				$oPOSTData->modifies->cargoSenderPhone,
				$oPOSTData->modifies->cargoSenderEmail,
				$oPOSTData->modifies->derivalTerminalId,
				$oPOSTData->modifies->cargoSenderCompanyName,
				$sSenderCompanyFormShortName,
				$oPOSTData->modifies->cargoSenderCompanyINN,
				$oPOSTData->modifies->cargoSenderCompanyAddress,
				$oPOSTData->modifies->cargoSenderCompanyAddressCell,
				$oPOSTData->modifies->cargoSenderCompanyPhone,
				$oPOSTData->modifies->cargoSenderCompanyEmail,
				$sSenderContactFIO,
				$oPOSTData->modifies->cargoSenderAddress,
				$oPOSTData->modifies->cargoSenderAddressCode,
				$oPOSTData->modifies->cargoSenderAddressHouseNumber,
				$oPOSTData->modifies->cargoSenderAddressBuildingNumber,
				$oPOSTData->modifies->cargoSenderAddressStructureNumber,
				$oPOSTData->modifies->cargoSenderAddressCell,

				$isDerivalCourier,
				$isArrivalCourier,
				$oPOSTData->modifies->cargoDesireDate,
				$oPOSTData->modifies->cargoDeliveryDate

			);
		} elseif ( intval( $oPOSTData->modifies->cargoCompanyID ) == 150 ) {
			$oCompanyObject   = new calculator_SDEK();
			$sCompanyOrderNum = $oCompanyObject->MakeOrder(
				$oPOSTData->modifies->cargoFrom,
				$oPOSTData->modifies->cargoTo,
				$oPOSTData->modifies->cargoFromZip,
				$oPOSTData->modifies->cargoToZip,
				$oPOSTData->modifies->cargoFromRegion,
				$oPOSTData->modifies->cargoToRegion,
				$oPOSTData->modifies->cargoWeight,
				$oPOSTData->modifies->cargoVol,
				$oPOSTData->modifies->cargoGoodsPrice,
				$oPOSTData->modifies->cargoLength,
				$oPOSTData->modifies->cargoWidth,
				$oPOSTData->modifies->cargoHeight,
				$oPOSTData->modifies->cargoGoodsName,
				$oPOSTData->modifies->cargoDate,
				$oPOSTData->modifies,

				$isRecipientJur,
				$sRecipientFIO,
				$oPOSTData->modifies->cargoRecepientDocumentTypeId,
				$oPOSTData->modifies->cargoRecepientDocumentNumber,
				$oPOSTData->modifies->cargoRecepientPhone,
				$oPOSTData->modifies->cargoRecepientEmail,
				$oPOSTData->modifies->arrivalTerminalId,
				$oPOSTData->modifies->cargoRecepientCompanyName,
				$sRecipientCompanyFormShortName,
				$oPOSTData->modifies->cargoRecepientCompanyINN,
				$oPOSTData->modifies->cargoRecepientCompanyAddress,
				$oPOSTData->modifies->cargoRecepientCompanyAddressCell,
				$oPOSTData->modifies->cargoRecepientCompanyPhone,
				$oPOSTData->modifies->cargoRecepientCompanyEmail,
				$sRecipientContactFIO,
				$oPOSTData->modifies->cargoRecepientAddress,
				$oPOSTData->modifies->cargoRecepientAddressCode,
				$oPOSTData->modifies->cargoRecepientAddressHouseNumber,
				$oPOSTData->modifies->cargoRecepientAddressBuildingNumber,
				$oPOSTData->modifies->cargoRecepientAddressStructureNumber,
				$oPOSTData->modifies->cargoRecepientAddressCell,

				$isSenderJur,
				$sSenderFIO,
				$oPOSTData->modifies->cargoSenderDocumentTypeId,
				$oPOSTData->modifies->cargoSenderDocumentNumber,
				$oPOSTData->modifies->cargoSenderPhone,
				$oPOSTData->modifies->cargoSenderEmail,
				$oPOSTData->modifies->derivalTerminalId,
				$oPOSTData->modifies->cargoSenderCompanyName,
				$sSenderCompanyFormShortName,
				$oPOSTData->modifies->cargoSenderCompanyINN,
				$oPOSTData->modifies->cargoSenderCompanyAddress,
				$oPOSTData->modifies->cargoSenderCompanyAddressCell,
				$oPOSTData->modifies->cargoSenderCompanyPhone,
				$oPOSTData->modifies->cargoSenderCompanyEmail,
				$sSenderContactFIO,
				$oPOSTData->modifies->cargoSenderAddress,
				$oPOSTData->modifies->cargoSenderAddressCode,
				$oPOSTData->modifies->cargoSenderAddressHouseNumber,
				$oPOSTData->modifies->cargoSenderAddressBuildingNumber,
				$oPOSTData->modifies->cargoSenderAddressStructureNumber,
				$oPOSTData->modifies->cargoSenderAddressCell,

				$isDerivalCourier,
				$isArrivalCourier,
				$oPOSTData->modifies->cargoDesireDate,
				$oPOSTData->modifies->cargoDeliveryDate
			);
		} else {
			$sCompClassName   = $oCompanyDesc["classname"];
			$oCompanyObject   = new $sCompClassName();
			$sCompanyOrderNum = $oCompanyObject->MakeOrder(
				$oPOSTData->modifies->cargoFrom,
				$oPOSTData->modifies->cargoTo,
				$oPOSTData->modifies->cargoFromZip,
				$oPOSTData->modifies->cargoToZip,
				$oPOSTData->modifies->cargoFromRegion,
				$oPOSTData->modifies->cargoToRegion,
				$oPOSTData->modifies->cargoWeight,
				$oPOSTData->modifies->cargoVol,
				$oPOSTData->modifies->cargoGoodsPrice,
				$oPOSTData->modifies->cargoLength,
				$oPOSTData->modifies->cargoWidth,
				$oPOSTData->modifies->cargoHeight,
				$oPOSTData->modifies->cargoGoodsName,
				$oPOSTData->modifies->cargoDate,
				$oPOSTData->modifies,

				$isRecipientJur,
				$sRecipientFIO,
				$oPOSTData->modifies->cargoRecepientDocumentTypeId,
				$oPOSTData->modifies->cargoRecepientDocumentNumber,
				$oPOSTData->modifies->cargoRecepientPhone,
				$oPOSTData->modifies->cargoRecepientEmail,
				$oPOSTData->modifies->arrivalTerminalId,
				$oPOSTData->modifies->cargoRecepientCompanyName,
				$sRecipientCompanyFormShortName,
				$oPOSTData->modifies->cargoRecepientCompanyINN,
				$oPOSTData->modifies->cargoRecepientCompanyAddress,
				$oPOSTData->modifies->cargoRecepientCompanyAddressCell,
				$oPOSTData->modifies->cargoRecepientCompanyPhone,
				$oPOSTData->modifies->cargoRecepientCompanyEmail,
				$sRecipientContactFIO,
				$oPOSTData->modifies->cargoRecepientAddress,
				$oPOSTData->modifies->cargoRecepientAddressCell,

				$isSenderJur,
				$sSenderFIO,
				$oPOSTData->modifies->cargoSenderDocumentTypeId,
				$oPOSTData->modifies->cargoSenderDocumentNumber,
				$oPOSTData->modifies->cargoSenderPhone,
				$oPOSTData->modifies->cargoSenderEmail,
				$oPOSTData->modifies->derivalTerminalId,
				$oPOSTData->modifies->cargoSenderCompanyName,
				$sSenderCompanyFormShortName,
				$oPOSTData->modifies->cargoSenderCompanyINN,
				$oPOSTData->modifies->cargoSenderCompanyAddress,
				$oPOSTData->modifies->cargoSenderCompanyAddressCell,
				$oPOSTData->modifies->cargoSenderCompanyPhone,
				$oPOSTData->modifies->cargoSenderCompanyEmail,
				$sSenderContactFIO,
				$oPOSTData->modifies->cargoSenderAddress,
				$oPOSTData->modifies->cargoSenderAddressCell,

				$isDerivalCourier,
				$isArrivalCourier,
				$oPOSTData->modifies->cargoDesireDate,
				$oPOSTData->modifies->cargoDeliveryDate

			);
		}


		if ( is_array( $sCompanyOrderNum ) ) {
			DropWithServerError( "Errors: " . implode( ',', $sCompanyOrderNum ) );
		} else if ( intval( $sCompanyOrderNum ) <= 0 ) {
			DropWithServerError( "Cargo Company cannot create order with this parameters." );
		} else {

			$oNewOrder->sCompanyInternalNumber = $sCompanyOrderNum;
			$oNewOrder->SaveOrder( $mysqli );

		}

		$isTtnFileCrated = $oCompanyObject->SavePdf( $sCompanyOrderNum, $iNewOrderResult );

	}

	$oFinance = new Finance();
	$oFinance->NewOperation( $mysqli, 0 - floatval( $oPOSTData->modifies->cargoPrice ), $iClientID, $iNewOrderResult, "Задолженность за логистические услуги" );

	$sNewStateQuery = "INSERT INTO `" . DB_STATES_TABLE . "` (`order_id`, `operation_id`, `comment`) 
					  VALUES (" . $iNewOrderResult . ", " .
	                  "1, \"Создание заказа\")";
	$iNewStateRes   = $mysqli->query( $sNewStateQuery );

	$message = '';

	$sCargoLogoPath = '';

	switch ( intval( $oPOSTData->modifies->cargoCompanyID ) ) {
		case 32:
			$sCargoLogoPath = $imagesPath . "logo-dellin.jpg";
			break;
		case 136:
			$sCargoLogoPath = $imagesPath . "logo-intime.jpg";
			break;
		case 8:
			$sCargoLogoPath = $imagesPath . "logo_airgreenland.jpg";
			break;
		case 130:
			$sCargoLogoPath = $imagesPath . "logo_matkahuolto.jpg";
			break;
		case 96:
			$sCargoLogoPath = $imagesPath . "logo_xpologistics.jpg";
			break;
		default:
			$sCargoLogoPath = GetImagePath( $oCompanyDesc['classname'], $oCompanyDesc['logo'] );
			break;
	}

	/** Send mail to dispatcher */

	$mail = new Mail();

	$mailToDispatcher = $mail->SendOrderMailToClient( $mysqli, $iNewOrderResult, "RU", 'dispatcher', $sCargoLogoPath );

	if ( ! IS_PRODUCTION ) {
		var_dump( $mailToDispatcher );
		var_dump( $mail );
	}
	/** Send mail to customer */

	$mailToClient = $mail->SendOrderMailToClient( $mysqli, $iNewOrderResult, "RU", 'customer', $sCargoLogoPath );

	if ( ! IS_PRODUCTION ) {
		var_dump( $mailToClient );
		var_dump( $mail );
	}
	/** Send mail to sender */

	$mailToSender = $mail->SendOrderMailToClient( $mysqli, $iNewOrderResult, "RU", 'sender', $sCargoLogoPath );

	if ( ! IS_PRODUCTION ) {
		var_dump( $mailToSender );
		var_dump( $mail );
	}

	if ( ! ( $mailToDispatcher === true ) ) {
		$message .= 'Mail to dispatcher not send. ';
	}

	if ( ! ( $mailToClient === true ) ) {
		$message .= 'Mail to client not send. ';
	}

	if ( ! ( $mailToSender === true ) ) {
		$message .= 'Mail to sender not send. ';
	}

	$file_mail_name  = TTN_PATH . "cargo_order_$iNewOrderResult.pdf";
	$file_mail_name2 = TTN_PATH . "ttn_#" . $iNewOrderResult . ".pdf";

	if ( file_exists( $file_mail_name ) ) {
		unlink( $file_mail_name );
	}

	if ( file_exists( $file_mail_name2 ) ) {
		unlink( $file_mail_name2 );
	}


}
$mysqli->close();

switch ( $iNewOrderResult ) {
	case PARCEL_NO_PARAMS:
		DropWithBadRequest( "Not enough parameters" );
	case PARCEL_DB_ERROR:
		DropWithServerError();
	case PARCEL_EXISTS:
		DropWithServerError( "Order already exists" );
	default: {
		//$oFinance = new Finance();
		//$oFinance->NewOperation($mysqli,0-$oPOSTData->modifies->cargoPrice, $iClientID, $iNewOrderResult, "Задолженность");
		ReturnSuccess( array( "id" => $iNewOrderResult, "message" => $message ) );
	}
}

function GetImagePath( $classname, $logoPath ) {
	$imagesPath     = 'email_template/content/img/';
	$name           = explode( '_', $classname );
	$logoName       = explode( '/', $logoPath );
	$file_sort_name = explode( '.', $logoName[ count( $logoName ) - 1 ] );
	$file_name      = $imagesPath . 'logo_' . strtolower( $name[1] ) . '.' . $file_sort_name[1];

	return $file_name;
}

function CityCorrection( $city ) {

	$cargoCities = explode( ',', $city );

	if ( count( $cargoCities ) < 2 ) {

		return $city;
	}

	return trim( $cargoCities[0] );

}

function GetDiscount( $paymentType, $companyId ) {
	$oHandler = new mysqlii( DB_HOST, DB_RW_LOGIN, DB_RW_PASSWORD, DB_NAME );
	$sqlQuery = "
			SELECT d.percent,d.description
            FROM " . DB_DISCOUNT . " d
            JOIN " . DB_PAYMENT_TYPE_DISCOUNT . " ptd ON d.id = ptd.discount_id
            WHERE ptd.payment_type_id = " . $paymentType . " and ptd.company_id = " . $companyId . "
			LIMIT 1;
			";

	$oRes = $oHandler->query( $sqlQuery );

	if ( ! IS_PRODUCTION ) {
		echo $sqlQuery, '<br>';
	}


	if ( $oHandler->affected_rows > 0 ) {
		$oRow   = $oRes->fetch_assoc();
		$result = [
			"percent"     => floatval( $oRow['percent'] ),
			"description" => strval( $oRow['description'] ),
		];

		return $result;
	} else {
		return null;
	}
}

?>
