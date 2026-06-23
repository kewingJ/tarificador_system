<?php
    require("includes/config.php");
	require("includes/security.php");
    $consulta = mysqli_query($link, "SELECT * FROM usuario");
    while($row = mysqli_fetch_array($consulta)){
        echo $row['activo_u'].'<br>';
    }

?>