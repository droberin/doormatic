<?php
  if (!isset($user)) {
    die("File should be INCLUDED not REQUESTED");
  }
?>
<body  style="overflow-y:scroll;">

<div id="Content">

  <div id="mainContent">

    <p style="text-align:center;"><img style="margin-top: 4px; margin-bottom: -10px;" src="img/smalldragon.png" alt="你好, 龙 [Ni hao, Lóng]" /></p>
<?php
  if (isset($doorlog)) {
?>
      <p style="text-align:center;"><small><?php print $doorlog?></small></p>
<?php
  }
?>
    <div id="loginForm" class="alist">
    <p><a href="#" onClick="toggle_visibility('loginRequest')">Solicitar autorización</a></p>
    <p style="text-align:center;">¿Deseas entrar?<br /> <small>Grandes aventuras aguardan...</small></p>
    <form action="" method="POST" autocomplete="on" style="text-align: center;">
      <input type="hidden" name="action" value="open" />
      <input type="hidden" name="door" value="main" />
      <input type="hidden" name="duration" value="3" />
      <input style="width:80%;" type="text" name="UserName" placeholder="Usuario" value="" /> <br />
      <input style="width:80%;" type="password" name="Passwd" placeholder="Contraseña" value="" /> <br />
      <input style="width:80%;" type="submit" name="doormatic" value="Abre la cueva" />
    </form>
    </div>
    <div id="loginRequest" style="display:none" class="alist" >
    <p><a href="#" onClick="toggle_visibility('loginForm')">¡Me he equivocado! ¡Yo ya tengo clave!</a></p>
    <p style="text-align:center;">¿Aún sin acceso? Rellena esta solicitud. <br /> <small>Si la puerta deseas cruzar el timbre tendrás que tocar.</small></p>
    <form action="" method="POST" autocomplete="on" style="text-align: center;">
      <input type="hidden" name="action" value="register" />
      <input type="text" name="FirstName" placeholder="Nombre" spellcheck="False"/>
      <input type="text" name="LastName" placeholder="Apellido" spellcheck="False"/>
      <input type="text" name="UserName" placeholder="Usuario" /> <br />
      <input type="text" name="Email" placeholder="Email" /> <br />
      <!-- <input type="password" name="Passwd" placeholder="Contraseña" disabled="disabled" value="" /> <br />-->
      <input type="submit" name="doormatic" value="Solicitar clave" /> <br />
    </form>
    </div>


  </div>
