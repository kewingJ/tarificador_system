<?php
  require("../includes/config.php");
  require("../includes/security.php");

  $fecha1 = $_POST['fecha1'];
  $fecha2 = $_POST['fecha2'];
?>
        <!-- grafica de top de ip bloqueadas -->
        <script type="text/javascript">
            $(document).ready(function(){
                Highcharts.chart('containerTopIp', {
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
                            $consult = mysqli_query($link,"SELECT ip_bloqueo, count(*) as total 
                            FROM bloqueo_ataques
                            WHERE fecha_bloqueo BETWEEN '$fecha1' AND '$fecha2'
                            GROUP BY ip_bloqueo 
                            ORDER BY total DESC 
                            LIMIT 5
                            ");
                            
                            while($rows = mysqli_fetch_array($consult))
                            {
                                $ip_bloqueo = $rows['ip_bloqueo'];
                                $total_bloqueos = $rows['total'];
                                if ($total_bloqueos > 0) {
                                    echo '{ name: "'.$ip_bloqueo.'", y:'.$total_bloqueos.'},';
                                }
                            }
                        ?>
                        ]
                      }]
                });
            });
        </script>