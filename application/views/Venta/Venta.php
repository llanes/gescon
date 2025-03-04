      <div class="content-wrapper">
  <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
 
        <!-- <section class="content-header">
          <h1 class="col-md-1">
              <button id="add" class="btn  btn-success btn-sm" >
               <i class="" id="accion" aria-hidden="true">&nbsp;&nbsp;Vender</i> 
              </button>
          </h1>
         <H5 class="col-md-10" id="atajos" style="display: none">
           <span class="text-danger">CTRL + Q = Cliente </span> |   <span class="text-danger">CTRL + B = Guardar </span> |   <span class="text-danger">CTRL + M = Nuevo Cliente </span> |   <span class="text-danger">CTRL + Z = Productos </span></H5>

          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Ventas</li></li>
            <li class="active">Vender</li>
          </ol>
       </section>  -->

        <div >

                   <div >
                    <form method="POST" action="#" accept-charset="UTF-8" name="vender" id="vender" autocomplete="off">
                    <input name="idOrden" id="idOrden" type="hidden" value="">
                            <div class="row">
                                <div class="col-md-9 col-col">
                                    <div class="box panel-default">
                                        <div class="panel-body">
                                            <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-btn">
                                                                       <button id="seartt" tabindex="-1" onclick="addclien();" name="seartttt" class="btn btn-default" type="button" data-toggle="tooltip" data-placement="top"  title="Nuevo Cliente">
                                                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                                                        </button>
                                                                </span>
                                                                <select data-select2-open="single-prepend-text" id="ClienteSearch" class="form-control input-sm select2-allow-clear Cliente" tabindex="1" aria-hidden="true" name="Cliente" title="Seleccione un Cliente" >
                                                                    <option></option>

                                                                </select>
                                                                <span class="input-group-btn">
                                                                    <button   class="btn btn-default" type="button" data-select2-open="single-prepend-text_2" tabindex="-1">
                                                                    F1
                                                                    </button>
                                                                </span>
                                                              </div>
                                                             <span class ="PRO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                        </div>

                                                        <div class="form-group">
                                                                <div class="input-group input-group-sm">
                                                                <span class="input-group-btn">
                                                                    <div  id="seat2" name="seat2" class="btn btn-default"  data-select2-open="single-prepend-text_2">
                                                                    <i class="fas fa-plus"></i>
                                                                    
                                                                    </div >
                                                                </span>

                                                                <input type="text" id="campoBusqueda" class="form-control custom-focus-style" tabindex="2" placeholder="Buscar productos 'Shift Enfocar'" name="campoBusqueda">

                                                                <span class="input-group-btn">
                                                                    <button  type="button" tabindex="-1"  class="btn btn-default search-product"  data-select2-open="single-prepend-text_2">
                                                                    <i class="fa fa-barcode" aria-hidden="true"></i> 
                                                                    Shift
                                                                    </button >
                                                                </span>
                                                                <input type="hidden" name="name" id="name" value="">
                                                                <input type="hidden" name="precio" id="precio" value="precio">
                                                                <input type="hidden" name="iva" id="iva" value="iva">

                                                                </div>
                                                                <span class ="PR text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                                            </div>
                                                    </div>

<div class="col-md-4">
    <div class="form-group">
        <div class="input-group input-group-sm">
            <div class="input-group">
                <div class="input-group-btn">
                    <div id="seartt" name="seartt" class="btn btn-default btn-sm"  data-select2-open="single-prepend-text_3">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                </div>
                <select id="single-prepend-text_3" class="form-control select2-allow-clear orden" tabindex="3" aria-hidden="true" name="orden">
                    <option></option>
                </select>
                <span class="input-group-btn">
            <div   class="btn btn-default"  data-select2-open="single-prepend-text_2">
                F3
            </div>
        </span>

            </div>
        </div>
    </div>

<div class="form-group">
  <div class="input-group input-group-sm">
    <div class="input-group ">
      <div class="input-group-btn">
        <div type="" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-file"></span></div>
      </div>
      <select name="tipoComprovante" id="tipoComprovante" tabindex="4" class="form-control select" required="required">
      <option value=""></option>
        <option value="0">Ticket</option>
        <option value="1">Factura</option>
      </select>
      <span class="input-group-btn">
            <div   class="btn btn-default"  data-select2-open="single-prepend-text_2">
                F4
    </div>
        </span>

    </div>
    <span class="TIPO text-danger"></span> <!-- INDICADORES DE ERROR A TRAVÉS DE AJAX -->
  </div>
</div>
                                                    </div>
  
<?php 
$comprobante = comprobante(); // Obtener el comprobante

$ticket = Ticket(); // Obtener el ticket
?>

<div class="col-md-2">
  <div class="form-group">
    <div class='input-group date'>
      <div class="input-group-btn">
            <div  class="btn btn-default btn-sm"> 
               <i class="fa fa-calendar"></i>  
            </div> 
      </div>
      <!-- //// -->
    </div>
    <span class ="FECHA text-danger"></span> <!-- INDICADORES DE ERROR A TRAVÉS DE AJAX -->
  </div>


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
  



                                            </div>
                  <!-- detalle -->
                  <div class="col-md-12 col-xs" id="detalle">

                    
                  </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="panel panel-default cls-panel">

                                        <div class="panel-body">

                                        <div class="form-group">
                  <span class="label label-default">Forma de Cobros</span>

                    <div class='input-group input-group-sm'>
                      <div class="input-group-btn">
                        <button type="button" tabindex="-1" class="btn btn-default btn-sm">
                        <span class="glyphicon glyphicon-credit-card"></span>
                        </button>

                      </div>
                      <select name="Estado" id="Estado" class="form-control select" required="required"  tabindex="5">
                        <option value="0">Contado</option>
                        <option value="2">Credito</option>
                      </select>
                      <span class="input-group-btn">
                              <div   class="btn btn-default"  data-select2-open="single-prepend-text_2">
                                  F8
                      </div>
                          </span>
                    </div>
                    <span class ="TIPO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>
<div style="display: none" id="contenCuotas">
                                            <div class="form-group" >
                                            <span class="label label-default">Cantidad de Cuotas</span>
                                                <div class="input-group">
                                                  <div class="input-group-btn">
                                                      <button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-stats"></span></button>
                                                  </div>
                                                    <select name="cuotas" id="cuotas" class="form-control input-sm" required>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                    </select>
                                                </div>
                                                <span class ="CUO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                            </div>
                <div class="form-group">
                      <span class="label label-default">Fecha de Inicio de Cuota:</span>
                      <div class='input-group'>
                          <div class="input-group-btn">
                              <button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-calendar"></span></button>
                          </div>
                          <input type='date' class="form-control input-sm" id="fecha_inicio_cuota" name="fecha_inicio_cuota" placeholder="Fecha de Inicio de Cuota" value="<?php echo date("Y-m-d") ?>"/>
                      </div>
                      <span class="desc text-danger"></span> <!-- Indicadores de error a través de AJAX -->
                </div>
                  <div class="progress-group">
                    <span class="progress-text">Límite de Crédito:</span>
                    <span class="progress-number"></span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-aqua" id="progress"></div>
                    </div>
                  </div>
</div>

                      <div class="form-group">

                    <div class='input-group input-group-sm' >
                      <div class="input-group-btn">
                      <button type="button" class="btn btn-default date-set">
                        <input type="checkbox"  name="checControl" id="checControl" value="0"  class="limpiar">
                        <input type="hidden" name="virtual" id="virtual">
                        </button>
                      </div>
                      <input disabled maxlength="15" type='text' class="form-control sumar validate" id="fletes" name="fletes" placeholder="Fletes" maxlength="15" pattern="[0-9]{0,15}"  />
                    </div>
                    <span class ="FLE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>

                                            <div class="form-group hidden" id="direcEnvio">
                                                <span class="label label-default">Direcion de Envio</span>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-comment"></i></span>
                                                    <textarea disabled class="form-control input-sm"  maxlength="50" rows="1" placeholder="Direccion" name="Direccion" type="text" id="Direccion"></textarea>
                                                </div>
                                                <span class ="DIR text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
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
                                            <button class="btn btn-lg btn-success btn-block btn-flat" id="add01"  tabindex="6" type="button">
                                                <i class="fas fa-save" id="bguarda"></i> Vender
                                            </button>

                                              <button type ="reset"  tabindex="7" onclick="Limpiar('1');" class="btn btn-lg btn-info btn-block btn-flat">
                                                <i class     ="fa fa-refresh "></i>  Limpiar</button>



                                        </div>
                                    </div>
                                </div>
                            </div>

             </form>



    <!-- modalSearch -->
<div id="modalSearch" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">
          <kbd class="DocSearch-Commands-Key">
                  <i class="fa fa-keyboard-o"> </i> 
            <svg width="20" height="15" aria-label="Escape key" role="img">
              <text x="2" y="12" fill="#FF5722" font-size="10" font-family="Arial, sans-serif">Esc</text>
            </svg>
          </kbd> 
      </button>
        <h4 class="modal-title">Búsqueda de Articulos</h4>
      </div>
      <div class="modal-body">


        <div class='input-group input-group-sm'>
          <div class="input-group-btn">
            <button type="button" tabindex="-1" class="btn btn-default btn-sm">
             <i class="fa fa-barcode" aria-hidden="true"></i> 
            </button>
          </div>
          <input type="text" id="campoSearch" class="form-control" placeholder="Buscar productos" autocomplete="off">
          <span class="input-group-btn">
                  <div   class="btn btn-default"  data-select2-open="single-prepend-text_2">
                  <i class="fa fa-keyboard-o"> F2</i> 
          </div>
              </span>
        </div>


        <div class="col-sm-2 col-md-2" style="padding: 20px;">
          <div  id="miThumbnail" class="thumbnail">
          <img class="img-thumbnail" src="" height="200">

          </div>
          <div class="caption text-center">
              <h4>Imagen</h4>
            </div>
        </div>
        <div class="col-sm-10 col-md-10">
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table" id="tablaProductos" style="height: 50px; overflow-y: auto;">
                  <thead>
                    <tr>
                      <th class="text-center"><i class="fa fa-hashtag"></i></th>
                      <th class="text-center"><i class="fa fa-check-circle-o"></i> Existencia</th>
                      <th class="text-center"><i class="fa fa-money"></i> Precio</th>
                      <th class="text-center"><i class="fa fa-money"></i> Mayorista</th>
                      <th class="text-center"><i class="fa fa-user"></i> Nombre</th>
                      <th class="text-center"><i class="glyphicon glyphicon-barcode"></i> Codigo-Barra</th>
                      <th class="text-center">Edit <i class="fa fa-cog"></i> Add</th>
                    </tr>
                    <tr id="loadingContainer" style="display: none;">
                      <td colspan="5">
                        <div id="loadingMessage">
                                      <div class="loading-spinner"></div>
                                      <p>Cargando...</p>
                                    </div>
                      </td>
                    </tr>
                  </thead>
                  <tbody class="todo-list ui-sortable">
                    <!-- Contenido de la tabla -->
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
        </div>





      </div>
    <div class="modal-footer">
    <footer class="modal-footer" style="display: flex; flex-direction: row; justify-content: flex-start; align-items: center;">
      <ul class="DocSearch-Commands" style="display: flex; flex-direction: row; justify-content: flex-start; align-items: center; list-style-type: none; padding: 0; margin: 0;">
        <li style="margin-right: 10px;">
          <kbd class="DocSearch-Commands-Key">
            <svg fill="#fff" width="15" height="15" viewBox="0 0 24.00 24.00" aria-label="Tecla TAB" role="img" >
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
              <g id="SVGRepo_iconCarrier">
                <path d="M22 4.25a.75.75 0 00-1.5 0v15a.75.75 0 001.5 0v-15zm-9.72 14.28a.75.75 0 11-1.06-1.06l4.97-4.97H1.75a.75.75 0 010-1.5h14.44l-4.97-4.97a.75.75 0 011.06-1.06l6.25 6.25a.75.75 0 010 1.06l-6.25 6.25z">
                </path>
              </g>
            </svg>
          </kbd>
          <span class="DocSearch-Label"> Tab Seleccion</span>
        </li>
        <li style="margin-right: 10px;">
          <kbd class="DocSearch-Commands-Key">
            <svg width="15" height="15" aria-label="Tecla Enter" role="img">
              <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2">
                <path d="M12 3.53088v3c0 1-1 2-2 2H4M7 11.53088l-3-3 3-3"></path>
              </g>
            </svg>
          </kbd>
          <span class="DocSearch-Label"> Enter Agregar</span>
        </li>

        <li style="margin-right: 10px;">
          <kbd class="DocSearch-Commands-Key">
            <svg width="15" height="15" aria-label="Flecha abajo" role="img">
              <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2">
                <path d="M7.5 3.5v8M10.5 8.5l-3 3-3-3"></path>
              </g>
            </svg>
          </kbd>
          <kbd class="DocSearch-Commands-Key">
            <svg width="15" height="15" aria-label="Flecha arriba" role="img">
              <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2">
                <path d="M7.5 11.5v-8M10.5 6.5l-3-3-3 3"></path>
              </g>
            </svg>
          </kbd>
          <span class="DocSearch-Label"> para Navegar</span>
        </li>
        <li>
          <kbd class="DocSearch-Commands-Key">
            <svg width="15" height="15" aria-label="Tecla Escape" role="img">
              <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2">
                <path d="M13.6167 8.936c-.1065.3583-.6883.962-1.4875.962-.7993 0-1.653-.9165-1.653-2.1258v-.5678c0-1.2548.7896-2.1016 1.653-2.1016.8634 0 1.3601.4778 1.4875 1.0724M9 6c-.1352-.4735-.7506-.9219-1.46-.8972-.7092.0246-1.344.57-1.344 1.2166s.4198.8812 1.3445.9805C8.465 7.3992 8.968 7.9337 9 8.5c.032.5663-.454 1.398-1.4595 1.398C6.6593 9.898 6 9 5.963 8.4851m-1.4748.5368c-.2635.5941-.8099.876-1.5443.876s-1.7073-.6248-1.7073-2.204v-.4603c0-1.0416.721-2.131 1.7073-2.131.9864 0 1.6425 1.031 1.5443 2.2492h-2.956"></path>
              </g>
            </svg>
          </kbd>
          <span class="DocSearch-Label"> para Cerrar</span>
        </li>
      </ul>
    </footer>
    </div>
    </div>
  </div>
</div>
<!-- FINICH modalSearch -->



  <div class="modal fade" id="modal-1">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header" id="mh">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only"></span>
          </button>
          <h4 class="modal-title">Nuevo Cliente</h4>
        </div>
        <div id="alertas"></div>
        <form action="#" method="POS" id="inserc" name="inserc" accept-charset="utf-8">
          <div class="modal-body">
            <div class="col-md-12">
              <div class="form-group">
                <label for="nombre">* Nombre </label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-user" aria-hidden="true"></i>
                  </div>
                  <input required title="Se necesita un nombre" maxlength="40" type="text" id="nombre" name="nombre" class="form-control" pattern="[A-Za-z ]{3,100}">
                </div>
                <span class=" alertascss text-danger"></span>
              </div>
              <div class="form-group">
                <label for="ruc">* RUC-CI </label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-user" aria-hidden="true"></i>
                  </div>
                  <input autofocus required oninput="formatRuc(this)" title="Se necesita un ruc o CI" maxlength="11" type="text" id="ruc" name="ruc" class="form-control" autocomplete="off">
                </div>
                <span class=" alertascss text-danger"></span>
              </div>
              <div class="form-group">
                <label for="Telefono">Telefono</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </div>
                  <input autofocus title="ingrese telefono" type="text" class="form-control" id="Telefono" name="Telefono">
                </div>
                <span class="alertascss text-danger"></span>
              </div>
              <div class="form-group">
                <label for="limite_credito">* Límite de Crédito</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                  </div>
                  <input autofocus  title="Se necesita un límite de crédito" type="text" id="limite_credito" name="limite_credito" class="form-control validat" autocomplete="off" maxlength="11">
                </div>
                <span class="alertascss text-danger"></span>
              </div>


            </div>
          </div>
          <div class="modal-footer">
            <div class="pull-right">
              <button type="submit" id="loading" name="loading" class="btn btn-sm btn-success" data-loading-text="Procesando..." autocomplete="off">
                <span class="glyphicon glyphicon-floppy-disk" id="btnSave">Guardar</span>
              </button>&nbsp;&nbsp;
              <button type="reset" class="btn btn-sm btn-info">
                <i class="fa fa-refresh"></i> Limpiar
              </button>&nbsp;
              <button type="button" class="btn btn-sm btn-danger" data-toggle="collapse" data-dismiss="modal">
                <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  