<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fab fa-dashcube fa-fw"></i> &nbsp; DASHBOARD
    </h3>
    <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit nostrum rerum animi natus beatae ex. Culpa
        blanditiis tempore amet alias placeat, obcaecati quaerat ullam, sunt est, odio aut veniam ratione.
    </p>
</div>

<!-- Content -->
<div class="full-box tile-container">
<?php 
    include_once 'controladores/clienteControlador.php';
    $inst_cliente = new clienteControlador();
    $total_clientes = $inst_cliente->datos_cliente_controlador("Conteo",0);
?>
    <a href="<?php echo SERVER_URL; ?>client-list/" class="tile">
        <div class="tile-tittle">Clientes</div>
        <div class="tile-icon">
            <i class="fas fa-users fa-fw"></i>
            <p><?= $total_clientes->rowCount(); ?> Registrados</p>
        </div>
    </a>

    <?php 
    include_once 'controladores/itemControlador.php';
    $inst_item = new itemControlador();
    $total_items = $inst_item->datos_item_controlador("Conteo",0);
    ?>

    <a href="<?php echo SERVER_URL; ?>item-list/" class="tile">
        <div class="tile-tittle">Items</div>
        <div class="tile-icon">
            <i class="fas fa-pallet fa-fw"></i>
            <p><?= $total_items->rowCount(); ?></p>
        </div>
    </a>

    <?php 
    include_once 'controladores/prestamosControlador.php';
    $inst_prestamo = new prestamosControlador();
    $total_prestamos = $inst_prestamo->datos_prestamo_controlador("Conteo_Prestamos",0);
    $total_reservaciones = $inst_prestamo->datos_prestamo_controlador("Conteo_Reservacion",0);
    $total_finalizados = $inst_prestamo->datos_prestamo_controlador("Conteo_Finalizado",0);
    ?>

    <a href="<?php echo SERVER_URL; ?>reservation-reservation/" class="tile">
        <div class="tile-tittle">Reservaciones</div>
        <div class="tile-icon">
            <i class="far fa-calendar-alt fa-fw"></i>
            <p><?= $total_reservaciones->rowCount(); ?></p>
        </div>
    </a>

    <a href="<?php echo SERVER_URL; ?>reservation-pending/" class="tile">
        <div class="tile-tittle">Prestamos</div>
        <div class="tile-icon">
            <i class="fas fa-hand-holding-usd fa-fw"></i>
            <p><?= $total_prestamos->rowCount(); ?></p>
        </div>
    </a>

    <a href="<?php echo SERVER_URL; ?>reservation-list/" class="tile">
        <div class="tile-tittle">Finalizados</div>
        <div class="tile-icon">
            <i class="fas fa-clipboard-list fa-fw"></i>
            <p><?= $total_finalizados->rowCount(); ?></p>
        </div>
    </a>
        <!--Restricción privilegios solo a usuarios nivel 1-->
    <?php
        if($_SESSION['privilegio_spm']==1){
            require_once 'controladores/usuarioControlador.php';
            $ins_usuario = new usuarioControlador();                    
                                                                        //0 porque no se envia el id, es un conteo de los usuarios en la bd
            $total_usuarios=$ins_usuario->datos_usuario_controlador("Conteo",0);
          ?>
    <a href="<?php echo SERVER_URL; ?>user-list/" class="tile">
        <div class="tile-tittle">Usuarios</div>
        <div class="tile-icon">
            <i class="fas fa-user-secret fa-fw"></i>
            <p><?=$total_usuarios->rowCount(); ?></p>
        </div>
    </a>
    <?php } ?>

    <a href="<?php echo SERVER_URL; ?>company/" class="tile">
        <div class="tile-tittle">Empresa</div>
        <div class="tile-icon">
            <i class="fas fa-store-alt fa-fw"></i>
            <p>1 Registrada</p>
        </div>
    </a>
</div>