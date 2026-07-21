<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	$id = $_SESSION['id_u'];

	if (!empty($_POST['nombre']) && !empty($_POST['nombre2']) && !empty($_POST['tipo']) && !empty($_POST['cargo']) && !empty($_POST['oficina']) && !empty($_POST['tel']) && !empty($_POST['indice']))
	{
		$nombre = clean(mysqli_real_escape_string($link,$_POST['nombre']));
		$apellido = clean(mysqli_real_escape_string($link,$_POST['nombre2']));
		$tipo = clean(mysqli_real_escape_string($link,$_POST['tipo']));
		$oficina = clean(mysqli_real_escape_string($link,$_POST['oficina']));
		$cargo = clean(mysqli_real_escape_string($link,$_POST['cargo']));
		$tel = clean(mysqli_real_escape_string($link,$_POST['tel']));
		$indice =  clean(mysqli_escape_string($link,$_POST['indice']));
		$fecha_r = date('y-m-d');

		//imagen de perfil
        $url = "https://api.genderize.io?name=" . urlencode($nombre);
        $context = stream_context_create(['http' => ['timeout' => 3], 'https' => ['timeout' => 3]]);
        $response = @file_get_contents($url, false, $context);
        $data = $response !== false ? json_decode($response, true) : null;
        $genero = is_array($data) ? ($data['gender'] ?? '') : '';

		//insertar en la tabla phonebook
		$query = mysqli_query($link,"INSERT INTO tbla_book_phonebook VALUES (0,'$nombre','$apellido','$tel','$indice','$tipo','$cargo','$oficina',1,'$fecha_r','$genero')") or die(mysqli_error($link));

		echo "bien";
	}
	else{
		echo "mal";
	}
?>