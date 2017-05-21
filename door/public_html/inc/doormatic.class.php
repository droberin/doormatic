<?php
class doorMatic {
  var $user = array();

  function __construct($user) {
    if (is_null($user)) {
      $this->user['username'] = null;
      $this->user['id'] = null;
      $this->user['lastseen'] = null;
    } else {
      $this->user = $user;
    }
  }

  function open() {
    global $speaker;
    global $doorHardware;
    // open the gate
    
    $aa = exec("sudo python " . $doorHardware['binary'] . " " . $doorHardware['wait_before_close'], $outputexec);

    // Should I notify acousticly?
    if ( ($speaker['active'] == true) && (isset($speaker['openScript'])) ) {
      system($speaker['baseDir'] . "/" . $speaker['openScript'] . " &");
    }
    return "Se abrió la puerta para: «" . $this->user['username'] . "»\n";
  }

  function close($door="A1") {
    global $doorHardware;
    global $speaker;

    // close the gate!!!
    system($doorHardware['binary'] . " " . $doorHardware['paramsOff']);

    // Should I notify acousticly?
    if ( ($speaker['active'] == true) && (isset($speaker['closeScript'])) ) {
      system($speaker['baseDir'] . "/" . $speaker['openScript'] . " &");
    }
    return "Se cerró la puerta inicialmente abierta para «" . $this->user['username'] . "»\n";
  }

  function openAndClose($door="A1", $sleeptime="3") {
    $val = $this->open($door);
    sleep($sleeptime);
    $val = $val . "\n" . $this->close($door);
    return $val;
  }

}
?>
