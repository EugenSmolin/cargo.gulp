<?php

require('services.php');
require('ccodes.php');

////////////////////////////////////
//
// rundir
$rundir = 'modules';
//
// Transports array
$transports = array();
//
////////////////////////////////////

// include all modules
foreach(glob($rundir . "/CALC_*.php") as $modname)
    {
	include $modname;
//	print($modname . PHP_EOL);
    }


$cl = 0;
$ncl = 0;
$mapIndexes = array();

foreach($aCCodes as $key => $val)
    {
	$mapIndexes[$key] = 0;
    }

$iAllCountries = 0;

foreach($transports as $transport)
    {
	if (isset($transport['classname']))
	    {
		$cl++;
		$oTransport = new $transport['classname']();

		foreach($oTransport->oDerivals as $sDerival)
		    {
			if ($sDerival == '*')
			    $iAllCountries++;
			else
			    @$mapIndexes[$sDerival]++;
		    }
	    }
	else
		$ncl++;
	
    }

foreach($mapIndexes as $key => $val)
    {
	$mapIndexes[$key] += $iAllCountries;
    }

header('Content-type: application/json');
print(json_encode($mapIndexes));

?>
