<?php

if (!isset($_SERVER['REMOTE_USER'])) {
 echo "no";
 die("Must configure your webserver properly... No user received...");
}


require_once("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isset($_POST['action'])) {
		switch ($_POST['action']) {
			case "disable":
				$report = "Desactivado usuario «" . getUserNameFromUserID($_POST['user']) . "»";
				print setStatus($_POST['user'], 0);
				break;

			case "enable":
				$report = "Activado usuario «" . getUserNameFromUserID($_POST['user']) . "»";
				print setStatus($_POST['user'], 1);
				break;

                        case "changePassword":
				$report = "Configurada nueva clave para «".getUserNameFromUserID($_POST['user'])."»";
				setPassword($_POST['user'], $_POST['newPassword']);
				break;

                        case "sendRevocation":

				$reportToEmail = array();
                                if ($reportToEmail = getEmailFromUserID($_POST['user'])) {
                                        global $emailAccount;
                                        $mailTo = $UserName . " <" . $reportToEmail['email'] . ">";
                                        $mailSubject = date("c") . " clave de revocación para el usuario " . $reportToEmail['username'];
                                        $mailMessage = "Dragons Door\nRevocación para usuario:" . $reportToEmail['username'] . "\r\n".
                                                "Si en algún momntopierdes tu dispositivo o crees que te lo han robado,\r\n" .
                                                "lo más seguro es que desactives tu usuario siguiendo el enlace a continuación\r\n" .
                                                "y confirmes que así ha ocurrido\r\n" .
                                                "\r\n" .
                                                "Cuando lo recuperes o tengas otro solicita la reactivación y cambio de clave a un administrador\r\n" .
                                                "\r\n" .
                                                "Enlace directo: " . $revocationServer . "?UserName=" . $reportToEmail['username'] . "&revocationKey=" . $reportToEmail['revocationKey'] . "\r\n" .
                                                "\n";
                                        $mailMessage = wordwrap($mailMessage, 70, "\r\n");
                                        /*
                                        $mailHeader = 'From: ' . $dragonSender . "\r\n" . 
                                                      'Reply-To: ' . $dragonEmail . "\r\n" .
                                                      'X-Mailer: PHP/' . phpversion() . "/doormaticAdmin";
                                        */
                                        //mail($mailTo, $mailSubject, $mailMessage, $mailHeader);
                                        $strSearch = array(
                                                "{{username}}",
                                                "{{revocationURI}}",
                                                "{{revocationKey}}",
                                        );
                                        $strReplace = array(
                                                $reportToEmail['username'],
                                                $revocationServer . "?UserName=" . $reportToEmail['username'] . "&revocationKey=" . $reportToEmail['revocationKey'],
                                                $reportToEmail['revocationKey'],
                                        );
                                        
                                        $file = fopen($emailAccount['sheet']['revocation'],"r");
                                        $dataSheet = fread($file,filesize($emailAccount['sheet']['revocation']));
                                        fclose($file);
                                        $mailMessage = str_replace($strSearch,$strReplace,$dataSheet);
                                        //$report = $mailMessage;
                                        $report = sendEmailTo($reportToEmail['email'], $mailSubject, $mailMessage);
        				//$report = "Clave enviada a  «" . $reportToEmail['email'] . "»";
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

		<div id="div_4">
			<div id="textheader_14">Nuevo usuario</div>
			<form method="POST">
        			<input type="hidden" name="action" value="newUser"/>
	        		<input type="text" name="username" id="texteditbox_11" maxlength=20 placeholder="Nombre de usuario"/>
		        	<input type="text" name="email" id="texteditbox_12" maxlength=150 placeholder="Email de contacto / Jabber"/>
		        	<input type="password" id="texteditbox_13" maxlength=150 placeholder="Contraseña" value=""/>
        			<input type="submit" value="Añadir" id="button_15" />
			</form>
		</div>
		<div id="div_3">
			<div id="infoList">Usuarios válidos: <?php print getTotalUsers(true);?>/<?php print getTotalUsers(false);?></div>
			<table border="1px" cellspacing="0px" id="table_8">
				<tr>
					<th>Visto</th>
					<th>Usuario</th>
					<th>Nombre</th>
					<th>email</th>
					<th>Contraseña</th>
					<th>Acciones</th>
				</tr>
<?php
$users = listUsers();
$firstInactive = true;
if (count($users) > 0) {
	foreach ($users as $usertmp) {
	global $firstInactive;
        if ($trBgColor != "trrow_white") {
                $trBgColor = "trrow_white";
        } else {
                $trBgColor = "trrow_grey"; 
        }

	if ($usertmp['active'] == "0") {
		$switchStatus = "enable";
		$switchCaption = "Activar usuario";
		$trBgColor = "trrow_red";
		if ($firstInactive == true) {
		        $firstInactive = false;
?>
				<tr>
					<th>Visto</th>
					<th>Usuario</th>
					<th>Nombre</th>
					<th>email</th>
					<th>Contraseña</th>
					<th>Acciones</th>
				</tr>
<?php
                }
	} else {
		$switchStatus = "disable";
		$switchCaption = "Desactivar usuario";
	}

?>
				<tr id="<?php print $trBgColor;?>">
					<td><?php print $usertmp['lastseen'];?></td>
					<td><?php print $usertmp['username'];?></td>
					<td><?php print $usertmp['username'];?></td>
					<td align="center"><?php print $usertmp['email'];?>
<?php
if ($usertmp['active'] == "1") {
?>
                                                <form method="POST">
							<input type="hidden" name="action" value="sendRevocation"/>
							<input type="hidden" name="user" value="<?php print $usertmp['ID'];?>" />
							<input id="btnRevocationKey" type="submit" value="Enviar clave revocación"/>
						</form>
<?php
}
?>
</td>
					<td align="center">
						<form method="POST">
							<input type="hidden" name="action" value="changePassword"/>
							<input type="hidden" name="user" value="<?php print $usertmp['ID'];?>" />
							<input type="password" name="newPassword" value="" />
							<input type="submit" value="Cambiar"/>
						</form>

					</td>
					<td align="center">
						<form method="POST">
							<?php
							?>
							<input type="hidden" name="action" value="<?php print $switchStatus?>"/>
							<input type="hidden" name="user" value="<?php print $usertmp['ID'];?>" />
							<input type="submit" value="<?php print $switchCaption?>"/>
						</form>


						<form method="GET" action="devices.php">
							<input type="hidden" name="user" value="<?php print $usertmp['ID'];?>" />
							<input type="submit" value="Ver/Añadir dispositivo"/>
						</form>
					</td>
				</tr>
				<?php
	  }
	}
?>
			</table>
		</div>
		<div id="div_5" style="display:none;">
			<button type="button"id="button_17">Buscar</button>
			<button type="button"id="button_18">Todos</button>
			<input type="text" id="texteditbox_19" value="Introduce un nombre/apodo"/>
		</div>
	</div>
</div>

</body>
</html>
