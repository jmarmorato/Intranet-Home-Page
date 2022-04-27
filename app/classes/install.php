<?php

class Installer {

  static function check_writable_directory($path) {
    /*
    *   See if the writable directory is writable
    */

    return is_writable($path . "/writable/");
  }

  static function build_database($host, $user, $pass, $db) {
    /*
    *   Connect to MySQL and create necessary tables.
    *   Returns true if successful, or the MySQL error if one is raised
    */

    try {
      $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      return $e->getMessage();
    }

    $sql = file_get_contents(APPPATH . "/system/schema.sql");

    $query_result = $conn->query($sql);

    return true;

  }

}


?>
