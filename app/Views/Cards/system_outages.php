<div class="card">
  <div class="card-body">
    <h5 class="card-title">System Outages</h5>
    <hr>
    <!--<p class="card-text"><i>Feature Not Yet Implimented<i></p>-->

    <?php if ($error): ?>
      <p><i>An error occured polling LibreNMS</i></p>
    <?php else: ?>

      <?php if(count($crit) == 0): ?>
        <p>All systems are currently reported operational</p>
      <?php endif; ?>

      <?php foreach($crit as $device): ?>
        <span class="badge badge-danger"><?php echo $device->hostname; ?></span>
      <?php endforeach; ?>

    <?php endif; ?>
    <hr>
    <a class="card-link" href="/NetworkOverview">Network Overview</a>
  </div>
</div>
