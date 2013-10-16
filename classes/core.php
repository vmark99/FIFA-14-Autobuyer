<?php
Namespace FIFA14;
 
require_once 'vendor/autoload.php';
require_once 'classes/connector.php';
require 'classes/eahashor.php';
require 'search.php';
define("CURL_OPTION_SSL_VERIFYPEER", false);
 
 
 
$answer = ""; // Enter your answer here (Not hash)
$hashor     = new EAHashor();
$hash       = $hashor->eaEncode($answer);
 
$datos = array(
            "username" => "",
            "password" => "",
            "platform" => "", // 360, pc, ps3
            "hash" => $hash,
            );
$connector = new Connector($datos);
$con = $connector->Connect();
 
$nuc = $con["phishingToken"];
$sess = $con["sessionId"];
$phish = $con["phishingToken"];
 
$s = new Search($nuc,$sess,$phish);
 
?>
