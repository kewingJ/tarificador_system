<?php
	session_start();
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	if (!empty($_POST['nombre'])) {

		//guardo los datos del usuario
		$nombre = clean(mysqli_real_escape_string($link,$_POST['nombre']));
		$apellido = clean(mysqli_real_escape_string($link,$_POST['apellido']));
		$email = clean(mysqli_real_escape_string($link,$_POST['email']));
		$telefono = clean(mysqli_real_escape_string($link,$_POST['tel']));
		$pass = clean(mysqli_real_escape_string($link,$_POST['pass']));
		$id_cargo = 1; //clean(mysqli_real_escape_string($link,$_POST['id_cargo']));
		$fecha_r = date('Y-m-d');
		$activo = 1;

		//encriptar contraseña
		$opciones = [
			'cost' => 12
		];
		$passw = password_hash($pass,PASSWORD_BCRYPT,$opciones);
				
		$query = mysqli_query($link,"INSERT INTO usuario VALUES (0,'$nombre','$apellido','$email','$telefono','$passw','$id_cargo','$activo','$fecha_r')") or die(mysql_error());
		echo "bien";
	} else {
		echo "mal";
	}
			
		
?>