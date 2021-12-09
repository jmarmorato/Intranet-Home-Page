<style>
.active-alerts-card {
  max-height: 20em;
  overflow-y: scroll;
  padding-bottom: 3em;
}

.header-row {
  margin-top:1em;
  padding-left: .8em;
}

.alerts-card {

}
</style>

<div class="container-fluid">

  <!--<div class="row header-row"> <h2>Monitored Host Health</h2> </div>-->

  <div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Hosts Up</h5>
          <hr>
          <?php foreach($okay as $device): ?>
            <span class="badge badge-<?php echo $device["badge"]; ?>"><?php echo $device["host"]->hostname; ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
      <div class="card alerts-card">
        <div class="card-body active-alerts-card">
          <h5 class="card-title">Active Alerts</h5>
          <hr>
          <?php  if ($alerts["count"] == 0): ?>
            <p class='card-text'><i>There are currently no reported alerts</i></p>
          <?php else: ?>
            <?php foreach($alerts["alerts"] as $alert): ?>

              <div class="card">
                <div class="card-header"><?php echo $alert["alert"]->hostname; ?>
                  <?php if($alert["rule"]->severity == "critical"): ?>
                    <div style="float: right;"><span class="badge badge-danger"><?php echo ucfirst($alert["alert"]->severity); ?></span></div>
                  <?php endif; ?>
                </div>
                <div class="card-body">
                  <p class='card-text'>Triggering Rule: <?php echo ucfirst($alert["rule"]->name); ?></p>
                </div>

              </div>

            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>


  </div>

  <div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Hosts Down</h5>
          <hr>

          <?php foreach($critical as $device): ?>
            <span class="badge badge-danger"><?php echo $device->hostname; ?></span>
          <?php endforeach; ?>

          <!--<hr>
          <a class="card-link">View Calendar</a> -->
        </div>
      </div>
    </div>
  </div>

</div>

</body>
