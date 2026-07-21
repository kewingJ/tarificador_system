<?php
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	require_csrf();
	if(!empty($_POST['id_usuario']))
	{
		include_once '../includes/config.php';
		include_once '../includes/security.php';

		$id_usuario = (int) $_POST['id_usuario'];

		//eliminamos al usuario
		$stmt = mysqli_prepare($link, "UPDATE usuario SET activo_u = 0 WHERE id_usuario = ?");
		mysqli_stmt_bind_param($stmt, 'i', $id_usuario);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		echo "bien";
	}
	else {
		echo "mal";
	}
?>