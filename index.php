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
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <p>The script is going to use the file <b>"report.txt"</b> present on root folder.</p> <br>
          <?php

          if (!file_exists("report.txt")) {

            echo '<span class="text-danger">File <b>report.txt</b> not found.</span>';

          } else{
          ?>

          <button type="submit" name="button" class="btn btn-primary">Generate report</button>

          <?php
          }
          ?>

        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>

</html>
