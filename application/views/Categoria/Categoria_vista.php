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
            <li class="active">Administrar Categoria</li>
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
                     <i class="fa fa-user-plus" aria-hidden="true" id="tituloboton">&nbsp;Agregar Nueva Categoria</i> 
                    </button>
                   </h3>
              <div class="collapse" id="collapseExample">
                      <span class="">Son necesarias las etiquetas de los campos Categorizados con *</span>
                  <div class="">

                    <form action="#" id="from_Categoria" class="from_Categoria">
                        <input type="hidden" value="" name="idCategoria"/> 

                         <div class="col-md-4  col-md-offset-4">
                              <div class="form-group">
                                <label>* Categoria </label>
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-align-center" aria-hidden="true"></i>

                                  </div>
                                    <input required title="Se necesita una Categoria" maxlength="100" type ="text" id="Categoria" name="Categoria" placeholder="Nombre de la Categoria" class="form-control" autofocus autocomplete="off"  placeholder=""  >
                                </div>
                                  <span class ="NO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                              </div>
                              <div class="form-group">
                                <label>* Descripcion </label>
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-align-center" aria-hidden="true"></i>
                                  </div>
                                     <textarea name="Descrip" id="Descrip" minlength="5" maxlength="50" class="form-control" autofocus autocomplete="off" placeholder=""  ></textarea>
                                </div>
                                  <span class ="MA text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                              </div>


                          <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <button type ="submit" id="clic" class="btn  btn-success btn-block btn-flat" >
                              <i class="fas fa-save" id="btnSave"></i> </button>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                              <button type ="reset"  class="btn  btn-info btn-block btn-flat">
                                <i class     ="fa fa-refresh "></i> Limpiar</button>
                              </div>
                              <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <button type ="button" class="btn  btn-danger btn-block btn-flat" data-toggle="collapse" onclick="resetear()" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" >
                                  <span class  ="glyphicon glyphicon-floppy-remove"></span> Cancelar</button>   
                                </div>
                          </div>

                    </form>
                    <!-- fin de formulario -->
                  </div>
                </div>
                  <div class="box-tools pull-right">
                                                <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('categoria')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" href="Reporte_exel/categoria" title="Exportar a EXEL" >
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
                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_Categoria">
                        <thead>
                          <tr>
                                  <th ><i class  ="fa fa-slack" aria-hidden="true"></i></th>
                            <th ><i class="fa fa-bars"></i>  Categoria</th>
                           <th ><i class="fa fa-bars"></i>  Descripcion</th>
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
    
