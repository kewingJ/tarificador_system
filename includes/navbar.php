<?php
$licenciaStatus = false;
if (is_dir(__DIR__ . '/../client') && file_exists(__DIR__ . '/../client/license_helpers.php')) {
    require_once __DIR__ . '/../client/license_helpers.php';
    if (function_exists('license_client_render_modal')) {
        $licenciaStatus = true;
    }
}
?>
            <div class="main-header bg-header wow fadeInDown animated animated" style="visibility: visible;">
                <div class="container">
                    <a href="home.php" class="header-logo" style="width: 40px;"></a>
                    
                    <ul class="header-nav collapse">
                        <li class="active">
                            <a href="home.php" title="">
                                <i class="fa fa-home"></i>
                                Inicio
                            </a>
                        </li>

                        <li <?php echo (isset($content)?$content:''); ?> class="">
                            <a href="user.php" title="">
                                <!-- <i class="fa fa-users"></i> -->
                                <img src="img/user.png" width="20%">
                                Usuarios
                            </a>
                        </li>

                        <li <?php echo (isset($content)?$content:''); ?> class="">
                            <a href="prefijos.php" title="">
                                <!-- <i class="fa fa-list"></i> -->
                                <img src="img/prefijo.png" width="20%">
                                Prefijos
                            </a>
                        </li>

                        <?php
                            if(isset($look_view_audio) && $look_view_audio) {
                                echo '
                                    <li class="">
                                        <a href="listAudio.php" title="">
                                            <!-- <i class="fa fa-file-audio-o"></i> -->
                                            <img src="img/audio.png" width="10%">
                                            Audios grabados
                                        </a>
                                    </li>
                                ';
                            }
                        ?>

                        <li class="active">
                            <a href="modulo_xmpp.php" title="">
                                <img src="img/conversation_chat.png" width="40%">
                                IM
                            </a>
                        </li>

                        <?php
                        if($licenciaStatus){
                        ?>
                        <li class="">
                            <a href="#modalLicense" data-toggle="modal">
                                <!-- <i class="fa fa-list"></i> -->
                                <img src="img/license.png" width="15%">
                                Licencia
                            </a>
                        </li>
                        <?php } ?>

                        <li class="">
                            <a href="salir.php" title="">
                                <!-- <i class="fa fa-power-off"></i> -->
                                <img src="img/salir.png" width="20%">
                                Salir
                            </a>
                        </li>
                    </ul><!-- .header-nav -->
                </div><!-- .container -->
            </div>
