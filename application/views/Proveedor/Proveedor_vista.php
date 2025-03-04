      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Seguridad</li></li>
            <li class="active">Administrar Proveedor
            </li>
          </ol>
        </section>
                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <!-- AREA CHART -->
              <div class="box box-primary">
                <div class="box-header with-border">
                   <h3 class="box-title"> 
                    <button id="add" class="btn btn-sm btn-success" onclick="_add()" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                     <i class="fa fa-user-plus" aria-hidden="true" id="tituloboton">&nbsp;Agregar Nuevo Proveedor</i> 
                    </button>
                   </h3>
                <div class="collapse" id="collapseExample">
                      <span class="">Son necesarias las etiquetas de los campos marcados con *</span>
                  <div class="well">
                    <!-- contenido de formulario -->
                    <div class="alert alert-info" id="Proveedorr_aler" >
                        <strong class="title" ></strong> 
                    </div>
                    <form action="#" id="from_Proveedor" class="from_Proveedor">
                        <input type="hidden" value="" id="idProveedor" name="idProveedor"/> 
                        <div class="col-md-4 .ol-md-offset-0">
                                <div class="form-group">
                                      <label>* RUC</label>
                                      <div class="input-group">
                                        <div class="input-group-addon">
                                          <i class="fa fa-bars" aria-hidden="true"></i>
                                        </div>
                                            <input required   type ="text" id="Ruc" name="Ruc" class="form-control "  autofocus autocomplete="off"  data-inputmask='"mask": "9999[9][9][9][9][9][9][9][9][9][9][-][9]"' data-mask  >
                                        </div>
                                       <span class ="RU text-danger" ></span>   <!--   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div>

                                <div class="form-group">
                                  <label>* Direccion</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    </div>
                                      <input required  type ="text" id="Direccion" name="Direccion" class="form-control " maxlength="45"  title="ingrese Direccion" pattern="[A-Za-z ]{5,50}" maxlength="45"  autofocus autocomplete="off"    >
                                  </div>
                                 <span class ="DI text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div>

                                 <div class="form-group">
                                  <label>Pagina Web</label>
                                      <div class="input-group">
                                        <div class="input-group-addon">
                                         <i class="fa fa-globe" aria-hidden="true"></i>
                                        </div>
                                            <input type ="url" maxlength="45"  id="Pagina_Web" name="Pagina_Web" pattern="https?://.+" class="form-control " placeholder="http://www.ejemplo.com"  autofocus  >
                                        </div> 
                                      <span class ="PG text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                 </div>

                          </div>

                          <div class="col-md-4 .ol-md-offset-0">

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
                          </div>

                          <div class="col-md-4 .ol-md-offset-0">
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
                                  <label>Correo</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                     <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </div>
                                        <input    type ="Email" id="Correo" name="Correo" class="form-control " placeholder="ejemplo@msn.com" title="ejemplo@correo.com" onfocus="autofocus"  pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" autofocus  >
                                    </div>
                                  <span class ="EM text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div> 

                        </div>
                    <table class="table">
                      <thead>
                        <tr>
                          <th>
                           <div class="pull-right">
                                    <button type ="submit"  class="btn btn-sm btn-success">
                                    <span class  ="glyphicon glyphicon-floppy-disk" id="btnSave"  >Guardar</span> </button>&nbsp;&nbsp;
                                    <button type ="reset"  class="btn btn-sm btn-info">
                                    <i class     ="fa fa-refresh "></i> Limpiar</button>&nbsp;
                                    <button type ="button" class="btn btn-sm btn-danger" data-toggle="collapse" onclick="resetear()" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" >
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
                          <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('proveedor')">
                          <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                          <a class="btn btn-success btn-xs" href="Reporte_exel/proveedor" title="Exportar a EXEL" >
                          <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                          </a></div>
                    </div>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                   <!-- tabla inicial -->
                  <div class="box">
                    <div class="box-body table-responsive no-padding">
                      <table  class="table table-striped table-bordered" cellspacing="30" width="100%" id="tabla_Proveedor">
                        <thead>
                          <tr>
                            <th><i class ="fa fa-slack" aria-hidden="true"></i></th>
                            <th><i class  ="fa fa-user"></i> Vendedor</th>
                            <th><i class  ="fa fa-inr" aria-hidden="true"></i> Ruc</th>
                            <th><i class  ="fa fa-industry" aria-hidden="true"></i> Razon Social</th>
                            <th><i class  ="fa fa-map-marker"></i> Direccion</th>
                            <th><i class  ="fa fa-mobile" aria-hidden="true"></i> Telefono</th>
                            <th><i class  ="fa fa-envelope"></i> Correo</th>
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