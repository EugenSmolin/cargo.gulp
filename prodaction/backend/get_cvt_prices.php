<?php

require_once('check_key.php');

/////////////////////////////////

$_stat_action = "get_cvt_prices";

require_once('services.php');
require_once('modules/stat_mod.php');

@$src = floatval($_POST['number']);
@$src_curr = substr($_POST['curr'],0,3);

//$src=44;
//$src_curr='RUB';

$retval = GetConvertedPrices($src,$src_curr);

header('Content-type: application/json;');
print(json_encode($retval));

?>