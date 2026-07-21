<?php
 	session_start();
 	require_once '../includes/auth_check.php';
 	require_ajax_auth_or_cli();
 	include_once '../includes/config.php';
    include_once '../includes/security.php';
    include_once '../geoIp/geoiploc.php';

    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');

    date_default_timezone_set('America/Managua');
	
	$i = 1;
	$fp = fopen("../fail2ban.log", "r");
	while(!feof($fp)) 
	{
		$linea = fgets($fp);
        if(!empty($linea))
        {
            //
            $data = explode(' ', $linea);
            //fecha de bloqueo
            $fecha_bloqueo = @$data[0];
            $hora = explode(',', @$data[1]);
            $hora_bloqueo = @$hora[0];
            $fecha = '';
            $fecha = $fecha_bloqueo.' '.$hora_bloqueo;

            //echo $fecha.'<br>';

            //optener datos del .log con rango de hora
            $fechaFin = date("Y-m-d H:i:s");
            $fechaInicio = date("Y-m-d H:i:s",strtotime($fechaFin."- 20 minutes"));

            //echo $fechaInicio.'<br>';

            if(!empty($fecha) && $fecha >= $fechaInicio && $fecha <= $fechaFin) 
            {
                //
                $ip_bloqueo = '';
                $tipo_bloqueo = '';
                //tipo bloqueo Uno
                $banderaUno = explode('[ssh-failed]', $linea);
                if(!empty(@$banderaUno[1])) {
                    $dataA = explode('[ssh-failed]', $linea);
                    $dataB = explode(' ', @$dataA[1]);
                    $ip_bloqueo = '';
                    if(@$dataB[2] != 'Ban') {
                        $ip_bloqueo = @$dataB[2];
                    } else {
                        $ip_bloqueo = @$dataB[3];
                    }
                    $tipo_bloqueo = 'ssh brute-force';
                } 

                //tipo bloqueo Dos
                $banderaDos = explode('[asterisk]', $linea);
                if(!empty(@$banderaDos[1])) {
                    $dataA = explode('[asterisk]', $linea);
                    $dataB = explode(' ', @$dataA[1]);
                    $ip_bloqueo = '';
                    if(@$dataB[2] != 'Ban') {
                        $ip_bloqueo = @$dataB[2];
                    } else {
                        $ip_bloqueo = @$dataB[3];
                    }
                    $tipo_bloqueo = 'Sip brute-force';
                }

                //tipo bloqueo tres
                $banderaTres = explode('[sshd]', $linea);
                if(!empty(@$banderaTres[1])) {
                    $dataA = explode('[sshd]', $linea);
                    $dataB = explode(' ', @$dataA[1]);
                    $ip_bloqueo = '';
                    if(@$dataB[2] != 'Ban') {
                        if(@$dataB[2] != 'already') {
                            $ip_bloqueo = @$dataB[2];
                        } else {
                            $ip_bloqueo = @$dataB[1];
                        }
                    } else {
                        $ip_bloqueo = @$dataB[3];
                    }
                    $tipo_bloqueo = 'ssh brute-force';
                }

                if(!empty($ip_bloqueo) && !empty($tipo_bloqueo) && !empty($fecha)){
                        $fecha_r = date('y-m-d');
                        $guardar = mysqli_query($link,"INSERT INTO bloqueo_ataques VALUES (0,'$fecha','$ip_bloqueo','$tipo_bloqueo','$fecha_r')");
                        //echo $fecha.'<br>';
                        //echo $ip_bloqueo.'<br>';
                        //echo $tipo_bloqueo.'<br>';
                }
            }
        }
    }
    fclose($fp);
?>