<?php
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-type: text/html");


$user = array();
require_once("inc/doormatic.class.php");
require_once("inc/dragonsFellow.class.php");
require_once("inc/doorLogger.class.php");
require_once("inc/arduinomatic.class.php");
require_once("../config/config.php");
//require_once("inc/sendmessage.php");
require_once("inc/getmac.php");


// Create messenger.
//$msg = new messenger($messenger);
$msg = NULL;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
  include_once("inc/header.php");

  include_once("inc/body.inc.php");

  include_once("inc/footer.php");

} else if ($_SERVER['REQUEST_METHOD'] == "POST") {

  if (!isset($_POST['portable'])) {
   include_once("inc/header.php");
  }

  $user = array();

  if ($_POST['action'] == "open") {

    $user['username'] = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['UserName']);
    $user['password'] = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['Passwd']);

    $door = new doorMatic($user);

    if ($debug == true) {
      $debugReport = date("c") . "<br />\n " . "[Pre-open] resquest as «" . $user['username'] . "» [IP: " . $_SERVER['REMOTE_ADDR'] . " ]";
      $msg->send($debugger, $debugReport);
    }

    // Let's create a fellow!
    $fella = new dragonsFellow($user,$dragonDB);

    // Don't forget about the logger
    $logger = new doorLogger($user,$dragonDB);
    
    if ($fella->dragonDB['ready'] != true) {
       $msg->send($debugger, date("c") . "\n La base de datos parece estar mal configurada:\nNombre de la DB: " . $fella->dragonDB['database'] . " \n");
    }

    // Validate MAC
    $testMAC = getMACforIP($_SERVER['REMOTE_ADDR']);
    if ( ($testMAC != false) && ($fella->isValidMAC($testMAC) == true) ) {

      if ($debug == true) {
        $msg->send($debugger, date("c") . "\n MAC válida: " . $testMAC . "\nNombre de dispositivo: " . $fella->user['lastDevice'] . " \n");
      }


      // Validar usuarios
      if ($fella->validate($user['password']) == true) {

        // Para el debug
        if ($debug == true) {
          $msg->send($debugger, date("c") . "\n Usuario validado: " . $user['username'] . "\nSolicitando apertura\n");
        }

        // Open up the door, baby!
        $doorlog .= $logger->log("Se abre la puerta al usuario");
        if ($testing != true) {
         $doorlog .= $door->open();
         //echo "$doorlog";
          if ($debug == true) {
            $debugReport = date("c") . "<br />\n Door->open: " . $doorlog;
            $msg->send($debugger, $debugReport);
          }
         // 
         // In case of use of Arduino....
         /*
         //$ardu = new arduinoMatic();
         //$ardu->baseREST="http://192.168.0.103/arduino/";
         //$ardu->setUserAndPass("root","miaomiao");
         //$ardu->open(12,$doorHardware['wait_before_close']);
         */
        }


        if ($debug == true) {
          $debugReport = date("c") . "<br />\n Door->close: " . $doorlog;
          $msg->send($debugger, $debugReport);
        }
        $doorlog = "You were recognized as one of us.<br /><b>Welcome to the Dragon's land.</b>\n";
        if ($debug == true) {
         $doorlog = $doorlog . "<br /> <b>Device</b>: <i>" . $fella->user['lastDevice'] . "</i>";
        }

        // Loguear entrada...
        //$fella->log();

      } else {
        //$msg->send($debugger, "Error de usuario/contraseña para «" . $user['username'] . "» [IP: " . $_SERVER['REMOTE_ADDR'] . " ]");
        $doorlog = "¡No eres bienvenido, foráneo!";
        $logger->log("Error de autenticación. Si es que existe tal usuario...");
        // Should I notify acousticly?
        if ( ($speaker['activeOnFail'] == true) && (isset($speaker['failScript'])) ) {
         system($speaker['baseDir'] . "/" . $speaker['failScript'] . " &");
        }


      }
    } else {
      $errmsg = "Intento de apertura con MAC no dada de alta «" . $testMAC . "»";
      //$msg->send($debugger, $errmsg);
      $logger->log($errmsg);
      $doorlog .= "Su <b>dispositivo no está permitido</b> para este sitio.<br /> Solicítelo a un <i>GateKeeper</i>";
      // Should I notify acousticly?
      if ( ($speaker['activeOnFail'] == true) && (isset($speaker['failScript'])) ) {
       system($speaker['baseDir'] . "/" . $speaker['failScript'] . " &");
      }

    }

    // portable devices output
    if (isset($_POST['portable'])) {
      print $doorlog;
    }

  } elseif ($_POST['action'] == "register") {
    $newUser = array( "UserName" => stripslashes($_POST['UserName']),
                      "FirstName" => stripslashes($_POST['FirstName']),
                      "LastName" => stripslashes($_POST['LastName']),
                      "Email" => stripslashes($_POST['Email'])
                    );
    if (count(array_unique($newUser)) > 2) {
     $newUser['IP_ADDR'] = $_SERVER['REMOTE_ADDR'];
     $newUser['MAC'] = getMACforIP($_SERVER['REMOTE_ADDR']);
     $newUser['BROWSER'] = $_SERVER['HTTP_USER_AGENT'];
     $newUser['LANG'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

     $mailTo = $dragonName . " <" . $dragonEmail . ">";
     $mailSubject = date("c") . " Dragons Door Solicitud de nuevo usuario: " . $newUser['UserName'] . " ( ". $newUser['FirstName'] . " " . $newUser['LastName'] . " )";
     $mailMessage = "Dragons Door\nSolicitud usuario: " . $newUser['UserName'] . "\r\n".
                    "Nombre: " . $newUser['FirstName'] . "\r\n" .
                    "Apellido: " . $newUser['LastName'] . "\r\n" .
                    "Dirección email: " . $newUser['Email'] . "\r\n".
                    "\r\n\r\n".
                    "Datos de la solicitud:\r\n" .
                    "IP: " . $newUser['IP_ADDR'] . "\r\n".
                    "MAC: " . $newUser['MAC'] . "\r\n".
                    "Navegador:". $newUser['BROWSER'] . "\r\n".
                    "Idiomas: ". $newUser['LANG'] . "\r\n".
                    "\n";
     $mailMessage = wordwrap($mailMessage, 70, "\r\n");
     $mailHeader = 'From: ' . $dragonSender . "\r\n" .
                   'Reply-To: ' . $dragonEmail . "\r\n" .
                   'X-Mailer: PHP/' . phpversion() . "/doormatic";

     $msg->send($debugger, $mailMessage);
     
     $doorlog = "Solicitud enviada: Si eres un auténtico dragón obtendrás la entrada a la cueva. Sé paciente.";

     if ($debug == false) {
       mail($mailTo, $mailSubject, $mailMessage, $mailHeader);
     }

    } else {
     $doorlog = "Error: el formulario no se completó correctamente.";
    }

  }

  if (!isset($_POST['portable'])) {
   include_once("inc/body.inc.php");
   include_once("inc/footer.php");
  }
  
}
