<body>
  <h2 class="installer-header">Intranet Home Page Installer</h2>


  <div class="card installer-card">
    <div class="card-body">
      <?php if (!isset($data["success"])): ?>
        <h5 class="card-title">MySQL</h5>
        <hr>

        <p>Intranet Home Page needs a MySQL database to store configuration and cache external content.  Enter the connection details below.  The user entered should only have access to the schema assigned to this application.</p>

        <hr>

        <h6>Pre-install checks</h6>

        <?php if ($data["is_writable"]): ?>
          <p>Writable directory is writable! (That's a good thing)</p>
        <?php else: ?>
          <p>Writable directory is not writable! (That's a bad thing)</p>
        <?php endif; ?>

        <hr>
      <?php endif; ?>


      <?php if (isset($data["is_writable"]) && $data["is_writable"] && !isset($data["success"])): ?>

        <?php if (isset($data["error"]) && $data["error"] == "form"): ?>
          <p style="color:red">Fill out all fields to continue</p>
        <?php elseif (isset($data["error"])): ?>
          <p style="color:red"><?php echo $data["error"]; ?></p>
        <?php endif; ?>

        <?php echo view("install/form"); ?>

      <?php elseif (isset($data["success"])): ?>
        <p>Installation completed successfully!</p>

        <h6>Next Steps:</h6>

        <ol>
          <li>Delete the install.php file from the public directory</li>
          <li>Setup a cron job to run the cron.php file every 5 or 10 minutes (refer to the README)</li>
          <li>Configure some widgets in the config.json</li>
          <li>Consider enabling MySQL query cache to make requests even faster</li>
        </ol>
      <?php else: ?>
        <p>Fix above errors before proceeding</p>
      <?php endif; ?>


    </div>
  </div>


</body>
