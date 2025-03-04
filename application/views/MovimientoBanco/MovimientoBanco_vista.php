      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
              <button id="add" class="btn  btn-success btn-sm">
               <i class="" id="accion" aria-hidden="true">&nbsp;&nbsp;Agregar </i> 
              </button>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Banco</li>
            <li class="active">Movimiento Banco</li>
          </ol>
       </section> 
                  <div id="alertasadd" style="display: none" >

                  </div>
        <div class="col-md-12">
          <div class="collapse" id="collapseExample" aria-expanded="true">
                 <span class="col-md-4">Son necesarias las etiquetas de los campos marcados con *</span>
                 <div role="tabpanel" class="tab-content">
                   <!-- Nav tabs -->
                   <ul class="nav nav-tabs " role="tablist">
                     <li role="presentation" class="active" id="acct1" >
                       <a class="btn-default" id="F" href="#Efectivo" aria-controls="Efectivo" role="tab" data-toggle="tab"><i class="fa fa-money"></i>  Efectivo</a>
                     </li>
                     <li role="presentation"  id="ocul">
                       <a class="btn-default" id="B" href="#tab" aria-controls="tab" role="tab" data-toggle="tab"><i class="fa fa-credit-card" aria-hidden="true"></i>  Cheques Terceros</a>
                     </li>
                     <li role="presentation" id="acct" >
                       <a class="btn-default" id="C" href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-credit-card" aria-hidden="true"></i>  Crear Cheque</a>
                     </li>
                   </ul>
                 
                   <!-- Tab panes -->
                   <div class="tab-content">
                     <div role="tabpanel" class="tab-pane active" id="Efectivo">
                   <form action="#" name="form_Efectivo" id="form_Efectivo" class="form_Efectivo" data-toggle="validator" role="form" autocomplete="off">
                   <input type="hidden" name="i4d" id="i4d" value="">
                    <div class="row">
                                <div class="col-md-12">
                                    <div class="box panel-default">
                                        <div class="panel-body">
                                            <div class="row">

                                                    <?php 
                                                    $val = 0;
                                                    foreach($Moneda as $key => $value)
                                                    {
                                                      $val++;
                                                    ?>
                                                      <div class="col-md-4 col-md-offset-1">
                                                      <div class="form-group">
                                                         <label for="">Moneda <?php echo $value->Signo.'  '.$value->Moneda ?></label>
                                                        <div class="input-group input-group-sm">
                                                          <div class="input-group-addon">
                                                            <i class="fa fa-money" aria-hidden="true">  </i>
                                                          </div>
                                                             <input title="Efectivo" type ="text" id="EF<?php echo $value->idMoneda ?>" maxlength="15" max="99999999999999"
                                                               data-monto="<?php echo $value->Compra ?>" name="EF<?php echo $value->idMoneda ?>"                                  class="form-control validat blocqueac" placeholder="<?php echo $value->Nombre?>" 
                                                               onkeyup="cambio('<?php echo $value->idMoneda ?>');"
                                                                  >
                                                                  <input type="hidden" name="Moneda<?php echo $value->idMoneda ?>" id="Moneda<?php echo $value->idMoneda ?>" value="<?php echo $value->idMoneda ?>">
                                                                   <input type="hidden" name="Signo<?php echo $value->idMoneda ?>" id="Moneda<?php echo $value->idMoneda ?>" value="<?php echo $value->Signo ?>">
                                                                  <input type="hidden" name="cam<?php echo $value->idMoneda ?>" id="cam<?php echo $value->idMoneda ?>" value="">
                                                        </div>
                                                                 <span id="eee<?php echo $value->idMoneda ?>" class ="eee  text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                      </div>
                                                       </div>
                                                      <?php
                                                      }
                                                      ?>
                                                      <input type="hidden" name="val" id="val"  value="5">
                                                      <input type="hidden" name="parcial1" class="" id="parcial1" value="">
                                              <div class="col-md-4 col-md-offset-1">
                                                      <div class="form-group">
                                                      <label> *  Movimiento Bancaria </label>
                                                        <div class="input-group input-group-sm">
                                                          <div class="input-group-addon">
                                                              <i class="fa fa-random" aria-hidden="true"></i>

                                                                </div>
                                                                <select id="movi" required class="form-control Movimiento" name="movi" title="Seleccione un Movimiento" data-placeholder="Seleccione una opcion" >
                                                                   <option></option>
                                                                    <option  value="Entrada">Entrada</option>
                                                                     <option value="Salida">Salida</option>
                                                                </select>
                                                              </div>
                                                             <span class ="movi text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>
                                               </div>
                                            <div class="col-md-4 col-md-offset-1">
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
                                                             <span class ="ppp text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>
                                              </div>
                                              <div class="col-md-4 col-md-offset-1">
                                                        <div class="form-group">
                                                          <label>* Cuenta Bancaria</label>
                                                          <div class="input-group input-group-sm">
                                                            <span class="input-group-addon">
                                                              <i class="fa fa-university" aria-hidden="true"></i>
                                                            </span>
                                                            <select required  name="cuenta_bancaria" id="cuenta_bancaria" class="form-control  " title="Seleccione un Cheque">
                                                             <option></option>
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
                                                            <span class ="cxc text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>
                                              </div>
                                              <div class="col-md-12" id="detalle">
                                                  <div class="pull-right">
                                                        <button type="submit" name="add_add_" class="btn btn-sm btn-success">
                                                        <span class="glyphicon glyphicon-floppy-disk" id="btnSave">Guardar</span> </button>&nbsp;&nbsp;
                                                        <button type="reset" class="btn btn-sm btn-info">
                                                        <i class="fa fa-refresh "></i> Limpiar</button>&nbsp;
                                                        <button type="button" class="btn btn-sm btn-danger"  onclick="resetear()" >
                                                        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar</button>     
                                                  </div>
                                              </div>
                                            </div>
                                        </div>

                                    </div>
                          </div>       </div>
                    </form>
                    <!-- fin de formulario -->
                     </div>




                     <div role="tabpanel" class="tab-pane" id="home">
                   <form action="#" name="formulario" id="newcheque" class="newcheque" data-toggle="validator" role="form" autocomplete="off">
                   <input type="hidden" name="id" id="id" class="hidden" value="">
                   <div class="row">
                                <div class="col-md-12">
                                    <div class="box panel-default">
                                        <div class="panel-body">
                                            <div class="row">
                                                        <div class="col-md-4 ">
                                                          <div class="form-group ">
                                                            <label>* Numero Cheque </label>
                                                            <div class="input-group input-group-sm">
                                                              <div class="input-group-addon">
                                                                <i class="fa fa-barcode" aria-hidden="true"></i>
                                                              </div>
                                                                 <input required title="Se necesita un Numeru" maxlength="40" type ="text" id="Numeru" name="Numeru" class="form-control" autofocus autocomplete="off" placeholder=""  >
                                                            </div>
                                                              <span class ="nnn text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                          </div>
                                                         <div class="form-group">
                                                                    <label>* Importe </label>
                                                                    <div class="input-group input-group-sm">
                                                                      <div class="input-group-addon">
                                                                        <i class="fa fa-bars" aria-hidden="true"></i>
                                                                      </div>
                                                                         <input required title="Se necesita un Importe" maxlength="40" type ="text" id="Importe" name="Importe" class="form-control" autofocus autocomplete="off" placeholder=""  >
                                                                    </div>
                                                                      <span class ="iii text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                                  </div>
                                                          </div>
                                                    <div class="col-md-4 ">
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
                                                             <span class ="ppp text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>

                                                        <div class="form-group">
                                                          <label>* Cuenta Bancaria</label>
                                                          <div class="input-group input-group-sm">
                                                            <span class="input-group-addon">
                                                              <i class="fa fa-university" aria-hidden="true"></i>
                                                            </span>
                                                            <select required  name="cuenta_bancaria" id="cuenta_bancaria" class="form-control  " title="Seleccione un Cheque">
                                                             <option></option>
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
                                                            <span class ="ccc text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>
                                                   </div>
                                                   <div class="col-md-4 ">
                                                       <div class="form-group">
                                                       <label>* Fecha de emisión. </label>
                                                        <div class='input-group input-group-sm' id='datetimepicker7'>
                                                            <div class="input-group-btn">
                                                                <button type="button" class="btn btn-default date-set"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                                                            </div>
                                                            <input required type='text' class="form-control " id="fecha" name="fecha" value="<?php echo date("d-m-Y");?>"/>

                                                        </div>
                                                          <span class ="FECHA text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                      </div>
                                                      <div class="form-group">
                                                      <label> *  Movimiento Bancaria </label>
                                                        <div class="input-group input-group-sm">
                                                          <div class="input-group-addon">
                                                              <i class="fa fa-random" aria-hidden="true"></i>

                                                                </div>
                                                                <select id="movi" required class="form-control Movimiento" name="movi" title="Seleccione un Movimiento" data-tags="true" data-placeholder="Seleccione una opcion" >
                                                                    <option  value="Entrada">Entrada</option>
                                                                     <option value="Salida">Salida</option>
                                                                </select>
                                                              </div>
                                                             <span class ="movii text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>

                                                   </div>
                                                   <div class="col-md-12" id="detalle">
                                                 <div class="pull-right">
                                                        <button type="submit" name="add_add" class="btn btn-sm btn-success">
                                                        <span class="glyphicon glyphicon-floppy-disk" id="btnSave">Guardar</span> </button>&nbsp;&nbsp;
                                                        <button type="reset" class="btn btn-sm btn-info">
                                                        <i class="fa fa-refresh "></i> Limpiar</button>&nbsp;
                                                        <button type="button" class="btn btn-sm btn-danger"  onclick="resetear()" >
                                                        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar</button>     
                                                </div>
                                               </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                    </form>
                    <!-- fin de formulario -->
                     </div>
                     <div role="tabpanel" class="tab-pane" id="tab">
                   <form action="#" id="addcheque" class="addcheque" autocomplete="off">
                   <input type="hidden" name="id" id="id" value="">
                   <div class="row">
                                <div class="col-md-12">
                                    <div class="box panel-default">
                                        <div class="panel-body">
                                            <div class="row">
                                             <div class="col-md-4 col-md-offset-0 ">
                                                        <div class="form-group">
                                                         <label>*  Cheques</label>
                                                            <div class="input-group input-group-sm">
                                                              <div class="input-group-addon">
                                                                <i class="fa fa-credit-card" aria-hidden="true"></i>
                                                              </div>
                                                               <select required  name="Cheques" id="Cheques"  class="form-control select2-multiple multi" multiple="multiple"  >
                                                                    <option></option>
                                                                </select>
                                                              </div>
                                                             <span class ="ch text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>
                                                          </div>
                                                    <div class="col-md-4 col-md-offset-0 ">
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
                                                             <span class ="ppp text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>

                                                          </div>
                                                          <div class="col-md-4 col-md-offset-0 ">
                                                        <div class="form-group">
                                                          <label>* Cuenta Bancaria</label>
                                                          <div class="input-group input-group-sm">
                                                            <span class="input-group-addon">
                                                              <i class="fa fa-university" aria-hidden="true"></i>
                                                            </span>
                                                            <select required  name="cuenta_bancaria" id="cuenta_bancaria" class="form-control  " title="Seleccione un Cheque">
                                                             <option></option>
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
                                                            <span class ="cb text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>
                                                   </div>
                                                          <div class="col-md-12" id="detalle">
                        <div class="pull-right">
                                    <button type="submit" name="add" class="btn btn-sm btn-success">
                                    <span class="glyphicon glyphicon-floppy-disk" id="btnSave">Guardar</span> </button>&nbsp;&nbsp;
                                    <button type="reset" class="btn btn-sm btn-info">
                                    <i class="fa fa-refresh "></i> Limpiar</button>&nbsp;
                                    <button type="button" class="btn btn-sm btn-danger"  onclick="resetear()" >
                                    <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar</button>     
                            </div>
                                                          </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                    </form>
                    <!-- fin de formulario -->
                     </div>
                   </div>
                 </div>

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
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('mbanco')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" href="Reporte_exel/mbanco" title="Exportar a EXEL" >
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
                                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_movi">
                                        <thead>
                                          <tr>
                                           <th ></th>
                                           <th ><i class="fa fa-bars"></i>  Cheque</th>
                                           <th ><i class="fa fa-truck"></i>  Concepto </th>
                                           <th ><i class="fa fa-calendar"></i>  Fecha </th>
                                           <th ><i class="fa fa-monei"></i>  Importe </th>
                                           <th ><i class="fa fa-university"></i>  Banco </th>
                                            <th ><i class="fa fa-bars"></i>  Accion </th>
                                           <th style="width:125px;"><i class="fa fa-cogs" ></i> Acciones</th>
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
    
