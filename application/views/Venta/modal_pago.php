<!-- ////////////////////////////////////////MODAL///////////////////////////////////////////////////////////// -->
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
          $montoDivisas .= '<tr class="cambioMoneda">
            <th style="width:50%">'.$value->Moneda. ':</th>
            <td id="'.$value->idMoneda.'" data-signo="'.$value->Signo.'" data-cambio="'.$value->Compra.'" class="'.$value->Moneda.'"></td>
          </tr>';
        }
        $html .= '
          <div class="form-group-lg '.$view.'" style="display:'.$value->style.'">
            <div class="input-group input-lg">
              <div class="input-group-addon">
                <i class="" aria-hidden="true">  '.$value->Moneda.'</i>
              </div>
              <input title="Efectivo" type="text" id="EF'.$value->idMoneda.'" maxlength="15" max="99999999999999" data-monto="'.$value->Compra.'" name="EF'.$value->idMoneda.'" class="form-control validat blocqueac" placeholder="'.$value->Nombre.'" onkeyup="cambio('.$value->idMoneda.');">
              <input type="hidden" class="hiddennone" name="Moneda'.$value->idMoneda.'" id="Moneda'.$value->idMoneda.'" value="'.$value->idMoneda.'">
              <input type="hidden" class="hiddennone" name="MontoMoneda'.$value->idMoneda .'" id="MontoMoneda'.$value->idMoneda.'" value="">
              <input type="hidden" class="hiddennone" name="MCambio'.$value->idMoneda .'" value="'.$value->Compra.'">
              <input type="hidden" class="hiddennone" name="signo'.$value->idMoneda.'" value="'.$value->Moneda.'">
              <input type="hidden"  name="montoCambiado'.$value->idMoneda.'" id="montoCambiado'.$value->idMoneda.'"  class="" > 
            </div>
            <span class="eee text-danger"></span>
          </div>';
      }
    ?>
 <!-- modal-id -->
<div class="modal fade" id="modal-id" data-keyboard="false" data-backdrop="static">
  <div class="modal-lg modal-dialog">
    <div class="modal-content">
      <!-- Encabezado de la pestaña -->
      <div role="tabpanel">
        <div class="modal-header" id="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
          <!-- <button type="button" class="close" data-dismiss="modal" inert>&times;</button> -->
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>

          <ul class="nav nav-tabs nav-justified" role="tablist">
            <!-- Pestañas de pago -->
            <li id="e" class="text-center">
              <a id="tabEfectivo" data-inputName="EF1" class="btn btn-app btn-primary" href="#Efectivo" aria-controls="Efectivo" role="tab" data-toggle="tab">
                <i class="fa fa-money"></i> Cobro en Efectivo  
              </a>
              <p class="text-info">Monto Parcial:&nbsp;<span class="text-danger" id="ParcialE">0</span>&nbsp;₲s.</p>

            <div class="keyboard-icon-container">
                <i class="fa fa-keyboard-o"></i>
                <div class="keyboard-label">F1</div>
            </div>
            
            </li>
            <li id="c" class="text-center">
              <a id="tabCheque" data-inputName="numcheque" class="btn btn-app btn-primary" href="#Cheque" aria-controls="Cheque" role="tab" data-toggle="tab">
                <i class="fa fa-credit-card"></i> Cobro con Cheque 
              </a>
              <p class="text-info">Monto Parcial:&nbsp;<span class="text-danger" id="ParcialC">0</span>&nbsp;₲s.</p>
              <div class="keyboard-icon-container">
                <i class="fa fa-keyboard-o"></i>
                <div class="keyboard-label">F2</div>
            </div>
            </li>
            <li id="t" class="text-center">
              <a id="tabTarjeta" data-inputName="efectivoTarjetatext" class="btn btn-app btn-primary" href="#Tarjeta" aria-controls="Tarjeta" role="tab" data-toggle="tab">
                <i class="fa fa-credit-card"></i> Cobro con Tarjeta 
              </a>
              <p class="text-info">Monto Parcial:&nbsp;<span class="text-danger" id="ParcialT">0</span>&nbsp;₲s.</p>
              <div class="keyboard-icon-container">
                <i class="fa fa-keyboard-o"></i>
                <div class="keyboard-label">F3</div>
            </div>
            </li>
            <li id="s" class="text-center">
              <a id="tabfabor" data-inputName="multifabor" class="btn btn-app btn-primary" href="#fabor" aria-controls="fabor" role="tab" data-toggle="tab">
                <i class="fa fa-balance-scale"></i> Crédito en cuenta 
              </a>
              <p class="text-info">Monto Parcial:&nbsp;<span class="text-danger" id="ParcialF">0</span>&nbsp;₲s.</p>
              <div class="keyboard-icon-container">
                <i class="fa fa-keyboard-o"></i>
                <div class="keyboard-label">F4</div>
            </div>
            
            </li>
          </ul>
        </div>
        <!-- Formulario principal -->
        <form action="Venta_submit" id="Venta_submit"  name="Venta_submit" method="get" autocomplete="off" accept-charset="utf-8">
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
                    <div class="keyboard-icon-container">
                        <i class="fa fa-keyboard-o"></i>
                        <div class="keyboard-label">F8</div>
                    </div>
                  </div>
                  <div class="col-xs-12">
                    <p class="lead"></p>
                    <div class="table-responsive">
                      <table class="table table-striped" id="divisas">
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
                  <input type="hidden" class="hiddennone" name="val" id="valtotalmoneda" value="<?php echo $val ?>">
                </div>
              </div>
            </div>

            <!-- Pestaña Cheque -->
            <div role="tabpanel" class="tab-pane fade" id="Cheque">
              <!-- Contenido de la pestaña Cheque -->
              <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-col">
                        <h3 class="text-war text-center"> <i class="fa fa-fw fa-houzz" aria-hidden="true"></i> Método Cheque </h3>
                        <div class="col-md-6 col-md-offset-0 col-col">
                        <div class="form-group-lg">
                            <label for="numcheque">* Número de Cheque</label>
                            <div class="input-group input-lg">
                                <div class="input-group-addon">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </div>
                                <input  title="Se necesita un numero" maxlength="15" type ="text" id="numcheque" name="numcheque" class="form-control " autofocus autocomplete="off" placeholder="" pattern="[0-9]{0,16}"   >
                            </div>
                            <span class="NN text-danger"></span> <!-- INDICADORES DE ERROR A TRAVÉS DE AJAX -->
                        </div>
                        <div class="form-group-lg">
                            <label for="montocheque">* Monto del Cheque</label>
                            <div class="input-group input-lg">
                                <div class="input-group-addon">
                                    <i class="fa fa-money" aria-hidden="true"></i>
                                </div>
                                <input disabled  title="Se necesita un monto" maxlength="15" type ="text" id="efectivotxt" name="efectivotxt" class="form-control  validat chequeControl">
                                <input type="hidden"  id="efectivo" name="efectivo">
                            </div>
                            <span class="NN text-danger"></span> <!-- INDICADORES DE ERROR A TRAVÉS DE AJAX -->
                        </div>
                        <div class="form-group-lg">
                            <label for="banco">* Banco</label>
                            <div class="input-group input-lg">
                                <div class="input-group-addon">
                                    <i class="fa fa-bank" aria-hidden="true"></i>
                                </div>
                                <select disabled title="Banco del cheque" id="banco" name="banco" class="form-control chequeControl">
                                  <option value="">Selecciona un Banco</option>
                                  <?php 
                                        foreach(Banco() as $key => $value)
                                        {
                                        ?>
                                        <option value="<?php echo $value -> idGestor_Bancos ?>"><?php echo $value -> Nombre;?>  (<?php echo $value -> Numero;?>) </option>
                                        <?php
                                        }
                                  ?>
                                  <!-- Agrega más opciones según tus necesidades -->
                              </select>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-6 col-md-offset-0 col-col">
                        <div class="form-group-lg">
        <label for="fechacheque">* Fecha del Cheque</label>
        <div class="input-group input-lg">
            <div class="input-group-addon">
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </div>
            <input disabled  title="Fecha de Cobro"  type="date" id="fecha_pago" name="fecha_pago" class="form-control chequeControl" />
        </div>
        <span class="NN text-danger"></span> <!-- INDICADORES DE ERROR A TRAVÉS DE AJAX -->
    </div>
    <div class="form-group-lg">
        <label for="firma">* Firma del Titular de la Cuenta</label>
        <div class="input-group input-lg">
            <div class="input-group-addon">
            <i class="fa fa-pencil-square"></i>
            </div>
            <input disabled title="Firma del titular de la cuenta" type="text" id="firma" name="firma" class="form-control chequeControl">
        </div>
        <span class="NN text-danger"></span> <!-- INDICADORES DE ERROR A TRAVÉS DE AJAX -->
    </div>
    <div class="form-group-lg">
        <label for="cuenta">* Número de Cuenta Asociada</label>
        <div class="input-group input-lg">
            <div class="input-group-addon">
                <i class="fa fa-list-ol" aria-hidden="true"></i>
            </div>
            <input disabled title="Número de cuenta asociada" type="text" id="cuenta" name="cuenta" class="form-control chequeControl">
        </div>
        <span class="NN text-danger"></span> <!-- INDICADORES DE ERROR A TRAVÉS DE AJAX -->
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
            <h2 class="text-war text-center">
              <i class="fa fa-money" aria-hidden="true"></i> Monto a Cobrar&nbsp;<span class="text-danger" id="m_total"></span>&nbsp;₲s.
            </h2>
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
                Crédito en cuenta &nbsp;
                  <input  type="checkbox" name="agregar_cuenta" id="agregar_cuenta" class="controlajustar" value="" style=" display: none;">&nbsp;<span class="text-danger" id="valor"></span>&nbsp;₲.
                  <span class ="aaa text-danger"></span>
                  <input type="hidden" name="si_no" id="si_no" value="">
                  <input type="hidden" name="ajustado" id="ajustado" value="">
                </div>
              </div>
            </div>
            <div class="form-group-lg">
              <button type="submit" id="loadingg" name="add_add" class="btn btn-lg btn-success btn-block btn-flat" data-loadingg-text="Procesando..." autocomplete="off">
                <i class="fas fa-save" id="bguarda"></i> Cobrar
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
<!-- FINICH modal-id -->