<?php

namespace sslLinkTool;

use Doctrine\DBAL\DriverManager;

/**
 * @author Lucas Giovanny <lucasgiovanny@gmail.com>
 */

class Files
{

  private $dbconfig = array();
  private $conn;
  private $sqlActivity;

  function __construct($dbconfig){

    $this->dbconfig = $dbconfig;

    $config = new \Doctrine\DBAL\Configuration();
    $conn = \Doctrine\DBAL\DriverManager::getConnection($this->dbconfig, $config);

    $this->conn = $conn;

    $this->sqlActivity = $this->sqlMount();

  }

  private function allModules() {

    $sql = "SELECT name FROM mdl_modules";
    $stmt = $this->conn->fetchAll($sql);

    return $stmt;

  }

  private function sqlMount(){

    $allModules = $this->allModules();

    $sqlActivity = "SELECT cr1.id as courseid, cr1.fullname as coursename, s1.id as sectionid, s1.name as sectionname, cm1.id as activityid, CASE ";

    foreach($allModules as $key => $module){

      $sqlActivity .= "WHEN m{$key}.name IS NOT NULL THEN m{$key}.name ";

    }

    $sqlActivity .= "ELSE NULL END AS activityname ";
    $sqlActivity .= "FROM mdl_course_modules AS cm1 ";
    $sqlActivity .= "INNER JOIN mdl_course AS cr1 ON cr1.id = cm1.course ";
    $sqlActivity .= "INNER JOIN mdl_course_sections AS s1 ON cm1.section = s1.id ";
    $sqlActivity .= "INNER JOIN mdl_context AS ctx ON ctx.contextlevel = 70 AND ctx.instanceid = cm1.id ";
    $sqlActivity .= "INNER JOIN mdl_modules AS mdl ON cm1.module = mdl.id ";

    foreach($allModules as $key => $module){

      $sqlActivity .= "LEFT JOIN mdl_{$module['name']} AS m{$key} ON mdl.name = '{$module['name']}' AND cm1.instance = m{$key}.id ";

    }

    return $sqlActivity;
  }

  function getFileHash($line){

    if(strpos($line, "Binary") === false && $line != ""){

      $explodeFirst = explode(":", $line);
      $explodeSecond = explode("/", $explodeFirst[0]);

      $hash = $explodeSecond[3];

    } else {

      $hash = NULL;

    }

      return $hash;

  }

  function getFileData($hashs){

    $hashsImplode = sprintf("'%s'", implode("', '",$hashs));

    $sqlActivity = $this->sqlActivity;

    $sql = "SELECT f.id, f.contenthash, f.filename, un.coursename, un.sectionname, un.activityname
            FROM mdl_files f
            INNER JOIN mdl_context c ON f.contextid = c.id
            LEFT JOIN (".$sqlActivity.") un ON CASE WHEN c.contextlevel = 70 THEN un.activityid WHEN c.contextlevel = 50 THEN un.courseid ELSE NULL END = c.instanceid
            WHERE f.contenthash IN (".$hashsImplode.")";

          // echo $sql;

    $stmt = $this->conn->fetchAll($sql);

    return $stmt;

  }


}
