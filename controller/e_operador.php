<?php
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	if(!empty($_POST['id_operador']))
	{
		include_once '../includes/config.php';
		include_once '../includes/security.php';

		$id_operador = (int) $_POST['id_operador'];

        $stmt = mysqli_prepare($link, "DELETE FROM costo WHERE id_costo = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id_operador);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

		echo "bien";
	}
	else {
		echo "mal";
	}
?>