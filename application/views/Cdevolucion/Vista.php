<style>
         .select2-result-repository { 
                padding-top: 4px;
                padding-bottom: 3px;
            }

            .nav.nav-tabs {
                background-color: #f5f5f5;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-bottom: 15px;
            }

            .nav.nav-tabs li.active {
                background-color: #fff;
                border-bottom-color: transparent;
                font-weight: bold;
            }

            td.details-control {
                background: url('<?php echo base_url('/content/details_open.png');?>') no-repeat center center;
                cursor: pointer;
                width: 20px;
                padding: 8px;
            }

            tr.shown td.details-control {
                background: url('<?php echo base_url('/content/details_close.png');?>') no-repeat center center;
                transition: all 0.3s ease;
            }
     </style>
     
     <div class="content-wrapper">

       <section class="content-header">
        <!-- Agregar las pestañas de "Devolver" y "Listar" aquí -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-devolver" data-toggle="tab" id="add">Devolver</a></li>
            <li><a href="#tab-listar" data-toggle="tab" >Listar Devoluciones</a></li>
        </ul>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Compras</li>
            <li class="active">Devoluciones</li>
        </ol>

    </section>
    <section class="content">
    <!-- Contenido de las pestañas -->
    <div class="tab-content">
        <!-- Pestaña "Devolver" -->
        <div class="tab-pane active" id="tab-devolver">
            <!-- Aquí coloca el contenido para la pestaña "Devolver" -->
            <form method="POST" action="#" accept-charset="UTF-8" name="devolver" id="devolver">
    <input name="idOrden" id="idOrden" type="hidden" value="">
    <div class="row">
        <!-- Left Column: Main Details -->
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="panel-body">
                    <!-- Proveedor -->
                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <span class="input-group-btn">
                                    <button id="seartt-proveedor" class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                                        <span class="">&nbsp;Proveedor</span>
                                    </button>
                                </span>
                                <select required class="form-control form-control-sm proveedor" name="proveedor" id="proveedor" title="Selecciona proveedor" placeholder="Selecciona" required autofocus>
                                    <option value="" selected disabled>Selecciona</option>
                                    <!-- Proveedores aquí -->
                                </select>
                                <!-- Campos para mostrar el nombre y el RUC -->
                                <input type="hidden" id="nombreProveedor" placeholder="Nombre del proveedor"  name="razon_social">
                                <input type="hidden" id="rucProveedor" placeholder="RUC del proveedor"  name="numero_identificacion">
                            </div>
                            <span class="PRO text-danger"></span>
                        </div>
                    </div>
                    <!-- Comprobante -->
                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <span class="input-group-btn">
                                    <button id="seartt-comprobante" class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                                        <span class="">&nbsp;Comprobantes</span>
                                    </button>
                                </span>
                                <select required id="Comprobante" class="form-control select2-allow-clear Comprobante" tabindex="-1" aria-hidden="true" name="Comprobante" title="Seleccione un Comprobante">
                                    <option></option>
                                </select>
                                <input type="hidden" name="comprobanteA" id="comprobanteA">
                                <input type="hidden" name="timbradoA" id="timbradoA">
                            </div>
                            <span class="COMP text-danger"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group input-group-sm">
                            <div class="input-group ">
                            <div class="input-group-btn">
                                <div type="" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-file"></span></div>
                            </div>
                            <select name="tipoComprovante" id="tipoComprovante" tabindex="4" class="form-control select2" required="required">
                                <option value="0">Ticket</option>
                                <option value="1">Factura</option>

                            </select>

                            </div>
                            <span class="TIPO text-danger"></span> <!-- INDICADORES DE ERROR A TRAVÉS DE AJAX -->
                        </div>
                        </div>
                    <?php 
$comprobante = comprobante(); // Obtener el comprobante

$ticket = Ticket(); // Obtener el ticket


// phpinfo();

?>






                    <!-- Opción de Devolución -->
                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default date-set">
                                        <span class="">&nbsp;Devolver</span>
                                    </button>
                                </div>
                                <select required name="tipooccion" id="tipooccion" class="form-control select2">
                                <option value="" selected disabled>Seleccionar opción</option>
                                <option value="1">Mercadería Devuelta y Cambiada</option>
                                <option value="2">Mercadería Devolver y Cobrar</option>
                                <option value="9">Mercadería Devolver y Cambio Posterior</option>
                                <option value="10">Mercadería Devolver y Cobro Posterior</option>
                                </select>
                            </div>
                            <span class="TIPO text-danger"></span>
                        </div>
                    </div>
                    <!-- Motivo de Devolución -->
                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default date-set">
                                        <span class="">&nbsp;&nbsp;* Estado&nbsp;&nbsp;</span>
                                    </button>
                                </div>
                                <select required name="mov" id="mov" class="form-control select2">
                                    <option value="" selected disabled>Seleccionar motivo</option>
                                    <!-- Motivos aquí -->
                                    <?php foreach ($motivos as $motivo) { ?>
                                        <option value="<?php echo $motivo->idMotivo; ?>"><?php echo $motivo->Nombre; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <span class="mov text-danger"></span>
                        </div>
                    </div>

<div class="col-md-2">
  <input required type='hidden' class="form-control input-sm " id="fecha" tabindex="-1" name="fecha" value="<?php echo date("d-m-Y");?>"/>
  <div class="form-group hidden" id="comprobante"  >
    <div class="input-group input-group-sm">
      <div class="input-group">
        <div class="input-group-btn">
          <div id="PrimeraSeccion" class="btn btn-default btn-sm btnsm" tabindex="-1" data-select2-open="single-prepend-text">
          <?php echo $comprobante["PrimeraSeccion"]; ?>
          </div>
        </div>
        <div class="input-group-btn">
          <div id="SegundaSeccion" class="btn btn-default btn-sm btnsm" tabindex="-1" data-select2-open="single-prepend-text">
          <?php echo $comprobante["SegundaSeccion"]; ?>
          </div>
        </div>
        <div class="input-group-btn">
          <div id="TerceraSeccion" class="btn btn-default btn-sm btnsm" tabindex="-1" data-select2-open="single-prepend-text">
          <?php echo $comprobante["TerceraSeccion"]; ?>
          </div>
        </div>

      </div>
      <span class="COMP text-danger"></span> <!-- INDICADORES DE ERROR A TRAVÉS DE AJAX -->
    </div>
  </div>
  <div class="form-group" id="Ticket"  >
    <div class="input-group input-group-sm">
      <div class="input-group">
        <div class="input-group-btn">
          <button id="" class="btn btn-default btn-sm " type="button" data-select2-open="single-prepend-text" tabindex="-1">
          <i class="fa fa-ticket"></i>
          </button>
        </div>

          <input class="form-control input-sm " readonly  id="inputTicket" tabindex="-1"   type="text" name="Ticket" value="<?php echo $ticket['NumeroTicket']; ?>">

        
      </div>
      <span class="COMP text-danger"></span> <!-- INDICADORES DE ERROR A TRAVÉS DE AJAX -->
    </div>
  </div>
</div>
  





                    <div class="col-md-12" id="detalle">
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Right Column: Order Summary -->
        <div class="col-md-3">
            <div class="box box-info">
                <div class="panel-body">
                    <h4>Resumen del Pedido</h4>
                    <!-- Gestión de Inventario -->
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default date-set">
                                        <span class="">Gestión</span>
                                    </button>
                                </div>
                                <select required name="inventario" id="inventario" class="form-control select2">
                                    <option value="" selected disabled>Seleccionar</option>
                                    <option value="1">Afectar al Stock</option>
                                    <option value="2">Afectar al Depósito</option>
                                </select>
                            </div>
                            <span class="mov text-danger"></span>
                        </div>
                    <div class="form-group">
                        <span class="label label-default"> Fecha de Devolución</span>
                        <div class="input-group input-group-sm">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default date-set"><i class="fa fa-random" aria-hidden="true"></i></button>
                            </div>
                            <input type="date" name="fecha_devolucion" id="fecha_devolucion" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group" id="fecha_pago_group" style="display:none;">
                        <span class="label label-default" id="fecha_label"> Fecha de Pago</span>
                        <div class="input-group input-group-sm">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default date-set"><i class="fa fa-random" aria-hidden="true"></i></button>
                            </div>
                            <input type="date" name="fecha_pago" id="fecha_pago" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <span class="label label-default"> Número de Referencia</span>
                        <div class="input-group input-group-sm">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default date-set"><i class="fa fa-random" aria-hidden="true"></i></button>
                            </div>
                            <input type="text" name="num_referencia" id="num_referencia" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <span class="label label-default"> Descuento Aplicado</span>
                        <div class="input-group input-group-sm">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default date-set"><i class="fa fa-random" aria-hidden="true"></i></button>
                            </div>
                            <input type="text" name="descuento" id="descuento" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <span class="label label-default">Fletes</span>
                        <div class="input-group input-group-sm">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default date-set"><i class="fa fa-random" aria-hidden="true"></i></button>
                            </div>
                            <input maxlength="15" type="number" class="form-control sumar" id="fletes" name="fletes" placeholder="Fletes / Acarreos" />
                        </div>
                        <span class="FLE text-danger"></span>
                    </div>
                    <div class="box box-info "></div>
                <strong class ="profile-ava  btn-caja">
                 <i class      ="fa fa-user fa-fw"></i>Usuario :
                 <abbr class   ="username">
                   <span class   ="text-danger">
                     <?= ucfirst($this->session->userdata('Usuario'));?>
                   </span>
                 </abbr>
                </strong> 
                <strong class ="profile-ava  btn-caja">
                 <i class      ="fa fa-cubes fa-fw"></i>  Caja :
                 <abbr class   ="username">
                   <span class   ="text-danger">
                     <?= $this->session->userdata('idCaja'); ?>
                   </span>
                 </abbr>
                 </strong>
                 <strong class ="profile-ava  btn-caja">
                 <i class="fa fa-calendar"></i>  Fecha :
                 <abbr class   ="username">
                   <span class   ="text-success">
                   <?php echo date("d-m-Y");?> 
                   </span>
                 </abbr>
                 </strong>

                    
                    <h2 class="bg-navy"> ₲ <span id="Importe1"> </span> </h2>
                    <button class="btn btn-lg btn-success btn-block btn-flat" id="add01">
                        <i class="fas fa-save" id="bguarda"></i> Guardar
                    </button>
                    <button type="reset" onclick="Limpiar('1');" class="btn btn-lg btn-info btn-block btn-flat">
                        <i class="fa fa-refresh"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <input type='hidden' class="validate" id="id" name="id" value="" />
    <input type='hidden' class="validate" id="descuento" name="descuento" value="" />
    <input type='hidden' class="validate" id="fletes" name="fletes" value="" />
</form>


        </div>

        <!-- Pestaña "Listar Devoluciones" -->
        <div class="tab-pane" id="tab-listar">
        <div class="row">
                <div class="col-md-12">
                  <!-- AREA CHART -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                          <div class="box-tools pull-right">
                             <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('cdevolucion')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" href="Reporte_exel/cdevolucion" title="Exportar a EXEL" >
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
                                      <table  class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_CD">
                                        <thead>
                                          <tr>
                                           <th ></th>
                                           <th ><i class="fa fa-bars"></i> Comprobante</th>
                                           <th ><i class="fa fa-truck"></i>  Proveedor</th>
                                           <th ><i class="fa fa-truck"></i>  Accion</th>
                                           <th ><i class="fa fa-truck"></i>  Motivo</th>

                                           <th ><i class="fa fa-calendar"></i>  Fecha</th>
                                           <th><i class="fa fa-info-circle"></i> Estado Agregado</th>
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
        </div>
    </div>
</section>

<div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->
    
