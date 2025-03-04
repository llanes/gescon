      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
  <style type="text/css" media="screen">
    .select2-result-repository { 
      padding-top: 4px;
      padding-bottom: 3px; 
      border: 1px solid lightgray;
    }
    .select2-result-repository__avatar { 
      float: left;
      width: 80px;
      margin-right: 10px; 
    }
    .select2-result-repository__avatar img { 
      width: 100%; 
      height: 50px; 
      border-radius: 2px; 
    }
    .select2-result-repository__meta { margin-left: 70px; }
    .select2-result-repository__title { color: black; font-weight: bold; word-wrap: break-word; line-height: 1.1; margin-bottom: 4px; }
    .select2-result-repository__forks, 
    .select2-result-repository__stargazers,
    .select2-result-repository__watchers { margin-right: 1em; }
    .select2-result-repository__forks, 
    .select2-result-repository__stargazers, 
    .select2-result-repository__watchers,
    .select2-result-repository__percent { display: inline-block; color: #000; font-size: 11px; }
    .select2-result-repository__description { font-size: 13px; color: #000; margin-top: 4px; }

    .col-ch{
          padding-right: 7px;
          padding-left: 10px;
    }
    .form-group {
        margin-bottom: 5px;
    }
    .danger{
      background-color: #ffa5002b;
    }
    .table > thead > tr > th,
    .table > tbody > tr > th,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > tbody > tr > td,
    .table > tfoot > tr > td {
      padding: 3px;
      line-height: 1.42857143;
      vertical-align: top;
      border-top: 1px solid #ddd;
    }


  </style>
        <section class="content-header">
                           <h1 > 
                            <button id="add" class="btn btn-sm btn-success" onclick="_add()" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                             <i class="fa fa-shopping-cart" aria-hidden="true" id="tituloboton">&nbsp;Agregar Nota</i> 
                            </button>
                           </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Gestor Pedidos</li></li>
            <li class="active">Notas de Remision</li>
          </ol>
        </section> 
        <div class="col-md-12 col-ch">
                <div class="collapse" id="collapseExample">
                <section class="content-header">
                </section>
                    <form method="POST" action="#" accept-charset="UTF-8" name="from_Remision" id="from_Remision">
                    <input name="idOrden" id="idOrden" type="hidden" value="">
                            <div class="row">
                                <div class="col-md-9 col-ch">
                                    <div class="box panel-default">
                                        <div class="panel-body">
                                            <div class="row">
                                                    <div class="col-md-12 col-ch">
                                                    <div class="form-group">
                                                            <div class="input-group input-sm ">
                                                                <span class="input-group-btn">
                                                                 <button id="seat2" class="btn btn-default" type="button" data-select2-open="single-prepend-text_2">
                                                                        <span class="fa fa-shopping-cart"></span>
                                                                    </button>
                                                                </span>
                                                                <select id="js-example-data-ajax" autofocus class="form-control select2-allow-clear producto" name="changeProduc" title="Seleccione">

                                                                </select>
                                                              </div>
                                                             <span class ="PRO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>

                                                    </div>

                                            </div>
                                            <div class="col-md-12 col-ch" id="detalle">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-ch">
                                    <div class="panel panel-default cls-panel">
                                    <div class="panel-heading">
                                            <h3 class="panel-title">
                                                Condiciones
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group ">
                                                <label for="termsOfPayment">Tipos de Notas</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon" data-toggle="tooltip" data-placement="top"  title="Estado"><i class="fa fa-file"></i></span>
                                                    <select name="Estado" id="Estado" class="form-control" required >
                                                        <option selected></option>
                                                        <option value="3">Nota de Entrada Productos</option>
                                                        <option value="4">Nota de Salida Productos</option>
                                                        <option value="5">Nota de Devolucion Productos</option>
                                                        <option value="6">Entrada Productos En Produccion</option>
                                                        <option value="7">Sin Accion</option>
                                                    </select>
                                                </div>
                                                <span class ="ESTA text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                            </div>
                                            <div class="form-group">
                                            <label> Cliente </label>
                                            <div class="input-group select2-bootstrap-prepend">
                                               <span class="input-group-btn">
                                                  <button id="seartt" onclick="addclien();" class="btn btn-default" type="button" data-toggle="tooltip" data-placement="top"  title="Nuevo Cliente">
                                                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                  </button>
                                                </span>
                                                <select required id="single-prepend-text" class="form-control select2-allow-clear cliente" tabindex="-1" aria-hidden="true" name="changecliente" title="Seleccione un Cliente">
                                                                   <?php 
                                                                    foreach($Cliente as $key => $value)
                                                                    {
                                                                      ?>
                                                                      <option selected value="<?php echo $value -> idCliente ?>"><?php echo $this->mi_libreria->getSubString($value -> Nombres,15);?>  (<?php echo $value -> Ruc;?>) </option>
                                                                      <?php
                                                                    }
                                                                    ?>
                                                </select>
                                                <span class ="PROVE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                              </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Entrega">Fecha</label>
                                               <div class='input-group date' id='datetimepicker6'>
                                                  <div class="input-group-btn">
                                                      <button type="button" class="btn btn-default date-set" data-toggle="tooltip" data-placement="top"  title="Fecha Entrega"><i class="fa fa-calendar"></i></button>
                                                  </div>
                                                  <input required type='text' class="form-control " id="Entrega" name="Entrega"  value="<?php echo date("d-m-Y");?>"/>

                                              </div>
                                                <span class ="ENTRE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                            </div>

                                            <div class="form-group">
                                                <label for="remarks">observaciones</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon" data-toggle="tooltip" data-placement="top"  title="Observaciones"><i class="fa fa-comment"></i></span>
                                                    <textarea class="form-control"  maxlength="50" rows="2" placeholder="observaciones" name="observac" type="text" id="observac"></textarea>
                                                </div>
                                                <span class ="OBSER text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="controval" name="controval" value="">
                    </form>
                </div>
        </div>
                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <section class="content">
          <div class="row">
                <div class="col-md-12 col-ch">
                  <!-- AREA CHART -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                          <div class="box-tools pull-right">
                            <div class="btn-group">
                     <div class="dt-buttons btn-group">
                          <div class="hidden-phone">
                          <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('remision')">
                          <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                          <a class="btn btn-success btn-xs" href="Reporte_exel/remision" title="Exportar a EXEL" >
                          <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                          </a></div>
                      </div>
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
                                            <i class="fa fa-globe" id="c_v"> </i> 
                                            <a data-toggle="collapse" href="#collaje_detalle" aria-expanded="false" controls="collaje_detalle"> <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
                                            <small class="pull-right">Fecha : <d id="fecha"></d> </small>
                                          </h4>
                                        </div><!-- /.col -->
                                      </div>
                                      <!-- info row -->
                                      <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong> </strong> <strong id=""></strong>
                                          </address>
                                        </div><!-- /.col -->
                                        <div class="col-sm-6 invoice-col">
                                          <address>
                                            <strong id="">Cliente:&nbsp;</strong>
                                             <small><d id="nom">&nbsp;</d><d id="cli"></d></small>
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
                                              <tbody><tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td>₲ <d id="mnt"></dtd>
                                              </tr>
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
                                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_Remisiones">
                                        <thead>
                                          <tr>
                                          <th ><i class ="fa fa-slack" aria-hidden="true"></i></th>
                                          <th ><i class ="fa fa-bars"></i>  Remisor</th>
                                          <th ><i class ="fa fa-bars"></i> Fecha </th>
                                          <th ><i class ="fa fa-bars"></i>  Condicion</th>
                                          <th ><i class ="fa fa-bars"></i>  Impote General</th>
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

            <div class="modal fade" id="modal-1">
              <div class="modal-sm1 modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only"></span>
                    </button>
                    <h4 class="modal-title">Nuevo Cliente</h4>
                  </div>
            <div id="alertas">
                  </div>
                  <form action="#" method="POS" id="inserc" accept-charset="utf-8">
                  <div class="modal-body">
                           <div class="col-md-12 .ol-md-offset-0">
                                <div class="form-group">
                                  <label>* Nombre </label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-user" aria-hidden="true"></i>
                                    </div>
                                       <input required title="Se necesita un nombre" maxlength="40" type ="text" id="nombre" name="nombre" class="form-control" autofocus autocomplete="off" placeholder="" pattern="[A-Za-z ]{3,100}"   >
                                  </div>
                                    <span class ="NOM text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div>

                                <div class="form-group">
                                  <label>* Telefono</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-phone"></i>
                                    </div>
                                     <input required title="ingrese telefono" type="text" class="form-control" id="Telefono" name="Telefono" data-inputmask='"mask": "(0999) 999-999"' data-mask/>
                                    </div>
                                  <span class ="TE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div>
                                <div class="form-group">
                                  <label>* RUC-CI </label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-user" aria-hidden="true"></i>
                                    </div>
                                    <input required title="Se necesita un ruc o CI" maxlength="11" type ="text" id="ruc" name="ruc" class="form-control" autofocus autocomplete="off" data-inputmask='"mask": "99999[9][9][9][9][9][9][9][9][9][9]-9"' data-mask/>
                                  </div>
                                    <span class ="RUC text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div>
                          </div>
                  </div>
                  <div class="modal-footer">
                  <div class="pull-right">
                  <button type="submit" id="loading" name="loading" class="btn btn-sm btn-success" data-loading-text="Procesando..." autocomplete="off" >
                            <span class  ="glyphicon glyphicon-floppy-disk" id="btnSave"  >Guardar</span> 
                     </button>&nbsp;&nbsp;
                     <button type ="reset"  class="btn btn-sm btn-info">
                            <i class     ="fa fa-refresh "></i> Limpiar
                     </button>&nbsp;
                     <button type ="button" class="btn btn-sm btn-danger" data-toggle="collapse"  data-dismiss="modal">
                            <span class  ="glyphicon glyphicon-floppy-remove"></span> Cancelar
                     </button>
                     </div>
                  </div>
                </div><!-- /.modal-content -->
                </form>
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
    </div><!-- ./wrapper -->
