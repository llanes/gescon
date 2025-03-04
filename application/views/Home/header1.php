 
  <body class="skin-blue sidebar-mini pace-done pace-done sidebar-collapse" id="barra">
        <div id="toastem"></div>
    <div class="modal fade bs-example-modal-lg" id="snnip">
          <div style="text-align: center">
            <br><br><br><br><br><br><br><br><br><br><br><br>
             <h1 class="text-danger" id="mensaje">Procesando....</h1>
            <p class="text-danger"><i class="fa fa-spinner fa-spin fa-5x"></i></p>
          </div>
    </div>
    <div class="wrapper">
    <!--header start-->
      <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo base_url('index.php/Inicio') ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>A.</b>G.</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Admin</b> Gescom </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" onclick="reload_table();" data-toggle="offcanvas" role="button">
            <span class="">Barra </span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>

                </a>
                <ul class="dropdown-menu">
                  <li class="header">Usted Tiene <?php echo $resultado = count($Alerta); ?> Alertas De Stock</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: auto;;"><ul class="menu" style="overflow: hidden; width: 100%; height: auto;">
                      <li>

                      </li>
                      <li>
                    </ul>
                    </div>
                  </li>
                  <li class="footer"><a href="<?= base_url('index.php/Stock/0') ?>">Ver todo</a></li>
                </ul>
              </li>
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <img src="<?php echo base_url();?>content/dist/img/avatar.png" class="user-image" alt="User Image"/>
                  <span class="hidden-xs"><?php echo $this->session->userdata('Usuario')?></span>
                  <!-- <span class="hidden-xs">Alexander Pierce</span> -->
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo base_url();?>content/dist/img/avatar.png" class="img-circle" alt="User Image">
                    <p>
                      Administrador - Web Gescom
                      <small>Ingresado Correctamente</small>
                    </p>
                  </li>

                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-right">
                      <a href="<?= site_url('Cerrar')?>" class="btn bg-navy btn-flat margin">Cerrar Sesion</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
    <!--header end-->
