      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <small>Control Cambios</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Seguridad</li></li>
            <li class="active">Administrar Cambios</li>
          </ol>
        </section> 

                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <!-- AREA CHART -->
              <div class="box box-primary">
                <div class="box-header with-border">
              <div class="collapse" id="collapseExample">
                      <span class="">Son necesarias las etiquetas de los campos Categorizados con *</span>
                  <div class="well">
                    <!-- contenido de formulario -->
                    <div id="cambioalert" >
                    </div>
                    <form action="#" id="from_Cambio" class="from_Cambio">
                        <input type="hidden" value="" name="idCambio"/> 
                         <div class="col-md-4  col-md-offset-4">
                              <div class="form-group">
                                <label>* Moneda</label>
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-align-center" aria-hidden="true"></i>

                                  </div>
                                    <input required title="Se necesita una Moneda" maxlength="100" type ="text" id="Moneda" name="Moneda" placeholder="Nombre de la Moneda" class="form-control" autofocus autocomplete="off"  placeholder=""  >
                                </div>
                                  <span class ="NO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                              </div>
                        </div>
                         <div class="col-md-4  col-md-offset-4">
                              <div class="form-group">
                                <label>* Cambio </label>
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-align-center" aria-hidden="true"></i>

                                  </div>
                                    <input required title="Se necesita una Cambio" maxlength="15" type ="text" id="Cambio" name="Cambio" placeholder="Cambio" class="form-control" autofocus autocomplete="off" minlength="0"  placeholder=""  >
                                </div>
                                  <span class ="NO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                              </div>
                        </div>
                        <div class="col-md-4  col-md-offset-4">
                              <div class="form-group">
                                <label>* Estado </label>
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-align-center" aria-hidden="true"></i>

                                  </div>
                                    <select name="Estado" id="Estado" class="form-control" required="required">
                                      <option value="0">Activo</option>
                                      <option value="1">Inactivo</option>

                                    </select>
                                </div>
                                  <span class ="NO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                              </div>
                        </div>
                    <table class="table">
                      <thead>
                        <tr>
                          <th>
                           <div class="pull-right">
                       <button type="submit" id="loadingg" name="add_add" class="btn btn-sm btn-success" data-loadingg-text="Procesando..." autocomplete="off">
                                    <span class  ="glyphicon glyphicon-floppy-disk" id="btnSave"  >Guardar</span> 
                             </button>&nbsp;&nbsp;
                                    <button type ="reset"  class="btn btn-sm btn-info">
                                    <i class     ="fa fa-refresh "></i> Limpiar</button>&nbsp;
                                    <button type ="button" class="btn btn-sm btn-danger"  onclick="resetear()" >
                                    <span class  ="glyphicon glyphicon-floppy-remove"></span> Cancelar</button>     
                            </div>
                          </th>
                        </tr>
                      </thead>
                    </table>
                    </form>
                    <!-- fin de formulario -->
                  </div>
                </div>
                  <div class="box-tools pull-right">
                                          <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('cambio')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" href="Reporte_exel/cambio" title="Exportar a EXEL" >
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
                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_Cambio">
                        <thead>
                          <tr>
                                  <th ><i class  ="fa fa-slack" aria-hidden="true"></i></th>
                            <th ><i class="fa fa-bars"></i>  Moneda</th>
                           <th ><i class="fa fa-bars"></i>  Cambio</th>
                           <th ><i class="fa fa-bars"></i>  Estado</th>

                           <th ><i class="fa fa-calerdar"></i>  Fecha</th>

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
            </div></div>
        </section>
<div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->
    
