<?php
class doorLogger {
  var $user = array(
                'username'      => null,
                'peopleID'      => null,
                'lastDevice'    => null,
  ); 

  var $dragonDB = array(
              'server'		=> "127.0.0.1",
              'username'	=> "",
              'database'	=> "dragondb",
              'password'	=> "",
              'table_preffix'	=> "",
              'dbType'		=> "mysql",
              'ready'		=> true,
  );

  function __construct($user,$db) {
    $this->user = $user;
    if (!is_null($db)) {
      $this->dragonDB = $db;
    }
  }

  function log($desc) {

    //can't continue if DB settings are not right
    //if ($this->dragonDB['ready'] != true) { return "fuck this shit!"; }

    $conn = mysql_connect($this->dragonDB['server'], $this->dragonDB['username'], $this->dragonDB['password']);
    $query = "insert into " . $this->dragonDB['database'] . ".access_log set user='" . $this->user['username'] . "', IP='" . $_SERVER['REMOTE_ADDR'] . "', description='". $desc . "'";
    $result = mysql_query($query, $conn);
    //return true;
  }

}
?>
