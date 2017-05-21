<?php

if (!isset($_SERVER['REMOTE_USER'])) {
 die("Must configure your webserver properly... No user received...");
}


require_once("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isset($_POST['action'])) {
		switch ($_POST['action']) {
			case "disable":
				$report = "Received disabling request for userid <".$_POST['user'].">";
				print setStatus($_POST['user'], 0);
				break;

			case "enable":
				$report = "Received enabling request for userid <".$_POST['user'].">";
				print setStatus($_POST['user'], 1);
				break;

                        case "changePassword":
				$report = "Nueva clave para <".$_POST['user']."> configurada";
				setPassword($_POST['user'], $_POST['newPassword']);
				break;

                        case "sendRevocation":

				$reportToEmail = array();
                                if ($reportToEmail = getEmailFromUserID($_POST['user'])) {

                                        $mailTo = $UserName . " <" . $reportToEmail['email'] . ">";
                                        $mailSubject = date("c") . " clave de revocación para el usuario " . $reportToEmail['username'];
                                        $mailMessage = "Dragons Door\nRevocación para usuario:" . $reportToEmail['username'] . "\r\n".
                                                "Datos para el bloqueo:\r\n" .
                                                "Servidor:". $revocationServer . "\r\n".
                                                "Usuario: ". $reportToEmail['username'] . "\r\n".
                                                "Clave de revocación: ". $reportToEmail['revocationKey'] . "\r\n".
                                                "\r\n".
                                                "Enlace directo: " . $revocationServer . "?UserName=" . $reportToEmail['username'] . "&revocationKey=" . $reportToEmail['revocationKey'] . "\r\n".
                                                "\n";
                                        $mailMessage = wordwrap($mailMessage, 70, "\r\n");
                                        $mailHeader = 'From: ' . $dragonSender . "\r\n" . 
                                                      'Reply-To: ' . $dragonEmail . "\r\n" .
                                                      'X-Mailer: PHP/' . phpversion() . "/doormaticAdmin";

                                        mail($mailTo, $mailSubject, $mailMessage, $mailHeader);
        				$report = "Clave enviada a  «" . $reportToEmail['email'] . "»";
        				
                                        } else {
                                                $doorlog .= "No se ha reportado vía email.";
                                        }
                                                                                                                                                                                                                                                                                                               				
				break;

                        case "newUser":
				$report = "Solicitado nuevo usuario...";
				print createUser($_POST['username'],$_POST['password'],$_POST['email']);
				break;

		}
	}
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Doormatic Admin Panel</title>
<link rel="stylesheet" href="main.css" type="text/css" />
</head>
<body>

<div id="div_1">
	<div id="div_2">
        	<img id="image_7" src="bgimg.png" />
        	<div id="textheader_9"><b>Doormatic</b>: <u><?php print $report;?></u></div>
		<ul id="navbar_6">
		        <li><a href="users.php">Usuarios</a></li>
                        <li><a href="devices.php">Dispositivos</a></li>
                        <li><a href="#"><del>Privilegios</del></a></li>
                        <li><a href="log.php">Registros</a></li>
                </ul>

		<div id="div_3">
			<div id="infoList">Listando las últimas entradas</div>
			<table border="1px" cellspacing="0px" id="table_8">
				<tr>
					<th>Fecha</th>
					<th>IP</th>
					<th>Usuario</th>
					<th>Descripción</th>
				</tr>
<?php
$logEntries = getLogEntries(300);
if (count($logEntries) > 0) {
	foreach ($logEntries as $logEntry) {
        if ($trBgColor != "trrow_white") {
                $trBgColor = "trrow_white";
        } else {
                $trBgColor = "trrow_grey"; 
        }
?>
				<tr id="<?php print $trBgColor;?>">
					<td><?php print $logEntry['date'];?></td>
					<td><?php print $logEntry['IP'];?></td>
					<td><?php print $logEntry['user'];?></td>
					<td align="center"><?php print $logEntry['description'];?></td>
				</tr>
				<?php
	  }
	}
?>
			</table>
		</div>
	</div>
</div>

</body>
</html>
