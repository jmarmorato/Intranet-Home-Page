<?php

$installed = false;

require_once "../app/init.php";

$writable_is_writable = Installer::check_writable_directory(APPPATH);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  /*
  *   The user has submitted the install form.  Test the submitted database
  *   configuration.  Do a basic check to ensure required data is present
  */

  echo view("main/header", array(
    "config" => array(
      "page_title" => "Installer"
    )
  ));

  if (!isset($_POST["host"]) || !isset($_POST["user"]) || !isset($_POST["pass"]) ||!isset($_POST["schema"])) {

    echo view("install/configuration", array(
      "is_writable" => $writable_is_writable,
      "error" => "form"
    ));

  } else {
    $result = Installer::build_database($_POST["host"], $_POST["user"], $_POST["pass"], $_POST["schema"], APPPATH);

    if ($result !== true) {

      echo view("install/configuration", array(
        "is_writable" => $writable_is_writable,
        "error" => $result
      ));
    } else {
      /*
      *   The database connection was successful.  Save the connection details
      *   to a file and redirect to the home page
      */

      echo view("install/configuration", array(
        "success" => "value"
      ));
    }

  }

} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  echo view("main/header", array(
    "config" => array(
      "page_title" => "Installer"
    )
  ));

  echo view("install/configuration", array(
    "is_writable" => $writable_is_writable
  ));
}

?>
