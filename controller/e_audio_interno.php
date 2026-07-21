<?php
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	if(!empty($_POST['tipo_audio']))
	{
		include_once '../includes/config.php';
		include_once '../includes/security.php';

		$tipo_audio = (string) $_POST['tipo_audio'];
		$audioDir = realpath(__DIR__ . '/../audio');

		$stmt = mysqli_prepare($link, "SELECT * FROM audios_llamadas WHERE audios_llamadas.tipo_audio = ?");
		mysqli_stmt_bind_param($stmt, 's', $tipo_audio);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row = mysqli_fetch_array($result))
        {
            //eliminar archivo desde la carpeta
			if(!empty($row['url_audio']))
			{
				$filePath = realpath(__DIR__ . '/../' . $row['url_audio']);
				if ($filePath !== false && $audioDir !== false && strpos($filePath, $audioDir . DIRECTORY_SEPARATOR) === 0) {
					unlink($filePath);
				}
			}
        }
        mysqli_stmt_close($stmt);

        //eliminamos audios entrantes
        $stmtDel = mysqli_prepare($link, "DELETE FROM audios_llamadas WHERE tipo_audio = ?");
        mysqli_stmt_bind_param($stmtDel, 's', $tipo_audio);
        mysqli_stmt_execute($stmtDel);
        mysqli_stmt_close($stmtDel);
        echo "bien";
	}
	else {
		echo "mal";
		exit;
	}
?>