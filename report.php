<?php

ini_set('memory_limit','1024M');

$time_start = microtime(true);

require_once('settings.config.php');
require_once('vendor/autoload.php');

use sslLinkTool\Files;

$file = new sslLinkTool\Files($dbconfig);

$reportFile = fopen("report.txt", "r") or die("Unable to open report.txt file!");

$comands = array();

while(!feof($reportFile)) {

  $commands[] = fgets($reportFile);

}

fclose($reportFile);

echo '<table border=1>';
echo '<thead class="thead-light">';
  echo '<tr>';
    echo '<th><b>File moodledata</b></th>';
    echo '<th><b>File</b></th>';
    echo '<th><b>Folder</b></th>';
    echo '<th><b>Full URL</b></th>';
    echo '<th><b>Domain</b></th>';
    echo '<th><b>Linha</b></th>';
    echo '<th><b>Course</b></th>';
    echo '<th><b>Section</b></th>';
    echo '<th><b>Activity</b></th>';
  echo '</tr>';

$total = sizeof($commands);
// $total = "20";

for ($i=0; $i < $total; $i++) {

  $line = $commands[$i];

  $hash = $file->getFileHash($line);

    if($hash != NULL){

      $hashs[] = $hash;

  }

}

$filterHashs = array_unique($hashs);
$fileData = $file->getFileData($filterHashs, $total);

foreach ($fileData as $key => $value) {

    echo '<tr>';

    echo '<td></td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td>'.utf8_encode($fileData[$key]["coursename"]).'</td>';
    echo '<td>'.utf8_encode($fileData[$key]["sectionname"]).'</td>';
    echo '<td>'.utf8_encode($fileData[$key]["activityname"]).'</td>';

    // if($fileData[$key]["contextlevel"] == 70){
    //   $activityName = $file->activityName($fileData[$key]["cmid"]);
    //   echo '<td>'.utf8_encode($activityName).'</td>';
    // } else{
    //   echo '<td></td>';
    // }

    echo '</tr>';

}

echo '</table>';

$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
echo '<b>Total Execution Time:</b> '.round($execution_time, 2, PHP_ROUND_HALF_DOWN).' Mins';
