<?php
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	if(!empty($_POST['id_audio']))
	{
		include_once '../includes/config.php';
		include_once '../includes/security.php';

		$data = is_array($_POST['id_audio']) ? $_POST['id_audio'] : [$_POST['id_audio']];
		$audioDir = realpath(__DIR__ . '/../audio');

		foreach($data as $id_audio) {
			$id_audio = (int) $id_audio;

			$stmt = mysqli_prepare($link, "SELECT * FROM audios_llamadas WHERE audios_llamadas.id_audio = ?");
			mysqli_stmt_bind_param($stmt, 'i', $id_audio);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$row = $result ? mysqli_fetch_array($result) : null;
			mysqli_stmt_close($stmt);

			//eliminar archivo desde la carpeta
			if(!empty($row['url_audio']))
			{
				$filePath = realpath(__DIR__ . '/../' . $row['url_audio']);
				if ($filePath !== false && $audioDir !== false && strpos($filePath, $audioDir . DIRECTORY_SEPARATOR) === 0) {
					unlink($filePath);
				}
			}
			//eliminamos al audio de la base de datos
			$stmtDel = mysqli_prepare($link, "DELETE FROM audios_llamadas WHERE id_audio = ?");
			mysqli_stmt_bind_param($stmtDel, 'i', $id_audio);
			mysqli_stmt_execute($stmtDel);
			mysqli_stmt_close($stmtDel);
		}
		echo "bien";
	}
	else {
		echo "mal";
		exit;
	}
?>