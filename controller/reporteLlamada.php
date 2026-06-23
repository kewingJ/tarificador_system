<?php 
	require_once('../includes/config.php');
    include_once '../includes/security.php';

    if(!empty($_GET['direccion']) && !empty($_GET['rangoFecha']))
    {
        $direccion = clean(mysqli_real_escape_string($link,$_GET['direccion']));
        $rangoFecha = clean(mysqli_real_escape_string($link,$_GET['rangoFecha']));
        $operador = clean(mysqli_real_escape_string($link,$_GET['operador']));
        $extension = clean(mysqli_real_escape_string($link,$_GET['extension']));
        //$telefono = clean(mysqli_real_escape_string($link,$_GET['telefono']));
        //echo $extension.' - '.$rangoFecha;

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

        //echo $fechaInicio.'<br>'.$fechaFin;

        ini_set("pcre.jit", "0");
        require_once('../tcpdf/tcpdf.php');

        $usuario = 'SELECT * FROM cdr INNER JOIN cdr_espejo ON cdr.id = cdr_espejo.id_cdr';  
        $usuarios=$link->query($usuario);
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

        //optener datos de cuadros
        $totalAtendidas = 0;
        $contE = 0;
        $contS = 0;
        $tiempo_en_segundos = 0;
        $tiempo_en_segundosUno = 0;
        $tiempo_en_segundosDos = 0;
        $tiempo_en_segundosTres = 0;
        $tiempo_en_segundosCuatro = 0;

        if($direccion == 'Entrante'){
            $filtro = "";
            if(!empty($operador)){
                $filtro .= " AND operador = '$operador'";
            }

            if(!empty($extension)){
                $filtro .= " AND destino = '$extension'";
            }
                                    
            $consulta = mysqli_query($link,"SELECT * FROM cdr_espejo
                                    WHERE cdr_espejo.fecha_llamada BETWEEN '$fechaInicio' AND '$fechaFin' $filtro");
        } else if($direccion == 'Saliente'){
            $filtro = "";
            if(!empty($operador)){
                $filtro .= " AND operador = '$operador'";
            }

            if(!empty($extension)){
                $filtro .= " AND origen = '$extension'";
            }

            $consulta = mysqli_query($link,"SELECT * FROM cdr_espejo
                                    WHERE cdr_espejo.fecha_llamada BETWEEN '$fechaInicio' AND '$fechaFin' $filtro");
        }

        while ($row = mysqli_fetch_array($consulta)) 
        {

            //esto es para calcular el total de llamadas entrantes
            $destino = $row['destino'];
            $origen = $row['origen'];

            //
            $id_cdr = $row['id_cdr'];
            $consultaCdr = mysqli_query($link,"SELECT * FROM cdr 
                                    WHERE cdr.id = '$id_cdr'");
            $rowCdr = mysqli_fetch_array($consultaCdr);

            $tiempo_en_segundosUno += $rowCdr['billsec'];

            $contE++;
        }

        $hora_texto = gmdate("H:i:s", $tiempo_en_segundosUno);

        $content .= '
                    <!-- EXAMPLE OF CSS STYLE -->
                    <style>
                        table.first {
                            color: #003300;
                            font-family: helvetica;
                            font-size: 8pt;
                        }
                        td {
                        }
                        td.second {
                            border: 2px dashed green;
                        }
                    </style>

                    <h1 style="text-align:center;">Direccion : '.$direccion.'</h1>


                    <table class="first" cellpadding="7" cellspacing="6">
                        <tr>
                            <td align="center" bgcolor="#004e92" color="#FFFFFF">
                                <b>Total llamadas : '.$contE.'</b>
                            </td>
                            <td align="center" bgcolor="#004e92" color="#FFFFFF">
                                <b>Tiempo total consumido : '.$hora_texto.'</b>
                            </td>
                        </tr>
                    </table>
                    
                    ';

        $fecha = Date('d/m/Y');

        $content .= '
        <div class="row">
            <div class="col-md-12">        
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
        while ($user=$usuarios->fetch_assoc()) {
            if($direccion == 'Entrante'){
                $condicion = true;
                if (!empty($extension)) {
                    $condicion = $condicion && ($user["dst"] == $extension || $user["userfield"] == $extension);
                }
                
                // operador
                if (!empty($operador)) {
                    $condicion = $condicion && ($user["operador"] == $operador);
                }

                if($condicion && (($user['fecha_llamada'] >= $fechaInicio) 
                    && ($user['fecha_llamada'] <= $fechaFin))){ 
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

                    $destino = $user['destino'];
                    if(!empty($user['userfield'])){
                        $destino = $user['userfield'];
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
                            <small>'.$destino.'</small>
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
            } else if($direccion == 'Saliente'){
                $condicion = true;
                if (!empty($extension)) {
                    $condicion = $condicion && ($user["origen"] == $extension);
                }
                
                // operador
                if (!empty($operador)) {
                    $condicion = $condicion && ($user["operador"] == $operador);
                }

                if($condicion && (($user['fecha_llamada'] >= $fechaInicio) 
                    && ($user['fecha_llamada'] <= $fechaFin))){ 
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

                    $destino = $user['destino'];
                    if(!empty($user['userfield'])){
                        $destino = $user['userfield'];
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
                            <small>'.$destino.'</small>
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
        }
        
        $content .= '</table>';
    
        $pdf->writeHTML($content, true, 0, true, 0);

        $pdf->lastPage();
        ob_end_clean();
        $pdf->output('reporteOperador.pdf', 'I');

        echo "bien";
    } else {
        echo "mal";
    }