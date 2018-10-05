<?php

require_once('mpdf.php');
//use Dompdf\Dompdf;

date_default_timezone_set('UTC');
$html = '<html><head><meta charset="utf-8"></head><body><h1>OLOLO!</h1><h2>it is generated рюзке pdf</h2></body></html>';

$mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /*задаем формат, отступы и.т.д.*/
$mpdf->charset_in = 'utf-8'; /*не забываем про русский*/

$mpdf->WriteHTML($html, 2); /*формируем pdf*/
$mpdf->Output('mpdf.pdf', 'I');

?>
