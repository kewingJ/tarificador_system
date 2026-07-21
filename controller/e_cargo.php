<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	$id = $_SESSION['id_u'];

	if (!empty($_POST['id_cargo'])) 
	{
		$id_cargo = (int) $_POST['id_cargo'];

		//eliminar
		$stmt = mysqli_prepare($link, "UPDATE tbla_book_cargo SET activo_cargo = 0 WHERE id_cargo = ?");
		mysqli_stmt_bind_param($stmt, 'i', $id_cargo);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		echo "bien";
	}
	else{
		echo "mal";
	}
?>