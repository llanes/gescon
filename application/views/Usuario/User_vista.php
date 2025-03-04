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
            <li class="active">Administrar Usuario
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
                     <i class="fa fa-user-plus" aria-hidden="true" id="tituloboton">&nbsp;Agregar Nuevo Usuario</i> 
                    </button>
                   </h3>
                  <div class="collapse" id="collapseExample">
                      <span class="">Son necesarias las etiquetas de los campos marcados con *</span>
                  <div class="well">
                    <!-- contenido de formulario -->
                    <div class="alert alert-info" id="user_aler" >
                        <strong class="title" ></strong> 
                    </div>
                    <form action="#" id="from_user" class="from_user">
                        <input type="hidden" value="" name="idUsuario"/> 
                        <div class="col-md-4 col-md-offset-4">
                                  <div class="form-group">
                                    <label>* Nombre Usuario:</label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                      </div>
                                    <input required title="Se necesita un nombre" maxlength="30" type ="text" id="usuario" name="usuario" class="form-control" onfocus="autofocus" autocomplete="off"  placeholder="Usuario" pattern="[A-Za-z ]{3,100}"   >
                                    </div>
                                      <span class ="US text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div>

                                  <div class="form-group">
                                    <label>* Tipo de Acceso:</label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-compress" aria-hidden="true"></i>
                                      </div>
                                          <select required class="form-control" name="cargo" id="cargo"  title="Seleciona cargo"  placeholder="Selecciona">
                                          <option value=""></option>
                                        <?php 
                                        foreach($Acceso as $fila1)
                                        {
                                          ?>
                                          <option value="<?php echo $fila1 -> idPermiso ?>"><?php echo $fila1 -> Descripcion;?></option>
                                          <?php
                                        }
                                        ?>
                                         </select>
                                      </div>
                                    <span class ="CA text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div>
                                  <!-- Date mm/dd/yyyy -->
                                  <div id="pasnew" class="form-group" style="display: none">
                                   <label id="recuperar">* Contraseña Antigua:</label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                       <i class="fa fa-key" aria-hidden="true"></i>
                                      </div>
                                         <input  title="Se necesita una Contraseña"  maxlength="45" type="password" id="pasantigua" name="pasantigua" class="form-control" onfocus="autofocus" autocomplete="off"  placeholder="Contraseña"   pattern="(?=.*[a-z]).{6,}"   onkeyup=";verificar();" onKEYPRESS="verificar();"  value="" >
                                    </div>
                                    <span class ="C_A text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div>

                                  <div class="form-group">
                                   <label id="con"></label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                       <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                                      </div>
                                         <input disabled title="Se necesita una Contraseña"  maxlength="45" type ="password" id="password" name="password" class="form-control" onfocus="autofocus" autocomplete="off"  placeholder="Contraseña"   pattern="(?=.*[a-z]).{6,}"  >
                                    </div>
                                    <span class ="CO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div>

                                  <div class="form-group">
                                    <label>* Confirmar Contraseña</label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                       <i class="fa fa-lock" aria-hidden="true"></i>
                                      </div>
                                      <input disabled  title="Confirmar Contraseña" type ="password" maxlength="45" id="passconf" name="passconf" class="form-control" onfocus="autofocus" autocomplete="off"  placeholder="Repetir Contraseña"  pattern="(?=.*[a-z]).{6,}">
                                    </div>
                                   <span class ="CC text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
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
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('user')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" href="Reporte_exel/user" title="Exportar a EXEL" >
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
                      <table  class="table table-striped table-bordered" cellspacing="30" width="100%" id="tabla_Usuario">
                        <thead>
                          <tr>
                              <th ><i class  ="fa fa-slack" aria-hidden="true"></i></th>
                              <th ><i class ="fa fa-user"></i>  Nombre</th>
                              <th ><i class ="fa fa-align-center" aria-hidden="true"></i></i>Descripcion</th>
                              <th ><i class ="fa fa-plug" aria-hidden="true"></i>Oservacion  </th>
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