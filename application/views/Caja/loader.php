                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
    <form class="form-horizontal" method="post" name="Cerrar_caja" id="Cerrar_caja" role="form" target="myIframe"  action="<?= site_url('index.php/Reportes/generar_caja_principal'); ?>">
        <input type="hidden" name="Fecha" id="Fecha" value="<?php echo date("Y-m-d");?>">
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <!-- AREA CHART -->
              <div class="box box-primary">
                  <div class="box-header datatime">
                      <button type="button" class="btn btn-block btn-info btn-flat">
                          <h3 class="box-title">
                                  <!-- <?php echo $_date; ?> -->
                           </h3> 
                      </button>
                  </div>
                <div class="box-body">
                   <!-- tabla inicial -->
                <!-- /////////////////////////////////////  -->
                <div class="col-md-8 col-md-offset-0" >
                        <div class="box box-success " style="height: 500px;  overflow : auto;  ">
                                    <table id="contenido_caja_ajax" class="table table-striped" >

                                      <thead>
                                        <tr class="success">
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
                <div class="col-md-4 col-md-offset-0" >
                      <div class="btn bg-navy btn-flat  margin " id="caja_abierta">
                             <button id="caja_abierta" type="button" class="btn bg-navy  btn-lg disabled" >Abierta</button>
                     </div>
                <div class="btn bg-orange btn-flat  margin " id="1">
                        <button type="submit" id="_cerrar"  class="btn bg-orange btn-lg"  name="1">
                            <i class="fa fa-folder-o" aria-hidden="true">&nbsp;Cerrar</i>
                        </button>
                </div><br>  
                <h3>
                    <p class="text-center text-primary">Informacion del Usuario<!-- <hr class="soften" /> --></p>
                </h3>
                <div class="box box-info"></div>
                 <strong class="profile-ava">
                  <i class="fa fa-user fa-fw"></i>Nombre:&nbsp;&nbsp;
                  <abbr class="username">
                      <span class="text-danger">
                          <?php 
                          echo ucfirst($this->session->userdata('Usuario'));
                           ?>
                      </span>
                  </abbr>
              </strong>
               <h3>
                 <p id="inicial" class=" text-info">Monto Final &nbsp; ₲S.</p>
               </h3>
                <div class="box box-info"></div>
                    <h3 id="monto_final1"></h3>
                     <input type="hidden" name="Importe" id="Importe" class="hidden" value=""  pattern="" title="" >

                     <h3 class="occiones">
                     <div class="dt-buttons btn-group">
                        <a class="btn btn-success  buttons-pdf buttons-html5 btn-sm" tabindex="0" aria-controls="datatable-buttons">
                          <i class="fa fa-file-pdf-o"></i>    PDF
                        </a>
                        <a class="btn btn-success  buttons-print btn-sm" tabindex="0" aria-controls="datatable-buttons">
                         <i class="fa fa-print" aria-hidden="true"></i>    Inprimir
                        </a>
                      </div><br><br>
                            <span onclick="ceerar()" class="btn btn-block btn-danger btn-flat">Cerrar </span>
                      </h3>
                </div>
                <!-- ///////////////////////////////////////////////// -->
                <!-- final tabla -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div></div>
        </section>
    </form>
