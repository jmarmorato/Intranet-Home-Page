<?php

function request($requestType, $url, $parameters = null, $headers = null) {

    // instantiate the response object
    $response = new \stdClass();

    // check if cURL is enabled
    if (!function_exists('curl_init')) {

	$response->success = false;
	$response->body = 'cURL is not enabled.';

	return $response;
    }

    // instantiate a cURL instance and set the handle
    $ch = curl_init();

    // build http query if $parameters is not null. Parameters with null as value will be removed from query.
    ($parameters !== null) ? $query = http_build_query($parameters) : $query = '';

    // POST:
    if ($requestType === 'POST') {

	// 1 tells libcurl to do a regular HTTP post and sets a "Content-Type: application/www-form-urlencoded" header by default
	curl_setopt($ch, CURLOPT_POST, 1);
	// add the query as POST body
	curl_setopt($ch, CURLOPT_POSTFIELDS, $query);

	// GET:
    } elseif ($requestType === 'GET') {

	// if not empty, add parameters to URL
	if ($query)
	    $url = $url . '?' . $query;

	// ELSE:
    } else {

	$response->success = false;
	$response->body = 'request type GET or POST is missing.';

	return $response;
    }

    // set the URL
    curl_setopt($ch, CURLOPT_URL, $url);
    // tell cURL to return the response body. A successful request will return true if not set.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // disable ssl certificate checks. Dirty, insecure workaround for common error "SSL Error: unable to get local issuer certificate". Fix it the correct way and remove the line!
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // more options here: http://php.net/manual/en/function.curl-setopt.php
    // add headers if present
    if ($headers !== null)
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // execute and store the result
    $result = curl_exec($ch);

    // check if request was successful. If yes, return result. If not, return error and its code.
    if ($result !== false) {

	$response->success = true;
	$response->body = $result;
    } else {

	$response->success = false;
	$response->body = curl_error($ch);
	$response->error = curl_errno($ch);
    }

    // close session and delete handle
    curl_close($ch);

    // return response object
    return $response;
}

function index() {

    //$request = service('request');

    $baseAutoCompleteURL = "http://suggestqueries.google.com/complete/search?client=firefox&q=";
    $query = $_GET["query"];

    $requestUrl = $baseAutoCompleteURL . urlencode($query);

    $parameters = array();
    $response = request('GET', $requestUrl, $parameters);
    $json_response = json_decode($response->body);

    if (is_null($json_response)) {
	return json_encode(array());
    }

    //Build the response object
    $response_object = array(
	"query" => $_GET["query"],
	"suggestions" => array()
    );

    foreach ($json_response[1] as $suggestion) {
	array_push($response_object["suggestions"], array(
	    "data" => $suggestion,
	    "value" => $suggestion
	));
    }

    return json_encode($response_object);
}

echo index();
?>
