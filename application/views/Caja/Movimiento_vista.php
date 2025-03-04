      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <small>Listado Caja</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Caja</li></li>
            <li class="active">Movimiento</li>
          </ol>
        </section> 
                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <!-- AREA CHART -->
              <div class="box box-primary">
                <div class="box-header with-border">
                     <h3 class="box-title">Registro de Caja</h3>
                 <div class="box-tools pull-right">
                     <div class="dt-buttons btn-group">
                          <div class="hidden-phone">
                          <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('Caja')">
                          <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                        <a href="Reporte_exel/Caja_list" class="btn btn-success btn-xs" tabindex="0" aria-controls="datatable-buttons">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>  Excel
                        </a>
                          </div>
                      </div>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                   <!-- tabla inicial -->
                  <div class="box">
                    <div class="box-body table-responsive no-padding">
                      <table  class="table table-striped table-bordered" cellspacing="30" width="100%"  id="registro_caja_ajax">
                        <thead>
                          <tr class="">
                            <th ><i class  ="fa fa-slack" aria-hidden="true"></i> Caja</th>
                            <th ><i class="fa fa-folder-open" aria-hidden="true"></i>  Apertura</th>
                            <th ><i class="fa fa-folder" aria-hidden="true"></i>  Cierre</th>
                            <th ><i class="fa fa-money" aria-hidden="true"></i>  Monto Inicial</th>
                            <th ><i class="fa fa-money" aria-hidden="true"></i>  Monto Final</th>
                            <th ><i class="fa fa-user-secret" aria-hidden="true"></i> Usuario</th>
                            <th ><i class ="fa fa-gears"></i> Acciones</th>
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
<div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->