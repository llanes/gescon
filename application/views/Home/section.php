      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Gescom
            <small>Control</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Tablero</li>
          </ol>
        </section>

                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo $Ordenc ?><sup style="font-size: 20px"></sup></h3>
                  <p>Ordenes de Compra</p>
                </div>
                <div class="icon">
                  <i class="fa fa-first-order" aria-hidden="true"></i>
                </div>
                <a href="<?= site_url('O_Comprar')?>" class="small-box-footer">Ordenes  <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo $Compra ?><sup style="font-size: 20px"></sup></h3>
                  <p>Compras</p>
                </div>
                <div class="icon">
                  <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                </div>
                <a href="<?= site_url('Comprar')?>" class="small-box-footer">Compras <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $Cliente-1; ?></h3>
                  <p>Clientes</p>
                </div>
                <div class="icon">
                  <i class="fa fa-users"></i>
                </div>
                <a href="<?= site_url('Cliente')?>" class="small-box-footer"> Clientes <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $Prove ?></h3>
                  <p>Proveedores</p>
                </div>
                <div class="icon">
                  <i class="fa fa-truck"></i>
                </div>
                <a href="<?= site_url('Proveedor')?>" class="small-box-footer">Proveedor <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
           <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo $Venta ?><sup style="font-size: 20px"></sup></h3>
                  <p>Venta</p>
                </div>
                <div class="icon">
                  <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="<?= site_url('Vender')?>" class="small-box-footer"> Ventas <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo $Ordenv ?><sup style="font-size: 20px"></sup></h3>
                  <p>Orden de Venta</p>
                </div>
                <div class="icon">
                  <i class="fa fa-first-order" aria-hidden="true"></i>
                </div>
                <a href="<?= site_url('O_Venta')?>" class="small-box-footer"> Ordenes <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
          </div><!-- /.row -->
      
        </section>
    </div><!-- ./wrapper -->