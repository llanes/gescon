      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
              <button id="add" class="btn  btn-success btn-sm">
               <i class="" id="accion" aria-hidden="true">&nbsp;&nbsp;Agregar</i> 
              </button>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Banco</li>
            <li class="active">Plan de Cuenta</li>
          </ol>
       </section> 
                  <div id="alertasadd" style="display: none" >

                  </div>
        <div class="col-md-12">
          <div class="collapse" id="collapseExample" aria-expanded="true">
                 <span class="col-md-4">Son necesarias las etiquetas de los campos marcados con *</span>
                   <form action="#" id="from_Plan" class="from_Plan">
                   <input type="hidden" name="id" id="id" value="">
  <div class="row">
                                <div class="col-md-12">
                                    <div class="box panel-default">
                                        <div class="panel-body">
                                            <div class="row">

                                                        <div class="col-md-4  col-md-offset-2  form-group">
                                                          <div class="form-group">
                                                            <label> Codigo </label>
                                                            <div class="input-group input-group-sm">
                                                              <div class="input-group-addon">
                                                                <i class="fa fa-barcode" aria-hidden="true"></i>
                                                              </div>
                                                                 <input  title="Se necesita un nombre" maxlength="40" type ="text" id="Codigo" name="Codigo" class="form-control" autofocus autocomplete="off" placeholder=""  >
                                                            </div>
                                                              <span class ="ccc text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                          </div>
                                                        </div>
                                                        <div class="col-md-4  col-md-offset-0 form-group">
                                                          <div class="form-group">
                                                            <label>* Nombre de la Cuenta </label>
                                                            <div class="input-group input-group-sm">
                                                              <div class="input-group-addon">
                                                                <i class="fa fa-bars" aria-hidden="true"></i>
                                                              </div>
                                                                 <input required title="Se necesita un nombre" maxlength="40" type ="text" id="nombre" name="nombre" class="form-control" autofocus autocomplete="off" placeholder=""  >
                                                            </div>
                                                              <span class ="nnn text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                          </div>
                                                        </div>

                                                          <div class="col-md-12" id="detalle">
                        <div class="pull-right">
                                    <button type="submit" class="btn btn-sm btn-success">
                                    <span class="glyphicon glyphicon-floppy-disk" id="btnSave">Guardar</span> </button>&nbsp;&nbsp;
                                    <button type="reset" class="btn btn-sm btn-info">
                                    <i class="fa fa-refresh "></i> Limpiar</button>&nbsp;
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="collapse" onclick="resetear()" data-target="#collapseExample" aria-expanded="true" aria-controls="collapseExample">
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
                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <section class="content">
          <div class="row">
                <div class="col-md-12">
                  <!-- AREA CHART -->
                    <div class="box box-primary">
                                <div class="box-header with-border">
                                  <div class="box-tools pull-right">
                                    <div class="btn-group">
                                        <button class="btn btn-box-tool" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-bars"></i>
                                        </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                          <li><a href="#"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Exportar PDF</a></li>
                                        </ul>
                                    </div>
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                  </div>
                                </div>
                               <div class="box-body">
                                   <!-- tabla inicial -->
                                  <div class="box">
                                    <div class="box-body table-responsive no-padding">
                                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_Plan">
                                        <thead>
                                          <tr>
                                           <th ></th>
                                           <th ><i class="fa fa-bars"></i>  Codigo</th>
                                           <th ><i class="fa fa-truck"></i>  Nombre de la Cuenta </th>
                                           <!-- <th ><i class="fa fa-calendar"></i>  Categoria</th> -->
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
    
