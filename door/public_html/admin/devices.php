<?php

if (!isset($_SERVER['REMOTE_USER'])) {
 die("Must configure your webserver properly... No user received...");
}


require_once("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isset($_POST['action'])) {
		switch ($_POST['action']) {
                        case "removeDevice":
				$report = "Solicitud de eliminar dispositivo finalizada.";
				removeDevice($_POST['user'], $_POST['deviceMAC']);
				break;

                        case "changeName":
				$report = "El cambio de nombres no está implementado. Lo sentimos";
				break;

                        case "newDevice":
				$report = "Solicitado nuevo dispositivo...";
				$tmp = createDevice($_POST['user'],$_POST['deviceMAC'],$_POST['deviceName']);
				if ($tmp != null) { $report = $tmp; }
				break;

		}
	}
} else if ((isset($_GET['user'])) && (is_numeric($_GET['user']))) {
        $userID = $_GET['user'];
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
	<img id="image_7" src="bgimg.png" />
	<div id="textheader_9"><b>Doormatic</b>: <u><?php print $report;?></u></div>
	<ul id="navbar_6">
		<li><a href="users.php">Usuarios</a></li>
		<li><a href="devices.php">Dispositivos</a></li>
		<li><a href="#"><del>Privilegios</del></a></li>
		<li><a href="log.php">Registros</a></li>
	</ul>
	<div id="div_2">

<?php
// Only show when you are adding a device for a user ;)
if (isset($userID)) {
?>
		<div id="div_4">
<?php
if ($username = getUserNameFromUserID($userID)) {
?>
			<div id="textheader_14">Añadir dispositivo para «<?php print $username;?>»</div>
			<form method="POST">
			<input type="hidden" name="action" value="newDevice"/>
			<input type="text" name="user" id="texteditbox_11" value="<?php print $userID;?>" placeholder="ID de usuario" readonly=yes />
			<input type="text" name="deviceMAC" id="texteditbox_12" placeholder="Dirección MAC"/>
			<input type="text" id="texteditbox_13" name="deviceName" placeholder="Nombre de dispositivo"/>
			<input type="submit" id="button_15" />
			</form>
<?php
} else {
?>
			<div id="textheader_14">Usuario no encontrado...</div>
<?php
}
?>
		</div>
<?php
}
?>
		<!--<div id="div_3">-->
			<table border = "1px" cellspacing = "0px" id = "table_8">
				<tr>
					<th>Fecha de alta</th>
					<th>Propietario/a</th>
					<th>Dirección MAC</th>
					<th>Nombre dispositivo</th>
					<th>Acciones</th>
				</tr>
<?php

if (isset($userID)) {
        $devices = listDevices($userID);
} else {
        $devices = listDevices();
}

if (count($devices) > 0) {
	foreach ($devices as $devicetmp) {

	if ($trBgColor != "trrow_white") {
	        $trBgColor = "trrow_white";
	} else {
	        $trBgColor = "trrow_grey";	        
	}
?>
				<tr id="<?php print $trBgColor;?>">
					<td><?php print $devicetmp['creation_date'];?></td>
					<td><?php print $devicetmp['person_id'];?></td>
					<td><?php print $devicetmp['mac_address'];?></td>
					<td><?php print $devicetmp['name'];?></td>
					<td align="center">
						<form method="POST">
							<input type="hidden" name="action" value="removeDevice"/>
							<input type="hidden" name="user" value="<?php print $devicetmp['person_id'];?>" />
							<input type="hidden" name="deviceMAC" value="<?php print $devicetmp['mac_address'];?>" />
							<input type="submit" value="Eliminar"/>
						</form>

					</td>
				</tr>
				<?php
	  }
	}
?>
			</table>
		<!--</div>-->
	</div>
</div>

</body>
</html>
