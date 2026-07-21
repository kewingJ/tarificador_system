<?php
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	$tipo = $_SESSION['tipo_usuario'];
	if (!empty($_POST['nombre']) && !empty($_POST['costo1']) && !empty($_POST['costo2'])) {
		
		$nombre = clean(mysqli_real_escape_string($link,$_POST['nombre']));
		$costo1 = clean(mysqli_real_escape_string($link,$_POST['costo1']));
        $costo2 = clean(mysqli_real_escape_string($link,$_POST['costo2']));
		
		//guardamos en operador
		$query = mysqli_query($link,"INSERT INTO costo VALUES(0,'$costo1','$costo2','$nombre')") or die(mysqli_error($link));

		echo "bien";
	} else {
		echo "mal";
	}
?>