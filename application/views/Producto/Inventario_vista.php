      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">

           <h1>Productos
            <small>Inventario</small> ||<small>Alertas</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <!-- <li class="active">Seguridad</li></li> -->
            <li class="active">Administrar Productos</li>
          </ol>
        </section> 

                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
       <section class="content">
          <div class="row">
              <div class="col-md-12">
                 <div role="tabpanel">
                    <!-- Nav tabs -->
                     <ul class="nav nav-pills  nav-justified" id="maincontent" role="tablist">
                      <li role="presentation" class="active">
                        <a href="#home" aria-controls="home" role="tab" class="des" data-toggle="tab">Inventario Total</a>
                      </li>
                      <li role="presentation">
                        <a href="#tab" aria-controls="tab" role="tab" class="ven" data-toggle="tab">Inventario Productos en Alertas</a>
                      </li>
                    </ul>
                  </div>
             <div class="box box-primary">

                                 <div role="tabpanel" >
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                      <div role="tabpanel" class="tab-pane active" id="home">
                                <div class="box-header with-border">
                                  <div class="box-tools pull-right">
                                   <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('listatodo')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a type="button" href="Reporte_exel/listatodo" class="btn btn-success btn-xs" title="Exportar a EXEL" >
                                  <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                                              </a></div>
                              </div>
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                  </div>
                                </div><br>
                                        <div class="box">
                                          <div class="box-body table-responsive no-padding">
                                            <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="listatodo">
                                              <thead>
                                                <tr>
                                                  <th ><i class="fa fa-slack" aria-hidden="true"></i></th>
                                                  <th ><i class ="fa fa-barcode"></i>  Codigo</th>
                                                  <th ><i class ="fa fa-shopping-cart"></i>  Producto</th>
                                                  <th ><i class ="fa fa-check-square-o"></i> Precio </th>
                                                  <th ><i class ="fa fa-check-square-o"></i> Iva </th>
                                                  <th ><i class ="fa fa-file-image-o"></i> Cantidad</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                            </tbody>
                                            </table>
                                          </div><!-- /.box-body -->
                                        </div>
                                      </div>
                                      <div role="tabpanel" class="tab-pane" id="tab">
                                <div class="box-header with-border">
                                  <div class="box-tools pull-right">
                                   <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('listatodo/1')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a type="button" href="Reporte_exel/listatodo/1" class="btn btn-success btn-xs" title="Exportar a EXEL" >
                                  <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                                              </a></div>
                              </div>
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                  </div>
                                </div><br>
                                          <div class="box">
                                            <div class="box-body table-responsive no-padding">
                                              <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="bajostock">
                                                <thead>
                                               <tr>
                                                  <th ><i class="fa fa-slack" aria-hidden="true"></i></th>
                                                  <th ><i class ="fa fa-barcode"></i>  Codigo</th>
                                                  <th ><i class ="fa fa-shopping-cart"></i>  Producto</th>
                                                  <th ><i class ="fa fa-check-square-o"></i> Precio </th>
                                                  <th ><i class ="fa fa-check-square-o"></i> Iva </th>
                                                  <th ><i class ="fa fa-file-image-o"></i> Cantidad</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                              </tbody>
                                              </table>
                                            </div><!-- /.box-body -->
                                          </div>
                                      </div>
                                    </div>
                                  </div>
              </div><!-- /.box -->
              </div>
          </div>

        </section>

</div>




<div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->
    
