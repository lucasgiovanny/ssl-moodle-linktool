<?php
  require_once("connection.php");
?>
<html>
  <head>
    <title>Ferramenta de URL - Moodledata</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>Ferramenta de URL - Moodledata</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2">
          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="index.php">Início</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="config.php">Config</a>
            </li>
          </ul>
        </div>
        <div class="col-md-10">
          <p>Faça o upload do arquivo <b>.txt</b> para dar início a geração do relatório</p>
          <form action="" method="post">
            <div class="form-group">
              <label for="file">Arquivo</label>
              <input class="form-control" type="file" name="file" value="">
            </div>
            <button type="submit" name="button" class="btn btn-primary">Gerar relatório</button>
          </form>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>

</html>
