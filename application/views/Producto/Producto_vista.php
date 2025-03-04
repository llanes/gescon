<style type="text/css" media="screen">
        .row {
            margin-left: 20px !important;
        }
      a{color:#fff;}
      ul.nav nav-tabs li.active > a{ color: #9e9e9e; }
      .tooltip{font-size: 11px;}
      .tooltip.right { padding:  7px;}
      .tooltip-inner { padding: 5px 12px; background-color: crimson; }
      .tooltip.bottom .tooltip-arrow {
          top: 0;
          margin-left: -5px;
          border-width: 0 5px 5px;
          border-bottom-color: crimson;
      }
      .tooltip.bottom{ margin-top: 0px }
  </style>
  <div role="tabpanel">
    <ul class="nav nav-tabs " role="tablist">
    <li class="" role="">
        <a data-toggle="tab" href="#" aria-controls="" role="tab" data-toggle="tab" style="background-color:#222d32">
        <i class="fas fa-list-ul"></i> </a>
      </li>


      <li class="active" role="presentation">
        <a data-toggle="tab" href="#home" aria-controls="home" role="tab" data-toggle="tab" style="background-color:#222d32">
        <i class="fas fa-list-ul"></i> Productos</a>
      </li>
      <li role="presentation" id="" >
        <a href="#tab" id="someTab"  aria-controls="tab" role="tab" onclick="_add()"  data-toggle="tab" style="background-color:#222d32">
          <i class="fa fa-user-plus" aria-hidden="true" id="tituloboton">&nbsp;Agregar Nuevo Producto</i>
        </a>
         
      </button>
      </li>
      <li></li>
      <li class="pull-right">
          <ol class="breadcrumb ">
            <li><i class="fa fa-dashboard"></i> Inicio</li>
            <!-- <li class="active">Seguridad</li></li> -->
            <li>Administrar Productos</li>
          </ol>
      </li>
    </ul>
    
    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane in active fade" id="home">
<!-- ///////////////////////////// -->
  <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
  <section class="">
    <div class="row">
      <div class="col-md-12">
        <!-- AREA CHART -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <div class="box-tools pull-right">
              <div class="dt-buttons btn-group">
                <div class="hidden-phone">
                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('Producto')">
                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                    <a href="Reporte_exel/Producto" class="btn btn-success btn-xs" tabindex="0" aria-controls="datatable-buttons">
                      <i class="fa fa-file-excel-o" aria-hidden="true"></i>  Excel
                    </a>
                  </div>
                </div>
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
            <style type="text/css" media="screen">
                td.details-control {
                    background-image: url('<?php echo base_url('/content/details_open.png');?>');
                    background-repeat: no-repeat;
                    background-position: center center;
                    cursor: pointer;
                    transition: background-image 0.3s ease-in-out;
                }
                tr.shown td.details-control {
                    background-image: url('<?php echo base_url('/content/details_close.png');?>');
                }
                </style>
            <div class="box">
              <div class="box-body table-responsive product-status-wrap">
                
                <table class="table table-striped" cellspacing="30" width="100%"  id="tabla_Producto">
                  <thead>
                    <tr>
                      <th></th>
                      <th ><i class ="fa fa-file-image-o"></i> Logo</th>
                      <th ><i class ="fa fa-barcode"></i>  Codigo</th>
                      <th ><i class ="fa fa-shopping-cart"></i>  Producto</th>
                      <th ><i class ="fa fa-money"></i>  Precio</th>
                      <th ><i class ="fa fa-check-square-o"></i> Cantidad </th>
                      <th ><i class ="fa fa-percent"></i> IVA</th>
                      <th ><i class ="fa fa-truck"></i> Estado</th>
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
<!-- //////////////////// -->
      </div>
      <div role="tabpanel" class="tab-pane fade" id="tab">
  <!-- /////////         -->
  <div class="col-md-1-12">
        <div class="text-center">
            <span class="text-danger" style="display: inline-block; vertical-align: middle;">Son necesarias las etiquetas de los campos marcados con *</span>
        </div>

        <form   method="post" name="formulario" id="from_Producto" action="<?php echo base_url();?>index.php/Producto/producto/adding" enctype="multipart/form-data">
          <input type="hidden" value="" name="idProducto" id="idProducto"> 
          <div class="row">
            <div class="col-md-12">
              <div class="box panel-default">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                      <div class="form-group">
                        <label>* Código </label>
                        <div class="input-group input-group-sm"  id="alert-code" data-toggle="tooltip"  data-placement="bottom"  >
                          <div class="input-group-addon">
                            <i class="fa fa-codepen" aria-hidden="true"></i>
                          </div>
                          <input required  maxlength="30" type ="text" id="Codigo" name="Codigo" class="form-control"  autofocus autocomplete="off" value=""  />
                        </div>
                        <!-- <span class ="CO text-danger"></span>   -->
                        <!-- <div class="CO invalid-feedback"></div> -->
                      </div>
                      <div class="form-group">
                        <label>* Nombre</label>
                        <div class="input-group input-group-sm"  id="alert-code1" data-toggle="tooltip"  data-placement="bottom">
                          <div class="input-group-addon">
                            <i class="fa fa-info"></i>
                          </div>
                          <input  required title="Se necesita un Nombre" maxlength="35" type ="text" id="Nombre" name="Nombre" class="form-control" autofocus autocomplete="off" value="">
                        </div>
                        <span class ="NO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                      </div>
                      <div class="form-group">
                        <label>* Marca</label>
                        <div class="input-group input-group-sm"  id="alert-code2" data-toggle="tooltip"  data-placement="bottom">
                          <div class="input-group-addon">
                            <i class="fa fa-magnet" aria-hidden="true"></i>
                          </div>
                          <select  required class ="form-control" name="Marca" id="Marca"  title="Seleccionar Marca" >
                           <option selected disabled value="" >Seleccione Marca....</option> 
                            <?php 
                            foreach($Marca as $fila1)
                            {
                              ?>
                              <option value="<?php echo $fila1 -> idMarca ?>"><?php echo $fila1 -> Marca;?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                        <span class ="MA text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                      </div>
                      <div class="form-group">
                        <label>* Categoría</label>
                        <div class="input-group input-group-sm"  id="alert-code3" data-toggle="tooltip"  data-placement="bottom">
                          <div class="input-group-addon">
                            <i class="fa fa-magnet" aria-hidden="true"></i>
                          </div>
                          <select  class ="form-control" name="Categoria" id="Categoria"  required title="Seleccionar Categoria" >
                            <option selected disabled value="" >Seleccione Categoria...</option> 
                            <?php 
                            foreach($Categoria as $fila2)
                            {
                              ?>
                              <option value="<?php echo $fila2 -> idCategoria ?>"><?php echo $fila2 -> Categoria;?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                        <span class ="CT text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                      </div>
                      <div class="form-group">
                        <label>Cantidad Almacen</label>
                        <div class="input-group input-group-sm"  id="alert-code4" data-toggle="tooltip"  data-placement="bottom">
                          <div class="input-group-addon">
                            <i class="fa fa-stack-overflow" aria-hidden="true"></i>
                          </div>
                          <input title="ingrese Cantidad" type="text" class="form-control" id="Cantidad_A" name="Cantidad_A"  maxlength="11"   />
                        </div>
                        <span class ="CA text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                      </div>
                      <div class="form-group">
                        <label>Stock Minimo</label>
                        <div class="input-group input-group-sm"  id="alert-code5" data-toggle="tooltip"  data-placement="bottom">
                          <div class="input-group-addon">
                            <i class="fa fa-stack-overflow" aria-hidden="true"></i>
                          </div>
                          <input title="ingrese descuento" type="text" class="form-control" id="Stock_Min" name="Stock_Min" maxlength="11"   />
                        </div>
                        <span class ="SM text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                      </div>
                    </div>
                
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                        <div class="form-group">
                          <label>* Precio Costo</label>
                          <div class="input-group input-group-sm"  id="alert-code6" data-toggle="tooltip"  data-placement="bottom">
                            <div class="input-group-addon">
                              <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                            <input required title="Ingrese Precio Costo"  id="Precio_Costo" name="Precio_Costo" type="text" class="form-control" autofocus maxlength="15" oninput="formatCurrency(this)" />

                          </div>
                          <span class ="PC text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                        </div>

                        <div class="form-group">
                          <label>* Precio Venta</label>
                          <div class="input-group input-group-sm"  id="alert-code7" data-toggle="tooltip"  data-placement="bottom">
                            <div class="input-group-addon">
                              <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                            <input  required type ="text" id="Precio_Venta" name="Precio_Venta" class="form-control " title="Ingrese Precio Venta" autofocus  maxlength="15" oninput="formatCurrency(this)" />
                          </div>
                          <span class ="PV text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                        </div>

                        <div class="form-group">
                          <label>* IVA</label>
                          <div class="input-group input-group-sm"  id="alert-code8" data-toggle="tooltip"  data-placement="bottom">
                            <div class="input-group-addon">
                              <i class="fa fa-percent"></i>
                            </div>
                            <select  class ="form-control" name="iva" id="iva" required  >
                             <option selected disabled value="">Seleccione Iva...</option>
                              <?php 
                                foreach (LoadIva() as $key => $value) {
                                  ?>
                                  <option value="<?php echo $value->Num_Iva ?>"><?php echo $value->Nom_Iva;?></option>
                                  <?php
                                }
                               ?>
                            </select>
                          </div>
                          <span class ="IV text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                        </div>

                        <div class="form-group">
                          <label>Cantidad Deposito</label>
                          <div class="input-group input-group-sm"  id="alert-code9" data-toggle="tooltip"  data-placement="bottom">
                            <div class="input-group-addon">
                              <i class="fa fa-stack-overflow" aria-hidden="true"></i>
                            </div>
                            <input title="ingrese descuento" type="text" class="form-control" id="Cantidad_D" name="Cantidad_D" maxlength="11"   />
                          </div>
                          <span class ="CD text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                        </div>

                        <div class="form-group">
                          <label>Descuento</label>
                          <div class="input-group input-group-sm"  id="alert-code10" data-toggle="tooltip"  data-placement="bottom">
                            <div class="input-group-addon">
                              <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                            <select  name="Descuento" id="Descuento"  class="form-control  " >
                              <option value=""></option>
                              <option value="3">3 %</option>
                              <option value="5">5 %</option>
                              <option value="10">10 %</option>
                              <option value="15">15 %</option>
                              <option value="20">20 %</option>
                              <option value="25">25 %</option>
                              <option value="30">30 %</option>
                              <option value="35">35 %</option>
                              <option value="40">40 %</option>
                              <option value="45">45 %</option>
                              <option value="50">50 %</option>
                              <option value="60">60 %</option>
                              <option value="70">70 %</option>
                              <option value="80">80 %</option>
                              <option value="90">90 %</option>
                              <option value="100">100 %</option>



                            </select>
                          </div>
                          <span class ="DS text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                        </div>

                        <div class="form-group">
                          <label>Venta o Produccion</label>
                          <div class="input-group input-group-sm"  id="alert-code11" data-toggle="tooltip"  data-placement="bottom">
                            <div class="input-group-addon">
                              <i class="fa fa-bolt" aria-hidden="true"></i>
                            </div>
                            <select required name="Porcentaje" id="Porcentaje" class="form-control" required="required">
                              <option selected disabled value="">Seleccione Opcion...</option>
                              <option value="1">Venta</option>
                              <option value="2">Produccion</option>
                            </select>
                          </div>
                          <span class ="POV text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                        </div>
                     </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                      <div class="form-group">
                        <label>Proveedor</label>
                        <div class="input-group input-group-sm"  id="alert-code12" data-toggle="tooltip"  data-placement="bottom">
                          <span class="input-group-addon">  
                            <i class="fa fa-truck" aria-hidden="true"></i>
                          </span>
                          <select  name="multi" id="multi"  Size="4" class="form-control select2-multiple multi" multiple="multiple" style="width: 100%" >

                          </select>
                        </div>
                        <span class ="PRO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                      <div class="form-group">
                        <label>Unidad</label>
                        <div class="input-group input-group-sm"  id="alert-code14" data-toggle="tooltip"  data-placement="bottom">
                          <div class="input-group-addon">
                            <i class="fa fa-outdent" aria-hidden="true"></i>
                          </div>
                          <input title="ingrese descuento" type="number" class="form-control" id="Unidad" name="Unidad" onkeydown="limitDigits(event, this, 11)"  />
                        </div>
                        <span class ="ME text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                      <div class="form-group">
                        <label>Medida</label>
                        <div class="input-group input-group-sm">
                          <div class="input-group-addon">
                            <i class="fa fa-outdent" aria-hidden="true"></i>
                          </div>
                          <select name="Medida" id="Medida" class="form-control" >
                            <option value="kg">kilo</option>
                            <option value="mg">Gramo</option>
                            <option value="kl">Litro</option>
                            <option value="ml">Mililitro</option>
                            <option value="un">Unidad</option>
                            <option value="dc">Docena</option>
                            <option value="cn">Centena</option>

                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                      <label class="col-md-12">Imagen Tamaño maximo 2MB!</label><br>

                      <div id="editfile" class="fileinput fileinput-new col-md-12" data-provides="fileinput"  data-toggle="tooltip"  data-html="true">
                        <div id="agregar" class="fileinput-preview thumbnail" data-trigger="fileinput">

                        </div>
                        <div>
                          <span id="hide" class="btn btn-default btn-file">
                            <span  class="fileinput-new">Agregar Imagen</span>
                            <span  class="fileinput-exists">Cambiar</span>
                            <input  type="file" id="file" name="file" value="" class="remove">
                          </span>
                          <a href="#"  class="btn btn-default fileinput-exists remove" data-dismiss="fileinput">Remover</a>
                        </div>
                      </div>
                      <span class ="IMGIMG text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                    <button type="submit" id="clic" class="btn btn-success btn-block">
                        Guardar
                    </button>
                    <button type="reset" class="btn btn-info btn-block">
                        Limpiar
                    </button>
                    <button type="button" class="btn btn-danger btn-block" onclick="cancelar()">
                        Cancelar
                    </button>   
                    </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
    </div>
  <!-- ////////// -->
      </div>
    </div>
  </div>


</div><!-- ./wrapper -->

