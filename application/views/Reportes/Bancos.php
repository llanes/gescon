        <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
                           <h1 > 
Reportes de Movimientos Bancarios 
                           </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Reportes</li></li>
            <li class="active">Movimientos Bancarios</li>
          </ol>
        </section> 
                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <section class="content">
          <div class="row">
                <div class="col-md-12">
                  <!-- AREA CHART -->
                    <div class="box box-primary">
                    <form id="view_form" name="view_form" method="post" target="Map" >
                         <div class="box-body">
                           <div class="form-group col-md-3">
                            <label>Caja:</label>
                            <div class="input-group input-group-sm">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <select name="caja" id="caja" fore class="form-control">
                                <?php 
                                $query = $this->db->get('Gestor_Bancos');
                                if ($query->num_rows() > 0) {
                                  foreach ($query->result() as $key => $value) {?>
                                    <option value="<?= $value->idGestor_Bancos ?>"> <?= $value->Nombre ?></option>

                               <?php } ?>
                                       <option value="0">Todas las Bancos</option>
                               <?php } ?>
                                  

                             </select>
                            </div><!-- /.input group -->
                          </div>
                          <div class="form-group col-md-3">
                            <label for="Entrega">Fecha de inicio</label>
                            <div class='input-group input-group-sm' id='datetimepicker7'>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default date-set"><i class="fa fa-calendar"></i></button>
                                </div>
                                <input  type='text' class="form-control " id="fecha" name="fecha" value="<?php echo date("Y-m-d");?>"/>

                            </div>
                              <span class ="FECHA text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                          </div>
                          <div class="form-group col-md-3">
                          <label for="Entrega">Fecha final</label>
                          <div class='input-group input-group-sm' id='datetimepicker7'>
                              <div class="input-group-btn">2
                                  <button type="button" class="btn btn-default date-set"><i class="fa fa-calendar"></i></button>
                              </div>
                              <input  type='text' class="form-control " id="fecha2" name="fecha2" value="<?php echo date("Y-m-d");?>"/>

                          </div>
                            <span class ="FECHA text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                          </div>
                           <div class="form-group col-md-3">
                            <label>Tipos de Movimientos:</label>
                            <div class="input-group input-group-sm">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <select name="reservation" id="reservation" class="form-control">
                                <option value="0">Todos los Movimientos</option>
                                <option value="1">Entrada</option>
                                <option value="2">Salida</option>
                              </select>
                            </div><!-- /.input group -->
                          </div>
                        <div class="box-header with-border">
                         </div>
                           <div class="form-group col-md-offset-4">
                                <span class="input-group input-group-sm">
<!--                                       <button id="" onclick="listarchartjs();" class="btn  btn-warning btn-flat" type="button" data-select2-open="single-prepend-text">
                                          <i class="fa fa-area-chart" aria-hidden="true"></i>&nbsp;Vista Grafica</i>
                                      </button> -->
                                      <button id="" onclick="view_my_report();" class="btn  btn-info btn-flat" type="button" data-select2-open="single-prepend-text">
                                          <i class="fa fa-file-pdf-o" aria-hidden="true">&nbsp; Exportar a PDF</i>
                                      </button>
                                       <button id="EXEL" form="view_form" class="btn  btn-success btn-flat" type="submit" data-select2-open="single-prepend-text">
                                          <i class="fa fa-file-excel-o" aria-hidden="true">&nbsp; Exportar a EXEL</i>
                                      </button>
                                 </span>
                          </div>
                          </div><!-- /.box-body -->
                  </from>



              <!-- <div class="box box-primary"> -->
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="areaChart" width="400" height="300"></canvas>
                  </div>
                </div><!-- /.box-body -->
              </div>

                        </div><!-- /.box -->
                    </div>
                </div>
        </section>

<div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->
