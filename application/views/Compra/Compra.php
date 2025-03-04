<div class="content-wrapper">
  <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
  <style type="text/css" media="screen">
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
      font-size: 16px;
    }
    .select2-result-repository { 
      padding-top: 4px;
      padding-bottom: 3px; 

    }
    #loadingMessage {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 4px solid rgba(255, 255, 255, 0.2);
  border-top-color: #1DB954;
  animation: spin 1s linear infinite;
  margin-bottom: 10px;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.price {
  font-weight: bold;
  color: #1DB954;
  font-size: 12px;
}

.row2 {
  display: flex;
}

.align-items-center {
  align-items: center;
}

.justify-content-center {
  justify-content: center;
}


    .form-group {
        margin-bottom: 4px;

    }
    .danger{
      background-color: #ffa5002b;
    }
    .table > thead > tr > th,
    .table > tbody > tr > th,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > tbody > tr > td,
    .table > tfoot > tr > td {
      padding: 3px;
      line-height: 1.42857143;
      vertical-align: top;
      border-top: 1px solid #ddd;
    }

    @media (min-width: 992px) {
      .modal-lg {
        width: 1100px;
      }
    }

#miThumbnail.thumbnail {
  height: auto;

}
.is-invalid {
    border-color: #dc3545; /* Color de Bootstrap para errores */
}

  </style>
  <section class="">
    <div class="row">
      <div class="col-md-12">
       <form method="POST" action="#" accept-charset="UTF-8" name="comprar" id="comprar">
          <input name="idOrden" id="idOrden" type="hidden" value="">
          <div class="row">
            <div class="col-md-9 col-col">
              <div class="box panel-default">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-sm-5 col-md-5">
<div class="form-group form-group-sm">
  <div class="input-group input-group-sm">
    <span class="input-group-btn">
      <button id="" name="seart_t" class="btn btn-default" type="button" data-toggle="tooltip" data-placement="top" title="Nuevo">
        <i class="fa fa-user-plus" aria-hidden="true"></i>
      </button>
    </span>
    <select class="form-control form-control-sm" name="proveedor" id="proveedor" title="Selecciona proveedor" class="proveedor" placeholder="Selecciona" required autofocus>
    </select>
  </div>
  <span class="NN text-red" id="id_del_div"></span>
</div>
           
                      <div class="form-group">
                        <div class="input-group input-group-sm">
                          <span class="input-group-btn">
                            <button id="seat2" name="seat2" class="btn btn-default" type="button" data-select2-open="single-prepend-text_2">
                            <i class="fa fa-barcode" aria-hidden="true"></i> 
                            </button>
                        </span>

                        <input type="text" id="campoBusqueda" class="form-control" placeholder="Buscar productos">

                        <span class="input-group-btn">
                            <button   class="btn btn-default search-product" type="button" data-select2-open="single-prepend-text_2">
                            <i class="fa fa-search"></i>
                            </button>
                        </span>
                          <input type="hidden" name="name" id="name" value="">
                          <input type="hidden" name="precio" id="precio" value="precio">
                          <input type="hidden" name="iva" id="iva" value="iva">

                        </div>
                        <span class ="PR text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                      </div>
                    </div>
                    <div class="col-sm-4 col-md-4">


                      <div class="form-group">
                        <div class="input-group input-group-sm">
                          <span class="input-group-btn">
                            <button id="seartt" name="seartt" class="btn btn-default" type="button" data-select2-open="single-prepend-text_3">
                              Orden
                            </button>
                          </span>
                          <select id="single-prepend-text_3" class="form-control select2-allow-clear orden select" tabindex="-1" aria-hidden="true" name="orden">
                            <option></option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                    <div class='input-group input-group-sm'>
                      <div class="input-group-btn">
                      <span type="" class="btn btn-default date-set">Tipo~</span>
                      </div>
                      <select name="tipoComprovante" id="tipoComprovante" class="form-control select" required="required">

                        <option value="0">Ticket</option>
                        <option value="1">Factura</option>
                        <option value="2">Nota de Credito</option>
                        <option value="3">Nota de Debito</option>
                      </select>

                    </div>
                    <span class ="TIPO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>



                    </div>
                    <div class="col-sm-3 col-md-3">
                      <div class="form-group">
                        <div class='input-group input-group-sm'>
                          <div class="input-group-btn">
                            <span type="" class="btn btn-default date-set">
                            Comp</span>
                          </div>
                          <input type="text" name="comprobante" maxlength="15" id="comprobante" class="form-control" value="" required="required" title="Comprobante" placeholder="Nº Comprobante" autofocus="" data-toggle="tooltip" data-placement="right" title="">
                        </div>
                        <span class ="COMP text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                      </div>

                      <div class="form-group">
                        <div class="input-group input-group-sm">
                          <div class="input-group-btn">
                            <span type="" class="btn btn-default date-set">Monto</span>
                          </div>
                          <input type="text"  name="montofinal" id="montofinal" class="form-control validate"  value="" required="required"  title="Final Monto" autofocus>

                        </div>
                      </div>
                      
                    </div>


                  </div>
                  <!-- detalle -->
                  <div class="col-md-12 col-xs" id="detalle">

                    
                  </div>

                </div>
              </div>
            </div>
            <div class="col-md-3 col-col">
              <div class="panel panel-default cls-panel">

                <div class="panel-body">


                  <div class="form-group">
                  <span class="label label-default">Fecha Comprobante</span>

                    <div class='input-group input-group-sm' id='datetimepicker7'>
                      <div class="input-group-btn">
                        <button type="button" class="btn btn-default date-set"><i class="fa fa-calendar" aria-hidden="true"></i></button>
                      </div>
                      <input required type='date' class="form-control " id="fecha" name="fecha" value=""/>

                    </div>
                    <span class ="FECHA text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>
                  <div class="form-group">
                  <span class="label label-default">* N° Timbrado</span>
                    <div class='input-group input-group-sm' id='datetimepicker7'>
                      <div class="input-group-btn">
                        <button type="button" class="btn btn-default date-set"><i class="fa fa-random" aria-hidden="true"></i></button>
                      </div>
                      <input required   type ="text" id="timbrado" name="timbrado" class="form-control selec_"  autofocus data-mask="00000000" min="99999999"  >
            
                    </div>
                    <span class ="TIM text-red"   ></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>

                  <div class="form-group  ">
                  <span class="label label-default">  Virtual</span>
                  <span class="label label-default">* Vencimiento</span>
                  <div class="input-group input-group-sm">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default date-set">
                        <input type="checkbox" name="checkbox_vence" id="checkbox_vence"  class="limpiar">
                        <input type="hidden" name="virtual" id="virtual">
                        </button>
                      </div>
                    <input  type ="date" id="vence" name="vence" class="form-control selec_"  autofocus class="limpiar" required>
            
                  </div>
                  <span class ="VEN text-red"   ></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                </div> 


                  <div class="form-group">
                  <span class="label label-default">Depositar/Carga</span>

                    <div class="input-group input-group-sm">
                      <div class="input-group-btn">
                        <button type="button" class="btn btn-default date-set"><i class="fa fa-random" aria-hidden="true"></i></button>
                      </div>
                      <select name="inicial" id="inicial" class="form-control select" required>
                        <option value="1">Al stock</option>
                        <option value="2">Al Deposito</option>
                      </select>
                    </div>
                    <span class ="INIT text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>

                  <div class="form-group">
                  <span class="label label-default">Forma de Pagos</span>

                    <div class='input-group input-group-sm'>
                      <div class="input-group-btn">
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-random" aria-hidden="true"></i></button>

                      </div>
                      <select name="Estado" id="Estado" class="form-control select" required="required"  >
                        <option value="0">Contado</option>
                        <option value="2">Credito</option>
                      </select>

                    </div>
                    <span class ="TIPO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>

                  <div class="form-group" style="display: none" id="contenCuotas">
                  <span class="label label-default">Cantidad Cuotas</span>

                    <div class="input-group input-group-sm">
                      <div class="input-group-btn">
                        <button type="button" class="btn btn-default date-set"><i class="fa fa-random" aria-hidden="true"></i></button>
                      </div>
                      <select name="cuotas" id="cuotas" class="form-control select" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                      </select>
                    </div>
                    <span class ="CUO text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>
                  <div class="form-group">
                  <span class="label label-default">Fletes</span>

                    <div class='input-group input-group-sm' id='datetimepicker7'>
                      <div class="input-group-btn">
                        <button type="button" class="btn btn-default date-set"><i class="fa fa-random" aria-hidden="true"></i></button>
                      </div>
                      <input maxlength="15" type='text' class="form-control sumar" id="fletes" name="fletes" placeholder="Fletes / Acarreos" maxlength="15" max=""  />
                    </div>
                    <span class ="FLE text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>




                <div class="form-group ">
                  <label class='label label-default' for='observac'>observaciones</label>
                    <div class='input-group input-group-sm' id='datetimepicker7'>
                      <div class="input-group-btn">
                      <button type="button" class="btn btn-default date-set"><i class="fa fa-comment"></i></button>
                      </div>
                      <textarea class="form-control input-sm" maxlength="50" rows="1" placeholder="observaciones" name="observac" type="text" id="observac"></textarea>
            
                    </div>
                    <span class ="OBSER text-danger"   ></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
                  </div>



              </div>


                  <h2 class="bg-navy"> ₲ <span id="Importe1"> </span> </h2>
                  <button   class="btn btn-lg btn-success btn-block btn-flat" id="add01">
                   <i class="fas fa-save" id="bguarda"></i> Guardar
                    </button>
                  <button type ="reset" onclick="Limpiar('1');" class="btn btn-lg btn-info btn-block btn-flat">
                    <i class     ="fa fa-refresh "></i>  Limpiar</button>

      <!-- cierre de carrito -->

                </div>
              </div>
            </div>
          </div>
        </form>
<!-- ////////////////////////////////////////MODAL///////////////////////////////////////////////////////////// -->
<div class="modal fade" id="modal-id" data-keyboard="false" data-backdrop="static">
  <div class="modal-lg modal-dialog">
    <div class="modal-content">
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
        <form action="compra_submit" id="compra_submit"  name="compra_submit" method="get" autocomplete="off" accept-charset="utf-8">
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
              <i class="fa fa-money" aria-hidden="true"></i> Monto a Cobrar &nbsp;<span class="text-danger" id="m_total"></span>&nbsp;₲s.
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


<!-- ///////////////////////////////////////////FIN-MODAL/////////////////////////////////////////////////////// -->

<!-- modalSearch -->
<div id="modalSearch" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Búsqueda de Articulos</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <div class="input-group">
            <input type="text" id="campoSearch" class="form-control" placeholder="Buscar productos">
            <span class="input-group-btn">
              <button class="btn btn-outline-secondary" type="button">
                <i class="fa fa-search" aria-hidden="true"></i> 
              </button>
            </span>
          </div>
        </div>
        <div class="col-sm-2 col-md-2" style="padding: 20px;">
          <div  id="miThumbnail" class="thumbnail">
          <img class="img-thumbnail" src="" height="200">

          </div>
          <div class="caption text-center">
              <h4>Imagen</h4>
            </div>
        </div>
        <div class="col-sm-10 col-md-10">
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
<table class="table" id="tablaProductos" style="height: 50px; overflow-y: auto;">
  <thead>
    <tr>
      <th><i class="fa fa-hashtag"></i></th>
      <th><i class="fa fa-check-circle-o"></i> Existencia</th>
      <th><i class="fa fa-money"></i> Precio</th>
      <th><i class="fa fa-user"></i> Nombre</th>
      <th><i class="glyphicon glyphicon-barcode"></i> Codigo-Barra</th>
    </tr>
    <tr id="loadingContainer" style="display: none;">
      <td colspan="5">
        <div id="loadingMessage">
                      <div class="loading-spinner"></div>
                      <p>Cargando...</p>
                    </div>
      </td>
    </tr>
  </thead>
  <tbody class="todo-list">
    <!-- Contenido de la tabla -->
  </tbody>
</table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
        </div>





      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- FINICH modalSearch -->






















        </div>
      </div>
    </section>

  </div><!-- ./wrapper -->

