<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	session_start();
	$id = $_SESSION['id_u'];

	if (!empty($_POST['id_cargo'])) 
	{
		$id_cargo = $_POST['id_cargo'];
		
		//eliminar
		$query = mysqli_query($link,"UPDATE tbla_book_cargo SET activo_cargo = 0 WHERE id_cargo = '$id_cargo'") or die(mysqli_error($link));

		echo "bien";
	}
	else{
		echo "mal";
	}
?>