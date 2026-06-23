<?php 
	require_once('../includes/config.php');
	require_once('../tcpdf/tcpdf.php');
    include_once '../includes/security.php';
    ini_set("pcre.jit", "0");

    //optener rango de fecha
    $rangoFecha = clean(mysqli_real_escape_string($link,$_GET['rangoFechaGeneral']));
    $tipo_reporte = clean(mysqli_real_escape_string($link,$_GET['tipo_reporte']));
    $tipo_ordenamiento = clean(mysqli_real_escape_string($link,$_GET['tipo_ordenamiento']));
    $optradio = clean(mysqli_real_escape_string($link,$_GET['optradio']));

    //separar fechas y dar formatos
    $rangoFechaInicio = explode('-', $rangoFecha);
    $fechaInicio = explode(' ', $rangoFechaInicio[0]);
    $fechaInicio = trim($fechaInicio[0]);

    $date = new DateTime($fechaInicio);
    $fechaInicio = $date->format('Y-m-d');

    $rangoFechaFin = explode('-', $rangoFecha);
    $fechaFin = explode(' ', $rangoFechaFin[1]);
    $fechaFin = trim($fechaFin[1]);

    $date = new DateTime($fechaFin);
    $fechaFin = $date->format('Y-m-d');

	class MYPDF extends TCPDF {

		public function Header() {
            // Fuente
            $this->SetFont('helvetica', 'A', 10);
            // Fecha
            $this->Cell(0, 18, date('d/m/Y'), 0, false, 'R', 0, '', 0, false, 'T', 'M'); 
        }


       	public function Footer() {
           $image_file = "pie.jpg";
           $this->Image($image_file, 0, 281, 220, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
       	}

	}
	

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('');
	$pdf->SetTitle('');

    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

	$pdf->SetAutoPageBreak(true, 20); 
	$pdf->SetFont('Helvetica', '', 10);

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->setFontSubsetting(true);

    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	$pdf->addPage();

	$content = '';

	$fecha = Date('d/m/Y');
	
	$content .= '
		<div class="row">
        	<div class="col-md-12">
            	<h1 style="text-align:center;">Reporte General</h1>
       	
      <table border="0" 
             cellspacing="1" 
             cellmargin="0" 
             cellpadding="2"
             style="border-collapse: collapse; margin:1px; border:1px solid #eee;">
        <thead>
          <tr bgcolor="#d5ce07" nobr="true">
           	<th style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
           		<small>Id</small>
           	</th>
           	<th style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
            	<small>Nombre</small>
            </th>
           	<th style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
            	<small>Origen</small>
            </th>
           	<th style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
            	<small>Destino</small>
            </th>
           	<th style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
            	<small>Estado</small>
            </th>
           	<th style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
            	<small>Fecha Llamada</small>
            </th>
           	<th style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
            	<small>Hora Llamada</small>
            </th>
           	<th style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
            	<small>Costo</small>
            </th>
           	<th style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
            	<small>Duracion</small>
            </th>
           	<th style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
            	<small>Operador</small>
            </th>
          </tr>
        </thead>
	';
	$i = 1;
	if($tipo_reporte == 'general') {
        //reporte de manera general
        if ($tipo_ordenamiento == 'fecha') {
            if ($optradio == 'DESC') {
                $usuario = mysqli_query($link,"SELECT * FROM cdr_espejo ORDER BY cdr_espejo.fecha_llamada DESC");
            } else {
                $usuario = mysqli_query($link,"SELECT * FROM cdr_espejo ORDER BY cdr_espejo.fecha_llamada ASC");
            }
        } else {
            if ($optradio == 'DESC') {
                $usuario = mysqli_query($link,"SELECT * FROM cdr_espejo ORDER BY cdr_espejo.duracion DESC");
            } else {
                $usuario = mysqli_query($link,"SELECT * FROM cdr_espejo ORDER BY cdr_espejo.duracion ASC");
            }
        }
        while ($user = mysqli_fetch_array($usuario)) {

			$estado = $user['estado'];
			$StringEstado = '';
			$colorEstado = '';

			if ($estado == 'ANSWERED') {
				$StringEstado = 'Contestada';
				$colorEstado = 'green';
			} else {
				$StringEstado = 'No Contestada';
				$colorEstado = 'red';
			}
			$content .= '
			<tr bgcolor="" nobr="true">
           		<td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
	            	<small>'.$i.'</small>
	            </td>
           		<td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
	            	<small>'.$user['nombre'].'</small>
	            </td>
           		<td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
	            	<small>'.$user['origen'].'</small>
	            </td>
           		<td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
	            	<small>'.$user['destino'].'</small>
	            </td>
           		<td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
	            	<small><font color="'.$colorEstado.'">'.$StringEstado.'</font></small>
	            </td>
           		<td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
	            	<small>'.$user['fecha_llamada'].'</small>
	            </td>
           		<td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
	            	<small>'.$user['hora_llamada'].'</small>
	            </td>
           		<td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
	            	<small>'.$user['costo'].'</small>
	            </td>
           		<td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
	            	<small>'.$user['duracion'].'</small>
	           	</td>
           		<td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
	            	<small>'.$user['operador'].'</small>
	            </td>
	        </tr>';
	        $i++;
    	}
	} else {
        //reporte por rango de fecha
        if ($tipo_ordenamiento == 'fecha') {
            if ($optradio == 'DESC') {
                $usuario = mysqli_query($link,"SELECT * FROM cdr_espejo 
                                            WHERE cdr_espejo.fecha_llamada BETWEEN '$fechaInicio' AND '$fechaFin'
                                            ORDER BY cdr_espejo.fecha_llamada DESC");
            } else {
                $usuario = mysqli_query($link,"SELECT * FROM cdr_espejo
                                            WHERE cdr_espejo.fecha_llamada BETWEEN '$fechaInicio' AND '$fechaFin' 
                                            ORDER BY cdr_espejo.fecha_llamada ASC");
            }
        } else {
            if ($optradio == 'DESC') {
                $usuario = mysqli_query($link,"SELECT * FROM cdr_espejo
                                            WHERE cdr_espejo.fecha_llamada BETWEEN '$fechaInicio' AND '$fechaFin' 
                                            ORDER BY cdr_espejo.duracion DESC");
            } else {
                $usuario = mysqli_query($link,"SELECT * FROM cdr_espejo 
                                            WHERE cdr_espejo.fecha_llamada BETWEEN '$fechaInicio' AND '$fechaFin'
                                            ORDER BY cdr_espejo.duracion ASC");
            }
        }
        while ($user = mysqli_fetch_array($usuario)) {

            $estado = $user['estado'];
            $StringEstado = '';
            $colorEstado = '';

            if ($estado == 'ANSWERED') {
                $StringEstado = 'Contestada';
                $colorEstado = 'green';
            } else {
                $StringEstado = 'No Contestada';
                $colorEstado = 'red';
            }
            $content .= '
            <tr bgcolor="" nobr="true">
                <td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
                    <small>'.$i.'</small>
                </td>
                <td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
                    <small>'.$user['nombre'].'</small>
                </td>
                <td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
                    <small>'.$user['origen'].'</small>
                </td>
                <td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
                    <small>'.$user['destino'].'</small>
                </td>
                <td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
                    <small><font color="'.$colorEstado.'">'.$StringEstado.'</font></small>
                </td>
                <td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
                    <small>'.$user['fecha_llamada'].'</small>
                </td>
                <td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
                    <small>'.$user['hora_llamada'].'</small>
                </td>
                <td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
                    <small>'.$user['costo'].'</small>
                </td>
                <td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
                    <small>'.$user['duracion'].'</small>
                </td>
                <td style="border-collapse: collapse; margin:1px; border:1px solid #eee;text-align:center;">
                    <small>'.$user['operador'].'</small>
                </td>
            </tr>';
            $i++;
        }        
    }
	
	$content .= '</table>';
	
	$pdf->writeHTML($content, true, 0, true, 0);

	$pdf->lastPage();
    ob_end_clean();
	$pdf->output('reporte.pdf', 'I');

?>