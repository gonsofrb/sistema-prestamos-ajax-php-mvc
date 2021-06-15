<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?php echo COMPAY; ?></title>
    <?php include 'vistas/inc/Link.php'; ?>
</head>
<body>
	<?php 
	$peticionAjax = false;
	require_once 'controladores/vistasControlador.php'; 
	$IV = new vistasControlador();

	$vistas = $IV->obtener_vistas_controlador();

	if($vistas == "login" || $vistas == "404"){
		require_once 'vistas/contenidos/'.$vistas."-view.php";
	}else{
		//Iniciamos sesion
		session_start(['name'=>'SPM']);

		//Array
		$pagina=explode("/",$_GET['views']);

		require_once 'controladores/loginControlador.php';
		$ilc= new loginControlador();
					//Comprobación si no se ha iniciado sesion se forza un cierre.
			if(!isset( $_SESSION['token_spm']) || !isset($_SESSION['usuario_spm'])  || !isset($_SESSION['privilegio_spm']) || !isset($_SESSION['id_spm']) ){
				echo $ilc->forzar_cierre_sesion_controlador();
				exit();
			}
	
	?>
	<!-- Main container -->
	<main class="full-box main-container">
		<!-- Nav lateral -->
		<?php include 'vistas/inc/NavLateral.php'; ?>

		<!-- Page content -->
		<section class="full-box page-content">
		<?php 
		
		include 'vistas/inc/NavBar.php'; 
		include $vistas;
		?>	

			<!-- Page header -->
		</section>
	</main>

	<?php
		include 'vistas/inc/LogOut.php';
	}

	include 'vistas/inc/Script.php'; ?>
</body>
</html>