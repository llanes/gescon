<div class="content-wrapper">
        <!-- Content Header (Page header) -->
                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
    <form class="form-horizontal" method="post" name="Cerrar_caja" id="Cerrar_caja" role="form" target="myIframe"  action="<?= site_url('index.php/Reportes/generar_caja_principal'); ?>">
        <input type="hidden" name="Fecha" id="Fecha" value="<?php echo date("Y-m-d");?>">
        <section class="content">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="box box-primary">
                <div class="box-body">
                   <!-- tabla inicial -->
                <!-- /////////////////////////////////////  -->
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                        <div class="" style="height: 500px;  overflow : auto;  ">
                                    <table id="contenido_caja_ajax" class="table" >

                                      <thead>
                                        <tr class="danger">
                                           <th class ="text-danger" style="text-align:center">   Descripcion</th>
                                           <th class ="text-danger" style="text-align:center">   Fecha</th>
                                           <th class ="text-danger" style="text-align:center">   Ingresos</th>
                                           <th class ="text-danger" style="text-align:center"> Egresos</th>
                                           <th class="success"  style="text-align:center">Total</th>
                                         </tr>
                                      </thead>
                                    </table>
                        </div>
                </div>
                <!-- /////////////////////////////////////  -->
                <div class="col-md-3 col-md-offset-0" >

                       <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                        <li class="active">Caja</li></li>
                        <li class="active">Cierre</li>
                      </ol>
                        <button type="submit" id="_cerrar"   class="btn bg-orange btn-lg  btn-caja btn-flat " id="1"  name="1">
                            <i class="fa fa-folder-o" aria-hidden="true">&nbsp;Cerrar</i>
                        </button>
 
                <h3 class="">
                    <p class="text-center text-primary">Informacion</p>
                </h3>
                <div class="box box-info "></div>
                <strong class ="profile-ava btn-caja">
                 <i class      ="fa fa-user fa-fw"></i>Usuario&nbsp;:
                 <abbr class   ="username">
                   <span class   ="text-danger">
                     <?php 
                     echo ucfirst($this->session->userdata('Usuario'));
                     ?>
                   </span>
                 </abbr>
                </strong> 
                <strong class ="profile-ava btn-caja">
                 <i class      ="fa fa-cubes fa-fw"></i>Caja&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                 <abbr class   ="username">
                   <span class   ="text-danger">
                     <?php 
      
                    if (isset($id) && $id !== '') {
                        echo $id;
                    } else {
                        echo $_date->id ;
                    }

                     ?>
                   </span>
                 </abbr>
                 </strong>
                 <strong class ="profile-ava btn-caja">
                 <i class      ="fa fa-calendar fa-fw"></i>Fecha&nbsp;&nbsp;&nbsp;&nbsp;:
                 <abbr class   ="username">
                   <span class   ="text-danger">
                     <?php 
                    if (isset($fecha) && $fecha !== '') {
                        echo $fecha;
                    } else {
                        echo $_date->date_time;
                    }

                     ?>
                   </span>
                 </abbr>
               </strong>

               <h3>
                 <p id="inicial" class="text-center text-primary">Monto Final </p>
               </h3>
                <div class="box box-info"></div>
                    <h3 id="monto_final1"></h3>
                     <input type="hidden" name="Importe" id="Importe" class="hidden" value=""  pattern="" title="" >

                     <h3 class="occiones">
                     <div class="dt-buttons btn-group">
                        <a onclick="pdf_exporte('Caja',<?= $this->session->userdata('idcaja'); ?>);" class="btn btn-success  buttons-pdf buttons-html5 btn-sm" tabindex="0" aria-controls="datatable-buttons">
                          <i class="fa fa-file-pdf-o"></i>    PDF
                        </a>
                        <a href="Reporte_exel/caja/<?= $this->session->userdata('idcaja'); ?>" class="btn btn-success  buttons-print btn-sm" tabindex="0" aria-controls="datatable-buttons">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>  Excel
                        </a>
                      </div>

                      </h3>
                </div>
                <!-- ///////////////////////////////////////////////// -->
                <!-- final tabla -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div></div>
        </section>
    </form>
<div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->