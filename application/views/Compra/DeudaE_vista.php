<!-- Content Wrapper. Contains page content -->
<style>
/* Estilos para el checkbox personalizado similar a Bootstrap 5 */
.bootstrap-5-switch {
    display: inline-block;
    position: relative;
    cursor: pointer;
    vertical-align: middle;
}

.bootstrap-5-switch input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.bootstrap-5-switch label {
    position: relative;
    display: inline-block;
    width: 40px; /* Ancho del switch */
    height: 20px; /* Altura del switch */
    background-color: #ccc; /* Color de fondo cuando está desactivado */
    border-radius: 20px; /* Forma del switch */
    cursor: pointer;
    transition: background-color 0.2s ease;
    vertical-align: middle;
}

.bootstrap-5-switch input:checked + label {
    background-color: #007bff; /* Color de fondo cuando está activado */
}

.bootstrap-5-switch label::before {
    content: "";
    position: absolute;
    width: 16px; /* Diámetro del círculo del switch */
    height: 16px; /* Diámetro del círculo del switch */
    background-color: #fff; /* Color de fondo del círculo cuando está desactivado */
    border-radius: 50%; /* Forma del círculo del switch */
    top: 50%;
    transform: translateY(-50%);
    transition: left 0.2s ease, background-color 0.2s ease;
}

.bootstrap-5-switch input:checked + label::before {
    left: calc(100% - 18px); /* Posición del círculo cuando está activado */
    background-color: #fff; /* Color de fondo del círculo cuando está activado */
}

.bootstrap-5-switch span {
    margin-left: 10px; /* Espacio entre el switch y el mensaje */
    vertical-align: middle;
}

/* Mueve el .containercheckbox hacia arriba */
.containercheckbox {
    margin-top: 22px; /* Ajusta el valor negativo según la cantidad de espacio que desees mover hacia arriba */
}
.highlight {
  background-color: grey; /* Cambia el fondo a amarillo cuando se aplica la clase "highlight" */
}


.col-col{
          padding-right: 5px;
          padding-left: 5px;
    }
    .panel-body{
      padding: 10px;
    }
    .col-col-3{
            padding-left: 0px;
    }

    .input-lg{
          padding: 5px 5px;
    }
    .form-group-lg .form-control{
      font-size: 14px;
    }
    .form-group-lg .select2-container--bootstrap .select2-selection--single {     
      font-size: 14px;
    }
    @media (min-width: 992px) {
    .modal-lg {
        width: 1200px;
    }
}

</style>
<div class="content-wrapper">
<input type="hidden" id="alertaCaja" name="alertaCaja" value="<?= ($this->session->userdata('idcaja') || $this->session->userdata('Permiso_idPermiso') == 1 ) ? 1 : 0 ;?>">


<div role="tabpanel">
  <!-- Nav tabs -->
  <ul class="nav nav-tabs nav-justified" role="tablist">
    <li role="presentation" class="active">
      <a id="limpiar" href="#Deudas" aria-controls="Deudas" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Deudas Empresa</a>
    </li>
    <li role="presentation">
      <a id="clicpagada" href="#pagadas" aria-controls="tab" role="tab" data-toggle="tab"> <i class="fa fa-money"></i> Deudas Pagadas</a>
    </li>
    <li></li>
        <li></li>
        <li></li>
        <li class="disabled" >
          <a >
            <i class="fa fa-dashboard"></i>
              Inicio
            <i class="fa fa-angle-right"></i>
              Compras
            <i class="fa fa-angle-right"></i>
              Deudas
          </a>
        </li>
  </ul>


<!-- modal open -->
<div class="modal fade" id="modal-id" data-keyboard="false" data-backdrop="static">
  <div class="modal-lg modal-dialog">
    <div class="modal-content">
    <section class="content-header">
      <h5 >
            <div class="col-sm-2 invoice-col col-md-offset-0">
                <strong id="numero"></strong>
            </div>
            <div class="col-sm-3 invoice-col col-md-offset-0">
                <strong id="recibo"></strong>
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
      <!-- Encabezado de la pestaña -->
      <div role="tabpanel">
        <div class="modal-header" id="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <ul class="nav nav-tabs nav-justified" role="tablist">
            <!-- Pestañas de pago -->
            <li id="e" class="text-center">
              <a id="tabefectivo" class="btn btn-app" href="#Efectivo" aria-controls="Efectivo" role="tab" data-toggle="tab">
                <i class="fa fa-money"></i> Pago en Efectivo
              </a>
              <p class="text-info">Monto Parcial:&nbsp;<span class="text-danger" id="ParcialE">0</span>&nbsp;₲s.</p>
            </li>
            <li id="c" class="text-center">
              <a class="btn btn-app" href="#Cheque" aria-controls="Cheque" role="tab" data-toggle="tab">
                <i class="fa fa-credit-card"></i> Pago con Cheque
              </a>
              <p class="text-info">Monto Parcial:&nbsp;<span class="text-danger" id="ParcialC">0</span>&nbsp;₲s.</p>
            </li>
            <li id="t" class="text-center">
              <a class="btn btn-app" href="#Tarjeta" aria-controls="Tarjeta" role="tab" data-toggle="tab">
                <i class="fa fa-credit-card"></i> Pago con Tarjeta
              </a>
              <p class="text-info">Monto Parcial:&nbsp;<span class="text-danger" id="ParcialT">0</span>&nbsp;₲s.</p>
            </li>
            <li id="s" class="text-center">
              <a class="btn btn-app" href="#fabor" aria-controls="fabor" role="tab" data-toggle="tab">
                <i class="fa fa-balance-scale"></i> Saldo a Favor
              </a>
              <p class="text-info">Monto Parcial:&nbsp;<span class="text-danger" id="ParcialF">0</span>&nbsp;₲s.</p>
            </li>
          </ul>
        </div>

        <!-- Código PHP -->
        <?php
          $moneda = monedase();
          $val = 0;
          $html = ''; // Inicializa la variable HTML vacía
          $montoDivisas = ''; 
          foreach ($moneda as $key => $value) {
            $val++;
            $view = '';
            if ($value->idMoneda != 1) {
              $view = 'mostrarMoneda';
              $montoDivisas .= '<tr>
                <th style="width:50%">'.$value->Moneda. ':</th>
                <td id="'.$value->idMoneda.'" data-signo="'.$value->Signo.'" data-cambio="'.$value->Compra.'"></td>
              </tr>';
            }
            $html .= '
              <div class="form-group-lg '.$view.'" style="display:'.$value->style.'">
                <div class="input-group input-lg">
                  <div class="input-group-addon">
                    <i class="fa fa-money" aria-hidden="true">'.$value->Signo." ".$value->Moneda.'</i>
                  </div>
                  <input title="Efectivo" type="text" id="EF'.$value->idMoneda.'" maxlength="15" max="99999999999999" data-monto="'.$value->Compra.'" name="EF'.$value->idMoneda.'" class="form-control validat blocqueac" placeholder="'.$value->Nombre.'" onkeyup="cambio('.$value->idMoneda.');">
                  <input type="hidden" name="Moneda'.$value->idMoneda.'" id="Moneda'.$value->idMoneda.'" value="'.$value->idMoneda.'">
                  <input type="hidden" name="MontoMoneda'.$value->idMoneda .'" id="MontoMoneda'.$value->idMoneda.'" value="">
                  <input type="hidden" name="MCambio'.$value->idMoneda .'" value="'.$value->Compra.'">
                  <input type="hidden" name="signo'.$value->idMoneda.'" value="'.$value->Moneda.'">
                  <input type="hidden" name="montoCambiado'.$value->idMoneda.'" id="montoCambiado'.$value->idMoneda.'"  class="" > 
                </div>
                <span class="eee text-danger"></span>
              </div>';
          }
        ?>

        <!-- Formulario principal -->
        <form action="pagosdeuda" id="pagosdeuda"  name="pagosdeuda" class='pagosdeuda' method="get" autocomplete="off" accept-charset="utf-8">
          <div class="tab-content">
            <!-- Pestaña Efectivo -->
            <div role="tabpanel" class="tab-pane active" id="Efectivo">
              <div class="col-md-8 col-md-offset-0 col-col">
                <div class="col-sm-5 invoice-col">
                  <!-- Checkbox personalizado similar a Bootstrap 5 -->
                  <div class="containercheckbox">
                    <div class="bootstrap-5-switch">
                      <input type="checkbox" id="customSwitch1">
                      <label for="customSwitch1"></label>
                      <span>Habilitar monedas extranjeras</span>
                    </div>
                  </div>
                  <div class="col-xs-12">
                    <p class="lead"></p>
                    <div class="table-responsive">
                      <table class="table" id="divisas">
                        <?= $montoDivisas; ?>
                        <tr><th></th><td></td></tr>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="col-md-7">
                  <h3 class="text-war text-center"> <i class="fa fa-money" aria-hidden="true"></i> Monedas </h3>
                  <input type="hidden" name="deudapagar" id="deudapagar" value="">
                  <?= $html; ?>
                  <input type="hidden" name="val" id="valtotalmoneda" value="<?php echo $val ?>">
                </div>
              </div>
            </div>

            <!-- Pestaña Cheque -->
            <div role="tabpanel" class="tab-pane fade" id="Cheque">
              <!-- Contenido de la pestaña Cheque -->
              <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-col">
                        <h3 class="text-war text-center"> <i class="fa fa-fw fa-houzz" aria-hidden="true"></i> Método por Cheque </h3>
                        <div class="col-md-6 col-md-offset-0 col-col">
                            <div class="form-group-lg">
                                <label for='numcheque'>* Numero Cheque </label>
                                <div class="input-group input-lg">
                                <div class="input-group-addon">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </div>
                                <input  title="Se necesita un numero" maxlength="15" type ="text" id="numcheque" name="numcheque" class="form-control " autofocus autocomplete="off" placeholder="" pattern="[0-9]{0,16}"   >
                                </div>
                                <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                            </div>
                            <div class="form-group-lg" >
                                <label for='fecha_pago'>* Fecha de Pago</label>
                                <div class="input-group input-lg">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </div>
                                <input disabled  title="Fecha de Pago"  type="date" id="fecha_pago" name="fecha_pago" class="form-control blocqueac" />
                                </div>
                                <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                            </div>
                            <div class="form-group-lg">
                                <label for='efectivo'>Monto </label>
                                <div class="input-group input-lg">
                                <div class="input-group-addon">
                                    <i class="fa fa-money" aria-hidden="true"></i>
                                </div>
                                <input disabled  title="Se necesita un monto" maxlength="15" type ="text" id="efectivotxt" name="efectivotxt" class="form-control  validat">
                                <input type="hidden"  id="efectivo" name="efectivo">
                                </div>
                                <span class ="eee text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                            </div>
                        </div>
                        <div class="col-md-6 col-md-offset-0 col-col">
                            <div class="form-group-lg">
                                <label for='cuenta_bancaria'>Afecta a Cuenta Bancaria?</label>
                                <div class="input-group input-lg ">
                                    <div class="input-group-addon">
                                        <input type="checkbox" name="checkboxbanca" id="checkboxbanca" value="">
                                    </div>
                                    <select  name="cuenta_bancaria" id="cuenta_bancaria" class="form-control  " title="Seleccione un Cheque">
                                        <option value="" selected></option>
                                        <?php 
                                        foreach(Banco() as $key => $value)
                                        {
                                        ?>
                                        <option value="<?php echo $value -> idGestor_Bancos ?>"><?php echo $value -> Nombre;?>  (<?php echo $value -> Numero;?>) </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                            </div>
                            <div class="form-group-lg">
                                <label for='cheque_tercero'>* Cheque Tercero</label>
                                    <div class="input-group input-lg ">
                                        <div class="input-group-addon">
                                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                        </div>
                                        <select   name="cheque_tercero" id="cheque_tercero"  Size="4" class="form-control select2-multiple multi" multiple="multiple" style="width: 100%" >
                                            <option value=""></option>
                                        </select>
                                        <input type="hidden" id="to_chek" value="">
                                    </div>
                                <span class ="NN text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                            </div>
                        </div>
                  </div>
            </div>

            <!-- Pestaña Tarjeta -->
            <div role="tabpanel" class="tab-pane fade" id="Tarjeta">
              <!-- Contenido de la pestaña Tarjeta -->
              <div class="col-md-8 col-md-offset-0 col-col">
                    <h3 class="text-war text-center"> 
                      Metodo por Tarjeta &nbsp;
                      <i class="fa fa-fw fa-cc-visa"></i>
                      <i class="fa fa-fw fa-cc-mastercard"></i>
                      <i class="fa fa-fw fa-cc-amex"></i>
                      <i class="fa fa-fw fa-cc-paypal"></i> 
                   </h3>
                    <div class="form-group-lg col-md-6 col-md-offset-0 col-col">
                      <label for='efectivoTarjeta'>Monto</label>
                      <div class="input-group input-lg">
                        <div class="input-group-addon">
                          <i class="fa fa-money" aria-hidden="true"></i>
                        </div>
                        <input type="hidden" id="efectivoTarjeta" name="efectivoTarjeta">
                        <input  title="Se necesita un monto" maxlength="15" type ="text" id="efectivoTarjetatext" name="efectivoTarjetatext" class="form-control  validat">
                      </div>
                      <span class ="eee text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                    </div>

                    <div class="form-group-lg col-md-6 col-md-offset-0 col-col">
                      <label for='Tarjeta'>* Tipo de Tarjeta </label>
                      <div class="input-group input-lg">
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
            </div>

            <!-- Pestaña Saldo a Favor -->
            <div role="tabpanel" class="tab-pane fade" id="fabor">
              <!-- Contenido de la pestaña Saldo a Favor -->
              <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <h3 class="text-war text-center"> <i class="fa fa-money" aria-hidden="true"></i> Método Saldo a Fabor </h3>
                      <div class="form-group-lg">
                        <label for='multifabor'>Monto </label>
                        <div class="input-group input-lg ">
                          <span class="input-group-addon">
                            <i class="fa fa-money" aria-hidden="true"></i>
                          </span>
                          <select  name="multi" id="multifabor"  Size="4" class="form-control select2-multiple multi blocqueac" multiple="multiple" style="width: 100%" >

                          </select>
                        </div>
                        <span class ="mult text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                      </div>
                      <!-- <input type="hidden" name="cobroen" id="cobroen" value="3"> -->
                      <input type="hidden" name="matriscuanta" id="matriscuanta" value="">
                      <input type="hidden" name="matris" id="matris" value="">
                  </div>
            </div>
          </div>

          <!-- Sección de montos y botones -->
          <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-col">
            <h3 class="text-war text-center">
              <i class="fa fa-money" aria-hidden="true"></i> Monto a Pagar &nbsp;<span class="text-danger" id="m_total"></span>&nbsp;₲s.
            </h3>
            <div class="form-group-lg">
              <div class="input-group input-lg">
                <div class="input-group-addon input-lg  bor"  >
                  Total:&nbsp;<span class="text-danger" id="Totalp"></span>&nbsp;₲s.
                </div>
              </div>
            </div>
            <div class="form-group-lg ">
              <div class="input-group input-lg">
                <div class="input-group-addon input-lg  bor"  >
                  Restante:&nbsp;<span class="text-danger" id="rerer"></span>&nbsp;₲s.
                </div>
              </div>
            </div>
            <div class="form-group-lg">
              <div class="input-group input-lg">
                <div class="input-group-addon input-lg  bor"  >
                  Vuelto:&nbsp;<span class="text-danger" id="vuelto"></span>&nbsp;₲s.
                </div>
              </div>
            </div>
            <div class="form-group-lg" >
              <div class="input-group input-lg">
                <div class="input-group-addon input-lg  bor"  >
                  Agregar a Cuenta &nbsp;
                  <input  type="checkbox" name="agregar_cuenta" id="agregar_cuenta" class="controlajustar" value="" style=" display: none;">&nbsp;<span class="text-danger" id="valor"></span>&nbsp;₲.
                  <span class ="aaa text-danger"></span>
                  <input type="hidden" name="si_no" id="si_no" value="">
                  <input type="hidden" name="ajustado" id="ajustado" value="">
                </div>
              </div>
            </div>
            <div class="form-group-lg">
              <button type="submit" id="loadingg" name="add_add" class="btn btn-lg btn-success btn-block btn-flat" data-loadingg-text="Procesando..." autocomplete="off">
                <i class="fas fa-save" id="bguarda"></i> Guardar
              </button> 
            </div>
          </div>

          <!-- Campos ocultos -->
          <input type="hidden" name="parcial1"  id="parcial1" value="">
          <input type="hidden" name="parcial2"  id="parcial2" value="">
          <input type="hidden" name="parcial3"  id="parcial3" value="">
          <input type="hidden" name="parcial4"  id="parcial4" value="">
          <input type="hidden" name="Acheque_tercero" id="Acheque_tercero" value="">
          <input type="hidden" name="Acheque" id="Acheque" value="">
          <input type="hidden" name="vueltototal" id="vueltototal" value="">
          <input type="hidden" name="cobroen" id="cobroen" value="1">
          <input type="hidden" name="Totalparclal" id="Totalparclal" value="">
          <input type="hidden" name="monto" id="monto" value="">
        <input type="hidden" name="id" id="id" value="">
        <input type="hidden" name="idProveedor" id="idProveedor" value="">
        <input type="hidden" name="idF" id="idF" value="">
        <input type="hidden" name="crEstado" id="crEstado" value="">
        <input type="hidden" name="cfEstado" id="cfEstado" value="">
        <input type="hidden" name="totalrous" id="totalrous" value="">
        <input type="hidden" name="cuotaN" id="cuotaN" value="">

          <div class="col-md-12 col-md-offset-0 ">
            <div class="alerter alert alert-error" style="display: none"></div>
          </div>
          <div class="modal-footer modal-footer2">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <h1>
                <i class="fa fa-fw fa-cc-visa"></i>
                <i class="fa fa-fw fa-cc-mastercard"></i>
                <i class="fa fa-fw fa-cc-amex"></i>
                <i class="fa fa-fw fa-cc-paypal"></i>
              </h1>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div id="piesss"></div>
</div>

<!-- modal off -->

                
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
                      <div class="box-tools pull-left">
            <div class="input-group input-group-sm hidden-xs" style="">
            <form id="facturaCuota" class="form-inline">
                <div class="form-group">
                  <select id="search-estatus" class="form-control" name="estatus" placeholder="Tipos de Cobros">
                  <option value="">Todos</option>
                    <option value="1">No Pagados</option>
                    <option value="2">Pagados</option>

                  </select>
                </div>
                <div class="form-group">
                  <input type="text" id="search-ruc"  name="ruc" class="form-control" placeholder="Buscar por RUC del cliente">
                </div>
                <div class="form-group">
                  <input type="text" id="search-factura" name="factura" class="form-control" placeholder="Buscar por factura">
                </div>
                <div class="form-group">
                  <input type="date" id="search-anho"  name="anho"class="form-control" placeholder="Buscar por año" min="1900" max="2099" step="1">
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
              </form>
            </div>
          </div>




                        <div class="box-tools pull-right">
                     <div class="dt-buttons btn-group">
                          <div class="hidden-phone">
                          <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('Deuda_e')">
                          <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                          <a class="btn btn-success btn-xs" href="Reporte_exel/Deuda_e" title="Exportar a EXEL">
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
                                    <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="Deuda_e">
                                      <thead>
                                      <tr>
                                      <th><i class="fa fa-check"></i> Pagados</th>
                    <th><i class="fa fa-clock-o"></i> Pendiente</th>
                    <th><i class="fa fa-truck"></i> Provvedor</th>
                    <th><i class="fa fa-truck"></i> N° Document</th>

                    <th><i class="fa fa-money"></i> M. Total</th>
                    <th><i class="fa fa-money"></i> Pago Parcial</th>
                    <th><i class="fa fa-money"></i> M. Pendiente</th>
                    <th><i class="fa fa-money"></i> M. Pagado</th>
                    <th><i class="fa fa-cogs"></i> Acciones</th>
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
    <div role="tabpanel" class="tab-pane" id="pagadas">
      <!-- Pagados -->
      <section class="content">
        <div class="row">
              <div class="col-md-12">
                <!-- AREA CHART -->
                  <div class="box box-primary">
                      <div class="box-header with-border">
                      <div class="box-tools pull-left">
                      <div class="input-group input-group-sm hidden-xs" style="">
                      <form id="listaCuotasPagadas" class="form-inline">
                          <div class="form-group">
                            <select id="search-estatus" class="form-control" name="estatus" placeholder="Tipos de Cobros">
                            <option value="">Todos</option>
                              <option value="1">Parciales</option>
                              <option value="2">Totales</option>

                            </select>
                          </div>
                          <div class="form-group">
                            <input type="text" id="search-ruc"  name="ruc" class="form-control" placeholder="Buscar por RUC del cliente">
                          </div>
                          <div class="form-group">
                            <input type="text" id="search-factura" name="factura" class="form-control" placeholder="Buscar por factura">
                          </div>
                          <div class="form-group">
                            <input type="date" id="search-anho"  name="anho"class="form-control" placeholder="Buscar por año" min="1900" max="2099" step="1">
                          </div>
                          <div class="form-group">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                          </div>
                        </form>
                      </div>
                    </div>



                      
                        <div class="box-tools pull-right">
                     <div class="dt-buttons btn-group">
                          <div class="hidden-phone">
                          <a class="btn btn-info btn-xs" href="javascript:void(0);" title="Exportar a PDF" onclick="pdf_exporte('pagadas')">
                          <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
                          <a class="btn btn-success btn-xs" href="Reporte_exel/pagadas"  title="Exportar a EXEL" >
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
                                    <table class="table table-striped table-bordered" cellspacing="30" width="100%"  id="Pagadas">
                                      <thead>
                                      <tr>
                                         <th></th>
                                         <th><i class ="fa fa-th-list"></i>  Cuota N°</th>
                                         <th><i class ="fa fa-th-list"></i>  Document</th>
                                         <th><i class ="fa fa-th-list"></i>  FechaPago</th>
                                         <th><i class ="fa fa-truck"></i> Provedor </th>
                                         <th><i class ="fa fa-money"></i> Importe Total</th>
                                         <th><i class ="fa fa-money"></i> M. Pagado</th>
                                         <th><i class ="fa fa-money"></i> Pendiente</th>
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
                                    <table class="table table-striped table-bordered"   id="list">
                                      <thead>
                                      <tr>
                                         <th><i class ="fa fa-th-list"></i>  Cuota N°</th>
                                         <th><i class ="fa fa-th-list"></i>  Comprobantes</th>
                                         <th><i class ="fa fa-truck"></i> Provedor </th>
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
    
