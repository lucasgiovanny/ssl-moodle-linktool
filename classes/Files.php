<?php

namespace sslLinkTool;

use Doctrine\DBAL\DriverManager;
use Medoo\Medoo;

/**
 * @author Lucas Giovanny <lucasgiovanny@gmail.com>
 */

class Files
{

  private $dbconfig = array();
  private $conn;

  function __construct($dbconfig){

    $this->dbconfig = $dbconfig;

    $database = new Medoo($this->dbconfig);

    $this->conn = $database;

  }

  function getFileURL($line){

    if(strpos($line, "Binary") === false && $line != ""){

      $explodeFirst = explode(":", $line);

      $hash = $explodeFirst[2] . ':' . $explodeFirst[3];

    } else {

      $hash = NULL;

    }

    return $hash;

  }

  function getDomainURL($line){

    if(strpos($line, "Binary") === false && $line != ""){

      $explodeFirst = explode(":", $line);
      $explodeSecond = explode("/", $explodeFirst[3]);

      $hash = $explodeFirst[2] . '://' . $explodeSecond[2];

    } else {

      $hash = NULL;

    }

    return $hash;

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

    $sql = "SELECT f.id, f.contenthash, f.component, f.filename, f.timecreated, f.timemodified, cr.fullname AS coursename, cr.id AS courseid, cs.name AS sectionname, cm.instance AS activityid
            FROM mdl_files f
            INNER JOIN mdl_context ct ON f.contextid = ct.id
            INNER JOIN
            	mdl_course_modules cm
            ON ct.instanceid = cm.id
            INNER JOIN mdl_course cr ON cm.course = cr.id
            left JOIN mdl_course_sections cs ON cm.section = cs.id
            WHERE ct.contextlevel = 70 AND f.contenthash IN (".$hashsImplode.")
            UNION
            SELECT f.id, f.contenthash, f.contextid, f.filename, f.timecreated, f.timemodified, cr.fullname AS coursename, cr.id AS courseid, 'Legacy' AS sectionname, null AS activityid
            FROM mdl_files f
            INNER JOIN mdl_context ct ON f.contextid = ct.id
            INNER JOIN mdl_course cr ON ct.instanceid = cr.id
            WHERE ct.contextlevel = 50 AND f.contenthash IN (".$hashsImplode.")";

    $stmt = $this->conn->query($sql)->fetchAll();

    return $stmt;

  }

}
