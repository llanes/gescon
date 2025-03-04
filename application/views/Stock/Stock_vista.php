      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Producto</li></li>
            <li class="active">Stock</li>
          </ol>
        </section> 
                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <!-- AREA CHART -->
              <div class="box box-primary">
                <div class="box-header with-border">
                              <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-sm btn-success btn-flat pull-left" href="javascript:void(0);" onclick="stocktodas()">
                                  <i class="fa fa-list-alt" aria-hidden="true"></i> Listar Todas</a>
                                  <a class="btn btn-sm btn-danger btn-flat pull-left" href="javascript:void(0);" id="stocktodas" onclick="stocktodas(0)">
                                  <i class="fa fa-list-alt" aria-hidden="true"> Listar Alertas </i> 
                                  </a></div>
                              </div>
                 <div class="box-tools pull-right">
                              <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('stock')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" href="Reporte_exel/stock" title="Exportar a EXEL">
                                  <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                                  </a></div>
                              </div>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                   <!-- tabla inicial -->
                  <div class="box">
                    <div class="box-body table-responsive no-padding">
                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_Stock">
                        <thead>
                          <tr>
                            <th ><i class  ="fa fa-slack" aria-hidden="true"></i></th>
                            <th ><i class ="ion-ios-copy"></i>  Nombre</th>
                            <th ><i class ="fa fa-align-center"></i>  Totales</th>
                            <th ><i class ="fa fa-align-center"></i>  Total Deposito</th>
                            <th ><i class ="fa fa-align-center"></i>  Total Stock</th>
                            <th ><i class ="fa fa-align-center"></i> Precio Venta</th>
                            <th ><i class ="fa fa-outdent"></i> Unidad || Medida</th>
                          </tr>
                        </thead>
                        <tbody>
                      </tbody>
                      </table>
                    </div><!-- /.box-body -->
                  </div>
                <!-- final tabla -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div></div>
        </section>
    </div><!-- ./wrapper -->
