
<style>
.loading-message {
  font-size: 17px;
  padding: 10px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 9999;
  color: #555; /* color de letra */
}

.loading-message::after {
  content: "";
  display: block;
  width: 20px;
  height: 20px;
  margin: 10px auto;
  border-radius: 50%;
  border: 4px solid #ccc;
  border-top-color: #333;
  animation: spin 1s ease-in-out infinite; /* animación de carga */
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 class="">
      Listados de Compras
      <!-- <i class="" id="accion" aria-hidden="true">&nbsp;&nbsp;Comprar</i>  -->
    </h1>
<!--             <H5 class="col-md-10" id="atajos" style="display: none">
  <span class="text-danger">CTRL + Q = Proveedor </span> |   <span class="text-danger">CTRL + B = Guardar </span> |   <span class="text-danger">CTRL + M = Monto </span> |   <span class="text-danger">CTRL + Z = Productos </span></H5> -->
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
    <li class="active">Comprar</li></li>
    <li class="active">Comprar</li>
  </ol>
</section> 

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- AREA CHART -->
      <div class="box box-primary">
        <div class="box-header with-border">



        <div class="box-tools pull-left">
            <div class="input-group input-group-sm hidden-xs" style="">
            <form id="listaCompras" class="form-inline">
                <div class="form-group">
                  <select id="search-estatus" class="form-control" name="estatus" placeholder="Tipos de Cobros">
                  <option value="">Todos</option>
                    <option value="2">No Pagados</option>
                    <option value="0">Pagados</option>
                    <option value="4">Anulados</option>


                  </select>
                </div>
                <div class="form-group">
                  <input type="text" id="search-ruc"  name="ruc" class="form-control" placeholder="Buscar por RUC del cliente">
                </div>
                <div class="form-group">
                  <input  type="text" id="search-factura" name="factura" class="form-control" placeholder="Buscar por factura">
                </div>
                <div class="form-group">
                        <div class='input-group date' id='datetimepicker1'>
                        <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
               </span>
                    <input required type='text' class="form-control " id="anho" name="anho"  value="<?php echo date("m-Y");?>"/>
                                <span class="input-group-addon clear-input">
                               <i class="fa fa-times"></i>
                                </span>

                  </div>

                    </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
              </form>
            </div>
          </div>












          <div class="box-tools pull-right">
            <div class="dt-buttons btn-group">
              <div class="hidden-phone">
                <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('compras')">
                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                  <a class="btn btn-success btn-xs" href="Reporte_exel/compras" title="Exportar a EXEL" >
                    <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                  </a></div>
                </div>

                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div><br>
            <!-- detalles -->
            <div class="collapse" id="collaje_detalle">
              <section class="invoice">
                <!-- title row -->
                <div class="row">
                  <div class="col-xs-12">
                    <h4 class="">
                      <i class="fa fa-globe"></i> Orden de Compra.
                      <a data-toggle="collapse" href="#collaje_detalle" aria-expanded="false" controls="collaje_detalle"> <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
                      <small class="pull-right">Fecha Entrega: <d id="fecha"></d> </small>
                    </h4>
                  </div><!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row invoice-info">
                  <div class="col-sm-4 invoice-col">
                    <address>
                      <strong>Usuario: </strong> <strong id="user"></strong>
                    </address>
                  </div><!-- /.col -->
                  <div class="col-sm-6 invoice-col">
                    <address>
                      <strong id="">Proveedor:&nbsp;</strong>
                      <small><d id="nom">&nbsp;</d><d id="nom"></d></small>
                      <small>&nbsp;&nbsp;Tel: <d id="tel"></d> </small>
                    </address>
                  </div><!-- /.col -->
                  <div class="col-sm-2 invoice-col">
                    <b>Orden</b> <b id="ordenid"></b><br>
                    <br>
                  </div><!-- /.col -->
                </div><!-- /.row -->

                <!-- Table row -->
                <div class="row">
                  <div class="col-xs-12 table-responsive" id="view">

                  </div><!-- /.col -->
                </div><!-- /.row -->

                <div class="row">
                  <!-- accepted payments column -->
                  <div class="col-xs-6">
                  </div><!-- /.col -->
                  <div class="col-xs-6">
                    <div class="table-responsive">
                      <table class="table">
                        <tbody>
                          <tr>
                            <th>Monto total:</th>
                            <td>₲ <d id="ttt"></></td>
                            </tr>
                          </tbody></table>
                        </div>
                      </div><!-- /.col -->
                    </div><!-- /.row -->
                  </section>
                </div>
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
                      <div id="loading-message" style="display:none;">Cargando...</div>
                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_compra">
                        <thead>
                          <tr>
                            <th ></th>
<th><i class="fa fa-file-text"></i> Comprobante</th>
<th><i class="fa fa-truck"></i> Proveedor</th>
<th><i class="fa fa-money-check"></i> Contado/Crédito</th>
<th><i class="fa fa-calendar"></i> Fecha</th>
<th><i class="fa fa-file-text"></i> Estado</th>
<th><i class="fa fa-money"></i> Monto Total</th>
<th><i class="fa fa-cogs"></i> Acciones</th>
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

