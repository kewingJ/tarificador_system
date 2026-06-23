<?php
  require("../includes/config.php");
  require("../includes/security.php");

  $fecha1 = $_POST['fecha1'];
  $fecha2 = $_POST['fecha2'];
?>

        <script type="text/javascript">
            $(document).ready(function(){
                    var area = new Morris.Area({
                        element   : 'revenue-chart2',
                        resize    : true,
                        data      : [
                        <?php
                            $consult = mysqli_query($link,"SELECT * FROM grafica_bloqueo WHERE fecha_bloqueo BETWEEN '$fecha1' AND '$fecha2' ORDER BY fecha_bloqueo ASC");
                                
                            while($rows = mysqli_fetch_array($consult))
                            {
                                $fecha_bloqueo = $rows['fecha_bloqueo'];
                                $total_ssh = $rows['ssh'];
                                $asterisk = $rows['asterisk'];

                                echo '{ y: "'.$fecha_bloqueo.'", item1: '.$total_ssh.', item2: '.$asterisk.'},';
                            }
                        ?>
                        ],
                        xkey      : 'y',
                        ykeys     : ['item1', 'item2'],
                        labels    : ['ssh brute-force', 'Sip brute-force'],
                        lineColors: ['#e73043', '#033c78'],
                        hideHover : 'auto'
                    });
            });
        </script>
