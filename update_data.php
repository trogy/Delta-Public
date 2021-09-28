<?php
/* 
Trogy.NZ Delta Self Hosted Edition
(C) Marc Anderson | All Rights Reserved 
File Last Updated : 9/28/2021
*/
//error_reporting(E_ERROR | E_PARSE);
file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] update_data.php Has been called. ' . PHP_EOL , FILE_APPEND);
$file = fopen('https://raw.githubusercontent.com/minhealthnz/nz-covid-data/main/locations-of-interest/august-2021/locations-of-interest.csv', 'r');
$data = array();
while (($line = fgetcsv($file)) !== FALSE) {
  if (array(0 => null) !== $line ) {
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

$date=date('m-d-Y');
$LOCFN = $date . "_LOC.json";
 if (file_exists($LOCFN)) {
   //File Exists - Skipping
 }
 else{
   $LOCIN = fopen($date . "_LOC.json", "w") or die("Unable to open file!");
   fwrite($LOCIN, json_encode($data));
 }
unset($data);
 $file = fopen('https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_daily_reports/'. gmdate("m-d-Y", strtotime("-1 days")) . '.csv', 'r');
 if ($file == false) {
   file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [WARNING] New Data File Not Found! - [Stats File Was 404 NOT FOUND] ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL , FILE_APPEND);
   goto LOGFILE;
 }
 $data = array();
 while (($line = fgetcsv($file)) !== FALSE) {
   if (array(0 => null) !== $line ) {
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
     }
   }
 }
LOGFILE:
file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] Running Log File Deletion. ' . PHP_EOL , FILE_APPEND);
$logfile = 'DELTA_' . date("j.n.Y", strtotime("-5 days")) . '.log';
if(file_exists($logfile)){
  if (!unlink($logfile)) {
    file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] ' . $logfile . ' Has Been Deleted [5 Days Old] ' . PHP_EOL , FILE_APPEND);
    }
    else {
    file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] ' . $logfile . ' Could Not Be Deleted ' . PHP_EOL , FILE_APPEND);
    }
  }
  else{
    file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] ' . $logfile . ' Could Not Be Deleted [File Does Not Exist] ' . PHP_EOL , FILE_APPEND);
  }
file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] Data update script has been run.' . PHP_EOL , FILE_APPEND);
