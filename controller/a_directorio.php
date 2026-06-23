<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	session_start();
	$id = $_SESSION['id_u'];

	if (!empty($_POST['id_phonebook']) && !empty($_POST['nombre']) && !empty($_POST['nombre2']) && !empty($_POST['tel'])) 
	{
		$id_phonebook = $_POST['id_phonebook'];
		$nombre = clean(mysqli_real_escape_string($link,$_POST['nombre']));
		$apellido = clean(mysqli_real_escape_string($link,$_POST['nombre2']));
		$tel = clean(mysqli_real_escape_string($link,$_POST['tel']));
		$indice = clean(mysqli_real_escape_string($link,$_POST['indice']));
		$tipo = clean(mysqli_escape_string($link,$_POST['tipo']));

		//imagen de perfil
        $url = "https://api.genderize.io?name=" . urlencode($nombre);
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        $genero = $data['gender'];
		
		if (empty($tipo)) {
			$query = mysqli_query($link,"UPDATE tbla_book_phonebook SET first_name = '$nombre',
													 last_name = '$apellido',
													 phone_number = '$tel',
													 account_index = '$indice',
													 genero = '$genero'
													 WHERE id_phonebook = '$id_phonebook'") or die(mysqli_error($link));
		} else{
			$query = mysqli_query($link,"UPDATE tbla_book_phonebook SET first_name = '$nombre',
													 last_name = '$apellido',
													 phone_number = '$tel',
													 account_index = '$indice',
													 genero = '$genero',
													 type = '$tipo'
													 WHERE id_phonebook = '$id_phonebook'") or die(mysqli_error($link));
		}
		echo "bien";
	}
	else{
		echo "mal";
	}
?>