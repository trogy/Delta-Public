<?php
/* 
Trogy.NZ Delta Self Hosted Edition
(C) Marc Anderson | All Rights Reserved 
File Last Updated : 9/28/2021
*/
error_reporting(E_ERROR | E_PARSE);

//$Sort = $_GET['SORT'];
file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] LOC_NEW.php Has been called. ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL , FILE_APPEND);
$file = fopen('https://raw.githubusercontent.com/minhealthnz/nz-covid-data/main/locations-of-interest/august-2021/locations-of-interest.csv', 'r');
$csv = array();
while (($line = fgetcsv($file)) !== FALSE) {
  if ( array(0 => null) !== $line ) {
      $data[] = $line;
  }
}
fclose($file);

//echo '<pre>' . var_export($csv, true) . '</pre>';
//echo '<pre>' . var_export($csv, true) . '</pre>';

foreach ($data as $i => $v) {
    if (!empty($v['0'])) {
        unset($data[$i]['0']);
        unset($data[$i]['6']);
    }
}
usort($data, function($a, $b) {
    return strtotime(str_replace('/', '-', $a[4])) <=> strtotime(str_replace('/', '-', $b[4]));
});
$data = array_reverse($data);

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
$nzdateobj = new DateTime("now", new DateTimeZone('Pacific/Auckland'));
$nzdate = $nzdateobj->format('Y-m-d H:i:s');

foreach ($data as $i => $v) {
  if(strstr($data[$i]['9'], '/')){
  $data[$i]['9'] = str_replace('/', '-', $data[$i]['9']);
  }
  $dif = strtotime($nzdate) - strtotime($data[$i]['9']);
  if(empty($data[$i]['9'])){
    $data[$i]['9'] = "No Date Provided";
  }
  if($dif < 86400){

  }
  else{
    unset($data[$i]);
  }
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
//echo '<pre>' . var_export($data, true) . '</pre>';
  file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] Locations Of Interest Generated! [FILTERED NEWLY ADDED] ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL , FILE_APPEND);
