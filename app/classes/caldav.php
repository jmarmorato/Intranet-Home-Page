<?php

include $app_path . '/ThirdParty/vendor/autoload.php';
include $app_path . '/ThirdParty/simpleCalDAV/SimpleCalDAVClient.php';

use Sabre\VObject;

class Caldav {

    private static function comparator($a, $b) {
	return $a["start"] <=> $b["start"];
    }

    /*
     * updateCache handles getting events from the CalDav server and caching
     * those events in the database.
     */
    
    public static function updateCache($db, $card) {

	/*
	 *   How many days forward should the page display?
	 *   Pull this value from the config file
	 */
	$daysForward = $card["days_to_display"];

	/*
	 *   Create CalDAV client object
	 */
	$client = new SimpleCalDav\SimpleCalDAVClient();

	/*
	 *   Use the previously created CalDAV client object to connect to calendar
	 *   server with the given credentials
	 */
	$client->connect($card["caldav_url"], $card["username"], $card["password"]);

	/*
	 *   Load a list of calendars visible to the user
	 */
	$arrayOfCalendars = $client->findCalendars();

	/*
	 *   This array will hold all of the events pulled from CalDAV
	 */
	$all_init = array();

	/*
	 *   $card["calendars"] contains the list of calendars to display from the
	 *   config file.  For each calendar, select calendar, get events, and
	 *   get calendar color.  Then add events to the events array.
	 */
	foreach ($card["calendars"] as $calendar) {
	    $client->setCalendar($arrayOfCalendars[$calendar]);
	    $calendar_events = $client->getEvents();
	    $color = $arrayOfCalendars[$calendar]->getRBGcolor();

	    foreach ($calendar_events as $calendar_event) {

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

	
	/*
	 * We need to set a date a long time in the past, because if we don't,
	 * recurring events that started a long time ago won't appear.
	 */
	$recurring_start->modify("-5 years");
	$recuring_end->modify("+$daysForward days");

	/*
	 * In this loop we get the start and end time for each event, get the
	 * event name, and push all of the event data to the final event array.
	 */
	foreach ($all_init as $event) {

	    $vcalendar = VObject\Reader::read($event["event"]->getData());
	    
	    /*
	     * If the event has recurrences, expand this event into instances of
	     * recurring events.
	     */
	    $recurrences = $vcalendar->expand($recurring_start, $recuring_end);
	    $color = $event["color"];
	    $calendar = $event["calendar"];

	    /*
	     * Push recurrances to the final event array
	     */
	    if ($recurrences->VEVENT != null && count($recurrences->VEVENT) > 1) {

		foreach ($recurrences->VEVENT as $event) {
		    $summary = $event->SUMMARY;
		    $start = $event->DTSTART;
		    $end = $event->DTEND;
		    $is_recurring = "Yes";

		    array_push($all_events, array(
			"summary" => $summary,
			"start" => strtotime($start),
			"end" => strtotime($end),
			"color" => $color,
			"calendar" => $calendar,
			"recurring" => $is_recurring
		    ));
		}
	    } else {
		/*
		 * The event is a single event.  Get event details and add to
		 * array.
		 */
		$summary = $vcalendar->VEVENT->SUMMARY;
		$start = $vcalendar->VEVENT->DTSTART;
		$end = $vcalendar->VEVENT->DTEND;
		$is_recurring = "No";

		array_push($all_events, array(
		    "summary" => strval($summary),
		    "start" => strtotime($start),
		    "end" => strtotime($end),
		    "color" => $color,
		    "calendar" => $calendar,
		    "recurring" => $is_recurring
		));
	    }
	}

	/*
	 * Add events to the caldav events table.  We use a transaction so the
	 * switch between old data and new is instantaneous.  This prevents the
	 * (unlikely) scenario of a user loading the page and not seeing 
	 * anything because the data is between a delete and insert.
	 */
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
