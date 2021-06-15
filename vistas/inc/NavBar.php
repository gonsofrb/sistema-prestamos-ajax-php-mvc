<nav class="full-box navbar-info">
    <a href="#" class="float-left show-nav-lateral">
        <i class="fas fa-exchange-alt"></i>
    </a>                                        <!--$ilc está definida en plantilla.php-->
    <a href="<?php echo SERVER_URL."user-update/".$ilc->encryption($_SESSION['id_spm'])."/"; ?>">
        <i class="fas fa-user-cog"></i>
    </a>
    <a href="#" class="btn-exit-system">
        <i class="fas fa-power-off"></i>
    </a>
</nav>