<?php
	require("../includes/config.php");
  require("../includes/security.php");

	$fecha1 = $_POST['fecha1'];
	$fecha2 = $_POST['fecha2'];
?>

<script>
            $(document).ready(function(){
                Highcharts.chart('container5', {
                      chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                      },
                      title: {
                        text: 'Grafico de tipos de ataques'
                      },
                      tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                      },
                      plotOptions: {
                        pie: {
                          allowPointSelect: true,
                          cursor: 'pointer',
                          dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                          }
                        }
                      },
                      series: [{
                        name: '',
                        colorByPoint: true,
                        data: [
                        <?php
                            $consult = mysqli_query($link,"SELECT * FROM costo");
                            //optener el total de reglas
                            $total = mysqli_num_rows($consult);
                            $i = 1;
                            while($rows = mysqli_fetch_array($consult))
                            {
                                $total_llamada = 0;
                                $i++;
                                $operador = $rows['operador'];

                                //
                                $consult2 = mysqli_query($link,"SELECT * FROM cdr_espejo 
                                                            WHERE cdr_espejo.operador = '$operador'
                                                            AND CONCAT(cdr_espejo.fecha_llamada,' ',cdr_espejo.hora_llamada) BETWEEN '$fecha1' AND '$fecha2'");
                                $total_llamada = mysqli_num_rows($consult2);

                                $color = "";
                                if($operador == 'Claro'){
                                    $color = '#e73043';
                                } else if($operador == 'Tigo'){
                                    $color = '#033c78';
                                } else if($operador == 'Cootel'){
                                    $color = '#f9c644';
                                } else if($operador == 'Convencional'){
                                    $color = '#a0d0e0';
                                }
                            
                                if(!empty($color)){
                                  echo '{ name: "'.$rows['operador'].'", y:'.$total_llamada.', color: "'.$color.'" },';
                                } else {
                                    echo '{ name: "'.$rows['operador'].'", y:'.$total_llamada.' },';
                                }
                            }
                          ?>
                        ]
                      }]
                    });
            });
        </script>