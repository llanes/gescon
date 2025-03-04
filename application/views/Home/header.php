<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
     <div id="toastem"></div>
  <!-- onselectstart="return false;" ondragstart="return false;" -->
    <div class="wrapper">
    <!--header start-->
      <header class="main-header ">
        <!-- Logo -->
        <a href="#" class="sidebar-toggle" id="myLink" data-toggle="offcanvas" role="button" >
        <i class="glyphicon glyphicon-list" id="sidebar-icon"></i>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top toggleNavbar"   role="navigation" style="display:none">
          <!-- Sidebar toggle button-->
            <span id="atajoscompra"  style="display: none; float: left; padding: 15px 15px; font-weight: bold;">
            <span >CTRL + Q = Proveedor </span> |   <span >CTRL + B = Guardar </span> |   <span >CTRL + M = Monto </span> |   <span >CTRL + Z = Productos </span></span>
                      
          <div class="navbar-custom-menu ">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  
                  <?php 
                      $resultado = count($Alerta); 
                      if ($resultado > 0) { ?>
                        <span class="label label-danger"><?php echo $resultado; ?></span>
                  <?php  } else {
                        # code...
                      }
                  ?>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">Usted Tiene <?php echo $resultado = count($Alerta); ?> Alertas De Stock</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: auto;;">
                      <ul class="menu" style="overflow: hidden; width: 100%; height: auto;">
                      <li>
                        <?php 
                           $resultado = count($Alerta);
                                        foreach($Alerta as $fila1)
                                        {
                                          ?>
                                            <a href="#" id="vertodo" data-id="<?= $fila1->id?>">
                                              <i class="fa fa-exclamation-triangle text-red"></i>

                                                <?php if ($fila1->can == '') {
                                                  echo "0";
                                                }else {
                                                    echo $fila1 -> can;
                                                }
                                                ?>
                                                &nbsp;&nbsp;
                                              <?php  echo $fila1 -> nom;?>
                                               &nbsp;&nbsp;Stock
                                            </a>
                                          <?php
                                        }
                        ?>

                      </li>
                      <li>
                    </ul>
                    </div>
                  </li>
                  <li class="footer"><a id="vertodo" href="javascript:void(0);" data-id="0">Ver todo</a></li>
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

                      <a href="<?= site_url('Cerrar')?>" class="btn bg-navy btn-flat margin">Cerrar Sesion</a>
  
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>

              </li>
            </ul>
          </div>
        </nav>


      </header>
      <div class="mini-menu">
            <a  href="<?php echo base_url('Inicio') ?>" class="menu-link">Inicio</a>
            <button id="toggleNavbarBtn" class="toggle-navbar-btn">
                <i id="toggleIcon" class="fa fa-bars"></i>
                Mostrar/Ocultar Barra Superior.
            </button>
        </div>
    <!--header end-->
