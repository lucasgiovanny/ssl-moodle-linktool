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

    $sql = "SELECT f.id, f.contenthash, f.filename,
            	CASE
            		WHEN cr.fullname IS NOT NULL THEN cr.fullname
            		WHEN cr.fullname IS NULL THEN c.contextlevel
            	END as coursename,
            	CASE
            		WHEN cs.name IS NOT NULL THEN cs.name
            		WHEN cs.name IS NULL THEN 'Legacy'
            	END as sectionname,
              CASE
            		WHEN c.contextlevel = 70 THEN c.instanceid
            		ELSE NULL
            	END as activityid
            FROM mdl_files f
            INNER JOIN mdl_context c ON f.contextid = c.id
            LEFT JOIN mdl_course_modules cm ON
            	CASE
            		WHEN c.contextlevel = 70 THEN c.instanceid = cm.id
            		ELSE NULL
            	END
            LEFT JOIN mdl_course cr ON
            	CASE
            		WHEN c.contextlevel = 70 THEN cm.course = cr.id
            		WHEN c.contextlevel = 50 THEN c.instanceid = cr.id
            	ELSE NULL
            	END
            LEFT JOIN mdl_course_sections cs ON
            	CASE
            		WHEN c.contextlevel = 70 THEN cm.section = cs.id
            	ELSE NULL
            	END
            WHERE f.contenthash IN (".$hashsImplode.")";

    $stmt = $this->conn->query($sql)->fetchAll();

    return $stmt;

  }


}
