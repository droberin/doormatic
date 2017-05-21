<?
include_once("../config/config.php");
include_once("inc/getmac.php");


//system($speaker['baseDir'] . "/" . $speaker['openScript'] . " &");

print getMACForIP("192.168.0.180");
