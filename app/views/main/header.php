<html>

<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" integrity="sha384-tKLJeE1ALTUwtXlaGjJYM3sejfssWdAaWR2s97axw4xkiAdMzQjtOjgcyw0Y50KU" crossorigin="anonymous">
  <script src="/typeahead.js"></script>
  <link rel="stylesheet" href="/style.css">

  <?php if(DARKMODE): ?>
    <link rel="stylesheet" href="/darkmode_style.css">
  <?php endif; ?>

  <?php if(isset($data["config"]["background_image"]) && $data["config"]["background_image"] != ""): ?>
    <style type="text/css">
      body{
        background-image: url('<?php echo $data["config"]["background_image"]; ?>');
        background-repeat: <?php echo $data["config"]["background_repeat"]; ?>;
        background-size: <?php echo $data["config"]["background_size"]; ?>;
      }
    </style>
  <?php endif; ?>

  <title><?php echo $data["config"]["page_title"]; ?></title>
</head>
