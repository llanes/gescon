      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Gescom
            <small>Control </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Tablero</li>
          </ol>
        </section>

                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
   <section class="content">
  <?php if ($this->db->count_all_results('Empresa') != 0): 
    if (is_array($data_view) || is_object($data_view)) :?>
    
              <div class="row">
          <?php 
          foreach($data_view as $fila2)
          {

              switch ($fila2 ->ID) {
                    case '12':?>
                        <div class="col-lg-2  col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-aqua">
                            <div class="inner">
                              <h3><?php echo $Ordenc ?><sup style="font-size: 20px"></sup></h3>
                              <p>Ordenes de Compra</p>
                            </div>
                            <div class="icon">
                              <i class="fa fa-first-order" aria-hidden="true"></i>
                            </div>
                            <a href="<?= site_url('O_Comprar')?>" class="small-box-footer">Ordenes  <i class="fa fa-arrow-circle-right"></i></a>
                          </div>
                        </div><!-- ./col -->
                    <?php  break;  
              }
          }
          ?>

          <?php 
          foreach($data_view as $fila2)
          {

              switch ($fila2 ->ID) {
                    case '14':?>
                      <div class="col-lg-2 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                          <div class="inner">
                            <h3><?php echo $Compra ?><sup style="font-size: 20px"></sup></h3>
                            <p>Compras</p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                          </div>
                          <a href="<?= site_url('Comprar')?>" class="small-box-footer">Compras <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                      </div><!-- ./col -->
                    <?php  break;  
              }
          }
          ?>
          <?php 
          foreach($data_view as $fila2)
          {

              switch ($fila2 ->ID) {
                    case '88':?>
                  <div class="col-lg-2  col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                      <div class="inner">
                        <h3><?php echo $Cliente-1; ?></h3>
                        <p>Clientes</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-users"></i>
                      </div>
                      <a href="<?= site_url('Cliente')?>" class="small-box-footer"> Clientes <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div><!-- ./col -->
                    <?php  break;  
              }
          }
          ?>

          <?php 
        foreach($data_view as $fila2)
        {

            switch ($fila2 ->ID) {
                  case '89':?>
                  <div class="col-lg-2  col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                      <div class="inner">
                        <h3><?php echo $Prove ?></h3>
                        <p>Proveedores</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-truck"></i>
                      </div>
                      <a href="<?= site_url('Proveedor')?>" class="small-box-footer">Proveedor <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div><!-- ./col -->
                  <?php  break;  
            }
        }
        ?>
   

          <?php 
          foreach($data_view as $fila2)
          {

              switch ($fila2 ->ID) {
                    case '18':?>
                   <div class="col-lg-2  col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                          <div class="inner">
                            <h3><?php echo $Venta ?><sup style="font-size: 20px"></sup></h3>
                            <p>Venta</p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-shopping-cart"></i>
                          </div>
                          <a href="<?= site_url('Vender')?>" class="small-box-footer"> Ventas <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                      </div><!-- ./col -->
                    <?php  break;  
              }
          }
          ?>

          <?php 
          foreach($data_view as $fila2)
          {

              switch ($fila2 ->ID) {
                    case '11':?>
                   <div class="col-lg-2  col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h3><?php echo $Ordenv ?><sup style="font-size: 20px"></sup></h3>
                        <p>Orden de Venta</p>
                      </div>
                      <div class="icon">
                        <i class="fa fa-first-order" aria-hidden="true"></i>
                      </div>
                      <a href="<?= site_url('O_Venta')?>" class="small-box-footer"> Ordenes <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div><!-- ./col -->
                    <?php  break;  
              }
          }
          ?>
  

          </div><!-- /.row -->

    <?php endif ?>      
  <?php endif ?>

        </section>

<div class="modal fade bs-example-modal-sm" id="id_empresa">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">Complete los datos para acceder al Sistema!!</h3>
      </div>
        <!-- contenedor de mensaje de confirmacion -->
       <div class="alert alert-info" id="empresar_aler"  >
        <strong class="title" >Registrado Correctamente</strong> 
      </div>
      <!-- fin de contenedor -->
                    <form action="#" id="from_empresa" class="from_empresa">
                        <input type="hidden" value="" name="idEmpresa"/> 
                           <div class="col-md-6">
                                <div class="form-group">
                                  <label>* Nombre Empresa:</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-user" aria-hidden="true"></i>
                                    </div>
                                       <input required title="Se necesita un nombre" maxlength="40" type ="text" id="Nombre" name="Nombre" class="form-control" autofocus autocomplete="off"  placeholder="" pattern="[A-Za-z ]{3,100}"   >
                                  </div><!-- /.input group -->
                                    <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->

                                <div class="form-group">
                                      <label>* RUC</label>
                                      <div class="input-group">
                                        <div class="input-group-addon">
                                          <i class="fa fa-inr" aria-hidden="true"></i>
                                        </div>
                                            <input required   type ="text" id="ruc" name="ruc" class="form-control " data-inputmask='"mask": "[9][9][9][9][9][9][9][9][9][9][9][9][9][9]-9"' data-mask  autofocus  >
                                        </div><!-- /.input group -->
                                      <span class ="RU text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->                          

                          <div class="form-group">
                                  <label>Timbrado</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-ravelry" aria-hidden="true"></i>
                                    </div>
                                        <input  type ="text" id="Timbrado" name="Timbrado" class="form-control " onfocus="autofocus"  data-inputmask='"mask": "[9][9][9][9][9][9][9][9][9][9][9][9][9][9]"' data-mask>
                                    </div><!-- /.input group -->
                                  <span class ="TI text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->
                             <div class="form-group">
                                  <label>* Telefono</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-phone"></i>
                                    </div>
                                     <input required title="ingrese telefono" type="text" class="form-control" id="Telefono" name="Telefono" data-inputmask='"mask": "(0999) 999-999"' data-mask/>
                                    </div><!-- /.input group -->
                                  <span class ="TE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->
                        </div>

                          <div class="col-md-6">
                                <div class="form-group">
                                  <label>* Correo</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                     <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </div>
                                        <input required   type ="Email" id="Email" name="Email" class="form-control " maxlength="30" placeholder="ejemplo@msn.com" title="ejemplo@correo.com" onfocus="autofocus" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" autofocus  >
                                    </div><!-- /.input group -->
                                  <span class ="EM text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->

                                <div class="form-group">
                                  <label>Series</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-bars" aria-hidden="true"></i>
                                    </div>
                                        <input  type ="text" id="Series" name="Series" class="form-control " onfocus="autofocus"  data-inputmask='"mask": "[9][9][9][9][9][9][9][9][9][9][9][9][9][9]"' data-mask   >
                                    </div><!-- /.input group -->
                                  <span class ="SE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->

                                <div class="form-group">
                                  <label>* Direccion</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    </div>
                                      <input required  type ="text" id="Direccion" name="Direccion" class="form-control " placeholder="" size='45' title="ingrese Direccion" pattern="[A-Za-z ]{5,50}" maxlength="30"  autofocus  >
                                  </div><!-- /.input group -->
                                 <span class ="DI text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->

                               <div class="form-group">
                                  <label>* Comprobante</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                      <i class="fa fa-xing" aria-hidden="true"></i>
                                    </div>
                                        <input required type ="text" id="Comprovante" name="Comprovante" class="form-control money" onfocus="autofocus"   data-inputmask='"mask": "[9][9][9][9][9][9][9][9][9][9][9][9][9][9][9]"' data-mask   >

                                    </div><!-- /.input group -->
                                  <span class ="LMC text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->
                            </div>
                            <div class="col-md-12">
                                 <div class="form-group">
                                 <label>Descripcion</label>
                                  <div class="input-group">
                                    <div class="input-group-addon">
                                     <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                    </div>
                                           <textarea    type="textarea" id="Descripcion" name="Descripcion" class="form-control" placeholder="" size='0' title="Descripcion"pattern="[A-Za-z ]{4,50}" maxlength="100" min="2" max="100"  autofocus   ></textarea>
                                  </div><!-- /.input group -->
                                  <span class ="DE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                                </div><!-- /.form group -->
                            </div>
                    <table class="table">
                      <thead>
                        <tr>
                          <th>
                           <div class="pull-right">
                                    <button type ="submit"  class="btn btn-sm btn-success">
                                    <span class  ="glyphicon glyphicon-floppy-disk" id="btnSave"  >Guardar</span> </button>&nbsp;&nbsp;
                                    <button type ="reset"  class="btn btn-sm btn-info">
                                    <i class     ="fa fa-refresh "></i> Limpiar</button>&nbsp;
                                    <button type ="button" class="btn btn-sm btn-danger"  class="close" data-dismiss="modal" aria-hidden="true" >
                                    <span class  ="glyphicon glyphicon-floppy-remove"></span> Cancelar</button>     
                            </div>
                          </th>
                        </tr>
                      </thead>
                    </table>
                    </form>
    </div>
  </div>
</div>

<div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->