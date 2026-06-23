<?php
    include_once '../includes/config.php';
    header("Content-type: application/vnd.ms-excel");
    $hoy = date("Y-m-d");
    header("Content-Disposition: attachment; filename=Reporte$hoy.xls");
?>
    <table  border="1" cellpadding="1" cellspacing="1">
        <tr bgcolor="#d5ce07">
            <td><strong>Id</strong></td>
            <td><strong>Nombre</strong></td>
            <td><strong>Origen</strong></td>
            <td><strong>Destino</strong></td>
            <td><strong>Estado</strong></td>
            <td><strong>Fecha Llamada</strong></td>
            <td><strong>Hora Llamada</strong></td>
            <td><strong>Costo</strong></td>
            <td><strong>Duracion</strong></td>
            <td><strong>Operador</strong></td>
        </tr>
<?php
          $sql = "SELECT * FROM `cdr_espejo`";
          $res = mysqli_query($link,$sql)or die("problema con la consulta");
          while($data = mysqli_fetch_array($res)){
            $estado = $data['estado'];
            $StringEstado = '';
            $colorEstado = '';

            if ($estado == 'ANSWERED') {
                $StringEstado = 'Contestada';
                $colorEstado = 'green';
            } else {
                $StringEstado = 'No Contestada';
                $colorEstado = 'red';
            }
?>
          <tr>
            <td><?php echo $data["id_cdr_espejo"];?></td>
            <td><?php echo $data["nombre"];?></td>
            <td><?php echo $data["origen"];?> </td>
            <td><?php echo $data["destino"];?></td>
            <?php echo '<td><font color="'.$colorEstado.'">'.$StringEstado.'</td>'; ?>
            <td><?php echo $data["fecha_llamada"];?></td>
            <td><?php echo $data["hora_llamada"];?></td>
            <td><?php echo $data["costo"];?></td>
            <td><?php echo $data["duracion"];?></td>
            <td><?php echo $data["operador"];?></td>
          </tr>
          <?php
          }  
        ?> 
    </table>