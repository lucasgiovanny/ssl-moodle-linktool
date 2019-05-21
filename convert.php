<?php

$reportFile = fopen("report.txt", "r") or die("Unable to open report.txt file!");

$comand = array();

while(!feof($reportFile)) {

  $command[] = fgets($reportFile);

}

var_dump($command);

fclose($reportFile);
