            <!-- Page header -->
            <div class="full-box page-header">
                <h3 class="text-left">
                    <i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR PRÉSTAMOS POR FECHA
                </h3>
                <p class="text-justify">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia fugiat est ducimus inventore,
                    repellendus deserunt cum aliquam dignissimos, consequuntur molestiae perferendis quae, impedit
                    doloribus harum necessitatibus magnam voluptatem voluptatum alias!
                </p>
            </div>

            <div class="container-fluid">
                <ul class="full-box list-unstyled page-nav-tabs">
                    <li>
                        <a href="<?php echo SERVER_URL; ?>reservation-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO PRÉSTAMO</a>
                    </li>
                    <li>
                        <a href="<?php echo SERVER_URL; ?>reservation-reservation/"><i class="far fa-calendar-alt"></i> &nbsp;
                            RESERVACIONES</a>
                    </li>
                    <li>
                        <a href="<?php echo SERVER_URL; ?>reservation-pending/"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp;
                            PRÉSTAMOS</a>
                    </li>
                    <li>
                        <a href="<?php echo SERVER_URL; ?>reservation-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;
                            FINALIZADOS</a>
                    </li>
                    <li>
                        <a class="active" href="reservation-search.html"><i class="fas fa-search-dollar fa-fw"></i>
                            &nbsp; BUSCAR POR FECHA</a>
                    </li>
                </ul>
            </div>

            <?php                   //Si no está definida o no existe
				if(!isset($_SESSION['fecha_inicio_prestamo']) && empty($_SESSION['fecha_inicio_prestamo']) && !isset($_SESSION['fecha_final_prestamo']) && empty($_SESSION['fecha_final_prestamo'])){

			
			?>
            <div class="container-fluid">
            <!--Form buscar-->
                <form class="form-neon FormularioAjax" action="<?= SERVER_URL ?>ajax/buscadorAjax.php" method="POST"  data-form="default" autocomplete="off">
                <input type="hidden" name="modulo" value="prestamo">
                    <div class="container-fluid">
                        <div class="row justify-content-md-center">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="busqueda_inicio_prestamo">Fecha inicial (día/mes/año)</label>
                                    <input type="date" class="form-control" name="busqueda_inicio_prestamo"
                                        id="busqueda_inicio_prestamo" maxlength="30">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="busqueda_final_prestamo">Fecha final (día/mes/año)</label>
                                    <input type="date" class="form-control" name="busqueda_final_prestamo"
                                        id="busqueda_final_prestamo" maxlength="30">
                                </div>
                            </div>
                            <div class="col-12">
                                <p class="text-center" style="margin-top: 40px;">
                                    <button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i>
                                        &nbsp; BUSCAR</button>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php }else{ ?>

            <div class="container-fluid">
            <!--Form eliminar-->
                <form class="form-neon FormularioAjax" action="<?= SERVER_URL ?>ajax/buscadorAjax.php" method="POST"  data-form="default" autocomplete="off">
                    <input type="hidden" name="modulo" value="prestamo">
                    <input type="hidden" name="eliminar_busqueda" value="eliminar">
                    <div class="container-fluid">
                        <div class="row justify-content-md-center">
                            <div class="col-12 col-md-6">
                                <p class="text-center" style="font-size: 20px;">
                                    Fecha de busqueda: <strong><?php echo date("d-m-Y",strtotime($_SESSION['fecha_inicio_prestamo'])); ?>&nbsp; a &nbsp; <?php echo date("d-m-Y",strtotime($_SESSION['fecha_final_prestamo'])); ?></strong>
                                </p>
                            </div>
                            <div class="col-12">
                                <p class="text-center" style="margin-top: 20px;">
                                    <button type="submit" class="btn btn-raised btn-danger"><i
                                            class="far fa-trash-alt"></i> &nbsp; ELIMINAR BÚSQUEDA</button>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="container-fluid">
            <?php   
                require_once 'controladores/prestamosControlador.php';
                $inst_prestamo = new prestamosControlador();

                echo $inst_prestamo->mostrar_prestamos_controlador($pagina[1],10,$_SESSION['privilegio_spm'],
                //$pagina[0] le pasamos la vista
                $pagina[0],"Busqueda",$_SESSION['fecha_inicio_prestamo'],$_SESSION['fecha_final_prestamo']);
            ?>
            </div>
            <?php } ?>