<?php
	
	require_once('config.php');

	$coupon = str_replace(array(' ','.',',','-','_','(',')','"','\'','*','/','#'), '',$_POST['copupon']);
	
	$date = new DateTime();
	$url = 'https://udsgame.com/v1/partner/customer?code='.$coupon;
	$uuid_v4 = UDS_KEY; //'549755998764'; //generate universally unique identifier version 4 (RFC 4122)
	$apiKey = UDS_API; //'ZmNmNGE0OWQtN2EzZC00ZDEwLTgwYTUtYjBlNGI4MjQwZmU0'; //set your api-key
	
	// Create a stream
	$opts = array(
		'http' => array(
			'method' => 'GET',
			'header' => "Accept: application/json\r\n" .
						"Accept-Charset: utf-8\r\n" .
						"X-Api-Key: ".$apiKey."\r\n" .
						"X-Origin-Request-Id: ".$uuid_v4."\r\n" .
						"X-Timestamp: ".$date->format(DateTime::ATOM)
		)
	);
	
	$context = stream_context_create($opts);
	
	$result = file_get_contents($url, false, $context);
	
	echo json_encode($result);

