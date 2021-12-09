<?php

if (!function_exists('str_contains')) {
  function str_contains(string $haystack, string $needle): bool
  {
    return '' === $needle || false !== strpos($haystack, $needle);
  }
}

?>

<?php foreach($alerts as $alert): ?>
  <?php if(isset($alert->id)): ?>
    <?php if($alert->title != "There are no active watches, warnings or advisories"): ?>
      <?php if(str_contains(strtolower($alert->title), "hurricane") || str_contains(strtolower($alert->title), "tornado") || str_contains(strtolower($alert->title), "severe")): ?>
        <div class="weather-alert alert alert-danger" style="margin-top: 1em; max-width: 88%; margin-left:auto; margin-right: auto;" role="alert">
          <a href="<?php echo $alert->id ?>"><?php echo $alert->title ?></a>
        </div>
      <?php else: ?>
        <div class="weather-alert alert alert-warning" style="margin-top: 1em; max-width: 88%; margin-left:auto; margin-right: auto;" role="alert">
          <a href="<?php echo $alert->id ?>"><?php echo $alert->title ?></a>
        </div>
      <?php endif; ?>
    <?php elseif($_SERVER['CI_ENVIRONMENT'] == "development"): ?>
      <div class="weather-alert alert alert-warning" style="margin-top: 1em; max-width: 88%; margin-left:auto; margin-right: auto;" role="alert">
        <?php echo "Application environment set to " . $_SERVER['CI_ENVIRONMENT'] . ". Client address: "; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
<?php endforeach; ?>
