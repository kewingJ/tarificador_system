<?php 
	require_once('../includes/config.php');
    include_once '../includes/security.php';

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    if(!empty($_GET['extension']) && !empty($_GET['rangoFecha']))
    {
        $extension = clean(mysqli_real_escape_string($link,$_GET['extension']));
        $rangoFecha = clean(mysqli_real_escape_string($link,$_GET['rangoFecha']));
        $telefono = clean(mysqli_real_escape_string($link,$_GET['telefono']));
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

        $consulta = mysqli_query($link,"SELECT * FROM cdr 
                                    WHERE cdr.dst = '$extension' 
                                    AND CAST(cdr.calldate AS DATE) BETWEEN '$fechaInicio' AND '$fechaFin'");
        while ($row = mysqli_fetch_array($consulta)) 
        {
            $estado = $row['disposition'];
            if ($estado == 'ANSWERED') {
                $totalAtendidas++;
            }

            //esto es para calcular el total de llamadas entrantes
            $destino = $row['dst'];
            $origen = $row['src'];

            if (!empty($destino)) {
                $aux = $destino[0];
            }
            
            if ($aux == 9) {
                $prefijo1 = substr($destino, 1, 4);
            } else {
                $prefijo1 = substr($destino, 0, 3);
            }

            //aqui verificamos a que operador pertenece
            $consulta2 = mysqli_query($link,"SELECT prefijos.operador FROM prefijos 
                WHERE prefijos.prefijo = '$prefijo1'");
            $row2 = mysqli_fetch_array($consulta2);
            if($row2){
                $operador = $row2['operador'];
            } else {
                $operador = "";
            }


            if (strlen($origen) > strlen($destino) && strlen($origen) >= 6) {
                $contE++;
            } else if(strlen($origen) <= strlen($destino) && strlen($destino) >= 6){
                $contS++;
            }

            $tiempo_en_segundos += $row['billsec'];

            if ($operador === 'Claro') {
                $tiempo_en_segundosUno += $row['billsec'];
            } else if ($operador === 'Tigo') {
                $tiempo_en_segundosDos += $row['billsec'];
            } else if ($operador === 'Cootel') {
                $tiempo_en_segundosTres += $row['billsec'];
            } else if ($operador === 'Convencional') {
                $tiempo_en_segundosCuatro += $row['billsec'];
            }

        }

        $hora_texto = gmdate("H:i:s", $tiempo_en_segundos);
        
        $hora_texto1 = gmdate("H:i:s", $tiempo_en_segundosUno);

        $hora_texto2 = gmdate("H:i:s", $tiempo_en_segundosDos);

        $hora_texto3 = gmdate("H:i:s", $tiempo_en_segundosTres);

        $hora_texto4 = gmdate("H:i:s", $tiempo_en_segundosCuatro);


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

                    <h1 style="text-align:center;">Extension : '.$extension.'</h1>


                    <table class="first" cellpadding="7" cellspacing="6">
                        <tr>
                            <td align="center" bgcolor="#004e92" color="#FFFFFF">
                                <b>Total llamadas atendidas : '.$totalAtendidas.'</b>
                            </td>
                            <td align="center" bgcolor="#004e92" color="#FFFFFF">
                                <b>Total llamadas salientes : '.$contS.'</b>
                            </td>
                            <td align="center" bgcolor="#004e92" color="#FFFFFF">
                                <b>Tiempo total consumido : '.$hora_texto.'</b>
                            </td>
                        </tr>
                    </table>
                    
                    <table class="first" cellpadding="5" cellspacing="2">
                        <tr>
                            <td align="center" bgcolor="#ea384d" color="#FFFFFF">
                                <b>Tiempo total por claro : '.$hora_texto1.'</b>
                            </td>

                            <td align="center" bgcolor="#add100" color="#FFFFFF">
                                <b>Tiempo total por tigo : '.$hora_texto2.'</b>
                            </td>

                            <td align="center" bgcolor="#eea849" color="#FFFFFF">
                                <b>Tiempo total por cootel : '.$hora_texto3.'</b>
                            </td>

                            <td align="center" bgcolor="#00d2ff" color="#FFFFFF">
                                <b>Tiempo total por convencional : '.$hora_texto4.'</b>
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
            if(($user['dst'] == $extension || $user['userfield'] == $extension)
                && (($user['calldate'] >= $fechaInicio) 
                && ($user['calldate'] <= $fechaFin))){ 
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
        
        $content .= '</table>';
    
        $pdf->writeHTML($content, true, 0, true, 0);

        $pdf->lastPage();
        // ob_end_clean();
        $pdf->output('reporteExtencion.pdf', 'I');

        echo "bien";
    } else {
        echo "mal";
    }