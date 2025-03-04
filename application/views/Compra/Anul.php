      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
       <h1>
            <small>Comprobantes Anulados</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Compras</li></li>
            <li class="active">Comprobantes Anulados</li>
          </ol>
       </section> 
        <div class="col-md-12">
        </div>
                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <section class="content">
          <div class="row">
                <div class="col-md-12">
                  <!-- AREA CHART -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                          <div class="box-tools pull-right">
                             <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('compranull')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" href="compranull" title="Exportar a EXEL" >
                                  <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                                  </a></div>
                              </div>
                                 
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                  </div>
                                </div><br>
                                <!-- detalles -->
                                <!-- fin detalles -->
                               <div class="box-body">
                                   <!-- tabla inicial -->
                                  <div class="box">
                                    <div class="box-body table-responsive no-padding">
                                    <style type="text/css" media="screen">
                                        td.details-control {
                                            background: url('<?php echo base_url('/content/details_open.png');?>') no-repeat center center;
                                            cursor: pointer;
                                        }
                                        tr.shown td.details-control {
                                            background: url('<?php echo base_url('/content/details_close.png');?>') no-repeat center center;
                                        }
                                     </style>
                                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_nul">
                                        <thead>
                                          <tr>
                                           <th ></th>
                                           <th ><i class="fa fa-bars"></i>  Nº Comprobante</th>
                                           <th ><i class="fa fa-truck"></i>  Proveedor</th>
                                           <th ><i class="fa fa-money"></i>  Monto Total</th>
                                           <th ><i class="fa fa-cogs" ></i> Acciones</th>
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
                    </div>
                </div>
        </section>
<div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->
    
