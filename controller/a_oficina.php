<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';

	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	$id = $_SESSION['id_u'];

	if (!empty($_POST['nombre']) && !empty($_POST['id_oficina'])) 
	{
		$id_oficina = $_POST['id_oficina'];
		$nombre = clean(mysqli_real_escape_string($link,$_POST['nombre']));

		//
		$query = mysqli_query($link,"UPDATE tbla_book_oficina SET nombre_oficina = '$nombre'
													 WHERE id_oficina = '$id_oficina'") or die(mysqli_error($link));
		echo "bien";
	}
	else{
		echo "mal";
	}
?>