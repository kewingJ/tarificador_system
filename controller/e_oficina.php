<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';

	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	$id = $_SESSION['id_u'];

	if (!empty($_POST['id_oficina'])) 
	{
		$id_oficina = (int) $_POST['id_oficina'];

		//eliminar
		$stmt = mysqli_prepare($link, "UPDATE tbla_book_oficina SET activo_oficina = 0 WHERE id_oficina = ?");
		mysqli_stmt_bind_param($stmt, 'i', $id_oficina);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		echo "bien";
	}
	else{
		echo "mal";
	}
?>