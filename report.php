<?php

ob_implicit_flush();
ini_set('memory_limit','-1');
ini_set('display_errors', 1);
set_time_limit(0);

date_default_timezone_set('America/Sao_Paulo');

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

while(!feof($reportFile)) {

  $commands[] = fgets($reportFile);

}

fclose($reportFile);

$total = sizeof($commands);

$sheet->setCellValue('A1', 'File moodledata');
$sheet->setCellValue('B1', 'File name');
$sheet->setCellValue('C1', 'Component');
$sheet->setCellValue('D1', 'Full URL');
$sheet->setCellValue('E1', 'Domain');
$sheet->setCellValue('F1', 'Course name');
$sheet->setCellValue('G1', 'Course ID');
$sheet->setCellValue('H1', 'Section name');
$sheet->setCellValue('I1', 'Activity ID');
$sheet->setCellValue('J1', 'Time Created');
$sheet->setCellValue('K1', 'Time Modified');

$hashFilesLink = array();
$hashFilesDomain = array();

for ($i=0; $i < $total; $i++) {

  $line = $commands[$i];

  $hash = $file->getFileHash($line);
  $link = $file->getFileURL($line);
  $domain = $file->getDomainURL($line);

    if($hash != NULL){

      $hashs[] = $hash;

      if(array_key_exists($hash, $hashFilesLink)){

        array_push($hashFilesLink[$hash], $link);
        array_push($hashFilesDomain[$hash], $domain);

      } else{

        $hashFilesLink[$hash] = [$link];
        $hashFilesDomain[$hash] = [$domain];

      }

    }

}

$filterHashs = array_unique($hashs);

$fileData = $file->getFileData($filterHashs);

$sheetline = 2;

foreach ($fileData as $value) {

    for ($i=0; $i < count($hashFilesLink[$value["contenthash"]]); $i++) {

      $sheet->setCellValue('A'.$sheetline.'', ''.$value["contenthash"].'');
      $sheet->setCellValue('B'.$sheetline.'', ''.$value["filename"].'');
      $sheet->setCellValue('C'.$sheetline.'', ''.$value["component"].'');
      $sheet->setCellValue('D'.$sheetline.'', ''.$hashFilesLink[$value["contenthash"]][$i].'');
      $sheet->setCellValue('E'.$sheetline.'', ''.$hashFilesDomain[$value["contenthash"]][$i].'');
      $sheet->setCellValue('F'.$sheetline.'', ''.$value["coursename"].'');
      $sheet->setCellValue('G'.$sheetline.'', ''.$value["courseid"].'');
      $sheet->setCellValue('H'.$sheetline.'', ''.$value["sectionname"].'');
      $sheet->setCellValue('I'.$sheetline.'', ''.$value["activityid"].'');
      $sheet->setCellValue('J'.$sheetline.'', ''.date('d/m/Y', $value["timecreated"]).'');
      $sheet->setCellValue('K'.$sheetline.'', ''.date('d/m/Y', $value["timemodified"]).'');
      $sheetline++;

    }

}

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

$spreadsheet->getActiveSheet()->setAutoFilter(
    $spreadsheet->getActiveSheet()
        ->calculateWorksheetDimension()
);

$date = date('Ymd_Ghs');
$filename = "report_".$date;

$writer = new Xlsx($spreadsheet);
$writer->save('export/'.$filename.'.xlsx');

$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
echo '<br>Total Execution Time:</b> '.round($execution_time, 2, PHP_ROUND_HALF_DOWN).' Mins';
echo '<br><br><span class="text-success">Done. Saved into <b>export/</b> folder</span>';
echo '<br><a href="export/'.$filename.'.xlsx">Click here to download report</a>';
// echo '<br><br><a href="">Click here to generate table for Activity name reference</a>';
