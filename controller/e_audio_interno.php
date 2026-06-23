<?php
	session_start();
	if(!empty($_POST['tipo_audio']))
	{
		include_once '../includes/config.php';
		include_once '../includes/security.php';

		$tipo_audio = $_POST['tipo_audio'];
        $query = mysqli_query($link,"SELECT * FROM audios_llamadas WHERE audios_llamadas.tipo_audio = '$tipo_audio'");
		while($row = mysqli_fetch_array($query))
        {
            //eliminar archivo desde la carpeta
			if(!empty($row['url_audio']))
			{
				unlink('../'.$row['url_audio']);
			}
        }
        //eliminamos audios entrantes
		$query = mysqli_query($link,"DELETE FROM audios_llamadas WHERE tipo_audio = '$tipo_audio'") or die(mysqli_error($link));
        echo "bien";
	}
	else {
		echo "mal";
		exit;
	}
?>