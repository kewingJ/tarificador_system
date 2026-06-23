<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	session_start();
	$id = $_SESSION['id_u'];

	if (!empty($_POST['nombre']) && !empty($_POST['id_cargo'])) 
	{
		$id_cargo = $_POST['id_cargo'];
		$nombre = clean(mysqli_real_escape_string($link,$_POST['nombre']));

		//
		$query = mysqli_query($link,"UPDATE tbla_book_cargo SET nombre_cargo = '$nombre'
													 WHERE id_cargo = '$id_cargo'") or die(mysqli_error($link));
		echo "bien";
	}
	else{
		echo "mal";
	}
?>