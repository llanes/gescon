    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">NAVEGACION</li>
            <li class="treeview" id="Caja">
              <a href="#">
                <i class="fa fa-cubes" aria-hidden="true"></i>
                <span>Administrar Caja</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="c_a" ><a href="CA"><i id="_c_a" class="fa fa-folder-open"></i> Cajas Activas</a></li>
                <li id="a_c" ><a href="CA1"><i id="_a_c" class="fa fa-folder-open"></i> Caja Chica</a></li>
                <li id="m_c"><a href="CA2"><i id="_m_c"  class="fa fa-info"></i> Listados de Cajas</a></li>
                <li id="_his"><a href="CA3"><i id="_his_"  class="fa fa-history"></i> Historial</a></li>
              </ul>
            </li>
            <li class="treeview" id="Producto">
              <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>Administrar Productos</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="pr" ><a href="Producto"><i id="_pro" class="fa fa-shopping-cart"></i> Productos</a></li>
                <li id="descuen" ><a href="Descuento"><i id="d_escue_n" class="fa fa-venus"></i> Aplicar Descuentos</a></li>
                <li id="PRO" ><a href="Producciones"><i id="P_R_O" class="fa fa-american-sign-language-interpreting " aria-hidden="true"></i>Produciones</a></li>
                <li id="st"><a href="<?php echo base_url('Stock') ?>"><i id="_stc"  class="fa fa-stack-overflow"></i> Notificaciones de Stock</a></li>
                <li id="ca"><a href="Categoria"><i id="_cate"  class="fa fa-creative-commons"></i>Categorias</a></li>
                <li id="ma"><a href="Marca"><i id="_mar"  class="fa fa-magnet"></i>    Marcas</a></li>
                 <li id="perd"><a href="P_perdido"><i id="_perd"  class="fa fa-magnet"></i>Productos Dañado</a></li>
                 <li id="inven"><a href="Inventario"><i id="_inven"  class="fa fa-list-ol"></i>Inventario</a></li>
              </ul>
            </li>
              <li class="treeview" id="Orden">
              <a href="#">
                <i class="fa fa-first-order"></i>
                <span>Gestionar Notas</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="odv" ><a href="O_Venta"><i id="o_d_v" class="fa fa-shopping-cart"></i>Orden de Venta</a></li>
                <li id="odc"><a href="O_Comprar"><i id="o_d_c"  class="fa fa-shopping-bag"></i>Orden de Compra</a></li>
                <li id="ndr"><a href="Remision"><i id="n_d_r"  class="fa fa-shopping-bag"></i>Notas de Remision</a></li>
              </ul>
            </li>
            <li class="treeview" id="Compra">
              <a href="#">
                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                <span>Compras</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                
                <li id="COMPRA"><a href="Comprar"><i id="_COM" class="fa fa-shopping-bag"></i>Compra</a></li>
                <li id="CPA"><a href="ComprasLista"><i id="C_P_A" class="fa fa-list"></i>Listar Compras</a></li>
                <li id="CDE" ><a href="Deudae"><i id="C_D_E" class="fa fa-credit-card"></i>Deuda Empresa</a></li>

                <!-- <li id="ANUL"><a href="Anulados"><i id="A_N_L"  class="fa fa-times-circle"></i>Comprobantes Anulados</a></li> -->
                <li id="DEV"><a href="CDevolucion"><i id="D_E_V"  class="fa fa-retweet"></i>Nota de Credito</a></li>
                <!-- Agregadas opciones adicionales -->
                <li id="INFOCOMPRAS"><a href="InformesCompras"><s><i id="I_N_F_O" class="fa fa-file-text"></i>Informes de Compras</s></a></li> 
                <li id="PEDIDOS"><a href="SeguimientoPedidos"><s><i id="P_E_D" class="fa fa-list-alt"></i>Seguimiento de Pedidos</s></a></li> 
                <li id="INVENTARIOCOMPRAS"><a href="InventarioCompras"><s><i id="I_N_V_E_N_T"></i>Inventario de Compras</s></a></li> 
                <li id="PROVEEDORES"><a href="GestionProveedores"><s><i id="P_R_O_V" class="fa fa-truck"></i>Gestión de Proveedores</s></a></li> 
                <li id="ALERTASCOMPRAS"><a href="AlertasCompras"><s><i id="A_L_E_R_T_A_S" class="fa fa-bell"></i>Alertas y Notificaciones</s></a></li>

              </ul>
            </li>
            <li class="treeview" id="Venta">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Ventas</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                <li id="VENTA"><a href="Vender"><i id="V_T_A" class="fa fa-shopping-cart"></i>Venta</a></li>
                <li id="VPA"><a href="VentaLista"><i id="V_P_A" class="fa fa-list"></i> Listar Ventas</a></li>
                <li id="CDC"><a href="Deudac"><i class="fa fa-credit-card"></i> Deuda Cliente</a></li>
                <li id="NUL"><a href="Anulado"><i class="fa fa-times-circle"></i> Comprobantes Anulados</a></li>
                <li id="DEVL"><a href="V_D"><i class="fa fa-retweet"></i> Devoluciones de Clientes</a></li>

                    <!-- Agregadas opciones adicionales -->
                    <li id="INFOVENTAS"><a href="InformesVentas"><s><i id="I_N_F_O" class="fa fa-file-text"></i>Informes de Ventas</s></a></li> 
                    <li id="PEDIDOSVENTAS"><a href="SeguimientoPedidosVentas"><s><i id="P_E_D" class="fa fa-list-alt"></i>Seguimiento de Pedidos</s></a></li> 
                    <li id="INVENTARIOVENTAS"><a href="InventarioVentas"><s><i id="I_N_V_E_N_T"></i>Inventario de Ventas</s></a></li> 
                    <li id="CLIENTES"><a href="GestionClientes"><s><i id="C_L_I_E_N_T" class="fa fa-users"></i>Gestión de Clientes</s></a></li> 
                    <li id="ALERTASVENTAS"><a href="AlertasVentas"><s><i id="A_L_E_R_T_A_S" class="fa fa-bell"></i>Alertas y Notificaciones</s></a></li>
                </ul>
            </li>

           <li class="treeview" id="PagosCobros">
              <a href="#">
               <i class="fa fa-archive" aria-hidden="true"></i>
                <span>Pagos | Cobros</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                 <li id="Cob" ><a href="Cobros"><i id="C_o_b" class="fa fa-handshake-o"></i> Cobros</a></li>
                 <li id="Codc" ><a href="CobroDC"><i id="C_o_d_c" class="fa fa-handshake-o"></i> Cobros Deuda Cliente</a></li>
                 <li id="Pag"><a href="Pagos"><i id="P_a_g" class="fa fa-handshake-o" aria-hidden="true"></i> Pagos</a></li>
                 <li id="PagE"><a href="PagoDE"><i id="P_a_g_e" class="fa fa-handshake-o" aria-hidden="true"></i> Pagos Deuda Empresa</a></li>
              </ul>
            </li>
           <li class="treeview" id="Modulos">
              <a href="#">
                <i class="fa fa-university" aria-hidden="true"></i>  
                <span>Bancos</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
               <li id="BAN" ><a href="Bancos"><i id="B_A_N" class="fa fa-university"></i>Administrar Bancos</a></li>
               <li id="Mov"><a href="Mov"><i id="M_o_v" class="fa fa-arrows" aria-hidden="true"></i>Movimientos Bancos</a></li>
              </ul>
            </li>



              <li class="treeview" id="Contabilidad">
              <a href="#">
               <i class="fa fa-calculator" aria-hidden="true"></i><span>Contabilidad</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="acien" class="" ><a href="Contabilidad"><i id="a_c_i_e_n" class="fa fa-list-alt"></i> Módulo de Contabilidad</a></li>
                <li id="PL"><a href="PlanCuenta"><i id="P_L"  class="fa fa-deaf" aria-hidden="true"></i>Plan de Cuenta</a></li>
              </ul>
            </li>
            <li class="treeview" id="Seguridad">
              <a href="#">
               <i class="fa fa-wrench"></i><span>Administrar</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                  <li id="cambios" class="" ><a href="Cambios"><i id="c" class="fa fa-users"></i> Gestión de Cambios</a></li>
                <li id="Cliente" class="" ><a href="Cliente"><i id="c" class="fa fa-users"></i> Gestión de Cliente</a></li>
                <li id="Proveedor" class="" ><a href="Proveedor"><i id="p" class="fa fa-truck"></i> Gestión de Proveedor</a></li>
                <li id="Empleado" class="" ><a href="Empleado"><i id="e" class="fa fa-gavel"></i> Gestión de Empleado</a></li>
                <li id="au" class="" ><a href="S1"><i id="_user" class="fa fa-circle-o"></i> Gestión de Usuario</a></li>
                <li id="ae" class=""><a href="S2"><i id="_emp" class="fa fa-circle-o"></i> Gestión de Empresa </a></li>
                <li id="ap" class=""><a href="S3"><i id="_Per" class="fa fa-circle-o"></i> Gestión de Permiso </a></li>
              </ul>
            </li>

            <li class="treeview" id="Reportes">
              <a href="#">
               <i class="fa fa-bar-chart"></i><span>Reportes</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
               <li id="reporcaja" class="" ><a href="<?= site_url('Reporte/Cajas')?>"><i id="repor_caja" class="fa fa-line-chart"></i> Informes  de Caja</a></li>
                  <li id="reportecompra" class="" ><a href="<?= site_url('Reporte/Compra')?>"><i id="reporte_compra" class="fa fa-line-chart"></i> Informes  de Compras</a></li>
                <li id="reporteventa" class="" ><a href="<?= site_url('Reporte/Venta/') ?>"><i id="reporte_venta" class="fa fa-line-chart"></i> Informes  de Ventas</a></li>
                <li id="repordecolucion" class="" ><a href="<?= site_url('Reporte/devoluciones/') ?>"><i id="repor_decolucion" class="fa fa-line-chart"></i> Informes  de Devoluciones</a></li>
                <li id="reporcobros" class="" ><a href="<?= site_url('Reporte/Cobros') ?>"><i id="repor_cobros" class="fa fa-line-chart  "></i> Informes  de Cobros</a></li>
                <li id="reportepagos" class=""><a href="<?= site_url('Reporte/Pagos') ?>"><i id="reporte_pagos" class="fa fa-line-chart "></i> Informes  de Pagos </a></li>
                <li id="reportebancario" class=""><a href="<?= site_url('Reporte/Bancario') ?>"><i id="reporte_bancario" class="fa fa-line-chart  "></i> Informes   Bancario </a></li>
              </ul>
            </li>
 <!-- Módulo: Configuración -->
 <li class="treeview" id="Configuracion"  >
    <a href="#">
      <i class="fa fa-cog" aria-hidden="true"></i>
      <span>Configuración</span>
      <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
    <li id="conf_impresora" class="" ><a href="<?= site_url('configuracion')?>"><i id="confgiImpresoras" class="fa fa-print"></i> Impresoras</a></li>
    </ul>
  </li>

  <!-- Módulo: Ayuda o Soporte -->
  <li class="treeview" id="AyudaSoporte">
    <a href="#">
      <i class="fa fa-question-circle"></i>
      <span>Ayuda | Soporte</span>
      <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
      <!-- Agregar aquí opciones de ayuda o soporte si es necesario -->
    </ul>
  </li>
  
        
        
        
        </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
