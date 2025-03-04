                            <div  style="overflow: auto; height: 100%">
                            <table id="principal" class="idRecorrer table" cellspacing="0" width="100%" >
                                        <thead>
                                          <tr>
                                                      <th class  ="text-danger">Cantidad</th>
                                                      <th   class ="text-danger">Nombre </th>
                                                      <th style="text-align:center"  class  ="text-danger">Precio </th>
                                                      <th style="text-align:right"  class  ="text-danger">Maximo </th>

                                                      <th style="text-align:right"   class ="text-danger">Devolucion <input type="checkbox" name="micheckbox" class="micheckbox" value=""></th>
                                                       <th style="text-align:left">  </th>
                                                      <th  style="text-align:center" class ="text-danger">Subtotal</th>
                                                      <th  style ="text-align:right"  class ="text-danger"style="width:125px;">Acciones</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                  <?php 
                                    $i = 1; 
                                    $totaldes = $iva10 = $iva5 = $grabadas5 = $grabadas10 = 0;
                                    $data = array();
          
                                  foreach ($this->cart->contents() as $items): ?>

                                          <?php echo form_hidden($i.'[rowid]', $items['rowid']); 
                                                if ($this->cart->has_options($items['rowid']) == TRUE):
                                                        $data = $this->cart->product_options($items['rowid']);

                                                 endif; 
                                                 $subtotales = $items['subtotal'];
                                                //  $iva = ($data['iva'] == 11) ? '10' : $data['iva'];
                                                 ?>
                                          <tr>
                                               <td colspan="1">
                                                  <?php echo  $items['qty']; ?>
                                                  </td>
                                                  <td>
                                                          <?php echo $items['name']; ?>
                                                  </td>
                                                    <td style="text-align:center" >
                                                         ₲. <?php echo number_format($items['price'],0,',','.'); ?>

                                                  </td>
                                                  <td style="text-align:right"   >
                                                    maximo:<?php echo $data['Cantidad_max_devol']; ?> 
                                                  </td>
                                                    <td style="text-align:right"   >
                                                   <?php 
                                                   if ($data['Cantidad_max_devol'] > 0) {
                                                    echo form_input(array(
                                                        'disabled' => 'disabled',
                                                        'name' => 'devolver',
                                                        'class' => 'qty valid devolver-input',
                                                        'type' => 'text',
                                                        'value' => '',
                                                        'data-id2' => $data['Cantidad_stock'],
                                                        'data-id' => $data['Cantidad_max_devol'],
                                                        'id' => $items['rowid'],
                                                        'placeholder' => '',
                                                        'data-monto' => $items['price'],
                                                        'data-iva' => $data['iva'],
                                                        'style' => "width:100px"
                                                    ));
                                                   } ?> 
                                                  </td>
                                                  <td style="text-align:left">Stock:<?php echo $data['Cantidad_stock'] ; ?>  Deposito:<?php echo $data['Cantidad_Deposito']; ?></td>
                                                  <td  style="text-align:center; " data-iva10="" data-iva5="" class="recorerr <?php echo $items['rowid']?>">
                                                  </td>
                                                  <td style="text-align:right" width="130">
                                                        <div class="pull-right hidden-phone">
                                                              <a class="btn btn-danger btn-xs fa fa-trash-o"  
                                                                      onclick="delete_rowid('<?php echo $items['rowid']?>')">
                                                              </a>
                                                         </div>
                                                  </td>
                                          </tr>
                                  <?php $i++; 

                                   endforeach; ?>
                             </tbody>
                            </table >
                            </div>
                            <div class="col-md-12 bg-navy">
    <div class="col-md-4">
        <label for=""  style="text-align:left">
              <strong>IVA 5 % &nbsp;</strong>₲. <span class='total_iva_cinco'>
                 <p></p>
              </span>
        </label>
    </div>
    <div class="col-md-4">
        <label for="" style="text-align:center">
              <strong>IVA 10 % &nbsp;</strong>₲. <span class='total_iva_diez'>
                 <p></p>
              </span>
        </label>
    </div>
   <div class="col-md-4">
    <label for=""  style="text-align:right">
    <strong>Monto Total &nbsp;</strong>₲. <span class='montoTotal'></span>
        <input type="hidden" name="total" id="total" value="">
    </label>
</div>

  </div >
  <div class="col-md-12 bg-navy">
      <div class="col-md-4">
        <label for=""  style="text-align:left">
           <strong>Descuento&nbsp;</strong>₲. <span class='Descuento'>
             <p></p>
           </span>
        </label>
      </div>
      <div class="col-md-4">
         <label for="" style="text-align:center">
              <strong>Total IVA &nbsp;</strong>₲. <span class='totalesiva'>
                
              
              </span>
        </label>
      </div>
   <div class="col-md-4 " >
        <label for=""  style="text-align:right">
              <strong>Monto Final &nbsp;</strong>₲. <span class='finales'></span>
        </label>
    </div>

  </div >
</div>

                           <!-- cierre de carrito -->
                    </div>
              </div>
          </div>
          <!-- ///////////////////////////////////////////// -->


</div>
        <input type="hidden" name="iva_cinco" id="iva_cinco"  class="form-control" value="">
      <input type="hidden" name="iva_diez" id="iva_diez"     class="form-control" value="">
      <input type="hidden" name="lesiva" id="lesiva"      class="form-control" value=""> 
      <input type="hidden" name="cartotal" id="cartotal" value="">
      <input type="hidden" name="finalcarrito" id="finalcarrito" class="finalcarrito" value="">
      <input type="hidden" class="" name="grabadas10" id="grabadas10" value="">
      <input type="hidden" class="" name="grabadas5" id="grabadas5" value="">
      </form>

<script type="text/javascript" charset="utf-8" async defer>
$(document).ready(function() {

    // Llamar al método totalfinal cada vez que se tipee en el input
    totalfinal();





    
});


</script>