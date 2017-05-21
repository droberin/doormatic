<?

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
				$report = "El envío de claves de revocación aún no está implementado. Lo sentimos";
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
		<div id="div_3">
			<table border = "1px" cellspacing = "0px" id = "table_8">
				<tr>
					<th>Visto</th>
					<th>Usuario</th>
					<th>Nombre</th>
					<th>email</th>
					<th>Contraseña</th>
					<th>Acciones</th>
				</tr>
<?
$users = listUsers();
if (count($users) > 0) {
	foreach ($users as $usertmp) {
	if ($usertmp['active'] == "0") {
		$switchStatus = "enable";
		$switchCaption = "Activar usuario";
		$trColour = "#FF0000";
	} else {
		$switchStatus = "disable";
		$switchCaption = "Desactivar usuario";
		$trColour = "#BADA55";
	}

?>
				<tr style="background-color:<?=$trColour?>;">
					<td><?=$usertmp['lastseen'];?></td>
					<td><?=$usertmp['username'];?></td>
					<td><?=$usertmp['username'];?></td>
					<td><?=$usertmp['email'];?></td>
					<td align="center">
						<form method="POST">
							<input type="hidden" name="action" value="changePassword"/>
							<input type="hidden" name="user" value="<?=$usertmp['ID'];?>" />
							<input type="password" name="newPassword" value="" />
							<input type="submit" value="Cambiar"/>
						</form>

					</td>
					<td align="center">
						<form method="POST">
							<?
							?>
							<input type="hidden" name="action" value="<?=$switchStatus?>"/>
							<input type="hidden" name="user" value="<?=$usertmp['ID'];?>" />
							<input type="submit" value="<?=$switchCaption?>"/>
						</form>

						<form method="POST">
							<input type="hidden" name="action" value="sendRevocation"/>
							<input type="hidden" name="user" value="<?=$usertmp['ID'];?>" />
							<input type="submit" value="Enviar clave revocación"/>
						</form>

						<form method="GET" action="devices">
							<input type="hidden" name="user" value="<?=$usertmp['ID'];?>" />
							<input type="submit" value="Añadir dispositivo"/>
						</form>
					</td>
				</tr>
				<?
	  }
	}
?>
			</table>
		</div>
		<div id="div_4">
			<form method="POST">
			<input type="hidden" name="action" value="newUser"/>
			<input type="text" name="username" id="texteditbox_11" placeholder="Nombre de usuario"/>
			<input type="text" name="email" id="texteditbox_12" placeholder="Email de contacto / Jabber"/>
			<input type="password" id="texteditbox_13" placeholder="Contraseña" value=""/>
			<p id="textheader_14">Añadir Usuario</p>
			<input type="submit" id="button_15" />
			</form>
		</div>
		<div id="div_5" style="display:none;">
			<button type="button"id="button_17">Buscar</button>
			<button type="button"id="button_18">Todos</button>
			<input type="text" id="texteditbox_19" value="Introduce un nombre/apodo"/>
		</div>
	</div>
	<ul id="navbar_6">
		<li><a href="users">Usuarios</a></li>
		<li><a href="devices">Dispositivos</a></li>
		<li><a href="#">Privilegios (fake)</a></li>
		<li><a href="#">Registros (soon)</a></li>
	</ul>
	<img id="image_7" src="bgimg.gif" />
	<p id="textheader_9"><b>Doormatic</b>: <u><?=$report;?></u></p>
</div>

</body>
</html>
