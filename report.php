<?php

ob_implicit_flush();
ini_set('memory_limit','-1');
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Sao_Paulo');
set_time_limit(0);

$time_start = microtime(true);
require_once('settings.config.php');
require_once('vendor/autoload.php');

use sslLinkTool\Files;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$file = new sslLinkTool\Files($dbconfig);
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$reportFile = fopen("report.txt", "r") or die("Unable to open report.txt file!");

$comands = array();

while(!feof($reportFile)) {

  $commands[] = fgets($reportFile);

}

fclose($reportFile);

  $sheet->setCellValue('A1', 'File moodledata');
  $sheet->setCellValue('B1', 'File');
  $sheet->setCellValue('C1', 'Full URL');
  $sheet->setCellValue('D1', 'Course');
  $sheet->setCellValue('E1', 'Section');
  $sheet->setCellValue('F1', 'Activity');

$total = sizeof($commands);

for ($i=0; $i < $total; $i++) {

  $line = $commands[$i];

  $hash = $file->getFileHash($line);
  $link = $file->getFileURL($line);

    if($hash != NULL){

      $hashs[] = $hash;

    }

    if($hash != NULL){

      $links[] = $link;

    }

}

$filterHashs = array_unique($hashs);
$filterLinks = array_unique($links);

$fileData = $file->getFileData($filterHashs, $total);

$sheetline = 2;

foreach ($fileData as $key => $value) {

    if(!empty($filterLinks[$key])){

      $sheet->setCellValue('A'.$sheetline.'', ''.utf8_encode($fileData[$key]["contenthash"]).'');
      $sheet->setCellValue('B'.$sheetline.'', ''.utf8_encode($fileData[$key]["filename"]).'');
      $sheet->setCellValue('C'.$sheetline.'', ''.$filterLinks[$key].'');
      $sheet->setCellValue('D'.$sheetline.'', ''.utf8_encode($fileData[$key]["coursename"]).'');
      $sheet->setCellValue('E'.$sheetline.'', ''.utf8_encode($fileData[$key]["sectionname"]).'');
      $sheet->setCellValue('F'.$sheetline.'', ''.utf8_encode($fileData[$key]["activityname"]).'');
      $sheetline++;

    }


}

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

$spreadsheet->getActiveSheet()->setAutoFilter(
    $spreadsheet->getActiveSheet()
        ->calculateWorksheetDimension()
);

$date = date('YmdGh');
$filename = "report_".$date;

$writer = new Xlsx($spreadsheet);
$writer->save('export/'.$filename.'.xlsx');

$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
echo '<br>Total Execution Time:</b> '.round($execution_time, 2, PHP_ROUND_HALF_DOWN).' Mins';
echo '<br><br><span class="text-success">Done. Saved into <b>export/</b> folder</span>';
echo '<br><a href="export/'.$filename.'.xlsx">Click here to download report</a>';
