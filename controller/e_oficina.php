<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';

	session_start();
	$id = $_SESSION['id_u'];

	if (!empty($_POST['id_oficina'])) 
	{
		$id_oficina = $_POST['id_oficina'];
		
		//eliminar
		$query = mysqli_query($link,"UPDATE tbla_book_oficina SET activo_oficina = 0 WHERE id_oficina = '$id_oficina'") or die(mysqli_error($link));

		echo "bien";
	}
	else{
		echo "mal";
	}
?>