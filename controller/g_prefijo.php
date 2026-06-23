<?php
	session_start();
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	$tipo = $_SESSION['tipo_usuario'];
	if (!empty($_POST['prefijo']) && !empty($_POST['operadora'])) {
		
		$prefijo = clean(mysqli_real_escape_string($link,$_POST['prefijo']));
		$operadora = clean(mysqli_real_escape_string($link,$_POST['operadora']));
		
		//guardamos en prefijo
		$query = mysqli_query($link,"INSERT INTO prefijos VALUES(0,'$prefijo','$operadora',1)") or die(mysql_error($link));

		echo "bien";
	} else {
		echo "mal";
	}
?>