<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	session_start();
	$id = $_SESSION['id_u'];

	if (!empty($_POST['id_phonebook'])) 
	{
		$id_phonebook = $_POST['id_phonebook'];
		//eliminar
		$query = mysqli_query($link,"UPDATE tbla_book_phonebook SET activo_p = 0 WHERE id_phonebook = '$id_phonebook'") or die(mysqli_error());

		echo "bien";
	}
	else{
		echo "mal";
	}
?>