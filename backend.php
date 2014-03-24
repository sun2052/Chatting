<?php
// display all errors
error_reporting(-1);
ini_set('display_errors', 'On');

// Disable caching of the current document:
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Pragma: no-cache');

$filename = dirname(__FILE__) . '/data.txt';

// store new message in the file
$message = isset($_POST['message']) ? $_POST['message'] : '';
if ($message != '') {
	file_put_contents($filename, $message);
	exit();
}

// infinite loop until the data file is modified
$lastmodif = isset($_POST['timestamp']) ? $_POST['timestamp'] : 0;
$currentmodif = filemtime($filename);
while ($currentmodif <= $lastmodif) {  // check if the data file has been modified
	usleep(10000); // sleep 10ms to unload the CPU
	clearstatcache();
	$currentmodif = filemtime($filename);
}

// return a json array
$response = array();
$response['message'] = file_get_contents($filename);
$response['timestamp'] = $currentmodif;
echo json_encode($response);
flush();