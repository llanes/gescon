      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
              <button id="add" class="btn  btn-success btn-sm">
               <i class="" id="accion" aria-hidden="true">&nbsp;&nbsp;Producir</i> 
              </button>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Producciones</li></li>
            <li class="active">Produccion</li>
          </ol>
       </section> 
                 <div id="alerta">
                  </div>
        <div class="col-md-12">
          <div class="collapse" id="collapseExample" aria-expanded="true">
                 <span class="col-md-4">Son necesarias las etiquetas de los campos marcados con *</span>
               <form   method="post" name="formulario" id="Producir" action="<?php echo base_url();?>index.php/Producto/producto/adding" enctype="multipart/form-data">
                    <input name="idOrden" id="idOrden" type="hidden" value="">
                    <input type="hidden" name="date" id="date" value="<?php echo date("Y-m-d").' '.strftime( "%H:%M:%S", time() )?>">
                    <input type="hidden" name="newdate" id="newdate" value="<?php echo date("Y-m-d").' '.strftime( "%H:%M:%S", time() )?>">
                    <input type="hidden" name="Montoidproduct" id="Montoidproduct" value="">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="box panel-default">
                                        <div class="panel-body">
                                            <div class="row">
              
                                                        <div class="col-md-5 form-group">
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-btn">
                                                                        <button id="seartt" onclick="addprovee();" name="seartttt" class="btn btn-default" type="button" data-toggle="tooltip" data-placement="top"  title="Nuevo Cliente">
                                                                        <i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;Proveedor
                                                                        </button>
                                                                </span>
                                                                <select  id="selectproveedor" class="form-control proveedor" tabindex="-1" aria-hidden="true" name="proveedor" data-url='Proveedor/select2remote' title="Seleccione un Proveedor">
                                                                    <option></option>
                                
                                                                </select>
                                                              </div>
                                                             <span class ="PR text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>
         
                                                        <div class="col-md-7 form-group">
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-btn">
                                                                    <button id="seat2" class="btn btn-default" type="button" data-select2-open="single-prepend-text_2">
                                                                        <span class="text-danger">&nbsp;* Producto</span>
                                                                    </button>
                                                                </span>
                                                                <select required id="selectproductos" class="form-control productos" tabindex="-1" aria-hidden="true" data-url='Produccion/list_productos' data-id="1" name="productos">
                                                                    <option id="artes" ></option>
                                                                                
                                                                </select>
                                                              </div>
                                                              <input type="hidden" name="idProduct" id="idProduct" value="">
                                                            <span class ="COMP text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>                                                 

                                                        <div class="col-md-5 form-group">
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-btn">
                                                                        <button id="seartt" onclick="addReceta();" name="addReceta" class="btn btn-default" type="button" data-toggle="tooltip" data-placement="top"  title="Nuevo Recetas">
                                                                        <i class="fa fa-book"></i>&nbsp;Recetas&nbsp;&nbsp;&nbsp;&nbsp;
                                                                        </button>
                                                                </span>
                                                                <select  id="selectrecetas" class="form-control " tabindex="-1" aria-hidden="true" data-url='Produccion/listRecetas' name="recetas" title="Seleccione un Recetas">
                                                                    <option></option>
                                
                                                                </select>
                                                              </div>
                                                             <span class ="RC text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>
                                                        <div class="col-md-7 form-group">
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-btn">
                                                                    <button id="seartt" class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                                                                        <span class="">&nbsp;Ingredientes</span>
                                                                    </button>
                                                                </span>
                                                                <select id="selectIngredientes" autofocus class="form-control Ingredientes" name="Ingredientes" data-url="Produccion/list_productos" title="Seleccione un Ingredientes">
                                                                    <option></option>

                                                                </select>
                                                              </div>
                                                             <span class ="PRO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>
                                       
                                                          <div class="col-md-12" id="detalle">


                                                            
                                                          </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="panel panel-default cls-panel">

                                        <div class="panel-body">
                                        <div class="form-group">
                                          
    <label for="cantidad_producir" class='control-label text-sm'>Cantidad a producir</label>
    <div class="input-group input-group-sm">
        <div class="input-group-btn">
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-building"></i></button>
        </div>
        <input type="number" name="cantidad_producir" id="cantidad_producir" class="form-control" >
    </div>
</div>

<div class="form-group">
    <label class='control-label text-sm' for="fecha_produccion">Fecha de producción</label>
    <div class="input-group input-group-sm">
        <div class="input-group-btn">
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></button>
        </div>

<input type="datetime-local" name="fecha_produccion" id="fecha_produccion" class="form-control" required value="<?php echo date('Y-m-d H:i'); ?>">
  
    </div>
</div>
  

<div class="form-group">
    <label class='control-label text-sm' for="estado_produccion">Estado de producción</label>
    <div class="input-group input-group-sm">
        <div class="input-group-btn">
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-cogs"></i></button>
        </div>
        <select name="estado_produccion" id="estado_produccion" class="form-control select" required>
            <option value="1">Produciendo</option>
            <option value="2">Terminado</option>
        </select>
    </div>
    <span class="tipo text-danger"></span>
</div>

<div class="form-group">
    <label class='control-label text-sm' for="responsable_produccion">Responsable de producción</label>
    <div class="input-group input-group-sm">
        <div class="input-group-btn">
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-user"></i></button>
        </div>
        <input type="text" name="responsable_produccion" id="responsable_produccion" class="form-control" >
    </div>
</div>

<div class="form-group">
    <label class='control-label text-sm' for="tiempo_produccion">Tiempo de producción</label>
    <div class="input-group input-group-sm">
        <div class="input-group-btn">
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-clock-o"></i></button>
        </div>
        <input type="time" name="tiempo_produccion" id="tiempo_produccion" class="form-control">
    </div>
</div>

<div class="form-group">
    <label class='control-label text-sm' for="lotes">Lotes o números de serie</label>
    <div class="input-group input-group-sm">
        <div class="input-group-btn">
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-list-ol"></i></button>
        </div>
        <select required name="lotes" id="lotes" class="form-control select">
            <option value="">Seleccione un número de lote</option>
                <?php 
                foreach($lotes as $key => $value)
                {
                ?>
                <option value="<?php echo $value -> idNumero  ?>"><?php echo $value -> numero_lote;?>  (<?php echo $value -> numero_serie;?>) </option>
                <?php
                }
                ?>
        </select>
    </div>
</div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="iddp" id="iddp" value="">
                    </form>
          </div>
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
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('produccion')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" href="Reporte_exel/produccion" title="Exportar a EXEL" >
                                  <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                                  </a></div>
                              </div>
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                  </div>
                                </div><br>
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
                                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_Produccion">
                                        <thead>
                                          <tr>
                                           <th >Ing.</th>
                                           <th ><i class="fa fa-bars"></i>  Producto</th>
                                           <th ><i class="fa fa-truck"></i>  Fabricante</th>
                                           <th ><i class="fa fa-circle-o-notch"></i>  Estado</th>
                                           <th ><i class="fa fa-truck"></i>  Cantidad Producido</th>
                                           <th ><i class="fa fa-calendar"></i>  Fecha Produccion</th>
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
                <span class="">Son necesarias las etiquetas de los campos marcados con *</span>
                <div class="modal-content">
                  <div class="modal-header" id="mh">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only"></span>
                    </button>
                    <h4 class="modal-title">Nuevo Proveedor</h4>
                  </div>
                  <div id="alertas">
                  </div>
                  <form action="#" method="POS" id="inserc" name="inserc" accept-charset="utf-8">
                  <div class="modal-body">
                           <div class="col-md-12 .ol-md-offset-0">
                                <div class="form-group">
                                      <label>* RUC</label>
                                      <div class="input-group">
                                        <div class="input-group-addon">
                                          <i class="fa fa-bars" aria-hidden="true"></i>
                                        </div>
                                            <input required   type ="text" id="Ruc" name="Ruc" class="form-control "  autofocus autocomplete="off"  data-inputmask='"mask": "9999[9][9][9][9][9][9][9][9][9][9][-][9]"' data-mask  >
                                        </div>
                                       <span class ="Ruc text-danger" ></span>   
                                </div>

                                <div class="form-group">
                                  <label>* Razon Social</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-user" aria-hidden="true"></i>
                                    </div>
                                       <input required title="Se necesita un Razon Social" maxlength="40" type ="text" id="Razon_Social" name="Razon_Social" class="form-control" autofocus autocomplete="off"   autocomplete="off"  placeholder=""  >
                                  </div>
                                    <span class ="RS text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div>
                                <div class="form-group">
                                    <label>* Vendedor:</label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                      </div>
                                    <input required title="Se necesita un nombre" maxlength="30" type ="text" id="Vendedor" name="Vendedor" class="form-control" onfocus="autofocus" autocomplete="off"  placeholder="" pattern="[A-Za-z ]{3,100}"   >
                                    </div> 
                                      <span class ="VE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
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


            <div class="modal fade" id="mymodal" data-keyboard="false" data-backdrop="static">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div id="alertasmyproduc">
                  </div>
                  <form action="#" method="POST" accept-charset="utf-8"  name="myproduccion" id="myproduccion">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Terminar Proceso de producción</h4>
                     <span class="">Son necesarias las etiquetas de los campos marcados con *</span>
                  </div>
                  <div class="modal-body">
                    <div class="form-group col-md-10  col-md-offset-1" >
                      <label>* Cantidad Producido</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                              <i class="fa fa-stack-overflow" aria-hidden="true"></i>
                          </div>
                          <input required type="number" name="cantidadProducido" id="cantidadProducido" class="form-control" placeholder="Cantidad" max="10000">


                        </div>
                        <span class="CUO text-danger" style="display: none;"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                    </div>  
                    <input type="hidden" name="idProducto" id="idProducto" value="">   
                    <input type="hidden" name="idDetalle" id="idDetalle" value="">    
                    <input type="hidden" name="Monto_Total" id="Monto_Total" value="">  

                    </div>
                  <div class="modal-footer">
                  <div class="pull-right">
                     <button type="submit" id="loading_gt" name="add_add_" class="btn btn-sm btn-success" data-loading_gt-text="Procesando..." autocomplete="off" >
                            <span class  ="glyphicon glyphicon-floppy-disk" id="btnSave"  >Terminar</span> 
                     </button>&nbsp;&nbsp;
                     <button type ="reset"  class="btn btn-sm btn-info">
                            <i class     ="fa fa-refresh "></i> Limpiar
                     </button>&nbsp;
                     <button type ="button" class="btn btn-sm btn-danger" data-toggle="collapse"  data-dismiss="modal">
                            <span class  ="glyphicon glyphicon-floppy-remove"></span> Cancelar
                     </button>
                     </div>
                  </div>
                  </form>
                </div>
              </div>
            </div>

    <div class="modal fade bs-example-modal-lg_lg" id="generalterminar" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg_lg">
        <div class="modal-content">
          <div id="alertaterminar">
          </div>
          <form action="#" method="POST" accept-charset="utf-8"  name="general_produccion" id="general_produccion">
            <div class="modal-header">
                <div class="col-md-4 ">
                    <div class="form-group" >
                      <label>* Cantidad Producto Producido</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                              <i class="fa fa-stack-overflow" aria-hidden="true"></i>
                          </div>
                          <input required type="number" name="cantidadProduc" id="cantidadProduc" class="form-control" placeholder="Cantidad Stock" maxlength="15" required="required" autofocus autocomplete="off">

                        </div>
                        <span class="CUO text-danger" style="display: none;"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                    </div>  
                 </div>
                <div class="col-md-4  ">
                    <div class="form-group" >
                      <label>Monto Produccion</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                              <i class="fa fa-money" aria-hidden="true"></i>
                          </div>
                              <input max="10000" autofocus type="text" name="montototal" id="montototal" value="" class="form-control validat" placeholder="Monto Total Produccion" required="required">
                        </div>
                        <span class="CUO text-danger" style="display: none;"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                    </div>  
                </div>
                <din class="col-md-4">  
                    <div class="form-group">
                      <label>Comprobamte</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                              <button type="button" class="btn btn-default date-set"></button>
                          </div>
                         <select name="tipoComprovante" id="tipoComprovante" class="form-control" required="required">
                           <option value="0">Ticket</option>
                           <option value="1">Factura</option>
                         </select>

                      </div>
                        <span class ="TIPO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                    </div>

                </din>

            </div>
            <div class="modal-body">



  <div role="tabpanel" id="tabpanel" style="display: none">
  <ul class="nav nav-tabs nav-justified" role="tablist" >
    <li  id="e"  class="text-center  ">
      <a id="limpp" href="#Efectivo" aria-controls="Efectivo" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Pago Efectivo</a>
    </li>

    <li id="c"  class="text-center  ">
      <a id="" href="#Cheque" aria-controls="Cheque" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Pago En Cheque</a>
    </li>

    <li id="t" class="text-center ">
      <a id="" href="#Tarjeta" aria-controls="Tarjeta" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Pago En Tarjeta</a>
    </li>
     <li id="s" class="text-center ">
      <a id="" href="#fabor" aria-controls="fabor" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Deuda Proveedor</a>
    </li>


  </ul>
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane  fade" id="Efectivo"><br>
        <div class="col-md-6 col-md-offset-0 ">
          <h4>Monto a Pagar &nbsp;<span id="spanmontopagar"></span>&nbsp;₲S.</h4>
          <?php 
                $val = 0;
                foreach($Moneda as $value)
                {
                  $val++;
                ?>
                  <div class="form-group">


                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-money" aria-hidden="true">  Pago en <?php echo $value->Signo.'  '.$value->Moneda ?></i>
                      </div>
                         <input
                           title="Efectivo" 
                           type ="text"
                           id="EF<?php echo $value->idMoneda ?>" 
                           maxlength="15" 
                           max="99999999999999" 
                           data-monto="<?php echo $value->Compra ?>"
                           name="EF<?php echo $value->idMoneda ?>" 
                           class="form-control validat blocqueac" 
                           placeholder="<?php echo $value->Nombre?>" 
                           onkeyup="cambio('<?php echo $value->idMoneda ?>');"
                              >
                              <input type="hidden" name="signo<?php echo $value->idMoneda ?>"  value="<?php echo $value->Moneda ?>">
                              <input type="hidden" name="Moneda<?php echo $value->idMoneda ?>" id="Moneda<?php echo $value->idMoneda ?>" value="">
                              <input type="hidden" name="cam<?php echo $value->idMoneda ?>" id="cam<?php echo $value->idMoneda ?>" value="">
                    </div>
                             <span class ="eee  text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>
                <?php
                }
          ?>
          <input type="hidden" name="val" id="val" value="<?php echo $val ?>">
        </div>
        <div class="col-md-6 col-md-offset-0 ">
          <h4>Cambios en &nbsp;₲S.</h4>
          <?php 
            foreach ($Moneda as $value) 
              { ?>
             <div class="form-group col-md-3">
                <div class="input-group">

                   <?php echo $value->Compra ?> &nbsp;₲S.
         

                </div>

              </div>
             <div class="form-group col-md-9">
                <div class="input-group">
                  <div class="input-group-addon">
                  =
                  </div>
                               <input disabled
                      title="Efectivo"
                      type ="text" 
                      id="cm<?php echo $value->idMoneda ?>" 
                      maxlength="11" 
                      max="99999999999999999999" 
                      name="cm<?php echo $value->idMoneda ?>" 
                      class="form-control " 
                      placeholder="<?php echo $value->Nombre?>" >
                       <input type="hidden" name="ex<?php echo $value->idMoneda ?>" id="ex<?php echo $value->idMoneda ?>"  class="hidden" >
                </div>
                         <span class ="eee  text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
              </div>
            <?php
            }
          ?>

        </div>
    </div>


    <div role="tabpanel" class="tab-pane fade" id="Cheque"><br>
      <!-- cheque -->
      <div class="col-md-12  col-md-offset-0 ">
            <div class="col-md-6  col-md-offset-0 form-group">
              <label>* Numero Cheque </label>
              <div class="input-group">
                <div class="input-group-addon">
                 <i class="fa fa-bars" aria-hidden="true"></i>
                </div>
                   <input  title="Se necesita un numero" maxlength="15" type ="text" id="numcheque" name="numcheque" class="form-control " autofocus autocomplete="off" placeholder="" pattern="[0-9]{0,16}"   >
              </div>
                <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
            </div>
           <?php if (!empty($Banco)) { ?>
            <div class="col-md-6  col-md-offset-0 form-group">
            <label>Afecta a Cuenta Bancaria?</label>
            <div class="input-group ">
            <div class="input-group-addon">
                  <input type="checkbox" name="checkboxbanca" id="checkboxbanca" value="">
            </div>
                <select  name="cuenta_bancaria" id="cuenta_bancaria" class="form-control  select" title="Seleccione un Cheque">
                <option value="" selected></option>}
                option
                <?php 
                foreach($Banco as $key => $value)
                {
                ?>
                <option value="<?php echo $value -> idGestor_Bancos ?>"><?php echo $value -> Nombre;?>  (<?php echo $value -> Numero;?>) </option>
                <?php
                }
                ?>
                </select>
            </div>
            <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
            </div>
            <?php } ?>
            <div class="col-md-6 col-md-offset-0  form-group">
              <label>Cheque Tercero</label>
              <div class="input-group ">
                <div class="input-group-addon">
                  <i class="fa fa-credit-card" aria-hidden="true"></i>
                </div>
                     <select   name="cheque_tercero" id="cheque_tercero"  Size="4" class="form-control select2-multiple multi select" multiple="multiple" style="width: 100%" >
                    <option value=""></option>
                    </select>
              </div>
                <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
            </div>
                           <input type="hidden" name="Acheque_tercero" id="Acheque_tercero" value="">
                           <input type="hidden" name="Acheque" id="Acheque" value="">
             <div class="col-md-6  col-md-offset-0 form-group" >
              <label>* Fecha de Pago</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-calendar" aria-hidden="true"></i>
                </div>
                   <input disabled  title="Fecha de Pago"  type ="text" id="fecha_pago" name="fecha_pago" class="form-control  blocqueac" value="<?php echo date("d-m-Y");?>"/>
              </div>
                <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
            </div>
           <div class="col-md-6  col-md-offset-0  form-group">
               <label>Monto a Pagar &nbsp;<span id="spanmontopagarchque"></span>&nbsp;₲S.</label>
              <div class="input-group">
                <div class="input-group-addon">
                 <i class="fa fa-money" aria-hidden="true"></i>
                </div>
                   <input disabled  title="Se necesita un monto" maxlength="15" type ="text" id="efectivo" name="efectivo" class="form-control  blocqueac">
              </div>
                <span class ="eee text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
            </div>

      </div>
    <!-- FIN cheque -->
    </div>


    <div role="tabpanel" class="tab-pane fade" id="Tarjeta">
      <!-- Tarjeta -->
      <div class="col-md-12  col-md-offset-0 "><br>
           <div class="col-md-4  col-md-offset-2 form-group">
               <label>Monto a Pagar &nbsp;<span id="spanmontopagartar"></span>&nbsp;₲S.</label>
              <div class="input-group">
                <div class="input-group-addon">
                 <i class="fa fa-money" aria-hidden="true"></i>
                </div>
                   <input  title="Se necesita un monto" maxlength="15" type ="text" id="efectivoTarjeta" name="efectivoTarjeta" class="form-control  blocqueac">
              </div>
                <span class ="eee text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
            </div>

            <div class="col-md-4  col-md-offset-0 form-group">
              <label>* Tipo de Tarjeta </label>
              <div class="input-group">
                <div class="input-group-addon">
                 <i class="fa fa-bars" aria-hidden="true"></i>
                </div>
                      <select name="Tarjeta" id="Tarjeta" class="form-control blocqueac select" >
                         <option value="1">Tarjetas de Crédito</option>
                         <option value="2">Tarjetas de Débito</option>
                      </select>
              </div>
                <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
            </div>
      </div>
      <!-- FIN Tarjeta -->
    </div>
    <div role="tabpanel" class="tab-pane fade" id="fabor">
                <div class="col-md-12  col-md-offset-0">
                      <div class="form-group">
                            <label>Deuda Proveedor</label>
                          <div class="input-group">
                          <div class="input-group-addon">
                             <i class="fa fa-balance-scale" aria-hidden="true"></i>
                          </div>
                             <select  name="deudaingrediente" id="deudaingrediente"  Size="4" class="form-control select2-multiple multi select" multiple="multiple" style="width: 100%" >
                              </select>
                           </div>
                           <span class ="PRO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                      </div>

                </div>
    </div>
<div id="piesss">
</div>
<div class="col-md-3 col-md-offset-0">
            <h5>
                       <p id="" class=" text-info">Efectivo:&nbsp;<span class="text-danger" id="ParcialE"></span>&nbsp;₲s.</p>
                       <input type="hidden" name="pagoparcial1" class="hidden" id="pagoparcial1" value="">
            </h5>
    </div>
    <div class="col-md-3 col-md-offset-0">
        <h5>
                       <p id="" class=" text-info">Cheque:<span class="text-danger" id="ParcialC"></span>&nbsp;₲s.</p>
                           <input type="hidden" name="pagoparcial2" class="hidden" id="pagoparcial2" value="">
                           
        </h5>
    </div>
    <div class="col-md-3 col-md-offset-0">
            <h5>
              <p id="" class=" text-info">Tarjeta:<span class="text-danger" id="ParcialT"></span>&nbsp;₲s.</p>
              <input type="hidden" name="pagoparcial3" class="hidden" id="pagoparcial3" value="">
            </h5>
    </div>
<div class="col-md-3 col-md-offset-0">
   <h5>
                 <p id ="" class=" text-info">Deuda P. :<span class="text-danger" id="Parcialf"></span>&nbsp;₲s.</p>
                 <input type ="hidden" name="pagoparcial4" id="pagoparcial4" value="">
                  <input type ="hidden" name="vueltototal" id="vueltototal" value="">
                  <input type ="hidden" name="cobroen" id="cobroen" value="1">
                  <input type ="hidden" name="parcial" id="parcial1" value="">
                  <input type ="hidden" name="matris" id="matris" value="">
                  <input type ="hidden" name="matriscuanta" id="matriscuanta" value="">
        </h5>
</div>
<div class="col-md-12 col-md-offset-0 ">
<div class="alerter alert alert-error" style="display: none">

</div>
</div>
  </div>
  </div>
    <div class="col-md-12 col-md-offset-0">
          <div class="form-group text-danger-align-right">
            <h3>
                     <p id="" class="text-info">Total a Pagar:   <span class="text-danger" id="Totalp"></span>&nbsp;₲s.</p>
                 <p id ="inicial" class=" text-info">Vuelto:   <span class="text-danger" id="vuelto"></span>&nbsp;₲s.</p>
                 <p id ="" class=" text-info">Diferencia de Deuda:   <span class="text-danger" id="Deudarestante"></span>&nbsp;₲s.</p>

                      <input type ="hidden" name="Montoapagar" id="Montoapagar" value="">

            </h3>
          </div>
    </div>
            </div>
            <div class="modal-footer modal-footer2">
                  <div class="pull-right col-md-12">
                     <button type="submit" id="loading_t" name="add_add_" class="btn btn-sm btn-success" data-loading_t-text="Procesando..." autocomplete="off" >
                            <span class  ="glyphicon glyphicon-floppy-disk" id="btnSave"  >Terminar</span> 
                     </button>&nbsp;&nbsp;
                     <button type ="reset"  class="btn btn-sm btn-info">
                            <i class     ="fa fa-refresh "></i> Limpiar
                     </button>&nbsp;
                     <button type ="button" class="btn btn-sm btn-danger" data-toggle="collapse"  data-dismiss="modal">
                            <span class  ="glyphicon glyphicon-floppy-remove"></span> Cancelar
                     </button>
                     </div>
            </div>
            <input type="hidden" name="id_Producto" id="id_Producto" value="">   
            <input type="hidden" name="id_Detalle" id="id_Detalle" value="">    
            <input type="hidden" name="MontoTotal" id="Monto_Total" value="">  
            <input type="hidden" name="idproveedore" id="idproveedore" value="">
          </form>

        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    </div><!-- ./wrapper -->
    
