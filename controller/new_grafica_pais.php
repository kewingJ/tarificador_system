<?php
  require("../includes/config.php");
  require("../includes/security.php");
  require('../geoIp/geoiploc.php');


  $fecha1 = $_POST['fecha1'];
  $fecha2 = $_POST['fecha2'];
?>
        <!-- grafica de ataques por paises -->
        <script type="text/javascript">
            $(document).ready(function(){
                Highcharts.chart('containerPais', {
                      chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                      },
                      title: {
                        text: ''
                      },
                      tooltip: {
                        pointFormat: 'Total : <b>{point.y:,.0f}</b>'
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
                        name: 'Paises',
                        colorByPoint: true,
                        point:{
                            events:{
                                click: function (event) {
                                    //alert(this.name);
                                    //setTimeout("location.href = 'bloqueo_pais.php?pais!="+this.name+"'",100);
                                }
                            }
                        }, 
                        data: [
                        <?php
                            $consult = mysqli_query($link,"SELECT * FROM paises");
                            while($rows = mysqli_fetch_array($consult))
                            {
                                $codigo_pais = $rows['iso'];
                                $id_pais = $rows['id_pais'];
                                $total_ataques = 0;
                                $codigo_ip3 = '';
                                $nombre_pais = $rows['nombre'];
                                
                                //optener total de ataque por pais
                                $query = mysqli_query($link,"SELECT * FROM bloqueo_ataques WHERE fecha_bloqueo BETWEEN '$fecha1' AND '$fecha2'");
                                while($bloqueo = mysqli_fetch_array($query))
                                {
                                    $ip_ataque = $bloqueo["ip_bloqueo"];
                                    $codigo_ip = getCountryFromIP($ip_ataque, "code");
                                    if ($codigo_pais === $codigo_ip) {
                                        $total_ataques++;
                                    }
                                }

                                if ($total_ataques > 0) {
                                    echo '{ name: "'.$nombre_pais.'", y:'.$total_ataques.'},';
                                }
                            }
                        ?>
                        ]
                      }]
                });
            });
        </script>