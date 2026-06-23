<?php
if(!empty($_POST['email']) && !empty($_POST['password'])) 
{
    require("includes/config.php");
	require("includes/security.php");

    $licenciaStatus = false;
    // 2. Cargar helpers y banner SOLAMENTE si la carpeta client existe
    if (is_dir(__DIR__ . '/client') && file_exists(__DIR__ . '/client/license_helpers.php')) {
        require_once __DIR__ . '/client/license_helpers.php';
        if (function_exists('license_client_render_banner')) {
        $licenciaStatus = true;
        }
    }

	/*guardo los datos del usuario y los limpio de cualquier caracter*/
	$email = mysqli_real_escape_string($link,$_POST['email']);
	$pass = mysqli_real_escape_string($link,$_POST['password']);
    /*verificamos en la base de datos si existe el usuario*/
    $consulta = mysqli_query($link, "SELECT * FROM usuario WHERE email_u='{$email}' AND activo_u = 1");
    $row = mysqli_fetch_array($consulta);
    if(is_numeric($row['id_usuario']) AND $row['id_usuario']>0) 
    {
        $passactual = $row['contrasena'];
        $passenviada = $pass;
        if(password_verify($passenviada,$passactual))
        {
            session_start();
            $id_us = $_SESSION['id_u'] = $row['id_usuario'];
            $_SESSION['nombre'] = $row['nombre_u'];
            $_SESSION['apellido'] = $row['apellido_u'];
            $_SESSION['activo'] = $row['activo_u'];
            $_SESSION['tipo_usuario'] = $row['tipo_u'];

            $tipo = $row['tipo_u'];
            switch ($tipo) {
                case 1:
                    if($licenciaStatus){
                       //mostrar circulo de cargando por 4 segundos con el texto de licencia activa
                        ?>
                        <div class="preloader">
                            <div class="loading">
                                <div class="loading-content">
                                    <div class="spinner"></div>
                                    <div class="text">Cargando licencia...</div>
                                </div>
                            </div>
                        </div>
                        <?php
                        exit;
                    }
                    header("Location: home.php");
                break;
                case 2:
                    //header("Location: inicio.php");
                    // header("Location: index.php");
                break;
            }
            
        }else{
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }
}else {
    header("Location: index.php");
}
?>