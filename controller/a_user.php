<?php
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	require_csrf();
	include_once '../includes/config.php';
	include_once '../includes/security.php';

	if (!empty($_POST['id_usuario'])) {

		$id_usuario = (int) $_POST['id_usuario'];
		$nombre = clean($_POST['nombre']);
		$apellido = clean($_POST['apellido']);
		$email = clean($_POST['email']);
		$tele = clean($_POST['tel']);
		$pass = clean($_POST['pass']);

		if (empty($pass)){
			//actualizamos la informacion del usuario
			$stmt = mysqli_prepare($link, "UPDATE usuario SET nombre_u = ?, apellido_u = ?, email_u = ?, telefono = ? WHERE id_usuario = ?");
			mysqli_stmt_bind_param($stmt, 'ssssi', $nombre, $apellido, $email, $tele, $id_usuario);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		else {
			//encriptar contraseña
			$opciones = [
				'cost' => 12
			];
			$passw = password_hash($pass,PASSWORD_BCRYPT,$opciones);

			//actualizamos la informacion del usuario
			$stmt = mysqli_prepare($link, "UPDATE usuario SET nombre_u = ?, apellido_u = ?, email_u = ?, telefono = ?, contrasena = ? WHERE id_usuario = ?");
			mysqli_stmt_bind_param($stmt, 'sssssi', $nombre, $apellido, $email, $tele, $passw, $id_usuario);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}

		echo "bien";
	} else {
		echo "mal";
	}
?>