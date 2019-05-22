<html>
  <head>
    <title>HTTP to HTTPS URL Checker Moodle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>HTTP to HTTPS URL Checker Moodle</h1>
          <p>This tool helps you to find all the URLs in your moodledata folder.</p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <h2>Generate the report file</h2>
          <ol>
            <li>Open Linux terminal off your server</li>
            <li>Navigate to moodledata/filedir folder</li>
            <li>Run the following bash script</li>
            <span class="text-info">grep -Eorn "(http|https)://[a-zA-Z0-9./?=_-]*" . > report.txt</span>
            <br>
            <li>Move the report.txt file generated to the same folder</li>
          </ol>

        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <p>The script is going to use the file <b>"report.txt"</b> present on root folder.</p>
          <?php

          if (!file_exists("report.txt")) {

            echo '<span class="text-danger">File <b>report.txt</b> not found.</span>';

          } else{

            echo '<span class="text-success">File <b>report.txt</b> found.</span><br>';

          ?>

          <br>
          <button type="submit" name="button" class="btn btn-primary" id="generatereport">Generate report</button>

          <?php
          }
          ?>

        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="loading" style="display: none">
            <br>
            Generating report...
            <br>
            <img src="assets/loading.gif">
          </div>
          <div id="result">

          </div>
        </div>
      </div>
    </div>

    <script src="assets/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script type="text/javascript">
    $("#generatereport").click(function(){
      $.ajax({
        url: "report.php",
        beforeSend: function(){
          $(".loading").show();
        },
        success: function(result){
          $(".loading").hide();
          $("#result").html(result);
        }
      });
    });
    </script>
  </body>

</html>
