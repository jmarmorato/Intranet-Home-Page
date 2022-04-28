<form method="POST" action="install.php">
  <div class="form-group">
    <label for="host">Host</label>
    <input name="host" type="text" class="form-control" id="host" aria-describedby="hostHelp" placeholder="mysql.example.com">
    <small id="hostHelp" class="form-text text-muted">MySQL FQDN or IP address</small>
  </div>
  <div class="form-group">
    <label for="host">User</label>
    <input name="user" type="text" class="form-control" id="host" aria-describedby="hostHelp" placeholder="intranetuser">
    <small id="hostHelp" class="form-text text-muted">MySQL Username.  Must have SELECT, UPDATE, DELETE permissions on provided schema.</small>
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input name="pass" type="text" class="form-control" id="password" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="host">Database</label>
    <input name="schema" type="text" class="form-control" id="host" aria-describedby="hostHelp" placeholder="intranet">
    <small id="hostHelp" class="form-text text-muted">MySQL Schema.  No other applications should share this schema.</small>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
