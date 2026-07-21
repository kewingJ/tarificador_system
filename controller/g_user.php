<?php
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	require_csrf();
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	if (!empty($_POST['nombre'])) {

		//guardo los datos del usuario
		$nombre = clean($_POST['nombre']);
		$apellido = clean($_POST['apellido']);
		$email = clean($_POST['email']);
		$telefono = clean($_POST['tel']);
		$pass = clean($_POST['pass']);
		$id_cargo = 1;
		$fecha_r = date('Y-m-d');
		$activo = 1;

		//encriptar contraseña
		$opciones = [
			'cost' => 12
		];
		$passw = password_hash($pass,PASSWORD_BCRYPT,$opciones);

		$stmt = mysqli_prepare($link, "INSERT INTO usuario VALUES (0,?,?,?,?,?,?,?,?)");
		mysqli_stmt_bind_param($stmt, 'sssssiis', $nombre, $apellido, $email, $telefono, $passw, $id_cargo, $activo, $fecha_r);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		echo "bien";
	} else {
		echo "mal";
	}
			
		
?>