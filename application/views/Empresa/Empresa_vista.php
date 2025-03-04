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
            <li class="active">Administrar Empresa</li>
          </ol>
        </section>

                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <!-- AREA CHART -->
              <div class="box box-primary">
                <div class="box-header with-border">
                <h3 class="box-title">Administrar Empresa</h3>
                                <div class="collapse" id="collapseExample">
                      <span class="">Son necesarias las etiquetas de los campos marcados con *</span>
                  <div class="well">
                    <!-- contenido de formulario -->
                    <div class="alert alert-info" id="empresar_aler" >
                    <strong class="title" ></strong> 
                    </div>
                    <form action="#" id="from_empresa" class="from_empresa">
                        <input type="hidden" value="" name="idEmpresa"/> 
                           <div class="col-md-4 col-md-offset-2 ol-md-offset-2">
                                <div class="form-group">
                                  <label>* Nombre Empresa:</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-user" aria-hidden="true"></i>
                                    </div>
                                       <input required title="Se necesita un nombre" maxlength="40" type ="text" id="Nombre" name="Nombre" class="form-control" autofocus autocomplete="off"  placeholder="" pattern="[A-Za-z ]{3,100}"   >
                                  </div><!-- /.input group -->
                                    <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->

                                <div class="form-group">
                                      <label>* RUC</label>
                                      <div class="input-group">
                                        <div class="input-group-addon">
                                          <i class="fa fa-inr" aria-hidden="true"></i>
                                        </div>
                                            <input required   type ="text" id="ruc" name="ruc" class="form-control " data-inputmask='"mask": "[9][9][9][9][9][9][9][9][9][9][9][9][9][9]-9"' data-mask  autofocus  >
                                        </div><!-- /.input group -->
                                      <span class ="RU text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->                          

                          <div class="form-group">
                                  <label>Timbrado</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-ravelry" aria-hidden="true"></i>
                                    </div>
                                        <input  type ="text" id="Timbrado" name="Timbrado" class="form-control " onfocus="autofocus"  data-inputmask='"mask": "[9][9][9][9][9][9][9][9][9][9][9][9][9][9]"' data-mask>
                                    </div><!-- /.input group -->
                                  <span class ="TI text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->
                             <div class="form-group">
                                  <label>* Telefono</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-phone"></i>
                                    </div>
                                     <input required title="ingrese telefono" type="text" class="form-control" id="Telefono" name="Telefono" data-inputmask='"mask": "(0999) 999-999"' data-mask/>
                                    </div><!-- /.input group -->
                                  <span class ="TE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->
                        </div>

                          <div class="col-md-4 .ol-md-offset-0">
                                <div class="form-group">
                                  <label>* Correo</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                     <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </div>
                                        <input required   type ="Email" id="Email" name="Email" class="form-control " maxlength="30" placeholder="ejemplo@msn.com" title="ejemplo@correo.com" onfocus="autofocus" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" autofocus  >
                                    </div><!-- /.input group -->
                                  <span class ="EM text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->

                                <div class="form-group">
                                  <label>Series</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-bars" aria-hidden="true"></i>
                                    </div>
                                        <input  type ="text" id="Series" name="Series" class="form-control " onfocus="autofocus"  data-inputmask='"mask": "[9][9][9][9][9][9][9][9][9][9][9][9][9][9]"' data-mask   >
                                    </div><!-- /.input group -->
                                  <span class ="SE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->

                                <div class="form-group">
                                  <label>* Direccion</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    </div>
                                      <input required  type ="text" id="Direccion" name="Direccion" class="form-control " placeholder="" size='45' title="ingrese Direccion" pattern="[A-Za-z ]{5,50}" maxlength="30"  autofocus  >
                                  </div><!-- /.input group -->
                                 <span class ="DI text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->

                               <div class="form-group">
                                  <label>* Comprobante</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-xing" aria-hidden="true"></i>
                                    </div>
                                        <input required type ="text" id="Comprovante" name="Comprovante" class="form-control money" onfocus="autofocus"   data-inputmask='"mask": "[9][9][9][9][9][9][9][9][9][9][9][9][9][9][9]"' data-mask   >

                                    </div><!-- /.input group -->
                                  <span class ="LMC text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->
                                <div class="form-group">
                                 <label>Descripcion</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                     <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                    </div>
                                           <textarea    type="textarea" id="Descripcion" name="Descripcion" class="form-control" placeholder="" size='0' title="Descripcion"pattern="[A-Za-z ]{4,50}" maxlength="100" min="2" max="100"  autofocus   ></textarea>
                                  </div><!-- /.input group -->
                                  <span class ="DE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->
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
                   <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('empresa')">
                   <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                   <a class="btn btn-success btn-xs" href="Reporte_exel/empresa" title="Exportar a EXEL" >
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
                      <table  class="table table-striped table-bordered" cellspacing="30" width="100%" id="tabla_Empresa">
                        <thead>
                          <tr>
                           <th ><i class  ="fa fa-slack" aria-hidden="true"></i></th>
                           <th ><i class="fa fa-user"></i>  Nombre</th>
                           <th ><i class="fa fa-road"></i>  Direccion</th>
                           <th ><i class="fa fa-mobile"></i> Telefono</th>
                           <th ><i class="fa fa-envelope"></i> Correo</th>
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