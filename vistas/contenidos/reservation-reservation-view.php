<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="far fa-calendar-alt fa-fw"></i> &nbsp; RESERVACIONES
    </h3>
    <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia fugiat est ducimus inventore, repellendus
        deserunt cum aliquam dignissimos, consequuntur molestiae perferendis quae, impedit doloribus harum
        necessitatibus magnam voluptatem voluptatum alias!
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVER_URL; ?>reservation-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO PRÉSTAMO</a>
        </li>
        <li>
            <a class="active" href="reservation-reservation.html"><i class="far fa-calendar-alt"></i> &nbsp;
                RESERVACIONES</a>
        </li>
        <li>
            <a href="<?php echo SERVER_URL; ?>reservation-pending/"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; PRÉSTAMOS</a>
        </li>
        <li>
            <a href="<?php echo SERVER_URL; ?>reservation-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; FINALIZADOS</a>
        </li>
        <li>
            <a href="<?php echo SERVER_URL; ?>reservation-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
        <?php   
            require_once 'controladores/prestamosControlador.php';
            $inst_prestamo = new prestamosControlador();

            echo $inst_prestamo->mostrar_prestamos_controlador($pagina[1],10,$_SESSION['privilegio_spm'],
            //$pagina[0] le pasamos la vista
            $pagina[0],"Reservacion","","");
        ?>
</div>