<div class="content-wrapper">
        <!-- Content Header (Page header) -->
   <section class="content">
        <div class="collapse" id="exCollapsingNavbar">
          <div class="bg-inverse p-a">
            <form class="form-horizontal" method="post" name="Cerrar_caja" id="Cerrar_caja" role="form" target="myIframe"  action="<?= site_url('index.php/Reportes/generar_caja_principal'); ?>">
                <input type="hidden" name="Fecha" id="Fecha" value="<?php echo date("Y-m-d");?>">
                <input type="text" name="cerrar" class="hidden"  value="">
                <section class="content">
                  <div class="row">
                    <div class="col-md-12">
                      <!-- AREA CHART -->
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
                        <button type="submit" id="_cerrar"   class="btn bg-orange btn-lg  btn-caja btn-flat" id="1"  name="1">
                            <i class="fa fa-folder-o" aria-hidden="true">&nbsp;Cerrar</i>
                        </button>
 
                <h3>
                    <p class="text-center text-primary">Informacion</p>
                </h3>
                <div class="box box-info"></div>
                <strong class ="profile-ava btn-caja">
                 <i class      ="fa fa-user fa-fw"></i>Usuario:&nbsp;&nbsp;
                 <abbr class   ="username">
                   <span class   ="text-danger" id="user">

                   </span>
                 </abbr>
                </strong> 
                <strong class ="profile-ava btn-caja">
                 <i class      ="fa fa-user fa-fw"></i>Caja:&nbsp;&nbsp;
                 <abbr class   ="username">
                   <span class   ="text-danger" id="caja">

                   </span>
                 </abbr>
                 </strong>
                 <strong class ="profile-ava btn-caja">
                 <i class      ="fa fa-user fa-fw"></i>Fecha:&nbsp;&nbsp;
                 <abbr class   ="username">
                   <span class   ="text-danger" id="datafecha">

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
                        <a id="pdf" class="btn btn-success  buttons-pdf buttons-html5 btn-sm" tabindex="0" aria-controls="datatable-buttons">
                          <i class="fa fa-file-pdf-o"></i>    PDF
                        </a>
                       <a id="exel" class="btn btn-success  buttons-print btn-sm" tabindex="0" aria-controls="datatable-buttons">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>  Excel
                        </a>
                      </div><br><br>
                                    <span onclick="ceerar()" class="btn btn-caja btn-danger btn-flat">Cerrar Movimiento </span>
                              </h3>
                        </div>
                        <!-- ///////////////////////////////////////////////// -->
                        <!-- final tabla -->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                    </div></div>
                </section>
            </form>
          </div>
        </div>
          <!-- Small boxes (Stat box) -->
          <div class="row">
          <?php  foreach ($Open as $value) {
            echo '
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                              <!-- small box -->
                              <div class="small-box bg-red">
                                <div class="inner ">
                                  <h3><i class="fa fa-folder-open-o" aria-hidden="true"></i> Caja Nº '.$value->idCaja.'</h3>
                                  <h4><i class=" fa fa-user-o" aria-hidden="true"></i> Cajero '.$value->Usuario.'</h4>
                                  <h4><i class="fa fa-clock-o" aria-hidden="true"></i> Inicio  '.$value->Fecha_apertura.'   '.$value->Hora_apertura.'</h4>
                                  <!-- <h4><i class="fa fa-money" aria-hidden="true"></i> Monto Inicio  ₲S.</h4>-->
                                 </div>
                                <div class="icon">
                                  <i class="ion ion-bag"></i>
                                </div>
                                <a href="#" data-userdata="'.$value->Usuario.'" data-fecha="'.$value->Fecha_apertura. ':' .$value->Hora_apertura.'" onclick="view('.$value->idCaja.')" id="'.$value->idCaja.'" class="small-box-footer">Ver Movimiento Caja <i class="fa fa-arrow-circle-right"></i></a>
                              </div>
                            </div>
            ';
           } ?>
          <?php if (empty($Open)) {?>
            <h1 class="alert alert-info">
              <a class="close" data-dismiss="alert">&times;</a>
              <strong>!</strong> Ningunas Caja Activas.
            </h1>
           <?php }?>
          </div><!-- /.row -->
        </section>

<div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->
