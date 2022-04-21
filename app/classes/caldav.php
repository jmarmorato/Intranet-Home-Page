<?php

include $app_path . '/ThirdParty/vendor/autoload.php';
include $app_path . '/ThirdParty/simpleCalDAV/SimpleCalDAVClient.php';
use Sabre\VObject;

class Caldav {

  private static function comparator($a, $b) {
    return $a["start"] <=> $b["start"];
  }

  public static function updateCache($db, $card) {

    $daysForward = $card["days_to_display"];

    $client = new SimpleCalDav\SimpleCalDAVClient();

    //Connect to calendar server
    $client->connect($card["caldav_url"], $card["username"], $card["password"]);

    //Load calendars
    $arrayOfCalendars = $client->findCalendars();

    //Initialize main event array
    $all_init = array();

    //Download all events from all selected calendars
    foreach ($card["calendars"] as $calendar) {
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
          "summary"   => strval($summary),
          "start" => strtotime($start),
          "end"   => strtotime($end),
          "color" => $color,
          "calendar" => $calendar,
          "recurring" => $is_recurring
        ));
      }

    }


    $db->beginTransaction();
    $db->query("DELETE FROM caldav;");
    $sql = "INSERT INTO caldav (`summary`, `start`, `end`, `color`, `calendar`, `recurring`) VALUES (:summary, :start, :end, :color, :calendar, :recurring);";

    foreach ($all_events as $event) {
      $statement = $db->prepare($sql);
      $statement->bindParam(':summary', $event["summary"], PDO::PARAM_STR);
      $statement->bindParam(':start', $event["start"], PDO::PARAM_INT);
      $statement->bindParam(':end', $event["end"], PDO::PARAM_INT);
      $statement->bindParam(':color', $event["color"], PDO::PARAM_STR);
      $statement->bindParam(':calendar', $event["calendar"], PDO::PARAM_STR);
      $statement->bindParam(':recurring', $event["recurring"], PDO::PARAM_INT);
      $statement->execute();
    }

    $db->commit();

  }

  public static function retrieveEvents($db) {
    $sql = "SELECT * FROM caldav";
    $result = $db->query($sql);

    return $result;
  }
}
?>
