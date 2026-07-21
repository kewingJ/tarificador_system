<?php
	session_start();
	require_once('../includes/auth_check.php');
	require_ajax_auth();

	require("../includes/config.php");
  require("../includes/security.php");

	$fecha1 = mysqli_real_escape_string($link, $_POST['fecha1']);
	$fecha2 = mysqli_real_escape_string($link, $_POST['fecha2']);
?>

        <script type="text/javascript">
            $(document).ready(function(){
                Highcharts.chart('container2', {
                      chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'bar'
                      },
                      title: {
                        text: ''
                      },
                      tooltip: {
                        pointFormat: 'Duración : <b>{point.z}</b>'
                      },
                      plotOptions: {
                        pie: {
                          allowPointSelect: true,
                          cursor: 'pointer',
                          dataLabels: {
                            enabled: true,
                            format: '<b>Extension : {point.name}</b> ',
                            style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                          }
                        }
                      },
                      xAxis: {
                            categories: [
                            <?php
                                $consult = mysqli_query($link,"SELECT * FROM cdr
                                                            WHERE cdr.calldate <> '0000-00-00 00:00:00'
                                                            AND cdr.src <> ''
                                                            AND cdr.calldate BETWEEN '$fecha1' AND '$fecha2'
                                                            ORDER BY cdr.billsec DESC
                                                            LIMIT 10");
                                while($rows = mysqli_fetch_array($consult))
                                {
                                    $extension = $rows['src'];
                                    echo "'".$extension."',";
                                }
                            ?>
                            ],
                            title: {
                                text: null
                            },
                            gridLineWidth: 1,
                            lineWidth: 0
                        },
                      series: [{
                        colorByPoint: true,
                        point:{
                            events:{
                                click: function (event) {
                                    //alert(this.name);
                                    setTimeout("location.href = 'details.php?origen!="+this.name+"'",100);
                                    //http://localhost/tarificador2/details.php?origen!=82526899
                                }
                            }
                        }, 
                        data: [
                        <?php
                            $consult = mysqli_query($link,"SELECT * FROM cdr
                                                        WHERE cdr.calldate <> '0000-00-00 00:00:00'
                                                        AND cdr.src <> ''
                                                        AND cdr.calldate BETWEEN '$fecha1' AND '$fecha2'
                                                        ORDER BY cdr.billsec DESC
                                                        LIMIT 10");
                            $total = 0;
                            while($rows = mysqli_fetch_array($consult))
                            {
                                $id_cdr = $rows['id'];

                                $consultaDuracion = mysqli_query($link,"SELECT SEC_TO_TIME(cdr.billsec) AS duracion 
                                                                        FROM cdr 
                                                                        WHERE cdr.id = '$id_cdr' ");
                                $rowDuracion = mysqli_fetch_array($consultaDuracion);
                                
                                $extension = $rows['src'];
                                $duration = $rows['billsec'];
                                //$hora_texto = gmdate("H:i:s", $duration);
                                $hora_texto = $rowDuracion['duracion'];  

                                echo '{ name: "'.$extension.'", y:'.$duration.', z: "'.$hora_texto.'"},';
                            }
                        ?>
                        ]
                      }]
                    });
            });
        </script>