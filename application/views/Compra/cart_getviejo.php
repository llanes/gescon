                            <div style="height : 400px; overflow : auto; ">
                            <table id="table" class="idRecorrer table" cellspacing="0" width="100%" >
                                        <thead>
                                          <tr>
                                                      <th class  ="text-danger">#</th>
                                                      <th class  ="text-danger">Cantidad</th>
                                                      <th class  ="text-danger">Nombre</th>
                                                      <th  class ="text-danger">Precio</th>
                                                       <th  style="text-align:right"   class ="text-danger">Iva</th>
                                                      <th  style ="text-align:right" class ="text-danger">Subtotal</th>
                                                      <th  style ="text-align:right"  class ="text-danger"style="width:125px;">Acciones</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                  <?php $i = 1; ?>
                                  <?php foreach ($this->cart->contents() as $items): ?>

                                          <?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>
                                          <tr>
                                                  <td colspan="1">
                                                  <?php echo  $i ?>
                                                  </td>
                                                  <td colspan="1">
                                                    <?php echo form_hidden(array('name' => $i.'[qty]','value' => $items['qty'],'maxlength' => '15', 'size' => '5')); ?>
                                                    <input 
                                                    type="number" 
                                                    min="1"
                                                     min="1" max="99999999999"
                                                    id="<?php echo $items['rowid']?>"
                                                    maxlength="15"
                                                    value="<?php echo $items['qty']?>"
                                                    onchange="update_rowid('<?php echo $items['rowid']?>');"
                                                    style="width:80px"
                                                    >
                                                  </td>
                                                  <td>
                                                          <?php echo $items['name']; ?>
                                                  </td>
                                                    <td >
                                                    <input 
                                                    type="number" 
                                                    min="1"
                                                     min="1" max="99999999999"
                                                    id="<?php echo $items['qty']?>"
                                                    class="<?php echo $items['rowid']?>"
                                                    maxlength="15"
                                                    value="<?php echo $items['price']; ?>"
                                                    onchange="update2_rowid('<?php echo $items['rowid']?>');"
                                                    style="width:80px"
                                                    >
                                                  </td>
                                                   <td id="td_id" style ="text-align:right">
                                                          <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>

                                                                  <p>
                                                                          <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>
                                                                                  <?php echo $option_value; ?>%
                                                                          <?php endforeach; ?>
                                                                  </p>

                                                          <?php endif; ?>
                                                  </td>
                                                  </td>
                                                  <td style="text-align:right" 
                                                      class='inpuesto' 
                                                      data-id="<?php echo $option_value;?>"
                                                      data-subtotal="<?php echo $items['subtotal']; ?>">₲. 
                                                      <?php echo $this->cart->format_number($items['subtotal']); ?>
                                                  </td>
                                                  <td style="text-align:right" width="130">
                                                        <div class="pull-right hidden-phone">
                                                              <a class="btn btn-danger btn-xs fa fa-trash-o"  
                                                                      onclick="delete_rowid('<?php echo $items['rowid']?>')">
                                                              </a>
                                                         </div>
                                                  </td>
                                          </tr>
                                  <?php $i++; ?>
                                  <?php endforeach; ?>
                             </tbody>
                            </table >
                            </div>
                            <div class="col-md-12 bg-gray color-palette">
                              <div class="col-md-4">
                                  <label for=""  style="text-align:left">
                                        <strong>Total IVA 5 &nbsp;</strong>₲. <span class='total_iva_cinco'></span>
                                  </label>
                              </div>
                              <div class="col-md-4">
                                  <label for="" style="text-align:center">
                                        <strong>Total IVA 10 &nbsp;</strong>₲. <span class='total_iva_diez'></span>
                                  </label>
                              </div>
                             <div class="col-md-4">
                                  <label for=""  style="text-align:right">
                                        <strong>Monto Total &nbsp;</strong>₲. <?php echo $this->cart->format_number($this->cart->total()); ?>
                                  </label>
                              </div>

                            </div >
                            <div class="col-md-12 bg-gray color-palette">
                                <div class="col-md-4">
                                  <label for=""  style="text-align:left">
                                     <strong>Descuento&nbsp;</strong>₲. <span class='Descuento'></span>
                                  </label>
                                </div>
                                <div class="col-md-4">
                                   <label for="" style="text-align:center">
                                        <strong>Final IVA &nbsp;</strong>₲. <span class='totalesiva'></span>
                                  </label>
                                </div>
                             <div class="col-md-4 " >
                                  <label for=""  style="text-align:right">
                                        <strong>Monto Final &nbsp;</strong>₲. <span class='finales'></span>
                                  </label>
                              </div>

                            </div >
                            <div class="col-md-12">
                            <br>  
                                                 <div class="pull-right">
                                                    <?php if ($this->cart->format_number($this->cart->total()) != '0') { ?>
                                                          <button type ="submit" name="add"  class="btn btn-sm btn-success">
                                                          <span class="fa fa-hand-rock-o" id="btnSave" >&nbsp;Procesar</span> </button>&nbsp;&nbsp;

                                                  <?php } ?>
                                                          <button type ="reset" onclick="Limpiar('1');" class="btn btn-sm btn-info">
                                                          <i class     ="fa fa-refresh "></i> Limpiar</button>&nbsp;
                                                          <button type ="button" class="btn btn-sm btn-danger" data-toggle="collapse" onclick="resetear()" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" >
                                                          <span class  ="glyphicon glyphicon-floppy-remove"></span> Cancelar</button>     
                                                  </div> 

                            </div>
                             <input type="hidden" name="iva_cinco" id="iva_cinco" value=''>
                            <input type="hidden" name="iva_diez" id="iva_diez"    value=''>
                            <input type="hidden" name="lesiva" id="lesiva"      value=''> 
                            <input type="hidden" name="cart_total" id="cart_total" value="<?php echo $this->cart->total(); ?>">
                             <input type="hidden" name="finalcarrito" id="finalcarrito" value="">
                           <!-- cierre de carrito -->
                    </div>
              </div>
          </div>
          <!-- ///////////////////////////////////////////// -->


</div>
      </form>

<script type="text/javascript" charset="utf-8" async defer>
                var fletes         = $('#fletes').val();
                var descuento      = $('#descuento').val();
                var cart_total     = $('#cart_total').val();
                var finalcarrito = $('#finalcarrito').val();
                var inpuesto_cinco  =0,iva  =0,inpuesto_diez  =0,total_cinco  =0,total_diez  =0,total_iva  =0,num1  =0,num2 =0;
  $(function() {
        if (fletes == '') {
          xx = formatNumber.new(cart_total);
          $('.finales').html(xx);
          $('#finalcarrito').val(cart_total);
          if (descuento !="") {
            finales = operaciones(cart_total,descuento,'-');
            $('.finales').html(formatNumber.new(finales));
            $('#finalcarrito').val(finales);
            $('.Descuento').html(formatNumber.new(descuento))
          }else{
            $('.finales').html(xx);
            $('#finalcarrito').val(cart_total);
            $('.Descuento').html(formatNumber.new(descuento))         
          }
        }else{
          resul = operaciones(cart_total,fletes,'+');
          $('.finales').html(formatNumber.new(resul));
          $('#finalcarrito').val(resul);
            if (descuento !="") {
            final = operaciones(resul,descuento,'-');
            $('.finales').html(formatNumber.new(final));
            $('#finalcarrito').val(final);
            $('.Descuento').html(formatNumber.new(descuento))
          }else{
            $('.finales').html(formatNumber.new(resul));
            $('#finalcarrito').val(resul);
            $('.Descuento').html(formatNumber.new(descuento))         
          }
        }

        // alert(iva);
        $('.idRecorrer td.inpuesto').each(function(){ //filas con clase 'contenido_caja', especifica una clase, asi no tomas el nombre de las columnas
         iva = $(this).attr('data-id');
         if (iva == '') {

         } else {
                if (iva == 5) {
                 inpuesto_cinco += parseFloat($(this).attr('data-subtotal').replace(',', ','));
                 num1 = inpuesto_cinco/21;
                 total_cinco = num1.toFixed(3);
                 $(".total_iva_cinco").html(total_cinco);
                 $("#iva_cinco").val(total_cinco);
               }
               if (iva == 10) {
                 inpuesto_diez += parseFloat($(this).attr('data-subtotal').replace(',', ','));
                 // alert(inpuesto_diez);
                 num2 = inpuesto_diez/11;
                 total_diez = num2.toFixed(3);
                 $(".total_iva_diez").html(total_diez);
                 $("#iva_diez").val(total_diez);
               }
                var num3 = num1 + num2;
                total_iva = num3.toFixed(3);
                $(".totalesiva").html(total_iva);
                $("#lesiva").val(total_iva);

         }
        });
});
$(function() {
  $('#fletes').keyup(function(event) {
        var des          = $('#descuento').val();
        var fle          = $(this).val();
        var carttotal    = $('#cart_total').val();
        var finalcarrito = $('#finalcarrito').val();
        if (fle == '') {
          xx = formatNumber.new(carttotal);
          $('.finales').html(xx);
          $('#finalcarrito').val(carttotal);
          if (des !="") {
            xx = operaciones(carttotal,des,'-');
            xxx = formatNumber.new(xx);
            $('.finales').html(xxx);
            $('#finalcarrito').val(xx);
            $('.Descuento').html(formatNumber.new(des))
          }else{
          $('.finales').html(xx);
          $('#finalcarrito').val(carttotal);
            $('.Descuento').html(formatNumber.new(des))
          }
        }else{
          xx = operaciones(carttotal,fle,'+');
          xxx = formatNumber.new(xx);
          $('.finales').html(xxx);
          $('#finalcarrito').val(xx);
            if (des !="") {
            final = operaciones(xx,des,'-');
            finales = formatNumber.new(final);
            $('.finales').html(finales);
            $('#finalcarrito').val(final);
            $('.Descuento').html(formatNumber.new(des))
          }else{
            $('.finales').html(xxx);
            $('#finalcarrito').val(xx);
            $('.Descuento').html(formatNumber.new(des))
          }
        }
  });
  $('#descuento').keyup(function(event) {
        var des      = $(this).val();
        var fle      = $('#fletes').val();
        var cartotal = $('#cart_total').val();
        var finalcarrito = $('#finalcarrito').val();
        if (fle == '') {
          xx = formatNumber.new(cartotal);
          $('.finales').html(xx);
          $('#finalcarrito').val(cartotal);
          if (des !="") {
            xx = operaciones(cartotal,des,'-');
            xxx = formatNumber.new(xx);
            $('.finales').html(xxx);
            $('#finalcarrito').val(xx);
            $('.Descuento').html(formatNumber.new(des))
          }else{
            $('.finales').html(xx);
            $('#finalcarrito').val(cartotal);
            $('.Descuento').html(formatNumber.new(des))
          }
        }else{
          xx = operaciones(cartotal,fle,'+');
          xxx = formatNumber.new(xx);
          $('.finales').html(xxx);
          $('#finalcarrito').val(xx);
            if (des !="") {
            final = operaciones(xx,des,'-');
            $('.finales').html(formatNumber.new(final));
            $('#finalcarrito').val(final);
            $('.Descuento').html(formatNumber.new(des))
          }else{
            $('.finales').html(xxx);
            $('#finalcarrito').val(xx);
            $('.Descuento').html(formatNumber.new(des))
          }
        }
  });
});

</script>


<div class='content-detalle' style=" overflow : auto; ">
  <table id="table" class="idRecorrer table table-striped" cellspacing="0" width="100%" >
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
        <label for="total_iva_cinco"  style="text-align:left">
              <strong>IVA 5 % &nbsp;</strong>₲. <span class='total_iva_cinco'>
                 <?php echo number_format($iva5) ?>
              </span>
        </label>
    </div>
    <div class="col-md-4">
        <label for="total_iva_diez" style="text-align:center">
              <strong>IVA 10 % &nbsp;</strong>₲. <span class='total_iva_diez'>
                 <?php echo number_format($iva10) ?>
              </span>
        </label>
    </div>
   <div class="col-md-4">
        <label for="total_monto"  style="text-align:right">
              <strong>Monto Total &nbsp;</strong>₲. <?php echo number_format($this->cart->total()); ?>
        </label>
    </div>

  </div >
  <div class="col-md-12 bg-navy">
      <div class="col-md-4">
        <label for="Descuento"  style="text-align:left">
           <strong>Descuento&nbsp;</strong>₲. <span class='Descuento'>
             <?php echo number_format($totaldes) ?>
           </span>
        </label>
      </div>
      <div class="col-md-4">
         <label for="totalesiva" style="text-align:center">
              <strong>Total IVA &nbsp;</strong>₲. <span class='totalesiva'>
                <?php echo number_format($iva10 + $iva5) ?>
              </span>
        </label>
      </div>
   <div class="col-md-4 " >
        <label for="finales"  style="text-align:right">
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
        // $(document).ready(function() {
        $.AdminLTE.layout.fix();
        totalfinal();

        $(document).on('change keyup', '.cantidad', function(event) {
            var j = $(this);
            update_rowid(j.val(), j.attr('id'));
        });

        $(document).on('change keyup', '[name="dataprice"]', function(event) {
            var $price = $(this).val();
            var $rowid = $(this).data('id');
            var $qty = $(this).data('qty');
            var $iva = $(this).data('iva');
            var $tyf = $(this).data('tyf');
            update2_rowid($price, $rowid, $qty, $iva, $tyf);
        });

        $(document).on('change', '[name="descuento"]', function(event) {
            var $val = $(this).val();
            var $id = $(this).data('id');
            var $qty = $(this).data('qty');
            var $price = $(this).data('price');
            var $i = $(this).data('i');
            update_descuento($val, $id, $qty, $price,$i);
        });

        $('.idRecorrer td.selec select.descuento').each(function(index, el) {
            $(this).val($(this).data('tyf'));
        });

        $(document).on('click', 'div.pull-right a.deleterow', function(event) {
            var rowid = $(this).data('id');
            deleterowid(rowid);
        });
    // });
</script>