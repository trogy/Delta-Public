<?php
/* 
Trogy.NZ Delta Self Hosted Edition
(C) Marc Anderson | All Rights Reserved 
File Last Updated : 9/28/2021
*/
?>
var options = {
  series: [{
    name: "Cases",
    data: <?php
    $date = "08/21/2021";
    echo '[';
    do {
      $date = date('m/d/Y', strtotime($date . ' +1 day'));
      $date_formatted = str_replace('/', '-', $date);
      $Filename = "../" . $date_formatted . '_CASE.casecount';

      if(file_exists($Filename)){
      $Count = file_get_contents($Filename);
      echo $Count . ',';
      }
    } while ($date != date('m/d/Y'));
    echo ']';

     ?>
}],
  chart: {
  height: 350,
  type: 'line',
  zoom: {
    enabled: false
  }
},
dataLabels: {
  enabled: true
},
stroke: {
  curve: 'straight'
},
title: {
  text: 'New Zealand COVID-19 Cases by Day',
  align: 'left'
},
grid: {
  row: {
    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
    opacity: 0.5
  },
},
xaxis: {
  categories:  <?php
    $date = "08/21/2021";
    echo '[';
    do {
      $date = date('m/d/Y', strtotime($date . ' +1 day'));
      $date_formatted = str_replace('/', '-', $date);
      echo "'" . $date_formatted . "',";
    } while ($date != date('m/d/Y'));
    echo ']';

     ?>
}
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();
