<?
require_once("../../config/config.php");


function genUUID() {
  if (!function_exists('uuid_create'))
      return false;

    uuid_create(&$context);

    uuid_make($context, UUID_MAKE_V4);
    uuid_export($context, UUID_FMT_STR, &$uuid);
    return trim($uuid);
}

function listUsers() {
  global $dragonDB;
  $users = array();
  $query = "select ID,username,email,active,lastseen from " . $dragonDB['database'] . ".users ORDER BY active";
  $conn = mysql_connect($dragonDB['server'], $dragonDB['username'], $dragonDB['password']);
  $result = mysql_query($query, $conn);
  if (mysql_num_rows($result) == 0) { return $users; }
  while ($row = mysql_fetch_assoc($result)) {
    array_push($users, $row);
  }
  return $users;
}

function listDevices() {
  global $dragonDB;
  $devices = array();
  $query = "select id,person_id,mac_address,creation_date,name from " . $dragonDB['database'] . ".device ORDER BY person_id";
  $conn = mysql_connect($dragonDB['server'], $dragonDB['username'], $dragonDB['password']);
  $result = mysql_query($query, $conn);
  if (mysql_num_rows($result) == 0) { return $users; }
  while ($row = mysql_fetch_assoc($result)) {
    array_push($devices, $row);
  }
  return $devices;
}

function setStatus($userID,$status=0) {
  global $dragonDB;
  if (!is_numeric($userID)) { return "Error"; }
  if (!is_numeric($status)) { return "Error"; }
  if (($status != 1) and ($status != 0)) { $status = 0; }
  $query = "update " . $dragonDB['database'] . ".users set active=" . $status . " where ID=" . $userID;
  //print "$query";
  $conn = mysql_connect($dragonDB['server'], $dragonDB['username'], $dragonDB['password']);
  $result = mysql_query($query, $conn);
  return;
}

function setPassword($userID,$newPassword) {
  global $dragonDB;
  if (!is_numeric($userID)) { return "Error"; }
  $newPassword = sha1($newPassword);
  $query = "update " . $dragonDB['database'] . ".users set password='" . $newPassword . "' where ID=" . $userID;
  //print "$query";
  $conn = mysql_connect($dragonDB['server'], $dragonDB['username'], $dragonDB['password']);
  $result = mysql_query($query, $conn);
  return;
}

function createUser($username,$password,$email) {
  global $dragonDB;

  $username = preg_replace("/[^\w\d ]/ui", '', $username);
  if ($username == "") return "Invalid username";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { return "Invalid email!"; }

  $password = sha1($password);
  
  $query = "insert into " . $dragonDB['database'] . ".users set password='" . $password . "',username='" . $username ."',email='" . $email . "',revocationKey=112221,creatorID=1";
  //print "$query";
  //return;
  $conn = mysql_connect($dragonDB['server'], $dragonDB['username'], $dragonDB['password']);
  $result = mysql_query($query, $conn);
  return;
}

function createDevice($user,$mac,$name) {
  global $dragonDB;

  $name = preg_replace("/[^\w\d ]/ui", '', $name);
  if (!is_numeric($user)) return "Invalid userID";
  if (!preg_match('/^[0-9a-fA-F]{2}(?=([:;.]?))(?:\\1[0-9a-fA-F]{2}){5}$/',$mac)) { return "Invalid MAC"; }


  $query = "insert into " . $dragonDB['database'] . ".device set person_id='" . $user . "',name='" . $name ."',mac_address='" . $mac . "'";
  $conn = mysql_connect($dragonDB['server'], $dragonDB['username'], $dragonDB['password']);
  $result = mysql_query($query, $conn);
  return;
}

function removeDevice($user,$mac) {
  global $dragonDB;

  $name = preg_replace("/[^\w\d ]/ui", '', $name);
  if (!is_numeric($user)) return "Invalid userID";
  if (!preg_match('/^[0-9a-fA-F]{2}(?=([:;.]?))(?:\\1[0-9a-fA-F]{2}){5}$/',$mac)) { return "Invalid MAC"; }


  $query = "delete from " . $dragonDB['database'] . ".device where person_id='" . $user . "' and mac_address='" . $mac . "'";
  $conn = mysql_connect($dragonDB['server'], $dragonDB['username'], $dragonDB['password']);
  $result = mysql_query($query, $conn);
  return;
}


?>