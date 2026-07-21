<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	$id = $_SESSION['id_u'];

	if (!empty($_POST['id_phonebook'])) 
	{
		$id_phonebook = (int) $_POST['id_phonebook'];
		//eliminar
		$stmt = mysqli_prepare($link, "UPDATE tbla_book_phonebook SET activo_p = 0 WHERE id_phonebook = ?");
		mysqli_stmt_bind_param($stmt, 'i', $id_phonebook);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		echo "bien";
	}
	else{
		echo "mal";
	}
?>