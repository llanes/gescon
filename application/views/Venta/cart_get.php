<div class='content-detalle' style="overflow: auto; height: 100%">
  <table id="table" class="idRecorrer  table-striped" cellspacing="0" width="100%" >
                                        <thead>
                                        <tr class="danger">
                                                    <th class  ="">#</th>
                                                      <th >Stock</th>
                                                      <th >Unidad Medida</th>
                                                      <th >Descripcion</th>
                                                      <th >Precio</th>
                                                      <th style="text-align:right">Rebaja</th>
                                                    <th  style="text-align:right">Impuesto</th>
                                                      <th  style ="text-align:right" >Subtotal</th>
                                                      <th  style ="text-align:right"  style="width:125px;">Acciones</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                  <?php 
                                            $i = 1; 
                                            $totaldes = $iva10 = $iva5 = 0;
                                            $data = array();
                                           
                                  foreach ($this->cart->contents() as $items): 
                                    $option = '';$selected = '';
                                    
                                    if ($this->cart->has_options($items['rowid']) == TRUE) {
                                        $data = $this->cart->product_options($items['rowid']);
                                    }
                                    $subtotales = $items['subtotal']- $data['descuento'];
                                     $iva = ($data['iva'] == 11) ? '10' : $data['iva'];
                                     if($items['descuento'] > 0): 
                                        if($data['descuento'] > 0): 
                                          $selected = 'selected';
                                        endif;
                                        $option = '
                                        <select 
                                        name="descuento" 
                                        data-tyf="'.$data['t_f'].'"  
                                        id="select'.$items['rowid'].'" 
                                        data-id="'.$items['rowid'].'" 
                                        data-qty="'.$items['qty'].'" 
                                        data-price="'.$data['poriginal'].'" 
                                        data-i="'.$data['iva'].'" 
                                        data-stock="'.$data['Cantidad_max'].'" 
                                        data-descuento="'.$items['descuento'].'" 
                                        data-idcode="'.$data['id'].'" 



                                        class="descuento"
                                        >
                                        <option value="0">select</option>
                                        <option '.$selected.' value="0.0'.$items['descuento'].'">'.$items['descuento'].' %</option>
                                        </select> 
                                        <span id="val" class="danger">'.number_format($data['descuento']).'</span>                         
                                        ';
                                     endif;
                                     echo form_hidden($i.'[rowid]', $items['rowid']).'
                                     <tr class="uno">
                                       <td colspan="1">
                                          '.$i.' 
                                       </td>
                                       <td colspan="1">
                                          '.$data['Cantidad_max'].' '.$data['Medida'].' 
                                       </td>
                                       <td colspan="1">
                                         '.form_hidden(array('name' => $i.'[qty]','value' => $items['qty'],'maxlength' => '15', 'size' => '5')).'
                                         <input required type="text" 
                                          min="1" 
                                          id="'.$items['rowid'].'" 
                                          data-id ="'.$data['id'].'"
                                          maxlength="6" 
                                          max="'.$data['Cantidad_max'].'" 
                                          data-id2 ="'.$data['Cantidad_max'].'"
                                          data-Medida ="'.$data['Medida'].'"

                                          value="'.$items['qty'].'" 
                                          style="width:80px" 
                                          class="cantidad valid">
                                       </td>


                                       <td>
                                         '.$items['name'].'
                                       </td>
                                       <td >
                                         '.$items['price'].'
                                       </td>
                                       <td class="selec" style="text-align:right">
                                       '.$option.'
                                       
                                       </td>
                                       <td style="text-align:right" colspan="" rowspan="" headers="">'.$data['iva'].' %</td>
                                       <td style="text-align:right" 
                                       class="inpuesto" 
                                       data-id="'.$data['iva'].'"
                                       data-subtotal="'.$subtotales.'">₲. '.number_format($subtotales).'
                                     </td>
                                     <td style="text-align:right" width="130">
                                       <div class="pull-right hidden-phone">
                                         <a class="btn btn-danger btn-xs fa fa-trash-o deleterow" data-id="'.$items['rowid'].'" id="deleterow">
                                          </a>
                                       </div>
                                     </td>
                                 </tr>';
                                  $i++;
                                  if ($data['iva'] == 11) {
                                   $iva10 += ($subtotales / 11);
                                    
                                  }
                                  if ($data['iva'] == 5) {
                                   $iva5 += ($subtotales / 21);
                                    
                                  }
                                  $totaldes += $data['descuento'];
                                endforeach; 
                                $totaldes = round($totaldes);
                                     ?>
                            </tbody>
                            </table >
                            </div>






 
                              <input type ="hidden" name="descuentototal" id="descuentototal" value="<?=$totaldes?>">
                              <input type ="hidden" name="finaldescuento" id="finaldescuento" value="<?=$totaldes?>">

                              <input type="hidden" name="iva_cinco" id="iva_cinco"  class="form-control" value=" <?= $iva5 ?>">
      <input type="hidden" name="iva_diez" id="iva_diez"     class="form-control" value=" <?= $iva10 ?>">
      <input type="hidden" name="lesiva" id="lesiva"      class="form-control" value=" <?= $iva10 + $iva5 ?>"> 
      <input type="hidden" name="cartotal" id="cartotal" value="<?= round($this->cart->total()-$totaldes) ?>">
      <input type="hidden" name="finalcarrito" id="finalcarrito" class="finalcarrito" value="">


                           <!-- cierre de carrito -->
                    </div>
              </div>
          </div>
          <!-- ///////////////////////////////////////////// -->


</div>
<div class="footer">
 <div class="col-md-12 bg-navy">
    <div class="col-md-4">
        <label  style="text-align:left">
              <strong>IVA 5 % &nbsp;</strong>₲. <span class='total_iva_cinco'>
                 <?php echo number_format($iva5) ?>
              </span>
        </label>
    </div>
    <div class="col-md-4">
        <label style="text-align:center">
              <strong>IVA 10 % &nbsp;</strong>₲. <span class='total_iva_diez'>
                 <?php echo number_format($iva10) ?>
              </span>
        </label>
    </div>
   <div class="col-md-4">
        <label  style="text-align:right">
              <strong>Monto Total &nbsp;</strong>₲. <?php echo number_format($this->cart->total()-$totaldes); ?>
        </label>
    </div>

  </div >
  <div class="col-md-12 bg-navy">
      <div class="col-md-4">
        <label  style="text-align:left">
           <strong>Descuento&nbsp;</strong>₲. <span class='Descuento'>
             <?php echo number_format($totaldes) ?>
           </span>
        </label>
      </div>
      <div class="col-md-4">
         <label style="text-align:center">
              <strong>Total IVA &nbsp;</strong>₲. <span class='totalesiva'>
                <?php echo number_format($iva10 + $iva5) ?>
              </span>
        </label>
      </div>
   <div class="col-md-4 " >
        <label  style="text-align:right">
              <strong>Monto Final &nbsp;</strong>₲. <span class='finales'></span>
        </label>
    </div>

  </div >
</div>
      </form>

<script type="text/javascript" charset="utf-8" async defer>
  
  totalfinal();
</script>