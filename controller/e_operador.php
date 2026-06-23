<?php
	session_start();
	if(!empty($_POST['id_operador']))
	{
		include_once '../includes/config.php';
		include_once '../includes/security.php';

		$id_operador = $_POST['id_operador'];

        $query1 = mysqli_query($link,"DELETE FROM costo WHERE id_costo = '$id_operador'") or die(mysqli_error($link));

		echo "bien";
	}
	else {
		echo "mal";
	}
?>