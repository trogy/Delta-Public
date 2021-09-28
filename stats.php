<?php
/* 
Trogy.NZ Delta Self Hosted Edition
(C) Marc Anderson | All Rights Reserved 
File Last Updated : 9/28/2021
*/
error_reporting(E_ERROR | E_PARSE);
file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] stats.php Has been called. ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL , FILE_APPEND);
$file = fopen('https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_daily_reports/'. gmdate("m-d-Y", strtotime("-1 days")) . '.csv', 'r');
$csv = array();
if ($file == false) {
  $date = gmdate("m-d-Y", strtotime("-2 days"));
  $date2 = gmdate("m-d-Y", strtotime("-3 days"));
  $date3 = gmdate("m-d-Y", strtotime("-1 days"));
  $Cases_Today = file_get_contents($date . "_CASE.casecount");
  $Cases_Yesterday = file_get_contents($date2 . "_CASE.casecount");
  echo 'All time stats: (Last Updated ' . $date3 . ') <br>';
  echo 'Confirmed Cases: ' . $Cases_Today . ' + ' . abs($Cases_Yesterday - $Cases_Today) . '<br>';
  echo 'Deaths: [Data Unavailable]<br>';
  file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [WARNING] Stats Generated! - [Stats File Was 404 NOT FOUND] ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL , FILE_APPEND);
  exit;
}
while (($line = fgetcsv($file)) !== FALSE) {
  if ( array(0 => null) !== $line ) {
      $data[] = $line;
  }
}
fclose($file);

foreach ($data as $i => $v) {
        unset($data[$i]['0']);
        unset($data[$i]['1']);
        unset($data[$i]['11']);
        unset($data[$i]['12']);
        unset($data[$i]['13']);
}

$date = gmdate("m-d-Y", strtotime("-1 days"));
$date2 = gmdate("m-d-Y", strtotime("-2 days"));

foreach ($data as $i => $v) {
  if($data[$i]['3'] == 'New Zealand'){
   if($data[$i]['2'] !== 'Cook Islands'){
     $caseFN = $date . "_CASE.casecount";
      if (file_exists($caseFN)) {
        //File Exists - Skipping
      }
      else{
        $casecount = fopen($date . "_CASE.casecount", "w") or die("Unable to open file!");
        fwrite($casecount, $data[$i]['7']);
      }
      $Cases_Today = file_get_contents($date . "_CASE.casecount");
      $Cases_Yesterday = file_get_contents($date2 . "_CASE.casecount");
      echo 'All time stats: (Last Updated Today [UTC]) <br>';
      echo 'Confirmed Cases: ' . $data[$i]['7'] . ' + ' . abs($Cases_Yesterday - $Cases_Today) . '<br>';
      echo 'Deaths: ' . $data[$i]['8'] . '<br>';;
       file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] Stats Generated! - [Stats File Was 200 OK] ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL , FILE_APPEND);
    }
  }
  else{
    unset($data[$i]['2']);
    unset($data[$i]['3']);
    unset($data[$i]['4']);
    unset($data[$i]['5']);
    unset($data[$i]['6']);
    unset($data[$i]['7']);
    unset($data[$i]['8']);
    unset($data[$i]['9']);
    unset($data[$i]['10']);
    unset($data[$i]);
  }
}

//echo '<pre>' . var_export($data, true) . '</pre>';
