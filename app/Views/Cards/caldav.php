<?php

include APPPATH.'ThirdParty/vendor/autoload.php';
include APPPATH.'ThirdParty/simpleCalDAV/SimpleCalDAVClient.php';

?>

<div class="card top-row-card">
  <div class="card-body">
    <h5 class="card-title"><?php echo $card_title; ?></h5>
    <hr>
    <div class="news-container">

      <?php

      //I'm sorry to whoever has to figure out how the below disaster
      //works.  I'll try to come back and add comments... If I can
      //figure it out

      $daysForward = $days_to_display;


      use Sabre\VObject;

      $client = new SimpleCalDav\SimpleCalDAVClient();

      function comparator($a, $b) {
        return $a["start"] <=> $b["start"];
      }

      //Connect to calendar server
      $client->connect($caldav_url, $username, $password);

      //Load calendars
      $arrayOfCalendars = $client->findCalendars();

      //Initialize main event array
      $all_init = array();

      //Download all events from all selected calendars
      foreach ($calendars as $calendar) {
        $client->setCalendar($arrayOfCalendars[$calendar]);
        $calendar_events = $client->getEvents();
        $color = $arrayOfCalendars[$calendar]->getRBGcolor();

        foreach($calendar_events as $calendar_event) {

          $toadd = array(
            "event" => $calendar_event,
            "color" => $color,
            "calendar" => $calendar
          );

          array_push($all_init, $toadd);
        }
      }

      //Setup date and array objects
      $today_date = $date = date('Ymd');
      $all_events = array();
      $return_events = array();

      $now = new Datetime();
      $now->setTimestamp(strtotime("now"));

      $recurring_start = new Datetime();
      $recuring_end = new Datetime();

      $recurring_start->modify("-1 day");
      $recuring_end->modify("+$daysForward days");

      foreach ($all_init as $event) {

        $vcalendar = VObject\Reader::read($event["event"]->getData());
        $recurrences = $vcalendar->expand($recurring_start, $recuring_end);
        $color = $event["color"];
        $calendar = $event["calendar"];

        if ($recurrences->VEVENT != null && count($recurrences->VEVENT) > 1) {

          foreach($recurrences->VEVENT as $event) {
            $summary = $event->SUMMARY;
            $start = $event->DTSTART;
            $end = $event->DTEND;
            $is_recurring = "Yes";

            array_push($all_events, array(
              "summary"   => $summary,
              "start" => strtotime($start),
              "end"   => strtotime($end),
              "color" => $color,
              "calendar" => $calendar,
              "recurring" => $is_recurring
            ));
          }
        } else {
          //foreach($vcalendar->VEVENT as $event) {
          $summary = $vcalendar->VEVENT->SUMMARY;
          $start = $vcalendar->VEVENT->DTSTART;
          $end = $vcalendar->VEVENT->DTEND;
          $is_recurring = "No";

          array_push($all_events, array(
            "summary"   => $summary,
            "start" => strtotime($start),
            "end"   => strtotime($end),
            "color" => $color,
            "calendar" => $calendar,
            "recurring" => $is_recurring
          ));
        }

      }

      unset($all_init);

      usort($all_events, "comparator");

      $return_events = $all_events;

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
            $event_string = "<p title='$title' style='padding:0.25em; border-radius:0.5em; background-color:" . $event["color"] . "'>" . $event["summary"] . " " . $start->format("g:i a") . " - " . $end->format("g:i a") . "pass through</p>";
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
