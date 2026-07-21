<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';

	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	$id = $_SESSION['id_u'];

	if (!empty($_POST['nombre'])) 
	{
		$nombre = clean(mysqli_real_escape_string($link,$_POST['nombre']));
		$fecha_r = date('y-m-d');


		//insertar en la tabla cargo
		$query = mysqli_query($link,"INSERT INTO tbla_book_oficina VALUES (0,'$nombre',1,'$fecha_r')") or die(mysqli_error($link));

		echo "bien";
	}
	else{
		echo "mal";
	}
?>