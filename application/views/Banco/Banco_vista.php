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
            <li class="active">Banco</li>
          </ol>
       </section> 
                  <div id="alertasadd" style="display: none" >

                  </div>
        <div class="col-md-12">
          <div class="collapse" id="collapseExample" aria-expanded="true">
                 <span class="col-md-4">Son necesarias las etiquetas de los campos marcados con *</span>
                   <form action="#" id="from_Banco" class="from_Banco">
                   <input type="hidden" name="id" id="id" value="">
  <div class="row">
                                <div class="col-md-12 ">
                                    <div class="box panel-default">
                                        <div class="panel-body">
                                            <div class="row">
                                                        <div class="col-md-4 col-md-offset-0 ">
                                                          <div class="form-group">
                                                            <label>* Nombre del Banco </label>
                                                            <div class="input-group input-group-sm">
                                                              <div class="input-group-addon">
                                                                <i class="fa fa-university " aria-hidden="true"></i>
                                                              </div>
                                                                 <input autofocus required  title="Se necesita un nombre" maxlength="40" type ="text" id="nombre" name="nombre" class="form-control" autofocus autocomplete="off" placeholder=""  >
                                                            </div>
                                                              <span class ="ccc text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                          </div>
                                                        </div>
                                                        <div class="col-md-4 col-md-offset-=0 ">

                                                          <div class="form-group">
                                                            <label>* Numero del Banco </label>
                                                            <div class="input-group input-group-sm">
                                                              <div class="input-group-addon">
                                                                <i class="fa fa-bars" aria-hidden="true"></i>
                                                              </div>
                                                                 <input required title="Se necesita un numero" maxlength="40" type ="text" id="numero" name="numero" class="form-control" autofocus autocomplete="off" placeholder="Numero bancario"  >
                                                            </div>
                                                              <span class ="nnn text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                          </div>
                                                        </div>
                                                        <div class="col-md-4 col-md-offset-=0 ">

                                                          <div class="form-group">
                                                            <label> Monto Inicial </label>
                                                            <div class="input-group input-group-sm">
                                                              <div class="input-group-addon">
                                                                <i class="fa fa-bars" aria-hidden="true"></i>
                                                              </div>
                                                                 <input  title="Se necesita un monto" maxlength="40" type ="text" id="monto" name="monto" class="form-control" data-inputmask='"mask":"99999999999999999999"' data-mask  >
                                                            </div>
                                                              <span class ="nnn text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                          </div>
                                                        </div>
                                                        <div class="col-md-4 col-md-offset-8 ">
                                                  <div class="pull-right">
                                                              <button type="submit" name="add" class="btn btn-sm btn-success">
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
                     <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('Bancos')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" href="Reporte_exel/Bancos" title="Exportar a EXEL" >
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
                                    <style type="text/css" media="screen">
                                        td.details-control {
                                            background: url('<?php echo base_url('/content/details_open.png');?>') no-repeat center center;
                                            cursor: pointer;
                                        }
                                        tr.shown td.details-control {
                                            background: url('<?php echo base_url('/content/details_close.png');?>') no-repeat center center;
                                        }
                                     </style>
                                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_Banco">
                                        <thead>
                                          <tr>
                                           <th style="width:80px;">Movimientos</th>
                                           <th ><i class="fa fa-university text-red"></i>  Nombre Banco </th>
                                           <th ><i class="fa fa-bars text-red"></i>  Numero</th>
                                           <th ><i class="fa fa-money text-red"></i>  Monto Activo</th>
                                           <th ><i class="fa fa-cogs text-red" ></i> Acciones</th>
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
    
