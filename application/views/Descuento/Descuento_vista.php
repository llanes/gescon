      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <small>Descuentos</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Productos</li></li>
            <li class="active">Administrar Descuentos</li>
          </ol>
        </section> 
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <!-- AREA CHART -->
              <div class="box box-primary">
               <div class="box-header with-border">
                  <div class="box-tools pull-right">
                                          <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('Descuento')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" href="Reporte_exel/Descuento" title="Exportar a EXEL" >
                                  <i class="fa fa-file-excel-o" aria-hidden="true"> EXCEL</i>
                                  </a></div>
                              </div>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><br>
                 <form action="Reporte_exel/baciento" method="post" id="form1" name="formulario" accept-charset="utf-8">
                                  <div class="col-md-4  form-group" >
                                    <label> Categor&iacute;a </label>
                                    <div class="input-group input-group-sm">
                                      <div class="input-group-addon">
                                       <i class="fa fa-th" aria-hidden="true"></i>
                                      </div>
                                          <select name="categoria" id="categoria"  class="form-control  " >
                                            <option value=""></option>
                                            <?php foreach ($cate as $key => $value) {?>
                                              <option value="<?php echo $value->idCategoria?>"><?php echo $value->Categoria?></option>
                                           <?php } ?>
                                          </select>
                                    </div>
                                      <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div>
                                 <div class="col-md-4 form-group">
                                     <label>Productos</label>
                                    <div class="input-group input-group-sm">
                                      <div class="input-group-addon">
                                     <i class="fa fa-th" aria-hidden="true"></i>
                                      </div>
                                          <select name="marca" id="marca"  class="form-control  " >
                                            <option value=""></option>
                                            <?php foreach ($productos as $key => $value) {?>
                                              <option value="<?php echo $value->idProducto?>"><?php echo $value->Nombre?></option>
                                           <?php } ?>
                                          </select>
                                    </div>
                                      <span class ="eee text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                  </div>
                                  <div class="col-md-3  form-group">
                                        <label>Descuento</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-btn">
                                                <button id="seartt" class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                                                  <i class="fa fa-th" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                          <select required name="Descuento" id="Descuento"  class="form-control  " >
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
                                            <span class="input-group-btn">
                                                 <button type="submit" id="loadingg" name="add_add" class="btn btn-sm btn-success" data-loadingg-text="Procesando..." autocomplete="off">
                                                    <i class="fa fa-floppy-o" aria-hidden="true">&nbsp; Aplicar </i>
                                                </button>

                                            </span>
                                          
                                          </div>
                                         <span class ="PROVE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                    </div>
                 </form>
                </div>  
                <div class="box-body">
                   <!-- tabla inicial -->
                  <div class="box">
                    <div class="box-body table-responsive no-padding">
                      <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="tabla_Des">
                        <thead>
                          <tr>
                                  <th ><i class  ="fa fa-slack" aria-hidden="true"></i></th>
                            <th ><i class="fa fa-bars"></i>  Categor&iacute;a</th>
                           <th ><i class="fa fa-bars"></i>  Marca</th>
                           <th ><i class="fa fa-bars"></i>  Descuento</th>
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
    
