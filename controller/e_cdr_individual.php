<?php
	session_start();
	if(!empty($_POST['id_cdr']))
	{
		include_once '../includes/config.php';
		include_once '../includes/security.php';

		$data = $_POST['id_cdr'];

        foreach($data as $id_cdr) {
            //optener operador
            $consult = mysqli_query($link,"SELECT * FROM cdr_espejo WHERE id_cdr = '$id_cdr'") or die(mysqli_error($link));
            $row = mysqli_fetch_array($consult);
            $operador = $row['operador'];
            //eliminamos
            $query1 = mysqli_query($link,"DELETE FROM cdr WHERE id = '$id_cdr'") or die(mysqli_error($link));

            $query2 = mysqli_query($link,"DELETE FROM cdr_espejo WHERE id_cdr = '$id_cdr'") or die(mysqli_error($link));

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