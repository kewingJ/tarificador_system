<?php
	session_start();
	include_once '../includes/config.php';
	include_once '../includes/security.php';

	if (!empty($_POST['id_usuario'])) {
	
		$id_usuario = $_POST['id_usuario'];
		$nombre = clean(mysqli_real_escape_string($link,$_POST['nombre']));
		$apellido = clean(mysqli_real_escape_string($link,$_POST['apellido']));
		$email = clean(mysqli_real_escape_string($link,$_POST['email']));
		$tele = clean(mysqli_real_escape_string($link,$_POST['tel']));
		$pass = clean(mysqli_real_escape_string($link,$_POST['pass']));
	
		if (empty($pass)){
			//actualizamos la informacion del usuario
			$query = mysqli_query($link,"UPDATE usuario SET nombre_u = '$nombre',apellido_u = '$apellido',email_u = '$email',telefono = '$tele' WHERE id_usuario = '$id_usuario'") or die(mysql_error());
		}
		else {
			//encriptar contraseña
			$opciones = [
				'cost' => 12
			];
			$passw = password_hash($pass,PASSWORD_BCRYPT,$opciones);
			
			//actualizamos la informacion del usuario
			$query = mysqli_query($link,"UPDATE usuario SET nombre_u = '$nombre',apellido_u = '$apellido',email_u = '$email',telefono = '$tele',contrasena = '$passw' WHERE id_usuario = '$id_usuario'") or die(mysql_error());
		}

		echo "bien";
	} else {
		echo "mal";
	}
?>