      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Producto</li></li>
            <li class="active">Administrar Permiso</li>
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
                     <i class="fa fa-user-plus" aria-hidden="true" id="tituloboton">&nbsp;Agregar Nueva Permiso</i> 
                    </button>
                   </h3>
                <div class="collapse" id="collapseExample">
                      <span class="">Son necesarias las etiquetas de los campos marcados con *</span>
                  <div class="well">
                    <!-- contenido de formulario -->
        						<div class="alert alert-info" id="Permiso_aler" >
        						<strong class="title" ></strong> 
        						</div>
                    <form action="#" id="from_Permiso" class="from_Permiso">
                        <input type="hidden" value="" name="idPermiso"/> 

                         <div class="col-md-4  col-md-offset-0">
                              <div class="form-group">
                                <label>* Nombre </label>
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                  </div>
                                     <input required title="Se necesita un nombre" maxlength="40" type ="text" id="Nombre" name="Nombre" class="form-control" autofocus autocomplete="off"  >
                                </div>
                                  <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                              </div>
                              <div class="form-group">
                                <label>* Descripcion </label>
                                <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-align-center" aria-hidden="true"></i>
                                  </div>
                                     <textarea name="Oservacion" id="Oservacion" minlength="5" maxlength="50" class="form-control" autofocus autocomplete="off" placeholder=""  ></textarea>
                                </div>
                                  <span class ="DD text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                              </div>
                        </div>
                        <div class="col-md-8  col-md-offset-0">
                           <div class="form-group">
                                  <label>Permiso Menu</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-bars" aria-hidden="true"></i>
                                    </div>
                                        <select  name="multi[]" id="multi"  Size="4" class="form-control select2-multiple multi" multiple="multiple" style="width: 100%" >
                                        <option value=""></option>
                                       <?php 
                                        foreach($Menu as $fila_menu)
                                        {
                                          ?>
                                          <option value="<?php echo $fila_menu -> idMenu ?>"><?php echo $fila_menu -> Nombre;?> </option>
                                          <?php
                                        }
                                        ?>
                                       </select>
                                  </div>
                                 <span class ="PRO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
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
                    <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('permiso')">
                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                    <a class="btn btn-success btn-xs" href="Reporte_exel/permi" title="Exportar a EXEL" >
                    <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                    </a></div>
                </div>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                    <div class="collapse" id="collaje_Permiso">

                      <div class="card card-block" >
                        <div class="box box-solid">
                          <div class="box-header with-border">
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            <h3 class="box-title">Acceso a los Menus  <a data-toggle="collapse" href="#collaje_Permiso" aria-expanded="false" controls="collaje_Permiso"> <i class="fa fa-chevron-up" aria-hidden="true"></i></a></h3>
                          </div><!-- /.box-header -->
                          <div class="box-body" >
                            <ol id="res_per">
                            </ol>
                          </div><!-- /.box-body -->
                        </div><!-- /.box -->
                      </div>
                    </div>
                <div class="box-body">
                   <!-- tabla inicial -->
                  <div class="box">
                    <div class="box-body table-responsive no-padding">
                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_Permiso">
                        <thead>
                          <tr>
                               <th ><i class ="fa fa-slack" aria-hidden="true"></i></th>
                               <th ><i class ="ion-ios-copy"></i>  Nombre</th>
                               <th ><i class ="fa fa-align-center"></i>  Descripcion</th>
                               <th ><i class ="fa fa-bars"></i>  Permisos</th>
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