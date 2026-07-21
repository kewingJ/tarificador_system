<?php
  session_start();
  require_once('../includes/auth_check.php');
  require_ajax_auth();

  require("../includes/config.php");
  require("../includes/security.php");

  $fecha1 = mysqli_real_escape_string($link, $_POST['fecha1']);
  $fecha2 = mysqli_real_escape_string($link, $_POST['fecha2']);
?>
        <!-- Grafica de compañias -->
        <script type="text/javascript">
            $(document).ready(function(){
                /* Morris.js Charts */
                // Sales chart
                var area = new Morris.Area({
                    element   : 'revenue-chart',
                    resize    : true,
                    data      : [
                    <?php
                        $consult = mysqli_query($link,"SELECT * FROM grafica_principal WHERE fecha_llamada BETWEEN '$fecha1' AND '$fecha2' ORDER BY fecha_llamada ASC");
                            
                        while($rows = mysqli_fetch_array($consult))
                        {
                            $fecha_llamada = $rows['fecha_llamada'];
                            $total_claro = $rows['total_claro'];
                            $total_tigo = $rows['total_tigo'];
                            $total_cootel = $rows['total_cootel'];
                            $total_convencional = $rows['total_convencional'];

                            echo '{ y: "'.$fecha_llamada.'", item1: '.$total_claro.', item2: '.$total_tigo.',  item3: '.$total_cootel.', item4: '.$total_convencional.'},';
                        }
                    ?>
                    ],
                    xkey      : 'y',
                    ykeys     : ['item1', 'item2', 'item3', 'item4'],
                    labels    : ['Claro', 'Tigo', 'Cootel', 'Convencional'],
                    lineColors: ['#e73043', '#033c78', '#f9c644', '#a0d0e0'],
                    hideHover : 'auto'
                });
            });
        </script>