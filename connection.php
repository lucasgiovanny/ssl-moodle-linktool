<?php
  $jsonString = file_get_contents('db.json');
  $data = json_decode($jsonString, true);
