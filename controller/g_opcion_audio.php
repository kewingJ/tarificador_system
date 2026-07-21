<?php
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	include_once '../includes/config.php';
	include_once '../includes/security.php';

	if (!empty($_POST['opc'])) {
		
		$opcion = clean(mysqli_real_escape_string($link,$_POST['opc']));
		
        $query = mysqli_query($link,"UPDATE opcion_audio SET opcion = '$opcion' WHERE id_opcion = 1") or die(mysqli_error($link));
		
        echo "bien";
	} else {
		echo "mal";
	}
?>