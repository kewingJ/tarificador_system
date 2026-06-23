<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	session_start();
	$id = $_SESSION['id_u'];

	if (!empty($_POST['id_operador']) && !empty($_POST['nombre']) && !empty($_POST['costo1']) && !empty($_POST['costo2'])) 
	{
		$id_operador = $_POST['id_operador'];
		$nombre = clean(mysqli_real_escape_string($link,$_POST['nombre']));
		$costo1 = clean(mysqli_real_escape_string($link,$_POST['costo1']));
        $costo2 = clean(mysqli_real_escape_string($link,$_POST['costo2']));
		
		$query = mysqli_query($link,"UPDATE costo SET operador = '$nombre',
													 costo = '$costo1',
													 costo_venta = '$costo2'
													 WHERE id_costo = '$id_operador'") or die(mysqli_error($link));
		echo "bien";
	}
	else{
		echo "mal";
	}
?>