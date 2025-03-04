      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Contabilidad
            <small>Acientos</small> ||   <small>Libro</small>  ||   <small>Balance</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Aciento | Aciento</li>
            <li class="active">Aciento</li>
          </ol>
       </section> 
                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <section class="content">
          <div class="row">
                <div class="col-md-12">
                  <!-- AREA CHART -->
                    <div class="box box-primary">
                      <div role="tabpanel">
                        <!-- Nav tabs -->
                          <ul class="nav nav-pills  nav-justified" id="maincontent" role="tablist">
                          <li role="presentation"  class="">
                            <a id="aciento" class=" text-center" href="#adiarios" aria-controls="adiarios" role="tab" data-toggle="tab"><i id="active" class="fa fa-columns"></i> <strong>Acientos Diarios |</strong></a>
                          </li>
                          <li role="presentation">
                            <a href="#acientobusqueda" class=" text-center" aria-controls="acientobusqueda" role="tab" data-toggle="tab"><i id="active" class="fa fa-search" aria-hidden="true"></i> <strong>Acientos por Busqueda |</strong></a>
                          </li>

                          <li role="presentation">
                            <a href="#libromayor" class=" text-center" aria-controls="libromayor" role="tab" data-toggle="tab"><i id="active" class="fa fa-book"></i><strong> Libro Mayor |</strong></a>
                          </li>

                          <li role="presentation">
                            <a href="#balance" class=" text-center" aria-controls="balance" role="tab" data-toggle="tab"><i id="active" class="fa fa-balance-scale"></i> <strong>Balance Genaral </strong></a>
                          </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content responsive">
                          <div role="tabpanel" class="tab-pane fade" id="adiarios">
                         <!-- ///////////////////////////////////////////////////////// -->
                            <div class="box box-primary"><br>

                                        <div class="box-header with-border">
                                          <div class="box-tools pull-right">
                         <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('aciento')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a type="" class="btn btn-success btn-xs" href="Reporte_exel/aciento" title="Exportar a EXEL" >
                                  <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                                  </a></div>
                              </div>
                                          </div>
                                        </div><br>
                                       <div class="table-responsive">
                                           <!-- tabla inicial -->
                                          <div class="well">
                                              <table class="table table-striped " cellspacing="30" width="100%"  id="tabla_acientos">
                                              <thead>
                                                <tr class="info">
                                                 <td class="col-md-2"><i class ="fa fa-calendar-o" aria-hidden="true"></i> <b> Fecha</b></td>
                                                 <td><i class ="fa fa-bars" aria-hidden="true"></i> <b> Debe Cuenta</b></td>
                                                 <td><i class ="fa fa-bars" aria-hidden="true"></i> <b> Haber Cuenta</b></td>
                                                 <td><i class ="fa fa-bars" aria-hidden="true"></i> <b> Debe</b></td>
                                                 <td><i class ="fa fa-bars" aria-hidden="true"></i> <b> Haber</b></td>
                                                </tr>
                                              </thead>
                                            <tbody>
                                          </tbody>

                                            </table>
                                          </div>

                                        <!-- final tabla -->
                                        </div><!-- /.box-body -->
                                </div><!-- /.box -->
                           <!-- ////////////////////////////////////////////////////////////// -->
                          </div>

                          <div role="tabpanel" class="tab-pane fade" id="acientobusqueda">
                            <div class="box box-primary">
                          <form action="Reporte_exel/baciento" method="post" id="form1" accept-charset="utf-8">
                                  <div class="col-md-3  form-group" >
                                    <label> Fecha </label>
                                    <div class="input-group input-group-sm">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                      </div>
                                         <input   title="Fecha de Pago"  type ="text" id="buscaprfecha" name="buscaprfecha" class="form-control  " value="<?php echo date("y-m-d");?>"/>
                                    </div>
                                      <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div>
                                 <div class="col-md-3 form-group">
                                     <label>Por Caja</label>
                                    <div class="input-group input-group-sm">
                                      <div class="input-group-addon">
                                       <i class="fa fa-search" aria-hidden="true"></i>
                                      </div>
                                          <select name="seleccaja" id="seleccaja"  class="form-control  " >
                                            <option value=""></option>
                                            <?php foreach ($caja as $key => $value) {?>
                                              <option value="<?php echo $value->idCaja?>">Caja Nº <?php echo $value->idCaja?></option>
                                           <?php } ?>
                                          </select>
                                    </div>
                                      <span class ="eee text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div>
                                 <div class="col-md-3 form-group">
                                     <label>Compra / Pagos</label>
                                    <div class="input-group input-group-sm">
                                      <div class="input-group-addon">
                                       <i class="fa fa-search" aria-hidden="true"></i>
                                      </div>
                                          <select name="selectforma" id="selectforma"  class="form-control  " >
                                            <option  selected value=""></option>
                                            <option value="1">Pagos</option>
                                            <option value="2">Cobros</option>
                                          </select>
                                    </div>
                                      <span class ="eee text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div><br>
                                                            </form>
                                        <div class="box-header with-border">
                                          <div class="box-tools pull-right">
                                          <div class="dt-buttons btn-group">
                                              <div class="hidden-phone">
                                              <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdfexport('baciento')">
                                              <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <button type="submit" form="form1" class="btn btn-success btn-xs" title="Exportar a EXEL" >
                                  <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                                              </button></div>
                                          </div>
                                          </div><br>
                                        </div>
                                       <div class="table-responsive">
                                           <!-- tabla inicial -->
                                          <div class="well">
                                              <table class="table table-striped " cellspacing="30" width="100%"  id="aciento_busqueda">
                                              <thead>
                                                <tr class="info">
                                                 <td class="col-md-2"><i class ="fa fa-calendar-o" aria-hidden="true"></i> <b> Fecha</b></td>
                                                 <td><i class ="fa fa-bars" aria-hidden="true"></i> <b> Debe Cuenta</b></td>
                                                 <td><i class ="fa fa-bars" aria-hidden="true"></i> <b> Haber Cuenta</b></td>
                                                 <td><i class ="fa fa-bars" aria-hidden="true"></i> <b> Debe</b></td>
                                                 <td><i class ="fa fa-bars" aria-hidden="true"></i> <b> Haber</b></td>

                                                </tr>
                                              </thead>
                                            <tbody>
                                          </tbody>

                                            </table>
                                          </div>

                                        <!-- final tabla -->
                                        </div><!-- /.box-body -->
                                </div><!-- /.box -->


                          </div>

                          <div role="tabpanel" class="tab-pane fade" id="libromayor">
                            <div class="box box-primary">
                              <form action="Reporte_exel/libromayor" method="POST"  id="fromlibromayor" accept-charset='utf-8'>
                                 <div class="col-md-3  form-group" >
                                    <label> Fecha </label>
                                    <div class="input-group input-group-sm">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                      </div>
                                         <input   title="Fecha de Pago"  type ="text" id="fechamayor" name="fechamayor" class="form-control  " value="<?php echo date("y-m-d");?>"/>
                                    </div>
                                      <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div>
                                 <div class="col-md-3 form-group">
                                     <label>Por Caja</label>
                                    <div class="input-group input-group-sm">
                                      <div class="input-group-addon">
                                       <i class="fa fa-search" aria-hidden="true"></i>
                                      </div>
                                          <select name="cajamayor" id="cajamayor"  class="form-control  " >
                                            <option value=""></option>
                                            <?php foreach ($caja as $key => $value) {?>
                                              <option value="<?php echo $value->idCaja?>">Caja Nº <?php echo $value->idCaja?></option>
                                           <?php } ?>
                                          </select>
                                    </div>
                                      <span class ="eee text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div>
                                    <div class="col-md-4 form-group">
                                         <label>Plan de Cuenta</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-btn">
                                                    <button id="seartt" class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                                                    <i class="fa fa-random" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                                <select id="planmayor" class="form-control planmayor" name="planmayor" title="Seleccione un Plan de Cuenta">
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
                                        </div><br>


                              </form>
                                        <div class="box-header with-border">
                                          <div class="box-tools pull-right">
                                          <div class="dt-buttons btn-group">
                                              <div class="hidden-phone">
                                              <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdfmayor('libromayor')">
                                              <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <button type="submit" form="fromlibromayor" class="btn btn-success btn-xs" title="Exportar a EXEL" >
                                  <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                                              </a></div>
                                          </div>
                                          </div>
                                        </div>

                <div class="box-body" id="loader">

                </div><!-- /.box-body -->

                                </div><!-- /.box -->
                          </div>

                          <div role="tabpanel" class="tab-pane fade" id="balance">
                            <div class="box box-primary"><br>
                                        <form action="Reporte_exel/balance" method="post" id="frombalance" accept-charset="utf-8">
                                         <div class="col-md-3  form-group col-md-offset-2" >
                                          <div class="input-group input-group-sm">
                                            <div class="input-group-addon">
                                              <i class="fa fa-calendar" aria-hidden="true"> Mes</i>
                                            </div>
                                               <input   title="Fecha de Pago"  type ="text" id="fechames" name="fechames" class="form-control  " value=""/>
                                          </div>
                                            <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                        </div>
                                         <div class="col-md-3  form-group" >
                                          <div class="input-group input-group-sm">
                                            <div class="input-group-addon">
                                              <i class="fa fa-calendar" aria-hidden="true"> Año</i>
                                            </div>
                                               <input   title="Fecha de Pago"  type ="text" id="fechaanmo" name="fechaanmo" class="form-control  " value=""/>
                                          </div>
                                            <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                        </div>
                                        </form>
         
                                        <div class="box-header with-border">
                                          <div class="box-tools pull-right">
                                          <div class="dt-buttons btn-group">
                                              <div class="hidden-phone">
                                              <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdfbalance('balance')">
                                              <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                                <button type="submit" form="frombalance" class="btn btn-success btn-xs" title="Exportar a EXEL" >
                                                <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                                              </a></div>
                                          </div>
                                          </div>
                                        </div><br>

                                       <div class="table-responsive">
                                           <!-- tabla inicial -->
                                          <div class="well"> 
                                          <strong class="text-center text-danger"><h3 class="">Balance de Comprobacion  </h3></strong>
                                          <h4 class="text-center text-danger"><p id="comfecha"> </p></h4>
                                          <table class="table table-striped " cellspacing="30" width="100%"  id="loadbalance">
  
                                              <thead>
                                                <tr class="info">
                                                 <td class="col-md-2"><i class ="fa fa-calendar-o" aria-hidden="true"></i> <b> </b></td>
                                                 <td><i class ="fa fa-bars" aria-hidden="true"></i> <b> Titulo de la Cuenta</b></td>
                                                 <td><i class ="fa fa-bars" aria-hidden="true"></i> <b> Debe</b></td>
                                                 <td><i class ="fa fa-bars" aria-hidden="true"></i> <b> Haber</b></td>

                                                </tr>
                                              </thead>
                                            <tbody>
                                          </tbody>

                                            </table>
                                          </div>

                                        <!-- final tabla -->
                                        </div><!-- /.box-body -->

                                </div><!-- /.box -->
                          </div>
                        </div>
                      </div>
                        </div><!-- /.box -->
                    </div>
                </div>
        </section>
<div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->
    
