<?php
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	if(!empty($_POST['id_cdr']))
	{
		include_once '../includes/config.php';
		include_once '../includes/security.php';

		$data = is_array($_POST['id_cdr']) ? $_POST['id_cdr'] : [$_POST['id_cdr']];

        foreach($data as $id_cdr) {
            $id_cdr = (int) $id_cdr;

            //optener operador
            $stmt = mysqli_prepare($link, "SELECT * FROM cdr_espejo WHERE id_cdr = ?");
            mysqli_stmt_bind_param($stmt, 'i', $id_cdr);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = $result ? mysqli_fetch_array($result) : null;
            mysqli_stmt_close($stmt);
            $operador = $row['operador'] ?? null;

            //eliminamos
            $stmtDel1 = mysqli_prepare($link, "DELETE FROM cdr WHERE id = ?");
            mysqli_stmt_bind_param($stmtDel1, 'i', $id_cdr);
            mysqli_stmt_execute($stmtDel1);
            mysqli_stmt_close($stmtDel1);

            $stmtDel2 = mysqli_prepare($link, "DELETE FROM cdr_espejo WHERE id_cdr = ?");
            mysqli_stmt_bind_param($stmtDel2, 'i', $id_cdr);
            mysqli_stmt_execute($stmtDel2);
            mysqli_stmt_close($stmtDel2);

            if(!empty($operador)){
                $consult = mysqli_query($link,"SELECT * FROM estadistica_llamadas") or die(mysqli_error($link));
                $row = mysqli_fetch_array($consult);
                $total_claro        = $row['total_claro'];
                $total_movistar     = $row['total_movistar'];
                $total_cootel       = $row['total_cootel'];
                $total_convencional = $row['total_convencional'];
                switch ($operador) {
                    case 'Claro':
                        $total = $total_claro - 1;
                        $queryU = mysqli_query($link,"UPDATE estadistica_llamadas SET total_claro = '$total'
                                                WHERE id_estadistica = 1") or die(mysqli_error($link));
                        break;
                    case 'Tigo':
                        $total = $total_movistar - 1;
                        $queryU = mysqli_query($link,"UPDATE estadistica_llamadas SET total_movistar = '$total'
                                                WHERE id_estadistica = 1") or die(mysqli_error($link));
                        break;
                    case 'Cootel':
                        $total = $total_cootel - 1;
                        $queryU = mysqli_query($link,"UPDATE estadistica_llamadas SET total_cootel = '$total'
                                                WHERE id_estadistica = 1") or die(mysqli_error($link));
                        break;
                    case 'Convencional':
                        $total = $total_convencional - 1;
                        $queryU = mysqli_query($link,"UPDATE estadistica_llamadas SET total_convencional = '$total'
                                                WHERE id_estadistica = 1") or die(mysqli_error($link));
                        break;
                }
            }
        }
		echo "bien";
	}
	else {
		echo "mal";
	}
?>