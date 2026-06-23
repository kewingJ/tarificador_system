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
                    ?>
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Cargando...</title>
                        <script src="https://cdn.tailwindcss.com"></script>
                        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
                        <style>
                            body { font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
                            @keyframes spin-slow {
                                from { transform: rotate(0deg); }
                                to { transform: rotate(360deg); }
                            }
                            .animate-spin-slow {
                                animation: spin-slow 2s linear infinite;
                            }
                        </style>
                    </head>
                    <body class="bg-gradient-to-br from-[#21499a] via-[#1a3a7a] to-[#0f244d] min-h-screen flex items-center justify-center">
                        <div class="text-center">
                            <!-- Spinner Contenedor -->
                            <div class="relative flex items-center justify-center mb-8">
                                <!-- Círculo exterior -->
                                <div class="w-24 h-24 border-4 border-white/10 border-t-white rounded-full animate-spin"></div>
                                <!-- Círculo interior -->
                                <div class="absolute w-16 h-16 border-4 border-white/5 border-b-yellow-400 rounded-full animate-spin-slow"></div>
                                <!-- Logo o Punto central -->
                                <div class="absolute w-2 h-2 bg-white rounded-full shadow-[0_0_15px_rgba(255,255,255,0.8)]"></div>
                            </div>

                            <div class="space-y-2">
                                <h2 class="text-white text-xl font-light tracking-[0.2em] uppercase animate-pulse">
                                    <?php echo $licenciaStatus ? 'Cargando... Licencia activa' : 'Cargando...'; ?>
                                </h2>
                                <p class="text-white/40 text-xs tracking-widest uppercase">Por favor espere un momento</p>
                            </div>
                        </div>

                        <script>
                            setTimeout(function() {
                                window.location.href = 'home.php';
                            }, 4000);
                        </script>
                    </body>
                    </html>
                    <?php
                    exit;
                break;
                case 2:
                    header("Location: home.php");
                break;
                default:
                    header("Location: home.php");
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