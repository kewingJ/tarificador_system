<?php
	session_start();
	if(!empty($_POST['id_prefijo']))
	{
		include_once '../includes/config.php';
		include_once '../includes/security.php';

		$id_prefijo = $_POST['id_prefijo'];
		
		//eliminamos al prefijo
		$query = mysqli_query($link,"UPDATE prefijos SET activo_p = 0 WHERE id_prefijo = '$id_prefijo'") or die(mysql_error());
		echo "bien";
	}
	else {
		echo "mal";
		exit;
	}
?>