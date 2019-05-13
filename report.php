<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>HTTP to HTTPS URL Checker Moodle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <h1>HTTP to HTTPS URL Checker Moodle - Report</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
<?php

  $jsonString = file_get_contents('db.json');
  $data = json_decode($jsonString, true);

  $servername = $data[0]['servername'];
  $username = $data[0]['username'];
  $password = $data[0]['password'];
  $dbname = $data[0]['database'];

  try {

      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      echo '<table class="js-dynamitable table table-striped table-bordered table-hover table-sm">';
        echo '<thead class="thead-light">';
          echo '<tr>';
            echo '<th><b>File</b></th>';
            echo '<th><b>Folder</b></th>';
            echo '<th><b>Full URL</b></th>';
            echo '<th><b>Domain</b></th>';
            echo '<th><b>Course</b></th>';
            echo '<th><b>Section</b></th>';
            echo '<th><b>Activity</b></th>';
          echo '</tr>';
        echo '</thead>';

      $myfile = fopen("report.txt", "r") or die("Unable to open file!");


      while(!feof($myfile)) {

        $line = fgets($myfile);

        if(strpos($line, "Binary") === false && $line != ""){

          $explode = explode(":", $line);

          $explode2 = explode("/", $explode[0]);

          $explode3 = explode("/", $explode[3]);

          echo '<tr>';
            echo '<td>' . $explode2[3] . '</td>';
            echo '<td>moodledata/' . $explode2[1] . '/' . $explode2[2] . '/</td>';
            echo '<td>' . $explode[2] . $explode[3] . "</td>";
            echo '<td>' . $explode3[2] . "</td>";
            echo '<td></td>';
            echo '<td></td>';
            echo '<td></td>';
          echo '</tr>';

        }

      }
      fclose($myfile);

      echo '</table>';

  }
  catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
  $conn = null;
?>
    </div>
  </div>
</div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
