<?php

ob_implicit_flush();
ini_set('memory_limit','1024M');

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
  $sheet->setCellValue('C1', 'Folder');
  $sheet->setCellValue('D1', 'Full URL');
  $sheet->setCellValue('E1', 'Domain');
  $sheet->setCellValue('F1', 'Course');
  $sheet->setCellValue('G1', 'Section');
  $sheet->setCellValue('H1', 'Activity');

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

$sheetline = 2;

foreach ($fileData as $key => $value) {

    $sheet->setCellValue('A'.$sheetline.'', ''.utf8_encode($fileData[$key]["contenthash"]).'');
    $sheet->setCellValue('B'.$sheetline.'', ''.utf8_encode($fileData[$key]["filename"]).'');
    $sheet->setCellValue('C'.$sheetline.'', '');
    $sheet->setCellValue('D'.$sheetline.'', '');
    $sheet->setCellValue('E'.$sheetline.'', '');
    $sheet->setCellValue('F'.$sheetline.'', ''.utf8_encode($fileData[$key]["coursename"]).'');
    $sheet->setCellValue('G'.$sheetline.'', ''.utf8_encode($fileData[$key]["sectionname"]).'');
    $sheet->setCellValue('H'.$sheetline.'', ''.utf8_encode($fileData[$key]["activityname"]).'');

    $sheetline++;

}

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

$spreadsheet->getActiveSheet()->setAutoFilter(
    $spreadsheet->getActiveSheet()
        ->calculateWorksheetDimension()
);

$writer = new Xlsx($spreadsheet);
$writer->save('export/report.xlsx');

$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
echo '<br>Total Execution Time:</b> '.round($execution_time, 2, PHP_ROUND_HALF_DOWN).' Mins';
echo '<br><br><span class="text-success">Done. Saved into <b>export/</b> folder</span>';
echo '<br><a href="export/report.xlsx">Click here to download report</a>';
