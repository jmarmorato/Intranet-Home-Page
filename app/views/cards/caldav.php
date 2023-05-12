<?php

$events_array = array();

foreach ($data["events"] as $event) {
  array_push($events_array, $event);
}

$events = $events_array;

function comparator($a, $b) {
  return $a["start"] <=> $b["start"];
}

$now = new Datetime();
$now->setTimestamp(strtotime("now"));

?>

<div class="card <?php if ($data["card_index"] < 3) { echo "top-row-card"; } else { echo "middle-row-card"; } ?> <?php echo card_dm(); ?>">
  <div class="card-body">
    <h5 class="card-title"><?php echo $data["card"]["card_title"]; ?></h5>
    <hr>
    <div class="cal-container news-container">

      <?php

      $daysForward = $data["card"]["days_to_display"];

      unset($all_init);

      usort($events, "comparator");

      $return_events = $events;

      $start = new DateTime();
      $end = new DateTime();

      //Echo the events

      $working_date = $now;
      for ($i = 0; $i < $daysForward; $i++) {

        $output_events = array();

        if ($i == 0) {
          $day_string = "Today";
        } else if ($i == 1) {
          $day_string = "Tomorrow";
        } else {
          $day_string = $working_date->format("l, F j");
        }

        $day_string = $day_string . ":";

        foreach($return_events as $event) {

          $start->setTimestamp($event["start"]);
          $end->setTimestamp($event["end"]);

          if ($end->format("Y") > 2000) {

            $title = $event["summary"] . "\nStart: " . $start->format("Y F d g:i a") . "\nEnd: " . $end->format("Y F d g:i a") . "\nCalendar: " . $event["calendar"] . "\nIs Recurring: " . $event["recurring"];

            if ($start->format("Ymd") == $end->format("Ymd") && $start->format("Ymd") == $working_date->format("Ymd")) {
              //Single day event with set time
              $event_string = "<p title='$title' style='padding:0.25em; border-radius:0.5em; background-color:" . $event["color"] . "'>" . $event["summary"] . " " . $start->format("g:i a") . " - " . $end->format("g:i a") . "</p>";
              array_push($output_events, $event_string);
            } else if ($start->format("Ymd") == $working_date->format("Ymd")) {
              //This is an event that spans two days only, and starts on this day
              $event_string = "<p title='$title' style='padding:0.25em; border-radius:0.5em; background-color:" . $event["color"] . "'>" . $event["summary"] . " " . $start->format("g:i a") . " - " . $end->format("g:i a") . "<i style='float:right' class='bi bi-chevron-double-right event-icon'></i></p>";
              array_push($output_events, $event_string);
            } else if ($end->format("Ymd") == $working_date->format("Ymd")) {
              //This is an event that spans two days only, and ends on this day
              $event_string = "<p title='$title' style='padding:0.25em; border-radius:0.5em; background-color:" . $event["color"] . "'>" . $event["summary"] . " " . $start->format("g:i a") . " - " . $end->format("g:i a") . "<i style='float:right' class='bi bi-chevron-bar-right event-icon'></i></p>";
              array_push($output_events, $event_string);
            } else if ($start->format("Ymd") <= $working_date->format("Ymd") && $end->format("Ymd") >= $working_date->format("Ymd")) {
              //This is an event that spans two days only, and passes through
              $event_string = "<p title='$title' style='padding:0.25em; border-radius:0.5em; background-color:" . $event["color"] . "'>" . $event["summary"] . " " . $start->format("g:i a") . " - " . $end->format("g:i a") . "</p>";
              array_push($output_events, $event_string);
            } else if ($event["summary"] == "Pay Day" && $start->format("Ymd") == $working_date->format("Ymd")) {
              $event_string = "<p title='$title' style='padding:0.25em; border-radius:0.5em; background-color:" . $event["color"] . "'>" . $event["summary"] . " " . $start->format("g:i a") . " - " . $end->format("g:i a") . "</p>";
              array_push($output_events, $event_string);
            }
          } else {
            //The end date is fucked up - Might be 0
            if ($start->format("Ymd") == $working_date->format("Ymd")) {
              $event_string = "<p title='$title' style='padding:0.25em; border-radius:0.5em; background-color:" . $event["color"] . "'>" . $event["summary"] . " " . $start->format("g:i a") . " - " . $end->format("g:i a") . "</p>";
              array_push($output_events, $event_string);
            }
          }

        }


        if (count($output_events) > 0) {
          echo $day_string;
          foreach ($output_events as $output_event) {
            echo $output_event;
          }
          echo "<hr>";
        }


        $working_date->modify("+1 day");
      }
      ?>

    </div>
  </div>
</div>
