<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
<div role="tabpanel">
       <section class="content-header">
       <h1>
            <small>Deudas Clientes</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Cobros</li></li>
            <li class="active">Deuda Clientes</li>
          </ol>
       </section> 
                         <div id="alertasadd" >

                  </div>
            <div class="collapse box" id="collapseExample">
                <section class="content-header">
                <h5 align="">
                       <div class="col-sm-2 invoice-col col-md-offset-0">
                          <strong id="recibo"></strong>
                       </div>
                       <div class="col-sm-2 invoice-col col-md-offset-0">
                          <strong id="numero"></strong>
                       </div>
                       <div class="col-sm-3 invoice-col col-md-offset-0">
                           <strong id="prove"></strong> 
                       </div>
                       <div class="col-sm-3 invoice-col col-md-offset-0">
                          <strong id="pendiente"></strong>
                       </div>

                        <small class="">Fecha Pago : <?php echo  date("d-m-Y"); ?></small>
                </h5>

                </section>
                  <div class="well">

                    <form action="#" id="pagosdeuda" class="pagosdeuda" name="pagosdeuda">

  <div role="tabpanel">
  <ul class="nav nav-tabs nav-justified" role="tablist">
    <li  id="e"  class="text-center ">
      <a id="limpp" href="#Efectivo" aria-controls="Efectivo" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Cobro Efectivo</a>
    </li>

    <li id="c"  class="text-center ">
      <a id="" href="#Cheque" aria-controls="Cheque" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Cobro En Cheque</a>
    </li>

    <li id="t" class="text-center">
      <a id="" href="#Tarjeta" aria-controls="Tarjeta" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Cobro En Tarjeta</a>
    </li>
    <li id="s" class="text-center">
      <a id="" href="#fabor" aria-controls="fabor" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Saldo Fabor</a>
    </li>



  </ul>



  <div class="tab-content">
    <div role="tabpanel" class="tab-pane fade " id="Efectivo"><br>
      <div class="col-md-4 col-md-offset-2 ">
  <h4>Monto a Pagar &nbsp;<span id="spanmontopagar"></span>&nbsp;₲S.</h4>
            <input type="hidden" name="deudapagar" id="deudapagar" value="">
  <?php 
        $val = 0;
        foreach($Moneda as $key => $value)
        {
          $val++;
        ?>
          <div class="form-group">


            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-money" aria-hidden="true">  Pago en <?php echo $value->Signo.'  '.$value->Moneda ?></i>
              </div>
                 <input
                   title="Efectivo" 
                   type ="text"
                   id="EF<?php echo $value->idMoneda ?>" 
                   maxlength="15" 
                   max="99999999999999" 
                   data-monto="<?php echo $value->Compra ?>"
                   name="EF<?php echo $value->idMoneda ?>" 
                   class="form-control validat blocqueac" 
                   placeholder="<?php echo $value->Nombre?>" 
                   onkeyup="cambio('<?php echo $value->idMoneda ?>');"
                      >
                      <input type="hidden" name="signo<?php echo $value->idMoneda ?>"  value="<?php echo $value->Moneda ?>">
                      <input type="hidden" name="Moneda<?php echo $value->idMoneda ?>" id="Moneda<?php echo $value->idMoneda ?>" value="">
                      <input type="hidden" name="cam<?php echo $value->idMoneda ?>" id="cam<?php echo $value->idMoneda ?>" value="">
            </div>
                     <span class ="eee  text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
          </div>
        <?php
        }
  ?>
<input type="hidden" name="val" id="val" value="<?php echo $val ?>">

</div>
<div class="col-md-4 col-md-offset-0 ">
  <h4>Cambios en &nbsp;₲S.</h4>
  <?php 
    foreach ($Moneda as $value) 
      { ?>
     <div class="form-group col-md-3">
        <div class="input-group">

           <?php echo $value->Compra ?> &nbsp;₲S.
 

        </div>

      </div>
     <div class="form-group col-md-9">
        <div class="input-group">
          <div class="input-group-addon">
          =
          </div>
                       <input disabled
              title="Efectivo"
              type ="text" 
              id="cm<?php echo $value->idMoneda ?>" 
              maxlength="11" 
              max="99999999999999999999" 
              name="cm<?php echo $value->idMoneda ?>" 
              class="form-control " 
              placeholder="<?php echo $value->Nombre?>" >
               <input type="hidden" name="ex<?php echo $value->idMoneda ?>" id="ex<?php echo $value->idMoneda ?>"  class="hidden" >
        </div>
                 <span class ="eee  text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
      </div>
    <?php
    }
  ?>

</div>
    </div>


    <div role="tabpanel" class="tab-pane fade" id="Cheque"><br>
      <!-- cheque -->
      <div class="col-md-12  col-md-offset-0 ">
            <div class="col-md-4  col-md-offset-4 form-group">
              <label>* Numero Cheque </label>
              <div class="input-group">
                <div class="input-group-addon">
                 <i class="fa fa-bars" aria-hidden="true"></i>
                </div>
                   <input  title="Se necesita un numero" maxlength="15" type ="text" id="numcheque" name="numcheque" class="form-control input-sm" autofocus autocomplete="off" placeholder="" pattern="[0-9]{0,16}"   >
              </div>
                <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
            </div>
             <div class="col-md-4  col-md-offset-4 form-group" >
              <label>* Fecha de Pago</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-calendar" aria-hidden="true"></i>
                </div>
                   <input disabled  title="Fecha de Pago"  type ="text" id="fecha_pago" name="fecha_pago" class="form-control input-sm blocqueac" value="<?php echo date("d-m-Y");?>"/>
              </div>
                <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
            </div>
           <div class="col-md-4  col-md-offset-4  form-group">
               <label>Monto a Pagar &nbsp;<span id="spanmontopagarchque"></span>&nbsp;₲S.</label>
              <div class="input-group">
                <div class="input-group-addon">
                 <i class="fa fa-money" aria-hidden="true"></i>
                </div>
                   <input disabled  title="Se necesita un monto" maxlength="15" type ="text" id="efectivo" name="efectivo" class="form-control  blocqueac">
              </div>
                <span class ="eee text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
            </div>
      </div>
    <!-- FIN cheque -->
    </div>


    <div role="tabpanel" class="tab-pane fade" id="Tarjeta">
      <!-- Tarjeta -->
      <div class="col-md-12  col-md-offset-0 "><br>
           <div class="col-md-4  col-md-offset-4 form-group">
               <label>Monto a Pagar &nbsp;<span id="spanmontopagartar"></span>&nbsp;₲S.</label>
              <div class="input-group">
                <div class="input-group-addon">
                 <i class="fa fa-money" aria-hidden="true"></i>
                </div>
                   <input  title="Se necesita un monto" maxlength="15" type ="text" id="efectivoTarjeta" name="efectivoTarjeta" class="form-control  blocqueac">
              </div>
                <span class ="eee text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
            </div>

            <div class="col-md-4  col-md-offset-4 form-group">
              <label>* Tipo de Tarjeta </label>
              <div class="input-group">
                <div class="input-group-addon">
                 <i class="fa fa-bars" aria-hidden="true"></i>
                </div>
                      <select name="Tarjeta" id="Tarjeta" class="form-control blocqueac" >
                         <option value="1">Tarjetas de Crédito</option>
                         <option value="2">Tarjetas de Débito</option>
                      </select>
              </div>
                <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
            </div>
      </div>
      <!-- FIN Tarjeta -->
    </div>
    <div role="tabpanel" class="tab-pane fade" id="fabor">
      <!-- fabor -->
      <!-- FIN fabor -->
    </div>

<div id="piesss">
</div>
  </div>
                              <input type ="hidden" name="monto" id="monto" value="">
                              <input type ="hidden" name="id" id="id" value="">
                              <input type ="hidden" name="idCliente" id="idCliente" value="">
                              <input type ="hidden" name="idF" id="idF" value="">
                              <input type ="hidden" name="crEstado" id="crEstado" value="">
                              <input type ="hidden" name="cfEstado" id="cfEstado" value="">
                              <input type ="hidden" name="totalrous" id="totalrous" value="">
  <!-- Nav tabs -->

                      <div class="modal-footer modal-footer2">
                          <div class="pull-right">
                             <button  type="submit" id="loadingg" name="add" class="btn btn-sm btn-success" data-loadingg-text="Procesando..." autocomplete="off">
                                    <span class  ="glyphicon glyphicon-floppy-disk" id="btnSave"  >Cobrar</span> 
                             </button>&nbsp;&nbsp;
                             <button type ="button" class="btn btn-sm btn-danger" data-toggle="collapse"  data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" >
                             <span class  ="glyphicon glyphicon-floppy-remove"></span> Cancelar</button>
                          </div>
                      </div>
            </div>

                    </form>
                    <!-- fin de formulario -->
                  </div>
                </div>
  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="Deudas">
      <!-- DEUDAS -->
      <section class="content">
        <div class="row">
              <div class="col-md-12">
                <!-- AREA CHART -->
                  <div class="box box-primary">
                      <div class="box-header with-border">
                        <div class="box-tools pull-right">
                             <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('deudacliente')">
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" href="Reporte_exel/deudacliente" title="Exportar a EXEL">
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
                                    <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="Deuda_c">
                                      <thead>
                                        <tr>
                                         <th ><i class ="fa fa-list-ol" aria-hidden="true"></i> Pendiente</th>
                                          <th ><i class="fa fa-truck"></i>  Cliente</th>
                                         <th ><i class="fa fa-money"></i>  M. Total</th>
                                         <th ><i class="fa fa-money"></i>  Pago Parcial </th>
                                         <th ><i class="fa fa-money"></i>  M. Pendiente</th>
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
      <!-- FIN DEUDAS -->
    </div>
    <div role="tabpanel" class="tab-pane" id="listados">
      <!-- Pagados -->
      <section class="content">
        <div class="row">
              <div class="col-md-12">
                <!-- AREA CHART -->
                  <div class="box box-primary">
                      <div class="box-header with-border">
                        <div class="box-tools pull-right">
                      <div class="dt-buttons btn-group">
                                  <div class="hidden-phone">
                                  <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" id="addtrpdf" >
                                  <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                                  <a class="btn btn-success btn-xs" id='addtrexel' title="Exportar a EXEL">
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
                                    <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="list">
                                      <thead>
                                      <tr>
                                         <th><i class ="fa fa-th-list"></i>  Cuota N°</th>
                                         <th><i class ="fa fa-th-list"></i>  Comprovantes</th>
                                         <th><i class ="fa fa-truck"></i> Cliente </th>
                                         <th><i class ="fa fa-money"></i> Importe a Pagar</th>
                                         <th><i class ="fa fa-money"></i> M. Pagado</th>
                                         <th><i class ="fa fa-calendar"></i>   Feca Vencimiento</th>
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
      <!-- FIN Pagados -->
    </div>
  </div>
</div>
  <div class='control-sidebar-bg'></div>
</div><!-- ./wrapper -->
    
