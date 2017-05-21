<?php
require_once("../../config/config.php");
require_once("../../config/email.inc.php");

#require_once "Mail.php";


/*
function genUUID() {
  if (!function_exists('uuid_create'))
      return false;

    uuid_create(&$context);

    uuid_make($context, UUID_MAKE_V4);
    uuid_export($context, UUID_FMT_STR, &$uuid);
    return trim($uuid);
}
*/


function getTotalUsers($valid=false) {
  global $dragonDB;

  $query = "select 1 from " . $dragonDB['database'] . ".users";
  if ($valid == true) {
    $query = "select 1 from " . $dragonDB['database'] . ".users where active=1";
  }
  $conn = mysql_connect($dragonDB['server'], $dragonDB['username'], $dragonDB['password']);
  $result = mysql_query($query, $conn);
  return mysql_num_rows($result);
}

function listUsers() {
  global $dragonDB;
  $users = array();
  $query = "select ID,username,email,active,lastseen from " . $dragonDB['database'] . ".users ORDER BY active DESC, username";
  $conn = mysql_connect($dragonDB['server'], $dragonDB['username'], $dragonDB['password']);
  $result = mysql_query($query, $conn);
  if (mysql_num_rows($result) == 0) { return $users; }
  while ($row = mysql_fetch_assoc($result)) {
    array_push($users, $row);
  }
  return $users;
}

function getLogEntries($limit=300) {
  global $dragonDB;
  $logEntries = array();
  $query = "select date,user,IP,description from " . $dragonDB['database'] . ".access_log ORDER BY date DESC LIMIT 300";
  $conn = mysql_connect($dragonDB['server'], $dragonDB['username'], $dragonDB['password']);
  $result = mysql_query($query, $conn);
  if (mysql_num_rows($result) == 0) { return $logEntries; }
  while ($row = mysql_fetch_assoc($result)) {
    array_push($logEntries, $row);
  }
  return $logEntries;
}

function listDevices($userID=0) {
  global $dragonDB;
  $devices = array();
  $query = "select id,person_id,mac_address,creation_date,name from " . $dragonDB['database'] . ".device ORDER BY person_id";
  if ((is_numeric($userID)) && ($userID > 0)) {
    $query = "select id,person_id,mac_address,creation_date,name from " . $dragonDB['database'] . ".device where person_id=" . $userID . " ORDER BY person_id";
  }
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
  $revocationKey = sha1(uniqid($username, true));
  
  $query = "insert into " . $dragonDB['database'] . ".users set password='" . $password . "',username='" . $username ."',email='" . $email . "',revocationKey='" . $revocationKey . "',creatorID=1";
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

function getEmailFromUserID($userID) {
  global $dragonDB;

  if (!is_numeric($userID)) { return false; }
  $userID = substr($userID,0,20);
  $query = "select email,username,revocationKey from " . $dragonDB['database'] . ".users where ID=" . $userID . " LIMIT 1";
  $conn = mysql_connect($dragonDB['server'], $dragonDB['username'], $dragonDB['password']);
  $result = mysql_query($query, $conn);
  if (mysql_num_rows($result) == 0) {
    return false;
  } else {
    $row = mysql_fetch_assoc($result);
    return $row;
  }
}

function getUserNameFromUserID($userID) {
  global $dragonDB;

  if (!is_numeric($userID)) { return false; }
  $userID = substr($userID,0,20);
  $query = "select username from " . $dragonDB['database'] . ".users where ID=" . $userID . " LIMIT 1";
  $conn = mysql_connect($dragonDB['server'], $dragonDB['username'], $dragonDB['password']);
  $result = mysql_query($query, $conn);
  if (mysql_num_rows($result) == 0) {
    return false;
  } else {
    $row = mysql_fetch_assoc($result);
    return $row['username'];
  }
}

function sendEmailTo($to, $subject, $body) {
// Pear Mail Library
  global $emailAccount;
  $from = '<' . $emailAccount['username']. '>';
  $to = '<' . $to . '>';
  $subject = '[Dragons]:' . $subject;

  $headers = array(
      'From' => $from,
      'To' => $to,
      'Subject' => $subject,
      'Content-type' => "text/html; charset=UTF-8;",
  );

  $smtp = Mail::factory('smtp', array(
          'host' => 'ssl://smtp.gmail.com',
          'port' => '465',
          'auth' => true,
          'username' => $emailAccount['username'],
          'password' => $emailAccount['password'],
          ));
  $mail = $smtp->send($to, $headers, $body);

  if (PEAR::isError($mail)) {
      return $mail->getMessage();
  } else {
      return 'Message successfully sent!';
  }

}
