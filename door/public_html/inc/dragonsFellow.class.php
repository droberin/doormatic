<?php
class dragonsFellow {
  var $user = array(
                'username'	=> null,
                'validUser'	=> false,
                'lastseen'	=> 1384162686,
                'peopleID'	=> null,
                'lastDevice'	=> null,
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
    if (is_null($user)) {
      $this->user['username'] = null;
      $this->user['password'] = null;
      $this->user['lastseen'] = null;
    } else {
      $this->user = $user;
    }
    if (!is_null($db)) {
      $this->dragonDB = $db;
//      if ((preg_match("/[^a-zA-z0-9_\-]/", $this->dragonDB['database'])) or (preg_match("/[^a-zA-z0-9_\-]/", $this->dragonDB['username'])) ) {
      if ((preg_match("/[^a-z_\-0-9]/i", $this->dragonDB['database'])) or (preg_match("/[^a-z_\-0-9]/i", $this->dragonDB['username']))  ) {
        echo "DB is not properly set, my friend!<br />\n";
        $this->dragonDB['ready'] = false;
      } else {
        $this->dragonDB['ready'] = true;
      }
    }
  }

  function validate($password) {

    //can't continue if DB settings are not right
    if ($this->dragonDB['ready'] != true) { return false; }

    $password = sha1($password);
    $conn = mysql_connect($this->dragonDB['server'], $this->dragonDB['username'], $this->dragonDB['password']);
    $query = "select ID, username, peopleID from " . $this->dragonDB['database'] . ".users where username='" . $this->user['username'] . "' and password='" . $password . "' and active='1' LIMIT 1";
    //var_dump($query);
    $result = mysql_query($query, $conn);
    //var_dump($result);

    //var_dump($id);
    
    if (mysql_num_rows($result) == 0) {
      $this->user['validUser'] = false;
    } else {
      global $veryRestrictiveAccess;
      $peopleID = mysql_result($result, 0, 2);
      $name = mysql_result($result, 0, 1);
      $id = mysql_result($result, 0, 0);

      $this->user['username'] = $name;
      $this->user['id'] = $id;
      $this->user['peopleID'] = $peopleID;
      $query = "update " . $this->dragonDB['database'] . ".users set lastseen=FROM_UNIXTIME(". date("U"). ") where username='" . $this->user['username'] . "' and id='" . $this->user['id'] . "' and peopleID='" . $this->user['peopleID'] . "'";
      $result = mysql_query($query, $conn);
      // if ($result != null) { mysql_free_result($result); }
      if ( ($veryRestrictiveAccess == true) && ($this->user['peopleID'] != $this->user['MAC_person_id']) ) { return false; }
      $this->user['validUser'] = true;
    }
    return $this->user['validUser'];
  }

  function isValidMAC($MAC) {
    // Cant continue if DB is not ready/well configured
    if ($this->dragonDB['ready'] != true) { return false; }

    $MAC = stripslashes($MAC);
    $query = "select name,person_id from " . $this->dragonDB['database'] . ".device where mac_address='" . $MAC . "' and active=1";
    $conn = mysql_connect($this->dragonDB['server'], $this->dragonDB['username'], $this->dragonDB['password']);
    $result = mysql_query($query, $conn);
    if (mysql_num_rows($result) == 0) {
      $validMAC = false;
    } else {
      $this->user['lastDevice'] = mysql_result($result, 0, 0);
      $this->user['MAC_person_id'] = mysql_result($result, 0, 1);
      $validMAC = true;
    }
    mysql_free_result($result);
    return $validMAC;
  }
}
?>
