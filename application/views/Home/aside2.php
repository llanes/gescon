  <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->

          <ul class="sidebar-menu">
      <?php if (is_array($data_view) || is_object($data_view)) :?>
            <li class="header">NAVEGACION</li>
        <?php 
                $n = 1;
                foreach($data_view as $fila)
                {  
                  if ($fila -> Url == 1 && $n == 1) {
                    $n += 1;  
                      ?>
                          <li class="treeview" id="Caja">
                            <a href="#">
                              <i class="fa fa-archive" aria-hidden="true"></i>
                              <span>Administrar Caja</span>
                              <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                          <?php 
                              foreach($data_view as $fila2)
                              {

                                  switch ($fila2 ->ID) {
                                    case '2':?>
                                    <li id="pr" ><a href="<?= base_url("index.php/CA1") ?>"><i id="_a_c" class="fa fa-folder-open"></i> Apertura || Cierra</a></li>
                                     <?php break;
                                   case '3':?>
                                      <li id="m_c"><a href="<?= base_url('index.php/CA2') ?>"><i id="_m_c"  class="fa fa-info"></i> Listado de Cajas</a></li>
                                     <?php  break;
                                   case '4':?>
                                      <li id="_his"><a href="<?= base_url('index.php/CA3') ?>"><i id="_his_"  class="fa fa-history"></i> Historial</a></li>
                                     <?php  break;
                                  }
                              }
                              ?>
                            </ul>
                          </li><?php  
                     } else {
                    # code...
                  }
                }
          ?>
          <?php 
                $n = 2;
                foreach($data_view as $fila)
                {  
                  if ($fila -> Url == 2 && $n == 2) {
                    $n += 1;  
                      ?>
                          <li class="treeview" id="Producto">
                            <a href="#">
                              <i class="fa fa-pie-chart"></i>
                              <span>Administrar Productos</span>
                              <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                            <?php 
                              foreach($data_view as $fila2)
                              {

                                  switch ($fila2 ->ID) {
                                        case '6':?>
                                        <li id="pr" ><a href="<?= base_url('index.php/Producto') ?>"><i id="_pro" class="fa fa-shopping-cart"></i> Productos</a></li>
                                        <?php break;
                                        case '32':?>
                                        <li id="descuen" ><a href="<?= base_url('index.php/Descuento') ?>"><i id="d_escue_n" class="fa fa-venus"></i> Aplicar Descuentos</a></li>
                                        <?php  break;
                                        case '21':?>
                                        <li id="PRO" ><a href="<?= base_url('index.php/Producciones') ?>"><i id="P_R_O" class="fa fa-american-sign-language-interpreting " aria-hidden="true"></i>Produciones</a></li>
                                        <?php  break;
                                        case '7':?>
                                        <li id="st"><a href="<?= base_url('index.php/Stock') ?>"><i id="_stc"  class="fa fa-stack-overflow"></i> Stock</a></li>
                                        <?php  break;
                                        case '8':?>
                                        <li id="ca"><a href="<?= base_url('index.php/Categoria') ?>"><i id="_cate"  class="fa fa-creative-commons"></i>Categorias</a></li>
                                        <?php  break;
                                        case '9':?>
                                        <li id="ma"><a href="<?= base_url('index.php/Marca') ?>"><i id="_mar"  class="fa fa-magnet"></i>    Marcas</a></li>
                                        <?php  break;
                                        case '10':?>
                                        <li id="ma"><a href="<?= base_url('index.php/P_perdido') ?>"><i id="_perd"  class="fa fa-magnet"></i>Productos Dañado</a></li>
                                        <?php  break;  
                                         default:
                                            // code...
                                            break;
                                  }
                              }
                              ?>
                            </ul>
                          </li>
                      <?php  
                     } else {
                    # code...
                  }
                }
          ?>
          <?php 
                $n = 3;
                foreach($data_view as $fila)
                {  
                  if ($fila -> Url == 3 && $n == 3) {
                    $n += 1;
                      ?>
                          <li class="treeview" id="Orden">
                            <a href="#">
                              <i class="fa fa-first-order"></i>
                              <span>Gestionar Pedidos</span>
                              <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                             <?php 
                              foreach($data_view as $fila2)
                              {

                                  switch ($fila2 ->ID) {
                                        case '11':?>
                                          <li id="odv" ><a href="<?= base_url('index.php/O_Venta') ?>"><i id="o_d_v" class="fa fa-shopping-cart"></i>Orden de Venta</a></li>
                                        <?php break;
                                        case '12':?>
                                         <li id="odc"><a href="<?= base_url('index.php/O_Comprar') ?>"><i id="o_d_c"  class="fa fa-shopping-bag"></i> Orden de Compra</a></li>
                                        <?php  break;  
                                        case '31':?>
                                         <li id="ndr"><a href="<?= base_url('index.php/Remision') ?>"><i id="n_d_r"  class="fa fa-shopping-bag"></i> Notas de Remision</a></li>
                                        <?php  break;  

                                  }
                              }
                              ?>
                            </ul>
                          </li>
                      <?php  
                     } else {
                    # code...
                  }
                }
          ?>
          <?php 
                $n = 4;
                foreach($data_view as $fila)
                {  
                  if ($fila -> Url == 4 && $n == 4) {
                    $n += 1;
                      ?>
                        <li class="treeview" id="Compra">
                          <a href="#">
                            <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                            <span>Compras</span>
                            <i class="fa fa-angle-left pull-right"></i>
                          </a>
                          <ul class="treeview-menu">
                             <?php
                              foreach($data_view as $fila2)
                              {

                                  switch ($fila2 ->ID) {
                                        case '13':?>
                                          <li id="CDE" ><a href="<?= base_url('index.php/Deudae') ?>"><i id="C_D_E" class="fa fa-credit-card"></i>Deuda Empresa</a></li>
                                        <?php break;
                                        case '14':?>
                                          <li id="CPA"><a href="<?= base_url('index.php/Comprar') ?>"><i id="C_P_A"  class="fa fa-shopping-bag"></i>Compras</a></li>
                                        <?php  break;
                                        case '15':?>
                                        <li id="ANUL"><a href="<?= base_url('index.php/Anulados') ?>"><i id="A_N_L"  class="fa fa-arrows-alt"></i>Comprobantes Anulados</a></li>
                                        <?php  break;
                                        case '16':?>
                                        <li id="DEV"><a href="<?= base_url('index.php/C_D') ?>"><i id="D_E_V"  class="fa fa-retweet"></i>Devoluciones</a></li>
                                        <?php  break;
                                  }
                              }
                              ?>
                            </ul>
                          </li>
                      <?php
                     } else {
                    # code...
                  }
                }
            ?>
          <?php 
                $n = 5;
                foreach($data_view as $fila)
                {  
                  if ($fila -> Url == 5 && $n == 5) {
                    $n += 1;
                      ?>
                         <li class="treeview" id="Venta">
                            <a href="#">
                              <i class="fa fa-shopping-cart"></i>
                              <span>Ventas</span>
                              <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                             <?php
                              foreach($data_view as $fila2)
                              {

                                  switch ($fila2 ->ID) {
                                        case '17':?>
                                           <li id="CDC" ><a href="<?= base_url('index.php/Deudac') ?>"><i id="C_D_C" class="fa fa-credit-card"></i> Deuda Cliente</a></li>
                                        <?php break;
                                        case '18':?>
                                         <li id=""><a href="<?= base_url('index.php/Vender') ?>"><i id=""  class="fa fa-shopping-cart"></i>Venta</a></li>
                                        <?php  break;
                                        case '19':?>
                                         <li id=""><a href="<?= base_url('index.php/Anulados') ?>"><i id=""  class="fa fa-arrows-alt"></i>Comprobantes Anulados</a></li>
                                        <?php  break;
                                        case '20':?>
                                        <li id="DEVL"><a href="<?= base_url('index.php/V_D') ?>"><i id="D_E_V_L"  class="fa fa-retweet"></i>Devoluciones</a></li>
                                        <?php  break;
                                  }
                              }
                              ?>
                            </ul>
                          </li>
                      <?php
                     } else {
                    # code...
                  }
                }
          ?>

          <?php 
                $n = 6;
                foreach($data_view as $fila)
                {  
                  if ($fila -> Url == 6 && $n == 6) {
                    $n += 1;
                      ?>
                     <li class="treeview" id="PagosCobros">
                        <a href="#">
                         <i class="fa fa-archive" aria-hidden="true"></i>
                          <span>Pagos | Cobros</span>
                          <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                             <?php
                              foreach($data_view as $fila2)
                              {

                                  switch ($fila2 ->ID) {
                                        case '23':?>
                                        <li id="Cob" ><a href="<?= base_url('index.php/Cobros') ?>"><i id="C_o_b" class="fa fa-handshake-o"></i> Cobros</a></li>
                                       <?php break;
                                        case '24':?>
                                        <li id="Codc" ><a href="<?= base_url('index.php/CobroDC') ?>"><i id="C_o_d_c" class="fa fa-handshake-o"></i> Cobros Deuda Cliente</a></li>
                                        <?php  break;
                                        case '25':?>
                                        <li id="Pag"><a href="<?= base_url('index.php/Pagos') ?>"><i id="P_a_g" class="fa fa-handshake-o" aria-hidden="true"></i> Pagos</a></li>
                                        <?php  break;
                                        case '26':?>
                                        <li id="PagE"><a href="<?= base_url('index.php/PagoDE') ?>"><i id="P_a_g_e" class="fa fa-handshake-o" aria-hidden="true"></i> Pagos Deuda Empresa</a></li>
                                        <?php  break;
                                  }
                              }
                              ?>
                            </ul>
                          </li>
                      <?php
                     } else {
                    # code...
                  }
                }
          ?>

          <?php 
                $n =7;
                foreach($data_view as $fila)
                {  
                  if ($fila -> Url == 7 && $n == 7) {
                    $n += 1;
                      ?>
                        <li class="treeview" id="bancos">
                            <a href="#">
                              <i class="fa fa-university" aria-hidden="true"></i>  
                              <span>Bancos</span>
                              <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                             <?php
                              foreach($data_view as $fila2)
                              {

                                  switch ($fila2 ->ID) {
                                        case '27':?>
                                        <li id="BAN" ><a href="<?= base_url('index.php/Bancos') ?>"><i id="B_A_N" class="fa fa-university"></i>Administrar Bancos</a></li>
                                       <?php break;
                                        case '28':?>
                                        <li id="Mov"><a href="<?= base_url('index.php/Mov') ?>"><i id="M_o_v" class="fa fa-arrows" aria-hidden="true"></i>Movimientos Bancos</a></li>
                                        <?php  break;
                                  }
                              }
                              ?>
                            </ul>
                          </li>
                      <?php
                     } else {
                    # code...
                  }
                }
          ?>



          <?php 
                $n =8;
                foreach($data_view as $fila)
                {  
                  if ($fila -> Url == 8 && $n == 8) {
                    $n += 1;
                      ?>
                        <li class="treeview" id="Contabilidad">
                        <a href="#">
                         <i class="fa fa-calculator" aria-hidden="true"></i><span>Contabilidad</span>
                          <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                             <?php
                              foreach($data_view as $fila2)
                              {

                                  switch ($fila2 ->ID) {
                                        case '29':?>
                                         <li id="acien" class="" ><a href="<?= base_url('index.php/Contabilidad') ?>"><i id="a_c_i_e_n" class="fa fa-list-alt"></i> Contabilidad</a></li>

                                       <?php break;
                                        case '30':?>
                                         <li id="PL"><a href="<?= base_url('index.php/PlanCuenta') ?>"><i id="P_L"  class="fa fa-deaf" aria-hidden="true"></i>Plan de Cuenta</a></li>

                                        <?php  break;
                                  }
                              }
                              ?>
                            </ul>
                          </li>
                      <?php
                     } else {
                    # code...
                  }
                }
          ?>



        <?php 
                $n = 10;
                foreach($data_view as $fila)
                {  
                  if ($fila -> Url == 10 && $n == 10) {
                    $n += 1;  
                      ?>
                        <li class="treeview" id="Seguridad">
                          <a href="#">
                           <i class="fa fa-user-secret" aria-hidden="true"></i><span>Seguridad</span>
                            <i class="fa fa-angle-left pull-right"></i>
                          </a>
                          <ul class="treeview-menu">
                           <?php 
                            foreach($data_view as $fila2)
                            {

                              switch ($fila2 ->ID) {
                             case '91':?>
                             <li id="cambios" class="" ><a href="<?= site_url('Cambios')?>"><i id="c" class="fa fa-users"></i> Administrar Cambios</a></li>

                             case '88':?>
                             <li id="Cliente" class="" ><a href="<?= site_url('Cliente')?>"><i id="c" class="fa fa-users"></i> Aministrar Cliente</a></li>
                             
                             <?php break;
                             case '89':?>
                             <li id="Proveedor" class="" ><a href="<?= base_url('index.php/Proveedor') ?>"><i id="p" class="fa fa-truck"></i> Administrar Proveedor</a></li>
                             
                             <?php break;
                             case '90':?>
                             <li id="Empleado" class="" ><a href="<?= base_url('index.php/Empleado') ?>"><i id="e" class="fa fa-gavel"></i> Administrar Empleado</a></li>
                             
                             <?php  break;  
                             
                             case '98':?>
                             <li id="au" class="" ><a href="<?= base_url('index.php/S1') ?>"><i id="_user" class="fa fa-circle-o"></i> Administrar Usuario</a></li>
                             <?php break;
                             case '99':?>
                             <li id="ae" class=""><a href="<?= base_url('index.php/S2') ?>"><i id="_emp" class="fa fa-circle-o"></i> Administrar Empresa </a></li>
                             <?php  break;  
                             case '100':?>
                             <li id="ae" class=""><a href="<?= base_url('index.php/S3') ?>"><i id="_Per" class="fa fa-circle-o"></i> Administrar Permiso </a></li>
                             <?php  break;  

                              }
                            }
                            ?>
                          </ul>
                        </li>
                      <?php  
                     } else {
                    # code...
                  }
                }
          ?>
          
          <?php endif ?>
          </ul>
         
        </section>
        <!-- /.sidebar -->
      </aside>
