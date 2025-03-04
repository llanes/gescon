      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          <div class="pull-left hidden-phone">
<!--               <a class="btn  btn-success btn-sm" href="javascript:void(0);" title="Buscar y Agregar" >
                  <i class="fa fa-trash-o">&nbsp;&nbsp;Pagos Deuda Cliente</i>
              </a> -->
              <a id="add" class="btn  btn-success btn-sm" href="javascript:void(0);" title="Agregar" >
                   <i class="" id="accion" aria-hidden="true">&nbsp;&nbsp;Pagos </i> 
              </a>
          </div>

          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Pagos | Pagos</li>
            <li class="active">Pagos</li>
          </ol>
       </section> 

        <div class="col-md-12">
          <div class="collapse" id="collapseExample" aria-expanded="true"><br>  
                            <div id="alertasadd" style="display: none" >

                  </div>
                 <span class="col-md-4">Son necesarias las etiquetas de los campos marcados con *</span>
                   <form action="#" id="from_Pagos" class="from_Pagos">
                   <input type="hidden" name="id" id="id" value="">
                    <div class="row">
                        <div class="col-md-12">
                          <div class="box panel-default">
                            <div class="panel-body">
                              <div class="row  ">
                              <!-- formulario -->
                  <div role="tabpanel" class="col-md-8">
                      <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li  id="e"  class="text-center">
                          <a id="limpiar" href="#Efectivo" aria-controls="Efectivo" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Pago En Efectivo</a>
                        </li>

                        <li id="c"  class="text-center ">
                          <a id="" href="#Cheque" aria-controls="Cheque" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Pago En Cheque</a>
                        </li>

                        <li id="t" class="text-center">
                          <a id="" href="#Tarjeta" aria-controls="Tarjeta" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Pago En Tarjeta</a>
                        </li>
                      </ul>
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade" id="Efectivo">
                            <div class="col-md-6 col-md-offset-0 ">
                              <h4>Monto a Pagar &nbsp;<span id="spanmontopagar"></span></h4>
                                        <input type="hidden" name="deudapagar" id="deudapagar" value="">
                              <?php 
                                    $val = 0;
                                    foreach($Moneda as $key => $value)
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
                                 <div class="form-group ">
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
                            <div class="col-md-6  col-md-offset-0 ">
                                  <div class="form-group">
                                    <label>* Numero Cheque </label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                       <i class="fa fa-bars" aria-hidden="true"></i>
                                      </div>
                                         <input  title="Se necesita un numero" maxlength="15" type ="text" id="numcheque" name="numcheque" class="form-control input-sm" autofocus autocomplete="off" placeholder="" pattern="[0-9]{0,16}"   >
                                    </div>
                                      <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div>

                                <div class="form-group">
                                <label>Afecta a Cuenta Bancaria?</label>
                                <div class="input-group ">
                                  <div class="input-group-addon">
                                    <input type="checkbox" name="checkbox" id="checkbox" value="">
                                  </div>
                                  <select  name="cuenta_bancaria" id="cuenta_bancaria" class="form-control  " title="Seleccione un Cheque">
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
                            </div>
                            <div class="col-md-6  col-md-offset-0 ">
                                   <div class="form-group" >
                                    <label>* Fecha de Emisión</label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                      </div>
                                         <input disabled  title="Fecha de Pago"  type ="text" id="fecha_pago" name="fecha_pago" class="form-control input-sm " value="<?php echo date("d-m-Y");?>"/>
                                    </div>
                                      <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div>
                                 <div class="form-group">
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

                        <div role="tabpanel" class="tab-pane fade" id="Tarjeta"><br> 
                          <!-- Tarjeta -->
                          <div class="col-md-6  col-md-offset-0 ">
                               <div class="form-group">
                                   <label>Monto a Pagar &nbsp;<span id="spanmontopagartar"></span>&nbsp;₲S.</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                     <i class="fa fa-money" aria-hidden="true"></i>
                                    </div>
                                       <input  title="Se necesita un monto" maxlength="15" type ="text" id="efectivoTarjeta" name="efectivoTarjeta" class="form-control  blocqueac">
                                  </div>
                                    <span class ="eee text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div>
                           </div>
                           <div class="col-md-6  col-md-offset-0 ">
                                <div class="form-group">
                                  <label>* Tipo de Tarjeta </label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                     <i class="fa fa-bars" aria-hidden="true"></i>
                                    </div>
                                          <select name="Tarjeta" id="Tarjeta" class="form-control blocqueac" >
                                             <option value="1">Tarjetas de Crédito</option>
                                             <option value="2">Tarjetas de Débito</option>
                                          </select>
                                  </div>
                                    <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div>
                          </div>
                          <!-- FIN Tarjeta -->
                        </div><br><br><br><br><br><br><br><br>
                    <div class="col-md-4 col-md-offset-0">
                                  <h4>
                                             <p id="" class=" text-info">Efectivo:&nbsp;<span class="text-danger" id="ParcialE"></span>&nbsp;₲s.</p>
                                             <input type="hidden" name="parcial1" class="hidden" id="parcial1" value="">
                                  </h4>
                          </div>
                          <div class="col-md-4 col-md-offset-0">
                              <h4>
                                             <p id="" class=" text-info">Cheque:<span class="text-danger" id="ParcialC"></span>&nbsp;₲s.</p>
                                                 <input type="hidden" name="parcial2" class="hidden" id="parcial2" value="">
                              </h4>
                          </div>
                          <div class="col-md-4 col-md-offset-0">
                                  <h4>
                                    <p id="" class=" text-info">Tarjeta:<span class="text-danger" id="ParcialT"></span>&nbsp;₲s.</p>
                                    <input type="hidden" name="parcial3" class="hidden" id="parcial3" value="">
                                  </h4>
                          </div>
                    <div class="col-md-6 col-md-offset-0 ">
                                    <h3>
                                             <p id="" class=" text-info">Total:   <span class="text-danger" id="Totalp"></span>&nbsp;₲s.</p>
                                             <input type="hidden" name="cobroen" id="cobroen" value="1">
                                             <input type="hidden" name="Totalparclal" id="Totalparclal" value="">
                                    </h3>
                                    <span class ="tttt text-danger"></span>  
                    </div>
                    <div class="col-md-6 col-md-offset-0 ">
                      <div class="alerter alert alert-error" style="display: none">
                      </div>
                    </div>

                      </div>
                      <!-- Nav tabs -->

                  </div>
                   <div class="col-md-4 col-md-offset-0">
                              <div class="form-group">
                               <label>*  Pagos de</label>
                                  <div class="input-group ">
                                      <span class="input-group-btn">
                                          <button id="seartt" class="btn btn-default" type="button" data-toggle="modal" data-target=".bs-example-modal-sm"    >
                                          <i class="fa fa-plus-square" aria-hidden="true"></i>
                                          </button>
                                      </span>
                                      <select id="selectrequired" required class="form-control" name="Razon" title="Seleccione un Elemento">
                                       <option></option>
                                      <?php foreach ($T_P as $key => $value) : ?>
                                          <option value="<?php echo $value->idTipos_de_Pago.','.$value->NombreTipo ?>">
                                              <?php echo $value->NombreTipo ?>
                                          </option>
                                      <?php endforeach; ?>

                                      </select>
                                      <input type="hidden" name="Nombre_Tipo" value="">
                                      <input type="hidden" name="razon" value="">
                                    </div>
                                   <span class ="ttt text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                              </div>
                              <div class="form-group" style="display: none" id="empleadito">
                               <label>*  Empleado</label>
                                  <div class="input-group ">
                                      <span class="input-group-btn">
                                          <button  class="btn btn-default" type="button">
                                           <i class="fa fa-gavel text-red"></i>
                                          </button>
                                      </span>
                                      <select id="select" class="form-control" name="R_H" title="Seleccione un Empleado">
                                       <option></option>
                                      <?php foreach ($EM as $key => $value) { ?>
                                          <option value="<?php echo $value->idEmpleado ?>">
                                              <?php echo $this->mi_libreria->getSubString($value->Nombres.' '.$value->Apellidos,25); ?>
                                          </option>
                                      <?php } ?>

                                      </select>
                                    </div>
                                   <span class ="ttt text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                              </div>
                              <div class="form-group">
                                      <label>*  Tipo de Comprobante</label>
                                 <div class='input-group ' id=''>
                                    <div class="input-group-addon">
                                  <i class="fa fa-square" aria-hidden="true"></i>
                                  </div>
                                   <select name="tipoComprovante" id="tipoComprovante" class="form-control input-sm" required="required">
                                     <option value="0">Ticket</option>
                                     <option value="1">Factura</option>
                                   </select>

                                </div>
                                  <span class ="TIPO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                              </div>
                              <div class="form-group" id="disbleinput">
                                      <label>*  Nº de Comprobante</label>
                                 <div class='input-group ' id=''>
                                  <div class="input-group-addon">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                  </div>
                                  <input required type="text" name="comprobante" class="form-control " id="comprobante" value="" >
                              </div>

                                </div>
                            <span class ="CMP text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                             <div class="form-group">
                               <label>*  Plan de Cuenta</label>
                                  <div class="input-group input-group-sm">
                                      <span class="input-group-btn">
                                          <button id="seartt" class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                                          <i class="fa fa-random" aria-hidden="true"></i>
                                          </button>
                                      </span>
                                      <select id="PlandeCuenta" required class="form-control PlandeCuenta" name="PlandeCuenta" title="Seleccione un Plan de Cuenta">
                                          <option></option>
                                              <?php   $Plande = PlandeCuenta();
                                            foreach($Plande as $key => $value)
                                            {
                                            ?>
                                            <option value="<?php echo $value -> idPlandeCuenta ?>"><?php echo $value -> Balance_General;?>   </option>
                                            <?php
                                            }
                                            ?>
                                      </select>
                                    </div>
                                   <span class ="PPP text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                              </div>
                              <div class="form-group">
                                <div class="form-group">
                                <label>Descripcion </label>
                                <div class="input-group ">
                                  <div class="input-group-addon">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                  </div>
                                  <textarea  title="Se necesita un Descripcion" maxlength="50"  id="Descripcion" name="Descripcion" class="form-control" autofocus autocomplete="off" rows="1"></textarea>
                                </div>
                                  <span class ="nnn text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div>
                              </div>

                       </div>  
                             <!-- fin de formulario -->
                              </div>

                       <div class="modal-footer col-md-6 col-md-offset-6">
                          <div class="pull-right">
                             <button type="submit" id="loadingg" name="add" class="btn btn-sm btn-success" data-loadingg-text="Procesando..." autocomplete="off">
                            <span class="glyphicon glyphicon-floppy-disk" id="btnSave">Guardar</span> </button>&nbsp;&nbsp;
                            <button type="reset" class="btn btn-sm btn-info" onclick="reseteart()">
                            <i class="fa fa-refresh "></i> Limpiar</button>&nbsp;
                          </div>
                       </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    </form>
                    <!-- fin de formulario -->
             <div class="modal fade bs-example-modal-sm" id="modal-1">
              <div class="modal-sm1 modal-dialog" role="document">
                  <form action="#" method="POS" id="insert_tipo" accept-charset="utf-8">
                <div class="modal-content">
                  <div class="modal-header" id="mh">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only"></span>
                    </button>
                    <h4 class="modal-title">Tipos de Pagos </h4>
                  </div>
                  <div id="alertas">
                  </div>
                  <div class="modal-body">
                           <div class="col-md-12 .ol-md-offset-0">
                                <div class="form-group">
                                  <label>* Referencia de Pago</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-user" aria-hidden="true"></i>
                                    </div>
                                       <input required title="Se necesita un Descripcion" maxlength="40" type ="text" id="Descripcion" name="Descripcion" class="form-control" pattern="[A-Za-z ]{3,40}"   >
                                  </div>
                                    <span class ="NOM text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div>
                          </div>
                  </div>
                  <div class="modal-footer">
                  <div class="pull-right">
                     <button type="submit" id="loading" name="loading add_add" class="btn btn-sm btn-success" data-loading-text="Procesando..." autocomplete="off">
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
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('pagos')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" href="Reporte_exel/pagos" title="Exportar a EXEL" >
                                  <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                                  </a></div>
                              </div>
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus" ></i></button>
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
                                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tablePagos">
                                        <thead>
                                          <tr>
                                          <th colspan="" rowspan="" headers="" scope=""></th>
                                           <th class="col-md-3" ><i class="fa fa-bars"></i> Descripcion</th>
                                           <th ><i class="fa fa-bars"></i>  Monto Total</th>
                                           <th ><i class="fa fa-truck"></i> Comprobantes </th>
                                           <th ><i class="fa fa-user"></i>  Razon Social</th>
                                           <th ><i class="fa fa-calendar"></i>  Fecha Pago</th>
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
    
