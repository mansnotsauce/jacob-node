<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("horizon_salesforce.php");

$sf = new horizon_salesforce();
$url = $sf->instance_url . "/services/data/v20.0/sobjects/Account/" . $_GET['record'];
$result = $sf->get($url);

echo '<pre>';
print_r($result);