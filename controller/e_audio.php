<?php
	session_start();
	if(!empty($_POST['id_audio']))
	{
		include_once '../includes/config.php';
		include_once '../includes/security.php';

		$data = $_POST['id_audio'];

		foreach($data as $id_audio) {
			$query = mysqli_query($link,"SELECT * FROM audios_llamadas WHERE audios_llamadas.id_audio = '$id_audio'");
			$row = mysqli_fetch_array($query);
			
			//eliminar archivo desde la carpeta
			if(!empty($row['url_audio']))
			{
				unlink('../'.$row['url_audio']);
			}
			//eliminamos al audio de la base de datos
			$query2 = mysqli_query($link,"DELETE FROM audios_llamadas WHERE id_audio = '$id_audio'") or die(mysqli_error($link));
		}
		echo "bien";
	}
	else {
		echo "mal";
		exit;
	}
?>