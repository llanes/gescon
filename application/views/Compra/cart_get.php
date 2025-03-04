<div class='content-detalle' style="overflow: auto; height: 431px;">
  <table id="table" class="idRecorrer  table-striped" cellspacing="0" width="100%" >
    <thead>
      <tr class="danger">
        <th class  ="">#</th>
        <th class  ="">Cantidad</th>
        <th class  ="">Nombre</th>
        <th  class ="">Precio</th>
        <th  class ="">Descuento</th>
        <th  style="text-align:right">Impuesto</th>

        <th style  ="text-align:right" class ="">Subtotal</th>
        <th  style ="text-align:right"  class =""style="width:100px;">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php 
          $i = 1; 
          $totaldes = $iva10 = $iva5 = 0;
          $data = array();
          
         foreach ($this->cart->contents() as $items): 
              if ($this->cart->has_options($items['rowid']) == TRUE) {
                  $data = $this->cart->product_options($items['rowid']);
              }
              $iva = ($data['iva'] == 11) ? '10' : $data['iva'];

         echo form_hidden($i.'[rowid]', $items['rowid']).'
        <tr class="uno">
          <td colspan="1">
             '.$i.' 
          </td>
          <td colspan="1">
            '.form_hidden(array('name' => $i.'[qty]','value' => $items['qty'],'maxlength' => '15', 'size' => '5')).'
            <input type="number" min="1" min="1" max="99999999999" id="'.$items['rowid'].'" maxlength="15" value="'.$items['qty'].'" style="width:80px" class="cantidad">
          </td>
          <td>
            '.$items['name'].'
          </td>
          <td >
            <input type="number" min="1" min="1" max="99999999999"  data-id="'.$items['rowid'].'" maxlength="15" value="'.$data['poriginal'].'"  style="width:80px" name="dataprice" data-tyf="'.$data['t_f'].'" data-qty="'.$items['qty'].'" data-iva="'.$data['iva'].'" >
          </td>
          <td class="selec">
          <select name="descuento" data-tyf="'.$data['t_f'].'"  id="select'.$items['rowid'].'" data-id="'.$items['rowid'].'" data-qty="'.$items['qty'].'" data-price="'.$data['poriginal'].'" data-i="'.$data['iva'].'" class="descuento">
          <option value="0">select</option>
          <option value="0.02">2 %</option>
          <option value="0.05">5 %</option>
          <option value="0.10">10 %</option>
          <option value="0.15">15 %</option>
          <option value="0.20">20 %</option>
          <option value="0.25">25 %</option>
          <option value="0.30">30 %</option>
          <option value="0.35">35 %</option>
          <option value="0.40">40 %</option>
          <option value="0.45">45 %</option>
          <option value="0.50">50 %</option>
          </select>
          <span id="val" class="danger">'.number_format($data['descuento']).'</span>
          </td>
          <td style="text-align:right" colspan="" rowspan="" headers="">'.$iva.' %</td>
          <td style="text-align:right" 
          class="inpuesto" 
          data-id="'.$data['iva'].'"
          data-subtotal="'.$items['subtotal'].'">₲. '.number_format($items['subtotal']).'
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
      $iva10 += ($items['subtotal'] / 11);
       
     }
     if ($data['iva'] == 5) {
      $iva5 += ($items['subtotal'] / 21);
       
     }
     $totaldes += $data['descuento'];
   endforeach; ?>
</tbody>
</table >
</div>
<div class="">
 <div class="col-md-12 bg-navy">
    <div class="col-md-4">
        <label for=""  style="text-align:left">
              <strong>IVA 5 % &nbsp;</strong>₲. <span class='total_iva_cinco'>
                 <?php echo number_format($iva5) ?>
              </span>
        </label>
    </div>
    <div class="col-md-4">
        <label for="" style="text-align:center">
              <strong>IVA 10 % &nbsp;</strong>₲. <span class='total_iva_diez'>
                 <?php echo number_format($iva10) ?>
              </span>
        </label>
    </div>
   <div class="col-md-4">
        <label for=""  style="text-align:right">
              <strong>Monto Total &nbsp;</strong>₲. <?php echo number_format($this->cart->total()); ?>
        </label>
    </div>

  </div >
  <div class="col-md-12 bg-navy">
      <div class="col-md-4">
        <label for=""  style="text-align:left">
           <strong>Descuento&nbsp;</strong>₲. <span class='Descuento'>
             <?php echo number_format($totaldes) ?>
           </span>
        </label>
      </div>
      <div class="col-md-4">
         <label for="" style="text-align:center">
              <strong>Total IVA &nbsp;</strong>₲. <span class='totalesiva'>
                <?php echo number_format($iva10 + $iva5) ?>
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



      <input type="hidden" name="iva_cinco" id="iva_cinco"  class="form-control" value=" <?= $iva5 ?>">
      <input type="hidden" name="iva_diez" id="iva_diez"     class="form-control" value=" <?= $iva10 ?>">
      <input type="hidden" name="lesiva" id="lesiva"      class="form-control" value=" <?= $iva10 + $iva5 ?>"> 
      <input type="hidden" name="cartotal" id="cartotal" value="<?= $this->cart->total() ?>">
      <input type="hidden" name="finalcarrito" id="finalcarrito" class="finalcarrito" value="">



  </div>
</div>
<!-- ///////////////////////////////////////////// -->


</div>
</form>
<script type="text/javascript" charset="utf-8" async defer>
        totalfinal();
</script>