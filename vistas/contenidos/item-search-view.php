   <!-- Page header -->
   <div class="full-box page-header">
       <h3 class="text-left">
           <i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM
       </h3>
       <p class="text-justify">
           Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum delectus eos enim numquam fugit optio
           accusantium, aperiam eius facere architecto facilis quibusdam asperiores veniam omnis saepe est et, quod
           obcaecati.
       </p>
   </div>

   <div class="container-fluid">
       <ul class="full-box list-unstyled page-nav-tabs">
           <li>
               <a href="<?php echo SERVER_URL; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM</a>
           </li>
           <li>
               <a href="<?php echo SERVER_URL; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ITEMS</a>
           </li>
           <li>
               <a class="active" href="<?php echo SERVER_URL; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM</a>
           </li>
       </ul>
   </div>
   <?php                   //Si no está definida o no existe
				if(!isset($_SESSION['busqueda_item']) && empty($_SESSION['busqueda_item'])){

				
			?>
   <!--CONTENT-->
   <div class="container-fluid">
        <!--Form buscar-->
       <form class="form-neon FormularioAjax" action="<?= SERVER_URL ?>ajax/buscadorAjax.php" method="POST"  data-form="default" autocomplete="off">
       <!--Modulo desde el cual se envía los datos-->
		    <input type="hidden" name="modulo" value="item">
           <div class="container-fluid">
               <div class="row justify-content-md-center">
                   <div class="col-12 col-md-6">
                       <div class="form-group">
                           <label for="inputSearch" class="bmd-label-floating">¿Qué item estas buscando?</label>
                           <input type="text" class="form-control" name="busqueda_inicial" id="inputSearch" maxlength="30">
                       </div>
                   </div>
                   <div class="col-12">
                       <p class="text-center" style="margin-top: 40px;">
                           <button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i> &nbsp;
                               BUSCAR</button>
                       </p>
                   </div>
               </div>
           </div>
       </form>
   </div>
   <?php }else{ ?>
   <div class="container-fluid">
        <!--Form eliminar-->
       <form  class="form-neon FormularioAjax"  action="<?= SERVER_URL ?>ajax/buscadorAjax.php" method="POST"  data-form="search" autocomplete="off">
            <!--Modulo desde el cual se envía los datos-->
            <input type="hidden" name="modulo" value="item">
           <input type="hidden" name="eliminar_busqueda" value="eliminar">
           <div class="container-fluid">
               <div class="row justify-content-md-center">
                   <div class="col-12 col-md-6">
                       <p class="text-center" style="font-size: 20px;">
                           Resultados de la busqueda <strong>“<?=$_SESSION['busqueda_item']; ?>”</strong>
                       </p>
                   </div>
                   <div class="col-12">
                       <p class="text-center" style="margin-top: 20px;">
                           <button type="submit" class="btn btn-raised btn-danger"><i class="far fa-trash-alt"></i>
                               &nbsp; ELIMINAR BÚSQUEDA</button>
                       </p>
                   </div>
               </div>
           </div>
       </form>
   </div>

   <div class="container-fluid">
   <?php   
					require_once 'controladores/itemControlador.php';
					$inst_item = new itemControlador();

					echo $inst_item->mostrar_item_controlador($pagina[1],10,$_SESSION['privilegio_spm'],$pagina[0],$_SESSION['busqueda_item']);
					?>
   </div>
   <?php } ?>