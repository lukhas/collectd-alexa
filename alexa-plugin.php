#!/usr/bin/env php
<?php

// set variables
$hostname=getenv('COLLECTD_HOSTNAME');

if ($hostname == FALSE) {
  $hostname = rtrim(`hostname -f`);
}

$interval=getenv('COLLECTD_INTERVAL');

if ($interval == FALSE) {
  $interval = 60;
}

$sites = array(
    "chessbomb.com",
    "lichess.org",
    "chess24.com",
    "chesscube.com",
    "gameknot.com",
    "itsyourturn.com",
    "sparkchess.com",
    "chessbase.com",
    "chesstempo.com",
    "chess.com");

$chess_links = "";
$str = "";

function safe($name) {
    return str_replace('.', '_', $name);
}

while (TRUE) {
  $chess_links = file_get_contents("http://chess-links.org/index.html");
  
  foreach ($sites as $site) {
    preg_match("/($site).+?<td>([0-9]+)<\/td>/", $chess_links, $val);
    $str = "PUTVAL \"$hostname/alexa/gauge-" . safe($site) . "\" interval=$interval N:" .$val[2] ."\n";
    // print_r(safe($site) . ".value ".$val[2]."\n");
    print_r($str);
  }
  sleep($interval);
}

?>
