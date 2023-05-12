<?php require_once "../app/init.php"; ?>

<body class="<?php echo don(); ?>">


    <?php $alert_i = 0; ?>

    <?php foreach ($data["alerts"] as $alert): ?>
	<?php $alert_i++; ?>

        <!-- The Modal -->
        <div class="modal <?php echo dol(); ?>" id="alertModal<?php echo $alert_i; ?>">
    	<div class="modal-dialog">
    	    <div class="modal-content">

    		<!-- Modal Header -->
    		<div class="modal-header">
    		    <h5 class="modal-title"><?php echo $alert["alert"]; ?></h5>
    		    <button type="button" class="close" data-dismiss="modal">&times;</button>
    		</div>

    		<!-- Modal body -->
    		<div class="modal-body">
    		    <?php echo preg_replace ("/\n\n/", "<br><br>", $alert["description"]);?>
		    <hr class="hr">
		    <?php echo $alert["instruction"]; ?>
    		</div>

    		<!-- Modal footer -->
    		<div class="modal-footer">
    		    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    		</div>

    	    </div>
    	</div>
        </div>

    <?php endforeach; ?>

    <?php
    echo view("/main/nav", array(
	"config" => $data["config"]
    ));
    ?>

    <!--Insert weather alert banner if enabled-->
    <?php if ($data["config"]["weather_alerts"]["enabled"]): ?>
	<?php
	echo view("main/weather_alerts", array(
	    "alerts" => $data["alerts"]
	));
	?>
    <?php endif; ?>

    <?php if (isset($data["config"]["icon_bar"])): ?>
	<?php echo view("main/icon_row"); ?>
    <?php endif; ?>

    <div class="container-fluid main-container">
	
	<?php $card_index = 0; ?>

	<?php foreach ($data["cards"] as $card): ?>

	    <?php if ($card["type"] == "quick_links"): ?>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
		    <!--Insert Quick Links Card-->
		    <?php
		    echo view("cards/quick_links", array(
			"card" => $card,
			"card_index" => $card_index
		    ));
		    ?>
		</div>

	    <?php elseif ($card["type"] == "us_weather"): ?>

		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
		    <!--Insert Current Conditions Card-->
		    <?php
		    echo view("cards/us_weather", array(
			"forecast" => $card["forecast"],
			"current" => $card["current"],
			"card_index" => $card_index
		    ));
		    ?>
		</div>

	    <?php elseif ($card["type"] == "piwigo"): ?>

		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
		    <!--Insert Current Conditions Card-->
		    <?php
		    echo view("cards/piwigo", array(
			"config" => $data["config"],
			"image" => $card["random_image"],
			"card" => $card,
			"card_index" => $card_index
		    ));
		    ?>
		</div>

	    <?php elseif ($card["type"] == "local_random_image"): ?>

		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
		    <!--Insert Current Conditions Card-->
		    <?php
		    echo view("cards/local_random_image", array(
			"config" => $data["config"],
			"image" => $card["random_image"],
			"card" => $card,
			"card_index" => $card_index
		    ));
		    ?>
		</div>

	    <?php elseif ($card["type"] == "image"): ?>

		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
		    <!--Insert Current Conditions Card-->
		    <?php
		    echo view("cards/image", array(
			"config" => $data["config"],
			"card" => $card,
			"card_index" => $card_index
		    ));
		    ?>
		</div>

	    <?php elseif ($card["type"] == "rss"): ?>

		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
		    <!--Insert RSS Card-->
		    <?php
		    echo view("cards/rss", array(
			"config" => $data["config"],
			"card" => $card,
			"articles" => $card["articles"],
			"card_index" => $card_index
		    ));
		    ?>
		</div>

	    <?php elseif ($card["type"] == "caldav"): ?>

		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
		    <!--Insert Current Conditions Card-->
		    <?php
		    echo view("cards/caldav", array(
			"config" => $data["config"],
			"card" => $card,
			"events" => $card["events"],
			"card_index" => $card_index
		    ));
		    ?>
		</div>

	    <?php elseif ($card["type"] == "iframe"): ?>

		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
		    <!--Insert iFrame Card-->
		    <?php
		    echo view("cards/iframe", array(
			"config" => $data["config"],
			"card" => $card,
			"card_index" => $card_index
		    ));
		    ?>
		</div>

	    <?php endif; ?>
	
	    <?php $card_index++; ?>

	<?php endforeach; ?>

    </div>

</body>
<script src="/script.js"></script>

</html>