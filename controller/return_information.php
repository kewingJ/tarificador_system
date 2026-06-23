<?php 
	function procesarInfo($id_cdr, $link) 
    {
        //obtener los datos del pago
        $queryUno = mysqli_query($link,"SELECT * FROM cdr WHERE id = '$id_cdr'");
        $rowDatosUno = mysqli_fetch_array($queryUno);
        $userfield  = $rowDatosUno['userfield'];
        $resultado = "";
        if(empty($userfield)){
            $resultado = "";
        } else {
            $resultado = $userfield;
        }
        
        return $resultado;
	}
?>