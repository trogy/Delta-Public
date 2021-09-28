<?php
/* 
Trogy.NZ Delta Self Hosted Edition
(C) Marc Anderson | All Rights Reserved 
File Last Updated : 9/28/2021
*/
error_reporting(E_ERROR | E_PARSE);
//Get File
file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] csv.php Has been called. ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL , FILE_APPEND);
$file = fopen('https://raw.githubusercontent.com/minhealthnz/nz-covid-data/main/locations-of-interest/august-2021/locations-of-interest.csv', 'r');
$csv = array();
while (($line = fgetcsv($file)) !== FALSE) {
  if (array(0 => null) !== $line ) {
      $data[] = $line;
  }
}
fclose($file);
//Sort By Newest First
usort($data, function($a, $b) {
    return strtotime(str_replace('/', '-', $a[4])) <=> strtotime(str_replace('/', '-', $b[4]));
});
$data = array_reverse($data);
$date=gmdate('m-d-Y');
//Save File To Server
$LOCFN = $date . "_LOC.json";
//Check File Exists
 if (file_exists($LOCFN)) {
   //Check the hash for new updates
   $hash = file_get_contents('CurrentHash.txt');
   $hash2 = hash('md5', json_encode($data));
   //Hash Identical [Do Nothing & Log It]
   if($hash === $hash2){
        file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] Hash Identical [No New Updates] ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL , FILE_APPEND);
      }
    //Hash Changed [Overwrite File]
    else{
      $LOCIN = fopen($date . "_LOC.json", "w") or die('File Not Found.');
      fwrite($LOCIN, json_encode($data));
      fclose($LOCIN);
      $LOC_HASH =  hash_file('md5', $date . "_LOC.json");
      $hash = fopen("CurrentHash.txt", "w") or die("Unable to open file!");
      fwrite($hash, $LOC_HASH);
      fclose($hash);
      file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] Hash Changed [New Updates Downloaded] ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL , FILE_APPEND);
    }
 }
 //If file not found make it.
 else{
   $LOCIN = fopen($date . "_LOC.json", "w") or die('File Not Found.');
   fwrite($LOCIN, json_encode($data));
   fclose($LOCIN);
   $LOC_HASH =  hash_file('md5', $date . "_LOC.json");
   echo $LOC_HASH;
   $hash = fopen("CurrentHash.txt", "w") or die("Unable to open file!");
   fwrite($hash, $LOC_HASH);
   fclose($hash);
   file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] Hello New Day! New Locations.JSON & Hash file made' . $_SERVER['REMOTE_ADDR'] . PHP_EOL , FILE_APPEND);
 }
//Essential Functions
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
$nzdateobj = new DateTime("now", new DateTimeZone('Pacific/Auckland'));
$nzdate = $nzdateobj->format('Y-m-d H:i:s');

function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours and %i minutes');
}



//Loop Through and echo relevent data.
foreach ($data as $i => $v) {
  //Replace / with - in dates
  if(strstr($data[$i]['9'], '/')){
  $data[$i]['9'] = str_replace('/', '-', $data[$i]['9']);
  }
  $dif = strtotime($nzdate) - strtotime($data[$i]['9']);
  if(empty($data[$i]['9'])){
    $data[$i]['9'] = "No Date Provided";
  }
  //Create a new line in array with start of exposure time.
  if(strstr($data[$i]['4'], '/')){
  $data[$i]['11'] = str_replace('/', '-', $data[$i]['4']);
  }
  $dif2 = strtotime($nzdate) - strtotime($data[$i]['11']);
  if(empty($data[$i]['11'])){
    $data[$i]['11'] = "No Date Provided";
  }
  //Setup colors and classes to be used by JQuery
  if($data[$i]['1'] == 'Event'){

  }
  else{
  if ($data[$i]['3'] == 'Wellington'){
    $bgcolor = "#4F6464";
    $class = "Wellington";
  }
  elseif ($data[$i]['3'] == 'Auckland'){
    $bgcolor = "#31363B";
    $class = "Auckland";
  }
  elseif ($data[$i]['3'] == 'Coromandel'){
    $bgcolor = "#585D67";
    $class = "Coromandel";
  }
  else{
    $bgcolor = "black";
    $class = "Other";
  }

  if($dif < 86400){
    $bgcolor = "#8b0000";
    $loc_time = " New";
  }
  else{
    $loc_time = " Old";
  }

//If location has no coordinates do not add them to Div
  if($data[$i]['7'] == 'NA'){
        echo '<div class="' . $class . $loc_time . '" style="width: 90%; background-color: ' . $bgcolor . '; border-radius:10px; padding: 10px; margin: auto; margin-top 5px; margin-bottom: 5px;">';
    }
    else{
      echo '<div class="' . $class . $loc_time . '" style="width: 90%; background-color: ' . $bgcolor . '; border-radius:10px; padding: 10px; margin: auto; margin-top 5px; margin-bottom: 5px; cursor: pointer;" onclick="ZoomMap(' . $data[$i]['7'] . ',' . $data[$i]['8'] . ')">';
    }
        echo 'Date Added: '  . $data[$i]['9'] . "<br>";
        echo $data[$i]['1'] . "<br>";
        echo $data[$i]['2'] . "<br>";
        if($data[$i]['4'] != 'NA'){
        echo $data[$i]['4'] . " - " . $data[$i]['5'] ;
        }
        echo '<br>Time Since Exposure: ' . secondsToTime($dif2);
      //  echo '<br>Time Since Added: ' . secondsToTime($dif);
        echo '</div>';
      }
}
/*echo '<h3 style="text-align:center;"> Raw Data Below </h3>';
echo '<pre>' . var_export($data, true) . '</pre>';*/
  file_put_contents('DELTA_'.date("j.n.Y").'.log', gmdate("Y-m-d\TH:i:s\Z") . ' | DELTA LOG: [INFORMATION] Locations Of Interest Generated! ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL , FILE_APPEND);
