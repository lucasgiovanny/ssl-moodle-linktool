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

    $sqlActivity = "SELECT CASE ";
    foreach($allModules as $key => $module){

      $sqlActivity .= "WHEN m{$key}.name IS NOT NULL THEN m{$key}.name ";

    }
    $sqlActivity .= "ELSE NULL END AS activityname ";
    $sqlActivity .= "FROM mdl_course_modules AS cm1 ";
    $sqlActivity .= "INNER JOIN mdl_context AS ctx ON ctx.contextlevel = 70 AND ctx.instanceid = cm1.id ";
    $sqlActivity .= "INNER JOIN mdl_modules AS mdl ON cm1.module = mdl.id ";
    foreach($allModules as $key => $module){

      $sqlActivity .= "LEFT JOIN mdl_{$module['name']} AS m{$key} ON mdl.name = '{$module['name']}' AND cm1.instance = m{$key}.id ";

    }

    $sqlActivity .= "WHERE cm1.id = cm.id LIMIT 1";

    return $sqlActivity;
  }

  public function activityName($cmid){

    $sql = $this->sqlActivity;
    $sql .= "WHERE cm.id = '{$cmid}'";
    $stmt = $this->conn->fetchAll($sql);

    return $stmt[0]['activityname'];

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

    $sql = "SELECT f.id, f.contenthash, f.component, f.filename, c.contextlevel, c.instanceid,
            CASE
            	WHEN c.contextlevel = 10 THEN 'System File'
            	WHEN c.contextlevel = 30 THEN 'User File'
            	WHEN c.contextlevel = 40 THEN 'Course Category File'
            	WHEN c.contextlevel = 50 THEN 'Course File'
            	WHEN c.contextlevel = 70 THEN 'Module File'
            	WHEN c.contextlevel = 80 THEN 'Block File'
            END AS FileType,
            un.coursename, un.sectionname, un.activityname
            FROM mdl_files f LEFT JOIN mdl_context c ON f.contextid = c.id
            LEFT JOIN (
            	SELECT cr.fullname as coursename, 50 as contextlevel, NULL as sectionname, NULL as activityname FROM mdl_course cr UNION
            	SELECT cr.fullname as coursename, 70 as contextlevel, s.name as sectionname, (".$sqlActivity.") as activityname FROM mdl_course cr INNER JOIN mdl_course_modules cm ON cm.course = cr.id INNER JOIN mdl_course_sections s ON cm.section = s.id
            ) un ON un.contextlevel = c.contextlevel WHERE f.contenthash IN (".$hashsImplode.")";

    $stmt = $this->conn->fetchAll($sql);

    return $stmt;

  }


}
