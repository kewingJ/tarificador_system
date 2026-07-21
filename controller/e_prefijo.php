<?php
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	if(!empty($_POST['id_prefijo']))
	{
		include_once '../includes/config.php';
		include_once '../includes/security.php';

		$id_prefijo = (int) $_POST['id_prefijo'];

		//eliminamos al prefijo
		$stmt = mysqli_prepare($link, "UPDATE prefijos SET activo_p = 0 WHERE id_prefijo = ?");
		mysqli_stmt_bind_param($stmt, 'i', $id_prefijo);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		echo "bien";
	}
	else {
		echo "mal";
		exit;
	}
?>