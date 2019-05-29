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
$sheet->setCellValue('B1', 'File');
$sheet->setCellValue('C1', 'Full URL');
$sheet->setCellValue('D1', 'Domain');
$sheet->setCellValue('E1', 'Course name');
$sheet->setCellValue('F1', 'Section name');
$sheet->setCellValue('G1', 'Activity ID');

for ($i=0; $i < $total; $i++) {

  $line = $commands[$i];

  $hash = $file->getFileHash($line);
  $link = $file->getFileURL($line);
  $domain = $file->getDomainURL($line);

    if($hash != NULL){

      $hashs[] = $hash;
      $hashFilesLink[$hash] = array('url' => $link, 'domain' => $domain);

    }

}

$filterHashs = array_unique($hashs);

$fileData = $file->getFileData($filterHashs, $total);

$sheetline = 2;

foreach ($fileData as $key => $value) {

    $sheet->setCellValue('A'.$sheetline.'', ''.$fileData[$key]["contenthash"].'');
    $sheet->setCellValue('B'.$sheetline.'', ''.$fileData[$key]["filename"].'');
    $sheet->setCellValue('C'.$sheetline.'', ''.$hashFilesLink[$fileData[$key]["contenthash"]]["url"].'');
    $sheet->setCellValue('D'.$sheetline.'', ''.$hashFilesLink[$fileData[$key]["contenthash"]]["domain"].'');
    $sheet->setCellValue('E'.$sheetline.'', ''.$fileData[$key]["coursename"].'');
    $sheet->setCellValue('F'.$sheetline.'', ''.$fileData[$key]["sectionname"].'');
    $sheet->setCellValue('G'.$sheetline.'', ''.$fileData[$key]["activityid"].'');
    $sheetline++;


}

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

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
