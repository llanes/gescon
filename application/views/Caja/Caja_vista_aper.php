<div class="content-wrapper">
        <!-- Content Header (Page header) -->
<!--         <section class="content-header">

        </section>  -->
                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <form class="form-horizontal" method="post" name="Abrir_caja" id="Abrir_caja" role="form" action="#">
        <input type="hidden" name="Fecha" id="Fecha" value="<?php echo date("Y-m-d");?>">
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <!-- AREA CHART -->
              <div class="box box-primary">
                <div class="box-body">
                   <!-- tabla inicial -->
                <!-- /////////////////////////////////////  -->
                <div class="col-md-8 col-md-offset-0" >
                    <div class="box-header">
                      <h3 class="text-center text-primary">Monto Apertura  &nbsp; ₲S.</h3>
                    </div>
                    <div class="box-body  col-md-4 col-md-offset-4">
                             <input required type="text" name="inicio" id="inicio" class="form-control input-lg" value="<?php echo $monto_inicial  ?>" placeholder="Monto" pattern="[0-9]{0,22}" min="0" maxlength="22"  title="insertar" >
                              <span class ="MI text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                    </div><!-- /.box-body -->
                </div>
                <!-- /////////////////////////////////////  -->
                <div class="col-md-4 col-md-offset-0" >
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Caja</li></li>
            <li class="active">Apertura</li>
          </ol>

                        <button  type="submit" id="_abrir" class="btn bg-navy btn-lg  btn-caja btn-flat col-md-12"><i class="fa fa-folder-open" aria-hidden="true">&nbsp;Abrir&nbsp;</i></button>

                <div class="col-md-12">
                  <h3 class="">
                    <p class="text-center text-primary">Informacion del Usuario<!-- <hr class="soften" /> --></p>
                </h3>
                </div>
                <div class="box box-info col-md-12"></div>
                 <strong class="profile-ava">
                  <i class="fa fa-user fa-fw"></i>Usuario:&nbsp;&nbsp;
                  <abbr class="username">
                      <span class="text-danger">
                          <?php 
                          echo ucfirst($this->session->userdata('Usuario'));
                           ?>
                      </span>
                  </abbr>
              </strong>
               <h3>
                 <p id="inicial" class="text-center text-primary">Monto Inicio</p>
               </h3>
                <div class="box box-info"></div>
                <h2> 
                <span  id="Importe1" ><?php echo number_format((!empty($monto_inicial)) ? $monto_inicial : 0 ).' ₲' ?></span>
                        <input type="text" name="Importe" id="Importe" class="hidden" value="<?php echo $monto_inicial  ?>"  >
                </h2>


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