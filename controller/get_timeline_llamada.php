<?php 
	include_once '../includes/config.php';
    include_once '../includes/security.php';
    
    session_start();
    require_once '../includes/auth_check.php';
    require_ajax_auth();
    $id = $_SESSION['id_u'];

	if (isset($_POST['id_llamada'])) 
	{
        echo '<div class="container">                      
                <div class="row text-center justify-content-center mb-5">
                    <div class="col-xl-6 col-lg-8">
                        <h2 class="font-weight-bold"></h2>
                        <p class="text-muted"></p>
                    </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">';

		$id_llamada = (string) $_POST['id_llamada'];
		$stmt = mysqli_prepare($linkAsteriskcel, "SELECT * FROM cel WHERE linkedid = ? AND eventtype = 'APP_START'");
		mysqli_stmt_bind_param($stmt, 's', $id_llamada);
		mysqli_stmt_execute($stmt);
		$queryProject = mysqli_stmt_get_result($stmt);
        $total = mysqli_num_rows($queryProject);
        $i = 1;
		while($rowProject = mysqli_fetch_array($queryProject)){

            $llamada_de = $rowProject['cid_num'];
            $llamada_para = $rowProject['exten'];
            
            if($total != $i){
            echo '
            <div class="timeline-step">
                <div class="timeline-content">
                    <div class="inner-circle"></div>
                    <p class="h6 mt-3 mb-1">'.$llamada_de.'</p>
                    <p class="h6 text-muted mb-0 mb-lg-0"></p>
                </div>
            </div>      
            <div class="timeline-step">
                <div class="timeline-content" data-toggle="popover">
                    <div class="inner-circle"></div>
                    <p class="h6 mt-3 mb-1">'.$llamada_para.'</p>
                    <p class="h6 text-muted mb-0 mb-lg-0"></p>
                </div>
            </div>';
            } else {
                echo '
                <div class="timeline-step">
                    <div class="timeline-content" data-toggle="popover">
                        <div class="inner-circle"></div>
                        <p class="h6 mt-3 mb-1">Transferencia</p>
                        <p class="h6 text-muted mb-0 mb-lg-0"></p>
                    </div>
                </div> 
                <div class="timeline-step">
                    <div class="timeline-content" data-toggle="popover">
                        <div class="inner-circle"></div>
                        <p class="h6 mt-3 mb-1">'.$llamada_de.'</p>
                        <p class="h6 text-muted mb-0 mb-lg-0"></p>
                    </div>
                </div>     
                <div class="timeline-step mb-0">
                    <div class="timeline-content">
                        <div class="inner-circle"></div>
                        <p class="h6 mt-3 mb-1">'.$llamada_para.'</p>
                        <p class="h6 text-muted mb-0 mb-lg-0"></p>
                    </div>
                </div>                               
                ';
            }
            $i++;
        }

        echo '
                    </div>
                </div>
            </div>
        </div>
        ';
	}
?>