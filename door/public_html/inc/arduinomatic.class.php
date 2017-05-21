<?php


/*
$ardu->pinMode(11,"output");
$ardu->digitalWrite(11,1);
$ardu->digitalWrite(13,0);
sleep(2);
$ardu->digitalWrite(11,0);
*/

//$ardu->acceptUntrustedCA = true;


// Working on this...
//$ardu->setUserAndPass("arduino","miaomiao");

// duration should be set in milliseconds.
//$ardu = new arduinoMatic();
//$ardu->baseREST="http://192.168.0.103/arduino/";
//$ardu->setUserAndPass("root","miaomiao");
//$ardu->open(11,4000);




class arduinoMatic {

  var $baseREST = "http://127.0.0.1/arduino/";
  //var $baseREST = "http://192.168.1.132/arduino/";
  var $acceptUntrustedCA = "false"; // set to "true" to accept any SSL certificate, in case you want it and you are using HTTPS
  var $mode = "mode";
  var $out = "output";
  var $in = "input";
  var $low = "0";
  var $high = "1";
  var $RESTUser;
  var $RESTPass;


  function setUserAndPass($user="",$pass="") {
    $this->RESTUser = $user;
    $this->RESTPass = $pass;
  }

  function open($pin, $duration=4000) {
    $url;

    if (!is_numeric($pin) or !is_numeric($duration)) { return "PIN or/and DURATION is/are not a number"; }

    $url = $this->baseREST . "door" . "/" . $pin . "/" . $duration;
    //print "Trying: $url\n";
    print $this->getThat($url);
  }


  function pinMode($pin, $mode) {
    $url;

    if (($mode != "output") && ($mode != "input")) {
      $mode = $this->out;
    }
    $mode = strtolower($mode);
    $url = $this->baseREST . $this->mode . "/" . $pin . "/" . $mode;

    return $this->getThat($url);
  }

  function digitalWrite($pin, $value) {
    $url;

    if (($value != 0) && ($value != 1)) {
      $value = $this->low;
    }

    $url = $this->baseREST . "digital/" . $pin . "/" . $value;

    return $this->getThat($url);
  }

  function getThat($url) {
    // Initialize session and set URL.
    $ch = curl_init();

    if ($this->acceptUntrustedCA) {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }

    if (!empty($this->RESTUser) or !empty($this->RESTPass)) {
      curl_setopt($ch, CURLOPT_USERPWD, $this->RESTUser . ":" . $this->RESTPass);
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    // Set so curl_exec returns the result instead of outputting it.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Get the response and close the channel.
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
  }

}
