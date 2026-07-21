<?php
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	include_once '../includes/config.php';
	include_once '../includes/security.php';

	$opcion = $_POST['opc'];

	switch ($opcion) {
		case 1:
			$query = mysqli_query($link,"DELETE FROM cdr LIMIT 50") or die(mysqli_error($link));
			//$query2 = mysqli_query($link,"DELETE FROM cdr_espejo LIMIT 50") or die(mysqli_error($link));
			include_once 'ajax_cdr_espejo.php';
			break;
		case 2:
			$query = mysqli_query($link,"DELETE FROM cdr LIMIT 100") or die(mysqli_error($link));
			//$query2 = mysqli_query($link,"DELETE FROM cdr_espejo LIMIT 100") or die(mysqli_error($link));
			include_once 'ajax_cdr_espejo.php';
			break;
		case 3:
			$query = mysqli_query($link,"DELETE FROM cdr LIMIT 150") or die(mysqli_error($link));
			//$query2 = mysqli_query($link,"DELETE FROM cdr_espejo LIMIT 150") or die(mysqli_error($link));
			include_once 'ajax_cdr_espejo.php';
			break;
		case 4:
			$query = mysqli_query($link,"DELETE FROM cdr LIMIT 200") or die(mysqli_error($link));
			//$query2 = mysqli_query($link,"DELETE FROM cdr_espejo LIMIT 200") or die(mysqli_error($link));
			include_once 'ajax_cdr_espejo.php';
			break;
		case 5:
			$query = mysqli_query($link,"TRUNCATE cdr") or die(mysqli_error($link));
			//$query2 = mysqli_query($link,"TRUNCATE cdr_espejo") or die(mysqli_error($link));
			include_once 'ajax_cdr_espejo.php';
			break;
	}
?>