      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <button id="_add" class="btn  btn-success btn-sm"  data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
             <i class="" id="accion" aria-hidden="true">&nbsp;&nbsp;Recibir Devolucion</i> 
           </button>
         </h1>
         <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
          <li class="active">Ventas</li></li>
          <li class="active">Devoluciones de Productos</li>
        </ol>
      </section> 
      <div id="alerta">
      </div>
      <div class="col-md-12">
        <div class="collapse" id="collapseExample">
          <section class="content-header">
          </section>
          <form method="POST" action="#" accept-charset="UTF-8" name="recibir_devolucion" id="recibir_devolucion">
            <input name="idOrden" id="idOrden" type="hidden" value="">
            <div class="row">
              <div class="col-md-12">
                <div class="box panel-default">
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-6">  

                        <div class="form-group">
                         <div class='input-group input-group-sm'>
                          <div class="input-group-btn">
                            <button type="button" class="btn btn-default date-set">
                              <span class="">&nbsp;* Opcion</span>
                            </button>
                          </div>
                          <select required name="tipooccion" id="tipooccion" class="form-control select2" required="required">
                           <option value="1">Recibir y Cambiar</option>
                           <option value="2">Recibir y Pagar Efectivo</option>
                           <option value="3">Recibir y Agregar a Cuenta Favor</option>
                         </select>

                       </div>
                       <span class ="TIPO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                     </div>
                   </div>
                   <div class="col-md-6">  
                    <div class="form-group">
                      <div class="input-group input-group-sm">
                        <div class="input-group-btn">
                          <button type="button" class="btn btn-default date-set">
                            <span class="">&nbsp;&nbsp;* Motivo&nbsp;&nbsp;</span>
                          </button>
                        </div>
                        <select required name="mov" id="mov" class="form-control select2" required="required">
                         <option selected></option>
                         <option value="1">Producto Vencido</option>
                         <option value="2">Producto Descompuesto</option>
                         <option value="3">Otros Motivos</option>
                       </select>
                     </div>
                     <span class ="mov text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                   </div>

                 </div>
                 <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group input-group-sm">
                      <span class="input-group-btn">
                        <button id="seartt" class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                          <span class="">&nbsp;Cliente</span>
                        </button>
                      </span>
                      <select required id="single-prepend-text" class="form-control select2-allow-clear Cliente" tabindex="-1" aria-hidden="true" name="Cliente" title="Seleccione un Cliente">
                        <option></option>
                        <?php 
                        foreach($Cliente as $key => $value)
                        {
                          ?>
                          <option value="<?php echo $value -> idCliente ?>"><?php echo $value -> Nombres;?>  (<?php echo $value -> Ruc;?>) </option>
                          <?php
                        }
                        ?>
                      </select>
                    </div>
                    <span class ="PRO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group input-group-sm">
                      <span class="input-group-btn">
                        <button id="seartt" class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                          <span class="">&nbsp;Comprobantes</span>
                        </button>
                      </span>
                      <select required id="single-prepend-text" class="form-control select2-allow-clear Comprobante" tabindex="-1" aria-hidden="true" name="Comprobante" title="Seleccione un Comprobante" id="Comprobante">
                        <option></option>
                      </select>
                    </div>
                    <span class ="COMP text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>
                </div>
              </div>
              <div class="col-md-12" id="detalle">
              </div>

            </div>
          </div>
        </div>
      </div>
      <input hidden type='text'  class="validate" id="id" name="id" value=""/>
      <input hidden type='text'  class="validate" id="descuento" name="descuento" value=""/>
      <input hidden type='text' class=" validate" id="fletes" name="fletes" value="" />
    </form>
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
                <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('ventadevol')">
                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                  <a class="btn btn-success btn-xs" href="Reporte_exel/ventadevol" title="Exportar a EXEL">
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
                <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_VD">
                  <thead>
                    <tr>
                     <th ></th>
                     <th ><i class="fa fa-bars"></i> Comprobante</th>
                     <th ><i class="fa fa-truck"></i>  Cliente</th>
                     <th ><i class="fa fa-calendar"></i>  Fecha</th>
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

