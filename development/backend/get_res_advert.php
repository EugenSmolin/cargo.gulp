<?php

//require_once('check_key.php');

/////////////////////////////////


//
// advert load module
//

$_stat_action = "get_res_advert";

require_once('modules/stat_mod.php');

$image = "../advert/res_advert.png";

if (file_exists($image)) {
    header('Content-type: image/png');
    header('Content-Length: ' . filesize($image));
    header('Content-Disposition: attachment; filename=res_advert.png');
    
    $imagefile = file_get_contents($image);
    
    print($imagefile);
}
else {
    header('HTTP/1.0 404 Not Found');
}
?>